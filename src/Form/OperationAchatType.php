<?php

namespace App\Form;

use App\Entity\Leads;
use App\Entity\OperationAchat;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationAchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
      
            ->add('modele')
          
            ->add('marque')
            ->add('prix_achat')
            ->add('prix_vente')
            ->add('numserie')
            
         



            ->add('leads', EntityType::class,array(
                'class' => Leads::class,
                'choice_label' => 'nom',
                'required' => false,
                'label' => false ,
                'mapped'=>true,
                  
                )) 
          
                ->add('vehicule', EntityType::class,array(
                    'class' => Vehicule::class,
                    'choice_label' => 'vin',
                    'required' => false,
                    'label' => false ,
                    'mapped'=>true,
                      
                    )) 
       

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OperationAchat::class,
        ]);
    }
}
