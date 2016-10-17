<?php

namespace Wifinder\CallbackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailingAddressFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('is_active', 'choice', array(
                'choices' => array(0 => 'No',1 => 'Yes'),
                'empty_value' => 'Yes or No',
                'required' => false))
            ->add('type', 'entity', array(
                'class' => 'CallbackBundle:EmailType',
                'property' => 'type',
                'required' => true,
                'empty_value' => ' '
            ))
            ->add('first_name')
            ->add('middle_name')
            ->add('last_name')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wifinder\CallbackBundle\Entity\MailingAddress'
        ));
    }

    public function getName()
    {
        return 'wifinder_callbackbundle_mailingaddressfiltertype';
    }
}
