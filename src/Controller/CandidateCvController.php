<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CandidateCvController extends AbstractController
{
    /**
     * @Route("/candidat/cv", name="my_cv")
     */
    public function index(){
        return $this->render('candidat/cv/index.html.twig');
    }
}