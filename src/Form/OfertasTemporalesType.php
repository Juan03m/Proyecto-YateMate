<?php

namespace App\Form;

use DateTime;
use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
class OfertasTemporalesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $marinas=['Norte','Centro','Sur','Este','Oeste','Delta','Bahia','Atlantico'];

        $tamaños=['Chica','Mediana','Grande'];
    
        $marinasAsociativo = array_combine($marinas, $marinas);
        $tamañosAsociativo = array_combine($tamaños, $tamaños);
        

        $builder
        ->add('desde', TypeDateType::class, [
            'widget' => 'single_text',
            'html5' => true,
            'attr' => [
                'min' => (new \DateTime())->format('Y-m-d'), // Establecer mínimo como fecha actual en formato Y-m-d
            ],
            'constraints' => [
                new NotBlank([
                    'message' => 'Por favor ingresa una fecha de inicio',
                ]),
            ],
        
            ])
            ->add('hasta', TypeDateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'), // Establecer mínimo como fecha actual en formato Y-m-d
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingresa una fecha de inicio',
                    ]),
                ],
            
                ])
            ->add('tamano',ChoiceType::class, [
                'choices' => $tamañosAsociativo,
                'required'=>false,
                'placeholder' => 'Tamaño',
                
            ])
            ->add('marinas',ChoiceType::class, [
                'choices' =>$marinasAsociativo,
                'required'=>false,
                'placeholder' => 'Marinas',
                'label' => 'marinas'
            ])



            ->add('Filtrar',SubmitType::class)
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
