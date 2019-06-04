<?php

// src/AppBundle/Form/Type/ContactType.php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactUsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'label' => 'Name',
            'required' => true,
            'attr' => [
                'placeholder' => 'Name',
                'data-help' => ""
            ]
        ));

        $builder->add('email', TextType::class, array(
            'label' => 'Email address',
            'required' => true,
            'attr' => [
                'placeholder' => 'Email address',
                'data-help' => ""
            ]
        ));

        $builder->add('subject', TextType::class, array(
            'label' => 'Your name',
            'required' => true,
            'attr' => [
                'placeholder' => 'Subject'
            ]
        ));

        $builder->add('library', TextType::class, array(
            'label' => 'Your library URL or name',
            'required' => true,
            'attr' => [
                'placeholder' => 'Organisation or URL eg library.lend-engine-app.com'
            ]
        ));

        $builder->add('message', TextareaType::class, array(
            'label' => 'Message',
            'required' => true,
            'attr' => [
                'placeholder' => 'Message',
                'rows' => 6
            ]
        ));

    }

    public function getName()
    {
        return 'contact_type';
    }

}