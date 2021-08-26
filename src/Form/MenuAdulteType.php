<?php

namespace App\Form;

use App\Entity\MenuAdulte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuAdulteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'attr'=>['class'=>"form-control", 'placeholder'=>"Le titre du menu", 'autocomplete'=>"off"]
            ])
            //->add('slug')
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
            'data_class' => MenuAdulte::class,
        ]);
    }
}
