<?php

namespace Group\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Group\Form\CreateGroupForm;
use Group\Model\Group;
use Group\Form\EditGroupForm;

class GroupController extends AbstractActionController {

    const GROUP_TABLE = 'Group\Model\GroupTable';
    const CREATE_GROUP_FILTER = 'Group\Form\CreateGroupFilter';
    const EDIT_GROUP_FILTER = 'Group\Form\EditGroupFilter';

    protected $groupTable;
    protected $problemTable;

    public function indexAction() {
        return new ViewModel(array('groups' => $this->getGroupTable()->fetchAll()));
    }

    public function getGroupTable() {
        if (!$this->groupTable) {
            $sm = $this->getServiceLocator();
            $this->groupTable = $sm->get(self::GROUP_TABLE);
        }
        return $this->groupTable;
    }

    public function createAction() {
        $form = new CreateGroupForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $group = new Group();
            $filter = $this->getServiceLocator()->get(self::CREATE_GROUP_FILTER);
            $form->setInputFilter($filter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $group->exchangeArray($form->getData());
                $this->getGroupTable()->save($group);
                return $this->redirect()->toRoute('group');
            }
        }

        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id || !$this->getGroupTable()->exist($id)) {
            return $this->redirect()->toRoute('group');
        }

        $group = $this->getGroupTable()->get($id);
        $form = new EditGroupForm();
        $filter = $this->getServiceLocator()->get(self::EDIT_GROUP_FILTER);
        $form->setInputFilter($filter->getInputFilter());
        $form->get('group_id')->setValue($id);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $newUser = $form->get('user_email')->getValue();
                $this->getGroupTable()->addUser($group->group_id, $newUser);
            }
        }

        $users = $this->getGroupTable()->getUsers($id);
        return array(
            'form' => $form,
            'group' => $group,
            'users' => $users,
        );
    }

}
