<?php
class permission extends MY_Controller{
    private $modualPermission = "permission/emp_permission";
    private $reportPermission = "permission/emp_permission_report";
    private $dashboardPermission = "permission/dashboard_permission";
    private $copyPermission = "permission/copy_permission";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "User Permission";
		$this->data['headData']->controller = "permission";
        $this->data['headData']->pageUrl = "permission";
	}

    public function index(){   
        $this->data['menu_type'] = 1;     
        $this->data['empList'] = $this->employee->getEmployeeList();
        $this->data['permission'] = $this->permission->getPermission();
        $this->load->view($this->modualPermission,$this->data);
    }

    public function reportPermission(){
        $this->data['empList'] = $this->employee->getEmployeeList();
        $this->data['permission'] = $this->permission->getPermission(1);
        $this->load->view($this->reportPermission,$this->data);
    }    

    public function copyPermission(){
        $this->data['fromList'] = $this->employee->getEmployeeList();
        $this->data['toList'] = $this->employee->getEmployeeList();
        $this->load->view($this->copyPermission,$this->data);
    }

    public function editPermission(){
        $emp_id = $this->input->post('emp_id');
        $menu_type = $this->input->post('menu_type');
        $this->printJson($this->permission->editPermission($emp_id,$menu_type));
    }

    public function savePermission(){
        $data = $this->input->post();
        $errorMessage = array();
        
        if(empty($data['emp_id']))
            $errorMessage['emp_id'] = "Employee name is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->permission->save($data));
        endif;
    }

    public function saveCopyPermission(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['from_id']))
            $errorMessage['from_id'] = "From User is required.";
        if(empty($data['to_id']))
            $errorMessage['to_id'] = "To User is required.";
        
        if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:                        
            $this->printJson($this->permission->saveCopyPermission($data));
        endif;
    }

    public function dashboardPermission(){
        $this->data['empList'] = $this->employee->getEmployeeList(['all'=>1]);
        $this->data['dashboardPermission'] = $this->permission->getDashboardWidget();
        $this->load->view($this->dashboardPermission,$this->data);
    }

    public function editDashboardPermission(){
        $data = $this->input->post();
        $empPermission = $this->permission->getDashboardPermission($data);
        $this->printJson(['status' => 1, 'message' => 'Record Found', 'empPermission' => $empPermission]);
    }

    public function saveDashboardPermission(){
        $data = $this->input->post();
        $errorMessage = array();
        
        if(empty($data['emp_id']))
            $errorMessage['emp_id'] = "Employee name is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->permission->saveDashboardPermission($data));
        endif;
    }

    public function appPermission(){        
        $this->data['empList'] = $this->employee->getEmployeeList(['all'=>1]);
        $this->data['permission'] = $this->permission->getPermission(0,2);
        $this->data['menu_type'] = 2;
        $this->load->view($this->modualPermission,$this->data);
    }
}
?>