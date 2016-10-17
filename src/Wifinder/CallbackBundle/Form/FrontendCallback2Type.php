<?php

namespace Wifinder\CallbackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FrontendCallback2Type extends AbstractType
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
            ->add('post', 'text', array(
                'required' => false,
                'max_length' => 250,
                'attr' => array('placeholder' => 'Please enter your post')
            ))
            ->add('company', 'text', array(
                'required' => false,
                'max_length' => 250,
                'attr' => array('placeholder' => 'Please enter your company')
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
                'attr' => array('placeholder' => 'Please enter text', 'rows' => 7)
            ))
            ->add('files', 'collection', array(
                'type' => new CallbackFileType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
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
        return 'wifinder_callbackbundle_frontendcallbackduble_type_captcha_div';
    }
}
