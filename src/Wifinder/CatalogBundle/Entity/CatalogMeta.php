<?php
// src/Wifinder/CatalogBundle/Entity/CatalogMeta.php

namespace Wifinder\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="catalog_meta")
 * @ORM\Entity(repositoryClass="Wifinder\CatalogBundle\Entity\Repository\CatalogMetaRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\CatalogBundle\Entity\CatalogMetaTranslation")
 */
class CatalogMeta {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $meta_title; 
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $meta_keywords;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $meta_description;
    
    /**
     * @ORM\OneToMany(
     *   targetEntity="CatalogMetaTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;
    
    /**
     * @ORM\OneToOne(targetEntity="Catalog", inversedBy="meta")
     * @ORM\JoinColumn(name="catalog_id", referencedColumnName="id")
     */
    protected $catalog;
    
    /**
     * @ORM\Column(name="catalog_id", type="integer")
     */
    protected $catalog_id;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set meta_title
     *
     * @param string $metaTitle
     * @return CatalogMeta
     */
    public function setMetaTitle($metaTitle)
    {
        $this->meta_title = $metaTitle;
    
        return $this;
    }

    /**
     * Get meta_title
     *
     * @return string 
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * Set meta_keywords
     *
     * @param string $metaKeywords
     * @return CatalogMeta
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->meta_keywords = $metaKeywords;
    
        return $this;
    }

    /**
     * Get meta_keywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * Set meta_description
     *
     * @param string $metaDescription
     * @return CatalogMeta
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;
    
        return $this;
    }

    /**
     * Get meta_description
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Add translations
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogMetaTranslation $translations
     * @return CatalogMeta
     */
    public function addTranslation(\Wifinder\CatalogBundle\Entity\CatalogMetaTranslation $translations)
    {
        if (!$this->translations->contains($translations)) {
            $this->translations[] = $translations;
            $translations->setObject($this);
        }
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogMetaTranslation $translations
     */
    public function removeTranslation(\Wifinder\CatalogBundle\Entity\CatalogMetaTranslation $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Set catalog
     *
     * @param \Wifinder\CatalogBundle\Entity\Catalog $catalog
     * @return CatalogMeta
     */
    public function setCatalog(\Wifinder\CatalogBundle\Entity\Catalog $catalog = null)
    {
        $this->catalog = $catalog;
    
        return $this;
    }

    /**
     * Get catalog
     *
     * @return \Wifinder\CatalogBundle\Entity\Catalog 
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * Set catalog_id
     *
     * @param integer $catalogId
     * @return CatalogMeta
     */
    public function setCatalogId($catalogId)
    {
        $this->catalog_id = $catalogId;
    
        return $this;
    }

    /**
     * Get catalog_id
     *
     * @return integer 
     */
    public function getCatalogId()
    {
        return $this->catalog_id;
    }
}