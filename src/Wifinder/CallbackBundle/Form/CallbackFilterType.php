<?php

namespace Wifinder\CallbackBundle\Form;

use \Symfony\Component\HttpFoundation\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wifinder\CallbackBundle\Entity\Repository\CallbackRepository;

class CallbackFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_received', 'choice', array(
                'choices' => array(0 => 'No',1 => 'Yes'),
                'empty_value' => 'Yes or No',
                'required' => false))
             ->add('last_name')
             ->add('first_name')
             ->add('middle_name')
             ->add('callback_text')
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
        return 'wifinder_reviewsbundle_callbackfiltertype';
    }
}
