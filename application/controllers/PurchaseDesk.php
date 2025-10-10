<?php
class PurchaseDesk extends MY_Controller{

	public function __construct()	{
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "PURCHASE DESK";
		$this->data['headData']->controller = "purchaseDesk";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'purchaseDesk','tableName'=>'purchase_enquiry']);
	}
	
	public function index(){
		$this->data['headData']->pageTitle = "Purchase Desk";
        $this->load->view('purchaseDesk/purchase_desk',$this->data);
    }
    
	public function getPurchaseEnqList($fnCall = "Ajax"){
        $postData = $this->input->post();
		if(empty($postData)){$fnCall = 'Outside';}
        $next_page = 0;
		
		$enqData = Array();
		if(isset($postData['page']) AND isset($postData['start']) AND isset($postData['length']))
        {
            $enqData = $this->purchase->getPurchaseEnqList($postData);
            $next_page = intval($postData['page']) + 1;
            
        }
        else{ $enqData = $this->purchase->getPurchaseEnqList($postData); }
		
		$this->data['enqData'] = $enqData;
		$enqList ='';
		$enqList = $this->load->view('purchaseDesk/enq_list',$this->data,true);
        if($fnCall == 'Ajax'){$this->printJson(['enqList'=>$enqList,'next_page'=>$next_page]);}
		else{return $enqList;}
    }

	public function addEnquiry(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>2]);
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['final_category'=>0, 'ref_id'=>0]);
        $this->data['unitData'] = $this->item->itemUnits();
        $this->load->view('purchaseDesk/enq_form',$this->data);
    }

	public function getItemList(){
		$data = $this->input->post();
		$itemList = $this->item->getItemList($data);
		$options = '<option value="">Select Item</option>
					<option value="-1">New Item</option>';
		if(!empty($itemList)){
			foreach($itemList as $row){
				$options .= '<option value="'.$row->id.'">'.$row->item_name.'</option>';
			}
		}
		$this->printJson(['status'=>1, 'options'=>$options]);
	}

	public function getItemDetails(){
		$data = $this->input->post();
		$unitList = $this->item->itemUnits();
		$itemData = $this->item->getItem($data);

		$options = '<option value="0">--</option>';
		if(!empty($unitList)){
			foreach($unitList as $row){
				$selected = (!empty($itemData->unit_id) && $itemData->unit_id == $row->id) ? 'selected' : '';
				$disabled = (!empty($itemData->unit_id) && $itemData->unit_id != $row->id) ? 'disabled' : '';
				$options .= '<option value="'.$row->id.'" '.$selected.' '.$disabled.'>['.$row->unit_name.'] '.$row->description.'</option>';
			}
		}
		$this->printJson(['status'=>1, 'options'=>$options]);
	}

    public function saveEnquiry(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['trans_no'])){
            $errorMessage['trans_no'] = 'Enquiry No. is required.';
		}
		if(empty($data['trans_date'])){
            $errorMessage['trans_date'] = 'Enquiry Date is required.';
		}
        if(empty($data['party_id'])){
            $errorMessage['party_id'] = "Supplier Name is required.";
		}
        if(empty($data['item_type'])){
            $errorMessage['item_type'] = 'Item Type is required.';
		}
		if(empty($data['unit_id'])){
            $errorMessage['unit_id'] = 'Unit is required.';
		}
		if(empty($data['qty'])){
            $errorMessage['qty'] = 'Qty is required.';
		}
		if(empty($data['item_id'])){
            $errorMessage['item_id'] = 'Item Name is required.';
		}
		elseif($data['item_id'] == '-1' && empty($data['item_name'])){
			$errorMessage['item_name'] = 'New Item Name is required.';
		}

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:	
            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;

            $p_id = count($data['party_id']);
            if($p_id > 0){
                foreach($data['party_id'] as $row){
                    $data['party_id'] = $row;
                    $result = $this->purchase->saveEnquiry($data);
                }
            }else{
                $result = $this->purchase->saveEnquiry($data);
            }
            $this->printJson($result);
        endif;
    }

    public function editEnquiry(){
		$data = $this->input->post();
        $this->data['dataRow'] = $dataRow = $this->purchase->getPurchaseEnqList(['id'=>$data['id'], 'single_row'=>1]);    
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>2]);
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['final_category'=>0, 'ref_id'=>0]);
        $this->data['unitData'] = $this->item->itemUnits();

		$itemList = $this->item->getItemList(['item_type'=>$dataRow->item_type]);
		$options = '<option value="">Select Item</option>
					<option value="-1" '.((!empty($dataRow->item_id) && $dataRow->item_id == '-1') ? 'selected' : '').'>New Item</option>';
		if(!empty($itemList)){
			foreach($itemList as $row){
				$selected = (!empty($dataRow->item_id) && $dataRow->item_id == $row->id) ? 'selected' : '';
				$options .= '<option value="'.$row->id.'" '.$selected.'>'.$row->item_name.'</option>';
			}
		}
		$this->data['itemData'] = $options;
        $this->load->view('purchaseDesk/enq_form',$this->data);
    }

    public function deleteEnquiry(){
        $id = $this->input->post('id');
		if(empty($id)):
			$this->printJson(['status'=>0,'message'=>'Something went wrong...Please try again.']);
		else:
			$this->printJson($this->purchase->deleteEnquiry($id));
		endif;
    }

	public function getEnqDetail(){
        $postData = $this->input->post();
		$enqDetail ='';$itemDetail ='';
		
		$enqData = $this->data['enqData'] = $this->purchase->getPurchaseEnqList(['id'=>$postData['id'],'orderData'=>1, 'single_row'=>1]);		

		if(!empty($enqData))
		{
    		
    		
    		$enqDetail = $this->load->view('purchaseDesk/enq_detail',$this->data,true);
			$this->data['quoteData'] = $this->purchase->getQuotationData(['id'=>$postData['id']]);  
			
    		$quoteDetail = $this->load->view('purchaseDesk/quote_detail',$this->data,true);
    		
			$this->data['itemOrdData'] = $this->purchaseOrder->getPurchaseOrderItems(['item_id'=>$enqData->item_id,'order_by'=>1]);
    		$itemDetail = $this->load->view('purchaseDesk/item_detail',$this->data,true);
		}
        $this->printJson(['enqDetail'=>$enqDetail,'itemDetail'=>$itemDetail,'quoteDetail'=>$quoteDetail]);
    }

    public function createQuotation(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->purchase->getPurchaseEnqList(['id'=>$data['id'], 'single_row'=>1]);  
        $this->load->view('purchaseDesk/quotation_form',$this->data);
    }

	public function saveQuotation(){
        $data = $this->input->post();
        $errorMessage = array();

		if(empty($data['item_id'])){
            $errorMessage['item_id'] = 'Item Name is required.';
		}
		if(empty($data['qty'])){
            $errorMessage['qty'] = 'MOQ is required.';
		}
        if(empty($data['price'])){
            $errorMessage['price'] = 'Price is required.';
		}
		if(empty($data['quote_no'])){
            $errorMessage['quote_no'] = 'Quotation No. is required.';
		}
        if(empty($data['quote_date'])){
            $errorMessage['quote_date'] = "Quotation Date is required.";
		}

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:	
			$result = $this->purchase->saveQuotation($data);
			$result['enq_id'] = $data['enq_id'];
            $this->printJson($result);
        endif;
    }

	public function approveEnquiry(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->purchase->getQuotationData(['id'=>$data['id']]);  
        $this->load->view('purchaseDesk/approve_form',$this->data);
    }

    public function chageEnqStatus(){
		$data = $this->input->post();
		if(empty($data['id'])):
			$this->printJson(['status'=>0,'message'=>'Something went wrong...Please try again.']);
		else:
			$result = $this->purchase->chageEnqStatus($data);
			$result['enq_id'] = $data['enq_id'];
			$this->printJson($result);
		endif;
	}
	
	public function quotationComparison(){
		$this->data['itemList'] = $this->item->getItemList();
        $this->load->view('purchaseDesk/compare_form',$this->data);
	}

	public function getCompareList(){
		$data = $this->input->post();
		$itemData = $this->purchase->getQuotationData(['item_id'=>$data['item_id'], 'group_by'=>'purchase_quotation.party_id', 'order_by'=>1, 'multi_row'=>1,'trans_status'=>1]);

		$itemList = ''; $i=1;
		if(!empty($itemData)){
			foreach($itemData as $row){
				$itemList .= '
				<div href="#" class="media grid_item transition" data-category="transition">
					<div class="media-left">
						<input type="checkbox" id="md_checkbox_'.$i.'" name="party_id[]" class="filled-in chk-col-success partyCheck" value="'.$row->party_id.'"><label for="md_checkbox_'.$i.'" class="mr-10"></label>
						<input type="hidden" id="trans_id" value="'.$i.'">
					</div>
					<div class="media-body">
						<div class="d-inline-block">
							<h6><a type="button"><i class="mdi mdi-account"></i>&nbsp;'.$row->party_name.'</a></h6>
							<p class="text-muted1"><i class="mdi mdi-clock"></i>&nbsp;'.date('d-m-Y H:i:s', strtotime($row->created_at)).'</p>
						</div>
						<div></div>
					</div>
				</div>';
				$i++;
			}
		}else{
			$itemList .= '<div class="error">No pending entry found for compare..!!</div>';
		}
		$this->printJson(['itemList'=>$itemList]);
	}

	public function getPartyComparison(){
		$data = $this->input->post();
		$quotData = $this->purchase->getQuotationData(['party_id'=>$data['party_id'],'multi_row'=>1,'lastPOPrice'=>1,'trans_status'=>1]);
		$html='';
		if(!empty($quotData)){
			$html = '
			<table class="table jpExcelTable mb-5">
				<thead>
					<tr class="thead-info text-center">
						<th width="15%"></th>';
						foreach($quotData as $row){
							$html .= '<th >'.(!empty($row->party_name) ? $row->party_name : '').'</th>';
						}
					$html .= '</tr>
				</thead>
				<tbody>';
					$html .= '</tr>
						<tr class="text-center">
							<th class="text-left">Enq No/Date</th>';
							foreach($quotData as $row){
								$html .= '<td>'.$row->enq_number.'<hr style="margin:0px">'.date("d-m-Y",strtotime($row->enq_date)).'</td>';
							}
					$html .= '</tr>
						<tr class="text-center">
							<th class="text-left">Feasible</th>';
							foreach($quotData as $row){
								$html .= '<td>'.(($row->feasible == 2) ? 'No' : 'Yes').'</td>';
							}
					$html .= '</tr>
						<tr class="text-center">
							<th class="text-left">MOQ</th>';
							foreach($quotData as $row){
								$html .= '<td>'.(!empty($row->qty) ? floatval($row->qty) : 0).'</td>';
							}
					$html .= '</tr>
					<tr class="text-center">
						<th class="text-left">Lead Time</th>';
						foreach($quotData as $row){
							$html .= '<td>'.(!empty($row->lead_time) ? $row->lead_time : '').'</td>';
						}
					$html.='<tr class="text-center">
						<th class="text-left">Quote Price</th>';
						foreach($quotData as $row){
							$html .= '<td>'.(!empty($row->price) ? floatval($row->price) : 0).'</td>';
						}
					$html.='<tr class="text-center">
						<th class="text-left">Last Order Price</th>';
						foreach($quotData as $row){
							$html .= '<td>'.(!empty($row->price) ? floatval($row->last_po_price) : 0).'</td>';
						}
					$html .= '</tr>
					<tr class="text-center">
						<th class="text-left">Notes</th>';
						foreach($quotData as $row){
							$html .= '<td>'.(!empty($row->quote_remark) ? $row->quote_remark : '').'</td>';
						}
					$html .= '</tr>
					<tr class="text-center">
						<th class="text-left"></th>';
						$approveParam = "{'postData':{'id' : ".$row->id.", 'enq_id' : '".$row->enq_id."', 'val' : '2', 'msg' : 'Approved', 'party_id' : '".$row->party_id."', 'item_id' : '".$row->item_id."'},'message' : 'Are you sure you want to Approve Quotation ?','fnsave':'chageEnqStatus','res_function':'compareResponse'}";

						$rejectParam = "{'postData':{'id' : ".$row->id.", 'enq_id' : '".$row->enq_id."', 'val' : '3', 'msg' : 'Rejected'}, 'message' : 'Are you sure you want to Reject this Quotation ?', 'fnsave':'chageEnqStatus', 'js_store_fn' : 'storeEnquiry', 'res_function':'compareResponse'}";
						foreach($quotData as $row){
							$approveBtn = ' <a class="btn btn-sm btn-success" href="javascript:void(0)" datatip="Approve" flow="down" onclick="confirmPurchaseStore('.$approveParam.')"><i class="fa fa-check"></i> Approve</a>';
							$rejectBtn = ' <a class="btn btn-sm btn-danger" href="javascript:void(0)" datatip="Approve" flow="down" onclick="confirmPurchaseStore('.$rejectParam.')"><i class="mdi mdi-close"></i> Reject</a>';
							$html .= '<th>'.$approveBtn.$rejectBtn.'</th>';
						}
					$html .= '</tr>
				</tbody>
			</table>';
		}
		$this->printJson(['partyData'=>$html]);
	}

	public function addEnquiryFromRequest(){
		$id = $this->input->post('id');
        $this->data['req_id'] = $id;		
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>2]);
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['final_category'=>0, 'ref_id'=>0]);
        $this->data['unitData'] = $this->item->itemUnits();
        $dataRow = $this->purchaseIndent->getPurchaseRequest(['id'=>$id]);
		$dataRow->req_id = $dataRow->id;
		unset($dataRow->id, $dataRow->entry_type, $dataRow->trans_no, $dataRow->trans_prefix, $dataRow->trans_number, $dataRow->trans_date);

		$itemList = $this->item->getItemList(['item_type'=>$dataRow->item_type]);
		$options = '<option value="">Select Item</option>
					<option value="-1" '.((!empty($dataRow->item_id) && $dataRow->item_id == '-1') ? 'selected' : '').'>New Item</option>';
		if(!empty($itemList)){
			foreach($itemList as $row){
				$selected = (!empty($dataRow->item_id) && $dataRow->item_id == $row->id) ? 'selected' : '';
				$options .= '<option value="'.$row->id.'" '.$selected.'>'.$row->item_name.'</option>';
			}
		}
		$this->data['itemData'] = $options;		
		$this->data['dataRow'] = $dataRow;
        $this->load->view('purchaseDesk/enq_form',$this->data);
    }
}
?>