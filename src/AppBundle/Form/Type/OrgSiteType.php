<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class OrgSiteType extends AbstractType
{

    public function __construct()
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();

        $builder->add('name', TextType::class, array(
            'label' => 'Name',
            'required' => true,
            'attr' => [
                'placeholder' => 'eg "Main site"',
                'data-help' => 'So you can identify this site as your organisation expands.'
            ]
        ));

        $builder->add('address', TextType::class, array(
            'label' => 'Address',
            'required' => true,
        ));

        $builder->add('postcode', TextType::class, array(
            'label' => 'Postal code / Zip code',
            'required' => true,
        ));

        $builder->add('description', TextareaType::class, array(
            'label' => 'Description',
            'required' => false,
            'attr' => [
                'data-help' => 'Shows on the pop-up when your site is clicked on the map.'
            ]
        ));

        $builder->add('country', CountryType::class, array(
            'label' => 'Country',
            'required' => true,
            'placeholder' => '- choose country -',
            'preferred_choices' => ['US', 'CA', 'AU', 'GB', 'NZ'],
        ));

        $builder->add('latitude', HiddenType::class, array(
            'label' => 'Lat',
            'required' => true,
        ));

        $builder->add('longitude', HiddenType::class, array(
            'label' => 'Long',
            'required' => true,
        ));

    }

}