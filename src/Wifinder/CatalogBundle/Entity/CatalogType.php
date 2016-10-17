<?php
// src/Wifinder/CatalogBundle/Entity/CatalogType.php

namespace Wifinder\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="catalog_type")
 * @ORM\Entity(repositoryClass="Wifinder\CatalogBundle\Entity\Repository\CatalogTypeRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\CatalogBundle\Entity\CatalogTypeTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class CatalogType {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $alias;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $name;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="Catalog", 
     *   mappedBy="catalog_type", 
     *   cascade={"persist", "remove"})
    */
    protected $catalog;
    
    /**
     * @ORM\OneToMany(
     *   targetEntity="CatalogTypeTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->catalog = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set alias
     *
     * @param string $alias
     * @return CatalogType
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    
        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CatalogType
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add catalog
     *
     * @param \Wifinder\CatalogBundle\Entity\Catalog $catalog
     * @return CatalogType
     */
    public function addCatalog(\Wifinder\CatalogBundle\Entity\Catalog $catalog)
    {
        $this->catalog[] = $catalog;
    
        return $this;
    }

    /**
     * Remove catalog
     *
     * @param \Wifinder\CatalogBundle\Entity\Catalog $catalog
     */
    public function removeCatalog(\Wifinder\CatalogBundle\Entity\Catalog $catalog)
    {
        $this->catalog->removeElement($catalog);
    }

    /**
     * Get catalog
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

    /**
     * Add translations
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogTypeTranslation $translations
     * @return CatalogType
     */
    public function addTranslation(\Wifinder\CatalogBundle\Entity\CatalogTypeTranslation $translations)
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
     * @param \Wifinder\CatalogBundle\Entity\CatalogTypeTranslation $translations
     */
    public function removeTranslation(\Wifinder\CatalogBundle\Entity\CatalogTypeTranslation $translations)
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
    
    public function __toString() {
        return $this->getName();
    }
}