<?php

namespace Group\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Group\Model\Training;
use Group\Form\TrainingForm;
use Group\Form\CreateTrainingForm;

class GroupController extends AbstractActionController {

    protected $groupTable;
    protected $problemTable;

    public function indexAction() {
        new ViewModel();
    }

    public function getTrainingTable() {
        if (!$this->groupTable) {
            $sm = $this->getServiceLocator();
            $this->groupTable = $sm->get('Group\Model\TrainingTable');
        }
        return $this->groupTable;
    }

    public function getProblemTable() {
        if (!$this->problemTable) {
            $sm = $this->getServiceLocator();
            $this->problemTable = $sm->get('Problem\Model\ProblemTable');
        }
        return $this->problemTable;
    }

    public function createtrainingAction() {
        $form = new CreateTrainingForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $training = new Training();
            $form->setInputFilter($training->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $training->exchangeArray($form->getData());
                $this->getTrainingTable()->saveTraining($training);
                return $this->redirect()->toUrl('../group/displaytrainings');
            }
        }

        return array('form' => $form);
    }

    public function displaytrainingsAction() {
        return new ViewModel(array(
            'trainings' => $this->getTrainingTable()->fetchAll(),
        ));
    }

    public function trainingAction() {

        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id == 0) {
            return $this->redirect()->toRoute("group");
        }

        $form = new TrainingForm();
        $form->get('training_id')->setAttribute('value', $id);

        $request = $this->getRequest();

        if ($request->isPost()) {
            $training = new Training();
            $form->setInputFilter($training->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $training->exchangeArray($form->getData());
                $this->getTrainingTable()->addProblem($training);
            }
        }

        if(isset($training))
            $id = $training->training_id;
        
        $problems = $this->getProblemTable()->getProblemsByTraining($id);
        return array('form' => $form, 'problems' => $problems, 'id' => $id);
    }

}