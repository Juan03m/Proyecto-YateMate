<?php

namespace App\Form;

use App\Entity\Amarra;
use App\Entity\Embarcacion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AmarraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Numero')
            ->add('sector')
            ->add('marina')
            ->add('embarcacion', EntityType::class, [
                'class' => Embarcacion::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Amarra::class,
        ]);
    }
}
