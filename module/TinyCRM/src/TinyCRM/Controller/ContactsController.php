<?php

namespace TinyCRM\Controller;

use Zend\View\Model\ViewModel;
use TinyCRM\Form;

class ContactsController extends \VisoftBaseModule\Controller\AbstractCrudController
{
    protected function bindExtra() 
    {
    	// var_dump();
    	// die('ggg');
    	// $time = ;
    	$time = empty($this->getEntity()->getTime()) ? null : $this->getEntity()->getTime()->format('Y/m/d H:i');
    	$this->editForm->get('time')->setValue($time);
		// for ($indx = 1; $indx < 5; $indx++) { 
    		// $getImageFunctionName = 'getImg';
    		// $image = $this->getEntity()->getImage();
    		// // var_dump($image);
    		// // die('dd');
    		// if(!empty($image)) {
    		// 	$this->editForm->get('xStartCrop')->setValue($image->getXStartCrop());
	    	// 	$this->editForm->get('yStartCrop')->setValue($image->getYStartCrop());
	    	// 	$this->editForm->get('heightCrop')->setValue($image->getHeightCrop());
	    	// 	$this->editForm->get('widthCrop')->setValue($image->getWidthCrop());
	    	// 	$this->editForm->get('heightCurrent')->setValue($image->getHeightCurrent());
	    	// 	$this->editForm->get('widthCurrent')->setValue($image->getWidthCurrent());
    		// }
    		
    	// }
    }

    public function moveScheduleAction()
    {
        if($this->request->isPost()) {
            $contact = $this->getEntity();
            $post = $this->request->getPost();
            $leadState = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactState')->findOneBy(['name' => 'Lead']);
            $contact->setTime(new \Datetime($post['time']));
            $contact->setState($leadState);
            $contact->setManager($this->identity());
            $this->entityManager->persist($contact);
            $this->entityManager->flush();
            $this->flashMessenger()->addSuccessMessage('Contact moved to your schedule on ' . $post['time']);
            $this->redirectToRefer();
        }
    }

    public function editCommentAction()
    {
        if($this->request->isPost()) {
            $contact = $this->getEntity();
            $post = $this->request->getPost();
            $contact->setComment($post['comment']);
            $this->entityManager->persist($contact);
            $this->entityManager->flush();
            $this->flashMessenger()->addSuccessMessage('Comment updated');
            $this->redirectToRefer();
        }
    }

    public function closedAction()
    {
        if($this->request->isPost()) {
            $contact = $this->getEntity();
            $post = $this->request->getPost();
            $closedState = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactState')->findOneBy(['name' => 'Closed']);
            $contact->setClosedTime(new \Datetime());
            $contact->setClosedAmount($post['closedAmount']);
            $contact->setState($closedState);
            $this->entityManager->persist($contact);
            $this->entityManager->flush();
            $this->flashMessenger()->addSuccessMessage('Contact closed with amount - ' . $post['closedAmount']);
            $this->redirectToRefer();
        }
    }

    public function tollAction()
    {
        $stateName = urldecode($this->params()->fromQuery('state'));
        // if($this->request->isPost()) {
            $contact = $this->getEntity();
            $post = $this->request->getPost();
            $closedState = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactState')->findOneBy(['name' => $stateName]);
            $contact->setClosedTime(new \Datetime());
            $contact->setState($closedState);
            $this->entityManager->persist($contact);
            $this->entityManager->flush();
            $this->flashMessenger()->addSuccessMessage('Contact marked as ' . $stateName);
            $this->redirectToRefer();
        // }
    }


    private function redirectToRefer()
    {
        // redirect to refer page
        // var_dump($this->request->getHeader('Referer')->uri());
        // die('ff');
        $scheme = $this->request->getHeader('Referer')->uri()->getScheme();
        $host = $this->request->getHeader('Referer')->uri()->getHost();
        $path = $this->request->getHeader('Referer')->uri()->getPath();
        $query = $this->request->getHeader('Referer')->uri()->getQuery();
        $redirectUrl = $scheme . '://' . $host . $path . '?' . $query;
        return $this->redirect()->toUrl($redirectUrl);
    }
}
