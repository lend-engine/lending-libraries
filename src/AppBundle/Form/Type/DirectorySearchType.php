<?php

// src/AppBundle/Form/Type/RegistrationType.php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DirectorySearchType extends AbstractType
{
    private $tags;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->tags = $options['tags'];

        $builder->add('search', TextType::class, array(
            'label' => 'Search',
            'required' => false,
            'attr' => [
                'placeholder' => "Library name (optional)"
            ]
        ));

        $choices = $this->tags;
        $builder->add('tags', ChoiceType::class, array(
            'label' => 'Tags',
            'choices' => $choices,
            'expanded' => true,
            'multiple' => true,
        ));

        $choices = [
            'Within 10 miles' => 10,
            'Within 50 miles' => 50,
            'Within 100 miles' => 100,
            'Within 500 miles' => 500,
            'All' => 100000
        ];
        $builder->add('distance', ChoiceType::class, array(
            'label' => 'Distance',
            'data' => 500,
            'choices' => $choices,
        ));

        $builder->add('latitude', HiddenType::class, array(
            'label' => 'Lat',
            'required' => false,
        ));

        $builder->add('longitude', HiddenType::class, array(
            'label' => 'Long',
            'required' => false,
        ));

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'tags' => null,
        ));
    }
}