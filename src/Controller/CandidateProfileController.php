<?php

namespace App\Controller;

use App\Entity\Candidates;
use App\Entity\Professions;
use App\Entity\Candidatexp;
use App\Entity\User;
use App\Entity\Country;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Repository\CandidatesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;



class CandidateProfileController extends AbstractController
{
      public function __construct(CandidatesRepository $CandidatesRepository , EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->CandidatesRepository=$CandidatesRepository;

    }
    /**
     * @Route("/candidat/profil/{id}", name="candidate_profile")
     */
    public function index($id ,Request $request){
        $candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($id);
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
          $l=$user->getEmail();
          $day=substr(($candidate->getBdate()),0,2);
          $month=substr(($candidate->getBdate()),3,-5);
          $year=substr(($candidate->getBdate()),-4);
          
          $form= $this->createFormBuilder($candidate)
        ->add('fname')
        ->add('lname')
        ->add('titre', EntityType::class, [
                'class' => Professions::class,
                'choice_label' => 'title',
                 'required'=>false,
                'placeholder' => 'Select',])
         ->add('experience', EntityType::class, [
                'class' => Candidatexp::class,
                'choice_label' => 'title',
                 'required'=>false,
                'placeholder' => 'Select',])
         ->add('about', TextareaType::class,['required'=>false])
         ->add('phone')
            ->add('Country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'Name',
                'required'=>false,
                'placeholder' => 'Select',
             
            ])
              ->add('day', ChoiceType::class, [
             'mapped'=>false,
                'required'=>false,
                 'data' => $day,
                   'choices'  => 
 ['01' => '01','02' => '02','03' => '03','04' => '04','05' => '05','06' => '06','07' => '07','08' => '08','09' => '09','10' => '10','11' => '11','12'=> '12',],'placeholder' => 'Select' ])
                ->add('month', ChoiceType::class, [
                'mapped'=>false,  
                'data' => $month,
                'required'=>false,
                'choices'  => 
 ['01' => '01','02' => '02','03' => '03','04' => '04','05' => '05','06' => '06','07' => '07','08' => '08','09' => '09','10' => '10','11' => '11','12'=> '12',],'placeholder' => 'Select' ])
            
                  ->add('year', ChoiceType::class, [
                'mapped'=>false,  
                'required'=>false,
                 'data' => $year,
'choices'  => ['1970' => '1970','1971' => '1971','1972' => '1972','1973' => '1973','1974' => '1974','1975' => '1975','1976' => '1976','1977' => '1977','1978' => '1978','1979' => '1979','1980' => '1980','1981' => '1981','1982' => '1982','1983' => '1983','1984' => '1984','1985' => '1985','1986' => '1986','1987' => '1987','1988' => '1988','1989' => '1989','1990' => '1990','1991'=> '1991','1992'=> '1992','1993'=> '1993','1994'=> '1994','1995'=> '1995','1996'=> '1996','1997'=> '1997','1998'=> '1998','1999'=> '1999','2000'=> '2000','2001'=> '2001','2002'=> '2002','2003'=> '2003','2004'],'placeholder' => 'Select' ])

             ->add('pcode', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'phonecode',
                'required'=>false,
                'placeholder' => 'Select',
             
            ])

         ->add('mail',EmailType::class,[ 'mapped' => false,'attr' => ['value' => $l]])
            ->add('zip')
            ->add('city')
            ->add('address')
            ->add('site')
            ->add('fb')
            ->add('tw')
            ->add('gl')
            ->add('ln')
        
        
         ->getForm();
        $form->handleRequest($request) ;
           $em = $this->getDoctrine()->getManager();
           $users = $this->manager->getRepository(User::class)->findAll();
           $fname=$form->get('fname')->getData();
           $lname=$form->get('lname')->getData();
           $Day=$form->get('day')->getData();
           $Month=$form->get('month')->getData();
           $Year=$form->get('year')->getData();
           
         
           $destination = $this->getParameter('kernel.project_dir').'/public/images/users';
        foreach ($users as $userr){
            $userArray[] = strtolower(str_replace(' ', '',$userr->getEmail()));
            
        }
        $email=$form->get('mail')->getData();
     
      
   if ($form->isSubmitted() && $form->isValid()) {
  
         
        


     $candidate->setBdate($Month.'/'.$Day.'/'.$Year);
     $em->persist($candidate);
     $user->setFname($fname);
     $user->setLname($lname);
    

           
           
          $em->flush();
         
              $this->addFlash('success', 'Votre profil a été mis à jour');

    if($email!=$l){ 
     if(in_array(strtolower(str_replace(' ', '',$form->get('mail')->getData())), $userArray)){
                
            
                $this->addFlash('error', 'Adresse existe déja');
               return $this->redirect($request->getUri());
           
            }
            else {
          $user->setEmail($email);
          $candidate->setEmail($email);
          
        
          $em->flush();
        
               $this->addFlash('success', 'L\'adresse été modifié avec succès!');
                return $this->redirect($request->getUri());
        }
    }
           
               
       

 }



  
         
        
  
        return $this->render('candidat/profil/index.html.twig',['form'=>$form->createView()]);
    }






/**
     * @Route("/candidat/profil/{id}/image", name="candidate_profile_image")
     */
    public function image($id ,Request $request){
        $candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($id);
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
         
       
          
          $form= $this->createFormBuilder()
     
            

        
            ->add('imagefield', FileType::class, [
                'mapped' => false,  'required'=>false, 'attr' => ['accept' =>"image/*"  ]   ])
                 ->add('image', HiddenType::class)
             ->add('coverfield', FileType::class, [
                'mapped' => false,  'required'=>false, 'attr' => ['accept' =>"image/*"  ]   ])
                 ->add('cover', HiddenType::class)
        
         ->getForm();
        $form->handleRequest($request) ;
           $em = $this->getDoctrine()->getManager();
           $users = $this->manager->getRepository(User::class)->findAll();
      
           
            $uploadedImage = $form['imagefield']->getData();
            $uploadedCover = $form['coverfield']->getData();
            $image=$candidate->getImage();
            $cover=$candidate->getCover();
           $destination = $this->getParameter('kernel.project_dir').'/public/images/users';
        foreach ($users as $userr){
            $userArray[] = strtolower(str_replace(' ', '',$userr->getEmail()));
            
        }
    
     
      
   if ($form->isSubmitted() && $form->isValid()) {
  
            if ($uploadedImage) {
            $ImageName = uniqid().'.'.$uploadedImage->guessExtension();
         
                $newImageName = $ImageName;
                $uploadedImage->move($destination,$newImageName);
                $candidate->setImage($ImageName);
                $user->setImage($ImageName);            
            }
            else {
          $candidate->setImage($image);

            }


               if ($uploadedCover) {
            $CoverName = uniqid().'.'.$uploadedCover->guessExtension();
         
                $newCoverName = $CoverName;
                $uploadedCover->move($destination,$newCoverName);
                $candidate->setCover($CoverName);
               
               
               
               
            }
              else {
          $candidate->setCover($cover);

            }
    
   
    

           
           
          $em->flush();
         return $this->redirectToRoute("candidate_profile_image",["id"=>$id]);
              $this->addFlash('success', 'Votre profil a été mis à jour');
              
  
           
               
       

 }



  
         
        
  
        return $this->render('candidat/profil/image.html.twig',['form'=>$form->createView()]);
    }




























}