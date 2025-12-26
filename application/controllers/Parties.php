<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Parties extends MY_Controller{
    private $index = "party/index";
    private $form = "party/form";
    private $lead_index = "party/lead_index";

    public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Party Master";
		$this->data['headData']->controller = "parties";        
    }

    public function list($type="customer"){
        $this->data['headData']->pageUrl = "parties/list/".$type;
        $this->data['type'] = $type;
        $this->data['party_category'] = $party_category = array_search(ucwords($type),$this->partyCategory);
		$this->data['headData']->pageTitle = $this->partyCategory[$party_category];
        $this->data['tableHeader'] = getMasterDtHeader($type);
        $this->load->view($this->index,$this->data);
    }

    public function getDTRows($party_category,$party_type = 1){
        $data=$this->input->post();
		$data['party_category'] = $party_category;
		$data['party_type'] = $party_type;
        $result = $this->party->getDTRows($data);
        $sendData = array();
        $i = ($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $row->table_status = $party_category;
            $row->party_category_name = $this->partyCategory[$row->party_category];
            $sendData[] = getPartyData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addParty(){
        $data = $this->input->post();
        $this->data['party_category'] = $data['party_category'];
        $this->data['party_type'] = (!empty($data['party_type']) ? $data['party_type'] : '1');
        $this->data['countryData'] = $this->party->getCountries();
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
        $this->load->view($this->form, $this->data);
    }

	public function save(){
        $data = $this->input->post();
        $errorMessage = array();
       
        if (empty($data['party_name']))
            $errorMessage['party_name'] = "Company name is required.";

        if (empty($data['party_category']))
            $errorMessage['party_category'] = "Party Category is required.";
           
        if (empty($data['party_phone']))
            $errorMessage['party_phone'] = "Mobile No. is required.";
    
        if (empty($data['country_id']))
            $errorMessage['country_id'] = 'Country is required.';

        if (empty($data['state_id']))
            $errorMessage['state_id'] = 'State is required.';

        if (empty($data['city_name']))
            $errorMessage['city_name'] = 'City is required.';

        if (empty($data['party_address']))
            $errorMessage['party_address'] = "Address is required.";
        

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $data['party_name'] = ucwords($data['party_name']);
            $data['gstin'] = (!empty($data['gstin']))?strtoupper($data['gstin']):"";

            $this->printJson($this->party->save($data));
        endif;
    }
	
    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->party->getPartyList(['id'=>$data['id'],'single_row'=>1]);
        $this->data['countryData'] = $this->party->getCountries();
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
        $this->load->view($this->form, $this->data);
       
    }

    public function delete(){
        $id = $this->input->post('id');
        if (empty($id)) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->party->delete($id));
        endif;
    }

    // CRM DATA
    public function leadIndex(){
        $this->data['headData']->pageUrl = "parties/leadIndex";
		$this->data['headData']->pageTitle = 'Lead';
        $this->data['tableHeader'] = getMasterDtHeader('lead');
        $this->load->view($this->lead_index,$this->data);
    }

    public function getLeadDTRows($party_type = 1){
        $data=$this->input->post();
		$data['party_category'] = 1;
		$data['party_type'] = $party_type;
        $result = $this->party->getDTRows($data);
        $sendData = array();
        $i = ($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $row->party_type = $party_type;
            $sendData[] = getLeadData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

     public function addReminder(){
        $data = $this->input->post();
        $this->data['party_id'] = $data['party_id'];
        $this->data['id'] = (!empty($data['id']) ? $data['id'] : '');
        $this->data['lead_stage'] = (!empty($data['lead_stage']) ? $data['lead_stage'] : '');
        $this->load->view('party/reminder_form', $this->data);
    }

    public function saveReminder(){
        $data = $this->input->post();
        $errorMessage = [];
        if (!empty($data['lead_stage']) && $data['lead_stage'] == 2 && empty($data['id'])) {
            if(empty($data['ref_date']))
                $errorMessage['ref_date'] = "Date is required.";
            if(empty($data['reminder_time']))
                $errorMessage['reminder_time'] = "Time is required.";
            if(empty($data['mode']))
                $errorMessage['mode'] = "Mode is required.";
            if (empty($data['remark'])) 
			    $errorMessage['remark'] = "Remark is required.";
		}
        
        // if (empty($data['response']) && !empty($data['id'])) {
		// 	$errorMessage['response'] = "Response is required.";
		// }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(empty($data['id']) && $data['lead_stage'] == 2){
                $data['ref_date'] = date("Y-m-d H:i:s",strtotime($data['ref_date']." ".$data['reminder_time']));
                unset($data['reminder_time']);
            }
            $this->printJson($this->party->savePartyActivity($data));
        endif;
    }

    public function saveResponse(){ 
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['response']))
            $errorMessage['response'] = "Response is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$party_id = $data['party_id']; unset($data['party_id']); 
            $result = $this->party->savePartyActivity($data);
            $result['message'] = ($result['status'] == 1)?"Response done":$result['message'];
			
			$this->data['activityDetails'] = $this->party->getPartyActivity(['party_id'=>$party_id]);
			$this->data['party_id'] = $party_id;
			$activityLogs = $this->load->view('party/party_activity_details', $this->data, true);
			
			$result['activityLogs'] = $activityLogs;
            $this->printJson($result);
        endif;
    }

    public function partyActivity($param=[]){
        $postData = $this->input->post();
		$data = Array();
		if(!empty($postData)){
            $data = $postData;
        }else{
            $data = $param;
        }
        $this->data['activityDetails'] = $this->party->getPartyActivity($data); 
        $this->data['party_id'] = $data['party_id'];
        $this->load->view('party/party_activity_details',$this->data);
    }

    public function changeLeadStages(){
        $postData = $this->input->post();
        $errorMessage = [];

        if(empty($postData['id']))
			$errorMessage['id'] = "Party is required.";
        if(empty($postData['lead_stage']))
			$errorMessage['lead_stage'] = "Lead Stage is required.";

       
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->party->changeLeadStages($postData));
        endif;
    }

    public function saveFollowups(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['notes']))
            $errorMessage['notes'] = "Notes is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['lead_stage'] = 5; $data['id']="";
            $result = $this->party->savePartyActivity($data);
            $result['message'] = ($result['status'] == 1)?"Follow up done":$result['message'];
			
			$this->data['activityDetails'] = $this->party->getPartyActivity(['party_id'=>$data['party_id']]);
			$this->data['party_id'] = $data['party_id'];
			$activityLogs = $this->load->view('party/party_activity_details', $this->data, true);
			
			$result['activityLogs'] = $activityLogs;
            $this->printJson($result);
        endif;
    }


}
?>