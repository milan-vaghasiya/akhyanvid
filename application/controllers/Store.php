<?php
class Store extends MY_Controller
{
    public function __construct(){
		parent::__construct(); 
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Material Issue";
		$this->data['headData']->controller = "store";
	}

    /* Material Issue Start */
    public function materialIssue(){
        $this->data['headData']->pageTitle = "Material Issue";
        $this->data['tableHeader'] = getStoreDtHeader('materialIssue');
        $this->load->view('store/issue_index', $this->data);
    }

    public function getIssueDTRows() {
        $data = $this->input->post();
        $result = $this->store->getIssueDTRows($data);
		$sendData = array(); $i = 1;
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $sendData[] = getMaterialIssueData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function issueMaterial(){
        $data = $this->input->post();
        $issue_no = $this->store->getNextIssueNo();
        $this->data['issue_number'] = 'ISU/'.$this->shortYear.'/'.$issue_no;
        $this->data['issue_no'] = $issue_no;
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['empData'] = $this->employee->getEmployeeList();
        $this->data['projectList']= $this->project->getProjectData(['trans_status'=>'1,2']);
        $this->load->view('store/issue_form', $this->data);
    }	
	
    public function getItemListDetail(){
        $data = $this->input->post();

        $options = '<option value="">Select Item Name</option>';
		
        if($data['product_type'] == 1){
            $itemList = $this->salesQuotation->getSalesQuotation(['project_id'=>$data['project_id']]);
            foreach($itemList as $row):
                $options .= '<option value="'.$row->item_id.'">'.(!empty($row->item_code)?'[ '.$row->item_code.' ] ':'').$row->item_name.'</option>';
			endforeach;
        }
        elseif($data['product_type'] == 2){
            $itemList = $this->item->getItemList(['item_type'=>"1"]);
            $options .= getItemListOption($itemList);
        }
       
        $this->printJson(['status'=>1,'options'=>$options]);
    }

    public function getItemStock(){
        $data = $this->input->post();
        $stockData = $this->store->getItemStockBatchWise(['item_id'=>$data['item_id'],'stock_required'=>1,'single_row'=>1]);

        $stock_qty = (!empty($stockData->qty)?floatval($stockData->qty).' <small>'.$stockData->uom.'</small>':0);

        //Get batch details
        $batchoption = '<option value="">Select Batch</option>';		
        $batchList = $this->itemStock->getItemStockBatchWise(['item_id'=>$data['item_id'],'group_by'=>'item_id,batch_no','stock_required'=>1]);
        foreach($batchList as $row){
            $selected = (!empty($data['item_id']) && $data['item_id'] == $row->item_id)?'selected':'';
            $batchoption .= '<option value="'.$row->batch_no.'" '.$selected.'>'.$row->batch_no .' (Stock Qty : '.$row->qty.')</option>';
        }
        $this->printJson(['status'=>1,'stock_qty'=>$stock_qty,'batchNo'=>$batchoption]);
    }

    public function saveIssuedMaterial() {

        $data = $this->input->post();
        $errorMessage = array(); 

        if(empty($data['item_id'])) {  $errorMessage['item_id'] = "Item is required";  }
		
        if(empty($data['project_id'])) {  $errorMessage['project_id'] = "Project is required";  }

        if(empty($data['batch_no'])) {  $errorMessage['batch_no'] = "Batch No is required";  }
		
		if(empty($data['issue_qty']) OR $data['issue_qty']<=0){ 
			$errorMessage['issue_qty'] = "Issue Qty is required."; 
		}else{
            $stockData = $this->store->getItemStockBatchWise(['item_id'=>$data['item_id'],'stock_required'=>1,'single_row'=>1]);
            $stock_qty = (!empty($stockData->qty) ? $stockData->qty : 0);
            if($data['issue_qty'] > $stock_qty){
                $errorMessage['issue_qty'] = "Stock not available."; 
            }
        }

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
			unset($data['product_type']);
			$this->printJson($this->store->saveIssuedMaterial($data));
        endif;
    }

    public function deleteIssuedItem(){
        $data = $this->input->post();
        if(empty($data)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->store->deleteIssuedItem($data));
        endif;
    }
    /* Material Issue End */

    /* Material Return Start */
	public function materialReturn(){
        $data = $this->input->post();        
        $this->data['issue_id'] = $data['id'];
        $this->load->view('store/material_return', $this->data);
    }

    public function saveMaterialReturn() {
        $data = $this->input->post();
        $errorMessage = array(); 
      
		if(empty($data['return_qty']) OR $data['return_qty']<=0){ $errorMessage['return_qty'] = "Return Qty is required."; }
        else{
            $issueData = $this->store->getIssueMaterialData(['id'=>$data['issue_id']]);
           
            if($data['return_qty'] > $issueData->issue_qty){
                $errorMessage['return_qty'] = "Issue Qty not available."; 
            }
        }
        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
			$this->printJson($this->store->saveMaterialReturn($data));
        endif;
    }

    public function deleteMaterialReturn(){
        $data = $this->input->post();
        if(empty($data)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->store->deleteMaterialReturn($data));
        endif;
    }
    /* Material Return End */

    /* Opening Stock Start */
    public function openingStock(){
        $this->data['headData']->pageTitle = "Opening Stock";
        $this->data['tableHeader'] = getStoreDtHeader('openingStock');
        $this->load->view('store/opening_stock_index', $this->data);
    }

    public function getOpeningStockDTRows() {
        $data = $this->input->post();
        $result = $this->store->getOpeningStockDTRows($data);
		$sendData = array(); $i = 1;
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $sendData[] = getOpeningStockData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

	public function addOpeningStock(){
        $data = $this->input->post(); 
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"1,2,3,10"]);
        $this->load->view('store/opening_stock_form', $this->data);
    }

    public function saveOpeningStock() {

        $data = $this->input->post();
        $errorMessage = array(); 
        if(empty($data['item_id'])) {  $errorMessage['item_id'] = "Item is required";  }
		if(empty($data['qty']) OR $data['qty']<=0){ $errorMessage['qty'] = "Stock Qty is required."; }
      
        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
			$this->printJson($this->store->saveOpeningStock($data));
        endif;
    }

    public function deleteOpeningStock(){
        $data = $this->input->post();
        if(empty($data)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->store->deleteOpeningStock($data));
        endif;
    }
    /* Opening Stock End */
}
?>