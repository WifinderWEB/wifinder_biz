<?php
// src/Wifinder/ProjectBundle/Entity/ProjectFile.php

namespace Wifinder\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="project_file")
 * @ORM\Entity(repositoryClass="Wifinder\ProjectBundle\Entity\Repository\ProjectFileRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\ProjectBundle\Entity\ProjectFileTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class ProjectFile {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $sort;
    
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
     *   targetEntity="ProjectFileTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Project", 
     *   inversedBy="files")
     * @ORM\JoinColumn(
     *   name="project_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $project;
    
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
     * Set sort
     *
     * @param integer $sort
     * @return ProjectFile
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
     * Set file
     *
     * @param string $file
     * @return ProjectFile
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
     * @return ProjectFile
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
     * @return ProjectFile
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
     * @param \Wifinder\ProjectBundle\Entity\ProjectFileTranslation $translations
     * @return ProjectFile
     */
    public function addTranslation(\Wifinder\ProjectBundle\Entity\ProjectFileTranslation $t)
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
     * @param \Wifinder\ProjectBundle\Entity\ProjectFileTranslation $translations
     */
    public function removeTranslation(\Wifinder\ProjectBundle\Entity\ProjectFileTranslation $translations)
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
     * Set project
     *
     * @param \Wifinder\ProjectBundle\Entity\Project $project
     * @return ProjectFile
     */
    public function setProject(\Wifinder\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \Wifinder\ProjectBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return ProjectFile
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
     * @return ProjectFile
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