<?php
class WorkProgress extends MY_ApiController{

	public function getWorkProgressList(){
        $data = $this->input->post();	
		$workPlanData = $this->workProgress->getWorkProgressData(['project_id' => $data['project_id'],'step_no' => $data['step_no']]); 
		
		if(!empty($data['project_id']) && !empty($workPlanData)){	
			$groupedPlanData = $workPlanRec = [];
			
			foreach ($workPlanData as $row) {
				$groupedPlanData[$row->work_title][] = $row;
			}

			if(!empty($groupedPlanData)){
				foreach ($groupedPlanData as $key => $workPlans) {
					foreach ($workPlans as $row) {
						$is_checked = $row->status == 1 ? 1 : 0;

						$workPlanRec[] = array(
							'work_id' => $row->work_id,
							'work_title' => $row->work_title,
							'description' => $row->description,
							'is_checked' => $is_checked,
						);
					}
				}
			}
			$this->printJson(['status' => 1, 'message' => 'Data Found', 'data' => $workPlanRec]);
		}
		else{
			$this->printJson(['status' => 0, 'message' => 'Data Not Found', 'data' => []]);
		}
    }

	public function save(){
        $data = $this->input->post(); 
		$data['work_id'] = json_decode($data['work_id'],true);		
        $errorMessage = array();

		if(empty($data['project_id'])){
            $errorMessage['project_id'] = "Project is required.";
		}
        if(empty($data['step_no'])){
            $errorMessage['step_no'] = "Step No. is required.";
		}
        if(empty($data['work_id'][0])){
            $errorMessage['general_error'] = "Please select at least one Instruction.";
        }

        if(!empty($errorMessage)){
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
		}else{
			$workPlanData = $this->workProgress->getWorkProgressData(['project_id'=>$data['project_id'],'step_no'=>$data['step_no']]); 
			$groupedData = [];

			foreach ($workPlanData as $row) {
				$groupedData[$row->work_title][] = $row;
			}

			foreach ($groupedData as $workTitle => $instructions) {
				foreach ($instructions as $row) {
					$data['id'][] = $row->id;
					$data['all_work_id'][] = $row->work_id;
				}
			}
            $this->printJson($this->workProgress->save($data));
        }
    }
}
?>