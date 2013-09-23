<?php
namespace Problem;

use Problem\Model\Problem;
use Problem\Model\ProblemTable;
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
                'Problem\Model\ProblemTable' =>  function($sm) {
                    $tableGateway = $sm->get('ProblemTableGateway');
                    $table = new ProblemTable($tableGateway);
                    return $table;
                },
                'ProblemTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Problem());
                    return new TableGateway('problem', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}