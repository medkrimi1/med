<?php

namespace App\Form;
use App\Entity\Professions;
use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
class SearchcandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
     {
        $builder
            ->add('c', TextType::class , [
            'label' => false ,
            'required' => false ,
            'attr' => [
                'placeholder' =>'Rechercher' ,
             'csrf_protection' => false


            ]
       ])
            ->add('Professions', EntityType::class , [
            'choice_label' => 'title',

                'placeholder' => 'Select',
            'class' => Professions::class,
            "class" => "form-check-input",
           
            'multiple' => true


            
       ]) ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidates::class,
        ]);
    }
}
