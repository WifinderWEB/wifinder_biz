<?php

//src/Wifinder/CallbackBundle/Entity/EmailType.php

namespace Wifinder\CallbackBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="email_type")
 * @ORM\Entity(repositoryClass="Wifinder\CallbackBundle\Entity\Repository\EmailTypeRepository")
 */
class EmailType {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    protected $type;
    
    /**
     * @ORM\ManyToMany(targetEntity="Wifinder\CallbackBundle\Entity\MailingAddress", inversedBy="type")
     */
    protected $addresses;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addresses = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set type
     *
     * @param string $type
     * @return EmailType
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add addresses
     *
     * @param \Wifinder\CallbackBundle\Entity\MailingAddress $addresses
     * @return EmailType
     */
    public function addAddresse(\Wifinder\CallbackBundle\Entity\MailingAddress $addresses)
    {
        $this->addresses[] = $addresses;
    
        return $this;
    }

    /**
     * Remove addresses
     *
     * @param \Wifinder\CallbackBundle\Entity\MailingAddress $addresses
     */
    public function removeAddresse(\Wifinder\CallbackBundle\Entity\MailingAddress $addresses)
    {
        $this->addresses->removeElement($addresses);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
    
    public function __toString() {
        return $this->getType();
    }
}