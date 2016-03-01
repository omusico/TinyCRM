<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TinyCRM;

return array(
    'router' => array(
        'routes' => array(
            // 'home' => array(
            //     'type' => 'Zend\Mvc\Router\Http\Literal',
            //     'options' => array(
            //         'route'    => '/',
            //         'defaults' => array(
            //             'controller' => 'TinyCRM\Controller\Index',
            //             'action'     => 'index',
            //         ),
            //     ),
            // ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            
            'tiny-crm' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'TinyCRM\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => ':controller[/[:action[/[:entityId[/]]]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                // 'entityId' =>
                            ),
                            'defaults' => [],
                        ),
                    ),
                ),
            ),
            'sign-in' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/sign-in[/]',
                    'defaults' => [
                        '__NAMESPACE__' => 'VisoftBaseModule\Service\Authentication\Controller',
                        'controller'    => 'Authentication',
                        'action'        => 'sign-in',
                    ],
                ),
            ),
            'sign-out' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/sign-out[/]',
                    'defaults' => [
                        '__NAMESPACE__' => 'VisoftBaseModule\Service\Authentication\Controller',
                        'controller'    => 'Authentication',
                        'action'        => 'sign-out',
                    ],
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        // 'invokables' => array(
        //     'TinyCRM\Controller\Index' => Controller\IndexController::class
        // ),
    ),
    'crud_controllers' => [
        'TinyCRM\Controller\Managers' => [
            'entityClass' => 'TinyCRM\Entity\User',
            'uploadPath' => 'public/uploads/managers',
            // 'templates' => [
            //     'create' => '',
            //     'edit' => '',
            // ],
            'forms' => [
                'class' => 'TinyCRM\Form\UserForm',
                'options' => [
                    'create' => 'create',
                    'edit' => 'edit',
                ],
            ]
        ],
        'TinyCRM\Controller\Databases' => [
            'entityClass' => 'TinyCRM\Entity\Database',
            'uploadPath' => 'public/uploads/databases',
            // 'templates' => [
            //     'create' => '',
            //     'edit' => '',
            // ],
            'forms' => [
                'class' => 'TinyCRM\Form\DatabaseForm',
                'options' => [
                    'create' => 'create',
                    'edit' => 'edit',
                ],
            ]
        ],
        'TinyCRM\Controller\Contacts' => [
            'entityClass' => 'TinyCRM\Entity\Contact',
            'uploadPath' => 'public/uploads/contacts',
            // 'templates' => [
            //     'create' => '',
            //     'edit' => '',
            // ],
            'forms' => [
                'class' => 'TinyCRM\Form\ContactForm',
                'options' => [
                    'create' => 'create',
                    'edit' => 'edit',
                ],
            ]
        ],
    ],
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/403'               => __DIR__ . '/../view/error/403.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy'
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                )
            )
        ),
        'entity_resolver' => array(
            'orm_default' => array(
                'resolvers' => array(
                    'VisoftBaseModule\Entity\UserInterface'             => 'TinyCRM\Entity\User',
                    'VisoftMailerModule\Entity\DatabaseInterface'       => 'TinyCRM\Entity\Database',
                    'VisoftMailerModule\Entity\ContactInterface'        => 'TinyCRM\Entity\Contact',
                    // 'VisoftMailerModule\Entity\EmailTemplateInterface'  => 'TinyCRM\Entity\EmailTemplate',
                    // TODO: Remomve shit bellow
                    // 'VisoftMailerModule\Entity\MailingInterface'        => 'TinyCRM\Entity\MailingAnnouncement',
                ),
            ),
        ),
    ),
);
