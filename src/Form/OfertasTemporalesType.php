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
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

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
            'html5' => true, // Usar tipo de entrada HTML5 para selector de fecha
            'required' => true,
            'attr' => [
                'min' => (new \DateTime())->format('Y-m-d'), // Establecer el mínimo como la fecha actual en formato Y-m-d
            ],
        ])
            ->add('hasta',TypeDateType::class,[
                'required'=>true,
                'html5' => true, // Usar tipo de entrada HTML5 para selector de fecha
                'attr' => [
                'min' => (new \DateTime())->format('Y-m-d'), // Establecer el mínimo como la fecha actual en formato Y-m-d
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

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                                
                $fechaDesde = $form->getData()['desde'];
                $fechaHasta = $form->getData()['hasta'];
                
                if ($fechaDesde && $fechaHasta) {
                    if ($fechaHasta <= $fechaDesde) {
                        $form->get('hasta')->addError(new FormError('La fecha de finalización debe ser mayor que la fecha de inicio.'));
                        $form->addError(new FormError('Las fechas de inicio debe ser mayor que la fecha de fin'));
                    }
                }
            }
            
        );




    }



    

    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            // Configure your form options here
        ]);
    }
}
