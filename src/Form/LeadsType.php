<?php

namespace App\Form;

use App\Entity\Administrateur;
use App\Entity\Agent;
use App\Entity\Concessionnaire;
use App\Entity\Leads;
use App\Entity\Marchand;
use App\Entity\Modeleemail;
use App\Entity\Modelesms;
use App\Entity\OperationAchat;
use App\Entity\Operations;
use App\Entity\Partenaire;
use App\Entity\SourcesLeads;
use App\Entity\Status;
use App\Entity\Statusleads;
use App\Entity\Utilisateur;
use App\Entity\Vendeurr;
use App\Repository\AgentRepository;
use App\Repository\ModeleemailRepository;
use App\Repository\ModelesmsRepository;
use App\Repository\PartenaireRepository;
use App\Repository\UtilisateurRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\ChoicesToValuesTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class LeadsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,  array $options): void
    {
        $builder
            ->add('nom')
            ->add('telephone')
            ->add('courriel')
    
            ->add('commantaire')
            ->add('numserie')
            ->add('budgetmonsuelle')
            ->add('datenaissance',
            'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
            'choices' => $this->getYears(1920)
        ])
            ->add('statutprofessionnel')
            ->add('revenumensuel')
           
            ->add('nomcompagnie')
            ->add('occupationposte')
            ->add('adressedomicile')
            ->add('locationproprietaire')
            ->add('paiementmonsuel')
    
            
            ->add('rappel', null, [
                'required'   => false,
               
            ]) 
            ->add('sujet')
            ->add('text')
            ->add('textsms')
            ->add('marque')
            ->add('modele')
       
            ->add('type')
            ->add('isCLient')
           


      ->add('agent', EntityType::class,array(
                'class' => Agent::class,
                'choice_label' => 'utilisateur.nomutilisateur',
                'required' => false,
                'label' => false ,
                'mapped'=>true,
                  
                ))


                ->add('operationAchats', EntityType::class,array(     	
                    'class' => OperationAchat::class,
                    'choice_label' => 'numserie',
                    'required' => false,
                    'multiple' => true ,
                    'mapped'=>true,
                      
                    ))
                ->add('vendeurr', EntityType::class,array(
                    'class' => Vendeurr::class,
                    'choice_label' => 'utilisateur.nomutilisateur',
                    'required' => false,
                    'label' => false ,
                    'mapped'=>true,
                      
                    ))
  

                  ->add('partenaire', EntityType::class,array(
                        'class' => Partenaire ::class,
                        'choice_label' => 'utilisateur.nomutilisateur',
                        'required' => false,
                        'label' => false ,
                        'mapped'=>true,
                        ))
                       
                            ->add('concessionnaire', EntityType::class,array(
                                'class' => Concessionnaire ::class,
                                'choice_label' => 'concessionnairemarchand.utilisateur.nomutilisateur',
                                'required' => false,
                                'label' => false ,
                                'mapped'=>true,
                
                                ))
                               ->add('marchand', EntityType::class,array(
                                    'class' => Marchand ::class,
                                    'choice_label' => 'concessionnairemarchand.utilisateur.nomutilisateur',
                                    'required' => false,
                                    'label' => false ,
                                    'mapped'=>true,
                                        
                               ))
                                    
                                    ->add('administrateur',EntityType::class,array(
                                        
                                        'class' => Administrateur::class,
                                        'choice_label' => 'utilisateur.nomutilisateur',
                                        'required' => false,
                                        'label' => false ,
                                        'mapped'=>true,
                                        
                          
                                    ))
                                   
                                  
                                  
                                         
                                        



                                        ->add('statusvehicule', ChoiceType::class,
                                            [
                                                'label' => 'Status :',
                                                'required' => false,
                                                'choices' => array(
                                                    'usag??' => 'usag??',
                                                    'neuf' => 'neuf',
                                              
                                                ),
                                                'placeholder' => ' ',
                                            ]
                                        )



            ->add('statusleads',EntityType::class,array(
                'class' => Statusleads::class,
                'choice_label' => 'nom',
  
            ))

            ->add('sourcesleads',EntityType::class,array(
                'class' => SourcesLeads::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => false ,
                'mapped'=>true,
  
            ))

            ->add('modele_vente')
            ->add('marquevente')
            
            ->add('anne_vente',
            'Symfony\Component\Form\Extension\Core\Type\ChoiceType',[
            'choices' => $this->getYears(1960),
            'required' => false , 
            'placeholder' => ' '
            
        ])
            ->add('kilometrage_vente')
            ->add('etatcarossrie_vente')
   
            ->add('remplacer_vehicule')
         
            ->add('etatpneus_vente')
            ->add('prix_achat')
            ->add('prix_vente')
            ->add('annee_revenu')
            ->add('mois_revenu')
            ->add('annee_habitation')
            ->add('mois_habitation')
       
            
           
        ;
    }


    private function getYears($min, $max='current')
    {
         $years = range($min, ($max === 'current' ? date('Y-M-D') : $max));

         return array_combine($years, $years);
    }
  

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Leads::class,
           
        ]);
    }
}
