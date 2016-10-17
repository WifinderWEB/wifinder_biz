<?php

//src/Wifinder/ImageGalleryBundle/Entity/Image.php

namespace Wifinder\ImageGalleryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="image_gallery")
 * @ORM\Entity(repositoryClass="Wifinder\ImageGalleryBundle\Entity\Repository\ImageRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\ImageGalleryBundle\Entity\ImageTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class Image {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * 
     * @Assert\File(maxSize="6000000")
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

    /**
     * @ORM\OneToMany(
     *   targetEntity="ImageTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $category_id;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="ImageCategory", 
     *   inversedBy="images")
     * @ORM\JoinColumn(
     *   name="category_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $category;
    
    protected $action;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath() {
        $path = $this->path;
        if($path === null || !file_exists($this->getUploadRootDir() . '/' . $this->path))
            $path = '/images/image_undefined.png';
        else
            $path = '/' . $this->getUploadDir() . '/' . $this->path;
        return $path;
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'uploads/images';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->image) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->image->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->image) {
            return;
        }

        $this->image->move($this->getUploadRootDir(), $this->path);
        unset($this->image);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($image = $this->getAbsolutePath()) {
            if(file_exists($image))
                unlink($image);
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
     * Set path
     *
     * @param string $path
     * @return Image
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
     * Set image
     *
     * @param string $image
     * @return Image
     */
    public function setImage($image) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Image
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
     * @return Image
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
     * Set is_active
     *
     * @param boolean $isActive
     * @return Image
     */
    public function setIsActive($isActive) {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive() {
        return $this->is_active;
    }

    /**
     * Add translations
     *
     * @param \Wifinder\ImageGalleryBundle\Entity\ImageTranslation $translations
     * @return Image
     */
    public function addTranslation(\Wifinder\ImageGalleryBundle\Entity\ImageTranslation $translations)
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
     * @param \Wifinder\ImageGalleryBundle\Entity\ImageTranslation $translations
     */
    public function removeTranslation(\Wifinder\ImageGalleryBundle\Entity\ImageTranslation $translations)
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
     * Set category
     *
     * @param \Wifinder\ImageGalleryBundle\Entity\ImageCategory $category
     * @return Image
     */
    public function setCategory(\Wifinder\ImageGalleryBundle\Entity\ImageCategory $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Wifinder\ImageGalleryBundle\Entity\ImageCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Set category_id
     *
     * @param integer $category_id
     * @return Image
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    
        return $this;
    }

    /**
     * Get category_id
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }
    
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
    
    public function getAction()
    {
        return $this->action;
    }
}