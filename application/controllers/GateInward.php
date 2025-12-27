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

    public function ir_print($id){
        $grnTransData = $this->gateInward->getInwardItem(['id'=>$id,'single_row' => 1]);    
        $companyData = $this->masterModel->getCompanyInfo();  
		
        $logo = (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->company_logo):base_url('assets/images/logo.png');
       
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [100, 68]]);
		$pdfFileName = 'IR_PRINT.pdf';
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);

        if(!empty($grnTransData)){            
            $qrIMG = base_url('assets/uploads/iir_qr/'.$grnTransData->id.'.png');
            if(!file_exists($qrIMG)){
                $qrText = $grnTransData->item_id.'~'.$grnTransData->batch_no;
                $file_name = $grnTransData->id;
                $qrIMG = base_url().$this->getQRCode($qrText,'assets/uploads/iir_qr/',$file_name);
            }
            
            $itemList ='<table class="table">
                <tr>
                    <td><img src="'.$logo.'" style="max-height:40px;"></td>
                    <td class="text-right"></td>
                </tr>
            </table>
            <table class="table top-table-border">
                <tr>
                    <td>GRN No. <br><b>'.(!empty($grnTransData->trans_number)?$grnTransData->trans_number:'-').'</b></td>
                    <td>Date <br><b>'.(!empty($grnTransData->trans_date)?formatDate($grnTransData->trans_date):'-').'</b></td>
                </tr>
                <tr>
                    <td colspan="2">'.(!empty($grnTransData->party_name)?$grnTransData->party_name:'-').'<br> <b>'.(!empty($grnTransData->item_name)?$grnTransData->item_name:'-').'</b></td>
                </tr>
                <tr>
                    <td>Qty<br><b>'.$grnTransData->qty.' ('.$grnTransData->unit_name.')</b></td>
                    <td>Serial No <br><b>'.(!empty($grnTransData->batch_no)?$grnTransData->batch_no:'-').'</b></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center"><img src="'.$qrIMG.'" style="height:30mm;"></td>
                </tr>
            </table>';
            
            $pdfData = '<div style="text-align:center;float:left;padding:1mm 1mm;rotate: -90;position: absolute;bottom:1mm;width:65mm;height:95mm;">' . $itemList . '</div>';

            $mpdf->AddPage('P','','','','',1,1,1,1,1,1);
            $mpdf->WriteHTML($pdfData);
        }  
        
		$mpdf->Output($pdfFileName, 'I');
    }
}
?>