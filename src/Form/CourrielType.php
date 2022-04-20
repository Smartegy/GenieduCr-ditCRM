<?php

namespace App\Form;

use App\Entity\Courriel;
use App\Entity\Leads;
use App\Entity\Modele;
use App\Entity\Modeleemail;
use App\Repository\ModeleemailRepository;

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
       

        ->add('text')
       ->add('sujet')
 
           // ->add('sujet' )*/


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


            ));

            /*
                ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {

                    $model = $event->getData();
                    $form = $event->getForm();


                    if (!$model) {
                        return;
                    }
            

                
                if (   ( isset($model['modele'])) && ( $model['modele'])  ) {
                    $form->add('text' ,TextareaType::class) ;
                     $form->add('sujet' ,TextareaType::class  ) ;
                } else {
                    unset($model['sujet']);
                    $event->setData($model);
                }

            })
            ->getForm();
                
             */
            //   FormEvents::PRE_SET_DATA,function ( FormEvent $event ) {
            /* $builder->get('modele')->addEventListener(FormEvents::SUBMIT, 
                function (FormEvent $event) {
                   // $form1 = $event->getForm();
                   //$form1 = $event->getForm()->getParent();
                   $form1 = $event->getForm();
 
                   $data = $event->getForm();
                   $form1 = $event->getForm()->getParent();
                  // dd($form1) ;die ; 
                  // $form1->get('modele')->getData()->getSujetemail();
                    
                    // dd($event->getData());die;
                     //$model = $event->getData()['modele']  ;/*
                     //dd($event->getData());die;

                     //dd($model);die;
                    
                      //$text = $model === null ? [] : $this->ModeleemailRepository->findOneById( $model ->getId())->getMessage();
                      
                      //$sujet=$model->getSujetemail(); 
                  //  $number=intval($model);
                        

                  $sujet= $form1->get('modele')->getData()->getSujetemail();
                  $text= $form1->get('modele')->getData()->getMessage();
                      
                  
                    /*  $event->getForm()->add('text' ,null , array(
                        'label' =>' Contenu de Email ',
                        'empty_data' => $form1->get('modele')->getData()->getSujetemail(),
                       
                         'required' => false,
                        'attr' => [ 'class' => 'form-control'     ] )  ) ;
                        $event->getForm()->add('sujet' ,null , array(
                          'label' =>' Titre  ',
                          'empty_data' => $form1->get('modele')->getData()->getMessage() ,
                         
                           'required' => false,
                          'attr' => [ 'class' => 'form-control'     ] )  ) ;

                        
                       // $c = $event->getData()['modele'] ?? null;
                       // $formOptions2 = $model === null ? [] : $this->ModeleemailRepository->findOneById( $model ->getId())->getMessage();
                        //$formOptions1 $sujet= $model === null ? [] : $this->ModeleemailRepository->findOneById( $model ->getId())->getSujetemail();
                       
                        $form = $event->getForm();
                          $form->add('sujet', TextareaType::class, $sujet); 
                          $form->add('text', TextareaType::class, $text );  

                     
                  });*/
                
    


            
      
            
                           
  


      /*  $builder->get('modele')->addEventListener(FormEvents::PRE_SUBMIT, 
        function (FormEvent $event) {
                 $form1 = $event->getForm();
                   
                 $data = $event->getForm();
                 $form1 = $event->getForm()->getParent();
                 $c = $event->getData()['modele'];
                 dd($c) ;die ; 
                   
                 $form1->add('sujet' ,null , array(
                    'label' => 'Sujet',
                    'empty_data' => $c ->getSujetemail()
                )) ;

                $form1->add('text' ,TextareaType::class , array(
                    'label' => 'texte message',
             
                    'empty_data' => $c ->getMessage() ,

                   )) ;

                $modele = $data->getData();

                
             
 
        })
         
        ;*/


       /* $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $user = $event->getData();

          
            $form = $event->getForm();
    
            $f="hello imen";
            // checks whether the user has chosen to display their email or not.
            // If the data was submitted previously, the additional value that is
            // included in the request variables needs to be removed.
            $form->add('sujet' ,null , array(
                'label' => 'Sujet',
              
            )) ;
            $form->add('sujet' ,null , array(
                'label' => 'text',
               
            )) ;
            $event->setData(['sujet'=>$f ]) ; 
            $event->setData(['text'=>$f ]) ; 
        })
        ->getForm();*/





    
    


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
