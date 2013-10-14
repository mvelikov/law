<?php
require_once 'BaseController.php';
class IndexController extends BaseController
{
    protected $_formOptions = array();
    protected $_redirector = null;

    public function init()
    {
        date_default_timezone_set('Europe/Helsinki');
        $this->_redirector = $this->_helper->getHelper('Redirector');
        $this->_formOptions = array(
        "types"     => $this->_types,
        "towns"     => $this->_towns,
        "courts"    => $this->_courts,
        "cases"     => $this->_cases,
    );
    }

    public function indexAction()
    {
        // $this->view->message = $this->_helper->flashmessenger->getMessages();
        $this->view->message = $this->_request->getParam('message');
        $this->view->menu = $this->_homeMenu;
        $this->view->title = "Адвокатски бележник";
    }


    public function insertCaseAction()
    {
        $form = new Application_Form_InsertCase();
        $form->setAction($this->view->url());
        $form->startForm($this->_formOptions);
        if ($this->_request->getPost()) {
            $formData = $this->_request->getPost();

            if ($form->isValid($formData)) {

                $formData['date'] = strtotime($formData['date'] . $formData['hour']);
                $formData['date_history'] = base64_encode(serialize(array($formData['date'])));
                unset($formData['hour'], $formData['submit']);

                $caseModel = new Application_Model_DbTable_Case();
                try {
                    $caseModel->insert($formData);
                } catch (Exception $e) {
                    $this->_redirector->gotoSimple('index', 'index', null, array("message" => "Имаше грешка, опитайте пак!"));
                }
                // $this->_helper->FlashMessenger("Делото е запазено!");
                $this->_redirector->gotoSimple('index', 'index', null, array("message" => "Делото е запазено!"));
            } else {
                $form->populate($formData);
            }
        }
        $this->view->title = "Добави дело";
        $this->view->insertCaseForm = $form;

        // $this->view
            // ->headLink()
            // ->appendStylesheet($this->view->baseUrl('css/jquery.mobile.datebox-1.0.0.min.css'));
        // $this->view
            // ->headScript()
            // ->prependFile($this->view->baseUrl('js/jquery.mobile.datebox-1.0.0.min.js'));
    }

    public function viewCaseAction()
    {

        $id = $this->_request->getParam('id');
        if ($id > 0) {
            $caseModel = new Application_Model_DbTable_Case();



            $case = $caseModel->getCaseById($id);
            if ($case['archive'] == 0) {
                $this->view->edit = array(
                    'urlConfig' => array(
                        'controller'    => 'index',
                        'action'        => 'edit-case',
                        'id'            => $id,
                    ),
                    'text'      => 'Промени',
                );
            }

            $case['date_history'] = unserialize(base64_decode($case['date_history']));
            if (is_array($case['date_history'])) {
                foreach ($case['date_history'] as $key => &$date) {
                    $date = date("r", $date);
                }
            }
            // $case['date_history'] = array_walk($case['date_history'],array("IndexController", "date"));
            $this->view->case = $case;
            $this->view->types = $this->_types;
            $this->view->towns = $this->_towns;
            $this->view->cases = $this->_cases;
            $this->view->courts = $this->_courts;
            $this->view->title = "Дело";
        } else {
            $this->_redirector->gotoSimple('index', 'index', null, array("message" => "Избрано е невалидно дело, опитай отново!"));
        }
    }

    public function editCaseAction()
    {
        $id = $this->_request->getParam('id');
        if ($id > 0) {
            $caseModel = new Application_Model_DbTable_Case();

            $case = $caseModel->getCaseById($id);
            $case['date_history'] = unserialize(base64_decode($case['date_history']));

            $form = new Application_Form_InsertCase();
            $form->setAction($this->view->url());

            if ($this->_request->getPost()) {
                $formData = $this->_request->getPost();

                if ($form->isValid($formData)) {

                    $caseModel = new Application_Model_DbTable_Case();
                    $oldCase = $caseModel->getCaseByNumber($formData['number']);
                    echo "<pre>";
                    var_dump($formData['number'], $oldCase);
                    $formData['date'] = strtotime($formData['date'] . $formData['hour']);
                    $formData['date_history'] = array_merge((array)unserialize(base64_decode($oldCase['date_history'])), array($formData['date']));

                    $formData['date_history'] = base64_encode(serialize(array_unique($formData['date_history'])));

                    unset($formData['hour'], $formData['submit']);
                    try {
                        // var_dump($formData);exit;
                        $caseModel->updateCaseByNumber($formData['number'], $formData);
                        $this->_redirector->gotoSimple('index', 'index', null, array("message" => "Промените са запазени!"));
                    } catch (Exception $e) {
                        $this->_redirector->gotoSimple('index', 'index', null, array("message" => "Имаше грешка при промяната, опитайте пак!"));
                    }
                } else {
                    $form->populate($formData);
                }
            } else {
                unset($case['date']);
                $form->editForm($this->_formOptions);
                $form->populate($case);
            }
            $this->view->form = $form;

            if (is_array($case['date_history'])) {
                foreach ($case['date_history'] as $key => &$date) {
                    $date = date("r", $date);
                }
            }
            $this->view->dateHistory = $case['date_history'];
            $this->view->title = "Дело № " . $case['number'];
        } else {
            $this->_redirector->gotoSimple('index', 'index', null, array("message" => "Избрано е невалидно дело, опитай отново!"));
        }
    }

    public function archiveCaseAction()
    {
        $id = $this->_request->getParam('id');

        if ($id > 0) {
            $caseModel = new Application_Model_DbTable_Case();
            // $case = $caseModel->getCaseById($id);
            $caseModel->updateCaseById($id, array('archive' => 1));
            $this->_redirector->gotoSimple('index', 'index', null, array("message" => "Делото е успешно архивирано!"));
        } else {
            $this->_redirector->gotoSimple('index', 'index', null, array("message" => "Избрано е невалидно дело, опитай отново!"));
        }
    }

    public function archiveAction()
    {

        $caseModel = new Application_Model_DbTable_Case();
        $this->view->cases = $caseModel->getArchivedCases();
        $this->view->title = "Архив";
    }
    public function searchAction()
    {
        // $this->view->type = $this->_request->getParam('type');

        // $form = new Application_Form_Search();
        // $form->startForm(array('type' => $type));
        // $form->setAction($this->view->url());
        $type = $this->_request->getParam('type');

        $caseModel = new Application_Model_DbTable_Case();
        // if ($this->_request->getPost()) {
            // $formData = $this->_request->getPost();

            // if ($form->isValid($formData)) {

                // $case = $caseModel->getCaseByNumber($formData['number'], $this->_request->getParam('type'));

                // $this->_redirector->gotoSimple('view-case', 'index', null, array('id' => $case['id']));
            // } else {
                // $form->populate($formData);
            // }
            // $this->view->form = $form;
        // }
        $cases = $caseModel->getCasesByType($type);
        $this->view->title = "Търси дело";
        $this->view->cases = $cases;

    }


}

    // public function selectCourtAction()
    // {
        // $this->_home = $this->_request->getParam('home');
        // $this->view->home = $this->view->title = $home;
        // $this->view->courtsList = $this->_courts;
    // }

    // public function selectCaseTypeAction()
    // {
        // $this->view->home = $this->_home = $this->_request->getParam('home');
        // $this->view->court = $this->_court = $this->_request->getParam('court');
        // $this->view->city = $this->_city = $this->_request->getParam('city');
        // $this->view->cases = $this->_cases;
    // }
    // public function selectCasesAction()
    // {}
     // public function submitAction()
    // {
        // $request = $this->getRequest();
        // $form = new Application_Form_Search();

    // }

        // protected $_courts = array(
        // 'Районен' => array(
            // 'Варна',
            // 'Провадия'
        // ),
        // 'Окръжен' => array(
            // 'Варна'
        // ),
        // 'Касационен' => array(
            // 'Варна',
            // 'София'
    // ));

        // protected $_homeMenu = array('Дело' => 'Търси Дело', 'НБПП' => 'Търси НБПП', 'Въведи ново дело',  'Архив');