<?php

namespace App\Form;

use App\Entity\Vendeurr;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditVendeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actif',CheckboxType::class,[
                'label_attr' => [
                    'class' => 'checkbox-switch'
                ],'required' => false

            ])
            ->add('authenenvoiemail',CheckboxType::class,[
                'label_attr' => [
                    'class' => 'checkbox-switch'
                ],'required' => false

            ])
            ->add('authenenvoisms',CheckboxType::class,[
                'label_attr' => [
                    'class' => 'checkbox-switch'
                ],'required' => false

            ])
            ->add('utilisateur', EditUtilisateurType::class)

        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vendeurr::class,
        ]);
    }
}
