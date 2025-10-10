<?php
class CustomerComplaints extends MY_ApiController{

	public function __construct(){
        parent::__construct();   
        $this->data['headData']->pageTitle = "Customer Complaints";	
	}

	public function getComplaintList(){
        $data = $this->input->post();	
		$errorMessage = array();

		if(empty($data['status'])){
            $errorMessage['status'] = "Status is required.";
		}
        if(!empty($errorMessage)){
            $this->printJson(['status' => 0,'message' => $errorMessage]);
		}
        else{
			$complaintRec = [];
			$complaintsData = $this->customerComplaints->getCustomerComplaintsData($data);

			if(!empty($complaintsData)){
				foreach ($complaintsData as $row) {
					$complaintRec[] = array(
						'id' => $row->id,
						'date' => date('d-m-Y',strtotime($row->date)),
						'project_name' => $row->project_name,
						'remark' => $row->remark,
					);
				}								
				$this->printJson(['status' => 1, 'message' => 'Data Found', 'data' => $complaintRec]);
			} else{
				$this->printJson(['status' => 0, 'message' => 'Data Not Found', 'data' => []]);
			}
		}
    }
}
?>