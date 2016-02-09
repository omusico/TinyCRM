<?php

namespace TinyCRM\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections;
use VisoftBaseModule\Entity as VisoftBaseModuleEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="tiny_crm_users")
 */
class User implements VisoftBaseModuleEntity\UserInterface
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
     * @var UserInterface
     * @ORM\ManyToOne(targetEntity="VisoftBaseModule\Entity\UserInterface")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id", nullable=true,  unique=false, onDelete="SET NULL")
     */
    protected $createdBy;

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

	public function setProviderId($providerName, $providerId) {}
	public function getProviderId($providerName) {}

	public function setAvatar(VisoftBaseModuleEntity\Image $avatar) {}
	public function getAvatar() {}

	public function setRole(VisoftBaseModuleEntity\UserRole $role) {}
	public function getRole() {}

	public function getCreatedBy() {  return $this->createdBy; }
	public function setCreatedBy(VisoftBaseModuleEntity\UserInterface $user) {
		$this->createdBy = $user;
		return $this; 
	}
}
