<?php
class WorkInstructions extends MY_Controller
{
    private $indexPage = "work_instructions/index";
    private $formPage = "work_instructions/form";
    
	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Work Instructions";
		$this->data['headData']->controller = "workInstructions";
        $this->data['headData']->pageUrl = "workInstructions";
	}
	
	public function index(){
        $this->data['tableHeader'] = getConfigDtHeader('workInstructions');
        $this->load->view($this->indexPage,$this->data);
    }
	
    public function getDTRows($work_type=1){
        $data = $this->input->post();$data['work_type'] = $work_type;
        $result = $this->workInstructions->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getWorkInstructionsData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addWorkInstructions(){
		$data = $this->input->post();
		$this->data['work_type'] = $data['work_type'];
        $this->load->view($this->formPage, $this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();		
      
        if(empty($data['description']) AND $data['work_type'] != 3):
			$errorMessage['description'] = "Description is required.";
		endif;
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->workInstructions->save($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->workInstructions->getWorkInstructions(['id'=>$data['id'],'single_row'=>1]);

        $this->load->view($this->formPage, $this->data);
    }
}
?>