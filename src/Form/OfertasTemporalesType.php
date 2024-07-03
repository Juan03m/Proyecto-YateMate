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
                'min' => (new \DateTime())->modify('+1 day')->format('Y-m-d'), 
                'max' => (new \DateTime())->modify('+2 year')->format('Y-m-d') // Establecer el mínimo como la fecha actual en formato Y-m-d
            ],
        ])
            ->add('hasta',TypeDateType::class,[
                'required'=>true,
                'html5' => true, // Usar tipo de entrada HTML5 para selector de fecha
                'attr' => [
                'min' => (new \DateTime())->format('Y-m-d'), // Establecer el mínimo como la fecha actual en formato Y-m-d
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
                

                if ($fechaDesde) {
                    $fechaMaximaHasta = (clone $fechaDesde)->modify('+3 months');
                    if ($fechaHasta && $fechaHasta > $fechaMaximaHasta) {
                        $form->get('hasta')->addError(new FormError('La diferencia entre la fecha desde y la fecha hasta no puede ser mayor a 3 meses.'));
                        $form->addError(new FormError('La diferencia entre la fecha desde y la fecha hasta no puede ser mayor a 3 meses.'));
                    }
                }
                if ($fechaDesde && $fechaHasta) {
                    if ($fechaHasta <= $fechaDesde) {
                        $form->get('hasta')->addError(new FormError('La fecha de finalización debe ser mayor que la fecha de inicio.'));
                        $form->addError(new FormError('La fecha de finalización debe ser mayor que la fecha de inicio.'));
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
