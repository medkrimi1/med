<?php

namespace App\Controller;
use App\Entity\Candidates;
use App\Repository\CandidatesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class profil_page_Controller extends AbstractController
{

    private $manager;
    public function __construct(CandidatesRepository $CandidatesRepository, EntityManagerInterface $manager)
    {

        $this->manager = $manager;
        $this->CandidatesRepository = $CandidatesRepository;
    }
    /**
     * @Route("/candidat/profil_page/{id}", name="candidat_profil")
     */
     public function index($id){
         $candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($id);
      
      


        return $this->render('candidat/profil_page/index.html.twig'
    , [
            'candidate' => $candidate
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
     public function details2(Candidates $candidate,Request $request): Response
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

        $form= $this->createFormBuilder($candidate)
        ->add('status',ChoiceType::class, [
        'choices'=>['Traité'=>'Traité','en cours de traitement'=>'en cours de traitement','Non traité'=>'Non traité']

        ])


      ->getForm();
     $form->handleRequest($request) ;
      $status=$form->get('status')->getData();

     if ($form->isSubmitted() && $form->isValid()) {


   $candidate->setstatus($status);
   $em->persist($candidate);
   $em->flush();
  
return $this->redirect($request->getUri());
}
      


        return $this->render('dashboard/candidats/profile.html.twig', ['candidate'=>$candidate,'form'=>$form->createView()]);
    }
}