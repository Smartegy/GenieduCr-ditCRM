<?php

namespace App\Form;

use App\Entity\Courriel;
use App\Entity\Leads;
use App\Entity\Modeleemail;
use App\Repository\ModeleemailRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
       

          ->add('text', TextareaType::class, [
                'label' => 'texte message',
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
 
            ->add('sujet' )


            ->add('modele', EntityType::class,array(
                'class' => Modeleemail ::class,
                'choice_label' => 'titre',
                'required' => true,
                'multiple' => false , 
                ))

 
   
            ->add('lead', EntityType::class,array(
                'class' => Leads ::class,
                'choice_label' => 'nom',
                'required' => false,


                ))

                
            
        ;


    
    
;

      /*     function onPostSubmit(FormEvent $event) {
            $form1 = $event->getForm();

              dump($event) ;
              dd($form) ; die ; 
                   this would be your entity, i.e. SportMeetup
                   $data = $event->getForm();
                   
                   $form1 = $event->getForm()->getParent();
                   $form1->add('sujet' ,null , array(
   
                       'label' => 'Sujet',
                       'empty_data' => $form1->get('modele')->getData()->getSujetemail()
                   )) ;
   
                    
                   $form1->add('text' ,TextareaType::class , array(
                       'label' => 'texte message',
                       'required' => false,
                       'empty_data' => $form1->get('modele')->getData()->getMessage() ,
   
                       'attr' => [
                           'class' => 'form-control'
                       ]
                   )) ;
   
                      }

        $builder->get('modele')->addEventListener(FormEvents::POST_SUBMIT, 
        function (FormEvent $event) {
            $form1 = $event->getForm();
 
                $data = $event->getForm();
                
                $form1 = $event->getForm()->getParent();
               dd($form1) ;die ; 
                $form1->add('sujet' ,null , array(

                    'label' => 'Sujet',
                    'empty_data' => $form1->get('modele')->getData()->getSujetemail()
                )) ;

                 
                $form1->add('text' ,TextareaType::class , array(
                    'label' => 'texte message',
                    'required' => false,
                    'empty_data' => $form1->get('modele')->getData()->getMessage() ,

                    'attr' => [
                        'class' => 'form-control'
                    ]
                )) ;

                $modele = $data->getData();

              
             
 
        });

     */
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
