<?php

namespace User\Model;

use User\Model\User;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

/**
 * Database manager for groups.
 *
 * @author Daniela Meneses
 */
class UserTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function getUserById($userID) {
        $userID = (int) $userID;
        $rowset = $this->tableGateway->select(array(User::ID => $userID));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row with id: $userID");
        }
        return $row;
    }

    public function getUserByEmail($email) {
        $select = new Select;
        $select->from(array('u' => 'user',));
        $select->where(array('u.email' => $email,));
        $statement = $this->tableGateway->getAdapter()->createStatement();
        $select->prepareStatement($this->tableGateway->getAdapter(), $statement);
        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());
        return $resultSet->current();
    }
}
