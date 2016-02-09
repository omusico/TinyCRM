<?php

namespace TinyCRM\Controller;

use Zend\View\Model\ViewModel;

class ManagersController extends \VisoftBaseModule\Controller\AbstractCrudController
{
    public function indexAction()
    {
    	$pageName = "Managers <small>Manage your employees</small>";
        return new ViewModel([
        	'pageName' => $pageName,
        	'users' => $this->entityRepository->findAll(),
        ]);
    }
}
