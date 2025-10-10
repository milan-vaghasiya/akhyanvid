<?php
class EmployeeModel extends MasterModel{
    private $empMaster = "employee_master";

	public function getDTRows($data){
        $data['tableName'] = $this->empMaster;
        $data['select'] = "employee_master.*,";
		
        if(!empty($data['emp_role'])):
            $data['where']['employee_master.emp_role'] = $data['emp_role'];
        else:
            $data['where_not_in']['employee_master.emp_role'] = [-1];
        endif;

		if($data['status'] == 0):
            $data['where']['employee_master.is_active'] = 1;
        else:
            $data['where']['employee_master.is_active'] = 0;
        endif;
        
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "employee_master.emp_code";
        $data['searchCol'][] = "employee_master.emp_name";
        $data['searchCol'][] = "employee_master.emp_mobile_no";
        $data['searchCol'][] = "employee_master.emp_role";
        
        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }
    
	public function getEmployeeList($data=array()){
        $queryData['tableName'] = $this->empMaster;
        $queryData['select'] = "employee_master.*";
        if(empty($data['appReport'])):
            $empRole = [-1];
        endif;

        if(!empty($data['not_role'])):
            $empRole = $data['not_role'];
        endif;

        if(!empty($data['emp_id'])):
            $queryData['where_in']['employee_master.id'] = $data['emp_id'];
        endif;

        if(!empty($data['emp_role'])):
            $queryData['where_in']['employee_master.emp_role'] = $data['emp_role'];
        endif;

        if(!empty($data['is_active'])):
            $queryData['where_in']['employee_master.is_active'] = $data['is_active'];
        endif;

        if(empty($data['all']) && empty($data['employee_master.emp_role'])):
            $queryData['where_not_in']['employee_master.emp_role'] = $empRole;
        endif;

        if(!empty($data['id'])):
            $queryData['where']['employee_master.id'] = $data['id'];
        endif;

        if(!empty($data['single_row'])):
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

	
    public function save($data){
        try{
            $this->db->trans_begin();

            if($this->checkDuplicate(['emp_mobile_no'=>$data['emp_mobile_no'],'id'=>$data['id']]) > 0):
                $errorMessage['emp_mobile_no'] = "Mobile no. is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;

            if($this->checkDuplicate(['emp_code'=>$data['emp_code'],'id'=>$data['id']]) > 0):
                $errorMessage['emp_code'] = "Emp Code is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;

            if(empty($data['id'])):
                $data['emp_psc'] = $data['emp_password'];
                $data['emp_password'] = md5($data['emp_password']);
            endif;

            $result =  $this->store($this->empMaster,$data,'Employee');
            
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
        $queryData['tableName'] = $this->empMaster;

        if(!empty($data['emp_code']))
           $queryData['where']['emp_code'] = $data['emp_code'];

        if(!empty($data['emp_mobile_no']))
            $queryData['where']['emp_mobile_no'] = $data['emp_mobile_no'];
        
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];
        
        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = ['created_by','updated_by','emp_id'];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);

            if($checkUsed == true):
                return ['status'=>0,'message'=>'The employee is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->empMaster,['id'=>$id],'Employee');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function activeInactive($postData){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->empMaster,$postData,'');
            $result['message'] = "Employee ".(($postData['is_active'] == 1)?"Activated":"De-activated")." successfully.";
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function changePassword($data){
        try{
            $this->db->trans_begin();

            if(empty($data['id'])):
                return ['status'=>2,'message'=>'Somthing went wrong...Please try again.'];
            endif;

            $empData = $this->getEmployeeList(['id'=>$data['id'],'single_row'=>1]);
            if(md5($data['old_password']) != $empData->emp_password):
                return ['status'=>0,'message'=>['old_password'=>"Old password not match."]];
            endif;

            if(md5($data['new_password']) == $empData->emp_password):
                return ['status'=>0,'message'=>['new_password'=>"The new password cannot be the same as the old password. Please choose a different password."]];
            endif;

            $postData = ['id'=>$data['id'],'emp_password'=>md5($data['new_password']),'emp_psc'=>$data['new_password']];
            $result = $this->store($this->empMaster,$postData);
            $result['message'] = "Password changed successfully.";

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function resetPassword($id){
        try{
            $this->db->trans_begin();

            $data['id'] = $id;
            $data['emp_psc'] = '123456';
            $data['emp_password'] = md5($data['emp_psc']); 
            
            $result = $this->store($this->empMaster,$data);
            $result['message'] = 'Password Reset successfully.';

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
	}
}
?>