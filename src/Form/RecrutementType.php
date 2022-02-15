<?php

namespace App\Form;

use App\Entity\Recrutement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RecrutementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class,[
	            'attr'=>['class'=>'form-control', 'placeholder'=>'Titre ', 'autocomplete'=>"off"],
	            'label'=>"Titre"
            ])
            ->add('contenu')
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
            ->add('tags', TextType::class,[
	            'attr' => ['class'=>'form-control', 'placeholder'=>"Les mots clés", 'data-role'=>"tagsinput"],
	            'label'=>"Mots clés"
            ])
            //->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recrutement::class,
        ]);
    }
}
