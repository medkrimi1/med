<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CandidateResumeController extends AbstractController
{
    /**
     * @Route("/candidat/carrière", name="my_resume")
     */
    public function index(){
        return $this->render('candidat/carrière/index.html.twig');
    }
}