<?php

namespace App\Controller;
use App\Entity\Candidatexp;
use App\Form\CandidatexpType;
use App\Repository\CandidatexpRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ExperienceCandidateController extends AbstractController
{
     private $manager;
    public function __construct(CandidatexpRepository $CandidatexpRepository, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->CandidatexpRepository = $CandidatexpRepository;
    }

    /**
     * 
     * @Route("/dashboard/experience/candidat", name="dashboard_experience_candidate")
     */
    public function index(){
        $experiences = $this->manager->getRepository(Candidatexp::class)->findAll();
  
        foreach ($experiences as $experience){
            $experiencesArray[] = [
            
                'title' => $experience->getTitle(),
                'id' => $experience->getId()
              


            ];
        }

   
        return $this->render('dashboard/experienceCandidat/index.html.twig', [
            'experiences' => $experiencesArray 
        ]);
    }


    /**
     * @Route("dashboard/experience/candidat/{id}/edit", name="experience_candidate_edit")
     * @param Experience $experience
     * @param Request $request
     * @return Response
     */
    public function edit(Candidatexp $experience, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $data = new Candidatexp();
        $form = $this->createForm(CandidatexpType::class, $experience);
        $form->handleRequest($request);
        $slug=strtolower(str_replace(' ', '',$form->get('title')->getData()));
        $title=$form->get('title')->getData();
       
            if($form->isSubmitted() && $form->isValid()) {
                
           $check = $em->getRepository(Candidatexp::class)->findBy(["slug" => $slug]);
             
            if($check) {
                $this->addFlash('error', 'Ce nom existe déja');
                return $this->redirectToRoute('dashboard_experience_candidate');
            }
         
             else{
                $experience->setSlug($slug);
                $experience->setTitle($title);
                $em->flush();

            
            $this->addFlash('success', 'L\'expérience a été Modifié avec succès!');
    

            return $this->redirectToRoute('dashboard_experience_candidate');
        }
    }
        return $this->render("dashboard/experienceCandidat/modifier.html.twig", [
            "form" => $form->createView()
        ]);
    }


      /**
     * @Route("/dashboard/experience/candidat/ajouter", name="add_experience_candidate")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $data = new Candidatexp();
        $form = $this->createForm(CandidatexpType::class, $data);
        $form->handleRequest($request);
        $experiences = $this->manager->getRepository(Candidatexp::class)->findAll();
        $slug=strtolower(str_replace(' ', '',$form->get('title')->getData()));
        $title=$form->get('title')->getData();
       
            if($form->isSubmitted() && $form->isValid()) {
                
           $check = $em->getRepository(Candidatexp::class)->findBy(["slug" => $slug]);
             
            if($check) {
                $this->addFlash('error', 'Ce nom existe déja');
                return $this->redirectToRoute('dashboard_experience_candidate');
            }
         
             else{
                $data->setSlug($slug);
                $data->setTitle($title);
                $em->persist($data);
                $em->flush();
                    $this->addFlash('success', 'L\'expérience a été ajoutée avec succès!');
                }
                
                return $this->redirectToRoute('dashboard_experience_candidate');
                      
            }
    
        return $this->render('dashboard/experienceCandidat/ajouter.html.twig', [
            "form" => $form->createView()
        ]);
    }


   /**
     * @Route("dashboard/experience/candidat/{id}/delete", name="experience_candidate_delete")
     * @param Experience $experience
     * @return RedirectResponse
     */
    public function delete(Candidatexp $experience): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($experience);
        $em->flush();


        return $this->redirectToRoute("dashboard_experience_candidate");
    }









}