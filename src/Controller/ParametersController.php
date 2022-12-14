<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\ProfileAdmin;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\ORM\EntityRepository;

class ParametersController extends AbstractController
{
     private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/dashboard/parameters/profile/{id}", name="dashboard_parameters")
     */
    public function edit($id , request $request , UserPasswordHasherInterface $userPasswordHasher){
     
         $user=$this->getDoctrine()->getRepository(User::class)->find($id);

        $form_nom= $this->createFormBuilder($user)
        ->add('fname')
        ->add('lname')
         ->getForm();
        $form_nom->handleRequest($request) ;
         $em = $this->getDoctrine()->getManager();
         
       
         $fname=$form_nom->get('fname')->getData();
         $lname=$form_nom->get('lname')->getData();
   if ($form_nom->isSubmitted() && $form_nom->isValid()) {
  
           
          $em->persist($user);
          $em->flush();
              // $this->addFlash('success', 'le nom été modifié avec succès!');
        
             return $this->redirect($request->getUri());
 }
      $l=$user->getEmail();
     $form_email= $this->createFormBuilder($user)
        ->add('mail',EmailType::class,[ 'mapped' => false,'attr' => ['value' => $l]])
         ->add('fname',HiddenType::class)
        ->add('lname',HiddenType::class)
         ->getForm();
        $form_email->handleRequest($request) ;
             $users = $this->manager->getRepository(User::class)->findAll();
            
        foreach ($users as $userr){
            $userArray[] = strtolower(str_replace(' ', '',$userr->getEmail()));
            
        }
        $email=$form_email->get('mail')->getData();
     
       
   if ($form_email->isSubmitted() && $form_email->isValid()) {
    
     if(in_array(strtolower(str_replace(' ', '',$form_email->get('mail')->getData())), $userArray)){
                
            
          
                $this->addFlash('error', 'Adresse existe déja');

           
            }
            else {
          $user->setEmail($email);
         
          $em->flush();
               $this->addFlash('success', 'L\'adresse été modifié avec succès!');
        }
             return $this->redirect($request->getUri());
 }



   $form_pass= $this->createFormBuilder($user)
        ->add('newpassword',PasswordType::class, [ 
                'mapped' => false,
               
            ])
       ->add('repeatedpassword',PasswordType::class, [
                'mapped' => false,
               
            ])

        
         ->add('fname',HiddenType::class)
        ->add('lname',HiddenType::class)
         ->add('email',HiddenType::class)
       

        
         ->getForm();
        $form_pass->handleRequest($request) ;
        $newpassword=$form_pass->get('newpassword')->getData();
        $repeatedpassword=$form_pass->get('repeatedpassword')->getData();
       
       
       
   
   if ($form_pass->isSubmitted() && $form_pass->isValid()) {
 
  
  
    if($newpassword==$repeatedpassword) {
               $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $newpassword
                )
            );
         
          $em->flush();
               $this->addFlash('success', 'Votre mot de passe été modifié avec succès!');
           
            }
            else {
                 $em->flush();
                $this->addFlash('error', 'Les mots de passe ne correspondent pas');

           
               
    }
             return $this->redirect($request->getUri());
 }




        return $this->render('dashboard/parameters/profile.html.twig',['form_nom' => $form_nom-> createView(),'form_email' => $form_email-> createView(),'form_pass' => $form_pass-> createView()]);
    }



  /**
     * @Route("/dashboard/parameters/profile/image/{id}", name="dashboard_image")
     */
    public function editimage($id , request $request ){
     
         $user=$this->getDoctrine()->getRepository(User::class)->find($id);

        $form= $this->createFormBuilder($user)
          ->add('imagefield', FileType::class, [
                'mapped' => false,
                'required'=>false,
                  'attr' => [
             'accept' => "image/*"

         ]
            ])
                 ->add('image', HiddenType::class, [
                
            ])
         ->getForm();
        $form->handleRequest($request) ;
         $em = $this->getDoctrine()->getManager();
           if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedImage = $form['imagefield']->getData();

           
            $destination = $this->getParameter('kernel.project_dir').'/public/images/users';
            if ($uploadedImage) {
            $ImageName = uniqid().'.'.$uploadedImage->guessExtension();
         
                $newImageName = $ImageName;
                $uploadedImage->move($destination,$newImageName);
                $user->setImage($ImageName);
               
               
               
            }
            
             
         $em->persist($user);
            $em->flush();
               return $this->redirect($request->getUri());
            
         }
    
  
        return $this->render('dashboard/parameters/image.html.twig',['form' => $form-> createView()]);
    }









      /**
     * @Route("/dashboard/parameters/profile/image/{id}/delete", name="dashboard_image_delete")
     */
    public function deleteAvatar($id , request $request ){
     
         $user=$this->getDoctrine()->getRepository(User::class)->find($id);

        $form= $this->createFormBuilder($user)
        ->getForm();
        $id=$user->getId();
        $form->handleRequest($request) ;
         $em = $this->getDoctrine()->getManager();
         $user->setImage('default.jpg');
          $em->persist($user);
            $em->flush();
               return $this->redirect('./');
            
     
    
  
        return $this->render('dashboard/parameters/image.html.twig',['form' => $form-> createView()]);
    }
    
}