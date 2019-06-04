<?php

// src/AppBundle/Form/RegistrationType.php

/**
 * Override the default FOSUserBundle Registration form
 *
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FosRegistrationType extends AbstractType
{
    private $tags;

    public function __construct()
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->tags   = $options['tags'];

        $builder->add('firstName', TextType::class, array(
            'label' => 'First name',
            'required' => true,
        ));

        $builder->add('lastName', TextType::class, array(
            'label' => 'Last name',
            'required' => false,
        ));

        $builder->add('email', TextType::class, array(
            'label' => 'Your email address',
            'required' => true,
            'attr' => array(
                'placeholder' => ""
            )
        ));

        // Hide the user name (entity class overrides this with email address)
        $builder->add('username', HiddenType::class, array(
            'required' => false,
            'attr' => array(
                'data-help' => ""
            )
        ));

        ## ORG

        $builder->add('orgName', TextType::class, array(
            'label' => 'Organisation name',
            'required' => true,
            'mapped' => false,
        ));

        $builder->add('orgEmail', TextType::class, array(
            'label' => 'Organisation email address',
            'required' => false,
            'mapped' => false,
        ));

        $builder->add('tags', ChoiceType::class, array(
            'label' => 'Tags for search filters',
            'choices' => $this->tags,
            'expanded' => true,
            'multiple' => true,
            'required' => true,
            'mapped' => false,
            'attr' => [
                'data-help' => ""
            ]
        ));

        $builder->add('website', TextType::class, array(
            'label' => 'Website',
            'required' => false,
            'mapped' => false,
        ));

        $builder->add('facebook', TextType::class, array(
            'label' => 'Facebook page',
            'required' => false,
            'mapped' => false,
        ));

        ## SITE

        $builder->add('siteName', TextType::class, array(
            'label' => 'Site name',
            'required' => false,
            'mapped' => false,
            'attr' => [
                'placeholder' => 'If different from organisation name',
                'data-help' => ''
            ]
        ));

        $builder->add('address', TextType::class, array(
            'label' => 'Address',
            'required' => true,
            'mapped' => false,
        ));

        $builder->add('postcode', TextType::class, array(
            'label' => 'Postal code / Zip code',
            'required' => true,
            'mapped' => false,
        ));

        $builder->add('description', TextareaType::class, array(
            'label' => 'Description',
            'required' => false,
            'mapped' => false,
            'attr' => [
                'data-help' => 'Shows on when your site is clicked on the map.'
            ]
        ));

        $builder->add('country', CountryType::class, array(
            'label' => 'Country',
            'required' => true,
            'mapped' => false,
            'placeholder' => '- choose country -',
            'preferred_choices' => ['US', 'CA', 'AU', 'GB', 'NZ'],
        ));

        $builder->add('latitude', HiddenType::class, array(
            'label' => 'Lat',
            'required' => false,
            'mapped' => false,
        ));

        $builder->add('longitude', HiddenType::class, array(
            'label' => 'Long',
            'required' => false,
            'mapped' => false,
        ));

    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => ['AppBundleRegistration'],
            'tags' => null,
        ));
    }
}