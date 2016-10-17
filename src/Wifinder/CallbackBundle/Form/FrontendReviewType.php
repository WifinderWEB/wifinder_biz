<?php

namespace Wifinder\CallbackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FrontendReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', 'text', array(
                'attr' => array(
                    'required' => true,
                    'max_length' => 100,
                    'placeholder' => 'Please enter your name')
            ))
            ->add('review', 'textarea', array(
                'attr' => array(
                    'required' => true,
                    'max_length' => 1000,
                    'placeholder' => 'Please enter your review')
            ))
            ->add('captcha', 'genemu_recaptcha')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Review'),
            'data_class' => 'Wifinder\CallbackBundle\Entity\Review'
        ));
    }

    public function getName()
    {
        return 'wifinder_reviewsbundle_frontendreviewtype';
    }
}
