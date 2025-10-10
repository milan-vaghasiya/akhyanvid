<?php 
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

header('Content-Type:application/json');
if (isset($_SERVER['HTTP_ORIGIN'])):
    header("Access-Control-Allow-Origin:*");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
endif;

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'):
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE,OPTIONS");
    
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
endif;

class MY_ApiController extends CI_Controller{
    public $termsTypeArray = ["Purchase","Sales"];
	public $gstPer = ['0'=>"NILL",'0.10'=>'0.10 %','0.25'=>"0.25 %",'1'=>"1 %",'3'=>"3%",'5'=>"5 %","6"=>"6 %","7.50"=>"7.50 %",'12'=>"12 %",'18'=>"18 %",'28'=>"28 %"];
	public $empRole = [1 => "Admin", 2 => "Employee", 3 => "Customer", 4 => "Technician"];
    public $gender = ["M"=>"Male","F"=>"Female","O"=>"Other"];

	public $gstRegistrationTypes = [1=>'Registerd',2=>'Composition',3=>'Overseas',4=>'Un-Registerd'];
	public $businessTypes = ["Builder","Individuals","Government","Institute","Other"];
	public $leaveType = ["Casual Leave","Sick Leave","Marriage Leave","Maternity Leave","Paternity Leave","Study Leave"];
    
    // Project Specification
	public $specArray = [1 =>"DB Location",2 => "DB Height", 3=>"DB Width", 4=>"Cabel Junction Space", 5=>"Rack Location", 6=>"QSM Location", 7=>"Keypad Location", 8=>"Gangbox Location",9=>"Ups Location",10=> "DALI System", 11=>"Special Notes"];

    public function __construct(){
        parent::__construct();
        $this->checkAuth();

        $this->data['headData'] = new StdClass;

        //Load Defualt Library
        $this->load->library('form_validation');

        //Load Models
        $this->load->model('masterModel');
        $this->load->model('PermissionModel','permission');
		
		/* Configration Models */
		$this->load->model("SelectOptionModel","selectOption");
		$this->load->model("WorkInstructionsModel","workInstructions");
		
		/* HR Models */
		$this->load->model("hr/EmployeeModel","employee");
		$this->load->model('TaskManagerModel', 'taskManager');
		
		/* Master Model */
		$this->load->model('PartyModel','party');
		$this->load->model('ItemCategoryModel','itemCategory');
		$this->load->model('ItemModel','item');
		
        /* Sales Model */
        $this->load->model('SalesQuotationModel','salesQuotation');
		$this->load->model('ProjectModel','project');
		$this->load->model('ServiceModel','service'); 
		$this->load->model('WorkProgressModel','workProgress'); 
		$this->load->model('CustomerComplaintsModel','customerComplaints');

		/* Store Model */
		$this->load->model('GateInwardModel','gateInward');
		$this->load->model('StoreModel','store');

        $this->setSessionVariables(["masterModel","permission","selectOption","employee","party","itemCategory","item","gateInward","salesQuotation","project","service","workProgress","customerComplaints","store","workInstructions","taskManager"]);
    }

    public function setSessionVariables($modelNames){
        $headData = json_decode(base64_decode($this->input->get_request_header('sign')));

        $this->dates = explode(' AND ',$headData->financialYear);
        $this->shortYear = date('y',strtotime($this->dates[0])).'-'.date('y',strtotime($this->dates[1]));
		$this->startYear = date('Y',strtotime($this->dates[0]));
		$this->endYear = date('Y',strtotime($this->dates[1]));
		$this->startYearDate = date('Y-m-d',strtotime($this->dates[0]));
		$this->endYearDate = date('Y-m-d',strtotime($this->dates[1]));

		$this->loginId = $headData->loginId;
		$this->userCode = $headData->emp_code;
		$this->userName = $headData->emp_name;
		$this->userRole = $headData->role;
		$this->userRoleName = $headData->roleName;

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
		endforeach;

		return true;
	}

    public function checkAuth(){
        if($token = $this->input->get_request_header('authToken')):
            $this->load->model('LoginModel','loginModel');
            $result = $this->loginModel->checkToken($token);

            if($result == 0):
                $this->printJson(['status'=>0,'message'=>"Unauthorized",'data'=>null],401);
            endif;

            if(!$this->input->get_request_header('sign')):
                $this->printJson(['status'=>0,'message'=>"Sign not found.",'data'=>null],401);
            endif;

            return true;  
        else:
            $this->printJson(['status'=>0,'message'=>"Unauthorized",'data'=>null],401);
        endif;
    }

    public function printJson($response,$headerStatus=200){
        $this->output->set_status_header($headerStatus)->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
        exit;
	}

	public function trashFiles(){
        /** define the directory **/
        $dirs = [
            realpath(APPPATH . '../assets/uploads/temp_files/')
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
}
?>