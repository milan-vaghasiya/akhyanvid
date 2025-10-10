<?php
class ProjectModel extends MasterModel{
    private $project_info = "project_info";
    private $project_agency = "project_agency";
    private $project_spec = "project_spec";
    private $payment_trans = "payment_trans";


 	public function getDTRows($data){
        $data['tableName'] = $this->project_info;
        $data['select'] = "project_info.*,party_master.party_name";

        $data['leftJoin']['party_master'] = "party_master.id = project_info.party_id";
		
        if(!empty($data['trans_status'])):
            $data['where']['project_info.trans_status'] = $data['trans_status'];
		endif;
        $data['order_by']['project_info.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "project_info.project_name";
        $data['searchCol'][] = "project_info.project_type";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "project_info.location";
        $data['searchCol'][] = "project_info.other_info";
        $data['searchCol'][] = "project_info.sq_no";
		$data['searchCol'][] = "project_info.amc";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();
           
                $result = $this->store($this->project_info, $data, 'Project Info');

                if(!empty($data['sq_no'])):
                    $this->edit('sq_master', ['trans_number'=> $data['sq_no']], ['project_id' => $result['id']]);
                endif;

                if(empty($data['id'])):
                    $partyData = $this->party->getPartyList(['id'=>$data['party_id'] ,'single_row' =>1]);
                    if($partyData->party_type == 2 && empty($data['id'])):

                        $this->party->savePartyActivity(['party_id'=>$data['party_id'],'lead_stage'=>7,'ref_date'=>date('Y-m-d')." ".date("H:i:s"),'ref_no'=>$data['sq_no'],'ref_id'=>$result['id']]);

                        $this->edit('party_master', ['id'=> $data['party_id']], ['party_type' => 1]);
                    endif;
                endif;

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
		
    public function getProjectData($data=[]){
        $queryData = array();
        $queryData['tableName'] = $this->project_info;
        $queryData['select'] = "project_info.*,party_master.party_name,party_master.party_code,party_master.contact_person,party_master.party_phone,party_master.party_email";
        $queryData['leftJoin']['party_master'] = "party_master.id = project_info.party_id";

        if(!empty($data['id'])){
            $queryData['where']['project_info.id'] = $data['id'];
        }
        if(!empty($data['trans_status'])){
            $queryData['where']['project_info.trans_status'] = $data['trans_status'];
        }
        if(!empty($data['search'])){
            $queryData['like']['project_info.project_name'] = $data['search'];
            $queryData['like']['project_info.project_type'] = $data['search'];
            $queryData['like']['project_info.location'] = $data['search'];
            $queryData['like']['party_master.party_name'] = $data['search'];
        }
		if(isset($data['start']) && isset($data['length'])){
			$queryData['start'] = $data['start'];
			$queryData['length'] = $data['length'];
        }
        
        $queryData['order_by']['id'] = "DESC";

        if(!empty($data['single_row'])):
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

    public function delete($data){
        try{
            $this->db->trans_begin();

            $projectData = $this->getProjectData(['id'=>$data['id'],'single_row'=>1]);
            if(!empty($projectData->sq_no)):
                $setData = array();
                $setData['tableName'] = 'sq_master';
                $setData['where']['trans_number'] = $projectData->sq_no;
                $setData['update']['project_id'] = 0;
                $this->setValue($setData);
            endif;

            $this->trash('party_activities',['ref_id'=>$data['id'],'lead_stage'=>'7']); 
            $result = $this->trash($this->project_info,['id'=>$data['id']],'Project');

            if(!empty($projectData->drawing_file))
			{
				$filePath = realpath(APPPATH . '../assets/uploads/project/'.$projectData->drawing_file);
				unlink($filePath);
			}
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function changeProjectStatus($data) { 
        try{
            $this->db->trans_begin();
            $this->edit($this->project_info, ['id'=> $data['id']], ['trans_status' => $data['trans_status'],'start_date'=>$data['start_date']]);

            $result = ['status' => 1, 'message' => 'Project '.$data['msg'].' Successfully.'];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	    
    }

    /* Start Project Specification */
    public function getProjectSpecData($data){
        $queryData = array();
        $queryData['tableName'] = $this->project_spec;
        $queryData['select'] = "project_spec.*,";

        if(!empty($data['id'])){
            $queryData['where']['project_spec.id'] = $data['id'];
        }

         if(!empty($data['project_id'])){
            $queryData['where']['project_spec.project_id'] = $data['project_id'];
        }
        
        if(!empty($data['single_row'])):
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

    public function saveSpecification($data){
		try{
            $this->db->trans_begin();

            foreach ($data['specData'] as $key => $value) { 
                $spec_desc = str_replace('_', ' ', $this->specArray[$key]);

                $specifications = [
                    'id'=> $data['id'][$key],
                    'project_id' => $data['project_id'],
                    'specification' => $spec_desc,    
                    'spec_desc' => $value  
                ];
                $result = $this->store($this->project_spec,$specifications,'Project Specification');
            }
			
			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
		   return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}
    /* End Project Specification */


    /* Project Agency Data Start */
    public function getProjectAgencyData($data){
        $queryData = array();
        $queryData['tableName'] = $this->project_agency;
        $queryData['select'] = "project_agency.*,";

        if(!empty($data['id'])){
            $queryData['where']['project_agency.id'] = $data['id'];
        }

         if(!empty($data['project_id'])){
            $queryData['where']['project_agency.project_id'] = $data['project_id'];
        }
        
        if(!empty($data['single_row'])):
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

    public function saveAgency($data){
		try{
            $this->db->trans_begin();    

            $result = $this->store($this->project_agency,$data,'Project Agency');
			
			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
		   return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}

    public function deleteAgency($id){
        try{
			$this->db->trans_begin();

			$result = $this->trash($this->project_agency,['id'=>$id],'Project Agency');

			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}
    /* Project Agency Data End*/

    /* Incharge Data*/
    public function saveIncharge($data){
		try{
            $this->db->trans_begin();

            $result = $this->store($this->project_info, $data, 'Project');

            if($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
        }catch(\Exception $e){
            return ['status'=>0,'error'=>$e->getMessage()];
        }
	}

     /* Project Payment Data Start*/
    public function getPaymentData($data){
        $queryData = array();
        $queryData['tableName'] = $this->payment_trans;
        $queryData['select'] = "payment_trans.*,";

        if(!empty($data['id'])){
            $queryData['where']['payment_trans.id'] = $data['id'];
        }

         if(!empty($data['project_id'])){
            $queryData['where']['payment_trans.project_id'] = $data['project_id'];
        }
        
        if(!empty($data['single_row'])):
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

    public function savePayment($data){
		try{
            $this->db->trans_begin();
        
            $result = $this->store($this->payment_trans,$data,'Payment');
			
			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
		   return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}

    public function deletePayment($id){
        try{
			$this->db->trans_begin();

			$result = $this->trash($this->payment_trans,['id'=>$id],'Payment');

			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}
    /* Project Payment Data End */

    /*Work Plan DATA */
    public function saveWorkPlan($data){ 
        try{
            $this->db->trans_begin();
            foreach($data['work_id'] as $key=>$value){
                if(!empty($data['work_step'][$key])):
                    $workData =[
                        'id'=>$data['id'][$key],
                        'project_id'=>$data['project_id'],
                        'work_id'=>$value,
                        'work_step'=>$data['work_step'][$key],
                    ];
                    $result = $this->store('work_progress', $workData, 'work progress');
                endif;
            }

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

	public function getTotalService($data){
        $queryData = array();
        $queryData['tableName'] = 'service_master';
        $queryData['select'] = "id";

        $queryData['where']['service_type'] = $data['service_type'];
        $queryData['where']['project_id'] = $data['project_id'];
        $queryData['resultType'] = "numRows";

        $result = $this->specificRow($queryData);

        return $result;
    }
}
?>