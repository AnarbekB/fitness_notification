<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MenuAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $list)
    {
        $list->add('id', IntegerType::class, [
            'label' => 'Id'
        ]);
        $list->add('name', TextType::class, [
            'label' => 'Имя'
        ]);
        $list->add('route', TextType::class, [
            'label' => 'Ссылка'
        ]);
        $list->add('static', 'boolean', [
            'label' => 'Статичное'
        ]);
        $list->add('typeMenu', ModelType::class, [
            'label' => 'Тип меню',
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
        $form->add('name');
        $form->add('route');
        $form->add('static');
        $form->add('typeMenu');
        $form->add('parent');
        $form->add('children');
    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show->add('id', IntegerType::class, [
            'label' => 'Id'
        ]);
        $show->add('name', TextType::class, [
            'label' => 'Имя'
        ]);
        $show->add('route', TextType::class, [
            'label' => 'Ссылка'
        ]);
        $show->add('static', 'boolean', [
            'label' => 'Статичное'
        ]);
        $show->add('typeMenu', ModelType::class, [
            'label' => 'Тип меню',
            'route' => ['name' => 'show']
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('name', null, [
            'label' => 'Имя'
        ]);
    }
}
