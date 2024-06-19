<?php

namespace App\Form;

use App\Entity\PublicacionAmarra;
use App\Entity\ReservaAmarra;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservaAmarraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('aceptada')
            ->add('publicacionAmarra', EntityType::class, [
                'class' => PublicacionAmarra::class,
           /*     'choice_label' => 'id',*/
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReservaAmarra::class,
        ]);
    }
}
