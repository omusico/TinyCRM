<?php

namespace TinyCRM\Form;

use Doctrine\ORM\EntityManager,
	DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class AuthenticationForm extends \VisoftBaseModule\Form\BaseForm
{
	public function __construct($entityManager, $formType, $identity = null)
	{
		parent::__construct($entityManager);
        $this->setHydrator(new DoctrineHydrator($entityManager));
		$this->setAttribute('method', 'post');
        // $this->addText('route', 'Route to page', 'label', false, false, '/route/to/example/page');
        switch ($formType) {
            case 'sign-in':
                $this->setSignInForm('Sign in to start your session');
                break;
            // case 'edit':
            //     $this->setLayoutV1Form('Edit layout v.1');
            //     break;
            default:
                # code...
                break;
        }
    }

    public function setSignInForm($title)//$action)
    {
        $this->setTitle($title);
        $this->setAttributes([
            // 'class' => 'form-horizontal',
        ]);

        $this->addText(
            $name = 'email', 
            $label = 'Email', 
            $labelClass = 'label',
            $id = null,
            $required = false,
            $placeholder = 'Email'
        );

        $this->addPassword(
            $name = 'password', 
            $label = 'Password', 
            $labelClass = 'label',
            $id = null,
            $required = false,
            $placeholder = 'Password'
        );
        
        $this->addSubmit(
            $name = 'submit', 
            $value = 'Sign In', 
            $class = 'btn btn-primary btn-block btn-flat'
        );
    }
}