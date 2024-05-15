<?php

namespace App\Form;

use App\Entity\Bien;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $bienes=$options['bienes'];
        $builder
            ->add('tipo',ChoiceType::class, [
                'choices' => $bienes,
                'placeholder' => 'Tipo de bien',
                'label' => 'Tipo de bien',
            ])
            ->add('nombre')
            ->add('descripcion')
            ->add('foto', FileType::class, [
                'label' => 'Foto (PNG, JPEG)',
                'mapped' => false, // No mapear a ninguna propiedad de la entidad
                'required' => true, // No requerido
                'attr' => [
                    'accept' => 'image/*', // Aceptar solo archivos de imagen
                ],
            ])

            /*
            ->add('owner', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'id',
            ])
              */
        ;
      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
            'bienes' => null,
        ]);
    }
}
