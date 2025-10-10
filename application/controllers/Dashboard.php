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
		//$dashpermission = $this->permission->getDashboardPermission(['emp_id'=>$this->loginId,'is_read'=>1]);
		$this->data['dashboardPermission'] = "";//implode(",",array_column($dashpermission,'sys_class'));
        $this->load->view('dashboard',$this->data);
    }

	public function getRevenue(){
		$data = $this->input->post();
		$result = $this->dashboard->getRevenue($data);
		$totalRevenue = convertToShortNumber($result->total_revenue);
		$this->printJson(['status'=>1,'totalRevenue'=>$totalRevenue['value']." ".$totalRevenue['format']]);
	}

	public function getOrderAvgValue(){
		$data = $this->input->post();
		$result = $this->dashboard->getOrderAvgValue($data);
		$orderAvgValue = moneyFormatIndia(round($result->ord_avg_value));
		$this->printJson(['status'=>1,'orderAvgValue'=>$orderAvgValue]);
	}

	public function getTodayOrder(){
		$data = $this->input->post();
		$result = $this->dashboard->getTodayOrder($data);
		$todayOrders = intval($result->today_orders);
		$this->printJson(['status'=>1,'todayOrders'=>$todayOrders]);
	}

	public function getConversionRate(){
		$data = $this->input->post();
		$result = $this->dashboard->getConversionRate($data);
		$conversionRate = round($result->conversion_rate,2);
		$this->printJson(['status'=>1,'conversionRate'=>$conversionRate]);
	}

	public function getOutstanding(){
		$data = $this->input->post();
		$result = $this->dashboard->getOutstanding($data);
		$receivable = convertToShortNumber($result->receivable);
		$payable = convertToShortNumber($result->payable);
		$this->printJson(['status'=>1,'receivable'=>$receivable['value']." ".$receivable['format'],'payable'=>$payable['value']." ".$payable['format']]);
	}

	public function getIncomeVsExpense(){
		$data = $this->input->post();
        
		$data['vou_name_s'] = "'Sale','GInc'";
		$income = $this->dashboard->getMonthWiseSummary($data);

		$data['vou_name_s'] = "'Purc','GExp'";
		$expense = $this->dashboard->getMonthWiseSummary($data);

		$monthList = $incomAmount = $expenseAmount = [];

		foreach($income as $row):
			$monthList[] = $row->month_name;
			$incomAmount[] = round($row->total_taxable_amount,2);
		endforeach;

		foreach($expense as $row):
			$expenseAmount[] = round($row->total_taxable_amount,2);
		endforeach;
		$this->printJson(['status'=>1,'monthList'=>$monthList,'income'=>$incomAmount,'expense'=>$expenseAmount]);
	}

	public function getTopSellingStateList(){
		$data = $this->input->post();
		$result = $this->dashboard->getTopSellingStateList($data);
		$this->printJson(['status'=>1,'stateList'=>$result]);
	}

	public function getTopSellingCustomerList(){
		$data = $this->input->post();
		$result = $this->dashboard->getTopSellingCustomerList($data);
		$this->printJson(['status'=>1,'customerList'=>$result]);
	}

	public function getTopSellingProductList(){
		$data = $this->input->post();
		$result = $this->dashboard->getTopSellingProductList($data);
		$this->printJson(['status'=>1,'productList'=>$result]);
	}

	public function getProductCategoryList(){
		$data = $this->input->post();
		$result = $this->dashboard->getProductCategoryList($data);
		$this->printJson(['status'=>1,'categoryList'=>$result]);
	}
}
?>