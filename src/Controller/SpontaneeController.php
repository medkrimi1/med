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

class SpontaneeController extends AbstractController
{
    private $manager;
    public function __construct(CandidatesRepository $CandidatesRepository , EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->CandidatesRepository=$CandidatesRepository;

    }
    /**
     * @Route("/dashboard/candidats/candidature-spontanee/", name="dashboard_spontanee")
     */
    public function index(CandidatesRepository $repository , Request $request ){
        $data=new SearchDataCandidate();
       
        $form= $this->createForm(SearchFormCandidate::class, $data);
        $form->handleRequest($request) ;
        $candidates=$this->manager->getRepository(Candidates::class)->findSearchspontanee($data);

       
        foreach ($candidates as $candidate){

             $offres=[];
            foreach($candidate->getApplications() as $offre)
            { 
             $title=$offre->getJob()->getTitle();
             $offres[]=[
             'id'=>$offre->getId() ,
             'jobid'=>$offre->getJob()->getId(),
             'title'=>$offre->getJob()->getTitle(),
             'slug'=> strtolower(str_replace(' ', '-',$title)),
             'appdate'=>$offre->getAppdate(),

             ] ;

            }

            $candidatesArray[] = [
               'id'=>$candidate->getId(),
              'fname'=>$candidate->getFname(),
              'lname'=>$candidate->getLname(),
              'seen'=>$candidate->getSeen(),
              'titre' => $candidate->getTitre(),
               'applications'=> $offres,
              
 
            ];
        }
         
       if(empty($candidatesArray)){$candidatesArray=[];}
     
   return $this->render('dashboard/candidats/candidature-spontanee.html.twig', [
            'candidates' => $candidatesArray , 'form' => $form-> createView()
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