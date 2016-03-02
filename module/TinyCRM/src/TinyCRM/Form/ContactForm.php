<?php

namespace TinyCRM\Form;

use Doctrine\ORM\EntityManager,
	DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ContactForm extends \VisoftBaseModule\Form\BaseForm
{
	public function __construct($entityManager, $formType, $identity = null)
	{
		parent::__construct($entityManager);
        $this->setHydrator(new DoctrineHydrator($entityManager));
		$this->setAttribute('method', 'post');
        // $this->addText('route', 'Route to page', 'label', false, false, '/route/to/example/page');
        switch ($formType) {
            case 'create':
                $this->setCreateForm('Register new contact');
                break;
            case 'edit':
                $this->setEditForm('Edit contact data');
                break;
            case 'move-to-schedule':
                $this->setMoveScheduleForm('Move contact to schedule');
                break;
            case 'edit-comment':
                $this->setEditCommentForm('Edit comment');
                break;
            case 'closed':
                $this->setClosedForm('Amount of deal');
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

        $this->addSelectState(
            $name = 'state', 
            $labelClass = 'col-sm-2 control-label'
        );

        $this->addText(
            $name = 'time', 
            $label = 'Time', 
            $labelClass = 'col-sm-2 control-label',
            $id = 'datetimepicker',
            $required = false,
            $placeholder = 'Time'
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
        $this->setTitle($title);

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

    public function setMoveScheduleForm($title)
    {
        $this->setTitle($title);
        $this->addText(
            $name = 'time', 
            $label = 'Time', 
            $labelClass = 'col-sm-2 control-label',
            $id = 'datetimepicker',
            $required = false,
            $placeholder = 'Select date and time you want to move'
        );
        $this->addSubmit(
            $name = 'submit', 
            $value = 'Move', 
            $class = 'btn btn-info'
        );
    }

    public function setEditCommentForm($title)
    {
        $this->setTitle($title);
        $this->addTextarea(
            $name = 'comment', 
            $label = 'Comment',
            $labelClass = 'col-sm-2 control-label'
        );
        $this->addSubmit(
            $name = 'submit', 
            $value = 'Update', 
            $class = 'btn btn-info'
        );
    }

    public function setClosedForm($title)
    {
        $this->setTitle($title);

        $this->addText(
            $name = 'closedAmount', 
            $label = 'Amount', 
            $labelClass = 'col-sm-2 control-label',
            $id = 'null',
            $required = false,
            $placeholder = 'Amount of deal'
        );
        $this->addSubmit(
            $name = 'submit', 
            $value = 'Close', 
            $class = 'btn btn-info'
        );
    }
}