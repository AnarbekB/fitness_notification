<?php

namespace App\Object;

use Symfony\Component\Form\FormInterface;

class PasswordObject
{
    /** @var  FormInterface */
    public $form;

    /** @var  bool */
    public $success;

    /**
     * @param FormInterface $form
     * @param bool $success
     * @return PasswordObject
     */
    public static function fromChangePassword(FormInterface $form, bool $success)
    {
        $object = new self();

        $object->form = $form;
        $object->success = $success;

        return $object;
    }
}
