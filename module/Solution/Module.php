<?php
namespace Solution;

use Solution\Model\Solution;
use Solution\Model\SolutionTable;
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
                'Solution\Model\SolutionTable' =>  function($sm) {
                    $tableGateway = $sm->get('SolutionTableGateway');
                    $table = new SolutionTable($tableGateway);
                    return $table;
                },
                'SolutionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Solution());
                    return new TableGateway('solution', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}