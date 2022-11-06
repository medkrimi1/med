<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JoblistController extends AbstractController
{
    /**
     * @Route("/offres", name="joblist")
     */
    public function index(){
        return $this->render('/offres/index.html.twig');
    }
}