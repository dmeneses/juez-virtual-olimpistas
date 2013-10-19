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

    public function indexAction() {
        return new ViewModel(array('trainings' => $this->getTrainingTable()->fetchAll()));
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
        if (!$id) {
            return $this->redirect()->toRoute('training');
        }

        $form = new EditTrainingForm();
        $form->get('training_id')->setValue($id);

        try {
            $training = $this->getTrainingTable()->get($id);
            $problems = array();
        } catch (Exception $ex) {
            return $this->redirect()->toRoute('training');
        }

        return array(
            'form' => $form,
            'trainingData' => $training,
            'trainingProblems' => $problems,
        );
    }

}
