<?php
class Project extends MY_Controller{
    private $indexPage = "project/index";
    private $form = "project/form";
    private $projectDetail = "project/project_detail";
    private $inchargeForm = "project/incharge_form";
    private $paymentForm = "project/payment_form";
    private $handover_certificate = "project/handover_certificate";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Project";
		$this->data['headData']->controller = "project";        
        $this->data['headData']->pageUrl = "project";
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader("project");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 1){
        $data = $this->input->post();$data['trans_status'] = $status;
        $result = $this->project->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            //$row->total_service = $this->project->getTotalService(['service_type' => 'AMC','project_id' => $row->id]);
            $sendData[] = getProjectData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addProject(){
        $data = $this->input->post(); 
        $this->data['sq_no'] = $data['trans_number'];
        $this->data['party_id'] = $data['party_id'];
        $this->data['project_type'] = $data['project_type'];
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['project_name']))
            $errorMessage['project_name'] = "Project Name is required.";

        if(empty($data['amc'])){
            $errorMessage['amc'] = "AMC is required.";
        }
        if(empty($data['amc_validity']) && $data['amc'] == "Yes"){
            $errorMessage['amc_validity'] = "Validity is required.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(isset($_FILES['drawing_file']['name'])):
                if($_FILES['drawing_file']['name'] != null || !empty($_FILES['drawing_file']['name'])):
                    $this->load->library('upload');
                    $_FILES['userfile']['name']     = $_FILES['drawing_file']['name'];
                    $_FILES['userfile']['type']     = $_FILES['drawing_file']['type'];
                    $_FILES['userfile']['tmp_name'] = $_FILES['drawing_file']['tmp_name'];
                    $_FILES['userfile']['error']    = $_FILES['drawing_file']['error'];
                    $_FILES['userfile']['size']     = $_FILES['drawing_file']['size'];
                    
                    $imagePath = realpath(APPPATH . '../assets/uploads/project/');
                    $config = ['file_name' => 'project-'.time(),'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];

                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload()):
                        $errorMessage['drawing_file'] = $this->upload->display_errors();
                        $this->printJson(["status"=>0,"message"=>$errorMessage]);
                    else:
                        $uploadData = $this->upload->data();
                        $data['drawing_file'] = $uploadData['file_name'];
                    endif;
                endif;
            endif;
          
            $this->printJson($this->project->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $dataRow = $this->project->getProjectData(['id'=>$data['id'],'single_row'=>1]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->project->delete($data));
        endif;
    }
    
    public function changeProjectStatus(){
		$data = $this->input->post();
		if(empty($data['id'])):
			$this->printJson(['status'=>0,'message'=>'Something went wrong...Please try again.']);
		else:
			$this->printJson($this->project->changeProjectStatus($data));
		endif;
	}

    public function printLedger($id){
        
        $ledgerTransactions = $this->project->getPaymentData(['project_id'=>$id]); 
        $projectData = $this->project->getProjectData(['id'=>$id,'single_row'=>1]); 
        $companyData = $this->masterModel->getCompanyInfo();
        $logo = base_url('assets/images/logo_text.png');          

        $i=1; $tbody=""; $totalAmt = 0;
        foreach($ledgerTransactions as $row):
            $tbody .= '<tr>
                <td>'.$i++.'</td>
                <td>'.$row->payment_date.'</td>
                <td>'.$row->pay_mode.'</td>
                <td>'.$row->ref_no.'</td>
                <td>'.$row->remark.'</td>
                <td style="text-align: center;">'.$row->amount.'</td>
            </tr>';
            $totalAmt += $row->amount;
        endforeach;        

        $pdfData = '<table class="table" style="border-bottom:1px solid #036aae;">
                <tr>
                    <td class="org_title text-uppercase text-left"><img src="'.$logo.'" class="img" style="height:30px;"></td>
                    <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$companyData->company_name.'</td>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"></td>
                </tr>
            </table>
            <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:2px;">
                <tr><td class="org-address text-center" style="font-size:13px;">'.$companyData->company_address.'</td></tr>
            </table>
            <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:10px;">
                <tr>
                    <td class="org_title text-uppercase text-left" style="font-size:1rem;width:30%">Client Name : '.$projectData->party_name.'</td>
                    <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$projectData->project_name.'</td>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"> Project Cost : '.$projectData->project_cost.'</td>

                </tr>
            </table>';
        $pdfData .= '<table class="table item-list-bb " repeat_header="1">
                        <thead >
                            <tr class="bg-light-grey">
                                <th>#</th>
                                <th>Date</th>
                                <th>Payment Mode</th>
                                <th>Ref.No.</th>
                                <th>Remarks</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">'.$tbody.'</tbody>
                    </table>
                    <table class="table" style="border-bottom:1px solid #036aae;border-top:1px solid #036aae;margin-top:10px;">
                        <tr>
                            <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"> Closing Balance: '.$projectData->project_cost - $totalAmt .'</td>
                        </tr>
                    </table>';
        $pdfData .= '<htmlpagefooter name="lastpage">
            <table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                <tr>
                    <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                    <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                </tr>
            </table>
            </htmlpagefooter>
		<sethtmlpagefooter name="lastpage" value="on" />';

      
        $mpdf = new \Mpdf\Mpdf();
        $filePath = realpath(APPPATH . '../assets/uploads/');
        $pdfFileName = $filePath.'/ledgerDetail.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->AddPage('L','','','','',5,5,5,5,3,3,'','','','','','','','','','A4-L');
        $mpdf->WriteHTML($pdfData);
        $mpdf->Output($pdfFileName, 'I');
    }

    public function projectDetail($id){
        $id = decodeURL($id);
        $this->data['headData']->pageTitle = "Project Detail";
        $this->data['dataRow'] = $this->project->getProjectData(['id'=>$id,'single_row'=>1]);
        $this->data['project_id'] = $id;
        $this->load->view($this->projectDetail,$this->data);
    }

     /* Start  Project Specification Data*/ 
    public function getSpecificationHtml(){
        $data = $this->input->post();
        $specificData = $this->project->getProjectSpecData(['project_id' => $data['project_id']]);
        $html = ""; $j=1; $specData = [];
        foreach($specificData as $row):
           $specData[$row->specification] = ['spec_desc' => $row->spec_desc,'id' => $row->id ];
        endforeach;

        foreach ($this->specArray as $key=>$label) {
            $value = isset($specData[$label]) ? $specData[$label]['spec_desc'] : '';
            $id =  (isset($specData[$label]) ? $specData[$label]['id'] : '');

            $html .= '<tr>
                    <th class="text-left bg-grey" style="width:20%;">' . $label . '</th>
                    <td class="text-left">
                        <input type="text" name="specData[' . $key . ']" class="form-control" value="' . htmlspecialchars($value) . '" />
                        <input type="hidden" name="id['.$key.']" value="' . $id . '" />
                        <input type="hidden" name="project_id" value="' . $data['project_id'] . '" />
                    </td>
                </tr>';
            $j++;
        }

        $this->printJson(['status' => 1, 'tbodyData' => $html]);
    }

    public function saveSpecification(){
        $data = $this->input->post(); 
        $errorMessage = array();

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->project->saveSpecification($data));
        endif;
    }

    /* END  Project Specification Data*/ 

    /* Start Project Agency Data*/ 
    public function resAgencyHtml(){
        $data = $this->input->post();
        $agencyData = $this->project->getProjectAgencyData(['project_id'=>$data['project_id']]);
		$i=1; $tbody='';
        
		if(!empty($agencyData)):
			foreach($agencyData as $row):
                $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Project Agency','res_function':'resAgencyHtml','fndelete':'deleteAgency'}";
                $editBtn = "<button type='button' onclick='editAgency(".json_encode($row).",this);' class='btn btn-sm btn-outline-info waves-effect waves-light btn-sm permission-modify' datatip='Edit'><i class='far fa-edit'></i></button>";

				$tbody.= '<tr>
						<td class="text-center">'.$i++.'</td>
						<td>'.$row->agency_name.'</td>
						<td>'.$row->agency_contact.'</td>
						<td class="text-center">'.$row->remark.'</td>
						<td class="text-center">
                        '.$editBtn.'
							<button type="button" onclick="trash('.$deleteParam.');" class="btn btn-sm btn-outline-danger waves-effect waves-light permission-remove"><i class="mdi mdi-trash-can-outline"></i></button>
						</td>
					</tr>';
			endforeach;
        else:
            $tbody = '<tr><td colspan="5" class="text-center">No data found.</td></tr>';
		endif;
        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
	}

    public function saveAgency(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['agency_name']))
            $errorMessage['agency_name'] = "Agency Name is required.";

         if(empty($data['agency_contact']))
            $errorMessage['agency_contact'] = "Agency Contact is required.";
		
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:

            
            $this->printJson($this->project->saveAgency($data));
        endif;
    }

    public function deleteAgency(){ 
        $data=$this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$this->printJson($this->project->deleteAgency($data['id']));
		endif;
    }

    /* End Project Agency Data*/ 

    /*Start Project Incharge */
    public function addIncharge(){
		$data = $this->input->post();
        $this->data['project_id'] = $data['project_id'];
		$this->data['employeeList'] = $this->employee->getEmployeeList();
        $this->data['dataRow'] = $this->project->getProjectData(['id'=>$data['project_id'],'single_row'=>1]);
		$this->load->view($this->inchargeForm,$this->data);
	}
	
	public function saveIncharge(){
        $data = $this->input->post();
        $errorMessage = []; 

        if(empty($data['incharge_ids']))
            $errorMessage['incharge_ids'] = "In-Charge is required.";
		
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else: 

            $this->printJson($this->project->saveIncharge($data));
        endif;
    }

    /*End Project Incharge */

    /*Start Project Work Plan */

    public function getWorkPlanHtml() {
        $data = $this->input->post();
        $workProgressData = $this->workProgress->getWorkProgressData(['project_id' => $data['project_id']]);
        $workStepData = $this->workInstructions->getWorkInstructions(['work_type'=>3]);
		

         $wpData = [];
        foreach($workProgressData as $row):
           $wpData[$row->description] = ['work_step' => $row->work_step,'id' => $row->id ];
        endforeach;
        
        $workPlanData = $this->workInstructions->getWorkInstructions(['work_type'=>2]);
        $html = "";  $i = 1; $groupedData = [];

        foreach ($workPlanData as $row) { $groupedData[$row->work_title][] = $row; }

        foreach ($groupedData as $workTitle => $workPlans) {
            $html .= '<tr class="text-center bg-grey">
                          <th colspan="2">'.$workTitle.'</th>
                      </tr>';

            foreach ($workPlans as $row) {
                $value = isset($wpData[$row->description]) ? $wpData[$row->description]['work_step'] : 0;
                $id = isset($wpData[$row->description]) ? $wpData[$row->description]['id'] : '';
				
				$work_step = '<option value="0" ' . (($value == 0) ? "selected" : "") . '>N/A</option>';
				if(!empty($workStepData)){
					foreach ($workStepData as $step_row) { $work_step .= '<option value="'.$step_row->work_title.'" ' . (($value == 0) ? "selected" : "") . '>'.$step_row->work_title.'</option>'; }
				}

                $html .= '<tr>
                            <td  class="text-wrap text-left">' . $row->description . '
                                <input type="hidden" name="work_id[]" id="work_id_' . $i . '"  value="' . $row->id . '">
                                <input type="hidden" name="id[]" id="id_' . $i . '"  value="' . $id . '">
                            </td>
                            <td>
                                <select name="work_step[]" id="work_step_' . $i . '" class="form-control basic-select2">'.$work_step.'</select>
                                    
                                
                            </td>
                        </tr>';
                $i++;
            }
        }

        $this->printJson(['status' => 1, 'tbodyData' => $html]);
    }

    public function saveWorkPlan(){
        $data = $this->input->post();
        $errorMessage = []; 

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else: 
            $this->printJson($this->project->saveWorkPlan($data));
        endif;
    }
    /*End Project Work Plan */

    /*Start Project Work Instruction */
    public function getWorkInstructionHtml(){
        $data = $this->input->post();
        $projectData = $this->project->getProjectData(['id'=>$data['project_id'],'single_row'=>1]);
        $workIds = (!empty($projectData) && !is_null($projectData->wi_id)) ? explode(',', $projectData->wi_id) : [];

        $workInstData = $this->workInstructions->getWorkInstructions(['work_type'=>1]);

        $html = "";  $i = 1; $groupedData = [];
        foreach ($workInstData as $row) {
            $groupedData[$row->work_title][] = $row;
        }

        foreach ($groupedData as $workTitle => $instructions) {
            $html .= '<tr class="text-center bg-grey">';
                if($i == 1):
                    $html .= '<th style="width:5%;">
                              <input type="checkbox" id="masterSelect" class="filled-in chk-col-success workInstruction" value=""><label for="masterSelect" > ALL</label>
                             </th>';
                endif;
                    $html .= '<th class="text-center" style="width:25%;" colspan="2">'.$workTitle.'</th>
                      </tr>';

            foreach ($instructions as $row) {
                $checked = in_array($row->id, $workIds) ? 'checked' : '';
                $html .= '<tr>
                            <td class="text-center" style="width:50px;">
                                <input type="checkbox" name="wi_id[]" id="wi_id_' . $i . '" class="filled-in chk-col-success workInstruction" value="' . $row->id . '" '.$checked.'>
                                <label for="wi_id_' . $i . '"></label>
                            </td>
                            <td style="width:100px;font-size:11px;" class="text-wrap text-left">' . $row->description . '</td>
                         
                        </tr>';
                $i++;
            }
        }

        $this->printJson(['status' => 1, 'tbodyData' => $html]);
    }

    public function saveWorkInstruction(){
        $data = $this->input->post();
        $errorMessage = []; 
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else: 
        	$data['wi_id'] = (!empty($data['wi_id']) ? implode(",",$data['wi_id']) : '');
            $data['id'] =$data['project_id'];
            unset($data['project_id']);
            $this->printJson($this->project->save($data));
        endif;
    }
    /*End Project Work Instruction */

    /* Start Project Payment Data*/ 
    public function addPayment(){
        $data = $this->input->post();
        $this->data['project_id'] = $data['id'];
        $this->load->view($this->paymentForm,$this->data);
    }

    public function getPaymentHtml(){
        $data = $this->input->post();
        $agencyData = $this->project->getPaymentData(['project_id'=>$data['project_id']]);
		$i=1; $tbody='';
        
		if(!empty($agencyData)):
			foreach($agencyData as $row):
                $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Payment','res_function':'getPaymentHtml','fndelete':'deletePayment'}";

				$tbody.= '<tr>
						<td class="text-center">'.$i++.'</td>
						<td>'.formatDate($row->payment_date).'</td>
						<td>'.$row->pay_mode.'</td>
						<td>'.$row->amount.'</td>
						<td>'.$row->ref_no.'</td>
						<td class="text-center">'.$row->remark.'</td>
						<td class="text-center">
							<button type="button" onclick="trash('.$deleteParam.');" class="btn btn-sm btn-outline-danger waves-effect waves-light permission-remove"><i class="mdi mdi-trash-can-outline"></i></button>
						</td>
					</tr>';
			endforeach;
        else:
            $tbody = '<tr><td colspan="7" class="text-center">No data found.</td></tr>';
		endif;
        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
	}

    public function savePayment(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['payment_date']))
            $errorMessage['payment_date'] = "Date is required.";

        if(empty($data['pay_mode']))
            $errorMessage['pay_mode'] = "Payment Mode is required.";

        if(empty($data['amount']))
            $errorMessage['amount'] = "Amount is required.";

        if(empty($data['ref_no']))
            $errorMessage['ref_no'] = "Ref. No. is required.";
		
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            
            $this->printJson($this->project->savePayment($data));
        endif;
    }

    public function deletePayment(){ 
        $data=$this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$this->printJson($this->project->deletePayment($data['id']));
		endif;
    }
    /* End  Project Payment Data*/ 

	/* Start  Project Handover Certificate Data*/ 
    public function addHandoverCertificate(){
        $data = $this->input->post();
        $this->data['project_id'] = $data['id'];
        $this->load->view($this->handover_certificate,$this->data);
    }

    public function saveHandover(){
        $data = $this->input->post();
        $errorMessage = []; 
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else: 
            $data['handover_by'] = $this->loginId;
            $data['handover_at'] = date('Y-m-d H:i:s');
            $data['trans_status'] = 4; //Complete Project
            $this->printJson($this->project->save($data));
        endif;
    }
    /* End Project Handover Certificate Data*/ 
}
?>