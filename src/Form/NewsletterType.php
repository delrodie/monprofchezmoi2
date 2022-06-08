<?php

namespace App\Form;

use App\Entity\Newsletter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,['attr'=>['class'=>"form-control", 'autocomplete'=>'off'], 'required'=>false])
            ->add('contact', TelType::class, ['attr'=>['class'=>"form-control", 'autocomplete'=>'off'], 'required'=>false])
            ->add('email', EmailType::class, ['attr'=>['class'=>"form-control", 'autocomplete'=>'off'], 'required'=>true])
            ->add('statut', CheckboxType::class,['required'=>false])
            //->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Newsletter::class,
        ]);
    }
}
