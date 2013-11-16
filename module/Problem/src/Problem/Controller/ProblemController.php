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

        return new ViewModel(array('page' => $page, 'paginator' => $paginator,));
    }

    public function getProblemTable() {
        if (!$this->problemTable) {
            $sm = $this->getServiceLocator();
            $this->problemTable = $sm->get('Problem\Model\ProblemTable');
            $this->problemTable->setTestCaseTable($sm->get('Problem\Model\TestCaseTable'));
        }
        return $this->problemTable;
    }

    public function getLoggedUserID() {
        return $this->getServiceLocator()->get('LoggedUserID');
    }

    public function addAction() {
        $form = new ProblemForm();
        $userID = $this->getLoggedUserID();

        if (!$userID) {
            return $this->redirect()->toRoute('login');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $problem = new Problem();
            $problem->setDatabaseAdapter($this->getProblemTable()->getAdapter());
            $form->setInputFilter($problem->getInputFilter());
            $post = array_merge_recursive($request->getPost()->toArray(), $request->getFiles()->toArray());
            if (isset($post['tests'])) {
                $post['tests'] = $this->mergeFiles($post['tests']);
            }
            $form->setData($post);

            if ($form->isValid()) {
                $data = $form->getData();
                $problem->exchangeArray($data);
                $problem->problem_creator = $userID;
                $this->getProblemTable()->saveProblem($problem);

                $this->buildProblemView($problem->problem_id, $data);
                return $this->redirect()->toRoute('problem');
            }
        }
        return array('form' => $form,);
    }

    private function mergeFiles(array $files) {
        $middle = count($files) / 2;
        $result = array();

        for ($index = 0; $index < $middle; $index++) {
            $filesToMerge = $files[$index + $middle];
            $dataToMerge = $files[$index];
            $merged = array_merge($dataToMerge, $filesToMerge);
            array_push($result, $merged);
        }
        return $result;
    }

    private function buildProblemView($id, $data) {

        $mainDesc = (!empty($data['main_description'])) ? $data['main_description']['tmp_name'] : null;
        $input = (!empty($data['input_description'])) ? $data['input_description']['tmp_name'] : null;
        $output = (!empty($data['output_description'])) ? $data['output_description']['tmp_name'] : null;
        $in_example = (!empty($data['input_example'])) ? $data['input_example']['tmp_name'] : null;
        $out_example = (!empty($data['output_example'])) ? $data['output_example']['tmp_name'] : null;
        $descriptionOutput = './data/problems/descriptions/problem' . $id . '.html';

        $command = './runLatexConverter ';
        $command .= $mainDesc . ' ';
        $command .= $input . ' ';
        $command .= $output . ' ';
        $command .= $in_example . ' ';
        $command .= $out_example . ' ';
        $command .= $descriptionOutput . ' ';
        $command .= $id . ' ';
        $command .= '"' . $data['problem_name'] . '" ';
        $command .= '"' . $data['problem_author'] . '" ';
        $command .= $data['memory_constraint'] . ' ';
        $command .= $data['time_constraint'] . ' ';
        $command .= $data['source_constraint'] . ' ';
        exec($command . ' > /tmp/holas');
    }

    public function displayAction() {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('problem', array('action' => 'add'));
        }

        try {
            $problem = $this->getProblemTable()->getProblem($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('problem');
        }

        return new ViewModel(array('problemData' => $problem));
    }

    public function solutionsAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        
        if (!$id) {
            return $this->redirect()->toRoute('problem', array('action' => 'add'));
        }
        
        try {
            $solutions = $this->getProblemTable()->getProblemSolutions($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute('problem');
        }

        return new ViewModel(array('solutions' => $solutions,));
    }
}
