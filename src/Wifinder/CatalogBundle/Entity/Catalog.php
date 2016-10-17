<?php

//src/Wifinder/CatalogBundle/Entity/Catalog.php

namespace Wifinder\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="catalog")
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Wifinder\CatalogBundle\Entity\Repository\CatalogRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\CatalogBundle\Entity\CatalogTranslation")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Catalog"})
 */
class Catalog {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"Catalog"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"Catalog"}
     * )
     */
    protected $alias;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $short_description;
    
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
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Catalog", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(type="integer", nullable=true)
     */
    private $root;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="Catalog", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\OneToMany(
     *   targetEntity="CatalogTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="CatalogImage", 
     *   mappedBy="catalog", 
     *   cascade={"persist", "remove"})
    */
    private $images;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="CatalogFile", 
     *   mappedBy="catalog", 
     *   cascade={"persist", "remove"})
    */
    private $files;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="CatalogType", 
     *   inversedBy="catalog")
     * @ORM\JoinColumn(
     *   name="catalog_type_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    private $catalog_type;
    
    /**
     * @ORM\OneToOne(targetEntity="CatalogMeta", 
     *   mappedBy="catalog", 
     *   cascade={"persist", "remove"})
    */
    protected $meta;

    /**
     * @ORM\ManyToMany(targetEntity="Wifinder\WebItemBundle\Entity\WebItem", inversedBy="join_catalogs")
     * @ORM\JoinTable(name="webitem_catalog")
     */
    protected $web_items;
    
    protected $action;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->web_items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Catalog
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
     * @return Catalog
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
     * Set short_description
     *
     * @param string $shortDescription
     * @return Catalog
     */
    public function setShortDescription($shortDescription)
    {
        $this->short_description = $shortDescription;
    
        return $this;
    }

    /**
     * Get short_description
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Catalog
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
     * @return Catalog
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
     * Set lft
     *
     * @param integer $lft
     * @return Catalog
     */
    public function setLft($lft)
    {
        $this->lft = $lft;
    
        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return Catalog
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;
    
        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return Catalog
     */
    public function setRoot($root)
    {
        $this->root = $root;
    
        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return Catalog
     */
    public function setLevel($level)
    {
        $this->level = $level;
    
        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set parent
     *
     * @param \Wifinder\CatalogBundle\Entity\Catalog $parent
     * @return Catalog
     */
    public function setParent(\Wifinder\CatalogBundle\Entity\Catalog $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Wifinder\CatalogBundle\Entity\Catalog 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Wifinder\CatalogBundle\Entity\Catalog $children
     * @return Catalog
     */
    public function addChildren(\Wifinder\CatalogBundle\Entity\Catalog $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \Wifinder\CatalogBundle\Entity\Catalog $children
     */
    public function removeChildren(\Wifinder\CatalogBundle\Entity\Catalog $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add translations
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogTranslation $translations
     * @return Catalog
     */
    public function addTranslation(\Wifinder\CatalogBundle\Entity\CatalogTranslation $t)
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
     * @param \Wifinder\CatalogBundle\Entity\CatalogTranslation $translations
     */
    public function removeTranslation(\Wifinder\CatalogBundle\Entity\CatalogTranslation $translations)
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
        if($this->getTitle())
            return str_repeat("--", $this->getLevel ()) . $this->getTitle();
        else
            return $this->getAlias();
    }

    /**
     * Add images
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogImage $images
     * @return Catalog
     */
    public function addImage(\Wifinder\CatalogBundle\Entity\CatalogImage $images)
    {
        if (!$this->images->contains($images)) {
            $this->images[] = $images;
            $images->setCatalog($this);
        }

        return $this;
    }

    /**
     * Remove images
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogImage $images
     */
    public function removeCatalogImage(\Wifinder\CatalogBundle\Entity\CatalogImage $images)
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
     * Remove images
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogImage $images
     */
    public function removeImage(\Wifinder\CatalogBundle\Entity\CatalogImage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Add files
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogFile $files
     * @return Catalog
     */
    public function addFile(\Wifinder\CatalogBundle\Entity\CatalogFile $files)
    {
        if (!$this->files->contains($files)) {
            $this->files[] = $files;
            $files->setCatalog($this);
        }
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogFile $files
     */
    public function removeFile(\Wifinder\CatalogBundle\Entity\CatalogFile $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set catalog_type
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogType $catalogType
     * @return Catalog
     */
    public function setCatalogType(\Wifinder\CatalogBundle\Entity\CatalogType $catalogType = null)
    {
        $this->catalog_type = $catalogType;
    
        return $this;
    }

    /**
     * Get catalog_type
     *
     * @return \Wifinder\CatalogBundle\Entity\CatalogType 
     */
    public function getCatalogType()
    {
        return $this->catalog_type;
    }

    /**
     * Set meta
     *
     * @param \Wifinder\CatalogBundle\Entity\CatalogMeta $meta
     * @return Catalog
     */
    public function setMeta(\Wifinder\CatalogBundle\Entity\CatalogMeta $meta = null)
    {
        $this->meta = $meta;
        $meta->setCatalog($this);
        
        return $this;
    }

    /**
     * Get meta
     *
     * @return \Wifinder\CatalogBundle\Entity\CatalogMeta 
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
    
    public function set($field, $value){
        $t = explode('_', $field);
        for($i = 0; $i <= count($t)-1; $i++){
            $t[$i] = ucfirst($t[$i]); 
        }
        call_user_func(array($this, 'set'.implode('', $t)), $value);
        return $this;
    }
}