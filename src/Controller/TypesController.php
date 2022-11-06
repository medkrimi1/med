<?php

namespace App\Controller;
use App\Entity\TypeJobs;
use App\Repository\TypeJobsRepository;
use App\Form\ContratType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class TypesController extends AbstractController
{



   private $manager;
    public function __construct(TypeJobsRepository $TypeJobsRepository ,EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->TypeJobsRepository=$TypeJobsRepository;
    }

    /**
     * @Route("/dashboard/types", name="dashboard_Types")
     */
    public function index(){
        $typejob = $this->manager->getRepository(TypeJobs::class)->findAll();
      
        foreach ($typejob as $typejob){
            $typejobsArray[] = [
            
                'title' => $typejob->getTitle(),
                'id' => $typejob->getId(),

               
                
            
                


            ];
        }

        return $this->render('dashboard/types/index.html.twig', [
            'typejobs' => $typejobsArray,'countAllTypes' => $this->TypeJobsRepository->countAllTypes()
        ]);
    }



  /**
     * @Route("/dashboard/types/ajouter", name="add_types")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
         $em = $this->getDoctrine()->getManager();
        $data = new TypeJobs();
        $form = $this->createForm(ContratType::class, $data);
        $form->handleRequest($request);
        $types = $this->manager->getRepository(TypeJobs::class)->findAll();
      

       
            $slug=strtolower(str_replace(' ', '',$form->get('title')->getData()));
           $title=$form->get('title')->getData();
       
            if($form->isSubmitted() && $form->isValid()) {
                
           $check = $em->getRepository(TypeJobs::class)->findBy(["slug" => $slug]);
             
            if($check) {
                $this->addFlash('error', 'Ce nom existe déja');
                return $this->redirectToRoute('dashboard_Types');
            }
         
             else{
                $data->setSlug($slug);
                $data->setTitle($title);
                $em->persist($data);
                $em->flush();
                    $this->addFlash('success', 'Le type a été ajoutée avec succès!');
                }
                
                return $this->redirectToRoute('dashboard_Types');
                      
            }
        return $this->render('dashboard/types/ajouter.html.twig', [
            "form" => $form->createView()
        ]);
    }



   /**
     * @Route("dashboard/types/{id}/edit", name="type_edit")
     * @param TypeJobs $type
     * @param Request $request
     * @return Response
     */
    public function edit(TypeJobs $type, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $data = new TypeJobs();
        $form = $this->createForm(ContratType::class, $type);
        $form->handleRequest($request);
        $slug=strtolower(str_replace(' ', '',$form->get('title')->getData()));
        $title=$form->get('title')->getData();
       
            if($form->isSubmitted() && $form->isValid()) {
                
           $check = $em->getRepository(TypeJobs::class)->findBy(["slug" => $slug]);
             
            if($check) {
                $this->addFlash('error', 'Ce nom existe déja');
                return $this->redirectToRoute('dashboard_Types');
            }
         
             else{
                $type->setSlug($slug);
                $type->setTitle($title);
                $em->flush();
             
            $this->addFlash('success', 'Le type a été Modifié avec succès!');
        

            return $this->redirectToRoute('dashboard_Types');
        }}
        return $this->render("dashboard/types/modifier.html.twig", [
            "form" => $form->createView()
        ]);
    }


     /**
     * @Route("dashboard/types/{id}/delete", name="type_delete")
     * @param TypeJobs $type
     * @return RedirectResponse
     */
    public function delete(TypeJobs $type): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($type);
        $em->flush();


        return $this->redirectToRoute("dashboard_Types");
    }



}