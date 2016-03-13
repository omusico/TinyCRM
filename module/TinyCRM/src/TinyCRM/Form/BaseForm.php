<?php

namespace TinyCRM\Form;

use Doctrine\ORM\EntityManager,
	DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class BaseForm extends \VisoftBaseModule\Form\BaseForm
{
	public function __construct(EntityManager $entityManager)
	{
		parent::__construct($entityManager);
	}

	public function addMultiCheckboxManagers($name, $id = '')
	{
		$this->add([
            'name' => $name,
            'type' => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'attributes' => [
                'id' => $id,
            ],
            'options' => array(
                'label' => 'Select managers',
                'label_attributes' => array(
                	'class'  => 'col-md-2 col-sm-4 col-xs-6'
                ),
                'object_manager' => $this->entityManager,
                'target_class' => 'VisoftBaseModule\Entity\UserInterface',
                'property' => 'fullName',
                'is_method' => true,
                'find_method' => array(
                    'name'   => 'findBy',
                    'params' => array(
                        'criteria' => array(),
                        'orderBy'  => array('fullName' => 'ASC'),
                    ),
                ),
            ),
        ]);
	}
}
