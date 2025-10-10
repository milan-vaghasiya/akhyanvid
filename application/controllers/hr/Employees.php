<?php
class Employees extends MY_Controller{
    private $indexPage = "hr/employee/index";
    private $employeeForm = "hr/employee/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Employees";
		$this->data['headData']->controller = "hr/employees";   
        $this->data['headData']->pageUrl = "hr/employees";
	}

    public function index(){        
        $this->data['tableHeader'] = getHrDtHeader('employees');
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status=0){
        $data = $this->input->post(); $data['status']=$status;
        $result = $this->employee->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
		foreach($result['data'] as $row):
			$row->sr_no = $i++;
            $row->emp_role = $this->empRole[$row->emp_role];
			$sendData[] = getEmployeeData($row);
		endforeach;
		
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addEmployee(){
        $this->load->view($this->employeeForm,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['emp_code']))
            $errorMessage['emp_code'] = "Employee Code is required.";
        if(empty($data['emp_name']))
            $errorMessage['emp_name'] = "Employee name is required.";
       
        if(empty($data['emp_mobile_no']))
            $errorMessage['emp_mobile_no'] = "Contact No. is required.";
		if(empty($data['emp_role']))
            $errorMessage['emp_role'] = "Role is required.";
		

        if(empty($data['id'])):
            $data['emp_password'] = "123456";
        endif;
				
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->employee->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->employee->getEmployeeList(['id'=>$data['id'],'single_row'=>1]);
        $this->load->view($this->employeeForm,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->employee->delete($id));
        endif;
    }

    public function activeInactive(){
        $postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->employee->activeInactive($postData));
        endif;
    }
    
    public function changePassword(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['old_password']))
            $errorMessage['old_password'] = "Old Password is required.";
        if(empty($data['new_password']))
            $errorMessage['new_password'] = "New Password is required.";
        if(empty($data['cpassword']))
            $errorMessage['cpassword'] = "Confirm Password is required.";
        if(!empty($data['new_password']) && !empty($data['cpassword'])):
            if($data['new_password'] != $data['cpassword'])
                $errorMessage['cpassword'] = "Confirm Password and New Password is Not match!.";
        endif;

        if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:
            $data['id'] = $this->loginId;
			$result =  $this->employee->changePassword($data);
			$this->printJson($result);
		endif;
    }

    public function resetPassword(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->employee->resetPassword($data['id']));
        endif;
    }


}
?>