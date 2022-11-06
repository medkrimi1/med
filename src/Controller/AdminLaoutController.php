<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Applications;
use App\Repository\ApplicationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminLaoutController extends AbstractController
{
      public function __construct(ApplicationsRepository $ApplicationsRepository, EntityManagerInterface $manager)
    {
         $this->ApplicationsRepository = $ApplicationsRepository;
        $this->manager = $manager;
    }
    /**
     * @Route("/dashboard/notifications", name="dashboard_notifications")
     */
    public function index(){
        $applications = $this->manager->getRepository(Applications::class)->findAll();
        return $this->render('dashboard/notifications/index.html.twig',compact('applications'));
    }
}