<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Entity\Candidates;
use App\Entity\Applications;
use App\Entity\Skills;
use App\Entity\Professions;
use App\Repository\ApplicationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private $manager;
    public function __construct(ApplicationsRepository $ApplicationsRepository, EntityManagerInterface $manager)
    {
         $this->ApplicationsRepository = $ApplicationsRepository;
        $this->manager = $manager;
    }
    /**
     * 
     * @Route("/dashboard", name="dashboard_page" )
     */
    public function index()
    {
      $applications = $this->manager->getRepository(Applications::class)->findall();
      $applicants = $this->manager->getRepository(Candidates::class)->findSearchApplicant2();
      $spontane = $this->manager->getRepository(Candidates::class)->findSearchspontane();
      $skills = $this->manager->getRepository(Skills::class)->findAll();
      $professions = $this->manager->getRepository(Professions::class)->findAll();
      $jobs = $this->manager->getRepository(Jobs::class)->findForDashboard();
      $candidates = $this->manager->getRepository(Candidates::class)->findAll();

       
       
        return $this->render('dashboard/index.html.twig',compact('applications','skills','professions','jobs','candidates','applicants','spontane'));
    }
}