<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CandidateProfileController extends AbstractController
{
    /**
     * @Route("/candidat/profil", name="candidate_profile")
     */
    public function index(){
        return $this->render('candidat/profil/index.html.twig');
    }
}