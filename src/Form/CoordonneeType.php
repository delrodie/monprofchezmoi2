<?php

namespace App\Form;

use App\Entity\Coordonnee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoordonneeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('localisation', TextType::class,['attr'=>['class'=>"form-control"]])
            ->add('tel', TelType::class,['attr'=>['class'=>"form-control", 'autocomplete'=>"off"],
	            'label' => "Contact 1"
            ])
            ->add('tel2', TelType::class,['attr'=>['class'=>"form-control", 'autocomplete'=>"off"],
	            'label' => "Contact 2"
            ])
            ->add('email', EmailType::class,['attr'=>['class'=>'form-control'],
	            'label' => 'Adresse mail'
            ])
            ->add('adresse', TextType::class,['attr'=>['class'=>"form-control"],
	            'label' => "Adresse boîte postale"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coordonnee::class,
        ]);
    }
}
