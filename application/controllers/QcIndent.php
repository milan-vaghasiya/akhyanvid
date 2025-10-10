<?php
class QcIndent extends MY_Controller
{
    private $indexPage = "qc_indent/index";
    private $form = "qc_indent/form";

    public function __construct(){
        parent::__construct();
        $this->isLoggedin();
        $this->data['headData']->pageTitle = "QC Indent";
        $this->data['headData']->controller = "qcIndent";
        $this->data['headData']->pageUrl = "qcIndent";
    }

    public function index(){
        $this->data['tableHeader'] = getQualityDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage, $this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();
        $data['status'] = $status;
        $result = $this->qcIndent->getDTRows($data);
        $sendData = array();
        $i = 1;
        $count = 0;
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $sendData[] = getQCIndentData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function closePurReq(){
        $data = $this->input->post();

        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Something went wrong...Please try again.']);
        else :
            $this->printJson($this->qcPRModel->closePurReq($data));
        endif;
    }
}