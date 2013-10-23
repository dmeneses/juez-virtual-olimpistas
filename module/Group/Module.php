<?php
namespace Group;

use Group\Model\Group;
use Group\Model\GroupTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Group\Model\GroupTable' =>  function($sm) {
                    $tableGateway = $sm->get('GroupTableGateway');
                    $table = new GroupTable($tableGateway);
                    return $table;
                },
                'GroupTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Group());
                    return new TableGateway('group', $dbAdapter, null, $resultSetPrototype);
                },
                'Group\Form\CreateGroupFilter' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new Form\CreateGroupFilter($dbAdapter);
                },
                'Group\Form\EditGroupFilter' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    return new Form\EditGroupFilter($dbAdapter);
                },
            ),
        );
    }
}