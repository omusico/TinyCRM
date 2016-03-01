<?php

namespace TinyCRM\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections;
use VisoftBaseModule\Entity as VisoftBaseModuleEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="tiny_crm_users")
 */
class User implements \VisoftBaseModule\Entity\UserInterface
{
	/**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="full_name", type="string", length=255, nullable=true, unique=false)
     */
    protected $fullName;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=true, unique=false)
     */
    protected $email;

    /**
     * @ORM\OneToOne(targetEntity="VisoftBaseModule\Entity\Image")
     * @ORM\JoinColumn(name="image")
     */
    protected $image;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, nullable=true, unique=false)
     */
    protected $password;

    /**
     * @ORM\ManyToOne(targetEntity="VisoftBaseModule\Entity\UserRole")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=true, onDelete="SET NULL", unique=false)
     */
    protected $role;

    /**
     * @var string
     * @ORM\Column(name="token", type="string", length=255, nullable=true, unique=false)
     */
    protected $token;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var UserInterface
     * @ORM\ManyToOne(targetEntity="VisoftBaseModule\Entity\UserInterface")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id", nullable=true,  unique=false, onDelete="SET NULL")
     */
    protected $createdBy;

    public function __construct() {
        $this->createdAt = new \DateTime();
    }

    public function getId() { return $this->id; }

    public function getRegistrationToken() { return $this->registrationToken; }
    public function setRegistrationToken($registrationToken) {
        $this->registrationToken = $registrationToken;
        return $this;
    }

	public function getToken() {}

	public function getFullName() { return $this->fullName; }
	public function setFullName($fullName) {
		$this->fullName = $fullName;
		return $this;
	}

    public function getEmail() { return $this->email; }
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setImage(\VisoftBaseModule\Entity\Image $avatar) { $this->image = $avatar; }
    public function getImage() { return $this->image; }

    public function getPassword() { return $this->password; }
    public function setPassword($password) {
        $this->password = \VisoftBaseModule\Service\RegistrationService::encryptPassword($password);
        return $this;
    }

    public function getRole() { return $this->role; }
    public function setRole(\VisoftBaseModule\Entity\UserRole $role) { 
        $this->role = $role; 
        return $this;
    }

	public function setProviderId($providerName, $providerId) {}
	public function getProviderId($providerName) {}

	public function getCreatedBy() {  return $this->createdBy; }
	public function setCreatedBy(VisoftBaseModuleEntity\UserInterface $user) {
		$this->createdBy = $user;
		return $this; 
	}
}
