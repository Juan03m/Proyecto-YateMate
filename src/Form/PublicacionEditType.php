<?php

namespace App\Form;

use App\Entity\Embarcacion;
use App\Entity\Publicacion;
use App\Repository\EmbarcacionRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Validator\Constraints\NotBlank;

class PublicacionEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $usuario=$options['user'];
        

        $builder
            ->add('titulo',TypeTextType::class,   
            ['constraints' => [
                new NotBlank([
                    'message' => 'Por favor selcciona una imagen',
                ]),
            ],])
            ->add('descripcion',TypeTextType::class,   
            ['constraints' => [
                new NotBlank([
                    'message' => 'Por favor selcciona una imagen',
                ]),
            ],])

            ->add('foto', FileType::class, [
                'label' => 'Foto (PNG, JPEG)',
                'mapped' => false, // No mapear a ninguna propiedad de la entidad
                'required' => false, //  requerido
                'attr' => [
                    'accept' => 'image/*', // Aceptar solo archivos de imagen
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor selcciona una imagen',
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
