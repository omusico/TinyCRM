<?php

namespace TinyCRM\Controller;

use Zend\View\Model\ViewModel,
    Zend\View\Model\JsonModel;
use TinyCRM\Form;

class DatabasesController extends \VisoftBaseModule\Controller\AbstractCrudController
{
    public function indexAction()
    {
        $roleName = $this->identity()->getRole()->getName();
        switch ($roleName) {
            case 'administrator':
                $pageTitle = "Databases <small>Manage your databases</small>";
                $databases = $this->entityRepository->findAll();
                $viewModel = new ViewModel();
                // transforming Databases Array
                foreach ($databases as $key => $database) {
                    $databases[$key] = [
                        'count' => $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->getCountByDatabaseIds($database->getId()),
                        'entity' => $database,
                        'export-status' => $this->entityManager->getRepository('VisoftMailerModule\Entity\StatusDatabaseExport')->findOneBy(['database' => $database->getId()], ['createdAt' => 'DESC']),
                    ];
                }
                $viewModel->setVariables([
                    'pageTitle' => $pageTitle,
                    'entities' => $databases,
                ]);

                $viewModel->setTemplate('tiny-crm/databases/index-' . $roleName);
                return $viewModel;
                break;
            case 'manager':
                $pageTitle = "Your database <small>Manage your databases</small>";
                $contactsInProgress = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findBy([
                    'manager' => $this->identity(), 
                    'time' => null,
                    'state' => null
                ]);
                $closedState = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactState')->findOneBy(['name' => 'Closed']);
                $contactsClosed = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findBy([
                    'manager' => $this->identity(), 
                    'time' => null,
                    'state' => $closedState
                ]);
                $toll1State = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactState')->findOneBy(['name' => 'Toll1']);
                $contactsToll1 = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findBy([
                    'manager' => $this->identity(), 
                    'time' => null,
                    'state' => $toll1State
                ]);
                $toll2State = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactState')->findOneBy(['name' => 'Toll2']);
                $contactsToll2 = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findBy([
                    'manager' => $this->identity(), 
                    'time' => null,
                    'state' => $toll2State
                ]);
                $toll3State = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactState')->findOneBy(['name' => 'Toll3']);
                $contactsToll3 = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findBy([
                    'manager' => $this->identity(), 
                    'time' => null,
                    'state' => $toll3State
                ]);
                $viewModel = new ViewModel();
                // transforming Databases Array
                // foreach ($databases as $key => $database) {
                //     $databases[$key] = [
                //         'count' => $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->getCountByDatabaseIds($database->getId()),
                //         'entity' => $database,
                //         'export-status' => $this->entityManager->getRepository('VisoftMailerModule\Entity\StatusDatabaseExport')->findOneBy(['database' => $database->getId()], ['createdAt' => 'DESC']),
                //     ];
                // }
                $viewModel->setVariables([
                    'pageTitle' => $pageTitle,
                    'contactsInProgress' => $contactsInProgress,
                    'contactsClosed' => $contactsClosed,
                    'contactsToll1' => $contactsToll1,
                    'contactsToll2' => $contactsToll2,
                    'contactsToll3' => $contactsToll3,
                    'moveScheduleForm' => new Form\ContactForm($this->entityManager, 'move-to-schedule', $this->identity()),
                    'editCommentForm' => new Form\ContactForm($this->entityManager, 'edit-comment', $this->identity()),
                    'closedForm' => new Form\ContactForm($this->entityManager, 'closed', $this->identity()),
                ]);

                $viewModel->setTemplate('tiny-crm/databases/index-' . $roleName);
                return $viewModel;
                break;
            default:
                # code...
                break;
        }
    }

    public function viewAction()
    {
    	$entity = $this->getEntity();
        // $citiesManageIdsArray = $user->getCitiesManage()->map(function($entity) { return $entity->getId(); })->toArray();
    	$pageTitle = $entity->getName() . " <small>Manage contacts</small>";
        return new ViewModel([
        	'pageTitle' => $pageTitle,
        	'database' => $entity,
            'contacts' => $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findBySibscribedOnMailingLists($entity->getId()),
            'export-status' => $this->entityManager->getRepository('VisoftMailerModule\Entity\StatusDatabaseExport')->findOneBy(['database' => $entity->getId()], ['createdAt' => 'DESC']),
            'moveScheduleForm' => new Form\ContactForm($this->entityManager, 'move-to-schedule', $this->identity()),
            'editCommentForm' => new Form\ContactForm($this->entityManager, 'edit-comment', $this->identity()),
            'closedForm' => new Form\ContactForm($this->entityManager, 'closed', $this->identity()),
        ]);
    }

    public function uploadCsvAction()
    {
    	$form = new Form\DatabaseForm($this->entityManager, 'upload-csv');
        $form->setAttributes(['action' => $this->request->getRequestUri()]);
    	$pageTitle = "Upload .csv file <small>Add contacts to database</small>";
        if($this->request->isPost()) {
            $this->post = array_merge_recursive(
                $this->request->getPost()->toArray(),           
                $this->request->getFiles()->toArray()
            );
            $file = $this->params()->fromFiles();
            $form->setData($this->post);
            if($form->isValid()) {
                $data = $form->getData();

                $contactService = $this->getServiceLocator()->get('VisoftMailerModule\Service\ContactService');

                // upload and transfer file to target dir
                $targetDir = $this->uploadPath . '/'. $this->getEntity()->getId() . '/';
                $this->checkDir($targetDir);
                $fileInfo = pathinfo($this->post['csv-file']['name']);
                $receiver = new \Zend\File\Transfer\Adapter\Http();
                $receiver->setDestination($targetDir)
                    ->setFilters([
                        new \Zend\Filter\File\Rename([
                            "target" => $targetDir . 'csv_' . '.' . $fileInfo['extension'],
                            "randomize" => true,
                        ]),
                    ]);
                $receiver->receive('csv-file');
                $uploadedCsvFilePath = $receiver->getFileName('csv-file');

                // map contacts array 
                $contactsArray = array_map('str_getcsv', file($uploadedCsvFilePath));
                $contactsArray = array_filter($contactsArray, function($contact) { 
                    return(!empty(array_filter($contact))); // remove empty row
                });
                array_walk($contactsArray[0], function(&$item) {
                    $item = str_replace(" ", "-", $item);
                    $item = strtolower($item); // make all column names to lower register
                });
                array_walk($contactsArray, function(&$contact) use ($contactsArray) {
                    $contact = array_combine($contactsArray[0], $contact); // make new associative array: 'columnName' => 'value'
                });
                array_shift($contactsArray); // remove column header

                // save contacts to database
                $contactService->enter($this->getEntity(), $contactsArray);

                // redirect with message
                $this->flashMessenger()->addSuccessMessage('Uploading started');
                return $this->redirect()->toRoute('tiny-crm/default', [
                    'controller' => 'databases', 
                    'action' => 'view', 
                    'entityId' => $this->getEntity()->getId()
                ]);
            }
        }
    	return new ViewModel([
        	'pageTitle' => $pageTitle,
        	'form' => $form,
        ]);
    }

    public function updateExportCsvAction()
    {
        if ($this->request->isXmlHttpRequest()) {
            $contactService = $this->getServiceLocator()->get('VisoftMailerModule\Service\ContactService');
            $database = $this->getEntity();
            $status =  $contactService->export($database);
            return new JsonModel([
                'statusId' => $status->getId(),
                'dateTimeCreated' => $status->getCreatedAt()->format('d-m-Y'),
                'city' => $status->getDatabase()->getName(),
            ]);
        }
    }

    public function updateStateExportAction()
    {
        if($this->request->isXmlHttpRequest()) {
            $status = $this->entityManager->getRepository('VisoftMailerModule\Entity\StatusDatabaseExport')->findOneBy(['database' => $this->getEntity()], ['createdAt' => 'DESC']);
            return new JsonModel([
                'state' => $status->getState(),
                'statusId' => $status->getId(),
            ]);
        }
    }

    public function downloadCsvAction()
    {
        $contactService = $this->getServiceLocator()->get('VisoftMailerModule\Service\ContactService');
        $contactService->downloadExportCsv($this->getEntity()->getId());
        exit;  
    }

    public function getContactsAction()
    {
        $contacts = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findBy(['manager' => null], [], 10);
        foreach ($contacts as $contact) {
            $contact->setManager($this->identity());
            $this->entityManager->persist($contact);
        }
        $this->entityManager->flush();
        return $this->redirect()->toRoute('tiny-crm/default', [
            'controller' => 'databases', 
            'action' => 'index'
        ]);
    }
}
