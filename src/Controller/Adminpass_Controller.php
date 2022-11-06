<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Adminpass_Controller extends AbstractController
{
    /**
     * @Route("/dashboard/password", name="admin_pass")
     */
    public function index(){
        return $this->render('dashboard/password/index.html.twig');
    }
}