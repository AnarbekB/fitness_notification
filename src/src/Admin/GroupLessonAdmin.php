<?php

namespace App\Admin;

use App\Constants\ChannelNotification;
use App\Entity\GroupLesson;
use App\Entity\GroupLessonType;
use App\Entity\User;
use App\Notification\Template\CreateLesson;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

class GroupLessonAdmin extends AbstractAdmin
{
    /** @var  Producer */
    protected $producerMQ;

    public function __construct($code, $class, $baseControllerName, Producer $producer)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->producerMQ = $producer;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->add('id', IntegerType::class, [
            'label' => 'Id'
        ]);
        $list->add('name', TextType::class, [
            'label' => 'Название'
        ]);
        $list->add('date', 'datetime', [
            'label' => 'Дата',
            'format' => 'Y-m-d H:i',
        ]);
        $list->add('trainer', EntityType::class, [
            'label' => 'Тренер',
            'route' => ['name' => 'show']
        ]);
        $list->add('lessonType', EntityType::class, [
            'label' => 'Тип занятия',
            'class' => GroupLessonType::class,
            'associated_property' => 'name',
            'route' => ['name' => 'show']
        ]);
        $list->add('_action', null, [
            'label' => 'Действия',
            'actions' => [
                'show' => [],
                'edit' => [],
                'delete' => []
            ]
        ]);
    }

    protected function configureFormFields(FormMapper $form)
    {

        $form->with('Основное', ['class' => 'col-sm-9']);
            $form->add('name', TextType::class, [
                'label' => 'Название'
            ]);
            $form->add('date', DateTimeType::class, [
                'label' => 'Дата'
            ]);
            $form->add('comment', TextareaType::class, [
                'label' => 'Комментарий'
            ]);
            $form->add('trainer', EntityType::class, [
                'label' => 'Тренер',
                'class' => User::class
            ]);
        $form->end();
        $form->with('Дополнительно', ['class' => 'col-sm-3']);
            $form->add('active', BooleanType::class, [
                'label' => 'Активность'
            ]);
            $form->add('lessonType', EntityType::class, [
                'label' => 'Тип занятия',
                'class' => GroupLessonType::class,
                'choice_label' => 'name',
                'required' => true
            ]);
        $form->end();
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->with('Основное', ['class' => 'col-sm-9']);
            $show->add('id', IntegerType::class, [
                'label' => 'Id'
            ]);
            $show->add('name', TextType::class, [
                'label' => 'Название'
            ]);
            $show->add('date', 'datetime', [
                'label' => 'Дата',
                'format' => 'Y-m-d H:i',
            ]);
            $show->add('comment', TextType::class, [
                'label' => 'Комметарий'
            ]);
            $show->add('trainer', EntityType::class, [
                'label' => 'Тренер',
                'route' => ['name' => 'show']
            ]);
        $show->end();
        $show->with('Дополнительно', ['class' => 'col-sm-3']);
            $show->add('active', 'boolean', [
                'label' => 'Активность'
            ]);
            $show->add('lessonType', null, [
                'label' => 'Тип занятия',
                'associated_property' => 'name',
            ]);
        $show->end();
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('id', null, ['label' => 'Id'], IntegerType::class);
        $filter->add('name', null, ['label' => 'Название'], TextType::class);
        //todo add filter lessonType
    }

    /**
     * @param GroupLesson $object
     */
    public function postPersist($object)
    {
        if ($object instanceof GroupLesson) {
            $users = $object->getLessonType()->getUsers();
            $template = null;
            /** @var User $user */
            foreach ($users as $user) {
                if ($user->getChannelNotification() != ChannelNotification::NOTHING()->getValue()) {
                    $template = new CreateLesson($user, $object);
                    $this->producerMQ->publish(serialize($template));
                }
            }
        }
    }
}
