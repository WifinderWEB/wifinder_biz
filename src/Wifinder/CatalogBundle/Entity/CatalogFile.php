<?php
// src/Wifinder/CatalogBundle/Entity/CatalogFile.php

namespace Wifinder\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="catalog_file")
 * @ORM\Entity(repositoryClass="Wifinder\CatalogBundle\Entity\Repository\CatalogFileRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\CatalogBundle\Entity\CatalogFileTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class CatalogFile {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\OneToMany(
     *   targetEntity="CatalogFileTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Catalog", 
     *   inversedBy="files")
     * @ORM\JoinColumn(
     *   name="catalog_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $catalog;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Wifinder\FileCategoryBundle\Entity\FileCategory", 
     *   inversedBy="files")
     * @ORM\JoinColumn(
     *   name="file_category_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $file_category;
    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath() {
        return null === $this->path ? null : '/' . $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'uploads/documents';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->file) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->file) {
            return;
        }

        $this->file->move($this->getUploadRootDir(), $this->path);
        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getAbsolutePath()) {
            if(file_exists($file))
                unlink($file);
        }
    }
    
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
     * Set file
     *
     * @param string $file
     * @return CatalogFile
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return CatalogFile
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CatalogFile
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add translations
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogFileTranslation $translations
     * @return CatalogFile
     */
    public function addTranslation(\Wifinder\CatalogBundle\Entity\CatalogFileTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogFileTranslation $translations
     */
    public function removeTranslation(\Wifinder\CatalogBundle\Entity\CatalogFileTranslation $translations)
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
     * @return CatalogFile
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
     * Set path
     *
     * @param string $path
     * @return CatalogFile
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set file_category
     *
     * @param \Wifinder\FileCategoryBundle\Entity\FileCategory $fileCategory
     * @return CatalogFile
     */
    public function setFileCategory(\Wifinder\FileCategoryBundle\Entity\FileCategory $fileCategory = null)
    {
        $this->file_category = $fileCategory;
    
        return $this;
    }

    /**
     * Get file_category
     *
     * @return \Wifinder\FileCategoryBundle\Entity\FileCategory 
     */
    public function getFileCategory()
    {
        return $this->file_category;
    }
}