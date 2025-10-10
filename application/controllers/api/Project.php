<?php
class Project extends MY_ApiController{

	public function getProjectList(){
        $data = $this->input->post();
		$data['trans_status'] = 2; //In progress project		
        $projectList = $this->project->getProjectData($data);

		if(!empty($projectList)){
			$this->printJson(['status' => 1, 'message' => 'Data Found', 'data' => $projectList]);
		} else{
			$this->printJson(['status' => 0, 'message' => 'Data Not Found', 'data' => []]);
		}
    }

	public function getProjectDetail(){
        $data = $this->input->post();		
        $projectDetail = $this->project->getProjectData($data);

		if(!empty($projectDetail) && !empty($data['id'])){
			$this->printJson(['status' => 1, 'message' => 'Data Found', 'data' => $projectDetail[0]]);
		} else{
			$this->printJson(['status' => 0, 'message' => 'Data Not Found', 'data' => []]);
		}
    }

	public function getProjectSpecification(){
        $data = $this->input->post();		
		$specificData = $this->project->getProjectSpecData(['project_id' => $data['id']]);
		
		if(!empty($data['id'])){
			$specificRec = $specData = [];

			foreach ($specificData as $key => $row) {
				$specData[$row->specification] = array(
					'id' => $row->id,
					'spec_desc' => $row->spec_desc,
				);
			}

			foreach ($this->specArray as $key => $label) {
				$id = isset($specData[$label]) ? $specData[$label]['id'] : '';
				$value = isset($specData[$label]) ? $specData[$label]['spec_desc'] : '';

				$specificRec[] = array(
					'id' => $id,
					'specification' => $label,
					'spec_desc' => $value,
				);
			}			
			$this->printJson(['status' => 1, 'message' => 'Data Found', 'data' => $specificRec]);
		}
		else{
			$this->printJson(['status' => 0, 'message' => 'Data Not Found', 'data' => []]);
		}
    }

	public function getProjectAgency(){
        $data = $this->input->post();
		$agencyData = $this->project->getProjectAgencyData(['project_id' => $data['id']]);
		
		if(!empty($data['id']) && !empty($agencyData)){
			$agencyRec = [];

			foreach ($agencyData as $key => $value) {
				$agencyRec[] = array(
					'id' => $value->id,
					'agency_name' => $value->agency_name,
					'agency_contact' => $value->agency_contact,
					'remark' => $value->remark,
				);
			}			
			$this->printJson(['status' => 1, 'message' => 'Data Found', 'data' => $agencyRec]);
		} else{
			$this->printJson(['status' => 0, 'message' => 'Data Not Found', 'data' => []]);
		}
    }

	public function getProjectWorkPlan(){
        $data = $this->input->post();
		
		if(!empty($data['id'])){
			$workPlanRec = [];

			$workProgressData = $this->workProgress->getWorkProgressData(['project_id' => $data['id']]);			
			foreach($workProgressData as $row){
				$wpData[$row->description] = ['step_no' => $row->step_no,'id' => $row->id];
			}
			
			$workPlanData = $this->workInstructions->getWorkInstructions(['work_type' => 2]);
			if(!empty($workPlanData)){
				foreach ($workPlanData as $key => $row) {
					$step_no = isset($wpData[$row->description]) ? $wpData[$row->description]['step_no'] : 0;

					$workPlanRec[] = array(
						'id' => $row->id,
						'work_title' => $row->work_title,
						'description' => $row->description,
						'step_no' => $step_no,
					);
				}
			}
			$this->printJson(['status' => 1, 'message' => 'Data Found', 'data' => $workPlanRec]);
		}
		else{
			$this->printJson(['status' => 0, 'message' => 'Data Not Found', 'data' => []]);
		}
    }

	public function getProjectWorkInstruction(){
        $data = $this->input->post();
		$records_available = false;
		
		$projectDetail = $this->project->getProjectData($data);				
		$workInstructionData = $this->workInstructions->getWorkInstructions(['work_type' => 1,'wi_id' => $projectDetail[0]->wi_id]);
		
		if(!empty($data['id']) && !empty($projectDetail) && !empty($workInstructionData)){				
			$workInstructionRec = [];
			$workIds = (!is_null($projectDetail[0]->wi_id)) ? explode(',', $projectDetail[0]->wi_id) : [];
			
			foreach ($workInstructionData as $key => $row) {
				$is_checked = in_array($row->id, $workIds) ? 1 : 0;

				if($is_checked == 1){
					$records_available = true;
					$workInstructionRec[] = array(
						'id' => $row->id,
						'work_title' => $row->work_title,
						'description' => $row->description,
						'is_checked' => $is_checked
					);
				}
			}
			if($records_available){
				$this->printJson(['status' => 1, 'message' => 'Data Found', 'data' => $workInstructionRec]);			
			}else{
				$this->printJson(['status' => 0, 'message' => 'Data Not Found', 'data' => []]);	
			}
		}
		else{
			$this->printJson(['status' => 0, 'message' => 'Data Not Found', 'data' => []]);
		}
    }

	public function saveSpecification(){
        $data = $this->input->post(); 		
		$decodeData = json_decode($data['specifications'],true);
		
		$specification_id = array_column($decodeData, 'id');
		$specification_desc = array_column($decodeData, 'description');
		unset($data['specifications']);

		$data['id']   = array_combine(range(1, count($specification_id)), array_values($specification_id));
		$data['specData'] = array_combine(range(1, count($specification_desc)), array_values($specification_desc));
		
		$this->printJson($this->project->saveSpecification($data));
    }

	public function saveAgency(){
        $data = $this->input->post();
        $errorMessage = array();

		if(empty($data['project_id'])){
            $errorMessage['project_id'] = "Project is required.";
		}
        if(empty($data['agency_name'])){
            $errorMessage['agency_name'] = "Agency Name is required.";
		}
        if(empty($data['agency_contact'])){
            $errorMessage['agency_contact'] = "Agency Contact is required.";
		}		
        if(!empty($errorMessage)){
            $this->printJson(['status' => 0,'message' => $errorMessage]);
		}
        else{            
			$this->printJson($this->project->saveAgency($data));
		}
    }

	public function deleteAgency(){ 
        $data = $this->input->post();

        if(empty($data['id'])){
            $this->printJson(['status' => 0,'message' => 'Somthing went wrong...Please try again.']);
		}
		else{
			$this->printJson($this->project->deleteAgency($data['id']));
		}
    }
}
?>