<?php

namespace TinyCRM\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections;

/**
 * @ORM\Entity(repositoryClass="TinyCRM\Entity\Repository\ContactRepository")
 * @ORM\Table(name="tiny_crm_contacts")
 */
class Contact implements \VisoftMailerModule\Entity\ContactInterface
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
     * @var string
     * @ORM\Column(name="phone", type="string", length=255, nullable=true, unique=false)
     */
    protected $phone;

    /**
     * @var string
     * @ORM\Column(name="token", type="string", length=255, nullable=true, unique=false)
     */
    protected $token;

    /**
     * @ORM\ManyToOne(targetEntity="VisoftMailerModule\Entity\ContactState")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=true, onDelete="SET NULL", unique=false)
     */
    protected $state;

    /**
     * @ORM\ManyToMany(targetEntity="VisoftMailerModule\Entity\DatabaseInterface", inversedBy="contacts")
     * @ORM\JoinTable(name="tiny_crm_association_contact_to_database",
     *      joinColumns={@ORM\JoinColumn(name="contact_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="database_id", referencedColumnName="id", onDelete="CASCADE")}
     * )
     */
    private $databases;

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

    /**
     * @var UserInterface
     * @ORM\ManyToOne(targetEntity="VisoftBaseModule\Entity\UserInterface")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id", nullable=true,  unique=false, onDelete="SET NULL")
     */
    protected $manager;

    /**
     * @var boolean
     * @ORM\Column(name="hold", type="boolean", nullable=true)
     */
    private $hold;

    /**
     * @var \DateTime
     * @ORM\Column(name="time", type="datetime", nullable=true)
     */
    protected $time;

    public function __construct() {
        $this->token = md5(uniqid(mt_rand(), true));
        $this->createdAt = new \DateTime();
        $this->databases = new Collections\ArrayCollection();
        $this->hold = false;
    }

    public function getId() { return $this->id; }

    public function getToken() { return $this->token; }
    public function getCreatedAt() { return $this->createdAt; }

	public function getFullName() { return $this->fullName; }
	public function setFullName($fullName) { $this->fullName = $fullName; }

    public function getEmail()  { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getPhone()  { return $this->phone; }
    public function setPhone($phone) { $this->phone = $phone; }

    public function getState() { return $this->state; }
    public function setState($state) { $this->state = $state; }

    public function getHold() { return $this->hold; }
    public function setHold($hold) { $this->hold = $hold; }

    public function getTime() { return $this->time; }
    public function setTime($time) { $this->time = $time; }

	public function getCreatedBy() {  return $this->createdBy; }
	public function setCreatedBy(\VisoftBaseModule\Entity\UserInterface $user) { $this->createdBy = $user; }

    public function setInfo(array $info) {
        if(isset($info['full-name'])) 
            $this->fullName = $info['full-name'];
        if(isset($info['email'])) 
            $this->email = $info['email'];
        if(isset($info['phone'])) 
            $this->phone = $info['phone'];
    }
    public function getInfo() {
        $info['full-name'] = $this->fullName;
        $info['email'] = $this->email;
        $info['phone'] = $this->phone;
        return $info;
    }

    public function getManager() { return $this->manager; }
    public function setManager($manager) { $this->manager = $manager; }

    public function getSubscribedOnMailingLists() { return $this->databases; }
    public function addSubscribedOnMailingList($mailingList) {}
    public function addSubscribedOnMailingLists($databases) {
        if(is_array($databases) || $databases instanceof Traversable 
            || $databases instanceof Collections\ArrayCollection 
            || $databases instanceof \Doctrine\ORM\PersistentCollection)
            foreach ($databases as $database) 
                $this->databases->add($database);
        elseif($databases instanceof \VisoftMailerModule\Entity\MailingListInterface)
            $this->databases->add($databases);
        else 
            throw new \Exception("Bad argument", 1);
        return $this;
    }
    public function removeSubscribedOnMailingList($mailingList) {}
    public function removeSubscribedOnMailingLists($mailingLists) {}

    public function getUnsubscribedFromMailingLists() {}
    public function addUnsubscribedFromMailingList($mailingList) {}
    public function addUnsubscribedFromMailingLists($mailingLists) {}
    public function removeUnsubscribedFromMailingList($mailingList) {}
    public function removeUnsubscribedFromMailingLists($mailingLists) {}

    public function removeAllSubscribtions() {}
}
