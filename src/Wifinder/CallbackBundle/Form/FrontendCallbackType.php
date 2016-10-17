<?php

namespace Wifinder\CallbackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FrontendCallbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('full_name', 'text', array(
                'attr' => array(
                    'required' => true,
                    'max_length' => 250,
                    'placeholder' => 'Пожалуйста введите Ф.И.О.')
            ))
            ->add('email', 'email', array(
                'required' => true,
                'max_length' => 100,
                'attr' => array('placeholder' => 'Please enter your e-mail')
            ))
            ->add('phone', 'text', array(
                'required' => true,
                'max_length' => 100,
                'attr' => array('placeholder' => 'Please enter phone')
            ))
            ->add('callback_text', 'textarea', array(
                'required' => true,
                'max_length' => 1000,
                'attr' => array('placeholder' => 'Please enter text')
            ))
//            ->add('captcha', 'genemu_recaptcha')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Callback'),
            'data_class' => 'Wifinder\CallbackBundle\Entity\Callback'
        ));
    }

    public function getName()
    {
        return 'wifinder_callbackbundle_frontendcallbacktype';
    }
}
