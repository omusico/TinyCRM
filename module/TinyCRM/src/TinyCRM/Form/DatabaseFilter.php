<?php 

namespace TinyCRM\Form;

use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\InputFilter;

use Doctrine\ORM\EntityManager;

class DatabaseFilter extends InputFilter
{
	protected $entityManager;

	public function __construct(EntityManager $entityManager, $type, $identity = null)
	{
		$this->entityManager = $entityManager;

        switch ($type) {
        	case 'create':
        		$this->addCreateInputFilter();
        		break;

        	case 'edit':
        		$this->addEditInputFilter();
        		break;
        	
        	default:
        		# code...
        		break;
        }
	}

	private function addCreateInputFilter()
	{
		$this->add(array(
            'name' => 'managers',
            'required' => false,
        ));
	}

	private function addEditInputFilter()
	{
		$this->addCreateInputFilter();
	}
}