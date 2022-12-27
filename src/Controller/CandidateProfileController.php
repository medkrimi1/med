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
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
$Year=date('Y')-13;
$maxBirth=$Year."-"."01"."-"."01" ;

$currentBdate=$candidate->getBdate();
$form_image= $this->createFormBuilder()
->add('imagefield', FileType::class, [
'mapped' => false,  'required'=>false, 'attr' => ['accept' =>"image/*"  ]   ])
->add('image', HiddenType::class)
->getForm();
$form_image->handleRequest($request) ;
$em = $this->getDoctrine()->getManager();
$users = $this->manager->getRepository(User::class)->findAll();
$uploadedImage = $form_image['imagefield']->getData();
$image=$candidate->getImage();
$destination = $this->getParameter('kernel.project_dir').'/public/images/users';
if ($form_image->isSubmitted() && $form_image->isValid()) {
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
$em->flush();
return $this->redirectToRoute("candidate_profile",["id"=>$id]);
$this->addFlash('success', 'Votre photo de profil a été mis à jour');
}
$form_cover= $this->createFormBuilder()
->add('coverfield', FileType::class, [
'mapped' => false,  'required'=>false, 'attr' => ['accept' =>"image/*"  ]   ])
->add('cover', HiddenType::class)
->getForm();
$form_cover->handleRequest($request) ;
$em = $this->getDoctrine()->getManager();
$users = $this->manager->getRepository(User::class)->findAll();
$cover=$candidate->getCover();
$destination = $this->getParameter('kernel.project_dir').'/public/images/users';
if ($form_cover->isSubmitted() && $form_cover->isValid()) {
$uploadedCover = $form_cover['coverfield']->getData();
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
return $this->redirectToRoute("candidate_profile",["id"=>$id]);
$this->addFlash('success', 'Votre photo de couverture a été mis à jour');
}

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

 ->add('bdate', DateType::class, [ 
        'widget' => 'single_text',
         'required'=>true,
         'mapped'=>false,


                  'attr' => ['class' => 'js-datepicker text-center text-primary','value'=>$currentBdate,'max'=>$maxBirth, 'min'=>'1930-01-01'],  
        ])

->add('pcode')
->add('phonecode',TextType::class, ['mapped'=>false,'required'=>false ] )
->add('mail',EmailType::class,[ 'mapped' => false,'attr' => ['value' => $l]])
->add('zip')
->add('city')
->add('address')
->add('site',TextType::class,[
'attr'=>  ['pattern'=>'(https://|http://).([a-zA-Z0-9_.-_/]{5,30})','placeholder'=>'https://exemple.com'],'required'=>false ])
->add('fb',TextType::class,[
'attr'=>  ['pattern'=>'(https://|http://)(www.|)facebook.([a-z]{2,3})/([a-zA-Z0-9_.-]{5,30})','placeholder'=>'https://facebook.com/user'] ,'required'=>false])
->add('tw',TextType::class,[
'attr'=>  ['pattern'=>'(https://|http://)(www.|)twitter.([a-z]{2,3})/([a-zA-Z0-9_.-]{5,30})','placeholder'=>'https://twitter.com/user'] ,'required'=>false])

->add('ln',TextType::class,[
'attr'=>  ['pattern'=>'(https://|http://)(www.|)linkedin.([a-z]{2,3})/([a-zA-Z0-9_.-]{5,30})','placeholder'=>'https://linkedin.com/user'] ,'required'=>false])
->getForm();
$form->handleRequest($request) ;

 


  
// Use JSON encoded string and converts
// it into a PHP variable
$ipdat = @json_decode(file_get_contents(
    "http://www.geoplugin.net/json.gp?ip=" ));
$ip=$ipdat->geoplugin_countryCode;
$em = $this->getDoctrine()->getManager();
$users = $this->manager->getRepository(User::class)->findAll();
$fname=$form->get('fname')->getData();
$lname=$form->get('lname')->getData();

$phone=$form->get('phone')->getData();
$phonecode=$form->get('phonecode')->getData();

$destination = $this->getParameter('kernel.project_dir').'/public/images/users';
foreach ($users as $userr){
$userArray[] = strtolower(str_replace(' ', '',$userr->getEmail()));
}
$email=$form->get('mail')->getData();
if ($form->isSubmitted() && $form->isValid()) {
    if ($phone){
 $candidate->setPhone('+'.$phonecode.' '. str_replace(' ','', $phone));  
  }

  else 
{$candidate->setPhone(null); }

  $bdate=$form->get('bdate')->getData();
  
 if($bdate!=null){
          $Bdate=$bdate->format('Y-m-d') ; 
          $candidate->setBdate($Bdate);}
          else {
            $candidate->setBdate(null); 
          }
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
return $this->render('candidat/profil/index.html.twig',[ 'ip'=>$ip ,'candidate'=>$candidate, 'form'=>$form->createView(),'form_image'=>$form_image->createView(),'form_cover'=>$form_cover->createView()]);
}
/**
* @Route("/candidat/profil/{id}/image/delete", name="candidate_image_delete")
*/
public function deleteAvatar($id , request $request ){
$candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($id);
$user=$this->getDoctrine()->getRepository(User::class)->find($id);
$form= $this->createFormBuilder()
->getForm();
$id=$user->getId();
$form->handleRequest($request) ;
$em = $this->getDoctrine()->getManager();
$user->setImage('default.jpg');
$candidate->setImage('default.jpg');
$em->persist($user);
$em->persist($candidate);
$em->flush();
return $this->redirectToRoute("candidate_profile",["id"=>$id]);
}
/**
* @Route("/candidat/profil/{id}/cover/delete", name="candidate_cover_delete")
*/
public function deleteCover($id , request $request ){
$candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($id);
$form= $this->createFormBuilder()
->getForm();
$form->handleRequest($request) ;
$em = $this->getDoctrine()->getManager();
$candidate->setCover('');
$em->persist($candidate);
$em->flush();
return $this->redirectToRoute("candidate_profile",["id"=>$id]);
}
}