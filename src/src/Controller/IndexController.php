<?php
/**
 * Created by PhpStorm.
 * User: anarbek
 * Date: 21.07.18
 * Time: 12:31
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
    public function index()
    {
        return new Response('test');
    }
}
