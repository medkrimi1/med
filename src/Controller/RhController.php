<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RhController extends AbstractController
{
    /**
     * @Route("/dashboard/management", name="dashboard_management")
     */
    public function index(){
        return $this->render('dashboard/rh/management.html.twig');
    }
}