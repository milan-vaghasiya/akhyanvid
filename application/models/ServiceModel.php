<?php
class ServiceModel extends MasterModel{
    private $serviceMaster = "service_master";
    private $serviceParts = "service_parts";

    public function getNextServiceNo($trans_prefix=""){
		$queryData = array(); $serviceData = new StdClass;
		if(!empty($trans_prefix))
		{
			$queryData['tableName'] = 'service_master';
			$queryData['select'] = "MAX(trans_no) as trans_no ";
			$queryData['where']['service_master.trans_prefix'] = $trans_prefix;
			$serviceData = $this->row($queryData);
		}
		$trans_no = ((!empty($serviceData->trans_no))? ($serviceData->trans_no + 1) : 1);
		return $trans_no;
    }

    public function getDTRows($data){
        $data['tableName'] = $this->serviceMaster;
        $data['select'] = "service_master.*,item_master.item_name,employee_master.emp_name,project_info.project_name,party_master.party_name";
        $data['leftJoin']['project_info'] = "project_info.id = service_master.project_id";
        $data['leftJoin']['party_master'] = "project_info.party_id = party_master.id";
        $data['leftJoin']['item_master'] = "item_master.id = service_master.ref_id";
        $data['leftJoin']['employee_master'] = "employee_master.id = service_master.technician_id";

        $data['where']['service_master.status'] = $data['status'];
        
        if($data['status'] == 2 AND in_array($this->userRole,[4])){ // Approved
			$data['customWhere'][] = "(service_master.technician_id = ".$this->loginId. " OR service_master.technician_id = 0)";
		}
        elseif(in_array($data['status'],[3,4,5,6]) AND !in_array($this->userRole,[-1,1])){ // Accepted Tech. & In Progress & Completed & Short Close
			$data['where']['service_master.technician_id'] = $this->loginId;
		}

		$data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "service_master.type"; 
        $data['searchCol'][] = "service_master.trans_number";
		$data['searchCol'][] = "DATE_FORMAT(service_master.trans_date,'%d-%m-%Y %H:%i:%s')";
        $data['searchCol'][] = "project_info.project_name";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "service_master.problem";
        $data['searchCol'][] = "employee_master.emp_name";
        $data['searchCol'][] = "DATE_FORMAT(service_master.start_date,'%d-%m-%Y %H:%i:%s')";
        $data['searchCol'][] = "DATE_FORMAT(service_master.complete_date,'%d-%m-%Y %H:%i:%s')";
        $data['searchCol'][] = "service_master.voice_notes";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

	public function getServiceList($data){
        $queryData['tableName'] = $this->serviceMaster;
        $queryData['select'] = "service_master.*,item_master.item_name,employee_master.emp_name as technician_name,project_info.project_name,party_master.party_name";
        $queryData['leftJoin']['project_info'] = "project_info.id = service_master.project_id";
        $queryData['leftJoin']['party_master'] = "project_info.party_id = party_master.id";
        $queryData['leftJoin']['item_master'] = "item_master.id = service_master.ref_id";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = service_master.technician_id";

        if($data['status'] == 2){ 
            $queryData['customWhere'][] = "(service_master.status = 2 OR service_master.status = 3) AND (service_master.technician_id = ".$this->loginId. " OR service_master.technician_id = 0)";
		}
        
        if(!empty($data['search'])){
            $queryData['like']['service_master.trans_number'] = $data['search'];
            $queryData['like']['project_info.project_name'] = $data['search'];
            $queryData['like']['party_master.party_name'] = $data['search'];
        }

        if(in_array($data['status'], [4,5,6])){ // In Progress, Completed, Hold
            $queryData['where']['service_master.status'] = $data['status'];
			$queryData['customWhere'][] = "(service_master.technician_id = ".$this->loginId. " OR service_master.technician_id = 0)";
		}

        if(isset($data['start']) && isset($data['length'])){
			$queryData['start'] = $data['start'];
			$queryData['length'] = $data['length'];
        }
        
        $queryData['order_by']['id'] = "DESC";
		
        return $this->rows($queryData);
    }
	
    public function getService($data){
        $queryData['tableName'] = $this->serviceMaster;
        $queryData['select'] = "service_master.*,party_master.party_name,employee_master.emp_name";
        $queryData['leftJoin']['party_master'] = "party_master.id = service_master.project";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = service_master.technician_id";
        $queryData['where']['service_master.id'] = $data['id'];
        return $this->row($queryData);
    }

    public function save($data){ 
        try{
            $this->db->trans_begin();//print_r($data);exit;
			if(empty($data['id'])):
				$data['trans_prefix'] = 'S'.n2y(date("Y")).n2m(date("m"));
				$data['trans_no'] = $this->getNextServiceNo($data['trans_prefix']);
				$data['trans_number'] = $data['trans_prefix'].lpad($data['trans_no'],3);
				
				if($this->checkDuplicate($data) > 0): 
					$errorMessage['trans_number'] = "Service No. is duplicate.";
					return ['status'=>0,'message'=>$errorMessage];
				endif;
			endif;
            
			$result = $this->store($this->serviceMaster,$data,'Service');
			
			$ref_id=0;
            if(empty($data['id']) && !empty($data['ref_id'])){
                $ref_id = $data['ref_id']; $complain_status = 3;
            }elseif(!empty($data['status']) AND $data['status'] == 5){
                $serviceData = $this->getService(['id'=>$data['id']]);
                $ref_id = (!empty($serviceData->ref_id) ? $serviceData->ref_id : 0);
                $complain_status = 2;
            }

            if(!empty($ref_id)):
                $this->edit('customer_complaint', ['id'=>$ref_id], ['status'=>$complain_status]); //Service request Add
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

    public function checkDuplicate($data){
        $queryData['tableName'] = $this->serviceMaster;
        if(!empty($data['trans_number'])){
            $queryData['where']['trans_number'] = $data['trans_number'];
        }
        if(!empty($data['id'])){
            $queryData['where']['id !='] = $data['id'];
        }
        $queryData['resultType'] = "numRows";
        return  $this->specificRow($queryData);
    }

    public function delete($id){
        try{
            $this->db->trans_begin();
			
			// $partResult = $this->trash($this->serviceParts,['service_id'=>$id],"Service Part");
			
			$data = [];
			$data['tableName'] = $this->serviceMaster;
			$data['where']['id'] = $id;
			$serviceData = $this->row($data);
			
			$filePath = realpath(APPPATH . '../assets/uploads/service/');
						
            $result = $this->trash($this->serviceMaster,['id'=>$id],'Service');

            if ($this->db->trans_status() !== FALSE):
				
				if(!empty($serviceData->bfr_images))
				{
					$b_files = explode(',',$serviceData->bfr_images);
					foreach($b_files as $file_name){ unlink($filePath.'/'.TRIM($file_name)); }
				}
				if(!empty($serviceData->aft_images))
				{
					$a_files = explode(',',$serviceData->aft_images);
					foreach($a_files as $file_name){ unlink($filePath.'/'.TRIM($file_name)); }
				}
				
				if(!empty($serviceData->ref_id)):
                    $this->edit('customer_complaint', ['id'=> $serviceData->ref_id], ['status'=>1]); //Service request Add
                endif;
				
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function changeOrderStatus($postData){ 
        try{
            $this->db->trans_begin();

            $result = $this->store($this->serviceMaster,$postData,'Service');
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

	public function approveService($data) { 
        try{
            $this->db->trans_begin();

            $date = ($data['is_approve'] == 1) ? date('Y-m-d H:i:s') : "";
            $isApprove =  ($data['is_approve'] == 1) ? $this->loginId : 0;
            $data['msg']  =  ($data['status'] == 1) ? 'Rejected' : 'Approved';
            
            $this->store($this->serviceMaster, ['id'=> $data['id'], 'status'=> $data['status'], 'technician_id'=> ($data['technician_id'] ?? 0),'approved_by ' => $isApprove, 'approved_at'=>$date]);
            $result = ['status' => 1, 'message' => 'Service ' . $data['msg'] . ' successfully.'];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	    
    }

    /* Accept Part Replace */
    public function getPartReplaceData($data){
        $queryData['tableName'] = $this->serviceParts;
        $queryData['select'] = "service_parts.*,item_master.item_name";
        $queryData['leftJoin']['item_master'] = "item_master.id = service_parts.item_id";
        $queryData['where']['service_id'] = $data['service_id'];
        return $this->rows($queryData);
    }

    public function savePartReplace($data){
        try{
            $this->db->trans_begin();
            
            $result = $this->store($this->serviceParts,$data,'Service');
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function deletePartReplace($id){
		try{
			$this->db->trans_begin();

			$result = $this->trash($this->serviceParts,['id'=>$id],"Service");

			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}

}
?>