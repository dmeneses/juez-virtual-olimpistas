<?php

namespace Group\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Group\Form\CreateGroupForm;
use Group\Model\Group;
use Group\Form\EditGroupForm;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;

class GroupController extends AbstractActionController {

    const GROUP_TABLE = 'Group\Model\GroupTable';
    const CREATE_GROUP_FILTER = 'Group\Form\CreateGroupFilter';
    const EDIT_GROUP_FILTER = 'Group\Form\EditGroupFilter';

    protected $groupTable;
    protected $problemTable;

    public function getGroupTable() {
        if (!$this->groupTable) {
            $sm = $this->getServiceLocator();
            $this->groupTable = $sm->get(self::GROUP_TABLE);
        }
        return $this->groupTable;
    }
    public function indexAction() {
        $paginator = new Paginator(new DbSelect($this->getGroupTable()->fetchAllQuery(), $this->getGroupTable()->getDbAdapter()));
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage(10)
                ->setPageRange(7);

        return new ViewModel(array(
            'page' => $page,
            'paginator' => $paginator,
        ));
    }


    public function getLoggedUserID() {
        return $this->getServiceLocator()->get('LoggedUserID');
    }

    public function createAction() {
        $userID = $this->getLoggedUserID();
        if (!$userID) {
            return $this->redirect()->toRoute('login');
        }

        $form = new CreateGroupForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $filter = $this->getServiceLocator()->get(self::CREATE_GROUP_FILTER);
            $form->setInputFilter($filter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $group = new Group();
                $group->exchangeArray($form->getData());
                $group->group_owner = $userID;
                $this->getGroupTable()->save($group);
                return $this->redirect()->toRoute('group');
            }
        }

        return array('form' => $form);
    }

    public function editAction() {
        $userID = $this->getLoggedUserID();
        if (!$userID) {
            return $this->redirect()->toRoute('login');
        }

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id || !$this->getGroupTable()->exist($id)) {
            return $this->redirect()->toRoute('group');
        }

        $form = new EditGroupForm();
        $form->get('group_id')->setValue($id);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            $form->setDbAdapter($this->getGroupTable()->getDbAdapter());

            if ($form->isValid()) {
                $newUser = $form->get('user_email')->getValue();
                $this->getGroupTable()->addUser($id, $newUser);
            }
        }

        $group = $this->getGroupTable()->get($id);
        $users = $this->getGroupTable()->getUsers($id);
        return array(
            'form' => $form,
            'group' => $group,
            'users' => $users,
            'isOwner' => $userID == $group->group_owner,
        );
    }

}
