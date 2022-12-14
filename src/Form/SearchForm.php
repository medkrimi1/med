<?php

namespace App\Form;
use App\Entity\TypeJobs;
use App\Entity\Experience;
use App\Data\SearchData;
use App\Entity\Skills;
use App\Entity\Jobs;
use App\Entity\Country;
use App\Entity\User;
use App\Repository\JobsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
              ->add('Title', EntityType::class, [
               'class' => Jobs::class,
               
               'required'=> false,
                  'query_builder' => function (EntityRepository $er) {
              return $er->createQueryBuilder('u')
             ->Where('u.id != :check')
              ->setParameter('check', 609679)
               ->andWhere('u.status != :status')
              ->setParameter('status', 'nonActif');
    },
             
            ])
              

            ->add('TypeJobs', EntityType::class , [
            'label' => false ,
            'required' => false ,
            'class' => TypeJobs::class,
            'multiple' => true

       ]) 
          ->add('Experience', EntityType::class , [
            'label' => false ,
            'required' => false ,
            'class' => Experience::class,
            'multiple' => true


            
       ])
           ->add('Country', EntityType::class , [
            'label' => false ,
            'required' => false ,
            'class' => Country::class,
            'multiple' => true

            
       ])
                ->add('employer', EntityType::class, [
               'class' => User::class,
                      'choice_label' => 'fname',
               'required'=> false,'placeholder' =>'Tous' ,
                  'query_builder' => function (EntityRepository $er) {
              return $er->createQueryBuilder('u')
             ->Where('u.roles != :roles')
              ->setParameter('roles', '["ROLE_Candidate"]')
             ;
    },
         
            ])
           ->add('Skills', EntityType::class , [
            'label' => false ,
            'required' => false ,
            'class' => Skills::class,
            'multiple' => true

            
       ])
           
                ->add('startdate', DateType::class, [
    
    
    'attr' => ['class' => 'js-datepicker'],
    'label' => false ,
     'format' => 'ddMMyyyy',
 'required' => true ,
    'input' => 'string',
    'input_format' => 'Y-m-d' // ajouté en 4.3
])
                   ->add('enddate', DateType::class, [
    
   
    'attr' => ['class' => 'js-datepicker'],
    'label' => false ,
    'format' => 'ddMMyyyy',
    'required' => true ,
    'input' => 'string'])

        ->add('status',ChoiceType::class, [
        'choices'=>['Actif'=>'Actif','Expiré'=>'Expiré'],
 'placeholder' =>'Tous' ,
            'required' => false ,
           

        ])             
            ;
    }






   public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }


    public function getblockPrefix()

    {
       return '' ;

    }
}
