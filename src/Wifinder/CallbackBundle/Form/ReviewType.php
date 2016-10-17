<?php

namespace Wifinder\CallbackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active')
            ->add('user')
            ->add('review', 'ckeditor', 
                    array(
                        'label' => 'Review',
                        'config' => array(
                            'uiColor' => '#DED0DF'
                        )))
            ->add('action', 'hidden')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wifinder\CallbackBundle\Entity\Review'
        ));
    }

    public function getName()
    {
        return 'wifinder_reviewsbundle_reviewtype';
    }
}
