<?php

namespace App\Controller;
use App\Entity\Candidates;
use App\Repository\CandidatesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class profil_page_Controller extends AbstractController
{

    private $manager;
    public function __construct(CandidatesRepository $CandidatesRepository, EntityManagerInterface $manager)
    {

        $this->manager = $manager;
        $this->CandidatesRepository = $CandidatesRepository;
    }
    /**
     * @Route("/candidat/profil_page", name="candidat_profil")
     */
     public function index(){
        $candidates = $this->manager->getRepository(Candidates::class)->findAll();
      
        foreach ($candidates as $candidate){

              $skills=[];
            foreach($job->getSkills() as $skill)
            {
             $skills[]=[
             'id'=>$skill->getId() ,
             'title'=>$skill->getTitle()
             ] ;
         }
            $candidatesArray[] = [
                'id' => $candidate->getId(),
                'fname' => $candidate->getfname(),
                'lname' => $candidate->getlname(),
             
                
                


                


            ];
        }


        return $this->render('candidat/profil_page/index.html.twig'
    , [
            'candidates' => $candidatesArray 
        ]);
    }




     /**
     * @Route("/candidat/profil_page/{fname}{lname}/{id}", name="candidat_profile")
     */
     public function details(Candidates $candidate): Response
    {


        return $this->render('candidat/profil_page/index.html.twig', compact('candidate'));
    }



     /**
     * @Route("dashboard/candidats/{fname}{lname}/{id}", name="candidatProfile")
     */
     public function details2(Candidates $candidate): Response
    {

   $candidates = new Candidates();
        
       
      
            $em = $this->getDoctrine()->getManager();
           $check=$candidate->getSeen();
    if ($check='1'){
          
           $vu='1';
          $candidate->setSeen($vu);
          $em->persist($candidate);
          $em->flush();
        }
        return $this->render('dashboard/candidats/profile.html.twig', compact('candidate'));
    }
}