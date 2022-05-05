<?php

namespace App\Form;

use App\Entity\Courriel;
use App\Entity\Leads;
use App\Entity\Modele;
use App\Entity\Modeleemail;
use App\Repository\ModeleemailRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;


use Symfony\Component\OptionsResolver\OptionsResolver;

class CourrielType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
     
            ->add('recepteur', EmailType::class, [
                'label' => 'mail lead',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('emetteur', EmailType::class, [
                'label' => 'mail user',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
       

        ->add('sujet')
       ->add('text',CKEditorType::class)
 
           // ->add('sujet' )*/


            ->add('modele', EntityType::class,array(
                'class' => Modeleemail ::class,
                'choice_label' => 'titre',
                'required' => true,
                'multiple' => false , 
                'placeholder' => 'choisir un modele',
                ))
              

            ->add('lead', EntityType::class,array(
                'class' => Leads ::class,
                'choice_label' => 'nom',
                'required' => false,


            ));


    }   
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Courriel::class,
        ]);
    }



    public function modeleliste ( ModeleemailRepository $modele_e) : void
    {


    }
}
