<?php

namespace App\Admin;

use App\Constants\Gender;
use App\Constants\NotifyTemplate;
use App\Entity\User;
use App\Notification\Channel\SmsNotification;
use App\Notification\Template\RegistrationSuccess;
use App\Notification\TestNotification;
use App\Service\NotifyService;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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

    public function __construct(
        $code,
        $class,
        $baseControllerName,
        ContainerInterface $container
    ) {
        parent::__construct($code, $class, $baseControllerName);
        $this->container = $container;
        $this->notifyService = $this->container->get('notify_service');
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    protected function configureFormFields(FormMapper $form)
    {
        /** @var User $user */
        $user = $this->getSubject();

        $imagePath = null;
        $imageEmbed = '';
        if ($user->getProfilePhoto()) {
            $imagePath = $user->getWebPath();
            $imageEmbed = '<img src="/'.$imagePath.'" class="admin-preview" />';
        }

        $form->add('username', TextType::class);
        $form->add('firstName', TextType::class);
        $form->add('middleName', TextType::class);
        $form->add('lastName', TextType::class);
        $form->add('password', PasswordType::class);
        $form->add('gender', ChoiceType::class, [
            'choices'  => [
                Gender::MAN => Gender::MAN(),
                Gender::WOMAN => Gender::WOMAN()
            ],
        ]);
        $form->add('dateOfBirth', BirthdayType::class);
        $form->add('email', EmailType::class);
        $form->add('phone', TelType::class);
        $form->add('file', FileType::class, [
            'required' => false,
            'data_class' => null,
            'help' => $imageEmbed,
        ]);
    }

    protected function configureShowFields(ShowMapper $show)
    {
        /** @var User $user */
        $user = $this->getSubject();

        $imagePath = null;
        $imageEmbed = '';
        if ($user->getProfilePhoto()) {
            $imagePath = $user->getWebPath();
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
            $link = $this->container->get('router')->generate(
                'reset_password',
                ['slug' => $user->getPasswordResetGuid()]
            );
            $notificationTemplate = new RegistrationSuccess($user, $link);
            $this->notifyService->notify($user, $notificationTemplate);
        }
    }

    public function saveFile(User $user)
    {
        $basePath = $this->getRequest()->getBasePath();
        $user->upload($basePath);
    }
}
