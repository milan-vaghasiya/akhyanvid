<?php
class SelectOption extends MY_Controller{ 
    private $index = "select_option/index";
    private $form = "select_option/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->controller = "selectOption";
		$this->data['headData']->pageTitle = "Select Option"; 
	} 
	
	public function index(){
		$this->data['headData']->pageTitle = "Select Option"; 
        $this->data['headData']->pageUrl = "selectOption";
        $this->data['tableHeader'] = getConfigDtHeader($this->data['headData']->controller);
        $this->load->view($this->index,$this->data);
    }
	
    public function getDTRows($type=1){
        $data = $this->input->post(); $data['type'] = $type;
        $result = $this->selectOption->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getSelectOptionData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addSelectOption(){
		$data = $this->input->post();
		$this->data['type'] = $data['type'];
        $this->load->view($this->form, $this->data);
    }

    public function save(){
        $data = $this->input->post();
		$errorMessage = array();
        if(empty($data['label'])){ $errorMessage['label'] = "Option is required."; }
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->selectOption->save($data));
        endif;
    }

    public function edit(){     
        $data = $this->input->post();
        $this->data['dataRow'] = $this->selectOption->getSelectOption($data);
        $this->load->view($this->form, $this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->selectOption->delete($id));
        endif;
    }
}
?>