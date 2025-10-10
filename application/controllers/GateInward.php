<?php
class GateInward extends MY_Controller{
    private $indexPage = "gate_inward/index";
    private $form = "gate_inward/form";
    private $inspectionFrom = "gate_inward/material_inspection";
    private $ic_inspect = "gate_inward/ic_inspect";
    private $inward_qc = "gate_inward/inward_qc";
	private $test_report = "gate_inward/test_report";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Goods Receipt Note";
		$this->data['headData']->controller = "gateInward";
        $this->data['headData']->pageUrl = "gateInward";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'gateInward','tableName'=>'grn_master']);
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

    public function createGI(){
        $data = $this->input->post();
        $gateEntryData = $this->gateEntry->getGateEntry($data['id']);
        $this->data['gateEntryData'] = $gateEntryData;
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>[1,2,3]]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>[1,2,3,4]]);
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['store_type'=>'0,15','final_location'=>1]);
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->load->view($this->form,$this->data);
    }

    public function addGateInward(){
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>[1,2,3]]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>[1,2,3,4]]);
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['final_location'=>1]);
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->load->view($this->form,$this->data);
    }

    public function getPoNumberList(){
        $data = $this->input->post();
        $data['entry_type'] = $this->transMainModel->getEntryType(['controller'=>'purchaseOrders'])->id;
        $poList = $this->purchaseOrder->getPartyWisePoList($data);

        $options = '<option value="">Select Purchase Order</option>';
        foreach($poList as $row):
            $options .= '<option value="'.$row->po_id.'" data-po_no="'.$row->trans_number.'" >'.$row->trans_number.'</option>';
        endforeach;

        $this->printJson(['status'=>1,'poOptions'=>$options]);
    }

    public function getItemList(){
        $data = $this->input->post();
        $data['entry_type'] = $this->transMainModel->getEntryType(['controller'=>'purchaseOrders'])->id;

        $options = '<option value="">Select Item Name</option>';
        if(empty($data['po_id'])):
            $options .= getItemListOption($this->item->getItemList());
        else:
            $itemList = $this->purchaseOrder->getPendingPoItems($data);

            foreach($itemList as $row):
                $options .= '<option value="'.$row->item_id.'" data-po_trans_id="'.$row->po_trans_id.'" data-price="'.$row->price.'" data-disc_per="'.$row->disc_per.'" data-location_id="'.$row->location_id.'">[ '.$row->item_code.' ] '.$row->item_name.' (Pending Qty. : '.$row->pending_qty.')</option>'; //02-04-25
            endforeach;
        endif;

        $this->printJson(['status'=>1,'itemOptions'=>$options]);
    }

    public function save(){
        $data = $this->input->post(); 
        $errorMessage = array();
        
        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['batchData']))
            $errorMessage['batch_details'] = "Item Details is required.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->gateInward->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $gateInward = $this->gateInward->getGateInward($data['id']);
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>[1,2,3]]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>[1,2,3,4]]);
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['final_location'=>1]);
        $this->data['gateInwardData'] = $gateInward;
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->gateInward->delete($id));
        endif;
    }
    
	public function ir_print($id){
        $irData = $this->gateInward->getInwardItem(['id'=>$id]);
        $companyData = $this->masterModel->getCompanyInfo();  
		$itemList="";$i=1;
        $logo = (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->company_logo):base_url('assets/images/logo.png');
       
        if(empty($irData->trans_status)){
            $header = "Pending QC";
            $qrIMG = "";
            $qty = $irData->qty;

            $itemList .='<style>.top-table-border th,.top-table-border td{font-size:12px;}</style>
                <table class="table">
                    <tr>
                        <td><img src="'.$logo.'" style="max-height:40px;"></td>
                        <td class="org_title text-right" style="font-size:18px;">'.$header.'</td>
                        '.$qrIMG .'
                    </tr>
                </table>
                <table class="table top-table-border">
                    <tr> 
                        <th>GI No</th>
                        <td>'.$irData->trans_number.'</td>
                        <th>GI Date</th>
                        <td>'.formatDate($irData->trans_date).'</td>
                    </tr>
                    <tr> 
                        <th>Part Name</th>
                        <td colspan="3">'.$irData->item_name.'</td>
                    </tr>
                    <tr> 
                        <th>Supplier</th>
                        <td colspan="3">'.$irData->party_name.'</td>
                    </tr>
                    <tr> 
                        <th>Batch Qty</th>
                        <td colspan="3">'.$qty.' '.$irData->uom.' </td>
                    </tr>
                    <tr> 
                        <th>Printed At</th>
                        <td colspan="3">'.date("d-m-Y h:i:s a").'</td>
                    </tr>
                </table>'; $i++;
        }else{
            $batchData = $this->gateInward->getItemWiseBatchList($irData->id);
            if(!empty($batchData)){
                foreach($batchData as $batch):
                    $qrIMG = base_url('assets/uploads/iir_qr/'.$irData->id.'.png');
                    if(!file_exists($qrIMG)){
                        $qrText = $batch->item_id.'~'.$batch->location_id;
                        $file_name = $irData->id;
                        $qrIMG = base_url().$this->getQRCode($qrText,'assets/uploads/iir_qr/',$file_name);
                    }
                    $header = "QC Ok";
                    $qrIMG =  '<td colspan="2" rowspan="2" class="text-right" style="padding:2px;"><img src="'.$qrIMG.'" style="height:25mm;"></td>';
                    $qty = $batch->qty;
              
                    $itemList .='<style>.top-table-border th,.top-table-border td{font-size:12px;}</style>
                    <table class="table">
                        <tr>
                            <td><img src="'.$logo.'" style="max-height:40px;"></td>
                            <td class="org_title text-right" style="font-size:18px;">'.$header.'</td>
                            '.$qrIMG .'
                        </tr>
                    </table>
                    <table class="table top-table-border">
                        <tr> 
                            <th>GI No</th>
                            <td>'.$irData->trans_number.'</td>
                            <th>GI Date</th>
                            <td>'.formatDate($irData->trans_date).'</td>
                        </tr>
                        <tr> 
                            <th>Part Name</th>
                            <td colspan="3">'.$irData->item_name.'</td>
                        </tr>
                        <tr> 
                            <th>Supplier</th>
                            <td colspan="3">'.$irData->party_name.'</td>
                        </tr>
                        <tr> 
                            <th>Batch Qty</th>
                            <td colspan="3">'.$qty.' '.$irData->uom.' </td>
                        </tr>
                        <tr> 
                            <th>Printed At</th>
                            <td colspan="3">'.date("d-m-Y h:i:s a").'</td>
                        </tr>
                    </table>'; $i++;
              endforeach;
            }
        }
        $pdfData = '<div style="width:100mm;height:25mm;">'.$itemList.'</div>';
       
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [100, 70]]);
		$pdfFileName='IR_PRINT.pdf';
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetProtection(array('print'));
		$mpdf->AddPage('P','','','','',2,2,2,2,2,2);
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
    }
	
    public function materialInspection(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->gateInward->getInwardItem(['id'=>$data['id']]);
        $this->load->view($this->inspectionFrom,$this->data);
    }

    public function saveInspectedMaterial(){
        $data = $this->input->post();
		
		$result = $this->gateInward->saveInspectedMaterial($data);
		
        $this->printJson($result);
    }

    public function getPartyInwards(){
        $data = $this->input->post();
        $this->data['orderItems'] = $this->gateInward->getPendingInwardItems($data);
        $this->load->view('purchase_invoice/create_invoice',$this->data);
    }

    public function inInspection($id){
		$this->data['inspectParamData'] = $inspectParamData = $this->gateInward->getInspectParamData($id);
        $this->data['inInspectData'] = $inInspectData = $this->gateInward->getInInspectData($id);
        $this->data['dataRow'] = $this->gateInward->getInwardItem(['id'=>$id]);
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
		$this->load->view($this->icInspectionForm,$this->data);
	}

    public function saveInInspection() {
        $data = $this->input->post();
        $errorMessage = Array();

        $insParamData = $this->gateInward->getInspectParamData($data['id']);

        $pre_inspection = Array();$param_ids = Array();$data['observation_sample'] = '';
        if(!empty($insParamData)):
            foreach($insParamData as $row):
                $param = Array();
                for($j = 1; $j <= $data['sample_size']; $j++):
                    $param[] = $data['sample'.$j.'_'.$row->id];
                    unset($data['sample'.$j.'_'.$row->id]);
                endfor;
                $param[] = $data['status_'.$row->id];
                $pre_inspection[$row->id] = $param;
				$param_ids[] = $row->id;
                unset($data['status_'.$row->id]);
            endforeach;
        endif;
		unset($data['sample_size']);
		$data['parameter_ids'] = implode(',',$param_ids);
        $data['observation_sample'] = json_encode($pre_inspection);
        $data['param_count'] = count($insParamData);
		
		if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$data['trans_date'] = date("Y-m-d");
            $data['created_by'] = $this->session->userdata('loginId');
            $this->printJson($this->gateInward->saveInInspection($data));
        endif;
    }

    public function inInspection_pdf($id){
		$this->data['inInspectData'] = $inInspectData = $this->gateInward->getInwardItem(['id'=>$id]);
        $this->data['observation'] = $this->gateInward->getInInspectData(['mir_trans_id'=>$id]);
        $this->data['paramData'] = $this->item->getInspectionParameter(['item_id'=>$inInspectData->item_id,'control_method'=>'IIR']);

		$inInspectData->fgCode="";
		if(!empty($inInspectData->fgitem_id)): $i=1; 
			$fgData = $this->grnModel->getFinishGoods($inInspectData->fgitem_id);
			$item_code = array_column($fgData,'item_code');
			$inInspectData->fgCode = implode(", ",$item_code);
		endif;

		$prepare = $this->employee->getEmployee(['id'=>$inInspectData->created_by]);
		$prepareBy = $prepare->emp_name.' <br>('.formatDate($inInspectData->created_at).')'; 
		$approveBy = '';
		if(!empty($inInspectData->is_approve)){
			$approve = $this->employee->getEmployee(['id'=>$inInspectData->is_approve]);
			$approveBy .= $approve->emp_name.' <br>('.formatDate($inInspectData->approve_date).')'; 
		}
		$response="";
		$logo=base_url('assets/images/logo.png'); $unapproved = base_url('assets/images/unapproved.jpg');
		$this->data['letter_head']=base_url('assets/images/letterhead_top.png');
		
		$pdfData = $this->load->view('gate_inward/ic_inspect_pdf',$this->data,true);
		
		$htmlHeader = '<table class="table">
							<tr>
								<td style="width:25%;"><img src="'.$logo.'" style="height:50px;"></td>
								<td class="org_title text-center" style="font-size:1rem;width:50%">INCOMING INSPECTION REPORT</td>
								<td style="width:25%;" class="text-right"><span style="font-size:0.8rem;"></td>
							</tr>
						</table><hr>';
		$htmlFooter = '<table class="table" style="border-bottom:1px solid #000000;">
						<tr>
							<td style="width:50%;"></td>
							<td style="width:25%;" class="text-center">'.$prepareBy.'</td>
							<td style="width:25%;" class="text-center">'.$approveBy.'</td>
						</tr>
						<tr>
							<td style="width:50%;"></td>
							<td style="width:25%;" class="text-center"><b>Prepared By</b></td>
							<td style="width:25%;" class="text-center"><b>Approved By</b></td>
						</tr>
					</table>
					<table class="table top-table" style="margin-top:10px;">
						<tr>
							<td style="width:25%;"></td>
							<td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
						</tr>
					</table>';
		// print_r($pdfData);exit;
		$mpdf = new \Mpdf\Mpdf();
		$pdfFileName='DC-REG-'.$id.'.pdf';
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.05,array(120,60));
		$mpdf->SetProtection(array('print'));
		
		$mpdf->SetHTMLHeader($htmlHeader);
		$mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',5,5,30,5,5,5,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');		
	}

    public function getInwardQc(){
        $data = $this->input->post();
        $this->data['dataRow'] = $dataRow = $this->gateInward->getInwardItem($data);
        $this->data['inInspectData'] = $this->gateInward->getInInspectData(['mir_trans_id'=>$data['id']]);
        $this->load->view($this->ic_inspect,$this->data); //04-10-2024
    }

    public function getIncomingInspectionData(){
        $data = $this->input->post();
        $paramData = $this->item->getInspectionParameter(['item_id'=>$data['item_id'],'control_method'=>'IIR']);
        $oldData = $this->gateInward->getInInspectData(['mir_trans_id'=>$data['mir_trans_id']]);
        $obj = new StdClass;
        if(!empty($oldData)):
            $obj = json_decode($oldData->observation_sample); 
        endif;
        $tbodyData="";$i=1; $theadData='';
                $theadData .= '<tr class="thead-info" style="text-align:center;">
                            <th rowspan="2" style="width:5%;">#</th>
                            <th rowspan="2">Parameter</th>
                            <th rowspan="2">Specification</th>
                            <th rowspan="2" style="width:15%">Instrument</th>
                            <th colspan="'.$data['sampling_qty'].'" style="text-align:center;">Observation on Samples</th>
                            <th rowspan="2" style="width:10%">Result</th>
                        </tr>
                        <tr style="text-align:center;">';
                        for($j=1; $j<=$data['sampling_qty']; $j++):
                            $theadData .= '<th>'.$j.'</th>';
                        endfor;    
                $theadData .='</tr>';
        if(!empty($paramData)):
            foreach($paramData as $row):
                $tbodyData.= '<tr>
                            <td style="text-align:center;">'.$i++.'</td>
                            <td style="width:10px;">'.$row->parameter.'</td>
                            <td style="width:10px;">'.$row->specification.'</td>   
                            <td style="width:10px;">'.$row->instrument.'</td>';
                            $c=0;
                            for($j=1; $j<=$data['sampling_qty']; $j++):
                                $value = (!empty($obj->{$row->id}[$c]) && $c < (count($obj->{$row->id})-1))?$obj->{$row->id}[$c]:'';
                                $tbodyData.=' <td style="min-width:100px;"><input type="text" name="sample'.($j).'_'.$row->id.'" class="form-control" value="'.$value.'"></td>';
                                $c++;
                            endfor;
                            $resultval =   !empty($obj)?(!empty($obj->{$row->id}[$c])?$obj->{$row->id}[count($obj->{$row->id})-1]:''):'';
                            $tbodyData.='<td style="min-width:80px;"><input name="result_'.$row->id.'" class="form-control text-center" value="'.$resultval.'"></td>';
                $tbodyData.='</tr>';
            endforeach;
        else:
            $tbodyData.= '<tr><td colspan="11" style="text-align:center;">No Data Found</td></tr>';
        endif;
        $this->printJson(['status'=>1,"tbodyData"=>$tbodyData,"theadData"=>$theadData]);
    }

    public function saveInwardQc(){ 
		$data = $this->input->post(); 
        $errorMessage = Array(); 

		if(empty($data['item_id']))
            $errorMessage['item_id'] = "Item is required.";

        $insParamData = $this->item->getInspectionParameter(['item_id'=>$data['item_id'],'control_method'=>'IIR']);
        if(count($insParamData) <= 0)
            $errorMessage['general'] = "Item Parameter is required.";

        $pre_inspection = Array(); $param_ids = Array();

        if(!empty($insParamData)):
            foreach($insParamData as $row):
                $param = Array();
                for($j = 1; $j <= $data['sampling_qty']; $j++):
                    $param[] = $data['sample'.$j.'_'.$row->id];
                    unset($data['sample'.$j.'_'.$row->id]);
                endfor;
                $param[] = $data['result_'.$row->id]; 
                $pre_inspection[$row->id] = $param;
				$param_ids[] = $row->id;
                unset($data['result_'.$row->id]);
            endforeach;
        endif;

        $data['observation_sample'] = json_encode($pre_inspection);
		$data['parameter_ids'] = implode(',',$param_ids);

		if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(empty($data['id'])){
                $data['trans_no'] = $this->gateInward->getNextIIRNo();
                $data['trans_number'] = "IIR".sprintf(n2y(date('Y'))."%03d",$data['trans_no']);
                $data['trans_date'] = date("Y_m-d");
                $data['created_by'] = $this->session->userdata('loginId');
            }
            
            $this->printJson($this->gateInward->saveInwardQc($data));
        endif;
	}


    public function getTestReport(){
        $data = $this->input->post();
		$this->data['grn_id'] = $data['grn_id'];
		$this->data['grn_trans_id'] = $data['id'];
        $this->data['dataRow'] = $this->gateInward->getTestReport(['grn_id'=>$data['grn_id']]);
        $this->data['supplierList'] = $this->party->getPartyList(['party_category'=>2]);
        $this->load->view($this->test_report,$this->data);
    }

    public function saveTestReport(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['agency_id'])){
            $errorMessage['agency_id'] = "Agency Name is required.";
        }
        if(empty($data['test_description'])){
            $errorMessage['test_description'] = "Description is required.";
        }
        if(empty($data['sample_qty'])){
            $errorMessage['sample_qty'] = "Sample Qty is required.";
        }
        

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if($_FILES['tc_file']['name'] != null || !empty($_FILES['tc_file']['name'])):
                $this->load->library('upload');
                $_FILES['userfile']['name']     = $_FILES['tc_file']['name'];
                $_FILES['userfile']['type']     = $_FILES['tc_file']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['tc_file']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['tc_file']['error'];
                $_FILES['userfile']['size']     = $_FILES['tc_file']['size'];

                $imagePath = realpath(APPPATH . '../assets/uploads/test_report/');
                $config = ['file_name' => "test_report".time(),'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path'	=>$imagePath];

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['item_image'] = $this->upload->display_errors();
                    $this->printJson(["status"=>0,"message"=>$errorMessage]);
                else:
                    $uploadData = $this->upload->data();
                    $data['tc_file'] = $uploadData['file_name'];
                endif;
            else:
                unset($data['tc_file']);
            endif;

            $data['created_by'] = $this->session->userdata('loginId');
            $this->printJson($this->gateInward->saveTestReport($data));
        endif;
    }

    public function testReportHtml(){
        $data = $this->input->post();
        $result = $this->gateInward->getTestReport($data);
		$i=1; $tbody='';
        
		if(!empty($result)):
			foreach($result as $row):
                $tdDownload = '';
                if(!empty($row->tc_file)) {  
                    $tdDownload = '<a href="'.base_url('assets/uploads/test_report/'.$row->tc_file).'" target="_blank"><i class="fa fa-download"></i></a>'; 
                }
                $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Process','res_function':'getTestReportHtml','fndelete':'deleteTestReport'}";

				$tbody.= '<tr>
						<td class="text-center">'.$i++.'</td>
                        <td>'.$row->name_of_agency.'</td>
                        <td>'.$row->test_description.'</td>
                        <td class="text-center">'.$row->test_report_no.'</td>
                        <td>'.$row->inspector_name.'</td>
                        <td class="text-center">'.floatval($row->sample_qty).'</td>
                        <td class="text-center">'.$row->test_result.'</td>
                        <td class="text-center">'.$tdDownload.'</td>
                        <td>'.$row->test_remark.'</td>
						<td class="text-center">
							<button type="button" onclick="trash('.$deleteParam.');" class="btn btn-sm btn-outline-danger waves-effect waves-light permission-remove"><i class="mdi mdi-trash-can-outline"></i></button>
						</td>
					</tr>';
			endforeach;
        else:
            $tbody = '<tr><td colspan="11" class="text-center">No data found.</td></tr>';
		endif;
        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
	}

    public function deleteTestReport(){ 
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$this->printJson($this->gateInward->deleteTestReport($data['id']));
		endif;
    }

	public function printGRN($id){
		$this->data['dataRow'] = $grnData = $this->gateInward->getGateInward($id);
		$this->data['partyData'] = $this->party->getParty(['id'=>$grnData->party_id]);
		$this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
		
		$logo = (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->company_logo):base_url('assets/images/logo.png');
        $this->data['letter_head'] =  (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->print_header):base_url('assets/images/letterhead_top.png');
				
		
		
		$prepare = $this->employee->getEmployee(['id'=>$grnData->created_by]);
		$this->data['dataRow']->prepareBy = $prepareBy = $prepare->emp_name.' <br>('.formatDate($grnData->created_at).')'; 
		$this->data['dataRow']->approveBy = $approveBy = '';
		if(!empty($poData->is_approve)){
			$approve = $this->employee->getEmployee(['id'=>$grnData->is_approve]);
			$this->data['dataRow']->approveBy = $approveBy .= $approve->emp_name.' <br>('.formatDate($grnData->approve_date).')'; 
		}

        $pdfData = $this->load->view('gate_inward/print',$this->data,true);
		
		$htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                <tr>
                    <td style="width:25%;">PO No. & Date : '.$grnData->trans_number.' ['.formatDate($grnData->trans_date).']</td>
                    <td style="width:25%;"></td>
                    <td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
                </tr>
            </table>';
        
		$mpdf = new \Mpdf\Mpdf();
		$pdfFileName='GRN-'.$id.'.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.05,array(120,45));
		$mpdf->showWatermarkImage = true;
		
		$mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',10,5,5,5,5,5,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
	}
}
?>