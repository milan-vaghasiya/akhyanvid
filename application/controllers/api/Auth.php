<?php

defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

header('Content-Type:application/json');

if(isset($_SERVER['HTTP_ORIGIN'])):
    header("Access-Control-Allow-Origin:*");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
endif;
// Access-Control headers are received during OPTIONS requests

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'):
    if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE,OPTIONS");
    if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
endif;

class Auth extends CI_Controller{

    public function __construct(){
		parent::__construct();

        //Load Defualt Library & Model
        $this->load->library('form_validation');
        $this->load->model('LoginModel','loginModel');
	}

    public function check(){
        $data = $this->input->post();
        
        $this->form_validation->set_rules('user_name','Username','required|trim');
		$this->form_validation->set_rules('password','Password','required|trim');

        if(!empty($this->form_validation->run() == false)):
            $this->printJson(['status'=>0,'message'=>$this->form_validation->error_array()]);
        else:
            $data['isApiAuth'] = 1;
            $this->printJson($this->loginModel->checkAuth($data));
        endif;
    }

    public function logout(){
        $headData = json_decode(base64_decode($this->input->get_request_header('sign')));
        $this->loginModel->appLogout($headData->loginId);
        $this->printJson(['status'=>1,'message'=>"Logout successfull."]);
    }

    public function getCurrentVersion(){
		$postData = $this->input->post();
		
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->loginModel->getCurrentVersion()]);
    }

    public function printJson($response,$headerStatus=200){
        $this->output->set_status_header($headerStatus)->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
        exit;
	}

}
?>