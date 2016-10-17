<?php
// src/Wifinder/CallbackBundle/Entity/CallbackFile.php

namespace Wifinder\CallbackBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="callback_file")
 * @ORM\Entity(repositoryClass="Wifinder\CallbackBundle\Entity\Repository\CallbackFileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CallbackFile {

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
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $origin_name;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Callback", 
     *   inversedBy="files")
     * @ORM\JoinColumn(
     *   name="callback_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $callback;
    
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
        return 'uploads/callback/documents';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->file) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->file->guessExtension();
            $this->origin_name = $this->file->getClientOriginalName();
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
     * @return CallbackFile
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
     * Set origin_name
     *
     * @param string $origin_name
     * @return CallbackFile
     */
    public function setOriginName($origin_name)
    {
        $this->origin_name = $origin_name;
    
        return $this;
    }

    /**
     * Get origin_name
     *
     * @return string 
     */
    public function getOriginName()
    {
        return $this->origin_name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CallbackFile
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
     * Set callback
     *
     * @param \Wifinder\CallbackBundle\Entity\Callback $callback
     * @return ProjectFile
     */
    public function setCallback(\Wifinder\CallbackBundle\Entity\Callback $callback = null)
    {
        $this->callback = $callback;
    
        return $this;
    }

    /**
     * Get callback
     *
     * @return \Wifinder\CallbackBundle\Entity\Callback 
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return CallbackFile
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
}