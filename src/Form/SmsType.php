<?php

namespace App\Form;

use App\Entity\Leads;
use App\Entity\Modeleemail;
use App\Entity\Modelesms;
use App\Entity\Sms;
use App\Repository\ModeleemailRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class SmsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


    
        $builder
            ->add('recepteur')
            ->add('emetteur' )
      
            ->add('text',TextareaType::class)
        
            ->add('lead', EntityType::class,array(
                'class' => Leads ::class,
                'choice_label' => 'nom',
                'required' => false,


            ))
          

            ->add('modele', EntityType::class,array(
                'class' => Modeleemail ::class,
                'choice_label' => 'titre',
                'required' => true,
                'multiple' => false ,
                'query_builder' => function(ModeleemailRepository $repo)
                {
                    $modele  = $repo->FindBySms(1);
                    return $modele;
                    },
              
                 
                'placeholder' => 'choisir un modele'
                      
                    ))
               
        ;







       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sms::class,
        ]);
    }
}
