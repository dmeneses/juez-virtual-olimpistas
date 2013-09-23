<?php

namespace Problem\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Problem\Model\Problem;
use Problem\Form\ProblemForm;

class ProblemController extends AbstractActionController {

    protected $problemTable;

    public function indexAction() {
        return new ViewModel(array(
            'problems' => $this->getProblemTable()->fetchAll(),
        ));
    }

    public function getProblemTable() {
        if (!$this->problemTable) {
            $sm = $this->getServiceLocator();
            $this->problemTable = $sm->get('Problem\Model\ProblemTable');
        }
        return $this->problemTable;
    }

    public function addAction() {
        $form = new ProblemForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $problem = new Problem();
            $form->setInputFilter($problem->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $problem->exchangeArray($form->getData());
                $this->getProblemTable()->saveProblem($problem);
                return $this->redirect()->toRoute('problem');
            }
        }
        return array('form' => $form);
    }
}
