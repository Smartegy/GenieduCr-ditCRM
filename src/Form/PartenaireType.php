<?php
namespace App\Form;
use App\Entity\Partenaire;
use App\Entity\Agent;
use App\Entity\Vendeurr;
use App\Form\UtilisateurType;
use App\Form\MediasType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Repository\AgentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class PartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class,[
                'required' => false])
            
            
                ->add('actif',CheckboxType::class,[
                    'label_attr' => [
                        'class' => 'checkbox-switch'
                    ],'required' => false
                ])
            

            ->add('utilisateur', UtilisateurType::class ,  [ 'required' => True     ] )
            
            
            ->add('agents', EntityType::class,array(
                'class' => Agent::class,
                'choice_label' => 'utilisateur.nom',
                    'expanded' => false,
                    'multiple' => true
                  
                ))
                ->add('vendeurrs', EntityType::class,array(
                    'class' => Vendeurr::class,
                    'choice_label' => 'utilisateur.nom', 

                        
                        'expanded' => false,
                        'multiple' => true,
                        'mapped' => true
                    ))
                    
                    
                ->add('media', MediasType::class)
            
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partenaire::class,
        ]);
    }
}


















