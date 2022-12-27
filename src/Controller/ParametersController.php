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
    $users = $this->manager->getRepository(User::class)->findAll();
$user=$this->getDoctrine()->getRepository(User::class)->find($id);
 $l=$user->getEmail();
$em = $this->getDoctrine()->getManager();
$form_pass= $this->createFormBuilder()
->add('newpassword',PasswordType::class, [ 
'mapped' => false,
])
->add('repeatedpassword',PasswordType::class, [
'mapped' => false,
])
->add('oldpassword',PasswordType::class, [
'mapped' => false,
])
->getForm();
$form_pass->handleRequest($request) ;
$newpassword=$form_pass->get('newpassword')->getData();
$oldpassword=$form_pass->get('oldpassword')->getData();
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
$form= $this->createFormBuilder($user)
->add('fname')
->add('lname')
 ->add('mail',EmailType::class,[ 'mapped' => false,'attr' => ['value' => $l]])
->getForm();
$form->handleRequest($request) ;
$fname=$form->get('fname')->getData();
$lname=$form->get('lname')->getData();
$mail=$form->get('mail')->getData();
foreach ($users as $userr){
$userArray[] = strtolower(str_replace(' ', '',$userr->getEmail()));
}
if ($form->isSubmitted() && $form->isValid()) {

     if($mail!=$l){ 
if(in_array(strtolower(str_replace(' ', '',$form->get('mail')->getData())), $userArray)){
$this->addFlash('error', 'Adresse existe déja');
}

else {
$user->setEmail($mail);
$em->flush();
$this->addFlash('success', 'L\'adresse été modifié avec succès!');
}
return $this->redirect($request->getUri());
}
else {
$em->persist($user);
$em->flush();
// $this->addFlash('success', 'le nom été modifié avec succès!');
return $this->redirect($request->getUri());
}
}

return $this->render('dashboard/parameters/profile.html.twig',['form' => $form-> createView(),'form_pass' => $form_pass-> createView()]);
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