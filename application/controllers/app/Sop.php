<?php
class Sop extends MY_Controller{

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Sop";
		$this->data['headData']->controller = "app/sop";
		$this->data['headData']->pageUrl = "app/sop";
		$this->data['headData']->appMenu = "app/sop";   
	}
	
	public function index(){
        $this->data['rec_per_page'] = 10; // Records Per Page
        $this->load->view('app/sop_desk',$this->data);
    }

    public function getPrcList($parameter = []){
        $next_page = 0;
        $postData = $this->input->post();
		$prcList = Array();
		if(isset($postData['page']) AND isset($postData['start']) AND isset($postData['length']))
        {
            $prcList = $this->sop->getPRCList($postData);
            $next_page = intval($postData['page']) + 1;
        }
        else{ $prcList = $this->sop->getPRCList($postData); }
		$this->data['prcList'] = $prcList;
        $prcList = $this->load->view('app/prc_list_view',$this->data,true);
        $this->printJson(['orderDetail'=>$prcList,'next_page'=>$next_page]);
    }
	
    public function prcDetail($id){
        $this->data['prcData'] = $this->sop->getPRC(['id'=>$id]);
        $this->load->view('app/prc_detail',$this->data);
    }

    public function getPrcDetailHtml(){
        $postData = $this->input->post();
		$this->data['status'] =2;
        $prcData = $this->data['prcData'] = $this->sop->getPRCDetail(['prc_id'=>$postData['id']]);
        $this->data['prcProcessData'] = (!empty($prcData->prcProcessData)) ? $prcData->prcProcessData : [];
        $prcProcessData = $this->sop->getPRCProcessList(['current_process_id'=>0,'prc_id'=>$postData['id'],'log_data'=>1,'movement_data'=>1,'single_row'=>1]); 
        $ok_qty = !empty($prcProcessData->ok_qty)?$prcProcessData->ok_qty:0;
        $movement_qty =!empty($prcProcessData->movement_qty)?$prcProcessData->movement_qty:0;
        $this->data['pending_movement'] = $ok_qty - $movement_qty;
        $processDetail = $this->load->view('app/prc_detail_view',$this->data,true);
		
        $this->printJson(['processDetail'=>$processDetail]);
    }

    public function prcMovement(){
		$data = $this->input->post();
		$this->data['dataRow'] = $prcProcessData = $this->sop->getPRCProcessList(['id'=>$data['id'],'log_data'=>1,'movement_data'=>1,'pending_accepted'=>1,'process_bom'=>1,'single_row'=>1]);
		$this->data['machineList'] = $this->item->getItemList(['item_type'=>5]);
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['final_location'=>1]);		
		$ok_qty = !empty($prcProcessData->ok_qty)?$prcProcessData->ok_qty:0;
		$movement_qty =!empty($prcProcessData->movement_qty)?$prcProcessData->movement_qty:0;
		$pending_movement = $ok_qty - $movement_qty;
		$this->data['pending_movement'] = $pending_movement;
		$this->data['masterSetting'] = $this->sop->getAccountSettings();
		$this->load->view('app/prc_movement_form',$this->data);
	}

    public function prcAccept(){
		$data = $this->input->post();
		$this->data['accepted_process_id'] = $data['id'];
		$this->data['prc_id'] = $data['prc_id'];
		$this->data['prev_prc_process_id'] = $data['prev_prc_process_id'];
		$this->load->view('app/accept_prc_qty',$this->data);
	}

	public function prcLog(){
		$data = $this->input->post();
		$this->data['dataRow'] = $prcProcessData = $this->sop->getPRCProcessList(['id'=>$data['id'],'log_data'=>1,'movement_data'=>1,'pending_accepted'=>1,'process_bom'=>1,'single_row'=>1]);
		if(!empty($data['challan_id'])){
			$this->data['challan_id'] = $data['challan_id'];
			$this->data['ref_trans_id'] = $data['ref_trans_id'];
			$this->data['process_by'] = $data['process_by'];
			$this->data['processor_id'] = $data['processor_id'];
		}
		$this->data['machineList'] = $this->item->getItemList(['item_type'=>5]);
		$this->data['shiftData'] = $this->shiftModel->getShiftList();
		$this->data['operatorList'] = $this->employee->getEmployeeList();		

		$in_qty = (!empty($prcProcessData->current_process_id))?(!empty($prcProcessData->in_qty)?$prcProcessData->in_qty:0):$prcProcessData->ok_qty;
		$ok_qty = !empty($prcProcessData->ok_qty)?$prcProcessData->ok_qty:0;
		$rej_found = !empty($prcProcessData->rej_found)?$prcProcessData->rej_found:0;
		$rw_qty = !empty($prcProcessData->rw_qty)?$prcProcessData->rw_qty:0;
		$rej_qty = !empty($prcProcessData->rej_qty)?$prcProcessData->rej_qty:0;
		$pendingReview = $rej_found - $prcProcessData->review_qty;
		$pending_production =($in_qty) - ($ok_qty+$rej_qty+$rw_qty+$pendingReview+$prcProcessData->ch_qty);
		$this->data['pending_log'] = $pending_production;
		$this->data['masterSetting'] = $this->sop->getAccountSettings();
		$this->load->view('app/prc_log_form',$this->data);
	}

    public function challanRequest(){
		$data = $this->input->post();
		$this->data['dataRow'] = $this->sop->getPRCProcessList(['id'=>$data['id'],'single_row'=>1]);
		$this->load->view('app/prc_challan_request',$this->data);
	}

    public function receiveStoredMaterial(){
        $data = $this->input->post();
		$this->data['dataRow'] = $this->sop->getPRCProcessList(['id'=>$data['id'],'single_row'=>1]);
        $this->data['movementList'] = $this->sop->getProcessMovementList(['prc_process_id'=>$data['id'],'send_to'=>4]);
        $this->load->view('app/receive_movement',$this->data);
    }

    public function addPrcStock(){
		$data = $this->input->post();
		$this->data['dataRow'] = $this->sop->getPRCProcessList(['id'=>$data['id'],'log_data'=>1,'movement_data'=>1,'pending_accepted'=>1,'single_row'=>1]);
		$this->load->view('app/prc_stock_form',$this->data);
	}

	public function getPrcLogDetail(){
		$data = $this->input->post();
		$this->data['logData'] = $this->sop->getProcessLogList($data);
		$this->data['dataRow'] = $this->sop->getPRCProcessList(['id'=>$data['prc_process_id'],'log_data'=>1,'movement_data'=>1,'pending_accepted'=>1,'single_row'=>1]);
		$this->load->view('app/log_detail',$this->data);
	}

	public function getPrcMovementDetail(){
		$data = $this->input->post();
		$this->data['movementData'] = $this->sop->getProcessMovementList(['prc_process_id'=>$data['prc_process_id']]);
		$this->data['dataRow'] = $this->sop->getPRCProcessList(['id'=>$data['prc_process_id'],'log_data'=>1,'movement_data'=>1,'pending_accepted'=>1,'single_row'=>1]);
		$this->load->view('app/movement_detail',$this->data);
	}
}
?>