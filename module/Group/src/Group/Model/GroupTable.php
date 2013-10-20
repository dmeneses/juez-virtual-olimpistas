<?php

namespace Group\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Validator\Db\RecordExists;

/**
 * Database manager for groups.
 *
 * @author Daniela Meneses
 */
class GroupTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $select = new Select;
        $select->columns(array('group_id', 'group_name'));
        $select->from(array('t' => 'group',))
                ->join(array('u' => 'user'), 'u.user_id = t.group_owner', array('name', 'lastname'));
        $statement = $this->tableGateway->getAdapter()->createStatement();
        $select->prepareStatement($this->tableGateway->getAdapter(), $statement);

        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());

        return $resultSet;
    }

    public function get($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('group_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function save(Group $group) {
        $data = array(
            'group_name' => $group->group_name,
            'group_owner' => $group->group_owner,
        );

        $id = (int) $group->group_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getTraining($id)) {
                $this->tableGateway->update($data, array('group_id' => $id));
            } else {
                throw new \Exception('Training id does not exist');
            }
        }
    }

    //TODO: When the user module is created move it there.
    function getUserByEmail($email) {
        $select = new Select;
        $select->from(array('u' => 'user',));
        $select->where(array('u.email' => $email,));
        $statement = $this->tableGateway->getAdapter()->createStatement();
        $select->prepareStatement($this->tableGateway->getAdapter(), $statement);

        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());

        $row = $resultSet->current();
       
        if (!$row) {
            throw new \Exception("Could not find user with email $email");
        }
        
        return $row;
    }
    
    public function addUser($groupID, $userEmail) {
        $user = $this->getUserByEmail($userEmail);
        $dbAdapter = $this->tableGateway->getAdapter();
        $sql = new Sql($dbAdapter);

        $insert = $sql->insert('user_has_group');
        $insert->values(array(
            'user_user_id' => $user->user_id,
            'group_group_id' => $groupID,
        ));

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    public function exist($groupID) {
        $dbValidator = new RecordExists(array(
            'table' => 'group',
            'field' => 'group_id',
            'adapter' => $this->tableGateway->getAdapter(),
        ));

        return $dbValidator->isValid($groupID);
    }

    public function getUsers($groupID)
    {
        $select = new Select;
        $select->from(array('u' => 'user',))
                ->join(array('ug' => 'user_has_group'), 'ug.user_user_id = u.user_id', array())
                ->join(array('g' => 'group'), 'g.group_id = ug.group_group_id', array());
        $select->where(array('g.group_id' => $groupID,));
        $statement = $this->tableGateway->getAdapter()->createStatement();
        $select->prepareStatement($this->tableGateway->getAdapter(), $statement);

        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());
        
        return $resultSet;
    }
    
}
