<?php

namespace Wifinder\CallbackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailingAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active')
            ->add('type')
            ->add('email')
            ->add('first_name')
            ->add('middle_name')
            ->add('last_name')  
            ->add('action', 'hidden')
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
        return 'wifinder_callbackbundle_mailingaddresstype';
    }
}
