<?php

namespace Wifinder\CallbackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CallbackFileType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file')
            ->add('description', 'text', array(
                'required' => false,
                'max_length' => 255,
                'attr' => array('placeholder' => 'Description (optional)')
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wifinder\CallbackBundle\Entity\CallbackFile'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wifinder_callbackbundle_callbackfile';
    }
}
