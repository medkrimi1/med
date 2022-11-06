<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CandidatePasswordController extends AbstractController
{
    /**
     * @Route("/candidat/password", name="candidate_password")
     */
    public function index(){
        return $this->render('candidat/password/index.html.twig');
    }
}