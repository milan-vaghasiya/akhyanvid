<?php
class SalesQuotation extends MY_Controller{
    private $indexPage = "sales_quotation/index";
    private $form = "sales_quotation/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Sales Quotation";
		$this->data['headData']->controller = "salesQuotation";        
        $this->data['headData']->pageUrl = "salesQuotation";
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader("salesQuotation");
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 1){
        $data = $this->input->post();$data['trans_status'] = $status;
        $result = $this->salesQuotation->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getSalesQuotationData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addQuotation(){
        $this->data['trans_no'] = $this->salesQuotation->getNextSQNo();
        $this->data['trans_number'] = 'SQ/'.$this->shortYear.'/'.$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>"1",'party_type'=>"1,2"]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>$this->TERMS_TYPES["23"]]);
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
		
        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Item Details is required.";
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(empty($data['id'])):
                $data['trans_no'] = $this->salesQuotation->getNextSQNo();
                $data['trans_number'] = 'SQ/'.$this->shortYear.'/'.$data['trans_no'];
            endif; 
            $this->printJson($this->salesQuotation->save($data));
        endif;
    }

    public function edit($jsonData=""){
        if(!empty($jsonData)):
            $postData = (Array) decodeURL($jsonData);
        endif; 
        $this->data['dataRow'] = $dataRow = $this->salesQuotation->getSalesQuotation(['trans_number'=>$postData['trans_number']]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => "1",'party_type'=>"1,2"]);
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>$this->TERMS_TYPES["23"]]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $data = $this->input->post();
        $data['trans_number'] = decodeURL($data['trans_number']);
        if(empty($data['trans_number'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesQuotation->delete($data));
        endif;
    }

    public function printQuotation($jsonData=""){
        if(!empty($jsonData)):
            $postData = (Array) decodeURL($jsonData);
        endif; 
        $this->data['dataRow'] = $dataRow = $this->salesQuotation->getSalesQuotation(['trans_number'=>$postData['trans_number']]);
        $this->data['partyData'] = $this->party->getPartyList(['id'=>$dataRow[0]->party_id,'single_row'=>1]);
        $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
        
        $logo = base_url('assets/images/logo.png');
        $this->data['letter_head'] =  base_url('assets/images/letterhead.png');
        
        if($postData['pdf_type'] == "print_gst"){
            $pdfData = $this->load->view('sales_quotation/print_gst', $this->data, true); //With GST 
        }else{
            $pdfData = $this->load->view('sales_quotation/print', $this->data, true);     
        }

        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
            <tr>
                <td style="width:25%;">Qtn. No. & Date : '.$dataRow[0]->trans_number . ' [' . formatDate($dataRow[0]->trans_date) . ']</td>
                <td style="width:25%;"></td>
                <td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
            </tr>
        </table>';
        
		$mpdf = new \Mpdf\Mpdf();
		$filePath = realpath(APPPATH . '../assets/uploads/sales_quotation/');
        $pdfFileName = $filePath.'/' . str_replace(["/","-"],"_",$dataRow[0]->trans_number) . '.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.05,array(125,30));
        $mpdf->showWatermarkImage = true;
        $mpdf->SetHTMLFooter($htmlFooter);
        
        $mpdf->SetDefaultBodyCSS('background', "url('".$lh_bg."')");
        $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        
		$mpdf->AddPage('P','','','','',5,5,5,25,5,5,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);
		
		ob_clean();
		$mpdf->Output($pdfFileName, 'I');		
    }
    

    public function changeQuotationStatus(){
		$data = $this->input->post();
		if(empty($data['trans_number'])):
			$this->printJson(['status'=>0,'message'=>'Something went wrong...Please try again.']);
		else:
			$this->printJson($this->salesQuotation->changeQuotationStatus($data));
		endif;
	}

}
?>