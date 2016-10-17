<?php

//src/Wifinder/MenuBundle/Entity/MenuItem.php

namespace Wifinder\MenuBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="menu_item")
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="Wifinder\MenuBundle\Entity\Repository\MenuItemRepository")
 * @Gedmo\TranslationEntity(class="Wifinder\MenuBundle\Entity\MenuItemTranslation")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"MenuItem"})
 */
class MenuItem {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="items")
     * @ORM\JoinColumn(name="menu_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $menu;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $menu_id;
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"MenuItem"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"MenuItem"}
     * )
     */
    protected $alias;

    /**
     * @ORM\Column(type="string", length=250)
     */
    protected $link;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_visible = true;

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
     * @ORM\ManyToOne(targetEntity="MenuItem", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $parent_id;
    
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
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\OneToMany(
     *   targetEntity="MenuItemTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $translations;

    protected $action;

    /**
     * Constructor
     */
    public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return MenuItem
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
     * @return MenuItem
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
     * Get menu_id
     *
     * @return integer 
     */
    public function getMenuId() {
        return $this->menu_id;
    }
    
    /**
     * Set menu_id
     *
     * @return integer 
     */
    public function setMenuId($menuId) {
        $this->menu_id = $menuId;
        
        return $this;
    }
    
    /**
     * Get parent_id
     *
     * @return integer 
     */
    public function getParentId() {
        return $this->parent_id;
    }
    
    /**
     * Set parent_id
     *
     * @return integer 
     */
    public function setParentId($parentId) {
        $this->parent_id = $parentId;
        
        return $this;
    }
    
    /**
     * Set lft
     *
     * @param integer $lft
     * @return MenuItem
     */
    public function setLft($lft) {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer 
     */
    public function getLft() {
        return $this->lft;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     * @return MenuItem
     */
    public function setRgt($rgt) {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer 
     */
    public function getRgt() {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param integer $root
     * @return MenuItem
     */
    public function setRoot($root) {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return integer 
     */
    public function getRoot() {
        return $this->root;
    }

    /**
     * Set level
     *
     * @param integer $level
     * @return MenuItem
     */
    public function setLevel($level) {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer 
     */
    public function getLevel() {
        return $this->level;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return MenuItem
     */
    public function setLink($link) {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return MenuItem
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
     * Set is_visible
     *
     * @param boolean $isVisible
     * @return MenuItem
     */
    public function setIsVisible($isVisible) {
        $this->is_visible = $isVisible;

        return $this;
    }

    /**
     * Get is_visible
     *
     * @return boolean 
     */
    public function getIsVisible() {
        return $this->is_visible;
    }

    /**
     * Set parent
     *
     * @param \Wifinder\MenuBundle\Entity\MenuItem $parent
     * @return MenuItem
     */
    public function setParent(\Wifinder\MenuBundle\Entity\MenuItem $parent = null) {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Wifinder\MenuBundle\Entity\MenuItem 
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Wifinder\MenuBundle\Entity\MenuItem $children
     * @return MenuItem
     */
    public function addChildren(\Wifinder\MenuBundle\Entity\MenuItem $children) {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Wifinder\MenuBundle\Entity\MenuItem $children
     */
    public function removeChildren(\Wifinder\MenuBundle\Entity\MenuItem $children) {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren() {
        return $this->children;
    }

    /**
     * Add translations
     *
     * @param \Wifinder\MenuBundle\Entity\MenuItemTranslation $translations
     * @return MenuItem
     */
    public function addTranslation(MenuItemTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    /**
     * Remove translations
     *
     * @param \Wifinder\MenuBundle\Entity\MenuItemTranslation $translations
     */
    public function removeTranslation(\Wifinder\MenuBundle\Entity\MenuItemTranslation $translations) {
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

    public function __toString() {
        if($this->getTitle())
            return str_repeat("--", $this->getLevel ()) . $this->getTitle();
        else
            return $this->getAlias();
    }


    /**
     * Set menu
     *
     * @param \Wifinder\MenuBundle\Entity\Menu $menu
     * @return MenuItem
     */
    public function setMenu(\Wifinder\MenuBundle\Entity\Menu $menu = null)
    {
        $this->menu = $menu;
    
        return $this;
    }

    /**
     * Get menu
     *
     * @return \Wifinder\MenuBundle\Entity\Menu 
     */
    public function getMenu()
    {
        return $this->menu;
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