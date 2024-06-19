<?php

namespace App\Form;

use App\Entity\Amarra;
use App\Entity\PublicacionAmarra;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicacionAmarraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fechaDesde', null, [
                'widget' => 'single_text',
            ])
            ->add('fechaHasta', null, [
                'widget' => 'single_text',
            ])
            ->add('numero')
            ->add('sector')
            ->add('marina')
            ->add('tamano')
            ->add('Amarra', EntityType::class, [
                'class' => Amarra::class,
                'choice_label' => 'id',
            ])
            ->add('usuario', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PublicacionAmarra::class,
        ]);
    }
}
