<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EditUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('courriel')
            ->add('telephone')
            ->add('nomutilisateur')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'admin' => 'ROLE_ADMIN',
                    'concessionnaire' => 'ROLE_CONCESSIONNAIRE',
                    'marchand' => 'ROLE_MARCHAND',
                    'partenaire' => 'ROLE_PARTENAIRE',
                    'agent' => 'ROLE_AGENT',
                 

                ],
                'expanded' => true,
                'multiple' => true,
                'label' => 'Rôles' 
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
