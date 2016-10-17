<?php

//src/Wifinder/PageBundle/Entity/Content.php

namespace Wifinder\PageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="content")
 * @Gedmo\TranslationEntity(class="Wifinder\PageBundle\Entity\ContentTranslation")
 * @ORM\Entity(repositoryClass="Wifinder\PageBundle\Entity\Repository\ContentRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Content"})
 */
class Content {

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
     * @Assert\NotBlank(message="Please enter alias.", groups={"Content"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"Content"}
     * )
     */
    protected $alias;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */ 
    protected $content;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $show_editor = true;
    
    /**
     * @ORM\OneToMany(
     *   targetEntity="ContentTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;
    
    /**
     * @ORM\OneToOne(targetEntity="ContentMeta", 
     *   mappedBy="content", 
     *   cascade={"persist", "remove"})
    */
    protected $meta;
    
    /**
     * @ORM\ManyToMany(targetEntity="Wifinder\WebItemBundle\Entity\WebItem", inversedBy="join_contents")
     * @ORM\JoinTable(name="webitem_content")
     */
    protected $web_items;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Wifinder\LayoutBundle\Entity\Layout", 
     *   inversedBy="pages")
     * @ORM\JoinColumn(
     *   name="layout_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $layout;
    
    protected $action;
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Content
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
     * Set alias
     *
     * @param string $alias
     * @return Content
     */
    public function setAlias($alias) {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias() {
        return $this->alias;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Content
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }
    
    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Content
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

    public function __toString() {
        if($this->getTitle())
            return $this->getTitle();
        else
            return $this->getAlias (); 
    }


    /**
     * Remove translations
     *
     * @param \Wifinder\PageBundle\Entity\ContentTranslation $translations
     */
    public function removeTranslation(\Wifinder\PageBundle\Entity\ContentTranslation $translations)
    {
        $this->translations->removeElement($translations);
    }
    
    public function __construct() {
        $this->translations = new ArrayCollection();
        $this->web_items = new ArrayCollection();
    }

    public function getTranslations() {
        return $this->translations;
    }

    public function addTranslation(ContentTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }
    
    /**
     * Set meta
     *
     * @param \Wifinder\PageBundle\Entity\ContentMeta $meta
     * @return Catalog
     */
    public function setMeta(\Wifinder\PageBundle\Entity\ContentMeta $meta = null)
    {
        $this->meta = $meta;
        $meta->setContent($this);
        
        return $this;
    }

    /**
     * Get meta
     *
     * @return \Wifinder\PageBundle\Entity\ContentMeta 
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Add web_items
     *
     * @param \Wifinder\WebItemBundle\Entity\WebItem $webItems
     * @return Content
     */
    public function addWebItem(\Wifinder\WebItemBundle\Entity\WebItem $webItems)
    {
        $this->web_items[] = $webItems;
    
        return $this;
    }

    /**
     * Remove web_items
     *
     * @param \Wifinder\WebItemBundle\Entity\WebItem $webItems
     */
    public function removeWebItem(\Wifinder\WebItemBundle\Entity\WebItem $webItems)
    {
        $this->web_items->removeElement($webItems);
    }

    /**
     * Get web_items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWebItems()
    {
        return $this->web_items;
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
     * Set layout
     *
     * @param \Wifinder\LayoutBundle\Entity\Layout $layout
     * @return Content
     */
    public function setLayout(\Wifinder\LayoutBundle\Entity\Layout $layout = null)
    {
        $this->layout = $layout;
    
        return $this;
    }

    /**
     * Get layout
     *
     * @return \Wifinder\LayoutBundle\Entity\Layout 
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set show_editor
     *
     * @param boolean $showEditor
     * @return Content
     */
    public function setShowEditor($showEditor)
    {
        $this->show_editor = $showEditor;
    
        return $this;
    }

    /**
     * Get show_editor
     *
     * @return boolean 
     */
    public function getShowEditor()
    {
        return $this->show_editor;
    }
}