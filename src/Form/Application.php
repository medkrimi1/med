<?php

namespace App\Form;

use App\Entity\Candidates;
use App\Entity\Professions;
use App\Entity\Country;
use App\Entity\Candidatexp;
use App\Entity\Jobs;
use App\Entity\Skills;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class Application extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('fname', TextType::class, [
                "attr" => [ 'required'=>true,
                    "class" => "form-control"
                ]
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
                ->add('titre', EntityType::class, [
                'class' => Professions::class,
                'choice_label' => 'title',
                 'required'=>true,
                'placeholder' => 'Select',
             
            ])
                
                  
                        ->add('cvfield', FileType::class, [
                'mapped' => false,
                'required'=>false,     'attr' => [
             'accept' => "application/pdf"

         ]
            ])
                 ->add('cv', HiddenType::class, [
                
            ])
              ->add('newpassword',PasswordType::class, [ 
                'mapped' => false,
               
            ])
       ->add('repeatedpassword',PasswordType::class, [
                'mapped' => false,
               
            ])
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidates::class,
        ]);
    }
}
