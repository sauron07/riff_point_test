<?php
return [
    'doctrine' => array(
        'driver' =>array(
            'user_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/User/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'User\Entity' => 'user_entities',
                ),
            ),
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'User\Entity\User',
                'identity_property' => 'username',
                'credential_property' => 'password',
                'credential_callable' => 'User\Entity\User::hashPassword',
            ),
        ),
    ),
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name' => 'myapp',
            ),
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            array(
                'Zend\Session\Validator\RemoteAddr',
                'Zend\Session\Validator\HttpUserAgent',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Auth' => 'User\Controller\AuthController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'registration' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/]registration[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\Auth',
                        'action' => 'registration'
                    ),
                ),
            ),
            'login' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/]login[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\Auth',
                        'action' => 'login'
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/]logout[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\Auth',
                        'action' => 'logout'
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view',
        ),
        'display_exceptions' => true,
    ),
];