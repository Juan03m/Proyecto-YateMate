<?php

namespace App\Form;

use App\Entity\Embarcacion;
use App\Entity\Publicacion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

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
                'required' => true, // No requerido
                'attr' => [
                    'accept' => 'image/*', // Aceptar solo archivos de imagen
                ],
            ])

           // ->add('embarcaciones',ChoiceType::class,[
             //   'choices'=>$usuario->getEmbarcaciones(),
               // 'choice_label'=>'matricula'
           // ])
            ->add('embarcacion', EntityType::class, [
                'class' => Embarcacion::class,
                'choice_label' => 'nombre',
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
