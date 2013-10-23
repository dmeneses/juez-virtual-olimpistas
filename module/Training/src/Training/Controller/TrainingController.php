<?php

namespace Training\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Training\Model\Training;
use Training\Model\TrainingTable;
use Training\Form\CreateTrainingForm;
use Training\Form\EditTrainingForm;

class TrainingController extends AbstractActionController {

    protected $trainingTable;
    protected $problemTable;

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

    public function createAction() {
        $form = new CreateTrainingForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $training = new Training();
            $training->setDbAdapter($this->getTrainingTable()->getDbAdapter());
            $form->setInputFilter($training->getInputFilter());
            $form->setData($request->getPost());

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
        $form = new EditTrainingForm();
        $filter = $this->getServiceLocator()->get('Training\Form\EditTrainingFilter');
        $form->setFilter($filter);
        $form->get('training_id')->setValue($id);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $newProblem = $form->get('problem_id')->getValue();
                $this->getTrainingTable()->addProblem($training->training_id, $newProblem);
            }
        }

        $problems = $this->getProblemTable()->getProblemsByTraining($id);
        return array(
            'form' => $form,
            'trainingData' => $training,
            'trainingProblems' => $problems,
        );
    }
}
