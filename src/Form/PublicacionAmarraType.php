<?php

namespace App\Form;

use App\Entity\Amarra;
use App\Entity\PublicacionAmarra;
use App\Repository\AmarraRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PublicacionAmarraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $usuario = $options['user'];

        $builder
            ->add('fechaDesde', null, [
                'widget' => 'single_text',
            ])
            ->add('fechaHasta', null, [
                'widget' => 'single_text',
            ])
            ->add('Amarra', EntityType::class, [
                'class' => Amarra::class,
                'choice_label' => 'id',
                /*
                Arreglar query!!!!!!!!!!!!!!!!

                'query_builder' => function(AmarraRepository $ar) use ($usuario){
                    return $ar->createQueryBuilder('a')
                    ->leftJoin('a.publicacionAmarra', 'pa')
                    ->where('a.usuario = :usuario')
                    ->andWhere('pa.id IS NULL')
                    ->setParameter('usuario', $usuario);
                },
                'constraints' => [
                    new NotBlank([
                        'message' => 'No tienes amarras para publicar',
                    ]),
                ],
                */
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PublicacionAmarra::class,
            'user' => null,
        ]);
    }
}

