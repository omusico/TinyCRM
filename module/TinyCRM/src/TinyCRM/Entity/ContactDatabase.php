<?php

namespace TinyCRM\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections;
use VisoftBaseModule\Entity as VisoftBaseModuleEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="tiny_crm_contact_databases")
 */
class ContactDatabase
{
	/**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    public function getId() { return $this->id; }
}
