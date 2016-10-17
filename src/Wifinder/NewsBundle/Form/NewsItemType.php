<?php

namespace Wifinder\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewsItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active', 'checkbox', array('required' => false))
            ->add('publish' ,'date',array(
                'widget' => 'single_text',
                'required' => true,
                'format' => 'dd.MM.yyyy',
                'attr' => array('class' => 'publish_date')
            ))
            ->add('end_date','date',array(
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd.MM.yyyy',
                'attr' => array('class' => 'end_date')
            ))
            ->add('category', 'entity', array(
                'class' => 'NewsBundle:NewsCategory',
                'required' => true,
                'empty_value' => false,
                'label' => 'News Category'
            ))
            ->add('alias')
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\NewsBundle\Entity\NewsItem",
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    ),
                    'anons' => array(
                        'label' => 'Anons',
                        'field_type' => 'ckeditor',
                        'config' => array(
                            'uiColor' => '#DED0DF'
                        )
                    ),
                    'content' => array(
                        'label' => 'Content',
                        'field_type' => 'ckeditor',
                        'config' => array(
                            'uiColor' => '#DED0DF'
                        )
                    )
                )
            ))
            ->add('images', 'collection', array(
                'type' => new NewsItemImageType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('meta', new NewsItemMetaType())
            ->add('action', 'hidden')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('NewsItem'),
            'data_class' => 'Wifinder\NewsBundle\Entity\NewsItem'
        ));
    }

    public function getName()
    {
        return 'newsbundle_newsitemtype';
    }
}
