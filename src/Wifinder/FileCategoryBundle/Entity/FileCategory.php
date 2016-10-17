<?php

//src/Wifinder/FileCategoryBundle/Entity/FileCategory.php

namespace Wifinder\FileCategoryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="file_category")
 * @ORM\Entity(repositoryClass="Wifinder\FileCategoryBundle\Entity\Repository\FileCategoryRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\FileCategoryBundle\Entity\FileCategoryTranslation")
 */
class FileCategory {

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
    protected $name;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    protected $alias;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $sort;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="Wifinder\CatalogBundle\Entity\CatalogFile", 
     *   mappedBy="file_category", 
     *   cascade={"persist", "remove"})
    */
    protected $files;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;
    
    /**
     * @ORM\OneToMany(
     *   targetEntity="FileCategoryTranslation",
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
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return FileCategory
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
     * Set alias
     *
     * @param string $alias
     * @return FileCategory
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
     * Set sort
     *
     * @param integer $sort
     * @return FileCategory
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    
        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return FileCategory
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
    
        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Add files
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogFile $files
     * @return FileCategory
     */
    public function addFile(\Wifinder\CatalogBundle\Entity\CatalogFile $files)
    {
        $this->files[] = $files;
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogFile $files
     */
    public function removeFile(\Wifinder\CatalogBundle\Entity\CatalogFile $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add translations
     *
     * @param \Wifinder\FileCategoryBundle\Entity\FileCategoryTranslation $translations
     * @return FileCategory
     */
    public function addTranslation(\Wifinder\FileCategoryBundle\Entity\FileCategoryTranslation $translations)
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
     * @param \Wifinder\FileCategoryBundle\Entity\FileCategoryTranslation $translations
     */
    public function removeTranslation(\Wifinder\FileCategoryBundle\Entity\FileCategoryTranslation $translations)
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