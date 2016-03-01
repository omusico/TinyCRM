<?php

namespace TinyCRM\Form;

use Doctrine\ORM\EntityManager,
	DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class UserForm extends \VisoftBaseModule\Form\BaseForm
{
	public function __construct($entityManager, $formType, $identity = null)
	{
		parent::__construct($entityManager);
        $this->setHydrator(new DoctrineHydrator($entityManager));
		$this->setAttribute('method', 'post');
        // $this->addText('route', 'Route to page', 'label', false, false, '/route/to/example/page');
        switch ($formType) {
            case 'create':
                $this->setCreateForm('Register new employee');
                break;
            case 'edit':
                $this->setEditForm('Register employee profile data');
                break;
            case 'password-reset':
                $this->setPasswordResetForm('Reset password');
                break;
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
            $label = 'Full name', 
            $labelClass = 'col-sm-2 control-label',
            $id = null,
            $required = false,
            $placeholder = 'Full name'
        );

        $this->addText(
            $name = 'email', 
            $label = 'Email', 
            $labelClass = 'col-sm-2 control-label',
            $id = null,
            $required = false,
            $placeholder = 'Email'
        );

        $this->addPassword(
            $name = 'password', 
            $label = 'Password', 
            $labelClass = 'col-sm-2 control-label',
            $id = null,
            $required = false,
            $placeholder = 'Password'
        );

        $this->addSelectRole(
            $name = 'role',
            $labelClass = 'col-sm-2 control-label'
        );
        
        $this->addSubmit(
            $name = 'submit', 
            $value = 'Save', 
            $class = 'btn btn-info'
        );
    }

    public function setEditForm($title) 
    {
        $this->setCreateForm($title);
    }

    public function setPasswordResetForm($title) 
    {
        $this->addPassword(
            $name = 'password', 
            $label = 'New password', 
            $labelClass = 'col-sm-2 control-label',
            $id = null,
            $required = false,
            $placeholder = 'New password'
        );

        $this->addSubmit(
            $name = 'submit', 
            $value = 'Save', 
            $class = 'btn btn-info'
        );
    }
}