<?php

namespace Wifinder\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wifinder\CatalogBundle\Entity\Repository\CatalogRepository;

class CatalogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', 'entity', array(
                'class' => 'CatalogBundle:Catalog',
                'query_builder' => function(CatalogRepository $er) {
                    return $er->GetCategoriesQuery();
                },
                'required' => true, 
                'empty_value' => false
            ))
            ->add('alias')
            ->add('catalog_type', 'entity', array(
                'class' => 'CatalogBundle:CatalogType',
                'property' => 'name',
                'required' => true,
                'empty_value' => false
            ))
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => "Wifinder\CatalogBundle\Entity\Catalog",
                'fields' => array(
                    'title' => array(
                        'label' => 'Title'
                    ),
                    'short_description' => array(
                        'label' => 'Short description',
                        'field_type' => 'ckeditor',
                        'config' => array(
                            'uiColor' => '#DED0DF'
                        )
                    ),
                    'description' => array(
                        'label' => 'Description',
                        'field_type' => 'ckeditor',
                        'config' => array(
                            'uiColor' => '#DED0DF'
                        )
                    )
                )
            ))
            ->add('is_active', 'checkbox', array('required' => false))
            ->add('images', 'collection', array(
                'type' => new CatalogImageType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
                ))
            ->add('files', 'collection', array(
                'type' => new CatalogFileType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
                ))
            ->add('meta', new CatalogMetaType())
            ->add('action', 'hidden')
            ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Catalog'),
            'data_class' => 'Wifinder\CatalogBundle\Entity\Catalog'
        ));
    }

    public function getName()
    {
        return 'wifinder_catalogbundle_catalogtype';
    }
}
