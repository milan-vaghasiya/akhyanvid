<?php
class Service extends MY_Controller{
    private $indexPage = "service/index";
    private $formPage = "service/form";
    private $partReplace = "service/part_replace";
    private $assignTechnician = "service/assign_technician";
    private $completeService = "service/complete_service";
    private $service_view = "service/service_view";
	    
	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Service";
		$this->data['headData']->controller = "service";
		$this->data['headData']->pageUrl = "service";
	}
	
	public function index(){
        $this->data['tableHeader'] = getSalesDtHeader('service');
        $this->load->view($this->indexPage,$this->data);
    }
	
	public function getDTRows($status=1){
        $data = $this->input->post();
        $data['status'] = $status;/* 1 = Pending, 2 = Approved, 3 = Accepted Tech. 4 = In Progress, 5= Completed, 6= On Hold*/ 
		
        $result = $this->service->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;
            $row->loginId = $this->session->userdata('loginId');
            $sendData[] = getserviceData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }
	
    public function addService(){
        $this->data['trans_prefix'] = 'S'.n2y(date("Y")).n2m(date("m"));
        $this->data['trans_no'] = $this->service->getNextServiceNo($this->data['trans_prefix']);
        $this->data['trans_number'] = $this->data['trans_prefix'].lpad($this->data['trans_no'],3);
        $this->data['projectList'] = $this->project->getProjectData();
		$this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'el_model'=>1]);
        $this->load->view($this->formPage,$this->data);
    }
    
    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['trans_date']))
            $errorMessage['trans_date'] = "Service Date is required.";
        if(empty($data['project_id']))
            $errorMessage['project_id'] = "Project is required.";  
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(!empty($_FILES['bfr_images']['name'][0])):
                $file_upload = array();$f=1;
                if($_FILES['bfr_images']['name'][0] != null || !empty($_FILES['bfr_images']['name'][0])):
                    $this->load->library('upload');
                    foreach ($_FILES['bfr_images']['tmp_name'] as $key => $value):
                        $_FILES['userfile']['name']     = $_FILES['bfr_images']['name'][$key];
                        $_FILES['userfile']['type']     = $_FILES['bfr_images']['type'][$key];
                        $_FILES['userfile']['tmp_name'] = $_FILES['bfr_images']['tmp_name'][$key];
                        $_FILES['userfile']['error']    = $_FILES['bfr_images']['error'][$key];
                        $_FILES['userfile']['size']     = $_FILES['bfr_images']['size'][$key];
                        
                        $imagePath = realpath(APPPATH . '../assets/uploads/service/');
						$fileName = 'BEFORE_'.time().'_'.$_FILES['bfr_images']['name'][$key];
                        $config = ['file_name' => $fileName,'allowed_types' => '*','max_size' => 10240,'overwrite' => TRUE, 'upload_path'=>$imagePath];
        
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload()):
                            $errorMessage['bfr_images'] = $this->upload->display_errors();
                            $this->printJson(["status"=>0,"message"=>$errorMessage]);
                        else:
                            $uploadData = $this->upload->data();
                            $file_upload[] = $uploadData['file_name'];
                        endif;
                        $f++;
        			endforeach;
        			if(!empty($file_upload)):
        			    $data['bfr_images'] = implode(",",$file_upload);
            		endif; 
    			endif;
            endif;
            if(!empty($data['bfr_images'])){
                $data['bfr_images'] = $data['bfr_images'];
            }			
			
            $this->printJson($this->service->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->service->getService($data);
        $this->data['projectList'] = $this->project->getProjectData();
		$this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'el_model'=>1]);
        $this->load->view($this->formPage,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->service->delete($id));
        endif;
    }

    public function changeOrderStatus(){
        $postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->service->changeOrderStatus($postData));
        endif;
    }

    public function approveService(){
        $postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->service->approveService($postData));
        endif;
    }

    /* Accept Part Replace */
    public function acceptPartReplace(){
        $data = $this->input->post();
        $this->data['service_id'] = $data['id'];
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"2,4"]);
        $this->load->view($this->partReplace, $this->data);
    }

    public function partReplaceHtml(){
        $data = $this->input->post();
        $result = $this->service->getPartReplaceData(['service_id'=>$data['service_id']]);
        $tbodyData="";$i=1; 
        if(!empty($result)):
            $i=1;
            foreach($result as $row):
                $deleteParam = "{'postData':{'id' : ".$row->id."},'res_function':'resPartReplace','fndelete':'deletePartReplace'}";
                $tbodyData.= '<tr>
                            <td>'.$i++.'</td>
                            <td>'.$row->item_name.'</td>
                            <td>'.$row->qty.'</td>
                            <td>'.$row->price.'</td>
                            <td>'.$row->reason.'</td>
                            <td class="text-center">
							<button type="button" onclick="trash('.$deleteParam.');" class="btn btn-sm btn-outline-danger waves-effect waves-light permission-remove"><i class="mdi mdi-trash-can-outline"></i></button>
						</td>
                    </tr>';
            endforeach;
        else:
            $tbodyData.= '<tr><td colspan="6" style="text-align:center;">No Data Found</td></tr>';
        endif;

        $this->printJson(['status'=>1,'tbodyData'=>$tbodyData]);
    }

    public function savePartReplace(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['item_id']))
            $errorMessage['item_id'] = "Item is required.";
        if(empty($data['qty']))
            $errorMessage['qty'] = "Qty. Date is required.";
        if(empty($data['price']))
            $errorMessage['price'] = "Price is required.";
        if(empty($data['reason']))
            $errorMessage['reason'] = "Reason is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->service->savePartReplace($data));
        endif;
    }

    public function deletePartReplace(){
        $data=$this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->service->deletePartReplace($data['id']));
        endif;
    }

    /* Accept Part Replace */
    public function assignTechnician(){
        $data = $this->input->post();
        $this->data['id'] = $data['id'];
        $this->data['empList'] = $this->employee->getEmployeeList(['emp_role'=>4]);
        $this->load->view($this->assignTechnician, $this->data);
    }

    public function saveAssignTechnician(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['technician_id']))
            $errorMessage['technician_id'] = "Technician is required.";
		
		$serviceData = $this->service->getService(['id'=>$data['id']]);
        if(!empty($serviceData->technician_id)){
            $errorMessage['technician_id'] = "This Service is already accepted.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->service->save($data));
        endif;
    }

    /* Accept Part Replace */
    public function completeService(){
        $data = $this->input->post();
        $this->data['id'] = $data['id'];
        $this->load->view($this->completeService, $this->data);
    }

    public function saveCompleteService(){
        $data = $this->input->post();
        $errorMessage = array();
		if(empty($data['action_detail'])){ $errorMessage['action_detail'] = "Action Taken required."; }
        
        $aft_images = array();
        if($_FILES['aft_images']['name'][0] != null || !empty($_FILES['aft_images']['name'][0])):
            foreach ($_FILES['aft_images']['tmp_name'] as $key => $value):
                $this->load->library('upload');
                $_FILES['userfile']['name']     = $_FILES['aft_images']['name'][$key];
                $_FILES['userfile']['type']     = $_FILES['aft_images']['type'][$key];
                $_FILES['userfile']['tmp_name'] = $_FILES['aft_images']['tmp_name'][$key];
                $_FILES['userfile']['error']    = $_FILES['aft_images']['error'][$key];
                $_FILES['userfile']['size']     = $_FILES['aft_images']['size'][$key];
                
                $imagePath = realpath(APPPATH . '../assets/uploads/service/');
				$fileName = 'AFTER_'.time().'_'.$_FILES['aft_images']['name'][$key];
                $config = ['file_name' => $fileName,'allowed_types' => '*','max_size' => 10240,'overwrite' => TRUE, 'upload_path'=>$imagePath];

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['aft_images'] = $this->upload->display_errors();
                    $this->printJson(["status"=>0,"message"=>$errorMessage]);
                else:
                    $uploadData = $this->upload->data();
                    $aft_images[] = $uploadData['file_name'];
                endif;
            endforeach;
        else:
            unset($data['aft_images']);
        endif; 
        if(!empty($aft_images)):
            $data['aft_images'] = implode(",",$aft_images);
        endif; 

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$data['complete_date'] = date('Y-m-d H:i:s');
            $this->printJson($this->service->save($data));
        endif;
    }

	public function completeServiceDetail(){
        $data = $this->input->post();
        $result = $this->service->getService($data);
        
        $afterImages = "";$beforeImages = "";$partySign = "";
        
        if(!empty($result->aft_images)){
            $afrImage = explode(',',$result->aft_images); 
            foreach($afrImage as $row):
				if(!empty($row)){
					$afterImages .= '<img src="'.base_url("assets/uploads/service/".$row).'" class="img-zoom m-t-10" alt="IMG">';
				}
            endforeach;
        }
        
        if(!empty($result->bfr_images)){
            $bfrImage = explode(',',$result->bfr_images); 
            foreach($bfrImage as $row):
				if(!empty($row)){
					$beforeImages .= '<img src="'.base_url("assets/uploads/service/".$row).'" class="img-zoom m-t-10" alt="IMG">';
				}
            endforeach;
        }
        
        if(!empty($result->party_sign)){
            $partySign .= '<img src="'.base_url("assets/uploads/service/party_sign/".$result->party_sign).'" class="img-zoom m-t-10" alt="IMG">';
        }
        
        echo '<div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th style="width:25%;">Before Image</th>
                    <td style="width:75%;">'.$beforeImages.'</td>
                </tr>
                <tr>
                    <th>Start Date</th>
                    <td>'.((!empty($result->start_date)) ? formatDate($result->start_date,'d-m-Y H:i') : '').'</td>
                </tr>
                <tr>
                    <th>Action taken</th>
                    <td>'.$result->action_detail.'</td>
                </tr>
                <tr>
                    <th>Complete Date</th>
                    <td>'.((!empty($result->complete_date)) ? formatDate($result->complete_date,'d-m-Y H:i') : '').'</td>
                </tr>
                <tr>
                    <th>After Image</th>
                    <td>'.$afterImages.'</td>
                </tr>
                <tr>
                    <th>Customer Sign</th>
                    <td>'.$partySign.'</td>
                </tr>
            </table>
        </div>';
                   
    }

	public function serviceViewFile(){
         $data = $this->input->post();
        $this->data['bfr_images'] = $data['bfr_images'];
        $this->load->view($this->service_view,$this->data);
    }

}
?>