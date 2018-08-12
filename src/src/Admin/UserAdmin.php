<?php

namespace App\Admin;

use App\Constants\Gender;
use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('username', TextType::class);
        $form->add('firstName', TextType::class);
        $form->add('middleName', TextType::class);
        $form->add('lastName', TextType::class);
        $form->add('password', PasswordType::class);
        $form->add('gender', ChoiceType::class, [
            'choices'  => array(
                Gender::MAN => Gender::MAN(),
                Gender::WOMAN => Gender::WOMAN()
            ),
        ]);
        $form->add('dateOfBirth', BirthdayType::class);
        $form->add('email', EmailType::class);
        $form->add('phone', TelType::class);
        $form->add('file', FileType::class, array('required' => false));
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('id');
        $filter->add('firstName');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->add('id');
        $list->add('firstName');
    }

    public function prePersist($user)
    {
        if ($user instanceof User) {
            $this->saveFile($user);
        }
    }

    public function preUpdate($user)
    {
        if ($user instanceof User) {
            $this->saveFile($user);
        }
    }

    public function saveFile(User $user)
    {
        $basePath = $this->getRequest()->getBasePath();
        $user->upload($basePath);
    }
}
