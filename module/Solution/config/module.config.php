<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Solution\Controller\Solution' => 'Solution\Controller\SolutionController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'solution' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/solution[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Solution\Controller\Solution',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'solution' => __DIR__ . '/../view',
        ),
    ),
);