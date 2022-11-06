<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CandidateApplicationsController extends AbstractController
{
    /**
     * @Route("/candidat/candidatures", name="my_applications")
     */
    public function index(){
        return $this->render('candidat/candidatures/index.html.twig');
    }
}