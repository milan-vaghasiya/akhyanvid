<?php
class Dashboard extends MY_Controller{

	private $hbd_msg = 'The warmest wishes to a great member of our team. May your special day be full of happiness, fun and cheer!\r\n-APPLIED AUTO PARTS PVT LTD';
	public function __construct()	{
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Dashboard";
		$this->data['headData']->controller = "dashboard";
	}
	
	public function index(){
		$dashpermission = $this->permission->getDashboardPermission(['emp_id'=>$this->loginId,'is_read'=>1]);
		$this->data['dashboardPermission'] = implode(",",array_column($dashpermission,'sys_class'));
        $this->load->view('dashboard',$this->data);
    }

	public function getPendingQuotation(){
		$data = $this->input->post();
		$result = $this->dashboard->getPendingQuotation($data);
		$this->printJson(['status'=>1,'totalPendingQuotation'=>$result]);
	}

	public function getOnGoingProjects(){
		$data = $this->input->post();
		$result = $this->dashboard->getOnGoingProjects($data);
		$this->printJson(['status'=>1,'totalOnGoingProjects'=>$result->totalOnGoingProjects]);
	}

	public function getPendingServices(){
		$data = $this->input->post();
		$result = $this->dashboard->getPendingServices($data);
		$this->printJson(['status'=>1,'totalPendingServices'=>$result->totalPendingServices]);
	}

	public function getPendingComplaint(){
		$data = $this->input->post();
		$result = $this->dashboard->getPendingComplaint($data);
		$this->printJson(['status'=>1,'totalPendingComplaint'=>$result->totalPendingComplaint]);
	}

	public function getPendingTask(){
		$data = $this->input->post();
		$result = $this->dashboard->getPendingTask($data);
		$this->printJson(['status'=>1,'totalPendingTask'=>$result->totalPendingTask]);
	}
}
?>