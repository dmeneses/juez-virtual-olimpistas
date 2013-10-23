<?php

namespace Solution\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Solution\Model\Solution;
use Solution\Form\SolutionForm;

class SolutionController extends AbstractActionController {

    protected $solutionTable;

    public function indexAction() {
        return new ViewModel(array(
            'solutions' => $this->getSolutionTable()->getLast20Solution(),
        ));
    }

    public function getSolutionTable() {
        if (!$this->solutionTable) {
            $sm = $this->getServiceLocator();
            $this->solutionTable = $sm->get('Solution\Model\SolutionTable');
        }
        return $this->solutionTable;
    }

    public function addAction() {

        $id = (int) $this->params()->fromRoute('id', 0);
        $form = new SolutionForm();

        if ($id) {
            $form->get('problem_id')->setAttribute('value', $id);
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $solution = new Solution();
            $form->setInputFilter($solution->getInputFilter());

            $post = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );

            $form->setData($post);

            if ($form->isValid()) {
                $solution->exchangeArray($form->getData());
                $this->getSolutionTable()->saveSolution($solution);
                exec('echo 1 ' . $solution->solution_id . ' ' . '> /tmp/bravesoft_input');
                return $this->redirect()->toRoute('solution');
            }
        }
        return array('form' => $form);
    }
    
    public function displayAction() {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('solution');
        }
        $solution = $this->getSolutionTable()->get($id);
        
        return array('solution' => $solution);
    }
}