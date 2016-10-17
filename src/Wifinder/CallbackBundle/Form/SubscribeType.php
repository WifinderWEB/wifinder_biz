<?php

namespace Wifinder\CallbackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SubscribeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'required' => true,
                'attr' => array(
                    'max_length' => 100,
                    'placeholder' => 'E-mail address')
            ))
            ->add('captcha', 'genemu_recaptcha')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('MailingAddress'),
            'data_class' => 'Wifinder\CallbackBundle\Entity\MailingAddress'
        ));
    }

    public function getName()
    {
        return 'Wifinder_callbackbundle_subscribetype';
    }
}
