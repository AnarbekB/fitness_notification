<?php

namespace App\Admin;

use App\Constants\Gender;
use App\Entity\User;
use App\Notification\Template\RegistrationSuccess;
use App\Service\NotifyService;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\Producer;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserAdmin extends AbstractAdmin
{
    /** @var ContainerInterface */
    protected $container;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var NotifyService */
    protected $notifyService;

    /** @var  Producer */
    protected $producerMQ;

    /** @var UploadService */
    protected $uploadService;

    public function __construct(
        $code,
        $class,
        $baseControllerName,
        ContainerInterface $container,
        Producer $producer,
        UploadService $uploadService
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
        $this->notifyService = $this->container->get('notify_service');
        $this->em = $this->container->get('doctrine.orm.entity_manager');
        $this->producerMQ = $producer;
        $this->uploadService = $uploadService;
    }

    protected function configureFormFields(FormMapper $form)
    {
        /** @var User $user */
        $user = $this->getSubject();

        $imagePath = null;
        $imageEmbed = '';
        if ($user->getProfilePhoto()) {
            $imageEmbed = '<img src="/'.$user->getProfilePhoto().'" class="admin-preview" />';
        }

        $form->with('Основное', ['class' => 'col-sm-9']);
            $form->add('firstName', TextType::class, ['label' => 'Имя']);
            $form->add('lastName', TextType::class, ['label' => 'Фамилия']);
            $form->add('middleName', TextType::class, ['label' => 'Отчество']);
            $form->add('email', EmailType::class, ['label' => 'Email', 'disabled' => true]);
            $form->add('phone', TelType::class, ['label' => 'Телефон']);
        $form->end();
        $form->with('Дополнительное', ['class' => 'col-sm-3']);
            $form->add('enabled', BooleanType::class, [
                'label' => 'Активен',
                'choices' => [
                    'Да' => 1,
                    'Нет' => 0
                ]
            ]);
            $form->add('gender', ChoiceType::class, [
                'label' => 'Пол',
                'choices'  => [
                    Gender::MAN => Gender::MAN(),
                    Gender::WOMAN => Gender::WOMAN()
                ],
            ]);
            $form->add('dateOfBirth', BirthdayType::class, ['label' => 'Дата рождения']);
            $form->add('file', FileType::class, [
                'label' => 'Изображение профиля',
                'required' => false,
                'data_class' => null,
                'help' => $imageEmbed,
            ]);
        $form->end();
    }

    protected function configureShowFields(ShowMapper $show)
    {
        /** @var User $user */
        $user = $this->getSubject();

        $imagePath = null;
        $imageEmbed = '';
        if ($user->getProfilePhoto()) {
            $imageEmbed = '<img src="/'.$imagePath.'" class="admin-preview" />';
        }

        $show->with('Основное', ['class' => 'col-sm-9']);
            $show->add('username');
            $show->add('firstName');
            $show->add('middleName');
            $show->add('lastName');
            $show->add('email');
            $show->add('phone');
        $show->end();
        $show->with('Дополнительно', ['class' => 'col-sm-3']);
            $show->add('enabled', null, [
                'label' => 'Активен'
            ]);
            $show->add('gender');
            $show->add('dateOfBirth');
            $show->add('profilePhoto', null, [ //todo view image in detail
                'html' => $imageEmbed,
            ]);
        $show->end();
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('id');
        $filter->add('firstName');
        $filter->add('middleName');
        $filter->add('lastName');
        $filter->add('gender');
        $filter->add('email');
        $filter->add('phone');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->add('id');
        $list->add('firstName');
        $list->add('middleName');
        $list->add('lastName');
        $list->add('gender');
        $list->add('email');
        $list->add('phone');
        $list->add('enabled');
        $list->add('_action', null, [
           'actions' => [
               'show' => [],
               'edit' => [],
               'delete' => []
           ]
        ]);
    }

    public function prePersist($user)
    {
        if ($user instanceof User) {
            $user->setPasswordResetGuid(guid());
            $user->setUsername($user->getEmail());
            $user->setPassword(guid());
            $this->em->flush();
            $this->saveFile($user);
        }
    }

    public function preUpdate($user)
    {
        if ($user instanceof User) {
            $this->saveFile($user);
        }
    }

    public function postPersist($user)
    {
        if ($user instanceof User) {
            $notificationTemplate = new RegistrationSuccess($user);
            $this->producerMQ->publish(serialize($notificationTemplate));
        }
    }

    public function saveFile(User $user)
    {
        $user->setProfilePhoto($this->uploadService->upload($this->classnameLabel, $user->getFile()));
    }
}
