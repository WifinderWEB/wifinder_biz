<?php

//src/Wifinder/ProjectBundle/Entity/Years.php

namespace Wifinder\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="years")
 * @ORM\Entity(repositoryClass="Wifinder\ProjectBundle\Entity\Repository\YearsRepository")
 * @UniqueEntity(fields="alias", message="Sorry, this year is already in use.", groups={"Years"})
 */
class Years {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="integer", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter year.", groups={"Years"})
     * @Assert\Regex( 
     *       pattern="/^[0-9]+$/",
     *       message="Year can contain only numbers.", 
     *       groups={"Years"}
     * )
     */
    protected $alias;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="Project", 
     *   mappedBy="years", 
     *   cascade={"persist", "remove"})
    */
    protected $project;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->project = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param integer $alias
     * @return Years
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    
        return $this;
    }

    /**
     * Get alias
     *
     * @return integer 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Add project
     *
     * @param \Wifinder\ProjectBundle\Entity\Project $project
     * @return Years
     */
    public function addProject(\Wifinder\ProjectBundle\Entity\Project $project)
    {
        $this->project[] = $project;
    
        return $this;
    }

    /**
     * Remove project
     *
     * @param \Wifinder\ProjectBundle\Entity\Project $project
     */
    public function removeProject(\Wifinder\ProjectBundle\Entity\Project $project)
    {
        $this->project->removeElement($project);
    }

    /**
     * Get project
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProject()
    {
        return $this->project;
    }
    
    public function __toString() {
        return strval($this->getAlias());
    }
}