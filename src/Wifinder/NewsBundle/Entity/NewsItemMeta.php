<?php
// src/Wifinder/NewsBundle/Entity/NewsItemMeta.php

namespace Wifinder\NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="news_item_meta")
 * @ORM\Entity(repositoryClass="Wifinder\NewsBundle\Entity\Repository\NewsItemMetaRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\NewsBundle\Entity\NewsItemMetaTranslation")
 */
class NewsItemMeta {

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
    protected $meta_title; 
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $meta_keywords;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $meta_description;
    
    /**
     * @ORM\OneToMany(
     *   targetEntity="NewsItemMetaTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;
    
    /**
     * @ORM\OneToOne(targetEntity="NewsItem", inversedBy="meta")
     * @ORM\JoinColumn(name="news_id", referencedColumnName="id")
     */
    protected $news_item;
    
    /**
     * @ORM\Column(name="news_id", type="integer")
     */
    protected $news_id;
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
     * Set meta_title
     *
     * @param string $metaTitle
     * @return NewsItemMeta
     */
    public function setMetaTitle($metaTitle)
    {
        $this->meta_title = $metaTitle;
    
        return $this;
    }

    /**
     * Get meta_title
     *
     * @return string 
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * Set meta_keywords
     *
     * @param string $metaKeywords
     * @return NewsItemMeta
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->meta_keywords = $metaKeywords;
    
        return $this;
    }

    /**
     * Get meta_keywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * Set meta_description
     *
     * @param string $metaDescription
     * @return NewsItemMeta
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;
    
        return $this;
    }

    /**
     * Get meta_description
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Add translations
     *
     * @param \Wifinder\NewsBundle\Entity\NewsItemMetaTranslation $translations
     * @return NewsItemMeta
     */
    public function addTranslation(\Wifinder\NewsBundle\Entity\NewsItemMetaTranslation $translations)
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
     * @param \Wifinder\NewsBundle\Entity\NewsItemMetaTranslation $translations
     */
    public function removeTranslation(\Wifinder\NewsBundle\Entity\NewsItemMetaTranslation $translations)
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
     * @return NewsItemMeta
     */
    public function setNewsItem(\Wifinder\NewsBundle\Entity\NewsItem $news_item = null)
    {
        $this->news_item = $news_item;
    
        return $this;
    }

    /**
     * Get news_item
     *
     * @return \Wifinder\NewsBundle\Entity\NewsItem 
     */
    public function getNewsItem()
    {
        return $this->news_item;
    }

    /**
     * Set news_id
     *
     * @param integer $newsId
     * @return NewsItemMeta
     */
    public function setNewsItemId($newsId)
    {
        $this->news_id = $newsId;
    
        return $this;
    }

    /**
     * Get news_id
     *
     * @return integer 
     */
    public function getNewsItemId()
    {
        return $this->news_id;
    }
}