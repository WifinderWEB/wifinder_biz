<?php

//src/Wifinder/NewsBundle/Entity/NewsItemImage.php

namespace Wifinder\NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="news_item_image")
 * @ORM\Entity(repositoryClass="Wifinder\NewsBundle\Entity\Repository\NewsItemImageRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\NewsBundle\Entity\NewsItemImageTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class NewsItemImage {

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
     *   targetEntity="NewsItemImageTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="NewsItem", 
     *   inversedBy="images")
     * @ORM\JoinColumn(
     *   name="news_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $news_item;
    
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
     * Set image
     *
     * @param string $image
     * @return NewsItemImage
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
     * Set path
     *
     * @param string $path
     * @return NewsItemImage
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
     * Set title
     *
     * @param string $title
     * @return NewsItemImage
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
     * @return NewsItemImage
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
     * @param \Wifinder\NewsBundle\Entity\NewsItemImageTranslation $translations
     * @return NewsItemImage
     */
    public function addTranslation(\Wifinder\NewsBundle\Entity\NewsItemImageTranslation $translations)
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
     * @param \Wifinder\NewsBundle\Entity\NewsItemImageTranslation $translations
     */
    public function removeTranslation(\Wifinder\NewsBundle\Entity\NewsItemImageTranslation $translations)
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
     * Set news_item
     *
     * @param \Wifinder\NewsBundle\Entity\NewsItem $news
     * @return NewsItemImage
     */
    public function setNewsItem(\Wifinder\NewsBundle\Entity\NewsItem $news_item = null)
    {
        $this->news_item = $news_item;
    
        return $this;
    }

    /**
     * Get news
     *
     * @return \Wifinder\NewsBundle\Entity\NewsItem 
     */
    public function getNewsItem()
    {
        return $this->news_item;
    }
}