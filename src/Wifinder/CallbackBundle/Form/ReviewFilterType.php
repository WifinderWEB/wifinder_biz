<?php

namespace Wifinder\CallbackBundle\Form;

use \Symfony\Component\HttpFoundation\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wifinder\CallbackBundle\Entity\Repository\ReviewRepository;

class ReviewFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active', 'choice', array(
                'choices' => array(0 => 'No',1 => 'Yes'),
                'empty_value' => 'Yes or No',
                'required' => false))
             ->add('user')
             ->add('review')
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
        return 'wifinder_reviewsbundle_reviewfiltertype';
    }
}
