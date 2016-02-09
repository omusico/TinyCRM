<?php

namespace TinyCRM\Form;

use Doctrine\ORM\EntityManager,
	DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

use VisoftBaseModule\Form\BaseForm;

class UserForm extends BaseForm
{
	public function __construct($entityManager, $formType, $identity)
	{
		parent::__construct($entityManager);
        $this->setHydrator(new DoctrineHydrator($entityManager));
		$this->setAttribute('method', 'post');
        // $this->addText('route', 'Route to page', 'label', false, false, '/route/to/example/page');
        switch ($formType) {
            case 'create':
                $this->setCreateForm('Register new employee');
                break;
            // case 'edit':
            //     $this->setLayoutV1Form('Edit layout v.1');
            //     break;
            default:
                # code...
                break;
        }
    }

    public function setCreateForm($title)//$action)
    {
        $this->setTitle($title);
        $this->setAttributes([
            'class' => 'form-horizontal',
        ]);

        $this->addText(
            $name = 'fullName', 
            $label = 'Full name ', 
            $labelClass = 'col-sm-2 control-label',
            $id = null,
            $required = false,
            $placeholder = 'Full name'
        );
        
        $this->addSubmit(
            $name = 'submit', 
            $value = 'Save', 
            $class = 'btn btn-info'
        );
    }
}