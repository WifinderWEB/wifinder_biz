<?php

//src/Wifinder/CatalogBundle/Entity/CatalogImage.php

namespace Wifinder\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="catalog_image")
 * @ORM\Entity(repositoryClass="Wifinder\CatalogBundle\Entity\Repository\CatalogImageRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\CatalogBundle\Entity\CatalogImageTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class CatalogImage {

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
     * @ORM\OneToMany(
     *   targetEntity="CatalogImageTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Catalog", 
     *   inversedBy="images")
     * @ORM\JoinColumn(
     *   name="catalog_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $catalog;

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
     * Constructor
     */
    public function __construct() {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return CatalogImage
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
     * @return CatalogImage
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CatalogImage
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Add translations
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogImageTranslation $translations
     * @return CatalogImage
     */
    public function addTranslation(\Wifinder\CatalogBundle\Entity\CatalogImageTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }

        return $this;
    }

    /**
     * Remove translations
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogImageTranslation $translations
     */
    public function removeTranslation(\Wifinder\CatalogBundle\Entity\CatalogImageTranslation $translations) {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations() {
        return $this->translations;
    }

    /**
     * Set catalog
     *
     * @param \Wifinder\CatalogBundle\Entity\Catalog $catalog
     * @return CatalogImage
     */
    public function setCatalog(\Wifinder\CatalogBundle\Entity\Catalog $catalog = null) {
        $this->catalog = $catalog;

        return $this;
    }

    /**
     * Get catalog
     *
     * @return \Wifinder\CatalogBundle\Entity\Catalog 
     */
    public function getCatalog() {
        return $this->catalog;
    }


    /**
     * Set path
     *
     * @param string $path
     * @return CatalogImage
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