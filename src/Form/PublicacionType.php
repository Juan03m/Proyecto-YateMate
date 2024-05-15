<?php

namespace App\Form;

use App\Entity\Embarcacion;
use App\Entity\Publicacion;
use App\Repository\EmbarcacionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;

class PublicacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $usuario=$options['user'];
        

        $builder
            ->add('titulo')
            ->add('descripcion')
            /*
            ->add('fecha', null, [
                'widget' => 'single_text',
            ])
            */
            ->add('foto', FileType::class, [
                'label' => 'Foto (PNG, JPEG)',
                'mapped' => false, // No mapear a ninguna propiedad de la entidad
                'required' => true, //  requerido
                'attr' => [
                    'accept' => 'image/*', // Aceptar solo archivos de imagen
                ],
            ])

            ->add('embarcacion', EntityType::class, [
                'class' => Embarcacion::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EmbarcacionRepository $er) use ($usuario) {
                    return $er->createQueryBuilder('e')
                        ->where('e.usuario = :usuario')
                        ->setParameter('usuario', $usuario);
                },
                'constraints' => [
                    new NotBlank([
                        'message' => 'Debes asignar una embarcacion a la publicacion',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publicacion::class,
            'user'=>null

        ]);
    }


    

}
