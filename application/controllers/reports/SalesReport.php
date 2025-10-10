<?php
class SalesReport extends MY_Controller
{

    public function __construct(){
		parent::__construct();
		$this->isLoggedin();
        $this->data['headData']->pageTitle = "SALES REPORT";
		$this->data['headData']->controller = "reports/salesReport";
	}

   /* Customer Details Report */
    public function customerDetails(){
        $this->data['headData']->pageTitle = "CUSTOMER DETAILS";
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
		$this->data['startDate'] = date("Y-m-01");
		$this->data['endDate'] = date("Y-m-d");
        $this->load->view("report/sales_report/customer_details",$this->data);
    }

    public function getCustomerDetailList($jsonData = ""){
        $data = (!empty($jsonData))?(Array)decodeUrl($jsonData):$this->input->post();

        $result = $this->salesReport->getCustomerDetailList($data);
    
        $custHtml = "";$i=1;
        foreach($result as $row):
           
            $custHtml .= '<tr>
							<td> '.$i++.' </td>
							<td>'.$row->party_name.'</td>
							<td>'.$row->executive_name.'</td>
							<td>'.$row->mode.'</td>
							<td>'.$row->business_type.'</td>
							<td>'.$row->contact_person.'</td>
							<td>'.$row->party_phone.'</td>
							<td>'.$row->whatsapp_no.'</td>
							<td>'.$row->party_email.'</td>
							<td>'.$row->gstin.'</td>
							<td>'.$row->city_name.'</td>
							<td>'.$row->state_name.'</td>
							<td>'.$row->country_name.'</td>
							<td>'.$row->party_address.'</td>
							<td>'.$row->created_name.'</td>
							<td>'.formatDate($row->created_at).'</td>
						</tr>';

        endforeach;
        $reportTitle = 'Customer Details Report';
		$logo = base_url('assets/images/logo.png');
        $htmlData = '';
        $thead = '<tr>
					<th>#</th>
					<th>Party Name</th>
					<th>Sales Executives</th>
					<th>Mode</th>
					<th>Business Type</th>
					<th>Contact Person</th>
					<th>Contact No</th>
					<th>Whatsapp No</th>
					<th>Email</th>
					<th>GSTIN</th>
					<th>City</th>
					<th>State</th>
					<th>Country</th>
					<th>Address</th>
                    <th>Created By</th>
                    <th>Created Date</th>
				</tr>';
        if(!empty($data['is_pdf'])){
            $htmlData = '<table class="table" style="border-bottom:1px solid #036aae;">
                        <tr>
                            <td class="text-uppercase text-left"><img src="'.$logo.'" class="img" style="height:50px;"></td>
                            <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$reportTitle.'</td>
                            <td class="text-uppercase text-right" style="font-size:0.8rem;width:30%"></td>
                        </tr>
                    </table>';

            $htmlData.= '<table class="table item-list-bb" style="margin-top:10px;">
                        	<thead class="gradient-theme">
								'.$thead.'
							</thead>
                            <tbody> '.$custHtml.'</tbody>
                        </table>';
            $pdfData = $this->generatePDF($htmlData,'L');
		}else { 
			$this->printJson(['status'=>1,'tbody'=>$custHtml]);
        }
    }
    /* Appointment Register Report */
    public function appointmentRegister(){
		$this->data['headData']->pageTitle = "APPOINTMENT REGISTER REPORT";
        $this->data['DT_TABLE'] = true;
        $this->data['startDate'] = date("Y-m-01");
        $this->data['endDate'] = date("Y-m-d");
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
        $this->load->view("report/sales_report/appointment_register",$this->data);
    }

    public function getAppointmentRegister(){
        $data = $this->input->post();

        $result = $this->salesReport->getAppointmentRegister($data);
        $i=1; $tbody='';
        if(!empty($result)):
            foreach($result as $row):
                $daysDiff = '';
				$respond_date = (!empty($row->updated_at))? $row->updated_at : date('Y-m-d');
                if(!empty($row->ref_date) AND !empty($respond_date)){
                    $ref_date = new DateTime($row->ref_date);
                    $resDate = new DateTime($respond_date);
                    $due_days = $ref_date->diff($resDate)->format("%r%a");
                    $daysDiff = ($due_days > 0) ? $due_days : 'On Time';
                }
                $tbody .= '<tr>
                    <td>'.$i++.'</td>
                    <td>'.formatDate($row->ref_date).'</td>
                    <td>'.$row->emp_name.'</td>
                    <td>'.$row->party_name.'</td>
                    <td>'.$row->mode.'</td>
                    <td>'.$row->notes.'</td>
                    <td>'.$row->remark.'</td>
                    <td>'.formatDate($row->updated_at).'</td>
                    <td>'.$daysDiff.'</td>';
                $tbody .= '</tr>';
            endforeach; 
        endif;  
        $this->printJson(['status'=>1,'tbody'=>$tbody]);
    }

    /* FollowUp Register Report */
    public function followUpRegister(){
		$this->data['headData']->pageTitle = "FOLLOWUP REGISTER REPORT";
        $this->data['DT_TABLE'] = true;
        $this->data['startDate'] = date("Y-m-01");
        $this->data['endDate'] = date("Y-m-d");
        $this->data['partyList'] = $this->party->getPartyList(); 
        $this->load->view("report/sales_report/followup_register",$this->data);
    }

    public function getFollowUpRegister(){
        $data = $this->input->post();
        $result = $this->salesReport->getFollowUpRegister($data);
		$i=1;$tbody='';
        if(!empty($result)):
            foreach($result as $row):
                $tbody .= '<tr>
                    <td>'.$i++.'</td>
                    <td>'.formatDate($row->created_at).'</td>
                    <td>'.$row->emp_name.'</td>
                    <td>'.$row->party_name.'</td>
                    <td>'.$row->business_type.'</td>
                    <td>'.$row->notes.'</td>
                    </tr>';
            endforeach; 
        endif; 
        
        $this->printJson(['status'=>1,'tbody'=>$tbody]);
    }

     /*Project Tracking Report */
	public function projectTracking(){
        $this->data['headData']->pageTitle = 'PROJECT TRACKING';
        $this->data['DT_TABLE'] = true;
        $this->data['projectList'] = $this->project->getProjectData(['trans_status'=>2]);
		$this->data['API_KEY'] = 'AIzaSyACJW3ouSsTuZserlw3FRHIC2MWbppIuJ4';

        $this->load->view("report/sales_report/project_tracking",$this->data);
    }
	
	
    public function getProjectTrackingData(){
        $data = $this->input->post();
        $errorMessage = array();
		if(empty($data['project_id']))
			$errorMessage['project_id'] = "Employee is Required";
		
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $logData = $this->workProgress->getWorkLogsData(['project_id'=>$data['project_id']]);

            $projectData =$this->project->getProjectData(['id'=>$data['project_id'],'single_row'=>1]); 

            $html = '<img src="'.base_url('assets/images/background/dnf_1.png').'" style="width:100%;">
            <h3 class="text-danger text-center font-24 fw-bold line-height-lg">Sorry!<br><span class="text-dark">Data Not Found</span></h3>';

            if(!empty($logData))
            {   $html="";
                foreach($logData as $row){
                    $link = ''; 
                    
                    $workIdCount = count(explode(',', $row->work_id));
                    $workViewParam = "{'postData':{'work_id' : '".$row->work_id."','step_no' : ".$row->step_no."},'modal_id' : 'modal-md', 'form_id' : 'workDetail', 'title' : 'Work Detail View', 'call_function' : 'workDetailView', 'button' : 'close'}";

                    $link = '<a class="text-warning" href="javascript:void(0)"  flow="down" onclick="modalAction('.$workViewParam.');"><span>[ TOTAL  ' . $workIdCount . ' Works Updated OutOf  '.$row->totalWork.' ]</span></a>';
                
                    $html.= '<div class="activity-info">
								<div class="icon-info-activity"><i class="mdi mdi-check-circle bg-soft-success"></i></div>
								<div class="activity-info-text">
									<div class="d-flex justify-content-between align-items-center">
										<h6 class="m-0 fs-13">Step No :'.$row->step_no.'</h6>
                                       
										<span class="text-muted w-30 d-block font-12">
										'.date("d-m-Y",strtotime($row->created_at)).'</span>
									</div>
									<p class=" m-1 font-12"><i class="fa fa-user"></i> '.$row->created_name.'</p>
									<p class="text-muted m-1 font-12">'.$row->notes.'</p>
									<p class="text-muted m-1 font-12">'.$link.'</p>
								</div>
							</div>';
                }
            
                $html .= '</div>';
            }
            $html2="";
                
            $html2 = '<table class="table table-striped">
                    <tr> 
                        <td colspan="2"><b> Project Name : </b>'. $projectData->project_name.'</td>
                    </tr>
                     <tr> 
                        <td><b> Project Start At :</b> '.(!empty($projectData->start_date) ?formatDate($projectData->start_date) : '').'</td>
                        <td><b> Project Type  :</b> '.$projectData->project_type.'</td>
                    </tr>

                    <tr> 
                        <td><b> Customer Name  :</b> '.$projectData->party_name.'</td>
                        <td><b> SQ. No.  :</b> '.$projectData->sq_no.'</td>
                    </tr>
                    <tr> 
                        <td colspan="2"><b>Project Location  :</b> '.$projectData->location.'</td>
                    </tr>   
                    <tr> 
                        <td colspan="2"><b> Project Other Info  :</b> '.$projectData->other_info.'</td>
                    </tr>   
                   
                    
                    
                </table>';
            
            $this->printJson(['status'=>1, 'html'=>$html, 'html2'=>$html2]);
        endif;
    }

    public function workDetailView(){
        $data = $this->input->post(); //print_r($data);exit;
        $this->data['workList'] = $this->workInstructions->getWorkInstructions(['work_id'=>$data['work_id']]);
        $this->data['step_no'] = $data['step_no'];

        $this->load->view("work_progress/work_detail",$this->data);
    }

    // Task Manager Report
     public function taskReport(){
        $this->data['headData']->pageTitle = "Task Report";
		$this->data['groupList'] = $this->taskManager->getGroupList();
        $this->data['empData'] = $this->employee->getEmployeeList();
        $this->data['startDate'] = getFyDate(date("Y-m-01"));
        $this->data['endDate'] = getFyDate(date("Y-m-d"));
        $this->load->view('report/sales_report/task_report',$this->data);
    }

    public function getTaskManager(){
        $data = $this->input->post();
        $customWhere = "DATE(task_master.due_date) BETWEEN '".date("Y-m-d",strtotime($data['from_date']))."' AND '".date("Y-m-d",strtotime($data['to_date']))."'";
        $postData = [
			'assign_to'=>$data['assign_to'],
			'group_id'=>$data['group_id'],
			'status'=>$data['status'],
			'created_by'=>$data['created_by'],
			'customWhere'=>$customWhere,
		]; 
       	$taskData = $this->taskManager->getTaskList($postData); 
        // print_r($this->db->last_query());exit;
        $i=1; $tbody=''; 
        if(!empty($taskData)){
            foreach($taskData as $row):
                $status = ($row->status == 1)?'Pending':(($row->status == 2)?'Completed':'Cancelled');
                $group_name = ($row->group_id == 0) ? 'Individual' : $row->group_name; 

                $due_days = '';
				if(!empty($row->due_date) AND !empty($row->complete_on)){
					$due_date = new DateTime($row->due_date);
					$complete_on = new DateTime($row->complete_on);
					$due_days = $due_date->diff($complete_on)->format("%r%a");
				}

                $tbody .= '<tr>
                    <td>'.$i++.'</td>
                    <td>'.$row->task_number.'</td>
                    <td>'.$group_name.'</td>
                    <td>'.$row->assign_name.'</td>
                    <td>'.$row->task_title.'</td>
                    <td>'.$row->notes.'</td>
                    <td>'.$row->repeat_type.'</td>
                    <td>'.formatDate($row->due_date).'</td>
                    <td>'.formatDate($row->complete_on).'</td>
                    <td>'.floatVal($due_days).'</td>
                    <td>'.$status.'</td>
                    <td>'.$row->assign_by_name.'</td>
                </tr>';
            endforeach;
        } 
        $this->printJson(['status'=>1, 'tbody'=>$tbody]);
    }
}
?>
