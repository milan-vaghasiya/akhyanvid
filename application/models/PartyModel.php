<?php
class PartyModel extends MasterModel{
    private $partyMaster = "party_master";
    private $countries = "countries";
    private $states = "states";
    private $partyActivities = "party_activities";


    /* Start Party Data */
    public function getDTRows($data){
        $data['tableName'] = $this->partyMaster;
        $data['select'] = "party_master.*,employee_master.emp_name as executive_name";
        $data['leftJoin']['employee_master'] = "employee_master.id = party_master.executive_id";

        $data['where']['party_master.party_category'] = $data['party_category'];
        $data['where']['party_master.party_type'] = $data['party_type'];
      
        $data['searchCol'][] = "";
		$data['searchCol'][] = "";
        $data['searchCol'][] = "party_master.party_code";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "party_master.contact_person";
        $data['searchCol'][] = "party_master.party_phone";
        $data['searchCol'][] = "party_master.business_type";
        $data['searchCol'][] = "employee_master.emp_name";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getPartyList($data=array()){
        $queryData = array();
        $queryData['tableName']  = $this->partyMaster;
        $queryData['select'] = "party_master.*,";

        if(!empty($data['addData'])){
            $queryData['select'] .= ",countries.name as country_name,states.name as state_name";
            $queryData['leftJoin']['countries'] = "party_master.country_id = countries.id";
            $queryData['leftJoin']['states'] = "party_master.state_id = states.id";
        }

        if(!empty($data['party_category'])):
            $queryData['where_in']['party_master.party_category'] = $data['party_category'];
        endif;

		if(!empty($data['party_type'])):
            $queryData['where_in']['party_master.party_type'] = $data['party_type'];
        endif;

        if(!empty($data['id'])):
            $queryData['where']['party_master.id'] = $data['id'];
        endif;

        if(!empty($data['single_row'])):
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

	public function save($data){ 
		try {
			$this->db->trans_begin();

            if(!empty($data['party_category']) && $this->checkDuplicate(['party_category'=>$data['party_category'], 'party_name'=>$data['party_name'], 'party_phone'=>$data['party_phone'], 'id'=>$data['id']]) > 0) :
				$errorMessage['party_name'] = "Party name is duplicate.";
				return ['status' => 0, 'message' => $errorMessage];
            endif;
	
            $result = $this->store($this->partyMaster, $data, 'Party');

            // Save Record Party Activity 
            if(!empty($result['id'])):
                if(empty($data['id']) && $data['party_type'] == 2):
                    $this->savePartyActivity(['party_id'=>$result['id'],'lead_stage'=>1]);
                endif;
            endif;
			
           
			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Throwable $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}
	
	public function checkDuplicate($data){
        $queryData['tableName'] = $this->partyMaster;

        if(!empty($data['party_name'])):
            $queryData['where']['party_name'] = $data['party_name'];        
        endif;

		if(!empty($data['party_category'])):
            $queryData['where']['party_category'] = $data['party_category']; 
        endif;
		
		if(!empty($data['party_phone'])):
            $queryData['where']['party_phone'] = $data['party_phone']; 
        endif;

        if(!empty($data['party_type'])):
            $queryData['where']['party_type'] = $data['party_type']; 
        endif;
        
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];
        
        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function delete($id){
		try {
			$this->db->trans_begin();
            $checkData['columnName'] = ['party_id'];
			$checkData['ignoreTable'] = ['party_master','party_activities'];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);
            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Party is currently in use. you cannot delete it.'];
            endif;

			 $this->trash('party_activities', ['party_id' => $id], 'Party');
			$result = $this->trash($this->partyMaster, ['id' => $id], 'Party');

			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Throwable $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}
    /* End Party Data */

    /* Start Country And State Data */
    public function getCountries(){
		$queryData['tableName'] = $this->countries;
		$queryData['order_by']['name'] = "ASC";
		return $this->rows($queryData);
	}

    public function getStates($data=array()){
        $queryData['tableName'] = $this->states;
		$queryData['where']['country_id'] = $data['country_id'];
		$queryData['order_by']['name'] = "ASC";
		return $this->rows($queryData);
    }
    /* End Country And State Data */


    /********** Start Party Activity Detail **********/
    public function savePartyActivity($param){ 
        try{
            $activityNotes =Array();
            $activityNotes[1] = 'New Lead generated';
            $activityNotes[2] = 'New appointment scheduled';
            $activityNotes[3] = 'Status updated to Qualified';
            $activityNotes[4] = 'Ohh..No ! We Lost..😞';
            $activityNotes[5] = (!empty($param['notes']))?$param['notes']:"";
            $activityNotes[6] = 'Quotation Generated';
            $activityNotes[7] = 'Order Confirmed';
            $activityNotes[8] = 'Re-opened Lead';
			$activityNotes[9] = 'Client Visit';

            $this->db->trans_begin();

            $data = Array();
			if(!empty($param['lead_stage'])){
                $param['notes'] = $activityNotes[$param['lead_stage']];
			}
			
            if(empty($param['ref_date'])){ $param['ref_date'] = date('Y-m-d H:i:s'); }
            $param['id'] = (isset($param['id']))? $param['id']:"";

            if(!empty($param['lead_stage']) && $param['lead_stage'] == 8){ $param['lead_stage'] = 1;}

            $result = $this->store($this->partyActivities, $param, 'Party Activity');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getPartyActivity($data){
        $queryData = [];
        $queryData['tableName'] = $this->partyActivities;
        $queryData['select'] = "party_activities.id, party_activities.lead_stage, party_activities.party_id, party_activities.ref_id, party_activities.ref_date, IFNULL(party_activities.ref_no,'') as ref_no, party_activities.mode, party_activities.notes, IFNULL(party_activities.response,'') as response, party_activities.remark, (CASE WHEN party_activities.lead_stage = 13 THEN (SELECT voice_notes FROM visits WHERE id = party_activities.ref_id AND is_delete = 0) ELSE '' END) AS voice_notes, IFNULL(employee_master.emp_name,'') as created_by_name,party_activities.created_at, IFNULL(party_master.party_name,'') as party_name,emp.emp_name as executive,party_activities.created_by,party_master.party_type";
		
        $queryData['leftJoin']['employee_master'] = "employee_master.id = party_activities.created_by";
        $queryData['leftJoin']['party_master'] = "party_master.id = party_activities.party_id";
        $queryData['leftJoin']['employee_master emp'] = "emp.id = party_master.executive_id";

		
        if(!empty($data['party_id'])){ $queryData['where']['party_activities.party_id'] = $data['party_id']; }
        if(!empty($data['created_by'])){ $queryData['where']['party_activities.created_by'] = $data['created_by']; }
        if(!empty($data['lead_stage'])){ $queryData['where']['party_activities.lead_stage'] = $data['lead_stage']; }
        if(!empty($data['ref_date'])){ $queryData['where']['DATE(party_activities.ref_date)'] = $data['ref_date']; }
        if(!empty($data['customWhere'])){ $queryData['customWhere'][] = $data['customWhere']; }
		
		
		$queryData['group_by'][] = 'party_activities.id';
        $queryData['order_by']['party_activities.ref_date'] = 'ASC';
        $queryData['order_by']['party_activities.id'] = 'ASC';
		
        if(!empty($data['single_row'])):
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

    public function changeLeadStages($param){ 
        try{
            $this->db->trans_begin();
			$pa = $this->savePartyActivity(['party_id'=>$param['id'],'lead_stage'=>$param['lead_stage']]);

            if($param['lead_stage'] == 8){  //Re-Open Lead
                $param['party_type'] = 2; 
            }else{
                 $param['party_type'] =  $param['lead_stage'];
            }
            unset($param['lead_stage']);
            $result = $this->store($this->partyMaster, $param, 'Lead Stage');
			
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
    
    /********** End Party Activity Detail **********/


}
?>