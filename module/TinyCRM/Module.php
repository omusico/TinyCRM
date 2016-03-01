<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TinyCRM;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent,
    Zend\Mvc\Controller\ControllerManager;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() 
    {
        return [
            'factories' => [
                'TinyCrmNavigation' => 'TinyCRM\Navigation\Factory\TinyCrmNavigationFactory',
            ],
        ];
    }

    public function getControllerConfig() 
    {
        return [
            'factories' => [
                'TinyCRM\Controller\Index' => function(ControllerManager $controllerManager) {
                    $serviceManager = $controllerManager->getServiceLocator();
                    $entityManager = $serviceManager->get('Doctrine\ORM\EntityManager');
                    // $registrationService = $serviceManager->get('Fryday\Service\RegistrationService');
                    // $visoftBaseOptions = $serviceManager->get('VisoftBaseModule\Options\ModuleOptions');
                    // $acMailService = $serviceManager->get('AcMailer\Service\MailService');
                    return new Controller\IndexController($entityManager);
                },
            ],
            'aliases' => [],
        ];
    }
}
