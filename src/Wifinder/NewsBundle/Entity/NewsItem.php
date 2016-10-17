<?php

//src/Wifinder/NewsBundle/Entity/NewsItem.php

namespace Wifinder\NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="news_item")
 * @Gedmo\TranslationEntity(class="Wifinder\NewsBundle\Entity\NewsItemTranslation")
 * @ORM\Entity(repositoryClass="Wifinder\NewsBundle\Entity\Repository\NewsItemRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"NewsItem"})
 */
class NewsItem {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"NewsItem"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"NewsItem"}
     * )
     */
    protected $alias;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $anons;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;
    
    /**
     * @ORM\OneToMany(
     *   targetEntity="NewsItemTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_sent = false;
    
    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;
    
    /**
     * @Assert\NotBlank(message="Please enter publish date.", groups={"NewsItem"})
     * @Assert\Date(message="Please enter valid publish date.", groups={"NewsItem"})
     * @ORM\Column(type="datetime")
     */
    protected $publish;
    
    /**
     * @Assert\Date(message="Please enter valid publish date.", groups={"NewsItem"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $end_date;
    
    /**
     * @ORM\OneToOne(targetEntity="NewsItemMeta", 
     *   mappedBy="news_item", 
     *   cascade={"persist", "remove"})
    */
    protected $meta;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="NewsItemImage", 
     *   mappedBy="news_item", 
     *   cascade={"persist", "remove"})
    */
    protected $images;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="NewsCategory", 
     *   inversedBy="items")
     * @ORM\JoinColumn(
     *   name="category_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $category;
    
    protected $category_id;
    
    protected $action;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return NewsItem
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
     * Set alias
     *
     * @param string $alias
     * @return NewsItem
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
     * Set anons
     *
     * @param string $anons
     * @return NewsItem
     */
    public function setAnons($anons)
    {
        $this->anons = $anons;
    
        return $this;
    }

    /**
     * Get anons
     *
     * @return string 
     */
    public function getAnons()
    {
        return $this->anons;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return NewsItem
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return NewsItem
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
     * Set is_sent
     *
     * @param boolean $isSent
     * @return NewsItem
     */
    public function setIsSent($isSent)
    {
        $this->is_sent = $isSent;
    
        return $this;
    }

    /**
     * Get is_sent
     *
     * @return boolean 
     */
    public function getIsSent()
    {
        return $this->is_sent;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return NewsItem
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return NewsItem
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add translations
     *
     * @param \Wifinder\NewsBundle\Entity\NewsItemTranslation $translations
     * @return NewsItem
     */
    public function addTranslation(\Wifinder\NewsBundle\Entity\NewsItemTranslation $translations)
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
     * @param \Wifinder\NewsBundle\Entity\NewsItemTranslation $translations
     */
    public function removeTranslation(\Wifinder\NewsBundle\Entity\NewsItemTranslation $translations)
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
     * Set meta
     *
     * @param \Wifinder\NewsBundle\Entity\NewsItemMeta $meta
     * @return NewsItem
     */
    public function setMeta(\Wifinder\NewsBundle\Entity\NewsItemMeta $meta = null)
    {
        $this->meta = $meta;
        $meta->setNewsItem($this);
    
        return $this;
    }

    /**
     * Get meta
     *
     * @return \Wifinder\NewsBundle\Entity\NewsItemMeta 
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Add images
     *
     * @param \Wifinder\NewsBundle\Entity\NewsItemImage $images
     * @return NewsItem
     */
    public function addImage(\Wifinder\NewsBundle\Entity\NewsItemImage $images)
    {
        if (!$this->images->contains($images)) {
            $this->images[] = $images;
            $images->setNewsItem($this);
        }
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \Wifinder\NewsBundle\Entity\NewsItemImage $images
     */
    public function removeImage(\Wifinder\NewsBundle\Entity\NewsItemImage $images)
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

    /**
     * Set category
     *
     * @param \Wifinder\NewsBundle\Entity\NewsCategory $category
     * @return NewsItem
     */
    public function setCategory(\Wifinder\NewsBundle\Entity\NewsCategory $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Wifinder\NewsBundle\Entity\NewsCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }
    
    /**
     * Set category_id
     *
     * @param string $category_id
     * @return NewsItem
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    
        return $this;
    }

    /**
     * Get category_id
     *
     * @return string 
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
    
    /**
     * Set $publish
     *
     * @param string $publish
     * @return NewsItem
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;
    
        return $this;
    }

    /**
     * Get publish
     *
     * @return string 
     */
    public function getPublish()
    {
        return $this->publish;
    }
    
    /**
     * Set $end_date
     *
     * @param string $publish
     * @return NewsItem
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    
        return $this;
    }

    /**
     * Get publish
     *
     * @return string 
     */
    public function getEndDate()
    {
        return $this->end_date;
    }
}