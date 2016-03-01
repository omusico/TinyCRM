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
}
