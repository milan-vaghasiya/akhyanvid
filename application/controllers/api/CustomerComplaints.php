<?php
class CustomerComplaints extends MY_ApiController{

	public function __construct(){
        parent::__construct();   
        $this->data['headData']->pageTitle = "Customer Complaints";	
	}

	public function getComplaintList(){
        $data = $this->input->post();	
		$errorMessage = array();

		if(empty($data['status'])){
            $errorMessage['status'] = "Status is required.";
		}
        if(!empty($errorMessage)){
            $this->printJson(['status' => 0,'message' => $errorMessage]);
		}
        else{
			$complaintRec = [];
			$complaintsData = $this->customerComplaints->getCustomerComplaintsData($data);

			if(!empty($complaintsData)){
				foreach ($complaintsData as $row) {
					$complaintRec[] = array(
						'id' => $row->id,
						'date' => date('d-m-Y',strtotime($row->date)),
						'project_name' => $row->project_name,
						'voice_note' => (!empty($row->voice_note) ? base_url('assets/uploads/cust_complaint/'.$row->voice_note) : ''),
						'remark' => $row->remark,
					);
				}								
				$this->printJson(['status' => 1, 'message' => 'Data Found', 'data' => $complaintRec]);
			} else{
				$this->printJson(['status' => 0, 'message' => 'Data Not Found', 'data' => []]);
			}
		}
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
			
			if(isset($_FILES['voice_note'])):
				if($_FILES['voice_note']['name'] != null || !empty($_FILES['voice_note']['name'])):
					$this->load->library('upload');
					$_FILES['userfile']['name']     = $_FILES['voice_note']['name'];
					$_FILES['userfile']['type']     = $_FILES['voice_note']['type'];
					$_FILES['userfile']['tmp_name'] = $_FILES['voice_note']['tmp_name'];
					$_FILES['userfile']['error']    = $_FILES['voice_note']['error'];
					$_FILES['userfile']['size']     = $_FILES['voice_note']['size'];
					
					$imagePath = realpath(APPPATH . '../assets/uploads/cust_complaint/');
					$ext = pathinfo($_FILES['voice_note']['name'], PATHINFO_EXTENSION);
					$config = ['file_name' => date("Y_m_d_H_i_s").".".$ext, 'allowed_types' => '*', 'max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

					$this->upload->initialize($config);
					if (!$this->upload->do_upload()):
						$errorMessage['voice_note'] = $this->upload->display_errors();
					else:
						$uploadData = $this->upload->data();
						$data['voice_note'] = $uploadData['file_name'];
					endif;
				endif;
			endif;

            if(!empty($data['complaint_file'])){$data['complaint_file'] = $data['complaint_file'];}
            if(!empty($data['voice_note'])){$data['voice_note'] = $data['voice_note'];}
           
            $this->printJson($this->customerComplaints->save($data));
        endif;
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->customerComplaints->delete($data));
        endif;
    }
}
?>