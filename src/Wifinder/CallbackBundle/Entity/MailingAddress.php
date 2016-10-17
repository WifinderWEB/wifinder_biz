<?php

//src/Wifinder/CallbackBundle/Entity/MailingAddress.php

namespace Wifinder\CallbackBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="mailing_address")
 * @ORM\Entity(repositoryClass="Wifinder\CallbackBundle\Entity\Repository\MailingAddressRepository")
 * @UniqueEntity(fields="email", message="Sorry, this email is already in use.", groups={"MailingAddress"})
 */
class MailingAddress {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @Assert\NotBlank(message="Please enter email.", groups={"MailingAddress"})
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.", groups={"MailingAddress"}
     * )
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    protected $email;
    
    /**
     *
     * @ORM\Column(type="string", length=225, nullable=true)
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z]+$/",
     *       message="Field can contain only letters.", 
     *       groups={"MailingAddress"}
     * )
     */
    protected $first_name;
    
    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z]+$/",
     *       message="Field can contain only letters.", 
     *       groups={"MailingAddress"}
     * )
     */
    protected $middle_name;
    
    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z]+$/",
     *       message="Field can contain only letters.", 
     *       groups={"MailingAddress"}
     * )
     */
    protected $last_name;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

    /**
     * @ORM\ManyToMany(targetEntity="Wifinder\CallbackBundle\Entity\EmailType", inversedBy="addresses")
     * @ORM\JoinTable(name="emailtype_mailingaddress")
     */
    private $type;
    
    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_confirm = true;
    
    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    protected $confirm_code;
    
    protected $action;
    
    protected $captcha;
    
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
     * Set email
     *
     * @param string $email
     * @return MailingAddress
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return MailingAddress
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
     * Constructor
     */
    public function __construct()
    {
        $this->type = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set first_name
     *
     * @param string $firstName
     * @return MailingAddress
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
    
        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set middle_name
     *
     * @param string $middleName
     * @return MailingAddress
     */
    public function setMiddleName($middleName)
    {
        $this->middle_name = $middleName;
    
        return $this;
    }

    /**
     * Get middle_name
     *
     * @return string 
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return MailingAddress
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;
    
        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Add type
     *
     * @param \Wifinder\$targetClassBundle\Entity\EmailType $type
     * @return MailingAddress
     */
    public function addType(\Wifinder\CallbackBundle\Entity\EmailType $type)
    {
        $this->type[] = $type;
    
        return $this;
    }
    
     public function setType(\Wifinder\CallbackBundle\Entity\EmailType $type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Remove type
     *
     * @param \Wifinder\CallbackBundle\Entity\EmailType $type
     */
    public function removeType(\Wifinder\CallbackBundle\Entity\EmailType $type)
    {
        $this->type->removeElement($type);
    }
    
     /**
     * Get type
     *
     * @return \Wifinder\CallbackBundle\Entity\EmailType 
     */
    public function getType()
    {
        return $this->type;
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
    
    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Callback
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
     * Set is_confirm
     *
     * @param boolean $isConfirm
     * @return MailingAddress
     */
    public function setIsConfirm($isConfirm)
    {
        $this->is_confirm = $isConfirm;
    
        return $this;
    }

    /**
     * Get is_confirm
     *
     * @return boolean 
     */
    public function getIsConfirm()
    {
        return $this->is_confirm;
    }
    
    /**
     * Set confirm_code
     *
     * @param string $confirmCode
     * @return MailingAddress
     */
    public function setConfirmCode($confirmCode)
    {
        $this->confirm_code = $confirmCode;
    
        return $this;
    }

    /**
     * Get confirm_code
     *
     * @return string 
     */
    public function getConfirmCode()
    {
        return $this->confirm_code;
    }
    
    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;
        return $this;
    }
    
    public function getCaptcha()
    {
        return $this->captcha;
    }
}