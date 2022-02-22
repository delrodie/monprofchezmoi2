<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"", 'autocomplete'=>"off"],
	            'label'=>"Nom de famille *"
            ])
            ->add('prenoms', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"", 'autocomplete'=>"off"],
	            "label" => "Prenoms *"
            ])
            ->add('telephone', TelType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"Votre numero de telephone", 'autocomplete'=>"off"],
	            'label' => "Telephone *"
            ])
            ->add('email', EmailType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"Votre adresse email", 'autocomplete'=>"off"],
	            'label' => "Email *"
            ])
            ->add('domicile', TextType::class,[
				'attr'=>['class'=>'form-control', 'placeholder'=>"Votre lieu de residence", "autocomplete"=>"off"],
	            "label" => "Domicile *"
            ])
            ->add('demande', TextareaType::class,[
				'attr'=>['class'=>'form-control', "rows"=>5],
	            'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
