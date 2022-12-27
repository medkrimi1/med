<?php

namespace App\Controller;
use App\Entity\Candidates;
use App\Entity\Jobs;
use App\Entity\Skills;
use App\Entity\Candidatexp;
use App\Entity\Country;
use App\Data\SearchDataCandidate;
use App\Form\SearchFormCandidate;
use App\Repository\CandidatesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidatesController extends AbstractController
{
    private $manager;
    public function __construct(CandidatesRepository $CandidatesRepository , EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->CandidatesRepository=$CandidatesRepository;

    }
    /**
     * @Route("/dashboard/candidats", name="dashboard_candidats")
     */
    public function index(CandidatesRepository $repository , Request $request ){
        $data=new SearchDataCandidate();
       
        $form= $this->createForm(SearchFormCandidate::class, $data);
        $form->handleRequest($request) ;
        $candidates=$this->manager->getRepository(Candidates::class)->findSearchCandidate($data);
        $skills=$this->manager->getRepository(Skills::class)->findAll();
         $experiences=$this->manager->getRepository(Candidatexp::class)->findAll();
         $countries=$this->manager->getRepository(Country::class)->findAll();
        
      
     
   return $this->render('dashboard/candidats/candidats.html.twig', [ 'skills'=>$skills,
         'experiences'=>$experiences, 'countries'=>$countries,  
            'candidates' => $candidates ,'form' => $form-> createView()
        ]);
    }

    /**
     * @Route("/dashboard/candidats/applicants", name="dashboard_candidats_applicants")
     */
    public function applicants(){
        return $this->render('dashboard/candidats/applicants.html.twig');
    }



     /**
     * @Route("dashboard/candidats/{id}", name="applications")
     */
     public function details(Candidates $candidate): Response
    {
   
     
        return $this->render('dashboard/candidats/applications.html.twig', compact('candidate'));
    }




    






}