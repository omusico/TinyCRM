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

    /**
     * @var boolean
     * @ORM\Column(name="individual", type="boolean", nullable=true)
     */
    private $individual;

    /**
     * @ORM\ManyToMany(targetEntity="VisoftBaseModule\Entity\UserInterface")
     * @ORM\JoinTable(name="tiny_crm_managers_to_database",
     *      joinColumns={@ORM\JoinColumn(name="database_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $managers;


   	public function __construct() {
   		$this->createdAt = new \DateTime();
   		$this->contacts = new Collections\ArrayCollection();
        $this->managers = new Collections\ArrayCollection();
   	}

    public function getId() { return $this->id; }
    public function getCreatedAt() { return $this->createdAt; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getIndividual() { return $this->individual; }
    public function setIndividual($individual) { $this->individual = $individual; }

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

    public function getManagers() { return $this->managers; }
    public function addManagers($managers) {
        if(is_array($managers) || $managers instanceof Traversable 
            || $managers instanceof Collections\ArrayCollection 
            || $managers instanceof \Doctrine\ORM\PersistentCollection)
            foreach ($managers as $manager) 
                $this->managers->add($manager);
        elseif($managers instanceof \VisoftMailerModule\Entity\MailingListInterface)
            $this->managers->add($managers);
        else 
            throw new \Exception("Bad argument", 1);
        return $this;
    }
    public function removeManagers($managers) {
        foreach ($managers as $manager) 
            $this->managers->removeElement($manager);
        // return $this;
    }
}
