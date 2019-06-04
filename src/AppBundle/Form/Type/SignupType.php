<?php

// src/AppBundle/Form/Type/RegistrationType.php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SignupType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'label' => 'Organisation name',
            'required' => true,
            'attr' => [
                'data-help' => ""
            ]
        ));

        $builder->add('stub', TextType::class, array(
            'label' => 'Tenant code',
            'required' => true,
            'attr' => [
                'placeholder' => 'e.g. "london-lender"',
                'data-help' => "A short version of your organisation name, used for your login URL"
            ]
        ));

        $builder->add('ownerName', TextType::class, array(
            'label' => 'Your name',
            'required' => true
        ));

        $builder->add('ownerEmail', TextType::class, array(
            'label' => 'Your email address',
            'required' => true
        ));

    }

    public function getName()
    {
        return 'registration_type';
    }

}