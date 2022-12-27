<?php

namespace App\Controller;

use App\Entity\Candidates;
use App\Repository\CandidatesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Applications;
use App\Repository\ApplicationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CandidateApplicationsController extends AbstractController
{
    /**
     * @Route("/candidat/candidatures/{id}", name="my_applications")
     */
    public function index($id){
     $status="annulé";
    $candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($id);
    $applications=$this->getDoctrine()->getRepository(Applications::class)->findBy(array("Candidate" => $candidate,"status"=>!$status )) ;
     
    

        return $this->render('candidat/candidatures/index.html.twig',compact('applications'));
    }

    /**
     * @Route("/candidat/candidatures/supprimer/{idc}/{id}", name="Application_delete")
     * @param Applications $application
     * @return RedirectResponse
     */
    public function delete(Applications $application,$idc)
    {  


        $em = $this->getDoctrine()->getManager();
      
         
         $application->setStatus('annulé');
         
         
          $em->persist($application);
          $em->flush();


         $this->addFlash('success', 'Votre candidature a été annulé avec succès!');
        return $this->redirectToRoute("my_applications",["id"=>$idc]);


     
    }
}