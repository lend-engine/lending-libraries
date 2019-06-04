<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{

    public function __construct()
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();

        $builder->add('firstName', TextType::class, array(
            'label' => 'First name',
            'required' => false,
        ));

        $builder->add('lastName', TextType::class, array(
            'label' => 'Last name',
            'required' => false,
        ));

        $builder->add('email', TextType::class, array(
            'label' => 'Contact email address',
            'required' => true,
        ));

        $builder->add('enabled', CheckboxType::class, array(
            'label' => 'Email verified',
            'required' => true,
        ));
    }

}