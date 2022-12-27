<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Candidates;
use App\Entity\User;
use App\Entity\Cv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

      /**
     * @Route("/redirect", name="redirection")
     */
    public function redirection()
    {
         return $this->render('redirection.html.twig'
        );  
    }


 /**
     * @Route("/inscription", name="signUp")
     */
     public function signup( Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    { 
        $em = $this->getDoctrine()->getManager();
        $candidate = new Candidates();
        $user = new User();  
        $cv = new Cv(); 
         
        $t=time();
        $Userid=$t;
        
        $form = $this->createFormBuilder()
         ->add('fname', TextType::class, [
                "attr" => [
                    "class" => "form-control",
                     'required'=>true,'placeholder'=>'Prénom'
                ]
            ])
            ->add('lname', TextType::class, [
                "attr" => [
                    "class" => "form-control",
                     'required'=>true,'placeholder'=>'Nom'
                ]
            ])
              ->add('email', TextType::class, [
                "attr" => [ 'required'=>true,
                    "class" => "form-control",'placeholder'=>'Adresse électronique'
                ]
            ])
               
                
                  
                        ->add('cvfield', FileType::class, [
                'mapped' => false,
                'required'=>true,     'attr' => [
             'accept' => "application/pdf"

         ]
            ])
                 ->add('cv', HiddenType::class, [
                    'mapped'=>false
                
            ])
              ->add('newpassword',PasswordType::class, [ 
                'mapped' => false,
               
            ])
              ->getForm();
        $form->handleRequest($request);
         $email=$form->get('email')->getData();
         $password=$form->get('newpassword')->getData();
        if($form->isSubmitted() && $form->isValid()) {
       $check = $em->getRepository(User::class)->findBy(["email" => $email]);
             
            if($check) {
                $this->addFlash('error', 'Adresse existe déja');
             return $this->redirect($request->getUri());
            }
            else {
        $fname=ucwords($form->get('fname')->getData());
        $lname=ucwords($form->get('lname')->getData());
        $uploadedCV = $form['cvfield']->getData();
        

        $candidate->setId($Userid);
        $candidate->setFname($fname);
          $candidate->setEmail($email);
        
        $candidate->setLname($lname);
          $candidate->setBdate(null);
        $candidate->setFullname($fname.' '.$lname); 
        $user->setFname($fname);
        $user->setLname($lname);
        $user->setId($Userid);
        $user->setEmail($email);

        $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );
        $user->setImage('default.jpg');
        $candidate->setImage('default.jpg');
        $role[]="ROLE_Candidate";
        $user->setRoles($role);

          
            $destination = $this->getParameter('kernel.project_dir').'/public/cv';
            if ($uploadedCV) {
             $CvName = 'cv'.uniqid().'.'.$uploadedCV->guessExtension();
                $newCvName = $CvName;
                $uploadedCV->move($destination,$newCvName);
               $cv->setCv($CvName);
               $cv->setCandidate($candidate);
                  $em->persist($cv); 
            }   

      
        $em->persist($user);       
        $em->persist($candidate);
        $em->flush();

   }


    $this->addFlash('success', 'Votre compte a été crée avec succès');
     return $this->redirectToRoute("app_login");
  
}









  
      
       
     
        return $this->render('signUp.html.twig',['form' => $form-> createView()]);
    }

}
