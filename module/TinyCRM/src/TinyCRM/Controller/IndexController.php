<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TinyCRM\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;

use TinyCRM\Form;

class IndexController extends AbstractActionController
{
    protected $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $dateString = urldecode($this->params()->fromQuery('date'));
        // $this->generateSchedule($dateString);
        // $this->generateCalendar($dateString);

        $now = new \Datetime();
        $time = empty($dateString) ? $now->format('Y/m/d') : $dateString;
        
    	$pageTitle = "Dashboard <small>" . $dateString . "</small>";
    	$role = $this->identity()->getRole()->getName();
    	$viewModel = new ViewModel([
    		'pageTitle' => $pageTitle,
            // 'contacts' => $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findByDate(new \DateTime($dateString), $this->identity()),
            'schedule' => $this->generateSchedule($dateString),
            'calendar' => $this->generateCalendar(),
            'moveScheduleForm' => new Form\ContactForm($this->entityManager, 'move-to-schedule', $this->identity()),
            'editCommentForm' => new Form\ContactForm($this->entityManager, 'edit-comment', $this->identity()),
            'closedForm' => new Form\ContactForm($this->entityManager, 'closed', $this->identity()),
            'dateString' => $time,
    	]);
    	$viewModel->setTemplate('tiny-crm/index/index-' . $role);
        return $viewModel;
    }

    public function generateSchedule($dateString)
    {
        // generating array with times
        for ($hour = 8; $hour < 19; $hour++) { 
            for ($minute = 0; $minute <= 45 ; $minute += 15) { 
                $hourLeadZero = str_pad($hour, 2, 0, STR_PAD_LEFT);
                $minuteLeadZero = str_pad($minute, 2, 0, STR_PAD_LEFT);
                $time = $hourLeadZero . ":" . $minuteLeadZero;
                $schedule[$time] = [];
            }
        }
        $leadState = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactState')->findOneBy(['name' => 'Lead']);
        $contacts = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findByDate(new \DateTime($dateString), $this->identity(), $leadState);

        foreach ($contacts as $contact) {
            // var_dump($contact);
            $contactTime = $contact->getTime()->format('H:i');
            if(!isset($schedule[$contactTime]))
                $schedule[$contactTime] = [];
            array_push($schedule[$contactTime], $contact);
        }
        // die('dd');
        return $schedule;
    }

    public function generateCalendar() 
    {
        $leadState = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactState')->findOneBy(['name' => 'Lead']);
        $contacts = $this->entityManager->getRepository('VisoftMailerModule\Entity\ContactInterface')->findBy(['state' => $leadState, 'manager' => $this->identity()]); //->getDataForCalensdar($this->identity());
        $calendar['dates'] = [];
        $calendar['queries'] = [];
        foreach ($contacts as $contact) {
            $date = $contact->getTime()->format('Y-n-j');
            // in_array($date, $data['date']) ? array_push($data['date'], $date) : null;
            // var_dump(in_array($date, $data['date']));
            // var_dump($date);
            if(!in_array($date, $calendar['dates'])) {
                array_push($calendar['dates'], $date);
                array_push($calendar['queries'], '/?date=' . urlencode($contact->getTime()->format('Y/m/d')));
            }
            // var_dump($data);
        }
        // var_dump($contacts);
        // die('ddd');
        return $calendar;
    }
}
