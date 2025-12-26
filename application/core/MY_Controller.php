<?php 
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class MY_Controller extends CI_Controller{

	public $TERMS_TYPES = ["23" => "Sales Quotation", "24" => "Sales Order"];
	public $gstPer = ['0'=>"NILL",'0.10'=>'0.10 %','0.25'=>"0.25 %",'1'=>"1 %",'3'=>"3%",'5'=>"5 %","6"=>"6 %","7.50"=>"7.50 %",'12'=>"12 %",'18'=>"18 %",'28'=>"28 %"];
	public $empRole = ["1"=>"Management","2"=>"Office Staff","3"=>"Sales Executive","4"=>"Technician","5"=>"Site Manager"];

	public $partyCategory = [1=>'Customer',2=>'Supplier',3=>'Vendor',4=>'Director',5=>'Ledger'];

	public $itemTypes = [1 => "Finish Goods", 2 => "Consumable", 3 => "Raw Material", 4 => 'Semi Finish', 5 => "Machineries", 10 => "Service Items"];

	//Types of Invoice

	public $appointmentMode = [1 => "Phone", 2 => "Email", 3 => "Visit", 4 => "Other"];
	public $iconClass = ['','far fa-check-circle bg-soft-success','far fa-bell bg-soft-danger','fas fa-comment-dots bg-soft-info','mdi mdi-help-circle bg-soft-primary','','mdi mdi-file-document bg-soft-warning','mdi mdi-shopping bg-soft-success','mdi mdi-account-cancel bg-soft-tumblr','mdi mdi-account-check bg-soft-primary','fa fa-smile-o bg-soft-info','mdi mdi-emoticon-sad bg-soft-dark','fa fa-refresh bg-soft-secondry'];

	// Project Specification
	public $specArray = [1 =>"DB Location",2 => "DB Height", 3=>"DB Width", 4=>"Cabel Junction Space", 5=>"Rack Location", 6=>"QSM Location", 7=>"Keypad Location", 8=>"Gangbox Location",9=>"Ups Location",10=> "DALI System", 11=>"Special Notes"];

	public function __construct(){
		parent::__construct();
		//echo '<br><br><hr><h1 style="text-align:center;color:red;">We are sorry!<br>Your ERP is Updating New Features</h1><hr><h2 style="text-align:center;color:green;">Thanks For Co-operate</h1>';exit;
		$this->isLoggedin();
		$this->data['headData'] = new StdClass;
		$this->load->library('form_validation');
		$this->load->library('fcm');
		
		$this->load->model('masterModel');
		$this->load->model('DashboardModel','dashboard');
		$this->load->model('PermissionModel','permission');
		$this->load->model('TermsModel','terms');
		
		/* Store Models */
		$this->load->model("GateInwardModel","gateInward");
		$this->load->model("StoreModel","store");

		/* Configration Models */
		$this->load->model("SelectOptionModel","selectOption");
		$this->load->model("WorkInstructionsModel","workInstructions");

		/* HR Models */
		$this->load->model("hr/EmployeeModel","employee");
		$this->load->model('TaskManagerModel', 'taskManager');


		/* Master Model */
		$this->load->model('ItemCategoryModel','itemCategory');
		$this->load->model('ItemModel','item');
		$this->load->model('PartyModel','party');

	

		/* Report Model */
		$this->load->model('report/StoreReportModel','storeReport'); 
		$this->load->model('report/PurchaseReportModel','purchaseReport');
		$this->load->model('report/SalesReportModel','salesReport');

		/* Sales Model */
		$this->load->model('SalesQuotationModel','salesQuotation');
		$this->load->model('ProjectModel','project');
		$this->load->model('ServiceModel','service'); 
		$this->load->model('WorkProgressModel','workProgress'); 
		$this->load->model('CustomerComplaintsModel','customerComplaints');

		
		$this->setSessionVariables(["masterModel", "dashboard", "permission", "employee", "itemCategory", "item","party","salesQuotation","project","terms","gateInward","store","service","selectOption","storeReport","salesReport","workProgress","workInstructions","customerComplaints","taskManager"]);
	}

	public function setSessionVariables($modelNames){
		$this->data['dates'] = $this->dates = explode(' AND ',$this->session->userdata('financialYear'));
        $this->data['shortYear'] = $this->shortYear = date('y',strtotime($this->dates[0])).'-'.date('y',strtotime($this->dates[1]));
		$this->data['startYear'] = $this->startYear = date('Y',strtotime($this->dates[0]));
		$this->data['endYear'] = $this->endYear = date('Y',strtotime($this->dates[1]));
		$this->data['startYearDate'] = $this->startYearDate = date('Y-m-d',strtotime($this->dates[0]));
		$this->data['endYearDate'] = $this->endYearDate = date('Y-m-d',strtotime($this->dates[1]));

		$this->loginId = $this->session->userdata('loginId');
		$this->userCode = $this->session->userdata('emp_code');
		$this->userName = $this->session->userdata('emp_name');
		$this->userRole = $this->session->userdata('role');
		$this->userRoleName = $this->session->userdata('roleName');
		$this->partyId = $this->session->userdata('party_id');

		$models = $modelNames;
		foreach($models as $modelName):
			$modelName = trim($modelName);
			$this->{$modelName}->dates = $this->dates;
			$this->{$modelName}->shortYear = $this->shortYear;
			$this->{$modelName}->startYear = $this->startYear;
			$this->{$modelName}->endYear = $this->endYear;
			$this->{$modelName}->startYearDate = $this->startYearDate;
			$this->{$modelName}->endYearDate = $this->endYearDate;

			$this->{$modelName}->loginId = $this->loginId;
			$this->{$modelName}->userCode = $this->userCode;
			$this->{$modelName}->userName = $this->userName;
			$this->{$modelName}->userRole = $this->userRole;
			$this->{$modelName}->userRoleName = $this->userRoleName;
			$this->{$modelName}->partyId = $this->partyId;
		endforeach;
		return true;
	}
	
	public function isLoggedin(){
		if(!$this->session->userdata("loginId")):
			echo '<script>window.location.href="'.base_url().'";</script>';
		endif;
		return true;
	}
	
	public function printJson($data){
		print json_encode($data);exit;
	}
	
	public function checkGrants($url){
		$empPer = $this->session->userdata('emp_permission');
		if(!array_key_exists($url,$empPer)):
			redirect(base_url('error_403'));
		endif;
		return true;
	}
	
	/**** Generate QR Code ****/
	public function getQRCode($qrData,$dir,$file_name){
		if(isset($qrData) AND isset($file_name)):
			$file_name .= '.png';
			/* Load QR Code Library */
			$this->load->library('ciqrcode');
			
			if (!file_exists($dir)) {mkdir($dir, 0775, true);}

			/* QR Configuration  */
			$config['cacheable']    = true;
			$config['imagedir']     = $dir;
			$config['quality']      = true;
			$config['size']         = '1024';
			$config['black']        = array(255,255,255);
			$config['white']        = array(255,255,255);
			$this->ciqrcode->initialize($config);
	  
			/* QR Data  */
			$params['data']     = $qrData;
			$params['level']    = 'L';
			$params['size']     = 10;
			$params['savename'] = FCPATH.$config['imagedir']. $file_name;
			
			$this->ciqrcode->generate($params);

			return $dir. $file_name;
		endif;

		return false;
	}

	public function getTableHeader(){
		$data = $this->input->post();

		$response = call_user_func_array($data['hp_fn_name'],[$data['page']]);
		
		$result['theads'] = (isset($response[0])) ? $response[0] : '';
		$result['textAlign'] = (isset($response[1])) ? $response[1] : '';
		$result['srnoPosition'] = (isset($response[2])) ? $response[2] : 1;
		$result['sortable'] = (isset($response[3])) ? $response[3] : '';

		$this->printJson(['status'=>1,'data'=>$result]);
	}

	public function getPartyDetails(){
        $data = $this->input->post();
		$data['single_row'] = 1;
        $partyDetail = $this->party->getPartyList($data);
        $gstDetails = [];//$this->party->getPartyGSTDetail(['party_id'=>$data['id']]);
		$shipToDetails = [];//$this->party->getPartyDeliveryAddressDetails(['party_id'=>$data['id']]);
        $this->printJson(['status'=>1,'data'=>['partyDetail'=>$partyDetail,'gstDetails'=>$gstDetails,'shipToDetails'=>$shipToDetails]]);
    }

	public function getItemDetail(){
		$data = $this->input->post();
		$itemDetail = $this->item->getItem($data);

		if(empty($itemDetail)):
			$this->printJson(['status'=>0,'message'=>'Item Not Found.']);
		else:
			$this->printJson(['status'=>1,'data'=>['itemDetail'=>$itemDetail]]);
		endif;
	}

	public function getPartyInvoiceList(){
        $data = $this->input->post();
        $this->printJson($this->transMainModel->getPartyInvoiceList($data));
    }

	public function getVillageList(){
		$data = $this->input->post();
		$this->printJson($this->party->getVillageList($data));
	}

	public function getProjectAgencyList($postData = []){
		$data = (!empty($postData))?$postData:$this->input->post();
		$result = $this->project->getProjectAgencyList($data);
		if(!empty($postData)):
			return $result;
		else:
			$this->printJson(['status'=>1,'agencyList'=>$result]);
		endif;
	}

	public function getNextTransNo(){
		$data = $this->input->post();
		$nextNo = $this->transMainModel->nextTransNo($data['entry_type'],0,"",$data['cm_id']);
		$this->printJson(['status'=>1,'next_no'=>$nextNo]);
	}

	public function getAccountSummaryHtml(){
        $data = $this->input->post();
		$taxClass = $this->taxClass->getTaxClass($data['tax_class_id']);

        $this->data['taxList'] = (!empty($taxClass->tax_ids))?$this->taxMaster->getTaxList(['tax_ids'=>$taxClass->tax_ids,'is_active'=>((!empty($data['taxSummary']))?0:1)]):array();
        $this->data['expenseList'] = (!empty($taxClass->expense_ids))?$this->expenseMaster->getExpenseList(['expense_ids'=>$taxClass->expense_ids,'is_active'=>((!empty($data['taxSummary']))?0:1)]):array();
        $this->data['ledgerList'] = $this->party->getPartyList(['group_code'=>["'DT'","'ED'","'EI'","'ID'","'II'"]]);
		
		$dataRow = (!empty($data['taxSummary']))?$data['taxSummary']:array();
        $this->data['dataRow'] = (object) $dataRow;
        $this->load->view('includes/tax_summary',$this->data);
    }

	public function trashFiles(){
        /** define the directory **/
        $dirs = [
            realpath(APPPATH . '../assets/uploads/qr_code/'),
            realpath(APPPATH . '../assets/uploads/import_excel/'),
            realpath(APPPATH . '../assets/uploads/invoice/'),
            realpath(APPPATH . '../assets/uploads/gst_report/'),
            realpath(APPPATH . '../assets/uploads/tcs_report/'),
            /* realpath(APPPATH . '../assets/uploads/eway_bill/'),
            realpath(APPPATH . '../assets/uploads/eway_bill_detail/'),
            realpath(APPPATH . '../assets/uploads/e_inv/') */
        ];

        foreach($dirs as $dir):
            $files = array();
            $files = scandir($dir);
            unset($files[0],$files[1]);

            /*** cycle through all files in the directory ***/
            foreach($files as $file):
                /*** if file is 24 hours (86400 seconds) old then delete it ***/
                if(time() - filectime($dir.'/'.$file) > 86400):
                    unlink($dir.'/'.$file);
                    //print_r(filectime($dir.'/'.$file)); print_r("<hr>");
                endif;
            endforeach;
        endforeach;

        return true;
    }

	public function getMonthListFY(){
		$monthList = array();
		$start    = (new DateTime($this->startYearDate))->modify('first day of this month');
        $end      = (new DateTime($this->endYearDate))->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        $i=0;
        foreach ($period as $dt):
            $monthList[$i]['val'] = $dt->format("Y-m-d");
            $monthList[$i++]['label'] = $dt->format("F-Y");
		endforeach;
		return $monthList;
	}
	
	public function callcURL($param = []){
	    $response = new StdClass;
	    if(isset($param['callURL']) AND (!empty($param['callURL'])))
	    {
    	    $curl = curl_init();
    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $param['callURL'],
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
	    }
        return $response;
	}
	
	public function getStatesOptions($postData=array()){
        $country_id = (!empty($postData['country_id']))?$postData['country_id']:$this->input->post('country_id');

        $result = $this->party->getStates(['country_id'=>$country_id]);

        $html = '<option value="">Select State</option>';
        foreach ($result as $row) :
            $selected = (!empty($postData['state_id']) && $row->id == $postData['state_id']) ? "selected" : "";
            $html .= '<option value="' . $row->id . '" ' . $selected . '>' . $row->name . '</option>';
        endforeach;

        if(!empty($postData)):
            return $html;
        else:
            $this->printJson(['status'=>1,'result'=>$html]);
        endif;
    }
	
		public function generatePDF($pdfData,$orientation){
        $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
        // $this->data['letter_head'] = $letter_head = base_url('assets/images/'.$companyData->company_letterhead);

		$htmlFooter = ' <htmlpagefooter name="lastpage">
							<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
								<tr>
									<td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
									<td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
								</tr>
							</table>
						 </htmlpagefooter>
						<sethtmlpagefooter name="lastpage" value="on" /> ';

        $pdfData = $pdfData.$htmlFooter; 

		$mpdf = new \Mpdf\Mpdf();
        $pdfFileName =  'report_pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
		$mpdf->AddPage($orientation,'','','','',5,5,5,15,5,5,'','','','','','','','','','A4-'.$orientation);
        $mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName, 'I');
    }

}
?>