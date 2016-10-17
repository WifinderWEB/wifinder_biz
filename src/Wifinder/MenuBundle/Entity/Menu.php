<?php

//src/Wifinder/MenuBundle/Entity/Menu.php

namespace Wifinder\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="Wifinder\MenuBundle\Entity\Repository\MenuRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Menu"})
 */
class Menu {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter name menu.", groups={"Menu"})
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"Menu"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"Menu"}
     * )
     *@Assert\Length(
     *     min = "4",
     *     max = "50",
     *     minMessage="Alias must have at least {{ limit }} characters.|Alias must have at least {{ limit }} characters.",
     *     maxMessage="Alias must have at most {{ limit }} characters.|Alias must have at most {{ limit }} characters.",
     *     groups={"Menu"}
     * )
     */
    protected $alias;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="MenuItem", 
     *   mappedBy="menu", 
     *   cascade={"persist", "remove"})
    */
    protected $items;
    
    protected $action;

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
     * Set name
     *
     * @param string $name
     * @return Menu
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return Menu
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
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Menu
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
     * Add items
     *
     * @param \Wifinder\MenuBundle\Entity\MenuItem $items
     * @return Menu
     */
    public function addItem(\Wifinder\MenuBundle\Entity\MenuItem $items)
    {
        $this->items[] = $items;
    
        return $this;
    }

    /**
     * Remove items
     *
     * @param \Wifinder\MenuBundle\Entity\MenuItem $items
     */
    public function removeItem(\Wifinder\MenuBundle\Entity\MenuItem $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }
    
    public function __toString() {
        return $this->getName();
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