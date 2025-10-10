<?php
class MachineBreakdown extends MY_Controller{
    private $indexPage = "machine_breakdown/index";
    private $formPage = "machine_breakdown/form";   

	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Machine Breakdown";
		$this->data['headData']->controller = "machineBreakdown";
        $this->data['headData']->pageUrl = "machineBreakdown";
	}
	
	public function index(){
        $this->data['tableHeader'] = getProductionDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }
	
    public function getDTRows(){
        $data = $this->input->post(); 
        $result = $this->machineBreakdown->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row): 
            $row->sr_no = $i++;         
            $sendData[] = getMachineBreakdownData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addMachineBreakdown(){
        $data = $this->input->post(); 
        $this->data['prc_id'] = (!empty($data['prc_id']) ?$data['prc_id'] : 0);
        $this->data['machineList'] = $this->item->getItemList(['item_type'=>5]);
        $this->data['prcList'] = $this->sop->getPRCList(['status'=>2]);
        $this->data['reasonList'] = $this->comment->getCommentList(['type'=>2]);
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();

        if(empty($data['trans_date']))
			$errorMessage['trans_date'] = "Date is required.";

        if(empty($data['start_time']))
			$errorMessage['start_time'] = "Start Time is required.";

        if(empty($data['end_time']))
			$errorMessage['end_time'] = "End Time is required.";

        // if(empty($data['prc_id']))
		// 	$errorMessage['prc_id'] = "Prc No. is required.";

        if(empty($data['machine_id']))
			$errorMessage['machine_id'] = "Machine is required.";

        if(empty($data['idle_reason']))
			$errorMessage['idle_reason'] = "Idle Reason is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->machineBreakdown->save($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->machineBreakdown->getMachineBreakdown(['id'=>$data['id']]);
        $this->data['machineList'] = $this->item->getItemList(['item_type'=>5]);
        $this->data['prcList'] = $this->sop->getPRCList(['status'=>2]);
        $this->data['reasonList'] = $this->comment->getCommentList(['type'=>2]);
        $this->load->view($this->formPage,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->machineBreakdown->delete($id));
        endif;
    }
}