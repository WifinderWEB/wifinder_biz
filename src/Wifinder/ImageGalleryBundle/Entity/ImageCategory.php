<?php

//src/Wifinder/ImageGalleryBundle/Entity/ImageCategory.php

namespace Wifinder\ImageGalleryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="image_gallery_category")
 * @ORM\Entity(repositoryClass="Wifinder\ImageGalleryBundle\Entity\Repository\ImageCategoryRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\ImageGalleryBundle\Entity\ImageCategoryTranslation")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"imageCategory"})
 */
class ImageCategory {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column( type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"ImageCategory"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"ImageCategory"}
     * )
     */
    protected $alias;
    
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
     *   targetEntity="ImageCategoryTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="Image", 
     *   mappedBy="category", 
     *   cascade={"persist", "remove"})
    */
    protected $images;
    
    protected $action;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set alias
     *
     * @param string $alias
     * @return ImageCategory
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
     * Set title
     *
     * @param string $title
     * @return ImageCategory
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
     * @return ImageCategory
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
     * @return ImageCategory
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
     * Add images
     *
     * @param \Wifinder\ImageGalleryBundle\Entity\Image $images
     * @return ImageCategory
     */
    public function addImage(\Wifinder\ImageGalleryBundle\Entity\Image $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \Wifinder\ImageGalleryBundle\Entity\Image $images
     */
    public function removeImage(\Wifinder\ImageGalleryBundle\Entity\Image $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
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
    
    /**
     * Add translations
     *
     * @param \Wifinder\ImageGalleryBundle\Entity\ImageCategoryTranslation $translations
     * @return ImageCategory
     */
    public function addTranslation(\Wifinder\ImageGalleryBundle\Entity\ImageCategoryTranslation $t)
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
     * @param \Wifinder\ImageGalleryBundle\Entity\ImageCategoryTranslation $translations
     */
    public function removeTranslation(\Wifinder\ImageGalleryBundle\Entity\ImageCategoryTranslation $translations)
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
        return $this->getTitle();
    }
}