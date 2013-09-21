<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Problem\Controller\Problem' => 'Problem\Controller\ProblemController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'problem' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/problem[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Problem\Controller\Problem',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'problem' => __DIR__ . '/../view',
        ),
    ),
);