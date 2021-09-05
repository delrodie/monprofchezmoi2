<?php

namespace App\Form;

use App\Entity\Cover;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CoverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>'Titre du cover', 'autocomplete'=>"off"],
                'label'=>"Titre"
            ])
            ->add('description', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>'Description', 'autocomplete'=>"off"],
                'label'=>"Description"
            ])
            ->add('media', FileType::class,[
                'attr'=>['class'=>"dropify", 'data-preview' => ".preview"],
                'label' => "Télécharger la photo 1",
                'mapped' => false,
                'multiple' => false,
                'constraints' => [
                    new File([
                        'maxSize' => "1000000k",
                        'mimeTypes' =>[
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => "Votre fichier doit être de type image"
                    ])
                ],
                'required' => false
            ])
            //->add('slug')
            //->add('debut')
            //->add('fin')
            ->add('statut', CheckboxType::class,[
                'attr' => ['class'=>'custom-control-input'],
                'label_attr' => ['class'=>'custom-control-label'],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cover::class,
        ]);
    }
}
