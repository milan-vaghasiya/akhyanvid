<?php
class TaskManagerModel extends MasterModel{
    private $taskMaster = "task_master";
    private $task_steps = "task_steps";
    private $task_group = "task_group";
	private $repeatArr = ['Daily'=>'+1 Days','Weekly'=>'+7 Days','Monthly'=>'+1 Months','Yearly'=>'+12 Months'];

    public function getNextTaskNumber($task_prefix = ""){
        $data['tableName'] = $this->taskMaster;
        $data['select'] = "MAX(CAST(SUBSTRING(task_number, 3) AS UNSIGNED)) as task_number";
        $data['like']['task_number'] = $task_prefix;
        $taskData = $this->row($data);
        $task_number = (!empty($taskData->task_number) ? (intval($taskData->task_number) + 1) : 1);
        $task_number = n2y(date("Y")).n2m(date("m")).lpad($task_number,4);
        return $task_number;
    }

    public function getTaskDetail($data){
        $queryData['tableName'] = $this->taskMaster;
        $queryData['select'] = "task_master.*,employee_master.emp_name as assign_name,employee_master.emp_code as assign_code , task_group.group_name, task_group.label, assignBy.emp_name as assign_by_name, assignBy.emp_code as assign_by_code, IFNULL(tm.task_number,'') as ref_no";
		$queryData['select'] .= ",(if(task_master.task_file IS NULL, '', CONCAT('https://angel.nativebittechnologies.in/assets/uploads/task_file/',task_master.task_file))) as task_file";
        
		$queryData['leftJoin']['employee_master'] = "employee_master.id = task_master.assign_to";
		$queryData['leftJoin']['employee_master assignBy'] = "assignBy.id = task_master.created_by";
		$queryData['leftJoin']['task_master tm'] = "tm.id = task_master.ref_id";
        $queryData['leftJoin']['task_group'] = "task_group.id = task_master.group_id";
        $queryData['where']['task_master.status != '] = 3;
        $queryData['where']['task_master.id'] = $data['id'];
        return $this->row($queryData);
    }

    public function saveTask($data){
        try{
            $this->db->trans_begin();
			
			if(empty($data['id'])){
				$task_prefix = n2y(date("Y")).n2m(date("m"));
				$data['task_number'] = $this->getNextTaskNumber($task_prefix);
			}
            $result = $this->store($this->taskMaster, $data, 'Task');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function saveTaskStep($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->task_steps,$data);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }
    
    public function getTaskList($param=array()){   
        $queryData['tableName'] = $this->taskMaster;
		$queryData['select'] = "task_master.*, employee_master.emp_code as assign_code,employee_master.emp_name as assign_name, employee_master.emp_code as assign_code, task_group.group_name, task_group.label, assignBy.emp_name as assign_by_name, assignBy.emp_code as assign_by_code, IFNULL(tm.task_number,'') as ref_no";
		$queryData['select'] .= ",(if(task_master.task_file IS NULL, '', CONCAT('https://angel.nativebittechnologies.in/assets/uploads/task_file/',task_master.task_file))) as task_file";
		
		$queryData['leftJoin']['employee_master'] = "employee_master.id = task_master.assign_to";
		$queryData['leftJoin']['employee_master assignBy'] = "assignBy.id = task_master.created_by";
		$queryData['leftJoin']['task_master tm'] = "tm.id = task_master.ref_id";
        $queryData['leftJoin']['task_group'] = "task_group.id = task_master.group_id";		
		
		if(!empty($param['step_count'])):
			$queryData['select'] .= ",COUNT(ts.id) AS total_steps, COUNT(CASE WHEN ts.status=2 THEN 1 END) AS cmp_steps";
            $queryData['leftJoin']['task_steps ts'] = "ts.task_id = task_master.id AND ts.status != 3 AND ts.is_delete=0";
			$queryData['group_by'][''] = "task_master.id";
        endif;
				
        if(!empty($param['customWhere'])){
            $queryData['customWhere'][] = $param['customWhere']; 
        }

        if(!empty($param['status']) && $param['status'] != 'ALL'):
            $queryData['where']['task_master.status'] = $param['status'];
		elseif(empty($param['status'])):
			$queryData['customWhere'][] = "((task_master.status = 1) OR (task_master.status = 1 AND DATE(task_master.complete_on) >= CURDATE() - INTERVAL 2 DAY ))"; 
        endif;

        if(!empty($param['assign_to'])):
            $queryData['where']['task_master.assign_to'] = $param['assign_to'];  
        endif;

        if(!empty($param['created_by'])):
            $queryData['where']['task_master.created_by'] = $param['created_by']; 
        endif;

        if(isset($param['group_id'])):
			if($param['group_id'] > 0):
				$queryData['where']['task_master.group_id'] = $param['group_id']; 
			elseif(empty($param['group_id'])):
				$queryData['where']['task_master.assign_to'] = $this->loginId;	// Assigned To Me
			elseif($param['group_id'] == -1):
				$queryData['where']['task_master.created_by'] = $this->loginId;	// Assigned By Me
			endif;
        endif;

        if(!empty($param['limit'])):
            $queryData['limit'] = $param['limit']; 
        endif;

        if(isset($param['start'])):
            $queryData['start'] = $param['start'];
        endif;

        if(!empty($param['length'])):
            $queryData['length'] = $param['length'];
        endif;
		
        $queryData['order_by']['task_master.task_number'] = "ASC";

        $result = $this->rows($queryData);
		
		return $result;
    }

    public function countTasks($param=array()){    
        $queryData['tableName'] = $this->taskMaster;
        $queryData['select'] = "count(*) as task_count";
		
        if(!empty($param['customWhere'])):
            $queryData['customWhere'][] = $param['customWhere']; 
        endif;

        if(!empty($param['status'])):
            $queryData['where']['status'] = $param['status'];
		else:
			$queryData['where']['status != '] = 3;
        endif;

        if(!empty($param['assign_to'])):
            $queryData['where']['assign_to'] = $param['assign_to'];
        endif;

        if(!empty($param['created_by'])):
            $queryData['where']['created_by'] = $param['created_by'];
        endif;

        if(isset($param['group_id'])):
            $queryData['where']['group_id'] = $param['group_id'];
        endif;

        $result = $this->row($queryData);
		//$this->printQuery();
		return $result;
    }
	
    public function countMyTasks($param=array()){    
        $queryData['tableName'] = $this->taskMaster;
        $queryData['select'] = "count(*) as total_task";
        $queryData['select'] .= ', SUM(CASE WHEN task_master.created_by = '.$this->loginId.' THEN 1 ELSE 0 END) as assigned_by_me';
        $queryData['select'] .= ', SUM(CASE WHEN task_master.assign_to = '.$this->loginId.' THEN 1 ELSE 0 END) as assigned_to_me';
		
		$queryData['customWhere'][] = '(task_master.created_by = '.$this->loginId.' OR task_master.assign_to = '.$this->loginId.')'; 
		
        if(!empty($param['customWhere'])):
            $queryData['customWhere'][] = $param['customWhere']; 
        endif;

        if(!empty($param['status'])):
            $queryData['where']['status'] = $param['status'];
		else:
			$queryData['where']['status != '] = 3;
        endif;

        if(!empty($param['assign_to'])):
            $queryData['where']['task_master.assign_to'] = $param['assign_to'];
        endif;

        if(!empty($param['created_by'])):
            $queryData['where']['task_master.created_by'] = $param['created_by'];
        endif;

        if(isset($param['group_id'])):
            $queryData['where']['task_master.group_id'] = $param['group_id'];
        endif;

        $result = $this->row($queryData);
		return $result;
    }
	
    public function deleteTask($data){
        try {
            $this->db->trans_begin();

            $result = $this->edit($this->taskMaster,['id'=>$data['id'],'group_id' => $data['group_id']],['status'=>3]);
            $this->trash($this->taskMaster, ['id' => $data['id']], 'Task');
            $this->trash($this->task_steps,['task_id'=>$data['id']]);

            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return $result;
            endif;
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }

    public function changeTaskStatus($data){
        try {
            $this->db->trans_begin();
            if($data['task_level'] == 1){
				if($data['status'] == 2){
					$queryData = array();
					$queryData['tableName'] = $this->taskMaster;
					$queryData['where']['id'] = $data['id'];
					$taskData = $this->row($queryData);
				
					if(!empty($taskData->repeat_type)){
						$due_date = (!empty($taskData->due_date) ? date('Y-m-d H:i:s', strtotime($taskData->due_date.' '.$this->repeatArr[$taskData->repeat_type])) : date('Y-m-d H:i:s'));
						$remind_at = (!empty($taskData->remind_at) ? date('Y-m-d H:i:s', strtotime($taskData->remind_at.' '.$this->repeatArr[$taskData->repeat_type])) : NULL);
						$start_on = (!empty($taskData->start_on) ? date('Y-m-d H:i:s', strtotime($taskData->start_on.' '.$this->repeatArr[$taskData->repeat_type])) : NULL);
					
						$repeatData = (array)$taskData;
						$repeatData['due_date'] = $due_date;
						$repeatData['remind_at'] = $remind_at;
						$repeatData['start_on'] = $start_on;
						$repeatData['id'] = '';
						
						$taskResult = $this->saveTask($repeatData);
						
						$queryData = array();
						$queryData['tableName'] = $this->task_steps;
						$queryData['where']['task_id'] = $data['id'];
						$stepsList = $this->rows($queryData);
						
						foreach($stepsList as $step){
							$stepData = (array)$step;
							$stepData['id'] = '';
							$stepData['task_id'] = $taskResult['insert_id'];
							$stepData['status'] = 1;
							$this->saveTaskStep($stepData);
						}
					}
				}
				
                $result = $this->store($this->taskMaster,['id'=>$data['id'],'status'=>$data['status'], 'complete_on'=>date('Y-m-d H:i:s')]); 
            }else{
                $result = $this->store($this->task_steps,['id'=>$data['id'],'status'=>$data['status'], 'complete_on'=>date('Y-m-d H:i:s')]); 
            }
               
            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return ['status' => 1, 'message' => "Task Status Updated Successfully."];
            endif;
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }

    public function getTaskSteps($param){
        $queryData['tableName'] = $this->task_steps;
        $queryData['select'] = "task_steps.*";
        $queryData['where']['task_steps.task_id'] = $param['task_id'];
        if(!empty($param['status'])):
            $queryData['where']['status'] = $param['status'];
		else:
			$queryData['where']['task_steps.status != '] = 3;
        endif;
        return $this->rows($queryData);
    }

	/********* GROUP MASTER *********/
	
	public function getMemberList($postData = array()){ 
        $queryData = array();
        $queryData['tableName'] = "employee_master";
        $queryData['select'] = "employee_master.id,employee_master.emp_code,employee_master.emp_name";
        
        if(isset($postData['is_active'])):
            $queryData['where_in']['employee_master.is_active'] = $postData['is_active'];
        else:
            $queryData['where']['employee_master.is_active'] = 1;
        endif;
		
		$queryData['customWhere'][] = "employee_master.emp_role NOT IN (-1)";
		
        if(isset($postData['ignore_emp'])){ $queryData['where_not_in']['employee_master.id'] = $postData['ignore_emp']; }
		
        if(!empty($postData['group_id']) && $postData['group_id'] != 'ALL'):
			//if(!in_array($this->userRole,[1,-1])):
				$queryData['join']['task_group'] = "find_in_set(employee_master.id, task_group.member_ids) > 0 AND task_group.id = ".$postData['group_id'];
			//endif;
		else:
			$queryData['join']['sub_menu_permission'] = "sub_menu_permission.emp_id = employee_master.id AND sub_menu_permission.is_delete = 0 AND sub_menu_permission.is_read = 1 AND sub_menu_permission.is_write = 1 AND sub_menu_permission.sub_menu_id = 271";
        endif;
		
		$result = $this->rows($queryData);
		return $result;
    }

    public function getGroupList($param=array()){    
        $queryData['tableName'] = $this->task_group;
        $queryData['select'] = "task_group.*";
		
		if(!empty($param['task_count'])):
			$queryData['select'] .= ",IFNULL(tl.task_count,0) as task_count";
			$queryData['leftJoin']['(SELECT COUNT(id) as task_count,group_id FROM task_master WHERE is_delete=0 AND ((status = 1) OR (status = 1 AND DATE(complete_on) >= CURDATE() - INTERVAL 2 DAY )) GROUP BY group_id) tl'] = "tl.group_id = task_group.id";
			$queryData['group_by'][''] = "task_group.id"; 
        endif;
		
        if(!empty($param['customWhere'])):
            $queryData['customWhere'][] = $param['customWhere']; 
        endif;
		
        if(!empty($param['status'])):
            $queryData['where']['status'] = $param['status']; 
        endif;

        if(!empty($param['limit'])):
            $queryData['limit'] = $param['limit']; 
        endif;

        if(isset($param['start'])):
            $queryData['start'] = $param['start'];
        endif;

        if(!empty($param['length'])):
            $queryData['length'] = $param['length'];
        endif;
		
		if(!in_array($this->userRole,[1,-1])):
			$queryData['customWhere'][] = '(FIND_IN_SET("'.$this->loginId.'", task_group.member_ids) > 0 OR task_group.member_ids IS NULL)';
        endif;
		
        $queryData['order_by']['task_group.label'] = "ASC";         
        $queryData['order_by']['task_group.group_name'] = "ASC";
		
		if(!empty($param['id'])):
            $queryData['where']['id'] = $param['id']; 
			$param['resultType'] = 'row';
        endif;
		
		if(!empty($param['resultType']) && $param['resultType'] == 'row'){
			$result = $this->row($queryData);	
		}else{
			$result = $this->rows($queryData);
		}
        return $result;
    }

    public function saveGroup($data){
        try{
            $this->db->trans_begin();
			
			if($this->checkDuplicateGroup($data['group_name'],$data['id']) > 0):
                $errorMessage['group_name'] = "Group Name is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            else:
				$result = $this->store($this->task_group, $data, 'Group');
			endif;
            

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	
	public function checkDuplicateGroup($group_name,$id=""){
        $data['tableName'] = $this->task_group;
        $data['where']['group_name'] = $group_name;
        if(!empty($id))
            $data['where']['id !='] = $id;
            
        $queryData['resultType'] = "numRows";
        return $this->specificRow($data);
 
    }
    
	public function labelSearch(){
		$data['tableName'] = $this->task_group;
		$data['select'] = 'label';
		$data['group_by'][] = 'label';
		$result = $this->rows($data);
		$searchResult = array();
		foreach ($result as $row) {
			$searchResult[] = $row->label;
		}
		return  $searchResult;
	}

}
?>