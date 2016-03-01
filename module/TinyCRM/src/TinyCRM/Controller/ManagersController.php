<?php

namespace TinyCRM\Controller;

use Zend\View\Model\ViewModel;
use TinyCRM\Form;

class ManagersController extends \VisoftBaseModule\Controller\AbstractCrudController
{
    public function indexAction()
    {
    	$pageTitle = "Managers <small>Manage your employees</small>";
        return new ViewModel([
        	'pageTitle' => $pageTitle,
        	'entities' => $this->entityRepository->findAll(),
        ]);
    }

    public function passwordResetAction()
    {
    	if($this->request->isPost()) {
    		$entity = $this->getEntity();
    		$post = $this->request->getPost();
    		// var_dump($post['password']);
    		// die('aaa');
    		$entity->setPassword($post['password']);
    		$this->entityManager->persist($entity);
    		$this->entityManager->flush();
    		$this->flashMessenger()->addSuccessMessage('Password successfully updated');
    		return $this->redirect()->toRoute('tiny-crm/default', [
    			'controller' => 'managers',
    			'action' => 'edit',
    			'entityId' => $entity->getId(),
    		]);
    	}
    }

    protected function addEditViewModelVariables()
    {
    	// add form for password reset
    	$passwordResetForm = new Form\UserForm($this->entityManager, 'password-reset');
    	$actionUrl = $this->url()->fromRoute('tiny-crm/default', [
    		'controller' => 'managers',
    		'action' => 'password-reset',
    		'entityId' => $this->getEntity()->getId(),
    	]);
    	// var_dump($actionUrl);
    	// die('action');
    	$passwordResetForm->setAttributes(['action' => $actionUrl]);
    	$this->viewModel->setVariables([
    		'passwordResetForm' => $passwordResetForm,
    	]);
    	// die('fff');
    }
}
