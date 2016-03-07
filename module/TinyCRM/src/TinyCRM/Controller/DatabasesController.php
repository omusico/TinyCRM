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
                $searchForm = new Form\ContactForm($this->entityManager, 'search', $this->identity());
                $searchForm->setAttribute('action', $this->url()->fromRoute('tiny-crm/default', [
                    'controller' => 'databases', 
                    'action' => 'search'
                ]));
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
                    'statuses' => $this->entityManager->getRepository('VisoftMailerModule\Entity\StatusContactEnter')->findBy([], ['createdAt' => 'DESC']),
                    'searchForm' => $searchForm,
                ]);

                $viewModel->setTemplate('tiny-crm/databases/index-' . $roleName);
                return $viewModel;
                break;
            case 'manager':
                $pageTitle = "Your database <small>Manage your databases</small>";
                $state = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactState')->findOneBy(['name' => 'Pending']);
                $contactsInProgress = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findBy([
                    'manager' => $this->identity(), 
                    'time' => null,
                    'state' => $state
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
    	$database = $this->getEntity();
        // $citiesManageIdsArray = $user->getCitiesManage()->map(function($entity) { return $entity->getId(); })->toArray();
        $contactsTotal = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->getCountByDatabaseIds($database->getId());
    	$pageTitle = $database->getName() . " <small>Total: <strong>" . $contactsTotal . "</strong> contacts</small>";
        return new ViewModel([
        	'pageTitle' => $pageTitle,
        	'database' => $database,
            'contacts' => $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findBySibscribedOnMailingLists($database->getId()),
            'export-status' => $this->entityManager->getRepository('VisoftMailerModule\Entity\StatusDatabaseExport')->findOneBy(['database' => $database->getId()], ['createdAt' => 'DESC']),
            'moveScheduleForm' => new Form\ContactForm($this->entityManager, 'move-to-schedule', $this->identity()),
            'editCommentForm' => new Form\ContactForm($this->entityManager, 'edit-comment', $this->identity()),
            'closedForm' => new Form\ContactForm($this->entityManager, 'closed', $this->identity()),
        ]);
    }

    public function uploadCsvAction()
    {
    	$form = new Form\DatabaseForm($this->entityManager, 'upload-csv');
        $form->setAttributes(['action' => $this->request->getRequestUri()]);
    	$pageTitle = "Upload CSV file <small>Add contacts to database</small>";
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

                // detect delimiter for csv
                $delimiter = $this->detectCsvFileDelimiter($uploadedCsvFilePath);

                // convert file to array 
                $contactsArray = file($uploadedCsvFilePath);

                // get titles of columns and transform
                $columnNames = str_getcsv($contactsArray[0], $delimiter);
                array_walk($columnNames, function(&$item) {
                    $item = str_replace(" ", "-", $item);
                    $item = strtolower($item); 
                });

                foreach ($contactsArray as $key => $contact) {
                    // get CSV line by line
                    $contact = str_getcsv($contact, $delimiter);
                    
                    // change keys in array to column names
                    $contact = array_combine($columnNames, $contact);

                    // detect Windows-1251 ecoding and change to UTF-8
                    array_walk($contact, function(&$item) {
                        if(mb_check_encoding($item, 'CP1251')){
                            $item = iconv('CP1251', 'UTF-8', $item);
                        }
                    });

                    // rewrite current element to new one
                    $contactsArray[$key] = $contact;
                }

                // remove column header
                array_shift($contactsArray); 

                // save contacts to database
                $contactService->enter($this->getEntity(), $contactsArray);

                // redirect with message
                $this->flashMessenger()->addSuccessMessage('Uploading started in background');
                return $this->redirect()->toRoute('tiny-crm/default', [
                    'controller' => 'databases', 
                    'action' => 'index', 
                    // 'entityId' => $this->getEntity()->getId()
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

    public function downloadCsvExampleAction()
    {
        $filePath = getcwd() . '/public/tiny-crm-files/tiny-crm-example-contact-list.csv';
        return $this->downloadFile($filePath);
    }

    public function downloadContactsEnterReportAction()
    {
        $statusId = $this->params()->fromRoute('entityId');
        $status = $this->entityManager->find('VisoftMailerModule\Entity\StatusContactEnter', $statusId);
        $filePath = $status->getOutputFilePath();
        return $this->downloadFile($filePath);
    }

    public function searchAction()
    {
        $form = new Form\ContactForm($this->entityManager, 'search', $this->identity());
        $form->setAttribute('action', $this->url()->fromRoute('tiny-crm/default', [
            'controller' => 'databases', 
            'action' => 'search'
        ]));
        if($this->request->isGet()) {
            $sample = $this->params()->fromQuery('search');
            if(strlen($sample) < 3) {
                $contacts = null;
            } else {
                $contacts = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->search($sample);
            }
            $form->get('search')->setValue($sample);
            return new ViewModel([
                'pageTitle' => 'Search on contacts',
                'form' => $form,
                'contacts' => $contacts,
                'moveScheduleForm' => new Form\ContactForm($this->entityManager, 'move-to-schedule', $this->identity()),
                'editCommentForm' => new Form\ContactForm($this->entityManager, 'edit-comment', $this->identity()),
                'closedForm' => new Form\ContactForm($this->entityManager, 'closed', $this->identity()),
            ]);
        }
    }
}
