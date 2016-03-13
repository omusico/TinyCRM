<?php

namespace TinyCRM\Form;

use Doctrine\ORM\EntityManager,
	DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

// use VisoftBaseModule\Form\BaseForm;

class DatabaseForm extends \TinyCRM\Form\BaseForm
{
	public function __construct($entityManager, $formType, $identity = null)
	{
		parent::__construct($entityManager);
        $this->setHydrator(new DoctrineHydrator($entityManager));
		$this->setAttributes([
            'method' => 'post',
            'class' => 'form-horizontal',
        ]);
        // $this->addText('route', 'Route to page', 'label', false, false, '/route/to/example/page');
        switch ($formType) {
            case 'create':
                $this->setCreateForm('Create new database');
                break;
            case 'edit':
                $this->setEditForm('Edit database');
                break;
            case 'upload-csv':
                $this->setUploadCsvForm('Select CSV file for uploading');
                break;
            default:
                # code...
                break;
        }
    }

    public function setCreateForm($title)//$action)
    {
        $this->setTitle($title);

        $this->addText(
            $name = 'name', 
            $label = 'Name', 
            $labelClass = 'col-sm-2 control-label',
            $id = null,
            $required = false,
            $placeholder = 'Name your database'
        );

        $this->addCheckbox(
            $name = 'individual',
            $label = 'Individual'
        );

        $this->addMultiCheckboxManagers(
            $name = 'managers'
        );
        
        $this->addSubmit(
            $name = 'submit', 
            $value = 'Save', 
            $class = 'btn btn-info'
        );
    }

    public function setEditForm($title)//$action)
    {
        $this->setCreateForm($title);
    }

    public function setUploadCsvForm($title)
    {
        $this->setTitle($title);

        $this->addFile(
            $name = 'csv-file', 
            $label = 'Upload *.csv file with contacts you want add'
        );

        $this->addSubmit(
            $name = 'submit', 
            $value = 'Upload', 
            $class = 'btn btn-info'
        );
    }


}