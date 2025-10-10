<?php
class Store extends MY_Controller
{
    private $indexPage = "store/index";
    private $formPage = "store/form";
    private $returnIndexPage = "store/return_index";
    private $closeFormPage = "store/close_form";
    private $issueIndex = "store/issue_index";
    private $issueForm = "store/issue_form";
    private $returnFormPage = "store/return_form";
    private $inspectIndexPage = "store/inspect_index";

    public function __construct(){
		parent::__construct(); 
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Store";
		$this->data['headData']->controller = "store";
		$this->data['headData']->pageUrl = "store";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'store']);
	}

	public function index($status=1){
        $this->data['status'] = $status;
        if($status == 3){
			$this->data['headData']->pageTitle = "Material Return";
            $this->data['tableHeader'] = getStoreDtHeader("returnRequisition");
        } else {
			$this->data['headData']->pageTitle = "Requisition";
            $this->data['tableHeader'] = getStoreDtHeader("requisition");
        }
        $this->load->view($this->indexPage, $this->data);
    }

	public function getDTRows($status=1){
		$data=$this->input->post();
		$data['status'] = $status; 
        $result = $this->store->getDTRows($data);
        $sendData = array();$i=1;
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getRequisitionData($row);                
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function returnIndex($status=1){
        $this->data['status'] = $status;
		$this->data['headData']->pageTitle = "Material Return";
        $this->data['tableHeader'] = getStoreDtHeader("returnRequisition");
        $this->load->view($this->returnIndexPage, $this->data);
    }

	public function getRequisitionList($fnCall = "Ajax"){
        $postData = $this->input->post();
		if(empty($postData)){$fnCall = 'Outside';}
        $next_page = 0;
		
		$reqData = Array();
		if(isset($postData['page']) AND isset($postData['start']) AND isset($postData['length']))
        {
            $reqData = $this->store->getRequestList($postData);
            $next_page = intval($postData['page']) + 1;
            
        }else{ $reqData = $this->store->getRequestList($postData); }
		
		$storeList='';$i=1;
		foreach($reqData as $row){
			$editButton = $deleteButton = $closeButton = "";
			if($row->status == 1) {
				if($row->issue_qty <= 0){
					$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editRequisition', 'title' : 'Update Requisition','js_store_fn':'customStore', 'fnsave' : 'save','res_function':'loadDesk','form_close':'YES'}";
					$editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

					$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Requisition'}";
					$deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
				}
				$closeParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'closeRequisition', 'call_function' : 'close', 'fnedit' : 'close', 'title' : 'Close Requisition', 'fnsave' : 'closeRequisition'}";
				$closeButton = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Close" flow="down" onclick="modalAction('.$closeParam.');"><i class="fa fa-close" ></i></a>';
			}
			$action = getActionButton($closeButton.$editButton.$deleteButton);
			
			$urgency='';
			if($row->urgency == 1){ $urgency = "Low"; }
			elseif($row->urgency == 2){ $urgency = "Medium"; }
			elseif($row->urgency == 3){ $urgency = "High"; }
			
			$storeList .='<tr>';
			$storeList .='<td>'.$action.'</td>';
			$storeList .='<td>'.$i++.'</td>';
			$storeList .='<td>'.$row->trans_number.'</td>';
			$storeList .='<td>'.formatDate($row->trans_date).'</td>';
			$storeList .='<td>'.$row->item_name.'</td>';
			$storeList .='<td>'.abs($row->req_qty).' '.$row->uom.'</td>'; 
			$storeList .='<td>'.abs($row->issue_qty).' '.$row->uom.'</td>';
			$storeList .='<td>'.$row->prc_number.'</td>';
			$storeList .='<td>'.$urgency.'</td>';
			$storeList .='</tr>';
		}
		if(empty($storeList)){
			$storeList .='<tr><td colspan="9" class="text-center">No data available in table</td></tr>';
		}
        if($fnCall == 'Ajax'){$this->printJson(['storeList'=>$storeList,'next_page'=>$next_page]);}
		else{return $leadDetail;}
    }

    public function getReqDTRows($status=1){
		$data=$this->input->post();
		$data['status'] = $status; 
        if($status == 3):
		    $result = $this->store->getIssueDTRows($data);
        else:
            $result = $this->store->getDTRows($data);
        endif;
        $sendData = array();$i=1;
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $row->status = $status;
            if($status == 3)
                $sendData[] = getReturnRequisitionData($row);
            else
                $sendData[] = getRequisitionData($row);
                
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addRequisition(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->store->getNextReqNo();
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['itemData'] = $this->item->getItemList(['item_type'=>'1,2,3']);
		$this->data['prcData'] = $this->sop->getPRCList(); //['status'=>'ALL']
        $this->load->view($this->formPage,$this->data);
    }

	public function save() {
        $data = $this->input->post();
        $errorMessage = array();

        if (empty($data['itemData'])){
            $errorMessage['general_error'] = "Item Detail required.";
        }

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $data['trans_prefix'] = $this->data['entryData']->trans_prefix;
            $this->printJson($this->store->saveRequest($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $reqData = $this->store->getRequest(['trans_number'=>$data['trans_number'], 'multi_rows'=>1]);
        $this->data['dataRow'] = $reqData[0];
        $this->data['reqItemList'] = $reqData;
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['itemData'] = $this->item->getItemList(['item_type'=>'1,2,3']);
		$this->data['prcData'] = $this->sop->getPRCList();
        $this->load->view($this->formPage,$this->data);
    }
	
    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->store->delete($id));
        endif;
    }

    public function close() {
        $this->data['id'] = $this->input->post('id');
        $this->load->view($this->closeFormPage,$this->data);
    }

    public function closeRequisition() {
        $data = $this->input->post();
        $this->printJson($this->store->closeRequest($data));
    }

    public function issueRequisition($status=1, $issue_type=1) {
        // $this->data['headData']->pageUrl = "store/issueRequisition";
		$this->data['headData']->pageTitle = "Material Issue";
        $this->data['status'] = $status;
        $this->data['issue_type'] = $issue_type;
        if($issue_type == 1)
            $this->data['tableHeader'] = getStoreDtHeader('pendingRequisition');
        else
            $this->data['tableHeader'] = getStoreDtHeader('issueRequisition');
        $this->load->view($this->issueIndex, $this->data);
    }

    public function getIssueDTRows($status=1, $issue_type=1) {
        $data = $this->input->post();
        $data['status'] = $status;

        if($issue_type == 1)
            $result = $this->store->getDTRows($data);
        else
            $result = $this->store->getIssueDTRows($data);

		$sendData = array(); $i = 1;
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $row->loginId = $this->session->userdata('loginId');
            if($issue_type == 1)
                $sendData[] = getPendingRequisitionData($row);
            else
                $sendData[] = getIssueRequisitionData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addIssueRequisition() {
        $data = $this->input->post();
        $issue_no = $this->store->getNextIssueNo();
        $this->data['issue_prefix'] = 'ISU/'.str_pad($issue_no, 5, '0', STR_PAD_LEFT);
        $this->data['issue_no'] = $issue_no;
		
		if(isset($data['trans_no'])){
			$this->data['reqData'] = $this->store->getRequestList(['trans_no' => $data['trans_no'],'status'=>'pending']);
		}else{
			$this->data['itemData'] = $this->item->getItemList(['item_type'=>'1,2,3']);
		}
		$this->data['empData'] = $this->employee->getEmployeeList();
		
        if(isset($data['prc_id'])){
            $this->data['prc_id'] = $data['prc_id'];
            $this->load->view('store/prc_mtr_issue', $this->data);
        }else{
			$this->data['issue_type'] = (!empty($data['issue_type']) ? $data['issue_type'] : '');
            $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['store_type'=>'0,15','final_location'=>1]); 
            $this->load->view($this->issueForm, $this->data);
        }
    }

    public function getBatchWiseStock() {
        $item_id = $this->input->post('item_id');
		
        $batchData = $this->itemStock->getItemStockBatchWise(["item_id" => $item_id,'stock_required'=>1,'group_by'=>'location_id']); 
        $tbodyData='';$i=1;
        if (!empty($batchData)) {
            foreach ($batchData as $row) {
                $tbodyData .= '<tr>';
                $tbodyData .= '<td>'.$row->location.'</td>';
                $tbodyData .= '<td>'.floatVal($row->qty).'</td>';
                $tbodyData .= '<td>
						<input type="text" name="batch_qty[]" class="form-control batchQty floatOnly" min="0" value="" />
						<div class="error batch_qty_' . $i . '"></div>
						<input type="hidden" name="location_id[]" id="location_' . $i . '" value="' . $row->location_id . '" />
					</td>
				</tr>';
                $i++;
            }
        } else {
            $tbodyData .= "<td colspan='6' class='text-center'>No Data</td>";
        }
        $this->printJson(['status' => 1, 'tbodyData' => $tbodyData]);
    }
	
	public function saveIssueRequisition() {

        $data = $this->input->post();
        $errorMessage = array(); $prcData = []; 
       
        if(isset($data['location_id'])){
            if(empty(array_sum($data['batch_qty']))){$errorMessage['table_err'] = "Batch Details is required.";}
            
            foreach($data['location_id'] AS $key=>$location_id){
                if($data['batch_qty'][$key] > 0){
                    $stockData = $this->itemStock->getItemStockBatchWise(["item_id" => $data['item_id'],'stock_required'=>1,'group_by'=>'location_id','location_id'=>$data['location_id'][$key],'single_row'=>1]);
                    $stock_qty = (!empty($stockData)) ? $stockData->qty : 0;
                    if($data['batch_qty'][$key] > $stock_qty){
                        $errorMessage['batch_qty_'.$key] = "Stock not available.";

                    }
                }else{
                    unset($data['batch_qty'][$key],$data['location_id'][$key]);
                }
            }
        } else {
            $errorMessage['table_err'] = "Batch Details is required.";
        }

		if(empty($data['issued_to'])){
			$errorMessage['issued_to'] = "Issued To is required.";
		}

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $data['created_by'] = $this->session->userdata('loginId');
			$this->printJson($this->store->saveIssueRequisition($data));
        endif;
    }


    public function deleteIssueRequisition() {
        $data = $this->input->post();
        if(empty($data)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $issueEntryData = $this->transMainModel->getEntryType(['controller'=>'store/prcMaterial']);
            $data['entry_type'] = $issueEntryData->id;
            $this->printJson($this->store->deleteIssueRequisition($data));
        endif;
    }

    public function return() {
        $id = $this->input->post('id');
        $this->data['dataRow'] = $this->store->getIssueRequest(['id'=>$id]);
        $this->load->view($this->returnFormPage,$this->data);
    }

    public function saveReturnReq() {
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['trans_date']))
            $errorMessage['trans_date'] = "Return Date is required.";

        if(empty($data['usable_qty']) && empty($data['missed_qty']) && empty($data['broken_qty']) && empty($data['scrap_qty'])){ //
            // empty($data['fresh_qty']) && 
            $errorMessage['genral_error'] = "Return Qty. is Required";
        } else {
            $data['usable_qty'] = (!empty($data['usable_qty'])?$data['usable_qty']:0);
            // $data['fresh_qty'] = (!empty($data['fresh_qty'])?$data['fresh_qty']:0);
            $data['missed_qty'] = (!empty($data['missed_qty'])?$data['missed_qty']:0);
            $data['broken_qty'] = (!empty($data['broken_qty'])?$data['broken_qty']:0);
            $data['scrap_qty'] = (!empty($data['scrap_qty'])?$data['scrap_qty']:0);
            
            $data['total_qty'] = $data['usable_qty'] + $data['missed_qty'] + $data['broken_qty'] + $data['scrap_qty']; //$data['fresh_qty'] + 
            
            if($data['total_qty'] > ($data['issue_qty'] - $data['return_qty'])){
                $errorMessage['genral_error'] = "Return Qty. is not Valid";
            }
        }

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            unset($data['issue_qty']);
            unset($data['return_qty']);
            $data['created_by'] = $this->session->userdata('loginId');
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['trans_type'] = 1;
            $this->printJson($this->store->saveReturnReq($data));
        endif;
    }

    public function inspection($trans_type = 1){
		$this->data['headData']->pageTitle = "Inspection";
        $this->data['trans_type'] = $trans_type;
        $this->data['tableHeader'] = getStoreDtHeader('inspection');
        $this->load->view($this->inspectIndexPage, $this->data);
    }

    public function getInspDTRows($trans_type = 1){
		$data=$this->input->post();
        $data['trans_type'] = $trans_type;
		$result = $this->store->getInspDTRows($data);
        $sendData = array();$i=1;
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $row->trans_type = $trans_type;
            $sendData[] = getInspectionData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addInspection() {
        $data = $this->input->post();
        $this->data['dataRow'] = $this->store->getIssueRequest(['id'=>$data['issue_id']]);
        $this->data['locationData'] = $this->storeLocation->getStoreLocationList(['final_location'=>1]);
        $this->data['mtData'] = $this->store->getMaterialData(['id'=>$data['id']]);
        $this->load->view($this->returnFormPage,$this->data);
    }

    public function saveInspection() {
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['trans_date']))
            $errorMessage['trans_date'] = "Trans Date is required.";

        if(empty($data['location_id']))
            $errorMessage['location_id'] = "Location is required.";

        if(empty($data['usable_qty']) && empty($data['missed_qty']) && empty($data['broken_qty']) && empty($data['scrap_qty'])){
            $errorMessage['genral_error'] = "Inspect Qty. is Required";
        } else {
            $data['usable_qty'] = (!empty($data['usable_qty'])?$data['usable_qty']:0);
            $data['missed_qty'] = (!empty($data['missed_qty'])?$data['missed_qty']:0);
            $data['broken_qty'] = (!empty($data['broken_qty'])?$data['broken_qty']:0);
            $data['scrap_qty'] = (!empty($data['scrap_qty'])?$data['scrap_qty']:0);
            
            $data['insp_qty'] = $data['usable_qty'] + $data['missed_qty'] + $data['broken_qty'] + $data['scrap_qty'];
            
            if($data['insp_qty'] != $data['total_qty']) {
                $errorMessage['genral_error'] = "Inspect Qty. is not Valid";
            }
        }

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            unset($data['issue_qty']);
            unset($data['return_qty']);
            $data['created_by'] = $this->session->userdata('loginId');
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['trans_type'] = 2;
            
			$issueEntryData = $this->transMainModel->getEntryType(['controller'=>'store/inspection']);
            $data['entry_type'] = $issueEntryData->id;
			
            $this->printJson($this->store->saveReturnReq($data));
        endif;
    }

	public function materialRequest() {
        $data = $this->input->post();
		
        $this->data['md_prc_id'] = $data['prc_id'];
        $this->data['md_item_id'] = $data['item_id'];
        $this->data['md_req_qty'] = $data['req_qty'];
		
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->store->getNextReqNo();
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['itemData'] = $this->item->getItemList(['item_type'=>'1,2,3']);
		$this->data['prcData'] = $this->sop->getPRCList(['status'=>'ALL']);
        $this->load->view($this->formPage,$this->data);
    }

    public function prcMaterial() {
        $this->data['headData']->pageUrl = "store/prcMaterial";
		$this->data['headData']->pageTitle = "Material Issue";
        $this->data['tableHeader'] = getStoreDtHeader('prcMaterial');
        $this->load->view('store/prc_material_index', $this->data);
    }

    public function getPrcMaterialDTRows() {
        $data = $this->input->post();
        $data['prc_type'] = 1;
        $result = $this->store->getPrcMaterialDTRows($data);
		$sendData = array(); $i = 1;
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $row->loginId = $this->session->userdata('loginId');
            $sendData[] = getPrcMaterialData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }
	
	public function getItemsForIssue(){
        $data = $this->input->post();
        $tbodyData = '';
        
		$bomData = $this->sop->getPrcBomData(['prc_id'=>$data['prc_id'],'stock_data'=>1]);
		if(!empty($bomData)){
			foreach($bomData As $row){
				$prcRq = $row->ppc_qty*$row->prc_qty;
				$prcIq = ((!empty($row->issue_qty))?$row->issue_qty:0);
				$prcPq =  ($prcRq - $prcIq);
				$tbodyData.='<tr>
					<td><a href="javascript:void(0)" class="itemDetail" data-item_id = "'.$row->item_id.'" data-item_type = "'.$row->item_type.'"   data-item_name = "'.(((!empty($row->item_code))?$row->item_code.' ':'').$row->item_name).'">'.((!empty($row->item_code))?$row->item_code.' ':'').$row->item_name.' </td>
					<td class="text-center">'.floatval($prcRq).' '.$row->uom.'</td>
					<td class="text-center">'.floatval($prcIq).' '.$row->uom.'</td>
					<td class="text-center">'.(($prcPq > 0) ? floatval($prcPq) : 0).' '.$row->uom.'</td>
				</tr>';
			}
		}
        
        if(empty($tbodyData)){
            $tbodyData = '<tr><th colspan="4" class="text-center">No data available.</th></tr>';
        }
        $this->printJson(['status'=>1,'tbodyData'=>$tbodyData]);
    }

    function printPrcReqMaterial($id){
        $this->data['prcData'] = $this->sop->getPRCDetail(['prc_id'=>$id]);
        $this->data['bomData'] = $this->sop->getPrcBomData(['prc_id'=>$id, 'stock_data'=>1]);
		$companyData = $this->purchaseOrder->getCompanyInfo();

		$letter_head = base_url('assets/images/letterhead_top.png');
			
		$pdfData = $this->load->view('store/print_prc_material',$this->data,true);	
		
		$htmlHeader = '<img src="'.$letter_head.'" class="img">';
		$htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
            <tr>
                <td style="width:25%;"></td>
                <td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
            </tr>
        </table>';
		
		$mpdf = new \Mpdf\Mpdf();
		$pdfFileName='PRC-'.$id.'.pdf';
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.03,array(120,45));
		$mpdf->showWatermarkImage = true;
		$mpdf->SetProtection(array('print'));
		
		$mpdf->SetHTMLHeader($htmlHeader);
		$mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',5,5,30,30,5,5,'','','','','','','','','','A5-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
	}

    public function assemblyMaterial() {
        $this->data['headData']->pageUrl = "store/assemblyMaterial";
		$this->data['headData']->pageTitle = "Assembly Material Issue";
        $this->data['tableHeader'] = getStoreDtHeader('prcMaterial');
        $this->load->view('store/assembly_material_index', $this->data);
    }

    public function getAssemblyMaterialDTRows() {
        $data = $this->input->post();
        $data['prc_type'] = 2;
        $result = $this->store->getPrcMaterialDTRows($data);
		$sendData = array(); $i = 1;
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $row->loginId = $this->session->userdata('loginId');
            $sendData[] = getPrcMaterialData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }
}