<?php

namespace App\Form\Type;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{

    private $imagePath;

    /**
     * @param $imagePath
     */
    public function __construct($imagePath)
    {
        $this->imagePath = $imagePath;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [new NotBlank()]
                ]
            )
            ->add(
                'title',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'file',
                FileType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new File(
                            [
                                'mimeTypes'=>[ "image/png", "image/jpeg", "image/jpg", "image/gif" ],
                                'maxSize' => '5M'
                            ]

                        )
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Image::class,
        ));
    }

    public function getName()
    {
        return 'image';
    }
}