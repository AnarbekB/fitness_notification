<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TypeMenuAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list)
    {
        $list->add('id', IntegerType::class, [
            'label' => 'Id'
        ]);
        $list->add('name', TextType::class, [
            'label' => 'Имя'
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

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('name', TextType::class, [
            'label' => 'Имя'
        ]);
        $form->add('code', TextType::class, [
            'label' => 'Код'
        ]);
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('id', IntegerType::class, [
            'label' => 'Id'
        ]);
        $show->add('name', TextType::class, [
            'label' => 'Имя'
        ]);
        $show->add('code', TextType::class, [
            'label' => 'Код'
        ]);
        $show->add('menu', CollectionType::class, [
            'label' => 'Меню'
        ]);
    }
}
