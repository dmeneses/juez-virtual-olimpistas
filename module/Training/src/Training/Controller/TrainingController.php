<?php

namespace Training\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Training\Model\Training;
use Training\Form\CreateTrainingForm;
use Training\Form\AddElementToTraining;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;

class TrainingController extends AbstractActionController {

    protected $trainingTable;
    protected $problemTable;
    protected $groupTable;
    protected $authService;

    public function getAuthService() {
        if (!$this->authService) {
            $this->authService = $this->getServiceLocator()->get('AuthService');
        }

        return $this->authService;
    }

    public function getProblemTable() {
        if (!$this->problemTable) {
            $sm = $this->getServiceLocator();
            $this->problemTable = $sm->get('Problem\Model\ProblemTable');
        }
        return $this->problemTable;
    }

    public function getTrainingTable() {
        if (!$this->trainingTable) {
            $sm = $this->getServiceLocator();
            $this->trainingTable = $sm->get('Training\Model\TrainingTable');
        }
        return $this->trainingTable;
    }

    public function getGroupTable() {
        if (!$this->groupTable) {
            $sm = $this->getServiceLocator();
            $this->groupTable = $sm->get('Group\Model\GroupTable');
        }
        return $this->groupTable;
    }

    public function getLoggedUserID() {
        return $this->getServiceLocator()->get('LoggedUserID');
    }

    public function indexAction() {
        $paginator = new Paginator(new DbSelect($this->getTrainingTable()->fetchAllQuery(), $this->getTrainingTable()->getDbAdapter()));
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage(10)
                ->setPageRange(7);

        return new ViewModel(array(
            'page' => $page,
            'paginator' => $paginator,
        ));
    }

    public function createAction() {
        $userID = $this->getLoggedUserID();

        if (!$userID) {
            return $this->redirect()->toRoute('login');
        }

        $form = new CreateTrainingForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $training = new Training();
            $form->setData($request->getPost());
            $form->setDbAdapter($this->getTrainingTable()->getDbAdapter());

            if ($form->isValid()) {
                $training->exchangeArray($form->getData());
                $training->training_owner = $userID;
                $this->getTrainingTable()->save($training);
                return $this->redirect()->toRoute('training');
            }
        }

        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id || !$this->getTrainingTable()->exist($id)) {
            return $this->redirect()->toRoute('training');
        }

        $training = $this->getTrainingTable()->get($id);
        $problemForm = new AddElementToTraining($id, 'problem');
        $groupForm = new AddElementToTraining($id, 'group');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost();
            if (isset($postData['addgroup'])) {
                $this->addGroup($groupForm, $postData, $training);
            } else if (isset($postData['addproblem'])) {
                $this->addProblem($problemForm, $postData, $training);
            } else if (isset($postData['removegroup'])) {
                $this->removeGroup($groupForm, $postData, $training);
            } else if (isset($postData['removeproblem'])) {
                $this->removeProblem($problemForm, $postData, $training);
            }
        }

        return $this->getDataToView($problemForm, $groupForm, $training);
    }

    private function getDataToView($problemForm, $groupForm, $training) {
        $isOwner = $this->getLoggedUserID() == $training->training_owner;
        $groups = $this->getGroupTable()->getGroupsByTraining($training->training_id);
        $data = array(
            'problemForm' => $problemForm,
            'groupForm' => $groupForm,
            'trainingData' => $training,
            'trainingGroups' => $groups,
            'isOwner' => $isOwner,);

        if ($this->isEnabled($training->start_date, $training->start_time) || $isOwner) {
            $problems = $this->getProblemTable()->getProblemsByTraining($training->training_id);
            $data['trainingProblems'] = $problems;
        }

        return $data;
    }

    private function addProblem($problemForm, $postData, $training) {
        $problemForm->setData($postData);
        $problemForm->setDbAdapter($this->getTrainingTable()->getDbAdapter());

        if ($problemForm->isValid()) {
            $newProblem = $problemForm->get('problem_id')->getValue();
            $this->getTrainingTable()->addProblem($training->training_id, $newProblem);
        }
    }

    private function addGroup($groupForm, $postData, $training) {
        $groupForm->setData($postData);
        $groupForm->setDbAdapter($this->getTrainingTable()->getDbAdapter());

        if ($groupForm->isValid()) {
            $newGroup = $groupForm->get('group_id')->getValue();
            $this->getTrainingTable()->addGroup($training->training_id, $newGroup);
        }
    }
    private function removeProblem($problemForm, $postData, $training) {
        $problemForm->setData($postData);
        $problemForm->setValidateAdd(false);
        $problemForm->setDbAdapter($this->getTrainingTable()->getDbAdapter());

        if ($problemForm->isValid()) {
            $problemID = $problemForm->get('problem_id')->getValue();
            $this->getTrainingTable()->removeProblem($training->training_id, $problemID);
        }
    }

    private function removeGroup($groupForm, $postData, $training) {
        $groupForm->setData($postData);
        $groupForm->setValidateAdd(false);
        $groupForm->setDbAdapter($this->getTrainingTable()->getDbAdapter());

        if ($groupForm->isValid()) {
            $groupID = $groupForm->get('group_id')->getValue();
            $this->getTrainingTable()->removeGroup($training->training_id, $groupID);
        }
    }

    public function isEnabled($startDate, $startTime) {
        $todayDate = date("Y-m-d");
        $todayTime = date("G:i");
        $date1 = strtotime($todayDate . ' ' . $todayTime);
        $date2 = strtotime(substr($startDate, 0, 10) . ' ' . $startTime);
        exec('echo ' . $date1 . ' > /tmp/hola');
        exec('echo ' . $date2 . ' >> /tmp/hola');

        if ($date1 < $date2) {
            return false;
        }

        return true;
    }

}
