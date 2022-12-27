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

class CandidatePasswordController extends AbstractController
{
     private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/candidat/paramètres/{id}", name="candidate_password")
     */
    public function index($id,Request $request, UserPasswordHasherInterface $userPasswordHasher){
         $user=$this->getDoctrine()->getRepository(User::class)->find($id);
         $em = $this->getDoctrine()->getManager();
         $form_pass= $this->createFormBuilder($user)
       ->add('oldpassword',PasswordType::class, [ 
                'mapped' => false,
               
            ])
        ->add('newpassword',PasswordType::class, [ 
                'mapped' => false,
               
            ])
       ->add('repeatedpassword',PasswordType::class, [
                'mapped' => false,
               
            ])

         ->getForm();
        $form_pass->handleRequest($request) ;
         $oldpassword=$form_pass->get('oldpassword')->getData();
        $newpassword=$form_pass->get('newpassword')->getData();
        $repeatedpassword=$form_pass->get('repeatedpassword')->getData();
       
       
       
   
   if ($form_pass->isSubmitted() && $form_pass->isValid()) {
 
  if ($userPasswordHasher->isPasswordValid($user, $oldpassword)) {
  
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

}
else{ $this->addFlash('error', 'Votre mot de passe est incorrect');}
             return $this->redirect($request->getUri());
 }


    

          

        
        
        return $this->render('candidat/password/index.html.twig',['form_pass'=>$form_pass->createView()]);
    }

     /**
     * @Route("/candidat/paramètres/supprimer/{id}", name="candidate_delete")
     * @param User $user
     * @return RedirectResponse
     */
    public function delete(User $user)
    {  
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("app_logout");


     
    }
}