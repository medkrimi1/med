<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;


class RhController extends AbstractController
{
       public function __construct(UserRepository $UserRepository , EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->UserRepository=$UserRepository;

    }
    /**
     * @Route("/dashboard/management", name="dashboard_management")
     */
    public function index(){
        $RH=$this->manager->getRepository(User::class)->findRH();
     
    
        return $this->render('dashboard/rh/management.html.twig',['RH'=>$RH]);
    }





 /**
     * @Route("/dashboard/RH/Ajouter", name="RH_Add")
     */
    public function AddRH(UserRepository $repository , Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher){
       
        $user=new User();
          $form= $this->createFormBuilder($user)
        ->add('fname')
        ->add('lname')
        ->add('mail',EmailType::class,[ 'mapped' => false,  'attr' => [
            

         ]])
          ->add('newpassword',PasswordType::class, [ 
                'mapped' => false,   'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]])

         ->getForm();
        $form->handleRequest($request) ;
         $em = $this->getDoctrine()->getManager();
        $email=$form->get('mail')->getData();
         $newpassword=$form->get('newpassword')->getData();
         $t=time();
        $Userid=$t;
         $users = $this->manager->getRepository(User::class)->findAll();
            
        foreach ($users as $userr){
            $userArray[] = strtolower(str_replace(' ', '',$userr->getEmail()));
            
        }

          if ($form->isSubmitted() && $form->isValid()) {
              if(in_array(strtolower(str_replace(' ', '',$email)), $userArray)){
                
            
          
                $this->addFlash('error', 'Adresse existe d??ja');

           
            }
            else {
           $user->setEmail($email);
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $newpassword
                )
            );
            $user->setImage('default.jpg');
            $role[]="ROLE_RH";
            $user->setId($Userid);
            $user->setRoles($role);
           $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'L\'utilisateur a ??t?? Ajout??e avec succ??s!');
           return $this->redirectToRoute('dashboard_management');
          }
             
        }

    
        return $this->render('dashboard/rh/AddRh.html.twig',['form'=>$form->createView()]);
    }









       /**
     * @Route("/dashboard/management/{id}", name="dashboard_managementEdit")
     */
    public function Edit( $id , UserRepository $repository , Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher){
      
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);
          $l=$user->getEmail();
          $pass=$user->getPassword();
          $password=$user->getPassword();
          $form= $this->createFormBuilder($user)
        ->add('fname')
        ->add('lname')
        ->add('mail',EmailType::class,[ 'mapped' => false,'attr' => ['value' => $l]])
          ->add('newpassword',PasswordType::class, [ 
                'mapped' => false,'required'=>false,
                ])

         ->getForm();
        $form->handleRequest($request) ;
         $em = $this->getDoctrine()->getManager();
        $email=$form->get('mail')->getData();
         $newpassword=$form->get('newpassword')->getData();
    
       
         $users = $this->manager->getRepository(User::class)->findAll();
            
        foreach ($users as $userr){
            $userArray[] = strtolower(str_replace(' ', '',$userr->getEmail()));
            
        }

          if ($form->isSubmitted() && $form->isValid()) {
             
         
             if($email!=$l){ 
     if(in_array(strtolower(str_replace(' ', '',$form->get('mail')->getData())), $userArray)){
                
            
                $this->addFlash('error', 'Adresse existe d??ja');
            
           
            }
            else {
          $user->setEmail($email);
          
          
        
          $em->flush();
        
               $this->addFlash('success', 'L\'adresse ??t?? modifi?? avec succ??s!');
               
        }
        return $this->redirectToRoute("dashboard_management");
           
          }

          else{  if($newpassword){
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $newpassword
                )
            );
        }
        else {$user->setPassword($pass); }
           
            $role[]="ROLE_RH";
          
            $user->setRoles($role);
           $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'L\'utilisateur a ??t?? modifi?? avec succ??s!');
          return $this->redirectToRoute("dashboard_management");

        }



             
        }


    
        return $this->render('dashboard/rh/EditManagement.html.twig',['form'=>$form->createView()]);
    }






    /**
     * @Route("dashboard/management/{id}/delete", name="RH_delete")
     * @param User $user
     * @return RedirectResponse
     */
    public function delete(User $user): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();


        return $this->redirectToRoute("dashboard_management");
    }
}