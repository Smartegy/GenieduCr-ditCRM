<?php

namespace App\Form;

use App\Entity\Modeleemail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeleemailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('sujetemail')
            ->add('mail')
            ->add('sms')
      

            ->add('message', TextType::class, array(
                'attr' => array('maxlength' => 4294967292)))
        
         ->add('user', HiddenType::class, [
                'data' => 'agent',
            ]);
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Modeleemail::class,
        ]);
    }
}
