<?php
	$taskLog = '';
	if(!empty($taskList)){
		foreach($taskList as $row){
			$userImg = base_url('assets/images/users/user_default.png');
			$editButton='';$deleteButton='';$cancelBtn='';$stageBtn='';$completeBtn='';$startBtn='';$subTaskBtn ='';
			$changeStatus = "";
			$lineThrough = "";
			$checked = "";
			$cancelBtn = '<strong class=""><a type="button" class="changeTaskStatus" data-status="3" data-id="'.$row->id.'" data-group_id="'.$row->group_id.'" href="javascript:void(0)"><i class="text-danger fs-22 mdi mdi-delete-forever"></i></a></strong>';
			if($row->status == 1){
				$changeStatus = 2;$lineThrough = "";$checked = "";
				$startParam = "{'postData':{'id':".$row->id.",'log_type':'2','status':'2'},'message':'Are you sure want to Change Status to Start?','fnsave':'changeTaskStatus','confirm':'1'}";
				$startBtn = '<a href="javascript:void(0)" class="dropdown-item btn-edit btn-danger permission-modify" style="justify-content: flex-start;" onclick="taskEdit('.$startParam.');" data-msg="Start" flow="down"><i class="mdi mdi-play"></i> Start Task</a>';

				$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-lg', 'form_id' : 'editTask', 'js_store_fn':'saveTask', 'title' : 'Update Task'}";
				
				$editButton = '<a class="btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="left" onclick="taskEdit('.$editParam.');"><i class="mdi mdi-tooltip-edit text-success fs-20"></i></a>';
				
				$deleteParam = "{'postData':{'id' : ".$row->id.",'group_id' : ".$row->group_id.",'status' : 3},'message' : 'Task'}";
				$deleteButton = '<a class="btn-delete permission-remove changeTaskStatus" href="javascript:void(0)" datatip="Remove" flow="left" onclick="trashTask('.$deleteParam.');"><i class="mdi mdi-delete-forever text-danger fs-22"></i></a>';

				$subTaskBtn = '<a class="addNewTask permission-write press-add-btn"  href="javascript:void(0)" type="buton" data-ref_id="'.$row->id.'" data-button="both" data-modal_id="modal-lg" data-function="addTask" data-controller="taskManager" data-form_title="Add Sub Task" data-js_store_fn="saveTask" datatip="Sub Task" flow="left"><i class="mdi mdi-source-branch text-dark fs-20 rotate-90"></i></a>';
				
				$completeBtn = '<span datatip="Complete Task" class="text-success font-bold cursor-pointer changeTaskStatus" data-status="'.$changeStatus.'" data-id="'.$row->id.'" data-group_id="'.$postData['group_id'].'" flow="up"><i class="mdi mdi-checkbox-marked-outline fs-18"></i> Complete</span>';
			}
			elseif($row->status == 2){
				$changeStatus = 1;$lineThrough = "strikeout";$checked = "checked";
				$completeParam = "{'postData':{'id':".$row->id.",'log_type':'3','status':'3'},'message':'Are you sure want to Change Status to Complete?','fnsave':'changeTaskStatus','confirm':'1'}";

				$subTaskParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-lg', 'form_id' : 'subTask', 'title' : 'Sub Task','fnedit':'addTask','fnsave':'save'}";
				
				$subTaskBtn = '<a class="addNewTask permission-write press-add-btn"  href="javascript:void(0)" type="buton" data-ref_id="'.$row->id.'" data-button="both" data-modal_id="modal-lg" data-function="addTask" data-controller="taskManager" data-form_title="Add Sub Task" data-js_store_fn="saveTask" datatip="Sub Task" flow="left"><i class="mdi mdi-source-branch text-dark fs-20 rotate-90"></i></a>';
				
			}
			$viewTaskParam = "{'postData':{'id':".$row->id."},'modal_id':'modal-md','form_id':'viewTask','fnedit':'viewTaskDetails','button':'close','title':'Task Details'}";
			$viewTaskBtn = '<a href="javascript:void(0)" class="dropdown-item btn-edit btn-danger permission-modify" style="justify-content: flex-start;" onclick="taskEdit('.$viewTaskParam.');" data-msg="View Task Details" flow="down"><i class="fas fa-eye"></i> View Task</a>';
			$priority = ((!empty($row->priority) AND $row->priority == 'High') ? 'text-danger' : 'text-turkish');
			
			$statusBadge = ['','Pending','In Progress','Completed'];
			$statusBadgeClr = ['','danger','info','success'];
			$status = '<span class="badge badge-pill badge-'.$statusBadgeClr[$row->status].'">'.$statusBadge[$row->status].'</span>';
			
			//BUTTON PERMISSION
			$ds="";
			if($this->loginId != $row->created_by){ $editButton=$deleteButton=""; }
			if(!in_array($this->loginId,[$row->created_by,$row->assign_to])){ $subTaskBtn="";  }
			if($this->loginId != $row->assign_to || $row->status == 2){ $ds="disabled"; }
			
			$tags = "";$ref_no=(!empty($row->ref_no) ? '<small><i class="mdi mdi-arrow-left"></i> '.$row->ref_no.'</small>' : '');
			
			if(!empty($row->tags )){
				$tags .= '<br><span class="text-primary">#'.str_replace(',','</span> <span class="text-primary">#',$row->tags);
			}
			
			$actionButtons = $subTaskBtn.$editButton.$deleteButton;
			
			$dueOn = '<span class="badge badge-soft-pink fw-semibold ms-2 v-super"><i class="far fa-fw fa-clock"></i> '.formatDate($row->due_date,'j, M Y').'</span>';
			
			if(!empty($actionButtons)){
				$dueOn = '<span class="badge badge-soft-pink fw-semibold ms-2 v-super due_on"><i class="far fa-fw fa-clock"></i> '.formatDate($row->due_date,'j, M Y').'</span>
							<span class="row_action v-super">'.$actionButtons.'</span>';
			}
			
			$rr = '';
			$repeat = '';$remind = '';
			if(!empty($row->remind_at)){
				$remind = '<i class="mdi mdi-alarm"></i> <span class="text-muted font-weight-normal"> '.formatDate($row->remind_at,'d M Y H:i A').'</span>';
			}
			if($row->repeat_type == 'Daily'){
				$repeat = '<i class="mdi mdi-repeat"></i><span class="text-muted font-weight-normal"> '.$row->repeat_type.'</span>' ;
			}
			
			if(!empty($repeat) OR !empty($remind))
			{
				$rr = '<span datatip="Complete Task" class="text-success font-bold cursor-pointer changeTaskStatus" data-status="'.$changeStatus.'" data-id="'.$row->id.'" data-group_id="'.$postData['group_id'].'" flow="up"><i class="mdi mdi-checkbox-marked-outline fs-18"></i> Complete</span>';
			}
			
			$assign_by_name = (!empty($row->assign_by_name) ? $row->assign_by_name : '');
			$abnShortName = $assign_by_name;
			if(strlen($assign_by_name) > 5){$abnShortName = getShortNameByFirstLetter($assign_by_name);}
			
			$assign_name = (!empty($row->assign_name) ? $row->assign_name : '');
			$atnShortName = $assign_name;
			if(strlen($assign_name) > 5){$atnShortName = getShortNameByFirstLetter($assign_name);}
			
			// $row->task_title.=$row->task_title;
			$taskLog .='<div class=" grid_item group_'.$row->group_id.'" style="width:24%;">
							<div class="card">
								<div class="card-body">                                    
									<div class="task-box">
										<div class="task-priority-icon"><i class="fas fa-circle '.$priority.'" style="border: 5px solid #e9edf2;"></i></div>
										<div class="float-right">
											'.$dueOn.'
										</div>
										<h5 class="mt-0 fs-15 cursor-pointer viewTask link '.$lineThrough.' " data-id="'.$row->id.'" >#'.(!empty($row->task_number) ? $row->task_number : '').$ref_no.'</h5>                                      
									</div>
										<p class=" mb-0 font-14 fw-400" style="height:50px;">'.truncateWithEllipsis($row->task_title,75).'</p>
										<div class="d-flex justify-content-between">  
											<h6 class="fw-semibold fs-13">'.(!empty($remind) ? $remind : '&nbsp;').'</h6>
											<h6 class="fw-semibold fs-13">'.(!empty($repeat) ? $repeat : '&nbsp;').'</h6>
										</div>
										<div class="media d-flex" style="justify-content: space-evenly;border-top:1px solid rgba(0, 0, 0, 0.1)">
											<span datatip="['.$row->assign_code.'] '.(!empty($row->assign_by_name) ? $row->assign_by_name : '').'" class="text-primary font-bold" flow="up"><i class="mdi mdi-account-edit  fs-18"></i> '.$abnShortName.'</span>
											
											<span datatip="['.$row->assign_by_code.'] '.(!empty($row->assign_name) ? $row->assign_name : '').'" class="text-info font-bold" flow="up"><i class="mdi mdi-account-check fs-18"></i> '.$atnShortName.'</span>
											
											'.$completeBtn.'
										</div> 
								</div>
							</div>
						</div>';
		}
	}
    echo $taskLog;
	
?>
							
							