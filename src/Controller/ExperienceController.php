<?php

namespace App\Controller;
use App\Entity\Jobs;
use App\Entity\Experience;
use App\Form\ExperienceType;
use App\Repository\ExperienceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ExperienceController extends AbstractController
{
     private $manager;
    public function __construct(ExperienceRepository $ExperienceRepository, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->ExperienceRepository = $ExperienceRepository;
    }

    /**
     * 
     * @Route("/dashboard/experience", name="dashboard_experience")
     */
    public function index(){
        $experiences = $this->manager->getRepository(Experience::class)->findAll();
  
        foreach ($experiences as $experience){
            $experiencesArray[] = [
            
                'title' => $experience->getTitle(),
                'id' => $experience->getId()
              


            ];
        }

   
        return $this->render('dashboard/experience/index.html.twig', [
            'experiences' => $experiencesArray 
        ]);
    }


    /**
     * @Route("dashboard/experience/{id}/edit", name="experience_edit")
     * @param Experience $experience
     * @param Request $request
     * @return Response
     */
    public function edit(Experience $experience, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $data = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);
        $slug=strtolower(str_replace(' ', '',$form->get('title')->getData()));
        $title=$form->get('title')->getData();
       
            if($form->isSubmitted() && $form->isValid()) {
                
           $check = $em->getRepository(Experience::class)->findBy(["slug" => $slug]);
             
            if($check) {
                $this->addFlash('error', 'Ce nom existe déja');
                return $this->redirectToRoute('dashboard_experience');
            }
         
             else{
                $experience->setSlug($slug);
                $experience->setTitle($title);
                $em->flush();

            
            $this->addFlash('success', 'L\'expérience a été Modifié avec succès!');
    

            return $this->redirectToRoute('dashboard_experience');
        }
    }
        return $this->render("dashboard/experience/modifier.html.twig", [
            "form" => $form->createView()
        ]);
    }


      /**
     * @Route("/dashboard/experience/ajouter", name="add_experience")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $data = new Experience();
        $form = $this->createForm(ExperienceType::class, $data);
        $form->handleRequest($request);
        $experiences = $this->manager->getRepository(Experience::class)->findAll();
        $slug=strtolower(str_replace(' ', '',$form->get('title')->getData()));
        $title=$form->get('title')->getData();
       
            if($form->isSubmitted() && $form->isValid()) {
                
           $check = $em->getRepository(Experience::class)->findBy(["slug" => $slug]);
             
            if($check) {
                $this->addFlash('error', 'Ce nom existe déja');
                return $this->redirectToRoute('dashboard_experience');
            }
         
             else{
                $data->setSlug($slug);
                $data->setTitle($title);
                $em->persist($data);
                $em->flush();
                    $this->addFlash('success', 'L\'expérience a été ajoutée avec succès!');
                }
                
                return $this->redirectToRoute('dashboard_experience');
                      
            }
    
        return $this->render('dashboard/experience/ajouter.html.twig', [
            "form" => $form->createView()
        ]);
    }


   /**
     * @Route("dashboard/experience/{id}/delete", name="experience_delete")
     * @param Experience $experience
     * @return RedirectResponse
     */
    public function delete(Experience $experience): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($experience);
        $em->flush();


        return $this->redirectToRoute("dashboard_experience");
    }









}