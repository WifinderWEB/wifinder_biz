<?php
//src/Wifinder/WebItemBundle/Entity/WebItem.php

namespace Wifinder\WebItemBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="web_item")
 * @Gedmo\TranslationEntity(class="Wifinder\WebItemBundle\Entity\WebItemTranslation")
 * @ORM\Entity(repositoryClass="Wifinder\WebItemBundle\Entity\Repository\WebItemRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"WebItem"})
 */
class WebItem {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column( type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"WebItem"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"WebItem"}
     * )
     */
    protected $alias;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $description;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;
    
    /**
     * @ORM\OneToMany(
     *   targetEntity="WebItemTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;
    
    /**
     * @ORM\ManyToMany(targetEntity="Wifinder\PageBundle\Entity\Content", inversedBy="web_items")
     * @ORM\JoinTable(name="webitem_content")
     */
    protected $join_contents;
    
    /**
     * @ORM\ManyToMany(targetEntity="Wifinder\CatalogBundle\Entity\Catalog", inversedBy="web_items")
     * @ORM\JoinTable(name="webitem_catalog")
     */
    protected $join_catalogs;
    
    /**
     * @ORM\ManyToMany(targetEntity="Wifinder\ProjectBundle\Entity\Project", inversedBy="web_items")
     * @ORM\JoinTable(name="webitem_project")
     */
    protected $join_projects;
    
    protected $action;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->join_contents = new ArrayCollection();
        $this->join_catalogs = new ArrayCollection();
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
     * @return WebItem
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
     * Set description
     *
     * @param string $description
     * @return WebItem
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
     * Set content
     *
     * @param string $content
     * @return WebItem
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
     * Add translations
     *
     * @param \Wifinder\WebItemBundle\Entity\WebItemTranslation $translations
     * @return WebItem
     */
    public function addTranslation(WebItemTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    }

    /**
     * Remove translations
     *
     * @param \Wifinder\WebItemBundle\Entity\WebItemTranslation $translations
     */
    public function removeTranslation(\Wifinder\WebItemBundle\Entity\WebItemTranslation $translations)
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
     * Set is_active
     *
     * @param boolean $isActive
     * @return WebItem
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
     * Add join_contents
     *
     * @param \Wifinder\PageBundle\Entity\Content $joinContents
     * @return WebItem
     */
    public function addJoinContent(\Wifinder\PageBundle\Entity\Content $joinContents)
    {
        $this->join_contents[] = $joinContents;
    
        return $this;
    }

    /**
     * Remove join_contents
     *
     * @param \Wifinder\PageBundle\Entity\Content $joinContents
     */
    public function removeJoinContent(\Wifinder\PageBundle\Entity\Content $joinContents)
    {
        $this->join_contents->removeElement($joinContents);
    }

    /**
     * Get join_contents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoinContents()
    {
        return $this->join_contents;
    }
    
    /**
     * Add join_catalogs
     *
     * @param \Wifinder\CatalogBundle\Entity\Catalog $joinCatalogs
     * @return WebItem
     */
    public function addJoinCatalog(\Wifinder\PageBundle\Entity\Content $joinCatalogs)
    {
        $this->join_catalogs[] = $joinCatalogs;
    
        return $this;
    }

    /**
     * Remove join_catalogs
     *
     * @param \Wifinder\CatalogBundle\Entity\Catalog $joinCatalogs
     */
    public function removeJoinCatalog(\Wifinder\PageBundle\Entity\Content $joinCatalogs)
    {
        $this->join_catalogs->removeElement($joinCatalogs);
    }

    /**
     * Get join_catalogs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoinCatalogs()
    {
        return $this->join_catalogs;
    }
    
    /**
     * Add join_projects
     *
     * @param \Wifinder\ProjectBundle\Entity\Project $joinProjects
     * @return WebItem
     */
    public function addJoinProject(\Wifinder\ProjectBundle\Entity\Project $joinProjects)
    {
        $this->join_projects[] = $joinProjects;
    
        return $this;
    }

    /**
     * Remove join_projects
     *
     * @param \Wifinder\ProjectBundle\Entity\Project $joinProjects
     */
    public function removeJoinProject(\Wifinder\ProjectBundle\Entity\Project $joinProjects)
    {
        $this->join_projects->removeElement($joinProjects);
    }

    /**
     * Get join_projects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJoinProjects()
    {
        return $this->join_projects;
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