<?php
class DispatchPlan extends MY_Controller{

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Assembly Order";
		$this->data['headData']->controller = "dispatchPlan";        
        $this->data['headData']->pageUrl = "dispatchPlan";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'dispatchPlan']);
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader("pendDispatchPlan");
        $this->load->view('dispatch_plan/index',$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->dispatchPlan->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getPendingDispatchPlanData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function dispatchPlan($status = 1){
        $this->data['status'] = $status;
        $this->data['tableHeader'] = getSalesDtHeader("dispatchPlan");
        $this->load->view('dispatch_plan/plan_index',$this->data);
    }

    public function getPlanDTRows($status = 1){
        $data = $this->input->post();$data['status'] = $status;
        $result = $this->dispatchPlan->getPlanDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getDispatchPlanData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addDispatchPlan(){
        $data = $this->input->post();
        $plan_no = $this->sop->getNextPRCNo(2);
        $this->data['plan_number'] = 'DP/'.getYearPrefix('SHORT_YEAR').'/'.sprintf("%02d",$plan_no);
        $this->data['soData'] = $this->salesOrder->getSalesOrderItem(['id'=>$data['so_trans_id']]);
        $this->load->view('dispatch_plan/form',$this->data);
    }

    public function save(){
        $data = $this->input->post(); 
        $errorMessage = array();

        if(empty($data['qty'])){ $errorMessage['qty'] = "Qty is required."; }
        if(empty($data['plan_date'])){ $errorMessage['plan_date'] = "Date is required."; }
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:     
            $postData['masterData'] = [
                'id'=>"",
                'prc_type'=>2,
                'prc_date'=>$data['plan_date'],
                'so_trans_id'=>$data['so_trans_id'],
                'party_id'=>$data['party_id'],
                'item_id'=>$data['item_id'],
                'prc_qty'=>$data['qty'],
            ];  
            $postData['prcDetail'] = [
               'remark'=>$data['remark'],
            ];  
            $this->printJson($this->sop->savePrc($postData));
        endif;
    }


    public function addIssueRequisition($item_type=1) {
        $data = $this->input->post();
        $this->data['prc_id'] = $data['prc_id'];
        $issue_no = $this->store->getNextIssueNo();
        $this->data['issue_number'] = 'ISU/'.str_pad($issue_no, 5, '0', STR_PAD_LEFT);
        $this->data['issue_no'] = $issue_no;
        $this->load->view('dispatch_plan/prc_mtr_issue', $this->data);
        
    }

    public function getItemsForIssue(){
        $data = $this->input->post();
        $tbodyData = '';
        $bomData = $this->sop->getPrcBomShortage(['prc_id'=>$data['prc_id'],'stock_data'=>1]);
        if(!empty($bomData)){
            foreach($bomData As $row){
                $required_qty = $row->ppc_qty*$row->prc_qty;
                $issue_qty = ((!empty($row->issue_qty))?$row->issue_qty:0);
                $pending_issue =  ($required_qty - $issue_qty);

                $sort_qty = ($pending_issue - ($row->wip_qty+$row->stock_qty+$row->pending_req + $row->pending_po + $row->pending_grn));
                $sortage_qty = (($sort_qty>0)?$sort_qty:0);
                
                $selectBox = '<input type="checkbox" name="ref_id[]" id="ref_id_'.$row->id.'" class="filled-in chk-col-success BulkRequest" value="'.$row->item_id.'" data-qty="'.$sortage_qty.'" data-item_name = "'.$row->item_name.'"><label for="ref_id_'.$row->id.'"></label>';

                $tbodyData.='<tr>
                    <td>'.$selectBox.'</td>
                    <td><a href="javascript:void(0)" class="itemDetail" data-item_id = "'.$row->item_id.'" data-item_type = "'.$row->item_type.'"   data-item_name = "'.(((!empty($row->item_code))?$row->item_code.' ':'').$row->item_name).'">'.((!empty($row->item_code))?$row->item_code.' ':'').$row->item_name.' </td>
                    <td class="text-center">'.floatval($required_qty).' '.$row->uom.'</td>
                    <td class="text-center">'.floatval($issue_qty).' '.$row->uom.'</td>
                    <td class="text-center">'.(($pending_issue > 0) ? floatval($pending_issue) : 0).' '.$row->uom.'</td>
                    <td class="text-center">'.floatval($row->wip_qty).' '.$row->uom.'</td>
                    <td class="text-center">'.floatval($row->stock_qty).' '.$row->uom.'</td>
                    <td class="text-center">'.floatval($row->pending_req).' '.$row->uom.'</td>
                    <td class="text-center">'.floatval($row->pending_po).' '.$row->uom.'</td>
                    <td class="text-center">'.floatval($row->pending_grn).' '.$row->uom.'</td>
                    <td class="text-center">'.floatval($sortage_qty).' '.$row->uom.'</td>
                </tr>';
            }
        }
        if(empty($tbodyData)){
            $tbodyData = '<tr><th colspan="4" class="text-center">No data available.</th></tr>';
        }
        $this->printJson(['status'=>1,'tbodyData'=>$tbodyData]);
    }

    public function saveIssueRequisition() {

        $data = $this->input->post();
        $errorMessage = array(); $prcData = []; $data['bom_batch'] = "";$batchCount = 0;
       
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
        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $data['created_by'] = $this->session->userdata('loginId');
			$this->printJson($this->store->saveIssueRequisition($data));
        endif;
    }

    public function getItemMrpdata(){
        $data = $this->input->post();
        $this->data['qty'] = $data['qty'];
        $this->data['bomData'] = $this->sop->getItemBomShortage(['item_id'=>$data['item_id'],'stock_data'=>1]);
        
        $this->load->view("dispatch_plan/mrp_detail",$this->data);
    }
}
?>