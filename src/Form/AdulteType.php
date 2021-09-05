<?php

namespace App\Form;

use App\Entity\Adulte;
use App\Entity\MenuAdulte;
use App\Entity\Thematique;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AdulteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>'Titre du cours adulte', 'autocomplete'=>"off"],
                'label'=>"Le titre"
            ])
            ->add('contenu')
            //->add('resume')
            ->add('media', FileType::class,[
                'attr'=>['class'=>"dropify", 'data-preview' => ".preview"],
                'label' => "Télécharger la photo d'illustration",
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
            ->add('tags', TextType::class,[
                'attr' => ['class'=>'form-control', 'placeholder'=>"Les mots clés", 'data-role'=>"tagsinput"],
                'label'=>"Mots clés"
            ])
            //->add('slug')
            ->add('menu', null,[
                'attr'=>['class' => 'form-control'],
                'class' => MenuAdulte::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label' => 'titre',
                'label' => 'Menu'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adulte::class,
        ]);
    }
}
