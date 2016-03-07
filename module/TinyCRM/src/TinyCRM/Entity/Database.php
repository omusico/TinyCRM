<?php

namespace TinyCRM\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections;

/**
 * @ORM\Entity(repositoryClass="TinyCRM\Entity\Repository\DatabaseRepository")
 * @ORM\Table(name="tiny_crm_databases")
 */
class Database implements \VisoftMailerModule\Entity\DatabaseInterface
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true, unique=false)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="VisoftMailerModule\Entity\ContactInterface", mappedBy="databases")
     */
    private $contacts;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="VisoftBaseModule\Entity\UserInterface")
     * @ORM\JoinColumn(name="created_by_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $createdBy;

   	public function __construct() {
   		$this->createdAt = new \DateTime();
   		$this->contacts = new Collections\ArrayCollection();
   	}

    public function getId() { return $this->id; }
    public function getCreatedAt() { return $this->createdAt; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getCreatedBy() { return $this->createdBy; }
    public function setCreatedBy($createdBy) {
        $this->createdBy = $createdBy;
        return $this;
    }

    public function getContacts() { return $this->contacts; }
    // public function clearFranchisors() {
    //     $this->franchisors->clear();
    //     return $this;
    // }
    // public function addFranchisor(UserInterface $user) {
    //     $this->franchisors->add($user);
    //     return $this;
    // }
    public function addContacts($contacts) {
        if(is_array($contacts) 
            || $contacts instanceof Traversable 
            || $contacts instanceof \Doctrine\Common\Collections\ArrayCollection
            || $contacts instanceof \Doctrine\ORM\PersistentCollection
        )
            foreach ($contacts as $user) 
                $this->contacts->add($user);
        elseif($contacts instanceof \VisoftBaseModule\Entity\ContactInterface)
            $this->contacts->add($contacts);
        else 
            throw new \Exception("Expected to be instance of UserInterface or Traversable", 1);
        return $this;
    }
    // public function removeFranchisor(UserInterface $user) {
    //     $this->franchisors->removeElement($user);
    //     return $this;
    // }
    public function removeContacts($contacts) {
        foreach ($contacts as $contact) 
            $this->contacts->removeElement($contact);
        return $this;
    }
}
