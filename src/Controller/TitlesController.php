<?php

namespace App\Controller;
use App\Entity\Professions;
use App\Form\ProfessionType;
use App\Repository\ProfessionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
class TitlesController extends AbstractController
{



   private $manager;
    public function __construct(ProfessionsRepository $ProfessionsRepository , EntityManagerInterface $manager )
    {
        $this->manager = $manager;
        $this->ProfessionsRepository=$ProfessionsRepository ;
    }

    /**
     * @Route("/dashboard/titles", name="dashboard_Titles")
     */
    public function index(){
        $profession = $this->manager->getRepository(Professions::class)->findAll();
      
        foreach ($profession as $profession){
            $professionsArray[] = [
            
                'title' => $profession->getTitle(),
                'id' => $profession->getId(),
               
        
            ];
        }

        return $this->render('dashboard/titles/index.html.twig', [
            'professions' => $professionsArray , 'countAllProfessions'=>
            $this->ProfessionsRepository->countAllProfessions()
        ]);
    }



  /**
     * @Route("/dashboard/titles/ajouter", name="add_titles")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
         $em = $this->getDoctrine()->getManager();
        $data = new Professions();
        $form = $this->createForm(ProfessionType::class, $data);
        $form->handleRequest($request);
   $professions = $this->manager->getRepository(Professions::class)->findAll();
        
           $slug=strtolower(str_replace(' ', '',$form->get('title')->getData()));
           $title=$form->get('title')->getData();
       
            if($form->isSubmitted() && $form->isValid()) {
                
           $check = $em->getRepository(Professions::class)->findBy(["slug" => $slug]);
             
            if($check) {
                $this->addFlash('error', 'Ce nom existe déja');
                return $this->redirectToRoute('dashboard_Titles');
            }
         
             else{
                $data->setSlug($slug);
                $data->setTitle($title);
                $em->persist($data);
                $em->flush();
                    $this->addFlash('success', 'le titre a été ajoutée avec succès!');
                }
                
                return $this->redirectToRoute('dashboard_Titles');
                      
            }
          
        return $this->render('dashboard/titles/ajouter.html.twig', [
            "form" => $form->createView()
        ]);
    }


 /**
     * @Route("dashboard/titles/{id}/edit", name="title_edit")
     * @param Professions $profession
     * @param Request $request
     * @return Response
     */
    public function edit(Professions $profession, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $data = new Professions();
        $form = $this->createForm(ProfessionType::class, $profession);
        $form->handleRequest($request);
       
         
           $slug=strtolower(str_replace(' ', '',$form->get('title')->getData()));
           $title=$form->get('title')->getData();
       
            if($form->isSubmitted() && $form->isValid()) {
                
           $check = $em->getRepository(Professions::class)->findBy(["slug" => $slug]);
             
            if($check) {
                $this->addFlash('error', 'Ce nom existe déja');
                return $this->redirectToRoute('dashboard_Titles');
            }
         
             else{
                $profession->setSlug($slug);
                $profession->setTitle($title);
                $em->flush();
               if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'le titre a été Modifié avec succès!');
        }

            return $this->redirectToRoute('dashboard_Titles');
        }}
        return $this->render("dashboard/titles/modifier.html.twig", [
            "form" => $form->createView()
        ]);
    }

  /**
     * @Route("dashboard/title/{id}/delete", name="title_delete")
     * @param Profressions $profession
     * @return RedirectResponse
     */
    public function delete(Professions $profession): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($profession);
        $em->flush();


        return $this->redirectToRoute("dashboard_Titles");
    }

}