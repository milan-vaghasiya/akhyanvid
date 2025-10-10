<?php
class TaskManager extends MY_ApiController{

	public function __construct(){
		parent::__construct();
        $this->data['headData']->pageTitle = "Task Manager";
        $this->data['headData']->pageUrl = "api/taskManager";
        $this->data['headData']->base_url = base_url();
	}
	
	public function getTaskList(){
        $postData = $this->input->post();
		if(empty($postData['group_id']))
		{
			$postData['assign_to'] = $this->loginId;
			unset($postData['group_id']);
		}
		$postData['step_count']=1;
		$this->data['taskList'] = $this->taskManager->getTaskList($postData);
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data['taskList']]);
    }
	
	public function getTaskDetail(){
        $postData = $this->input->post();
		$this->data['taskDetail'] = $this->taskManager->getTaskDetail($postData);
		if($this->data['taskDetail']):
			$this->data['taskDetail']->steps = $this->taskManager->getTaskSteps(['task_id'=>$postData['id']]);
		endif;
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data['taskDetail']]);
    }
	
    public function getMemberList(){       
        $postData = $this->input->post();
		$this->data['empData'] = $this->taskManager->getMemberList($postData);
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function saveTask(){
        $data = $this->input->post();
		$errorMessage = array();

		if(empty($data['task_title'])){ $errorMessage['task_title'] = "Task Title is required."; }
        if(empty($data['due_date'])){ $errorMessage['due_date'] = "Due Date is required."; }else{ $data['due_date'] = date('Y-m-d', strtotime($data['due_date'])); }
        if(empty($data['remind_at'])){ unset($data['remind_at']); }else{ $data['remind_at'] = date('Y-m-d H:i:s', strtotime($data['remind_at'])); }
        if(empty($data['start_on'])){ unset($data['start_on']); }else{ $data['start_on'] = date('Y-m-d', strtotime($data['start_on'])); }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(isset($_FILES['task_file']['name']) AND (!empty($_FILES['task_file']['name']) || $_FILES['task_file']['name'] != null)):
                $this->load->library('upload');
				$_FILES['userfile']['name']     = $_FILES['task_file']['name'];
				$_FILES['userfile']['type']     = $_FILES['task_file']['type'];
				$_FILES['userfile']['tmp_name'] = $_FILES['task_file']['tmp_name'];
				$_FILES['userfile']['error']    = $_FILES['task_file']['error'];
				$_FILES['userfile']['size']     = $_FILES['task_file']['size'];
				
				$imagePath = realpath(APPPATH . '../assets/uploads/task_file/');
				$config = ['file_name' => time()."_order_item_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];

				$this->upload->initialize($config);
				if (!$this->upload->do_upload()):
					$errorMessage['task_file'] = $this->upload->display_errors();
					$this->printJson(["status"=>0,"message"=>$errorMessage]);
				else:
					$uploadData = $this->upload->data();
					$data['task_file'] = $uploadData['file_name'];
				endif;
			endif;
			
            $data['created_by'] = $this->loginId;
            $data['created_at'] = date('Y-m-d H:i:s');
            $result = $this->taskManager->saveTask($data);
            $this->printJson($result);
        endif;
    }
	
    public function saveTaskStep(){
        $postData = $this->input->post();
		
		$errorMessage = array();

        if(empty($postData['task_id'])){ $errorMessage['task_id'] = "Task is required."; }
        if(empty($postData['steps'])){ $errorMessage['step_note'] = "Notes is required."; }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
		
			$stepData = [];$result = ['status'=>0,'message'=>"Somthing went Wrong...!"];
			if(!empty($postData['steps']) && gettype($postData['steps']) == "string"): $stepData = json_decode($postData['steps'],true); endif;
			if(!empty($stepData))
			{
				$data = [];
				foreach ($stepData as $row) {
					$data['id'] = (!empty($row['id']) ? $row['id'] : "");
					$data['step_note'] = $row['step_note'];
					$data['task_id'] = $postData['task_id'];
					$result = $this->taskManager->saveTaskStep($data);
				}
				$this->printJson($result);
			}
			else{
				$errorMessage['step_note'] = "Notes is required.";
				$this->printJson(['status'=>0,'message'=>$errorMessage]);
			}
            
        endif;
    }
	
	public function getGroupList(){
        $postData = $this->input->post();
		$postData['task_count']=1;
		$this->data['groupList'] = $this->taskManager->getGroupList($postData);
		
		$postData['assign_to'] = $this->loginId;
		$this->data['assigned_me'] = $this->taskManager->countTasks($postData);
		
		$this->data['labelList'] = [];
		if(!empty($this->data['groupList']))
		{
			$labels = [];
			foreach($this->data['groupList'] as $row)
			{
				$labels[] = $row->label;
			}
			$this->data['labelList'] = array_unique($labels);
		}
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }
	
    public function saveGroup(){
        $data = $this->input->post();
		$errorMessage = array();

        if(empty($data['group_name'])){ $errorMessage['trans_date'] = "Group Name is required."; }
        if(empty($data['label'])){ $data['label'] = "General"; }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $result = $this->taskManager->saveGroup($data);
            $this->printJson($result);
        endif;
    }

	public function changeTaskStatus(){
        $data = $this->input->post();
        $result = $this->taskManager->changeTaskStatus($data);
        /* $taskData= $this->taskManager->getTask($data);
        $result['task_date'] = $taskData->trans_date; */
        $this->printJson($result);
    }
}
?>