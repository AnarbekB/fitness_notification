<?php

namespace App\Manager;

use App\Object\PasswordObject;
use Symfony\Component\HttpFoundation\Request;

interface SecurityManagerInterface
{
    public function changePasswordByResetGuid(string $guid, Request $request) :PasswordObject;
}
