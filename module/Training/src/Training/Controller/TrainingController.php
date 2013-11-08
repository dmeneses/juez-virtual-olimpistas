<?php

namespace Training\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Training\Model\Training;
use Training\Form\CreateTrainingForm;
use Training\Form\AddProblemToTrainingForm;
use Training\Form\AddGroupToTrainingForm;
use Training\Model\DateValidator;

class TrainingController extends AbstractActionController {

    protected $trainingTable;
    protected $problemTable;
    protected $groupTable;

    public function indexAction() {
        return new ViewModel(array('trainings' => $this->getTrainingTable()->fetchAll()));
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

    public function createAction() {
        $form = new CreateTrainingForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $training = new Training();
            $form->setData($request->getPost());
            $form->setDbAdapter($this->getTrainingTable()->getDbAdapter());

            if ($form->isValid()) {
                $training->exchangeArray($form->getData());
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
        $problemForm = new AddProblemToTrainingForm();
        $problemForm->get('training_id')->setValue($id);
        $groupForm = new AddGroupToTrainingForm();
        $groupForm->get('training_id')->setValue($id);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost();
            if (isset($postData['addGroup'])) {
                $groupForm->setData($postData);
                $groupForm->setDbAdapter($this->getTrainingTable()->getDbAdapter());

                if ($groupForm->isValid()) {
                    $newGroup = $groupForm->get('group_id')->getValue();
                    $this->getTrainingTable()->addGroup($training->training_id, $newGroup);
                }
            } else {
                $problemForm->setData($postData);
                $problemForm->setDbAdapter($this->getTrainingTable()->getDbAdapter());

                if ($problemForm->isValid()) {
                    $newProblem = $problemForm->get('problem_id')->getValue();
                    $this->getTrainingTable()->addProblem($training->training_id, $newProblem);
                }
            }
        }

        $groups = $this->getGroupTable()->getGroupsByTraining($id);
        $data = array('problemForm' => $problemForm, 'groupForm' => $groupForm,
            'trainingData' => $training, 'trainingGroups' => $groups);
        if ($this->isEnabled($training->start_date, $training->start_time)) {
            $problems = $this->getProblemTable()->getProblemsByTraining($id);
            $data['trainingProblems'] = $problems;
        }

        return $data;
    }

    public function isEnabled($startDate, $startTime) {
        $todayDate = date("Y-m-d");
        $todayTime = date("G:i");
        $date1 = strtotime($todayDate . ' ' . $todayTime);
        $date2 = strtotime(substr($startDate, 0, 10) . ' ' . $startTime);
        exec('echo ' . $date1 . ' > /tmp/hola');
        exec('echo ' . $date2 . ' >> /tmp/hola');
        
        if($date1 < $date2) {
            return false;
        }
        
        return true;
    }

}
