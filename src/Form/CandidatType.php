<?php

namespace App\Form;

use App\Entity\Candidat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civilite', ChoiceType::class,[
				'attr'=>['class'=>'form-control'],
	            'choices' => [
					'-- Selectionnez --' => '',
					'MADEMOISELLE' => 'Mademoiselle',
					'MADAME' => 'Madame',
					'MONSIEUR' => 'Monsieur',
	            ]
            ])
            ->add('nom', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"Nom de famille", 'autocomplete'=>"off"]
            ])
            ->add('prenoms', TextType::class,[
	            'attr'=>['class'=>'form-control', 'placeholder'=>"Prenoms", 'autocomplete'=>"off"]
            ])
            ->add('email', EmailType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>'Adresse email']
            ])
            ->add('dateNaissance', TextType::class,[
				'attr' => ['class' => 'form-control', 'placeholder'=>"Date de naissance"]
            ])
            ->add('adresse', TextType::class,[
	            'attr'=>['class'=>'form-control', 'placeholder'=>"Adresse", 'autocomplete'=>"off"]
            ])
            ->add('cv', FileType::class,[
	            'attr'=>['class'=>"dropify", 'data-preview' => ".preview"],
	            'label' => "Votre CV",
	            'mapped' => false,
	            'multiple' => false,
	            'constraints' => [
		            new File([
			            'maxSize' => "1000000k",
			            'mimeTypes' =>[
				            'application/pdf',
				            'application/x-pdf',
				            'application/msword',
				            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			            ],
			            'mimeTypesMessage' => "Votre fichier doit être de type pdf ou word"
		            ])
	            ],
	            'required' => true
            ])
            ->add('lettreMotivation', FileType::class,[
	            'attr'=>['class'=>"dropify", 'data-preview' => ".preview"],
	            'label' => "Lettre de motivation",
	            'mapped' => false,
	            'multiple' => false,
	            'constraints' => [
		            new File([
			            'maxSize' => "1000000k",
			            'mimeTypes' =>[
				            'application/pdf',
				            'application/x-pdf',
				            'application/msword',
			            ],
			            'mimeTypesMessage' => "Votre fichier doit être de type pdf ou word"
		            ])
	            ],
	            'required' => false
            ])
            ->add('approuve', CheckboxType::class,[
				'attr'=>['class'=>'custom-control-input'],
	            'label_attr' => ['class' => 'custom-control-label']
            ])
            //->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}
