<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class explore_job_Controller extends AbstractController
{
    /**
     * @Route("/offres/explore", name="job_page")
     */
    public function index(){
        return $this->render('/offres/explore/index.html.twig');
    }
}