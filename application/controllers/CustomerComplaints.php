<?php
class CustomerComplaints extends MY_Controller
{
    private $indexPage = "customer_complaints/index";
    private $formPage = "customer_complaints/form";
    private $solution_form = "customer_complaints/solution_form";
    private $complaint_view = "customer_complaints/complaint_view";
    private $service_request = "customer_complaints/service_request";
   
    public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Customer Complaints";
		$this->data['headData']->controller = "customerComplaints";
		$this->data['headData']->pageUrl = "customerComplaints";
	}
	
	public function index(){
        $this->data['tableHeader'] = getSalesDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 1){
        $data = $this->input->post(); $data['status'] = $status;
        $result = $this->customerComplaints->getDTRows($data);
        $sendData = array();$i=1;
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getCustomerComplaintsData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addCustomerComplaints(){
        $this->data['projectList'] = $this->project->getProjectData();
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if($data['status'] == 1){
            if (empty($data['date']))
                $errorMessage['date'] = " Complaint Date is required.";

            if (empty($data['project_id']))
                $errorMessage['project_id'] = "Project is required.";
        }else{
            if(empty($data['solution']))
                $errorMessage['solution'] = "Solution is required.";
        }
	

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
             if(!empty($_FILES['complaint_file']['name'][0])):
                $file_upload = array(); $f=1;
                if($_FILES['complaint_file']['name'][0] != null || !empty($_FILES['complaint_file']['name'][0])):
                    $this->load->library('upload');
                    foreach ($_FILES['complaint_file']['tmp_name'] as $key => $value):
                        $_FILES['userfile']['name']     = $_FILES['complaint_file']['name'][$key];
                        $_FILES['userfile']['type']     = $_FILES['complaint_file']['type'][$key];
                        $_FILES['userfile']['tmp_name'] = $_FILES['complaint_file']['tmp_name'][$key];
                        $_FILES['userfile']['error']    = $_FILES['complaint_file']['error'][$key];
                        $_FILES['userfile']['size']     = $_FILES['complaint_file']['size'][$key];
                        
                        $imagePath = realpath(APPPATH . '../assets/uploads/cust_complaint/');
                        $fileName = $_FILES['complaint_file']['name'][$key];
                        $config = ['file_name' => $fileName,'allowed_types' => '*','max_size' => 10240,'overwrite' => TRUE, 'upload_path'=>$imagePath];
        
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload()):
                            $errorMessage['complaint_file'] = $this->upload->display_errors();
                            $this->printJson(["status"=>0,"message"=>$errorMessage]);
                        else:
                            $uploadData = $this->upload->data();
                            $file_upload[] = $uploadData['file_name'];
                        endif;
                        $f++;
        			endforeach;
        			if(!empty($file_upload)):
        			    $data['complaint_file'] = implode(",",$file_upload);
            		endif; 
    			endif;
            endif;

            if(!empty($data['complaint_file'])){
                $data['complaint_file'] = $data['complaint_file'];
            }
           
            $this->printJson($this->customerComplaints->save($data));
        endif;
    }

	public function edit(){
        $data = $this->input->post(); 
        $this->data['projectList'] = $this->project->getProjectData();
        $this->data['dataRow'] = $dataRow = $this->customerComplaints->getCustomerComplaints(['id'=>$data['id']]);
        $this->load->view($this->formPage,$this->data);
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->customerComplaints->delete($data));
        endif;
    }

    public function complaintSolution(){
        $data = $this->input->post();
        $this->data['dataRow'] = $dataRow = $this->customerComplaints->getCustomerComplaints(['id'=>$data['id']]);
        $this->load->view($this->solution_form,$this->data);
    }

    public function complaintViewFile(){
         $data = $this->input->post();
        $this->data['complaint_file'] = $data['complaint_file'];

        $this->load->view($this->complaint_view,$this->data);
    }
    
	// Service Request 
    public function addServiceRequest(){
         $data = $this->input->post();
        $this->data['trans_prefix'] = 'S'.n2y(date("Y")).n2m(date("m"));
        $this->data['trans_no'] = $this->service->getNextServiceNo($this->data['trans_prefix']);
        $this->data['trans_number'] = $this->data['trans_prefix'].lpad($this->data['trans_no'],3);
        $this->data['projectList'] = $this->project->getProjectData();
		$this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'el_model'=>1]);
        $this->data['dataRow'] = $dataRow = $this->customerComplaints->getCustomerComplaints(['id'=>$data['id']]);
        $this->load->view($this->service_request,$this->data);
    }
    
    public function saveServiceReq(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['trans_date']))
            $errorMessage['trans_date'] = "Service Date is required.";
        if(empty($data['project_id']))
            $errorMessage['project_id'] = "Project is required.";  
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->service->save($data));
        endif;
    }
    
	
}
?>