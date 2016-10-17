<?php

//src/Wifinder/CallbackBundle/Entity/Callback.php

namespace Wifinder\CallbackBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="callback")
 * @ORM\Entity(repositoryClass="Wifinder\CallbackBundle\Entity\Repository\CallbackRepository")
 */
class Callback {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    protected $first_name;
    
    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    protected $middle_name;
    
    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    protected $last_name;

    protected $full_name;
    
    /**
     * @ORM\Column(type="string", length=225)
     * @Assert\NotBlank(message="Required field", groups={"Callback"})
     * @Assert\Email(
     *     message = "The email is not a valid email", groups={"Callback"}
     * )
     */
    protected $email;
    
    /**
     * @ORM\Column(type="string", length=225)
     * @Assert\NotBlank(message="Required field", groups={"Callback"})
     */
    protected $phone;
    
    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    protected $post;

    /**
     * @ORM\Column(type="string", length=225, nullable=true)
     */
    protected $company;

    /**
    * @ORM\OneToMany(
     *   targetEntity="CallbackFile", 
     *   mappedBy="callback", 
     *   cascade={"persist", "remove"})
    */
    private $files;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Required field", groups={"Callback"})
     */
    protected $callback_text;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_received = true;
    
    /**
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

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
     * Set first_name
     *
     * @param string $firstName
     * @return Callback
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
     * @return Callback
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
     * @return Callback
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
     * Set email
     *
     * @param string $email
     * @return Callback
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
     * Set phone
     *
     * @param string $phone
     * @return Callback
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set callback_text
     *
     * @param string $callbackText
     * @return Callback
     */
    public function setCallbackText($callbackText)
    {
        $this->callback_text = $callbackText;
    
        return $this;
    }

    /**
     * Get callback_text
     *
     * @return string 
     */
    public function getCallbackText()
    {
        return $this->callback_text;
    }

    /**
     * Set is_received
     *
     * @param boolean $isReceived
     * @return Callback
     */
    public function setIsReceived($isReceived)
    {
        $this->is_received = $isReceived;
    
        return $this;
    }

    /**
     * Get is_received
     *
     * @return boolean 
     */
    public function getIsReceived()
    {
        return $this->is_received;
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
    
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
    
    public function getAction()
    {
        return $this->action;
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
    
    /**
     * Set post
     *
     * @param string $post
     * @return Callback
     */
    public function setPost($post)
    {
        $this->post = $post;
    
        return $this;
    }

    /**
     * Get post
     *
     * @return string 
     */
    public function getPost()
    {
        return $this->post;
    }
    
    /**
     * Set company
     *
     * @param string $company
     * @return Callback
     */
    public function setCompany($company)
    {
        $this->company = $company;
    
        return $this;
    }

    /**
     * Get company
     *
     * @return string 
     */
    public function getCompany()
    {
        return $this->company;
    }
    
    public function setFullName($full_name){
        $this->full_name = $full_name;
        if($full_name){
            $nameArr = preg_split('/\s+/', $full_name, null, PREG_SPLIT_NO_EMPTY);
            if(isset($nameArr[0]))
                $this->setLastName ($nameArr[0]);
            if(isset($nameArr[1]))
                $this->setFirstName ($nameArr[1]);
            if(isset($nameArr[2]))
                $this->setMiddleName ($nameArr[2]);
        }
        
        return $this;
    }
    
    public function getFullName(){
        return $this->full_name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add files
     *
     * @param \Wifinder\CallbackBundle\Entity\CallbackFile $files
     * @return Callback
     */
    public function addFile(\Wifinder\CallbackBundle\Entity\CallbackFile $files)
    {
        if($files->getFile()){
            $this->files[] = $files;
            $files->setCallback($this);
        }
        return $this;
    }

    /**
     * Remove files
     *
     * @param \Wifinder\CallbackBundle\Entity\CallbackFile $files
     */
    public function removeFile(\Wifinder\CallbackBundle\Entity\CallbackFile $files)
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
}