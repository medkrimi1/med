<?php
namespace App\Controller;
use App\Entity\Jobs;
use App\Entity\User;
use App\Entity\Applications;
use App\Entity\Candidates;
use App\Entity\Cv;
use App\Entity\Country;
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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\File;



class IndexControlller extends AbstractController
{
private $manager;
public function __construct(JobsRepository $JobsRepository, EntityManagerInterface $manager)
{
$this->manager = $manager;
$this->JobsRepository = $JobsRepository;
}


/**
* @Route("/spontanee", name="spontanee")
*/
public function spontanee(JobsRepository $repository , Request $request ){

$em = $this->getDoctrine()->getManager();
$candidate = new Candidates();
$cv = new Cv(); 
$application = new Applications();  
$form2 = $this->createForm(Application::class,$candidate);
$form2->handleRequest($request);
$email=$form2->get('email')->getData();
if($form2->isSubmitted() && $form2->isValid()) {
$check = $em->getRepository(Candidates::class)->findBy(["email" => $email]);
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
$cv->setCv($CvName);
$cv->setCandidate($candidate);
}   
$application->setJob(null);
$application->setCandidate($candidate);
$candidate->setImage('default.jpg');
$em->persist($cv);        
$em->persist($candidate);
$em->persist($application);
$em->flush();
$this->addFlash('successFreeApply', 'Vous avez postulé avec succès');
}
if(empty($jobsArray)){$jobsArray=[];}
return $this->render('spontanee.html.twig', [
'form2' => $form2-> createView()
]);
}


/**
* @Route("/", name="offres")
*/
public function index(JobsRepository $repository , Request $request ){
$data=new SearchData1();
$form= $this->createForm(SearchForJob::class, $data);
$form->handleRequest($request) ;
$jobs=$this->manager->getRepository(Jobs::class)->SearchForJob($data);
$t=time();
$Userid=$t;
$em = $this->getDoctrine()->getManager();
$candidate = new Candidates();
$cv = new Cv(); 
$application = new Applications();  
$form2 = $this->createForm(Application::class,$candidate);
$form2->handleRequest($request);
$email=$form2->get('email')->getData();


if($form2->isSubmitted() && $form2->isValid()) {
$check = $em->getRepository(Candidates::class)->findBy(["email" => $email]);
$fname=ucwords($form2->get('fname')->getData());
$lname=ucwords($form2->get('lname')->getData());
$uploadedCV = $form2['cvfield']->getData();
$candidate->setFname($fname);
$candidate->setLname($lname);
$candidate->setId($Userid);
$candidate->setFullname($fname.' '.$lname); 
$destination = $this->getParameter('kernel.project_dir').'/public/cv';
if ($uploadedCV) {
$CvName = 'cv'.uniqid().'.'.$uploadedCV->guessExtension();
$newCvName = $CvName;
$uploadedCV->move($destination,$newCvName);
$cv->setCv($CvName);
$cv->setCandidate($candidate);
}   
$application->setJob(null);
$application->setCandidate($candidate);
$candidate->setImage('default.jpg');
$em->persist($cv);        
$em->persist($candidate);
$em->persist($application);
$em->flush();
return $this->redirectToRoute("offres");
}
if(empty($jobsArray)){$jobsArray=[];}
return $this->render('offres/index.html.twig', [
'jobs' => $jobs ,'form' => $form-> createView(),'form2' => $form2-> createView()
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
$cv = new Cv(); 
$t=time();
$Userid=$t;
 $ip = @json_decode(file_get_contents(
"http://www.geoplugin.net/json.gp?ip=" ));
$country_code=$ip->geoplugin_countryCode;
$country = $this->getDoctrine()->getRepository(Country::class)->findOneBy(["iso" => $country_code ]);

$form = $this->createForm(Application::class,$candidate);
$form->handleRequest($request);
$email=$form->get('email')->getData();
$password=$form->get('newpassword')->getData();
if($form->isSubmitted() && $form->isValid()) {
$check = $em->getRepository(Candidates::class)->findBy(["email" => $email]);
if($check) {
$this->addFlash('error', 'Adresse existe déja');
}
else {
$fname=ucwords($form->get('fname')->getData());
$lname=ucwords($form->get('lname')->getData());
$uploadedCV = $form['cvfield']->getData();
$cvType = $uploadedCV->guessExtension();
if($cvType=='pdf' or $cvType=='doc' or $cvType=='docx') {
$filesize=filesize($uploadedCV);
if ($filesize<5000000){
$candidate->setId($Userid);

$candidate->setFname($fname);
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
$application->setJob($job);
$application->setCandidate($candidate);
$application->setStatus('Non Traité');
$em->persist($user);       
$em->persist($candidate);
$em->persist($application);
$em->flush();
$this->addFlash('successApply', 'Vous avez postulé avec succès');
}
else {  $this->addFlash('error', 'taille de fichier ne doit pas dépasser 5 mo ,veuillez réessayer !');
return $this->redirect($request->getUri());}
}
else 
{
$this->addFlash('error', 'Format de cv non supporté, veuillez réessayer avec d\' autres formats (PDF,DOC,DOCS)');
return $this->redirect($request->getUri());
}
}
}
$form_guest= $this->createFormBuilder()
->add('fname', TextType::class, [
'required'=>true
])
->add('lname', TextType::class, [
"attr" => [
"class" => "form-control",
'required'=>true
]
])
->add('email', TextType::class, [
"attr" => [ 'required'=>true,
"class" => "form-control"
]
])
->add('cvfield', FileType::class, [
'required' => true,
'constraints' => [
new File([
'maxSize' => '5M',
'mimeTypes' => [
'application/pdf',
'application/x-pdf',
],
'mimeTypesMessage' => 'Please upload a valid PDF',
])
]
])
->add('cv', HiddenType::class, [
'mapped'=>false
])
->getForm();
$form_guest->handleRequest($request) ;
$fname=ucwords($form_guest->get('fname')->getData());
$lname=ucwords($form_guest->get('lname')->getData());
$emailGuest=$form_guest->get('email')->getData();
$uploadedCV = $form_guest['cvfield']->getData();
if($form_guest->isSubmitted() && $form_guest->isValid()) {
$emailcheck = $em->getRepository(Candidates::class)->findBy(["email" => $emailGuest]);
$check=$this->getDoctrine()->getRepository(Applications::class)->findBy(array('Candidate' => $emailcheck,'job' => $job)); 
if($check){ $this->addFlash('error', 'Vous avez déjà postulé à cette offre'); }
else {


$filesize=filesize($uploadedCV);

$t=time();
$Userid=$t;
$candidate->setId($Userid);
$candidate->setFname($fname);
$candidate->setLname($lname);

$candidate->setBdate(null);
$candidate->setEmail($emailGuest);
$candidate->setFullname($fname.' '.$lname); 
$candidate->setImage('default.jpg');
$destination = $this->getParameter('kernel.project_dir').'/public/cv';
if ($uploadedCV) {
$CvName = 'cv'.uniqid().'.'.$uploadedCV->guessExtension();
$newCvName = $CvName;
$uploadedCV->move($destination,$newCvName);
$cv->setCv($CvName);
$cv->setCandidate($candidate);
$em->persist($cv); 
}   
$application->setJob($job);
$application->setCandidate($candidate);
$application->setStatus('Non Traité');
$em->persist($candidate);
$em->persist($application);
$em->flush();
$this->addFlash('successApply', 'Vous avez postulé avec succès');



}
}
$expired=$job->getExpiredAt()->format('Y-m-d');
$skills=[];
foreach($job->getSkills() as $skill)
{ 
$skills[]=[
'title'=>$skill->getTitle() ,
] ;
}
$jobsArray[] = [
'id' => $job->getId(),
'title' => $job->getTitle(),
'image' => $job->getImage(),
'cover' => $job->getCover(),
'country' => strtolower($job->getCountry()->getName()),
'city' => $job->getCity(),
'beginAt'=>$job->getCreatedAt(),
'expireAt' => $job->getExpiredAt(),
'type' => $job->getTypeid()->getTitle(),
'exp' => $job->getExp()->getTitle() ,
'presentation' => $job->getPresentation(),
'resp' => $job->getResp() ,
'req' => $job->getReq (),
'fname' => $job->getUser()->getFname(),
'lname' => $job->getUser()->getLname(),
'today'=>strtotime(date('Y/m/d')),
'skills'=>$skills,
'expire'=>strtotime($expired),
];
return $this->render('offres/offre.html.twig',['job'=>$jobsArray, 'form' => $form-> createView(), 'form_guest' => $form_guest-> createView()]);
}


/**
* @Route("/offre/apply/{id}/{idc}", name="apply") 
* @param Jobs $job
* @return RedirectResponse
*/
public function apply(Jobs $job,$idc, Request $request): Response
{ 
$em = $this->getDoctrine()->getManager();
$application = new Applications();
$candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($idc);
$slug=$job->getSlug();
$jobid=$job->getId();
$checks=$this->getDoctrine()->getRepository(Applications::class)->findBy(array('Candidate' => $candidate,'job' => $job));
$checkCv=$this->getDoctrine()->getRepository(CV::class)->findBy(['candidate' => $candidate]); 

foreach ($checks as $check ) {
   $status=$check->getStatus();
}

if($checks ){
if($status!='annulé' )
 {   
 $this->addFlash('error', 'Vous avez déjà postulé à cette offre');}
 else {if ($checkCv) {
$application->setJob($job);
$application->setCandidate($candidate);
$application->setStatus('Non Traité');
$em->persist($application);
$em->flush();
$this->addFlash('successApply', 'Vous avez postulé avec succès');
}
else {
$this->addFlash('error', 'Vous devez ajouter au moins un cv pour postuler à une offre');
} }
}
else{
if ($checkCv) {
$application->setJob($job);
$application->setCandidate($candidate);
$application->setStatus('Non Traité');
$em->persist($application);
$em->flush();
$this->addFlash('successApply', 'Vous avez postulé avec succès');
}
else {
$this->addFlash('error', 'Vous devez ajouter au moins un cv pour postuler à une offre');
}
}
return $this->redirectToRoute("offre_get",["slug"=>$slug,"id"=>$jobid]);
}
/**
* @Route("candidat/offre/apply/{id}", name="apply_login") 
* @param Jobs $job
* @return RedirectResponse
*/
public function apply_login(Jobs $job,$id): Response
{ 


return $this->render('apply.html.twig',['id'=>$id]);
}


}


