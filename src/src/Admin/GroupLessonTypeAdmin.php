<?php

namespace App\Admin;

use App\Entity\GroupLessonType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GroupLessonTypeAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list)
    {
        $list->add('id', IntegerType::class, [
            'label' => 'Id'
        ]);
        $list->add('name', TextType::class, [
            'label' => 'Название'
        ]);
        $list->add('code', TextType::class, [
            'label' => 'Код'
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

    protected function configureShowFields(ShowMapper $show)
    {
        $show->with('Основное', ['class' => 'col-sm-9']);
            $show->add('id', IntegerType::class, [
                'label' => 'Id'
            ]);
            $show->add('name', TextType::class, [
                'label' => 'Имя'
            ]);
            $show->add('code', TextType::class, [
                'label' => 'Код'
            ]);
            $show->add('comment', TextType::class, [
                'label' => 'Комментарий'
            ]);
        $show->end();
        $show->with('Дополнительно', ['class' => 'col-sm-3']);
        $show->end();
        $show->with('Пользователи', ['class' => 'col-sm-12']);
            $show->add('users', CollectionType::class, [
                'label' => false
            ]);
        $show->end();
        $show->with('Занятия', ['class' => 'col-sm-12']);
            $show->add('lessons', CollectionType::class, [
                'label' => false
            ]);
        $show->end();
    }

    protected function configureFormFields(FormMapper $form)
    {
        /** @var GroupLessonType $groupLessonType */
        $groupLessonType = $this->getSubject();

        $imagePath = null;
        $imageEmbed = '';
        if ($groupLessonType->getImage()) {
            $imagePath = $groupLessonType->getWebPath();
            $imageEmbed = '<img src="/'.$imagePath.'" class="admin-preview" />';
        }

        $form->add('name', TextType::class, [
            'label' => 'Название'
        ]);
        $form->add('code', TextType::class, [
            'label' => 'Код'
        ]);
        $form->add('comment', TextareaType::class, [
            'label' => 'Комментарий'
        ]);
        $form->add('file', FileType::class, [
            'label' => 'Изображение',
            'required' => true,
            'data_class' => null,
            'help' => $imageEmbed,
        ]);
    }

    public function prePersist($lessonType)
    {
        if ($lessonType instanceof GroupLessonType) {
            $this->saveFile($lessonType);
        }
    }

    public function preUpdate($lessonType)
    {
        if ($lessonType instanceof GroupLessonType) {
            $this->saveFile($lessonType);
        }
    }


    public function saveFile(GroupLessonType $lessonType)
    {
        $basePath = $this->getRequest()->getBasePath();
        $lessonType->upload($basePath);
    }
}
