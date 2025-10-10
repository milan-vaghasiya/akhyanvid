<?php
class TaskManager extends MY_Controller{
    private $index = "task_manager/index";
    private $form = "task_manager/form";
    private $group_form = "task_manager/group_form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Task Manager";
		$this->data['headData']->controller = "taskManager";
        $this->data['headData']->pageUrl = "taskManager";
	}

    public function index(){
        $this->data['rec_per_page'] = 25; // Records Per Page
        $this->load->view("task_manager/index",$this->data);
    }
	
    public function getTaskDetail(){
        $postData = $this->input->post();
        $taskDetail = $this->taskManager->getTaskDetail($postData);
		$taskView = '';
		if(!empty($taskDetail)){
			$priority = ((!empty($taskDetail->priority) AND $taskDetail->priority == 'High') ? '<i class="text-danger mdi mdi-checkbox-blank-circle"></i> High' : 'N/A');
			
			$ds=$hd="";
			if(!in_array($this->loginId,[$taskDetail->created_by,$taskDetail->assign_to])){ $ds="disabled"; $hd="hidden"; }
			$stepsList = '';
			$steps = $this->taskManager->getTaskSteps(['task_id'=>$postData['id']]);
			if(!empty($steps))
			{
				foreach($steps as $row)
				{
					$changeStatus = (($row->status == 1) ? "2": "1");
					$lineThrough = (($row->status == 2) ? "strikeout": "");
					$checked = (($row->status == 2) ? "checked": "");
					$stepsList .='<li class="list-group-item todo-item border-bottom" data-role="task">
										<div class="row" style="margin:0px;align-items: center;">
											<div class="custom-control custom-checkbox p-0 col" style="min-width: 88%;width: 88%;">
												<input type="radio" name="id_'.$row->id.'" id="id_'.$row->id.'" class="filled-in chk-col-success changeStepStatus" data-status="'.$changeStatus.'" data-id="'.$row->id.'" data-task_id="'.$row->task_id.'" value="" '.$checked.'>
												<label class="custom-control-label todo-label d-block text-left " style="text-overflow: ellipsis;white-space: nowrap;overflow: hidden;" for="id_'.$row->id.'" >
													<div class="todo-desc '.$lineThrough.' ">'.$row->step_note.'</div>
												</label>
											</div>
											<div class="col text-center" style="width: 12%;">
												<strong class="float-right '.$hd.'"><a type="button" class="changeStepStatus" data-status="3" data-id="'.$row->id.'"  data-task_id="'.$row->task_id.'" href="javascript:void(0)" '.$ds.'><i class="text-danger fs-22 mdi mdi-delete-forever"></i></a></strong>
											</div>
										<div>
									</li>';
				}
			}
			$taskView .= '<div class="p-20 border-bottom">
							<div class="d-flex align-items-center">
								<div>
									<h4>'.(!empty($taskDetail->task_title) ? $taskDetail->task_title : '').'</h4>
									<span><b>Cteated By:</b> '.(!empty($taskDetail->assign_by_name) ? $taskDetail->assign_by_code.' '.$taskDetail->assign_by_name : '').'</span>
								</div>
								<div class="ml-auto">
									<button id="cancel_compose" onclick="getTasklist('.$taskDetail->group_id.')" class="btn btn-dark">Back</button>
								</div>
							</div>
						</div>
						<div class="row" style="margin:0px;">
							<div class="col-8 border-right">
								<div class="card-body border-bottom">
									<h4 class="m-b-15">Description</h4>
									<p>'.(!empty($taskDetail->notes) ? $taskDetail->notes : '').'</p>
								</div>
								<div class="row text-center m-t-40">
									<div class="col-4 border-right">
										<h4 class="font-bold">'.(!empty($taskDetail->group_name) ? $taskDetail->group_name : 'Individual').'</h4>
										<h6>Group</h6>
									</div>
									<div class="col-4 border-right">
										<h4 class="font-bold">'.(!empty($taskDetail->assign_name) ? $taskDetail->assign_code.' <small>'.$taskDetail->assign_name.'</small>' : '').'</h4>
										<h6>Assigned To</h6>
									</div>
									<div class="col-4">
										<h4 class="font-bold">'.formatDate($taskDetail->due_date,'j, M Y').'</h4>
										<h6>Due On</h6>
									</div>
								</div>
								<div class="row text-center m-t-40">
									<div class="col-4 border-right">
										<h4 class="font-bold">'.(!empty($taskDetail->remind_at) ? formatDate($taskDetail->remind_at,'j, M Y') : 'N/A').'</h4>
										<h6>Remind On</h6>
									</div>
									<div class="col-4 border-right">
										<h4 class="font-bold">'.(!empty($taskDetail->repeat_type) ? $taskDetail->repeat_type : 'N/A').'</h4>
										<h6>Repeat On</h6>
									</div>
									<div class="col-4">
										<h4 class="font-bold">'.$priority.'</h4>
										<h6>Priority</h6>
									</div>
								</div>
							</div>
							<div class="col-4">
								<div class="card">
									<div class="card-body custom-form" style="padding:0.5rem;">
										<div class="form-group">
											<input type="text" class="form-control lbl_anm" name="step_note" id="step_note" data-task_id="'.$taskDetail->id.'" '.$hd.' />
											<label for="step_note" class="animated-label" '.$hd.'>Step Note...</label>
										</div>
										<div class="todo-widget scrollable" style="max-height: calc(100vh - 250px);overflow-y:auto;">
											<ul class="list-task todo-list list-group m-b-0 stepList" >'.$stepsList.'</ul>
										</div>
									</div>
								</div>
							</div>
						</div>';
		}
		
		$this->printJson(['taskView'=>$taskView]);
    }

    public function getTaskList(){
        $postData = $this->input->post();
		
		if($postData['group_id'] > 0){
			$groupHead = '<h4>'.$postData['group_name'].' 
					<span class="text-success pl-2 permission-approve addNewGroup" data-button="both" data-group_id="'.$postData['group_id'].'" data-modal_id="modal-md" data-function="addGroup" data-controller="taskManager" data-form_title="Add Group" data-fnsave="saveGroup"><i class="fa fa-edit"></i></span>
			</h4> <span>Here is the list of Task</span>';
		}
		elseif($postData['group_id'] == 0){ $groupHead = '<h4> Assigned To Me </h4> <span>Here is the list of Task</span>'; }
		elseif($postData['group_id'] == -1){ $groupHead = '<h4> Assigned By Me </h4> <span>Here is the list of Task</span>'; }
		
		$postData['step_count']=1;
        $this->data['taskList'] = $taskdata = $this->taskManager->getTaskList($postData);
		
		$this->data['postData'] = $postData;
        $taskList = $this->load->view('task_manager/task_list',$this->data,true);
        
		$this->printJson(['taskList'=>$taskList,'groupHead'=>$groupHead]);
    }

    public function addTask(){
        $data = $this->input->post();
        $this->data['ref_id'] = (!empty($data['id']) ?$data['id'] : 0);
		$this->data['groupList'] = $this->taskManager->getGroupList();
        $this->load->view($this->form, $this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();

        if(empty($data['due_date'])){ $errorMessage['due_date'] = "Due Date is required."; }
        if(empty($data['task_title'])){ $errorMessage['task_title'] = "Task Title is required."; }
		if(empty($data['remind_at'])){ unset($data['remind_at']); }
        if(empty($data['start_on'])){ unset($data['start_on']); }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if($_FILES['task_file']['name'] != null || !empty($_FILES['task_file']['name'])):
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

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->taskManager->getTaskDetail($data);
		$this->data['groupList'] = $this->taskManager->getGroupList(); 
        $this->load->view($this->form, $this->data);
    }

    public function delete(){
        $data = $this->input->post();  
        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->taskManager->deleteTask($data));
        endif;
    }

    public function changeTaskStatus(){
        $postData = $this->input->post();

		$postData['task_level'] = 1;
        $result = $this->taskManager->changeTaskStatus($postData);
        $this->data['taskList'] = $this->taskManager->getTaskList(['group_id'=>$postData['group_id'],'step_count'=>1]);
		$this->data['postData'] = $postData;
        $taskList = $this->load->view('task_manager/task_list',$this->data,true);
		if(!empty($taskList)){
			$this->printJson(['status'=>1,'list'=>$taskList]);
		}
		else{
			$this->printJson(['status'=>0,'list'=>$taskList]);
		}
    }

    public function saveTaskStep(){
        $postData = $this->input->post();
		
		$errorMessage = array();

        if(empty($postData['task_id'])){ $errorMessage['task_id'] = "Task is required."; }
        if(empty($postData['step_note'])){ $errorMessage['step_note'] = "Notes is required."; }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$postData['id'] = (!empty($postData['id']) ? $postData['id'] : "");
			$result = $this->taskManager->saveTaskStep($postData);            
			
			$stepsList = $this->getTakSteps($postData['task_id']);
			if(!empty($stepsList))
			{
				$this->printJson(['status'=>1,'stepsList'=>$stepsList]);
			}
			else
			{
				$this->printJson(['status'=>0,'stepsList'=>$stepsList]);
			}
        endif;
    }	

    public function getTakSteps($task_id=0){
        $stepsList = '';
		if(!empty($task_id))
		{
			$steps = $this->taskManager->getTaskSteps(['task_id'=>$task_id]);
			if(!empty($steps))
			{
				foreach($steps as $row)
				{
					$changeStatus = (($row->status == 1) ? "2": "1");
					$lineThrough = (($row->status == 2) ? "strikeout": "");
					$checked = (($row->status == 2) ? "checked": "");
					$stepsList .='<li class="list-group-item todo-item border-bottom" data-role="task">
						<div class="row" style="margin:0px;align-items: center;">
							<div class="custom-control custom-checkbox p-0 col" style="min-width: 88%;width: 88%;">
								<input type="radio" name="id_'.$row->id.'" id="id_'.$row->id.'" class="filled-in chk-col-success changeStepStatus" data-status="'.$changeStatus.'" data-id="'.$row->id.'" data-task_id="'.$row->task_id.'" value="" '.$checked.'>
								<label class="custom-control-label todo-label d-block text-left " style="text-overflow: ellipsis;white-space: nowrap;overflow: hidden;" for="id_'.$row->id.'" >
									<div class="todo-desc '.$lineThrough.' ">'.$row->step_note.'</div>
								</label>
							</div>
							<div class="col text-center" style="width: 12%;">
								<strong class=" float-right"><a type="button" class="changeStepStatus" data-status="3" data-id="'.$row->id.'"  data-task_id="'.$row->task_id.'" href="javascript:void(0)"><i class="text-danger fs-22 mdi mdi-delete-forever"></i></a></strong>
							</div>
						<div>
					</li>';
				}
			}
		}
		return $stepsList;
    }
	
    public function changeTaskStepStatus(){
        $postData = $this->input->post();

		$postData['task_level'] = 2;
        $result = $this->taskManager->changeTaskStatus($postData);
        $stepsList = $this->getTakSteps($postData['task_id']);

		if(!empty($stepsList))
		{
			$this->printJson(['status'=>1,'list'=>$stepsList]);
		}
		else
		{
			$this->printJson(['status'=>0,'list'=>$stepsList]);
		}
    }

	/*** GROUP ****/
	
	public function getGroupList(){
        $postData = $this->input->post();
		$postData['task_count']=1;
		$groupListData = $this->taskManager->getGroupList($postData);
		
		$myTaskCount = $this->taskManager->countMyTasks(['status'=>1]);
		
		$groupList = '<li class="list-group-item">
						<a href="javascript:void(0)" class="active list-group-item-action group_item" data-id="0" data-group_name="Assigned To Me"><i class="mdi mdi-format-list-bulleted"></i> Assigned To Me 
						<span class="label label-success lh-inh float-right">'.$myTaskCount->assigned_to_me.'</span></a>
					</li>';
		$groupList .= '<li class="list-group-item">
						<a href="javascript:void(0)" class=" list-group-item-action group_item" data-id="-1" data-group_name="Assigned By Me"><i class="mdi mdi-format-list-bulleted"></i> Assigned By Me 
						<span class="label label-success lh-inh float-right">'.$myTaskCount->assigned_by_me.'</span></a>
					</li>';
		
		if(!empty($groupListData)){
			$groupedLabel = array_reduce($groupListData, function($groupArr, $group) {
				$groupArr[$group->label][] = $group;
				return $groupArr;
			}, []);
			
			foreach ($groupedLabel as $label => $group){
				$groupList .= '<li class="list-group-item bg-transparent-info"><div class="task-label">' . $label . '</div></li>';
				foreach ($group as $row){
					$groupList .= '<li class="list-group-item">
                        <a href="javascript:void(0)" class="list-group-item-action group_item" data-id="'.$row->id.'" data-group_name="'.$row->group_name.'" ><i class="mdi mdi-format-list-bulleted"></i> '.$row->group_name.' 
						<span class="label label-success lh-inh float-right">'.$row->task_count.'</span></a>
                    </li>';
				}
			}
		}
		
        $this->printJson(['status'=>1,'groupList'=>$groupList,'my_count'=>$myTaskCount]);
    }
	
	public function addGroup(){
		$data = $this->input->post(); 
		if(!empty($data['id'])){ $this->data['dataRow'] = $this->taskManager->getGroupList($data); }
		$this->data['empData'] = $this->employee->getEmployeeList();
        $this->load->view($this->group_form, $this->data);
	}
	
    public function saveGroup(){
        $data = $this->input->post(); 
		$errorMessage = array();

        if(empty($data['group_name'])){ $errorMessage['group_name'] = "Group Name is required."; }
        if(empty($data['label'])){ $data['label'] = "General"; }
        if(empty($data['member_ids'])){ $errorMessage['member_ids'] = "Members Required"; }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$data['member_ids'] = $this->loginId.','.$data['member_ids'];
            $result = $this->taskManager->saveGroup($data);
            $this->printJson($result);
        endif;
    }

	public function labelSearch(){
		$this->printJson($this->taskManager->labelSearch());
	}
	
    public function getMemberList(){
        $postData = $this->input->post();
		$memberList = '<option value="'.(!empty($this->loginId) ? $this->loginId : '').'">Self</option>';
		$empData = $this->taskManager->getMemberList(['group_id'=>$postData['group_id']]);
		if(!empty($empData)){
			foreach($empData as $row){
				$selected = (!empty($postData['assign_to']) && $postData['assign_to'] == $row->id) ? 'selected' : '';
				$memberList .= '<option value="'.$row->id.'" '.$selected.'>[ '.$row->emp_code.' ] '.$row->emp_name.'</option>';
			}
		}
		$this->printJson(['status'=>1,'memberList'=>$memberList]);
    }

}
?>