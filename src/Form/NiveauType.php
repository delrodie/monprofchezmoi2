<?php

namespace App\Form;

use App\Entity\Niveau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NiveauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class,[
	            'choices'=>[
		            '-- Selectionnez le type --' => '',
		            '' => '',
		            'Primaire' => 'PRIMAIRE',
		            'College' => 'COLLEGE',
		            'Lycee' => 'LYCEE',
		            'Etudes supérieures' => 'ETUDE SUPERIEURES',
		            'Adultes particuliers' => 'ADULTES PARTICULIERS',
	            ],
	            'attr' => ['class'=>'form-control']
            ])
            ->add('libelle', TextType::class,[
	            'attr'=>['class'=>'form-control', 'placeholder'=>"Le nom de domaine", 'autocomplete'=>'off']
            ])
            ->add('statut', CheckboxType::class,['required'=>false])
            //->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Niveau::class,
        ]);
    }
}
