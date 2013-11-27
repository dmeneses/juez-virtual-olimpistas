<?php

namespace Group\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Validator\Db\RecordExists;
use User\Model\UserTable;

/**
 * Database manager for groups.
 *
 * @author Daniela Meneses
 */
class GroupTable {

    protected $tableGateway;
    protected $userTable;

    public function __construct(TableGateway $tableGateway, UserTable $userTable) {
        $this->tableGateway = $tableGateway;
        $this->userTable = $userTable;
    }

    public function getDbAdapter() {
        return $this->tableGateway->getAdapter();
    }

    public function fetchAll() {
        $select = $this->fetchAllQuery();
        $statement = $this->tableGateway->getAdapter()->createStatement();
        $select->prepareStatement($this->tableGateway->getAdapter(), $statement);

        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());

        return $resultSet;
    }

    public function fetchAllQuery() {
        $select = new Select;
        $select->columns(array('group_id', 'group_name'));
        $select->from(array('t' => 'group',))
                ->join(array('u' => 'user'), 'u.user_id = t.group_owner', array('name', 'lastname'));
        return $select;
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
            if ($this->get($id)) {
                $this->tableGateway->update($data, array('group_id' => $id));
            } else {
                throw new \Exception('Training id does not exist');
            }
        }
    }

    public function addUser($groupID, $userEmail) {
        $user = $this->userTable->getUserByEmail($userEmail);
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

    public function removeUser($groupID, $userEmail) {
        $user = $this->userTable->getUserByEmail($userEmail);
        $dbAdapter = $this->tableGateway->getAdapter();
        $sql = new Sql($dbAdapter);

        $delete = $sql->delete('user_has_group');
        $delete->where(array(
            'group_group_id' => $groupID,
            'user_user_id' => $user->user_id,
        ));

        $statement = $sql->prepareStatementForSqlObject($delete);
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

    public function getUsers($groupID) {
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

    public function getGroupsByTraining($trainingID) {
        $select = new Select;
        $select->from(array('g' => 'group',))
                ->join(array('tp' => 'training_has_group'), 'tp.group_group_id = g.group_id', array())
                ->join(array('t' => 'training'), 't.training_id = tp.training_training_id ', array());
        $select->where(array('t.training_id' => $trainingID,));
        $statement = $this->tableGateway->getAdapter()->createStatement();
        $select->prepareStatement($this->tableGateway->getAdapter(), $statement);

        $resultSet = new ResultSet();
        $resultSet->initialize($statement->execute());

        return $resultSet;
    }

}
