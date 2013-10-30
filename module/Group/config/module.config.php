<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Group\Controller\Group' => 'Group\Controller\GroupController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'group' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/group[/:action][/:id][/page/:page]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Group\Controller\Group',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'group' => __DIR__ . '/../view',
        ),
        'template_map' => array(
            'paginator-slide' => __DIR__ . '/../view/group/group/slidePaginator.phtml',
        ),
    ),
);