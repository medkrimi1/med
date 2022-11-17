<?php
namespace App\Controller;
use App\Entity\Candidates;
use App\Repository\CandidatesRepository;
use App\Entity\Cv;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class CandidateCvController extends AbstractController
{
    
/**
* @Route("/candidat/cv/{id}", name="my_cv")
*/
public function index($id,Request $request){
$candidate=$this->getDoctrine()->getRepository(Candidates::class)->find($id);

$cvs=$this->getDoctrine()->getRepository(Cv::class)->findBy(["candidate" => $candidate]);
$cv = new Cv();  
$form= $this->createFormBuilder($cvs)
->add('cvfield', FileType::class, [
'mapped' => false,
'required'=>false,     'attr' => [
'accept' => "application/pdf"
]
])
->add('cv', HiddenType::class, [    ])
->getForm();
$form->handleRequest($request) ;
$em = $this->getDoctrine()->getManager();
$uploadedCV = $form['cvfield']->getData();
if ($form->isSubmitted() && $form->isValid()) {
    
if ($uploadedCV) {
$CvName = 'cv'.uniqid().'.'.$uploadedCV->guessExtension();
$destination = $this->getParameter('kernel.project_dir').'/public/cv';
$uploadedCV->move($destination,$CvName);
$cv->setCv($CvName);
$cv->setCandidate($candidate);
}

$em->persist($cv);
$em->flush();
  $this->addFlash('success', 'le cv a été Ajoutée avec succès!');
return $this->redirect($request->getUri());
}

$form_delete= $this->createFormBuilder($cvs)

->getForm();
$form_delete->handleRequest($request) ;

if ($form_delete->isSubmitted() && $form_delete->isValid()) {
    $em->remove($cvs);
       $em->flush();
}

return $this->render('candidat/cv/index.html.twig',['form'=>$form->createView(),'form_delete'=>$form_delete->createView(),'cvs'=>$cvs]);
}



  /**
     * @Route("candidat/cv/{idc}/{id}/delete", name="cv_delete")
     * @param Cv $cv
     * @return RedirectResponse
     */
    public function delete(Cv $cv,$idc ,Request $request): RedirectResponse
    {  
        $em = $this->getDoctrine()->getManager();
        $em->remove($cv);
        $em->flush();
       
 return $this->redirectToRoute("my_cv",["id"=>$idc]);
    }
}