<?php
class GateInward extends MY_Controller{
    private $indexPage = "gate_inward/index";
    private $form = "gate_inward/form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Gate Inward";
		$this->data['headData']->controller = "gateInward";
        $this->data['headData']->pageUrl = "gateInward";
    }

    public function index(){
        $this->data['tableHeader'] = getStoreDtHeader("gateInward");
		$this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($type = 2,$status = 0){
        $data = $this->input->post();
        $data['trans_type'] = $type;
        $data['trans_status'] = $status;

        $result = $this->gateInward->getDTRows($data);
        $sendData = array();$i=($data['start']+1);

        foreach($result['data'] as $row):
            $row->sr_no = $i++;        
            $row->controller = $this->data['headData']->controller;
            $sendData[] = getGateInwardData($row);
        endforeach;

        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addGateInward(){
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>[1,2]]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['trans_no'] = $this->gateInward->getNextGrnNo();
        $this->data['trans_prefix'] = "GRN/".$this->shortYear."/";
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post(); 
        $errorMessage = array();
        
        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['item_data']))
            $errorMessage['batch_details'] = "Item Details is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->gateInward->save($data));
        endif;
    }

	public function edit(){
        $data = $this->input->post();
        $this->data['grnData'] = $grnData = $this->gateInward->getGateInward(['id'=>$data['grn_id']]);//print_r($this->db->last_query());exit;
        $this->data['grnTransData'] = $grnTransData = $this->gateInward->getInwardItem(['id'=>$data['id'],'single_row'=>1]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>[1,2]]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->load->view('gate_inward/edit_form',$this->data);
    }

    public function updateGRN(){
        $data = $this->input->post(); 
        $errorMessage = array();
        
        if(empty($data['itemData']['qty']))
            $errorMessage['qty'] = "Qty is required.";
        if(empty($data['itemData']['item_id']))
            $errorMessage['item_id'] = "Item is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->gateInward->updateGRN($data));
        endif;
    }

    public function delete(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->gateInward->delete($data));
        endif;
    }

    public function printGateInward($id){
		$this->data['dataRow'] = $dataRow = $this->gateInward->getGateInward(['id'=>$id,'itemData'=>1]);
		$this->data['partyData'] = $this->party->getPartyList(['id'=>$dataRow->party_id,'single_row'=>1]);
		$this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
		
		$logo = base_url('assets/images/logo.png');
        $this->data['letter_head'] = base_url('assets/images/letterhead.png');

        $pdfData = $this->load->view('gate_inward/print',$this->data,true);
		
		$mpdf = new \Mpdf\Mpdf();
		$pdfFileName='GRN-'.$id.'.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.05,array(120,45));
		$mpdf->showWatermarkImage = true;
		
		$mpdf->AddPage('P','','','','',5,5,5,5,5,5,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
	}
}
?>