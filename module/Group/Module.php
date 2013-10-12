<?php
namespace Group;

//use Group\Model\Group;
//use Group\Model\GroupTable;
use Group\Model\Training;
use Group\Model\TrainingTable;
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
//                'Group\Model\GroupTable' =>  function($sm) {
//                    $tableGateway = $sm->get('GroupTableGateway');
//                    $table = new GroupTable($tableGateway);
//                    return $table;
//                },
                'Group\Model\TrainingTable' =>  function($sm) {
                    $tableGateway = $sm->get('TrainingTableGateway');
                    $table = new TrainingTable($tableGateway);
                    return $table;
                },
//                'GroupTableGateway' => function ($sm) {
//                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Group());
//                    return new TableGateway('group', $dbAdapter, null, $resultSetPrototype);
//                },
                'TrainingTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Training());
                    return new TableGateway('training', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}