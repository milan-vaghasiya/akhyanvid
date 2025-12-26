<?php
class CustomerComplaintsModel extends MasterModel{
    private $customerComplaint = "customer_complaint";

   public function getDTRows($data){
        $data['tableName'] = $this->customerComplaint;
        $data['select'] = "customer_complaint.*,project_info.project_name,";
        $data['leftJoin']['project_info'] = "customer_complaint.project_id = project_info.id";
        $data['where']['customer_complaint.status'] = $data['status'];

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "DATE_FORMAT(customer_complaint.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "project_info.project_name";
        $data['searchCol'][] = "customer_complaint.remark";
        $data['searchCol'][] = "customer_complaint.voice_note";
		
        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

   public function getCustomerComplaints($data){
        $queryData['tableName'] = $this->customerComplaint;
        $queryData['where']['id'] = $data['id'];
        return $this->row($queryData);
    }

	public function save($data){   
        try{
            $this->db->trans_begin();
            if($data['status'] == 2){
                $data['solution_by'] = $this->loginId;
                $data['solution_at'] = date('Y-m-d H:i:s');
            }
            $result = $this->store($this->customerComplaint,$data);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
             return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
	}

    public function delete($data){
        try{
            $this->db->trans_begin();
        
            $result = $this->trash($this->customerComplaint,['id'=>$data['id']]);

            if (!empty($data['complaint_file'])) {
                $custFiles = explode(',',$data['complaint_file']);
                foreach($custFiles as $key=>$val):
                    $old_file_path = FCPATH."assets/uploads/service/" . $val;
                    if (file_exists($old_file_path)) {
                        unlink($old_file_path);
                    }
                endforeach;
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

    public function getCustomerComplaintsData($data){
        $queryData = array();
        $queryData['tableName'] = $this->customerComplaint;
        $queryData['select'] = "customer_complaint.*,project_info.project_name,";
        $queryData['leftJoin']['project_info'] = "customer_complaint.project_id = project_info.id";
        $queryData['where']['customer_complaint.status'] = $data['status'];

        if(!empty($data['id'])){
            $queryData['where']['customer_complaint.id'] = $data['id'];
        }
        
        $result = $this->rows($queryData);
        return $result;
    }

}