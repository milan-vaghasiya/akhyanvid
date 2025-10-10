<?php 
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class MY_Controller extends CI_Controller{

	public $termsTypeArray = ["Purchase","Sales"];
	public $gstPer = ['0'=>"NILL",'0.10'=>'0.10 %','0.25'=>"0.25 %",'1'=>"1 %",'3'=>"3%",'5'=>"5 %","6"=>"6 %","7.50"=>"7.50 %",'12'=>"12 %",'18'=>"18 %",'28'=>"28 %"];
	public $deptCategory = ["1"=>"Admin","2"=>"HR","3"=>"Purchase","4"=>"Sales","5"=>"Store","6"=>"QC","7"=>"General","8"=>"Machining"];
	public $empRole = ["1"=>"Admin","2"=>"Production Manager","3"=>"Accountant","4"=>"Sales Manager","5"=>"Purchase Manager","6"=>"Employee","7"=>"Client"];
    public $gender = ["M"=>"Male","F"=>"Female","O"=>"Other"];
    public $systemDesignation = [1=>"Machine Operator",2=>"Line Inspector",3=>"Setter Inspector",4=>"Process Setter",5=>"FQC Inspector",6=>"Sale Executive",7=>"Designer",8=>"Production Executive"];
	public $maritalStatus = ["Married","UnMarried","Widow"];
	public $empType = [1=>"Permanent (Fix)",2=>"Permanent (Hourly)",3=>"Temporary"];
	public $empGrade = ["Grade A","Grade B","Grade C","Grade D"];
	//public $paymentMode = ['CASH','CHEQUE','NEFT','UPI'];
	public $paymentMode = ['CASH','CHEQUE','NEFT/RTGS/IMPS ','CARD','UPI'];

	public $partyCategory = [1=>'Customer',2=>'Supplier',3=>'Vendor',4=>'Ledger'];
	public $suppliedType = [1=>'Goods',2=>'Services',3=>'Goods & Services'];
	public $gstRegistrationTypes = [1=>'Registerd',2=>'Composition',3=>'Overseas',4=>'Un-Registerd'];
	public $automotiveArray = ["1" => 'Yes', "2" => "No"];
	public $vendorTypes = ['Manufacture', 'Service'];

	public $itemTypes = [1 => "Finish Goods", 2 => "Consumable", 3 => "Raw Material", 5 => "Machineries" , 4 => "Semi Finish", 9 => "Packing Material"/*, 6 => "Instruments", 7 => "Gauges", 8 => "Service Items", 9 => "Packing Material", 10 => "Scrap" */]; // 20-02-2024
	public $stockTypes = [0=>"None",1=>'Batch Wise',2=>"Serial Wise"];
	public $fgColorCode = ["WHITE"=>"W","GREY"=>"G"];
	public $fgCapacity = ["3 TON"=>"3T","5 TON"=>"5T"];

	//Crm Status
	public $leadFrom = ["Facebook","Indiamart","Instagram","Facebook Comments","Trade India","Exporter India","Facebook Admanager"];
	public $leadStatus = ["Initited", "Appointment Fixed", "Qualified", "Enquiry Generated", "Proposal", "In Negotiation", "Confirm", "Close"];
	public $appointmentMode = [1 => "Phone", 2 => "Email", 3 => "Visit", 4 => "Other"];
	public $followupStage = [0 => 'Open', 1 => "Confirmed", 2 => "Hold", 3 => "Won", 4 => "Lost", 5 => "Enquiry" , 6 => "Quatation"];

	//Types of Invoice
	public $purchaseTypeCodes = ["'PURGSTACC'","'PURIGSTACC'","'PURJOBGSTACC'","'PURJOBIGSTACC'","'PURURDGSTACC'","'PURURDIGSTACC'","'PURTFACC'","'PUREXEMPTEDTFACC'","'IMPORTACC'","'IMPORTSACC'","'SEZRACC'"/* ,"'SEZSGSTACC'","'SEZSTFACC'","'DEEMEDEXP'" */];

	public $salesTypeCodes = ["'SALESGSTACC'","'SALESIGSTACC'","'SALESJOBGSTACC'","'SALESJOBIGSTACC'","'SALESTFACC'","'SALESEXEMPTEDTFACC'","'EXPORTGSTACC'","'EXPORTTFACC'","'SEZSGSTACC'","'SEZSTFACC'","'DEEMEDEXP'"];

	public $taxClassCodes = [
		1 => [
			'PURGSTACC' => 'Local',
			'PURIGSTACC' => 'Central',
			'PURJOBGSTACC' => 'Jobwork Local',
			'PURJOBIGSTACC' => 'Jobwork Central',
			'PURURDGSTACC' => 'URD Local',
			'PURURDIGSTACC' => 'URD Central',
			'PURTFACC' => 'Local Tax Free',
			'PURCTFACC' => 'Central Tax Free',
			'PUREXEMPTEDTFACC' => 'Local Exempted (Nill Rated)',
			'PURCEXEMPTEDTFACC' => 'Central Exempted (Nill Rated)',
			'PURNONGST' => 'Local Non GST',
			'PURCNONGST' => 'Central Non GST',
			'IMPORTACC' => 'Import',
			'IMPORTSACC' => 'Import Services',
			'SEZRACC' => 'Received SEZ'
		],
		2 => [
			'SALESGSTACC' => 'Local',
			'SALESIGSTACC' => 'Central',
			'SALESJOBGSTACC' => 'Jobwork Local',
			'SALESJOBIGSTACC' => 'Jobwork Central',
			'SALESTFACC' => 'Local Tax Free',
			'SALESCTFACC' => 'Central Tax Free',
			'SALESEXEMPTEDTFACC' => 'Local Exempted (Nill Rated)',
			'SALESCEXEMPTEDTFACC' => 'Central Exempted (Nill Rated)',
			'SALESNONGST' => 'Local Non GST',
			'SALESCNONGST' => 'Central Non GST',
			'EXPORTGSTACC' => 'Export With Payment',
			'EXPORTTFACC' => "Export Without Payment",
			'SEZSGSTACC' => 'SEZ With Payment',
			'SEZSTFACC' => 'SEZ Without Payment',
			'DEEMEDEXP' => 'Deemed Export'
		]
	];
	
	public $stockTransTYpe = [
		'OPS' => 'OPENING STOCK',
		'GRN' => 'GRN',
		'SSI' => 'STORE STOCK ISSUE',
		'SSR' => 'STORE STOCK RETURN',
		'PRD' => 'PRODUCTION',
		'PMR' => 'PRODUCTION MATERIAL RETURN',
		'FIR' => 'FIR',
		'PCK' => 'PACKING',
		'PCK' => 'PACKING',
		'PPK' => 'Primary Packing',
		'FPK' => 'Final Packing',
		'STR' => 'STOCK TRANSFER',
		'CNV' => 'PRODUCT CONVERSION',
		'DLC' => 'DELIVERY CHALLAN',
		'INV' => 'SALES TAX INVOICE',
		'PUR' => 'PURCHASE INVOICE',
		'SVR' => 'STOCK VERIFICATION',
		'FPK' => 'FINAL PALLET PACKING',
		'CRD' => 'CREDIT NOTE',
		'DBT' => 'DEBIT NOTE',
		'EST' => 'ESTIMATE',
	];
	public function __construct(){
		parent::__construct();
		//echo '<br><br><hr><h1 style="text-align:center;color:red;">We are sorry!<br>Your ERP is Updating New Features</h1><hr><h2 style="text-align:center;color:green;">Thanks For Co-operate</h1>';exit;
		$this->isLoggedin();
		$this->data['headData'] = new StdClass;
		$this->load->library('form_validation');
		
		$this->load->model('masterModel');
		$this->load->model('DashboardModel','dashboard');
		$this->load->model('PermissionModel','permission');
		$this->load->model('StockTransModel','itemStock');
		$this->load->model('EbillModel','ebill');

		/* Configration Models */
		$this->load->model("TermsModel","terms");
		$this->load->model("TransportModel","transport");
		$this->load->model("HsnMasterModel","hsnModel");
		$this->load->model("CustomFieldModel","customField");
		$this->load->model("CustomOptionModel","customOption");
		$this->load->model('TaxClassModel','taxClass');
		$this->load->model("GroupMasterModel","group");

		/* HR Models */
		$this->load->model("hr/DepartmentModel","department");
		$this->load->model("hr/DesignationModel","designation");
		$this->load->model("hr/EmployeeCategoryModel","employeeCategory");
		$this->load->model("hr/ShiftModel","shiftModel");
		$this->load->model("hr/EmployeeModel","employee");
		$this->load->model("hr/EmpLoanModel","empLoan");
		$this->load->model('hr/AdvanceSalaryModel','advanceSalary');
		$this->load->model('hr/LeaveAuthorityModel', 'leaveAuthorityModel');
		$this->load->model('hr/LeaveModel','leave');
		$this->load->model('hr/LeaveSettingModel','leaveSetting');
		$this->load->model('hr/LeaveApproveModel','leaveApprove');

		/* Master Model */
		$this->load->model('PartyModel','party');
		$this->load->model('ItemCategoryModel','itemCategory');
		$this->load->model('ItemModel','item');
		$this->load->model('CountriesModel','countries');
		$this->load->model('MaterialGradeModel','materialGrade');
		$this->load->model('ScrapGroupModel','scrapGroup');
		$this->load->model('ItemPriceStructureModel','itemPriceStructure');

		/* Sales Model */
		$this->load->model('TransactionMainModel','transMainModel');
		$this->load->model('TaxMasterModel','taxMaster');
		$this->load->model('ExpenseMasterModel','expenseMaster');
		$this->load->model('LeadModel','leads');
		$this->load->model('SalesEnquiryModel','salesEnquiry');
		$this->load->model('SalesOrderModel','salesOrder');
		$this->load->model('SalesQuotationModel','salesQuotation');
		$this->load->model('DeliveryChallanModel','deliveryChallan');
		$this->load->model('DispatchOrderModel','dispatchOrder');
		$this->load->model('ProductionRequestModel','productionRequest'); 
		$this->load->model('DispatchPlanModel','dispatchPlan'); 

		/* Purchase Model */
		$this->load->model('PurchaseOrderModel','purchaseOrder');
		$this->load->model('PurchaseIndentModel','purchaseIndent');

		/* Store Model */
		$this->load->model('StoreModel','store');
		$this->load->model('StoreLocationModel','storeLocation');
		$this->load->model('GateInwardModel','gateInward');
		$this->load->model('StockVerificationModel','stockVerify');
		// $this->load->model('RequisitionModel', 'requisition');
		// $this->load->model('IssueRequisitionModel', 'issueRequisition');
		$this->load->model('InspectionModel', 'inspection');

		/* Accounting Model */
		$this->load->model("PurchaseInvoiceModel","purchaseInvoice");
		$this->load->model("DebitNoteModel","debitNote");
		$this->load->model("SalesInvoiceModel","salesInvoice");
		$this->load->model("CreditNoteModel","creditNote");
		$this->load->model("GstExpenseModel","gstExpense");
		$this->load->model("GstIncomeModel","gstIncome");
		$this->load->model("JournalEntryModel","journalEntry");
		$this->load->model("PaymentVoucherModel","paymentVoucher");
		$this->load->model("TaxPaymentModel","taxPayment");
		$this->load->model("BillWiseModel","billWise");

		/* Production Model */
		$this->load->model('ProcessModel','process');
		$this->load->model('RejectionCommentModel','comment');
		$this->load->model('sopModel','sop');
		// $this->load->model('PrcMaterialIssueModel','prcMaterialIssue');
		$this->load->model('OutsourceModel','outsource');
		$this->load->model('RejectionReviewModel','rejectionReview');
		$this->load->model('PurchaseModel','purchase');
		$this->load->model('PackingModel','packingModel');
		//$this->load->model('PrimaryPackingModel','primaryPacking');
		$this->load->model('FinalPackingModel','finalPacking');

		/* Store Report Model */
		$this->load->model('report/StoreReportModel','storeReport');

		/* Sales Report Model */
		$this->load->model('report/SalesReportModel','salesReport');

		/* Accounting Report Model */
		$this->load->model('report/AccountingReportModel','accountReport');
		
		/* Purchase Report Model */
		$this->load->model('report/PurchaseReportModel','purchaseReport');

		/* GST Report Model */
		$this->load->model('report/GstReportModel','gstReport');

		/* Estimation Model [Cash Entry] */
		$this->load->model("EstimateModel",'estimate');

		/* Export Model */
		$this->load->model("PackingListModel","packingList");
		$this->load->model("CommercialInvoiceModel","commercialInvoice");
		$this->load->model("CustomInvoiceModel","customInvoice");
		
		/* Quality Model */		
		$this->load->model('InstrumentModel','instrument');
		$this->load->model('QcChallanModel','qcChallan');
		$this->load->model('QcPRModel', 'qcPRModel');
		$this->load->model('QCIndentModel', 'qcIndent');
		$this->load->model('QCPurchaseModel', 'qcPurchase');
		$this->load->model('LineInspectionModel', 'lineInspection');
		// $this->load->model('FinalInspectionModel', 'finalInspection');
		
		/* Maintenance Model  */
		$this->load->model("MachineBreakdownModel",'machineBreakdown');
		
		$this->setSessionVariables(["masterModel","customField","customOption","dashboard","permission","terms","transport","hsnModel","itemCategory","item","department","designation","employeeCategory","shiftModel","employee","party","transMainModel","taxMaster","expenseMaster","salesEnquiry","salesOrder","purchaseOrder","store","storeLocation","gateInward","salesInvoice","purchaseInvoice","paymentVoucher","leads","salesQuotation","gstExpense","gstIncome","journalEntry","creditNote","debitNote","ebill","storeReport","salesReport","accountReport","gstReport","estimate","countries","packingList","commercialInvoice","customInvoice","instrument","empLoan","advanceSalary","leaveAuthorityModel","leave","leaveSetting","leaveApprove","inspection","process","comment","sop","qcChallan","qcPRModel","qcIndent","qcPurchase","materialGrade","scrapGroup","purchaseIndent","purchase","rejectionReview","purchaseReport","lineInspection","taxClass","group","itemPriceStructure","taxPayment","billWise","deliveryChallan","machineBreakdown","packingModel","dispatchOrder","productionRequest","dispatchPlan",'finalPacking']);

		$this->data['companyDetail'] = $this->masterModel->getCompanyInfo($this->session->userdata('cm_id'));
	}

	public function setSessionVariables($modelNames){
		$this->data['dates'] = $this->dates = explode(' AND ',$this->session->userdata('financialYear'));
        $this->data['shortYear'] = $this->shortYear = date('y',strtotime($this->dates[0])).'-'.date('y',strtotime($this->dates[1]));
		$this->data['startYear'] = $this->startYear = date('Y',strtotime($this->dates[0]));
		$this->data['endYear'] = $this->endYear = date('Y',strtotime($this->dates[1]));
		$this->data['startYearDate'] = $this->startYearDate = date('Y-m-d',strtotime($this->dates[0]));
		$this->data['endYearDate'] = $this->endYearDate = date('Y-m-d',strtotime($this->dates[1]));

		$this->loginId = $this->session->userdata('loginId');
		$this->userName = $this->session->userdata('user_name');
		$this->userRole = $this->session->userdata('role');
		$this->userRoleName = $this->session->userdata('roleName');
		$this->partyId = $this->session->userdata('partyId');
		$this->cm_id = $this->data['cm_id'] = $this->session->userdata('cm_id');
		$this->processId = $this->session->userdata('processId');

		$this->RTD_STORE = $this->session->userdata('RTD_STORE');
		$this->SCRAP_STORE = $this->session->userdata('SCRAP_STORE');
		$this->CUT_STORE = $this->session->userdata('CUT_STORE');
		$this->FIR_STORE = $this->session->userdata('FIR_STORE');
		$this->PACKING_STORE = $this->session->userdata('PACKING_STORE');
		$this->DISP_STORE = $this->session->userdata('DISP_STORE');
		$this->PRD_FNSH_STORE = $this->session->userdata('PRD_FNSH_STORE');
		$this->FPCK_STORE = $this->session->userdata('FPCK_STORE');
		$this->RTD_FPCK_STORE = $this->session->userdata('RTD_FPCK_STORE');

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
			$this->{$modelName}->userName = $this->userName;
			$this->{$modelName}->userRole = $this->userRole;
			$this->{$modelName}->userRoleName = $this->userRoleName;
			$this->{$modelName}->partyId = $this->partyId;
			$this->{$modelName}->cm_id = $this->cm_id;
			$this->{$modelName}->processId = $this->processId; 

			$this->{$modelName}->RTD_STORE = $this->RTD_STORE;
			$this->{$modelName}->SCRAP_STORE = $this->SCRAP_STORE;
			$this->{$modelName}->CUT_STORE = $this->CUT_STORE;
			$this->{$modelName}->FIR_STORE = $this->FIR_STORE;
			$this->{$modelName}->PACKING_STORE = $this->PACKING_STORE;
			$this->{$modelName}->DISP_STORE = $this->DISP_STORE;
			$this->{$modelName}->PRD_FNSH_STORE = $this->PRD_FNSH_STORE;
			$this->{$modelName}->FPCK_STORE = $this->FPCK_STORE;
			$this->{$modelName}->RTD_FPCK_STORE = $this->RTD_FPCK_STORE;
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
        $partyDetail = $this->party->getParty($data);
        $gstDetails = $this->party->getPartyGSTDetail(['party_id'=>$data['id']]);
        $this->printJson(['status'=>1,'data'=>['partyDetail'=>$partyDetail,'gstDetails'=>$gstDetails]]);
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

	public function trashFiles(){
        /** define the directory **/
        $dirs = [
            realpath(APPPATH . '../assets/uploads/qr_code/'),
            //realpath(APPPATH . '../assets/uploads/import_excel/'),
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

	public function importExcelFile($file,$path,$sheetName){
		$excel_file = '';
		if(isset($file['name']) || !empty($file['name']) ):
			$this->load->library('upload');
			$_FILES['userfile']['name']     = $file['name'];
			$_FILES['userfile']['type']     = $file['type'];
			$_FILES['userfile']['tmp_name'] = $file['tmp_name'];
			$_FILES['userfile']['error']    = $file['error'];
			$_FILES['userfile']['size']     = $file['size'];
			
			$imagePath = realpath(APPPATH . '../assets/uploads/'.$path);
			$config = ['file_name' => time()."_UP_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' =>$imagePath];

			$this->upload->initialize($config);
			if (!$this->upload->do_upload()):
				$errorMessage['excel_file'] = $this->upload->display_errors();
				$this->printJson(["status"=>0,"message"=>$errorMessage]);
			else:
				$uploadData = $this->upload->data();
				$excel_file = $uploadData['file_name'];
			endif;

			if(!empty($excel_file)):
				$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($imagePath.'/'.$excel_file);
				$fileData = array($spreadsheet->getSheetByName($sheetName)->toArray(null,true,true,true));
				return $fileData;
			else:
				return ['status'=>2,'message'=>'Data not found...!'];
			endif;
		else:
			return ['status'=>2,'message'=>'Please Select File!'];
		endif;
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

    public function getCitiesOptions($postData=array()){
        $state_id = (!empty($postData['state_id']))?$postData['state_id']:$this->input->post('state_id');

        $result = $this->party->getCities(['state_id'=>$state_id]);
        
        $html = '<option value="">Select City</option>';
        foreach ($result as $row) :
            $selected = (!empty($postData['city_id']) && $row->id == $postData['city_id']) ? "selected" : "";
            $html .= '<option value="' . $row->id . '" ' . $selected . '>' . $row->name . '</option>';
        endforeach;

        if(!empty($postData)):
            return $html;
        else:
            $this->printJson(['status'=>1,'result'=>$html]);
        endif;
    }

	public function getPartyNetInvoiceSum(){
		$data = $this->input->post();
		$cm_id = (!empty($data['cm_id']))? $data['cm_id'] : 1; unset($data['cm_id']);
		$result = $this->transMainModel->getPartyNetInvoiceSum($data);
		$accountSetting = $this->masterModel->getAccountSettings($cm_id);
		$result['accountSetting'] = $accountSetting;
		$this->printJson($result);
	}

	public function getAccountSummaryHtml(){
        $data = $this->input->post();
		$taxClass = $this->taxClass->getTaxClass($data['tax_class_id']);
		
        $this->data['taxList'] = (!empty($taxClass->tax_ids))?$this->taxMaster->getTaxList(['tax_ids'=>$taxClass->tax_ids]):array();
        $this->data['expenseList'] = (!empty($taxClass->expense_ids))?$this->expenseMaster->getExpenseList(['expense_ids'=>$taxClass->expense_ids]):array();
        $this->data['ledgerList'] = $this->party->getPartyList(['group_code'=>["'DT'","'ED'","'EI'","'ID'","'II'"]]);
		
		$dataRow = (!empty($data['taxSummary']))?$data['taxSummary']:array();
        $this->data['dataRow'] = (object) $dataRow;
        $this->load->view('includes/tax_summary',$this->data);
    }

	/* Use For [Delivery Challan, Sales Invoice] */
	public function getBatchWiseItemStock(){
		$data = $this->input->post();

		$data['location_ids'] = (!empty($data['location_ids']))?$data['location_ids']:[];//[$this->RTD_STORE->id,$this->DISP_STORE->id];
		
		$data['batchDetail'] = (!empty($data['batchDetail']))?json_decode($data['batchDetail'],true):[];
		
		$postData = ["item_id" => $data['item_id'], 'location_ids'=> $data['location_ids'], 'stock_required'=>1, 'group_by'=>'location_id,opt_qty'];		

		if(!empty($data['batchDetail']) && !empty($data['id'])):			
			// $batch_no = array_column($data['batchDetail'],'batch_no');
			// $batch_no = "'".implode("', '",$batch_no)."'";

			$opt_qty = array_column($data['batchDetail'],'opt_qty');
			$opt_qty = "'".implode("', '",$opt_qty)."'";

			$postData['customHaving'] = "(SUM(stock_trans.qty * stock_trans.p_or_m) > 0  AND stock_trans.opt_qty IN (".$opt_qty."))";
		endif;

		/* if(!empty($data['party_id'])):
			$postData['party_id'] = $data['party_id'];
		endif; */
		$batchData = $this->itemStock->getItemStockBatchWise($postData);

		$batchDetail = [];
		if(!empty($data['batchDetail'])):			
			$batchDetail = array_reduce($data['batchDetail'],function($item,$row){
				$item[$row['remark']] = $row['batch_qty'];
				return $item;
			},[]);
		endif;

        $tbody = '';$i=1;
        if(!empty($batchData)):
            foreach($batchData as $row):
                // $batchId = trim(preg_replace('/[^A-Za-z0-9]/', '', $row->batch_no)).floatval($row->opt_qty).$row->location_id.$row->item_id;
                $batchId = floatval($row->opt_qty).$row->location_id.$row->item_id;
                $location_name = '['.$row->store_name.'] '.$row->location;

				$qty = (isset($batchDetail[$batchId]))?$batchDetail[$batchId]:0;
				$boxQty = ($qty > 0 && !empty(floatval($row->opt_qty)))?($qty / $row->opt_qty):$qty;

				$totalBox = (!empty(floatval($row->qty)) && !empty(floatval($row->opt_qty)))?floatval(($row->qty / $row->opt_qty)):floatval($row->qty);
				$opt_qty = (!empty(floatval($row->opt_qty)))?floatval($row->opt_qty):1;

				if(!empty($data['id'])):
					$row->qty = $row->qty + $qty;
				endif;

                $tbody .= '<tr id="'.$batchId.'" data-ind="'.$i.'">
                    <td>['.$row->store_name.'] '.$row->location.'</td>
                    <td>
                        '.floatval($totalBox).'
                        <br>
                        <!--<small>['.floatval($totalBox).' x '.floatval($opt_qty).' = '.floatval($row->qty).']</small>-->
                    </td>
                    <td>
                        <!--<input type="text" id="box_qty_'.$i.'" class="form-control floatOnly calculateBoxQty" data-srno="'.$i.'" value="'.$boxQty.'">-->
                        <input type="text" name="batchDetail['.$i.'][batch_qty]" id="batch_qty_'.$i.'" class="form-control floatOnly calculateBatchQty calculateBoxQty" value="'.$qty.'" data-srno="'.$i.'">
                        <input type="hidden" name="batchDetail['.$i.'][opt_qty]" id="opt_qty_'.$i.'" value="'.floatval($opt_qty).'">
                        <input type="hidden" name="batchDetail['.$i.'][location_id]" id="location_id_'.$i.'" value="'.$row->location_id.'">
                        <input type="hidden" name="batchDetail['.$i.'][remark]" id="batch_id_'.$i.'" value="'.$batchId.'">
                        <input type="hidden" name="batchDetail['.$i.'][batch_stock]" id="batch_stock_'.$i.'" value="'.floatVal($row->qty).'">
                        <div class="error batch_qty_'.$i.'"></div>
                    </td>
                </tr>';
                $i++;
				// <input type="hidden" name="batchDetail['.$i.'][batch_no]" id="batch_no_'.$i.'" value="'.$row->batch_no.'">
				
				// <!--<td>'.$row->batch_no.'</td>-->
            endforeach;
        endif;

		if(empty($tbody)):
            $tbody = '<tr>
                <td colspan="3" class="text-center">No data available in table</td>
            </tr>';
        endif;

        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
	}
}
?>