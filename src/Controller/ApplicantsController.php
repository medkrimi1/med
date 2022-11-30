<?php

namespace App\Controller;
use App\Entity\Candidates;
use App\Entity\Jobs;
use App\Data\SearchDataCandidate;
use App\Form\SearchFormCandidate;
use App\Repository\CandidatesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplicantsController extends AbstractController
{
    private $manager;
    public function __construct(CandidatesRepository $CandidatesRepository , EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->CandidatesRepository=$CandidatesRepository;

    }
    /**
     * @Route("/dashboard/candidats/postulants", name="dashboard_applicants")
     */
    public function index(CandidatesRepository $repository , Request $request ){
        $data=new SearchDataCandidate();
       
        $form= $this->createForm(SearchFormCandidate::class, $data);
        $form->handleRequest($request) ;
        $candidates=$this->manager->getRepository(Candidates::class)->findSearchApplicant($data);


     
   return $this->render('dashboard/candidats/postulants.html.twig', [
            'candidates' => $candidates , 'form' => $form-> createView()
        ]);
    }

   



     /**
     * @Route("dashboard/candidats/{id}", name="applications")
     */
     public function details(Candidates $candidate): Response
    {
   
     
        return $this->render('dashboard/candidats/applications.html.twig', compact('candidate'));
    }














   



}