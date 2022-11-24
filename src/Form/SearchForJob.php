<?php

namespace App\Form;

use App\Entity\Jobs;
use App\Entity\TypeJobs;
use App\Data\SearchData1;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class SearchForJob extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
              ->add('Name', TextType::class , [
            'label' => false ,
            'required' => false ,
            'attr' => [
                'placeholder' =>'Nom d\'offre' ,
             'csrf_protection' => false


            ]
       ])
             
           
              ->add('TypeJobs', EntityType::class, [
                'class' => TypeJobs::class,
                 'label' => false ,
            'required' => false ,

                'placeholder' => 'Tous',
             
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
         $resolver->setDefaults([
            'data_class' => SearchData1::class,
            'method' => 'GET',
            'csrf_protection' => false,
            
        ]);
    }
     public function getblockPrefix()

    {
       return '' ;

    }
}
