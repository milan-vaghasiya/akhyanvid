<?php
class Outsource extends MY_Controller
{
    private $indexPage = "outsource/index";
    private $formPage = "outsource/form";

	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Outsource";
		$this->data['headData']->controller = "outsource";
		$this->data['headData']->pageUrl = "outsource";
	}
	
	public function index(){
        $this->data['tableHeader'] = getProductionDtHeader('outsource');
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $result = $this->outsource->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getOutsourceData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addChallan(){
        $this->data['ch_prefix'] = 'VC/'.getYearPrefix('SHORT_YEAR').'/';
        $this->data['ch_no'] = $this->outsource->getNextChallanNo();
        $this->data['requestData']=$this->sop->getChallanrequestData(['pending_challan'=>1]);
        $this->data['vendorList'] = $this->party->getPartyList(['party_category'=>3]);
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['party_id'])){ $errorMessage['party_id'] = "Vendor is required.";}
        if(empty($data['id'])){ $errorMessage['general_error'] = "Select Item ";}else{
            foreach($data['id'] as $key=>$id){
                $reqData = $this->sop->getChallanRequestData(['id'=>$id,'single_row'=>1]);
                if($data['ch_qty'][$key] > $reqData->qty || empty($data['ch_qty'][$key])){
                    $errorMessage['chQty' . $id] = "Qty. is invalid.";
                }
            }
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['created_by'] = $this->session->userdata('loginId');
            $this->printJson($this->outsource->save($data));
        endif;
    }

   
    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->outsource->delete($id));
        endif;
    }  
    
    public function outSourcePrint($id){
        $this->data['outSourceData'] = $this->outsource->getOutSourceData(['id'=>$id]);
        $this->data['reqData'] = $this->sop->getChallanRequestData(['challan_id'=>$id]);
        $this->data['companyData'] = $this->outsource->getCompanyInfo();	

        $logo = (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->company_logo):base_url('assets/images/logo.png');
        $this->data['letter_head'] =  (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->print_header):base_url('assets/images/letterhead_top.png');
    
        $pdfData = $this->load->view('outsource/print', $this->data, true);        
		$mpdf = new \Mpdf\Mpdf();
        $pdfFileName='VC-'.$id.'.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.05,array(120,45));
        $mpdf->showWatermarkImage = true;
		$mpdf->AddPage('P','','','','',10,5,5,15,5,5,'','','','','','','','','','A4-P');
		
        $mpdf->WriteHTML($pdfData);
		ob_clean();
		$mpdf->Output($pdfFileName, 'I');
		
    }

    function jobworkOutChallan($id)
	{
        $this->data['outSourceData'] = $this->outsource->getOutSourceData(['id'=>$id]);
        $this->data['reqData'] = $this->sop->getChallanRequestData(['challan_id'=>$id]);
        $this->data['companyData'] = $this->outsource->getCompanyInfo();	

        $logo = (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->company_logo):base_url('assets/images/logo.png');
        $this->data['letter_head'] =  (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->print_header):base_url('assets/images/letterhead_top.png');
    
		$this->data['jobworkerHtml']= '<table class="table item-list-bb" style="margin-top:10px;">
			<tr>
				<th colspan="4">PART - II - TO BE FILLED BY JOBWORKER</th>
			</tr>
			<tr class="text-center">
				<th style="width:20%;">Date & Time Of Dispatch</th>
				<th style="width:20%;">Quantity Of Dispatch</th>
				<th style="width:20%;">Nature of Process Done</th>
				<th style="width:40%;">Quantity of waste material returned to the parent factory</th>
			</tr>
			<tr class="text-center">
				<td style="width:20%;" height="200"></td>
				<td style="width:20%;"></td>
				<td style="width:20%;"></td>
				<td style="width:40%; font-size:12px; vertical-align:top;">During the process of jobwork KGS scrap generate and the same is retained on behalf of principal.</td>
			</tr>
		</table>';
        $pdfData = $this->load->view('outsource/print', $this->data, true);        
	
		
		$mpdf = new \Mpdf\Mpdf();
		$pdfFileName = 'Challan_' . $id . '.pdf';
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
		$mpdf->WriteHTML($stylesheet, 1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetProtection(array('print'));

		$mpdf->AddPage('P', '', '', '', '', 5, 5, 5, 5, 5, 5, '', '', '', '', '', '', '', '', '', 'A4-P');
		$mpdf->WriteHTML($pdfData);
		
		$mpdf->Output($pdfFileName, 'I');
	}
}
?>