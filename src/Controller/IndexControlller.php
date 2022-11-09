<?php

namespace App\Controller;

use App\Entity\Jobs;
use App\Entity\User;
use App\Entity\Applications;
use App\Entity\Candidates;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\JobsRepository;
use App\Repository\CandidatesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Data\SearchData1;
use App\Form\SearchForJob;
use App\Form\Application;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


 

class IndexControlller extends AbstractController
{
    private $manager;
    public function __construct(JobsRepository $JobsRepository, EntityManagerInterface $manager)
    {

        $this->manager = $manager;
        $this->JobsRepository = $JobsRepository;
    }

    /**
     * @Route("/", name="offres")
     */
    public function index(JobsRepository $repository , Request $request ){
        $data=new SearchData1();
       
        $form= $this->createForm(SearchForJob::class, $data);
        $form->handleRequest($request) ;
        $jobs=$this->manager->getRepository(Jobs::class)->SearchForJob($data);
        $spontane=$this->getDoctrine()->getRepository(Jobs::class)->find(609679);
        foreach ($jobs as $job){
             $title=$job->getTitle();
            $str = [' ','é','è','\'','ç'];
            $rplc =['-','e','e','','c'];
              $skills=[];
            foreach($job->getSkills() as $skill)
            {
             $skills[]=[
             'id'=>$skill->getId() ,
             'title'=>$skill->getTitle()
             ] ;

            }
            $jobsArray[] = [
                'id' => $job->getId(),
                'title' => $job->getTitle(),
                 'country' => strtolower($job->getCountry()->getName()),
                'image'=>$job->getImage(),
                'city' => $job->getCity(),
                'beginAt' => $job->getCreatedAt(),
                'expireAt' => $job->getExpiredAt(),
                'type' => $job->getTypeid()->getTitle(),
                'exp' => $job->getExp()->getTitle() ,
                'presentation' => $job->getPresentation(),
                'resp' => $job->getResp() ,
                'req' => $job->getReq (),
                'slug'=> strtolower(str_replace($str,$rplc,$title))
             
            ];
        }

         $em = $this->getDoctrine()->getManager();
        $candidate = new Candidates();
        $application = new Applications();  
        $form2 = $this->createForm(Application::class,$candidate);
        $form2->handleRequest($request);
         $email=$form2->get('email')->getData();
        if($form2->isSubmitted() && $form2->isValid()) {
       $check = $em->getRepository(Candidates::class)->findBy(["email" => $email]);
              
            if($check) {
                $this->addFlash('error', 'Adresse existe déja');
             return $this->redirect($request->getUri());
            }
            else {
        $fname=ucwords($form2->get('fname')->getData());
        $lname=ucwords($form2->get('lname')->getData());
        $uploadedCV = $form2['cvfield']->getData();
        $candidate->setFname($fname);
        $candidate->setLname($lname);
        $candidate->setFullname($fname.' '.$lname); 

          
            $destination = $this->getParameter('kernel.project_dir').'/public/cv';
            if ($uploadedCV) {
                $CvName = 'cv'.uniqid().'.'.$uploadedCV->guessExtension();
                $newCvName = $CvName;
                $uploadedCV->move($destination,$newCvName);
                $candidate->setCv($CvName);
                
            }   
          
        $application->setJob($spontane);
        $application->setCandidate($candidate);

                
        $em->persist($candidate);
        $em->persist($application);
        $em->flush();

   }
  
}
          if(empty($jobsArray)){$jobsArray=[];}

        return $this->render('offres/index.html.twig', [
            'jobs' => $jobsArray ,'form' => $form-> createView(),'form2' => $form2-> createView()
        ]);
    }

    /**
     * @Route("/offre/{slug}/{id}", name="offre_get")
     */
     public function new(Jobs $job, Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    { 
        $em = $this->getDoctrine()->getManager();
        $candidate = new Candidates();
        $application = new Applications();
        $user = new User();  
        $form = $this->createForm(Application::class,$candidate);
        $form->handleRequest($request);
         $email=$form->get('email')->getData();
         $password=$form->get('newpassword')->getData();
        if($form->isSubmitted() && $form->isValid()) {
       $check = $em->getRepository(Candidates::class)->findBy(["email" => $email]);
             
            if($check) {
                $this->addFlash('error', 'Adresse existe déja');
             return $this->redirect($request->getUri());
            }
            else {
        $fname=ucwords($form->get('fname')->getData());
        $lname=ucwords($form->get('lname')->getData());
        $uploadedCV = $form['cvfield']->getData();
        $candidate->setFname($fname);
        $candidate->setLname($lname);
        $candidate->setFullname($fname.' '.$lname); 
        $user->setFname($fname);
        $user->setLname($lname);
        $user->setEmail($email);

        $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );
        $user->setImage('default.jpg');
        $role[]="ROLE_Candidate";
        $user->setRoles($role);

          
            $destination = $this->getParameter('kernel.project_dir').'/public/cv';
            if ($uploadedCV) {
             $CvName = 'cv'.uniqid().'.'.$uploadedCV->guessExtension();
                $newCvName = $CvName;
                $uploadedCV->move($destination,$newCvName);
                $candidate->setCv($CvName);
                
            }   
        $application->setJob($job);
        $application->setCandidate($candidate);

        $em->persist($user);       
        $em->persist($candidate);
        $em->persist($application);
        $em->flush();

   }
   $this->addFlash('successApply', 'Vous avez postulé avec succès');
}





  
          
          
          
       
     
        return $this->render('offres/offre.html.twig',['job'=>$job, 'form' => $form-> createView()]);
    }

       /**
     * @Route("/offre/{candidate}/{id}", name="apply")
     *  
     * @param Candidates $candidate
   
     */
    
     public function apply(Jobs $job,$candidate, Request $request): Response
    { 
        $em = $this->getDoctrine()->getManager();
        $application = new Applications();
     
    
            
        $application->setJob($job);
        $application->setCandidate($candidate);
         
    
        $em->persist($application);
        $em->flush();

  
   $this->addFlash('successApply', 'Vous avez postulé avec succès');

   



  
          
          
          
       
     
    }
}