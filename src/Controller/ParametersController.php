<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\ProfileAdmin;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ParametersController extends AbstractController
{
     private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
     /**
     * @Route("/dashboard/parameters/profile/", name="dashboard_parameters_profile")
     */
    public function index(UserRepository $UserRepository){
        $user=$this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('dashboard/parameters/profile.html.twig');
    }
    /**
     * @Route("/dashboard/parameters/profile/{id}", name="dashboard_parameters")
     */
    public function edit($id , request $request){
     
         $user=$this->getDoctrine()->getRepository(User::class)->find($id);

        $form= $this->createForm(ProfileAdmin::class,$user);
        $form->handleRequest($request) ;
         $em = $this->getDoctrine()->getManager();
         $email=$form->get('email')->getData();
         $fname=$form->get('fname')->getData();
         $lname=$form->get('lname')->getData();
         $check = $em->getRepository(User::class)->findBy(["email" => $email]);
   if ($form->isSubmitted() && $form->isValid()) {
    $user->setEmail($email);
            $em->persist($user);
        if($check) {
            
            $em->flush();
                $this->addFlash('error', 'Adresse existe déja');

           
            }
            else {
          $em->persist($user);
          $em->flush();
               $this->addFlash('success', 'test');
           }
             return $this->redirect($request->getUri());
 }
        return $this->render('dashboard/parameters/profile.html.twig',['form' => $form-> createView()]);
    }

    
}