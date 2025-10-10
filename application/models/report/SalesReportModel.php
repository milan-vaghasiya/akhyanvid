<?php 
class SalesReportModel extends MasterModel
{

   /* Customer Detail Report */
    public function getCustomerDetailList($data){
        $queryData = [];
        $queryData['tableName']  = "party_master";
		$queryData['select'] = "party_master.*, executive_master.emp_name as executive_name,created_master.emp_name as created_name,b_countries.name as country_name,b_states.name as state_name,GROUP_CONCAT(party_activities.mode) as mode";

        $queryData['leftJoin']['party_activities'] = "party_activities.party_id = party_master.id";
        $queryData['leftJoin']['employee_master as executive_master'] = "executive_master.id = party_master.executive_id";
        $queryData['leftJoin']['employee_master as created_master'] = "created_master.id = party_master.created_by";
        $queryData['leftJoin']['countries as b_countries'] = "party_master.country_id = b_countries.id";
        $queryData['leftJoin']['states as b_states'] = "party_master.state_id = b_states.id";


        if(!empty($data['executive_id'])):
			$queryData['where']['party_master.executive_id'] = $data['executive_id'];
		endif;

        if(!empty($data['business_type'])):
			$queryData['where']['party_master.business_type'] = $data['business_type'];
		endif;

        if(!empty($data['lead_stage'])):
			$queryData['where']['party_master.party_type'] = $data['lead_stage'];
		endif;

		if(!empty($data['mode'])):
			$queryData['where']['party_activities.mode'] = $data['mode'];
		endif;
		
        if(!empty($data['from_date'])):
			$queryData['where']['party_master.created_at >= '] = date('Y-m-d H:i:s',strtotime($data['from_date'].' 00:00:00'));
		endif;
        if(!empty($data['to_date'])):
			$queryData['where']['party_master.created_at <= '] = date('Y-m-d H:i:s',strtotime($data['to_date'].' 23:59:59'));
		endif;
		
		// if(!in_array($this->userRole,[1,-1,2])):
        //     $queryData['customWhere'][] = '(find_in_set("'.$this->empId.'", employee_master.super_auth_id) > 0 OR employee_master.id = '.$this->loginId.')';
        // endif;   
         
        $queryData['group_by'][] = "party_master.id";
        $result = $this->rows($queryData);
        return $result;
    }
    
    /* Appointment Register Data */
    public function getAppointmentRegister($data){ 
        $queryData = array();
        $queryData['tableName'] = "party_activities";
        $queryData['select'] = "party_activities.id,party_activities.ref_date,party_activities.lead_stage,party_activities.notes,party_activities.remark,party_activities.updated_at,party_activities.mode,party_activities.party_id,party_master.party_name ,employee_master.emp_name,party_activities.created_by";

        $queryData['leftJoin']['party_master'] = "party_master.id = party_activities.party_id";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = party_master.executive_id";
        
        if(!empty($data['executive_id'])):
            $queryData['where']['party_master.executive_id'] = $data['executive_id'];
        endif;
        
        if(!empty($data['mode'])):
            $queryData['where']['party_activities.mode'] = $data['mode'];
        endif;

        if(!empty($data['status'])) {
            if($data['status'] == 1){
                $queryData['customWhere'][] = 'party_activities.updated_at IS NULL';
            }elseif($data['status'] == 2){
                $queryData['customWhere'][] = 'party_activities.updated_at IS NOT NULL';
            }elseif($data['status'] == 3){ 
                $queryData['customWhere'][] = 'DATE(party_activities.ref_date) < DATE(party_activities.updated_at)';
            }
        }

        if(!empty($data['from_date'])){
            $queryData['where']['DATE(party_activities.ref_date) >='] = $data['from_date'];
        }

        if(!empty($data['to_date'])){
            $queryData['where']['DATE(party_activities.ref_date) <='] = $data['to_date'];
        }
        $queryData['where']['party_activities.lead_stage'] = 2;
		$queryData['order_by']['party_activities.ref_date'] = 'ASC';

        $result = $this->rows($queryData);
        return $result;
    }

    /*  Followup Register Data*/
    public function getFollowUpRegister($data){
        $queryData = array();
        $queryData['tableName'] = "party_activities";
        $queryData['select'] = "party_activities.id,party_activities.created_at,party_master.executive_id,party_activities.notes,party_master.party_type,party_activities.party_id,party_master.party_name,employee_master.emp_name,party_master.business_type";
        $queryData['leftJoin']['party_master'] = "party_master.id = party_activities.party_id";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = party_master.executive_id";

		if(!empty($data['business_type'])){
            $queryData['where']['party_master.business_type'] = $data['business_type'];
        }
        if(!empty($data['from_date'])){
            $queryData['where']['DATE(party_activities.created_at) >='] = $data['from_date'];
        }
        if(!empty($data['to_date'])){
            $queryData['where']['DATE(party_activities.created_at) <='] = $data['to_date'];
        }
        if(!empty($data['party_id'])){
            $queryData['where']['party_activities.party_id'] = $data['party_id'];
        }
        $queryData['where']['party_activities.lead_stage'] = 5;

        $result = $this->rows($queryData);
        return $result;
    }
}
?>
