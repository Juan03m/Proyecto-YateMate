<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltradoPublicacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $marinas=[$options['marinas']];

        $builder
            ->add('titulo')
            ->add('marina', ChoiceType::class, [
                'label' => 'Selecciona una marina',
                'choices' => $marinas, // Revertir el array para tener valores y claves en el formulario
                // Otras opciones del campo...
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'marinas'=>[]
            // Configure your form options here
        ]);
    }
}
