<?php

namespace Problem\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Problem\Model\Problem;
use Problem\Form\ProblemForm;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbTableGateway;

class ProblemController extends AbstractActionController {

    protected $problemTable;

    public function indexAction() {
        $paginator = new Paginator(new DbTableGateway($this->getProblemTable()->getTableGateway()));
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;

        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage(10)
                ->setPageRange(7);

        return new ViewModel(array(
            'page' => $page,
            'paginator' => $paginator,
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
            $problem->setDatabaseAdapter($this->getProblemTable()->getAdapter());
            $form->setInputFilter($problem->getInputFilter());
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());

            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();
                $problem->exchangeArray($data);
                $this->getProblemTable()->saveProblem($problem);
                $description = './data/problems/descriptions/problem' . $problem->problem_id . '.html';
                $this->buildProblemView($problem->problem_id, $data, $description);
                return $this->redirect()->toRoute('problem');
            }
        }
        return array('form' => $form,);
    }

    private function buildProblemView($id, $data, $descriptionFile) {

        $mainDesc = (!empty($data['main_description'])) ? $data['main_description']['tmp_name'] : null;
        $input = (!empty($data['input_description'])) ? $data['input_description']['tmp_name'] : null;
        $output = (!empty($data['output_description'])) ? $data['output_description']['tmp_name'] : null;
        $in_example = (!empty($data['input_example'])) ? $data['input_example']['tmp_name'] : null;
        $out_example = (!empty($data['output_example'])) ? $data['output_example']['tmp_name'] : null;

        $command = './runLatexConverter ';
        $command .= $mainDesc . ' ';
        $command .= $input . ' ';
        $command .= $output . ' ';
        $command .= $in_example . ' ';
        $command .= $out_example . ' ';
        $command .= $descriptionFile. ' ';
        $command .= $id . ' ';
        exec($command);
    }

    public function displayAction() {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('problem', array(
                        'action' => 'add'
            ));
        }

        try {
            $problem = $this->getProblemTable()->getProblem($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('problem');
        }

        return new ViewModel(array(
            'problemData' => $problem
        ));
    }

}
