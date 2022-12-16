<?php

namespace App\Controller;


use App\Entity\Candidates;
use App\Repository\CandidatesRepository;
use App\Entity\Skills;
use App\Entity\Formation;
use App\Entity\WorkExperience;
use App\Entity\Certificate;
use App\Entity\Language;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\DateTime;


class CandidateResumeController extends AbstractController
{
      public function __construct(CandidatesRepository $CandidatesRepository , EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->CandidatesRepository=$CandidatesRepository;

    }
    /**
     * @Route("/candidat/carrière/{id}", name="my_resume")
     */
    public function index($id,Request $request){
       $candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($id);
       $formations=$this->getDoctrine()->getRepository(Formation::class)->findBy(["candidate" => $candidate]);

       $works=$this->getDoctrine()->getRepository(WorkExperience::class)->findBy(["candidate" => $candidate]);
       $certificates=$this->getDoctrine()->getRepository(Certificate::class)->findBy(["candidate" => $candidate]);
       $languages=$this->getDoctrine()->getRepository(Language::class)->findBy(["candidate" => $candidate]);
       $newformation = new Formation();
       $newWork = new WorkExperience();
       $newCertif = new Certificate();
       $newlanguage = new Language();

           $em = $this->getDoctrine()->getManager();
           $choices=['Débutant'=>'Débutant','Intermédiaire'=>'Intermédiaire','Avancé'=>'Avancé'  ];
           $form_language= $this->createFormBuilder($newlanguage)
           
        ->add('lname', ChoiceType::class, ['choices'=>['français'=>'français','anglais'=>'anglais','espagnol'=>'espagnol','russe'=>'russe','Arabe'=>'Arabe','portugais'=>'portugais' ,'Allemand'=>'Allemand' ] ])
      ->add('reading', ChoiceType::class, ['choices'=>$choices ])
       ->add('speak', ChoiceType::class, ['choices'=>$choices ])
        ->add('writing', ChoiceType::class, ['choices'=>$choices  ])
          

       
         ->getForm();
        $form_language->handleRequest($request) ;
          
           $lname=$form_language->get('lname')->getData();
   
          if ($form_language->isSubmitted() && $form_language->isValid()) {
         
    

         $check=$this->getDoctrine()->getRepository(language::class)->findBy(array('candidate' => $candidate,'lname' => $lname)); 

         if($check){ $this->addFlash('warning', 'Langue existe déja');
       return $this->redirect($request->getUri());}
          
            else {
          
    
         
          $newlanguage->setCandidate($candidate);
         $em->persist($newlanguage);
          $em->flush();
               
         $this->addFlash('success', 'La langue a été Ajoutée avec succès!');
             return $this->redirect($request->getUri());

          }
      }
       $form_formation= $this->createFormBuilder($newformation)
        ->add('university')
        ->add('diploma')
           ->add('startdate', DateType::class, [ 
        'widget' => 'single_text',
         'required'=>true,
       
                  'attr' => ['class' => 'js-datepicker text-center text-primary'],  
        ])
               ->add('enddate', DateType::class, [ 
        'widget' => 'single_text',
        'required'=>false,'mapped'=>false,
       
                  'attr' => ['class' => 'js-datepicker text-center text-primary'],  
        ])
        ->add('description', TextareaType::class)
       
         ->getForm();
        $form_formation->handleRequest($request) ;
           

          

          if ($form_formation->isSubmitted() && $form_formation->isValid()) {
          $enddate=$form_formation->get('enddate')->getData();
          if($enddate!=null){
          $Enddate=$enddate->format("d-m-y") ; 
          $newformation->setEnddate($Enddate);}
          else {
            $newformation->setEnddate(null); 
          }
       
          $newformation->setCandidate($candidate);
         $em->persist($newformation);
          $em->flush();
               
         $this->addFlash('success', 'La formation a été Ajoutée avec succès!');
             return $this->redirect($request->getUri());


          }




            
       $form_work= $this->createFormBuilder($newWork)
        ->add('title', TextType::class)
        ->add('company', TextType::class)
           ->add('startdate', DateType::class, [ 
        'widget' => 'single_text',
        'required'=>true,
                  'attr' => ['class' => 'js-datepicker text-center text-primary'],  
        ])
               ->add('enddate', DateType::class, [ 
        'widget' => 'single_text',
          'required'=>false,
          'mapped'=>false,
                  'attr' => ['class' => 'js-datepicker text-center text-primary'],  
        ])
         ->add('project', TextType::class)
          ->add('envi', TextType::class)       
        ->add('duties', TextareaType::class,['required'=>false])
       
         ->getForm();
        $form_work->handleRequest($request) ;
          
          

          if ($form_work->isSubmitted() && $form_work->isValid()) {
           $enddate=$form_work->get('enddate')->getData();
          if($enddate!=null){
          $Enddate=$enddate->format("d-m-y") ; 
          $newWork->setEnddate($Enddate);}
          $newWork->setCandidate($candidate);
         $em->persist($newWork);
          $em->flush();
               
         $this->addFlash('success', 'L\'expérience a été Ajoutée avec succès!');
             return $this->redirect($request->getUri());


          }




  $form_certif= $this->createFormBuilder($newCertif)
        ->add('title', TextType::class)
       ->add('attachmentField', FileType::class, [
'mapped'=>false,
'required'=>false,     'attr' => [
'accept' => "application/pdf"
]
])

->add('attachment', HiddenType::class, [    ])
           ->add('startdate', DateType::class, [ 
        'widget' => 'single_text',
         'required'=>true,
       
                  'attr' => ['class' => 'js-datepicker text-center text-primary'],  
        ])
               ->add('enddate', DateType::class, [ 
        'widget' => 'single_text','mapped'=>false,
       
        'required'=>false,
                  'attr' => ['class' => 'js-datepicker text-center text-primary'],  
        ])

       
         ->getForm();
        $form_certif->handleRequest($request) ;
          
          
   $attachment = $form_certif['attachmentField']->getData();
          if ($form_certif->isSubmitted() && $form_certif->isValid()) {
              $enddate=$form_certif->get('enddate')->getData();
          if($enddate!=null){
          $Enddate=$enddate->format("d-m-y") ; 
          $newCertif->setEnddate($Enddate);}
            if ($attachment) {
$AttachmentName = 'certif'.uniqid().'.'.$attachment->guessExtension();
$destination = $this->getParameter('kernel.project_dir').'/public/certificates';
$attachment->move($destination,$AttachmentName);
$newCertif->setAttachment($AttachmentName);

}
              

         
          $newCertif->setCandidate($candidate);
         $em->persist($newCertif);
          $em->flush();
               
         $this->addFlash('success', 'Certificat a été Ajoutée avec succès!');
             return $this->redirect($request->getUri());

          }

       


 $form_skills= $this->createFormBuilder($candidate)
         ->add('skill', EntityType::class , [
            
            'required' => true ,
            'class' => Skills::class,
            'multiple' => true

            
       ])
      
         ->getForm();
        $form_skills->handleRequest($request) ;
         $em = $this->getDoctrine()->getManager();

          if ($form_skills->isSubmitted() && $form_skills->isValid()) {

         $em->persist($candidate);
          $em->flush();
               
        
             return $this->redirect($request->getUri());


          }
         


        return $this->render('candidat/carrière/index.html.twig',['candidate'=>$candidate,'formations'=>$formations,'works'=>$works,'certificates'=>$certificates,'languages'=>$languages,'form_formation'=>$form_formation->createView(),'form_work'=>$form_work->createView(),'form_certif'=>$form_certif->createView(),'form_language'=>$form_language->createView()]);
    }




     /**
     * @Route("/candidat/carrière/compétences/{id}", name="Edit_Skills_Candidate")
     */
    public function skills($id,Request $request){
       $candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($id);
     

      
        $form_skills= $this->createFormBuilder($candidate)
         ->add('skill', EntityType::class , [
            
            'required' => true ,
            'class' => Skills::class,
            'multiple' => true

            
       ])
      
         ->getForm();
        $form_skills->handleRequest($request) ;
         $em = $this->getDoctrine()->getManager();

          if ($form_skills->isSubmitted() && $form_skills->isValid()) {

         $em->persist($candidate);
          $em->flush();
               
        
              return $this->redirectToRoute("my_resume",["id"=>$id]);


          }


            return $this->render('candidat/carrière/update/skills.html.twig',['form_skills'=>$form_skills->createView()]);
    }


  /**
   * @Route("candidat/carrière/{idc}/{id}/ModifierFormation", name="formation_edit")
     * @param Request $request
     * @return Response
     */
    public function editFormation(Formation $formation,$idc ,Request $request): Response
    {   
          $old=$formation->getEnddate();
         if($old){
         $date = new \DateTime($old);
           }
           else{$date=null;}
            $candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($idc);
       $form_formation= $this->createFormBuilder($formation)
        ->add('university')
        ->add('diploma')
           ->add('startdate', DateType::class, [ 
        'widget' => 'single_text',
         'required'=>true,
        
                  'attr' => ['class' => 'js-datepicker text-center text-primary'],  
        ])
               ->add('enddate', DateType::class, [ 
        'widget' => 'single_text',
        'required'=>false,
        'mapped'=>false,

        'data'=>$date,
        

                  'attr' => ['class' => 'js-datepicker text-center text-primary'],  
        ])
        ->add('description', TextareaType::class)
       
         ->getForm();
        $form_formation->handleRequest($request) ;
         $em = $this->getDoctrine()->getManager();
          

          if ($form_formation->isSubmitted() && $form_formation->isValid()) {
            $enddate=$form_formation->get('enddate')->getData();
          if($enddate!=null){
          $Enddate=$enddate->format("d-m-y") ; 
          $formation->setEnddate($Enddate);}
          else {
            $formation->setEnddate(null); 
          }
       
          
          $formation->setCandidate($candidate);
         $em->persist($formation);
          $em->flush();
               

             $this->addFlash('success', 'La formation a été modifié avec succès!');
             return $this->redirectToRoute("my_resume",["id"=>$idc]);

        }
        return $this->render('candidat/carrière/update/Formation.html.twig', [
            "form_formation" => $form_formation->createView()
        ]);
    }



     /**
   * @Route("candidat/carrière/{idc}/{id}/ModifierExperience", name="Experience_edit")
     * @param Request $request
     * @return Response
     */
    public function editWork(WorkExperience $experience,$idc ,Request $request): Response
    {
        $old=$experience->getEnddate();
         if($old){
         $date = new \DateTime($old);
           }
           else{$date=null;}
            $candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($idc);
       $form_work= $this->createFormBuilder($experience)
        ->add('title', TextType::class)
        ->add('company', TextType::class)
           ->add('startdate', DateType::class, [ 
        'widget' => 'single_text',
         
      
                  'attr' => ['class' => 'js-datepicker text-center text-primary'],  
        ])
               ->add('enddate', DateType::class, [ 
        'widget' => 'single_text',
         'data'=>$date,
         'mapped'=>false,
         'required'=>false,
      
                  'attr' => ['class' => 'js-datepicker text-center text-primary'],  
        ])
         ->add('project', TextType::class)
          ->add('envi', TextType::class)       
        ->add('duties', TextareaType::class,['required'=>false])
       
         ->getForm();
        $form_work->handleRequest($request) ;
         $em = $this->getDoctrine()->getManager();
          

          if ($form_work->isSubmitted() && $form_work->isValid()) {
                $enddate=$form_work->get('enddate')->getData();
          if($enddate!=null){
          $Enddate=$enddate->format("d-m-y") ; 
          $experience->setEnddate($Enddate);}
          else {
            $experience->setEnddate(null); 
          }
          
          $experience->setCandidate($candidate);
         $em->persist($experience);
          $em->flush();
               

             $this->addFlash('success', 'L\'expérience a été modifié avec succès!');
             return $this->redirectToRoute("my_resume",["id"=>$idc]);

        }
        return $this->render('candidat/carrière/update/Experience.html.twig', [
            "form_work" => $form_work->createView()
        ]);
    }




/**
   * @Route("candidat/carrière/{idc}/{id}/ModifierLangue", name="Language_edit")
     * @param Request $request
     * @return Response
     */
    public function editLanguage(Language $language,$idc ,Request $request): Response
    {
       
            $candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($idc);
       

           $choices=['Débutant'=>'Débutant','Intermédiaire'=>'Intermédiaire','Avancé'=>'Avancé'  ];
             $form_language= $this->createFormBuilder($language)
          
           
        ->add('lname', ChoiceType::class, ['choices'=>['français'=>'français','anglais'=>'anglais','espagnol'=>'espagnol','russe'=>'russe','Arabe'=>'Arabe','portugais'=>'portugais' ,'Allemand'=>'Allemand' ],'mapped'=>false ])
      ->add('reading', ChoiceType::class, ['choices'=>$choices ])
       ->add('speak', ChoiceType::class, ['choices'=>$choices ])
        ->add('writing', ChoiceType::class, ['choices'=>$choices  ])
          


       
         ->getForm();
        $form_language->handleRequest($request) ;
         $em = $this->getDoctrine()->getManager();
           $llname=$language->getLname();
        
          

          if ($form_language->isSubmitted() && $form_language->isValid()) {
 
         
        
          $language->setLname($llname);
         $em->persist($language);
          $em->flush();
               

             $this->addFlash('success', 'La langue a été modifié avec succès!');
             return $this->redirectToRoute("my_resume",["id"=>$idc]);

        }
        return $this->render('candidat/carrière/update/Language.html.twig', [
            "form_language" => $form_language->createView(),'language'=>$language
        ]);
    }
























     /**
     * @Route("candidat/carrière/{idc}/{id}/deleteFormation", name="formation_delete")
     * @param Formation $formation
     * @return RedirectResponse
     */
    public function deleteFormation(Formation $formation,$idc ,Request $request): RedirectResponse
    {  
        $em = $this->getDoctrine()->getManager();
        $em->remove($formation);
        $em->flush();
         $this->addFlash('warning', 'La formation a été supprimée avec succès!');
       
 return $this->redirectToRoute("my_resume",["id"=>$idc]);
    }

     /**
     * @Route("candidat/carrière/{idc}/{id}/deleteWork", name="work_delete")
     * @param WorkExperience $work
     * @return RedirectResponse
     */
    public function deleteWork(WorkExperience $work,$idc ,Request $request): RedirectResponse
    {  
        $em = $this->getDoctrine()->getManager();
        $em->remove($work);
        $em->flush();
         $this->addFlash('warning', 'L\'expérience a été supprimée avec succès!');
 return $this->redirectToRoute("my_resume",["id"=>$idc]);
    }

     /**
     * @Route("candidat/carrière/{idc}/{id}/deleteCertif", name="certif_delete")
     * @param Certificate $certif
     * @return RedirectResponse
     */
    public function deleteCertif(Certificate $certif,$idc ,Request $request): RedirectResponse
    {  
        $em = $this->getDoctrine()->getManager();
        $em->remove($certif);
        $em->flush();
         $this->addFlash('warning', 'Certificat a été supprimée avec succès!');
 return $this->redirectToRoute("my_resume",["id"=>$idc]);
    }
      /**
     * @Route("candidat/carrière/{idc}/{id}/deleteLanguage", name="language_delete")
     * @param Languages $language
     * @return RedirectResponse
     */
    public function deleteLanguage(Language $language,$idc ,Request $request): RedirectResponse
    {  
        $em = $this->getDoctrine()->getManager();
        $em->remove($language);
        $em->flush();
         $this->addFlash('warning', 'La langue a été supprimée avec succès!');
 return $this->redirectToRoute("my_resume",["id"=>$idc]);
    }
}