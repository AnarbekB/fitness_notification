<?php
/**
 * Created by PhpStorm.
 * User: anarbek
 * Date: 21.07.18
 * Time: 12:31
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function index()
    {
        return $this->render('fitness/homepage.html.twig');
    }
}
