<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getPurchaseDtHeader($page){
    /* Purchase Order Header */
	$data['purchaseOrders'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['purchaseOrders'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
	$data['purchaseOrders'][] = ["name"=>"PO. No."];
	$data['purchaseOrders'][] = ["name"=>"PO. Date"];
	$data['purchaseOrders'][] = ["name"=>"Supplier"];
	$data['purchaseOrders'][] = ["name"=>"Project"];
	$data['purchaseOrders'][] = ["name"=>"Product"];
	$data['purchaseOrders'][] = ["name"=>"Order Qty"];
	$data['purchaseOrders'][] = ["name"=>"Receive Qty"];
	$data['purchaseOrders'][] = ["name"=>"Pending Qty"];
    $data['purchaseOrders'][] = ["name"=>"Net Amount"];
	$data['purchaseOrders'][] = ["name"=>"Ind./Enq. No."];
    $data['purchaseOrders'][] = ["name"=>"Remark"];

    /* RM Sample Header */
    $data['rmSample'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['rmSample'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['rmSample'][] = ["name"=>"Unit"];    
	$data['rmSample'][] = ["name"=>"CH No."];
	$data['rmSample'][] = ["name"=>"CH Date"];
	$data['rmSample'][] = ["name"=>"Party Name"];
	$data['rmSample'][] = ["name"=>"RM Name"];
    $data['rmSample'][] = ["name"=>"Qty"];
    $data['rmSample'][] = ["name"=>"Remark"];
    $data['rmSample'][] = ["name"=>"To Location"];
    $data['rmSample'][] = ["name"=>"Responsible Person"];
    $data['rmSample'][] = ["name"=>"Created By"];
    $data['rmSample'][] = ["name"=>"Approved By"];
    $data['rmSample'][] = ["name"=>"Approved At"];
    $data['rmSample'][] = ["name"=>"Approved Note"];

    /* Purchase Indent Header */
    $masterCheckBox = '<input type="checkbox" id="masterSelect" class="filled-in chk-col-success BulkRequest" value="" disabled><label for="masterSelect">ALL</label>';
    
    $data['purchaseIndent'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['purchaseIndent'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['purchaseIndent'][] = ["name"=>$masterCheckBox,"textAlign"=>"center","class"=>"text-center no_filter","orderable"=>"false"];
	$data['purchaseIndent'][] = ["name"=>"Indent No."];
	$data['purchaseIndent'][] = ["name"=>"Indent Date"];
	$data['purchaseIndent'][] = ["name"=>"Project"];
    $data['purchaseIndent'][] = ["name"=>"Item Name"];
    $data['purchaseIndent'][] = ["name"=>"Req. Qty"];    
    $data['purchaseIndent'][] = ["name"=>"Delivery Date"];
    $data['purchaseIndent'][] = ["name"=>"Remark"];
    $data['purchaseIndent'][] = ["name"=>"Status"];

    /* Purchase Desk Header */
    $data['purchaseDesk'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['purchaseDesk'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
	$data['purchaseDesk'][] = ["name"=>"Enquiry No."];
	$data['purchaseDesk'][] = ["name"=>"Enquiry Date"];
	$data['purchaseDesk'][] = ["name"=>"Indent No."];
	$data['purchaseDesk'][] = ["name"=>"Supplier"];
	$data['purchaseDesk'][] = ["name"=>"Project"];
	$data['purchaseDesk'][] = ["name"=>"Item Name"];
	$data['purchaseDesk'][] = ["name"=>"Unit"];
    $data['purchaseDesk'][] = ["name"=>"Quantity"];
	$data['purchaseDesk'][] = ["name"=>"M.O.Q."];
	$data['purchaseDesk'][] = ["name"=>"Receive Qty"];
	$data['purchaseDesk'][] = ["name"=>"Pending Qty"];
    $data['purchaseDesk'][] = ["name"=>"Price"];
    $data['purchaseDesk'][] = ["name"=>"Lead Time (In Days)"];
    $data['purchaseDesk'][] = ["name"=>"Quot. No."];
    $data['purchaseDesk'][] = ["name"=>"Quot. Date"];
	$data['purchaseDesk'][] = ["name"=>"Delivery Date"];
    $data['purchaseDesk'][] = ["name"=>"Feasible"];
    $data['purchaseDesk'][] = ["name"=>"Remark"];
    $data['purchaseDesk'][] = ["name"=>"Quot. Remark"];

    /* Agreement Header */
    $data['agreement'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['agreement'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['agreement'][] = ["name"=>"Unit"];    
	$data['agreement'][] = ["name"=>"Agreement No."];
	$data['agreement'][] = ["name"=>"Agreement Date"];
	$data['agreement'][] = ["name"=>"Party Name"];
	$data['agreement'][] = ["name"=>"Item Name"];
    $data['agreement'][] = ["name"=>"Agreement Qty"];
    $data['agreement'][] = ["name"=>"Agreement Price"];
    $data['agreement'][] = ["name"=>"Received Qty"];
    $data['agreement'][] = ["name"=>"Remark"];

    return tableHeader($data[$page]);
}

function getPurchaseOrderData($data){
    $editButton = $deleteButton = $approveBtn = $rejectBtn = $shortClose = $reOpenBtn = '';

    if (in_array($data->trans_status,[0,3])) {
		if (empty($data->is_approve) && empty($data->trans_status)) {         
            $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('purchaseOrders/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

            $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Purchase Order'}";
            $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

            $approveParam = "{'postData':{'id' : ".$data->id.", 'trans_status' : 3,'is_approve':1,'msg':'Approved'},'fnsave':'approvePO','message':'Are you sure want to Approve this Purchase Order?'}";
            $approveBtn = '<a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Approve PO" flow="down" onclick="confirmStore('.$approveParam.');"><i class="mdi mdi-check"></i></a>';
        } else {
            $rejectParam = "{'postData':{'id' : ".$data->id.", 'trans_status' : 0,'is_approve':0,'msg':'Reject'},'fnsave':'approvePO','message':'Are you sure want to Reject this Purchase Order?'}";
            $rejectBtn = '<a class="btn btn-dark permission-modify" href="javascript:void(0)" datatip="Un-Approve" flow="down" onclick="confirmStore('.$rejectParam.');"><i class="mdi mdi-close"></i></a>';    

            $shortCloseParam = "{'postData':{'id' : ".$data->trans_child_id.", 'trans_status' : 2},'fnsave':'changeOrderStatus','message':'Are you sure want to Short Close this Purchase Order?'}";
            $shortClose = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Short Close" flow="down" onclick="confirmStore('.$shortCloseParam.');"><i class="mdi mdi-close-circle-outline"></i></a>'; 
        }
    } elseif ($data->trans_status == 2) {
        $reOpenParam = "{'postData':{'id' : ".$data->trans_child_id.", 'trans_status' : 3},'fnsave':'changeOrderStatus','message':'Are you sure want to Re-Open this Purchase Order?'}";
        $reOpenBtn = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Re-Open" flow="down" onclick="confirmStore('.$reOpenParam.');"><i class="mdi mdi-replay"></i></a>';
    }

    $printBtn = '<a class="btn btn-dribbble" href="'.base_url('purchaseOrders/printPO/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print"></i></a>';

    $action = getActionButton($approveBtn.$rejectBtn.$shortClose.$reOpenBtn.$printBtn.$editButton.$deleteButton);
    
    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->project_name,(!empty($data->item_code) ? '[ '.$data->item_code.' ] ' : '').$data->item_name,floatval($data->qty),floatval($data->dispatch_qty),floatval($data->pending_qty),moneyFormatIndia($data->net_amount),(!empty($data->enq_number) ? $data->enq_number : ''),$data->item_remark];
}

/* RM Sample Data */
function getRmSampleData($data){
    $editButton = $deleteButton = "";

    if($data->confirm_status == 0):
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editSample', 'title' : 'Update RM Sample','call_function':'edit'}";
        $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'RM Sample'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;

    $statusParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'sampleStatus', 'title' : 'RM Sample Status','call_function':'changeSmapleStatus','fnsave':'saveChangedSampleStatus'}";
    $statusButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Status" flow="down" onclick="modalAction('.$statusParam.');"><i class="mdi mdi-check"></i></a>';

    $action = getActionButton($statusButton.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->company_code,$data->trans_number,$data->trans_date,$data->party_name,$data->item_desc,$data->qty,$data->item_remark,$data->attachment,$data->responsible_person_name,$data->created_by_name,$data->conform_by_name,$data->confirm_date,$data->confirm_remark];
}

/* Purchase Indent Data  */
function getPurchaseIndentData($data){
    $editButton = $deleteButton = $approveBtn = $rejectBtn = $shortClose = $reOpenBtn = $selectBox = '';

    if (in_array($data->trans_status,[1,4])) {
        if (empty($data->approved_by)) {         
            $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-md-modal', 'form_id' : 'editPurchaeIndent', 'title' : 'Update Purchae Indent','call_function':'edit'}";
            $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

            $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Purchase Indent'}";
            $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

            $approveParam = "{'postData':{'id' : ".$data->id.", 'trans_status' : 4,'is_approve':1,'msg':'Approved'},'fnsave':'approveIndent','message':'Are you sure want to Approve this Purchase Indent?'}"; //09-04-25
            $approveBtn = '<a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Approve Indent" flow="down" onclick="confirmStore('.$approveParam.');"><i class="mdi mdi-check"></i></a>';
        } else {
            $rejectParam = "{'postData':{'id' : ".$data->id.", 'trans_status' : 1,'is_approve':0,'msg':'Reject'},'fnsave':'approveIndent','message':'Are you sure want to Reject this Purchase Indent?'}";//09-04-25
            $rejectBtn = '<a class="btn btn-dark permission-modify" href="javascript:void(0)" datatip="Un-Approve" flow="down" onclick="confirmStore('.$rejectParam.');"><i class="mdi mdi-close"></i></a>';    

            $shortCloseParam = "{'postData':{'id' : ".$data->id.", 'trans_status' : 3},'fnsave':'changeReqStatus','message':'Are you sure want to Short Close this Purchase Indent?'}";
            $shortClose = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Short Close" flow="down" onclick="confirmStore('.$shortCloseParam.');"><i class="mdi mdi-close-circle-outline"></i></a>'; 
            
            $selectBox = '<input type="checkbox" name="ref_id[]" id="ref_id_'.$data->sr_no.'" class="filled-in chk-col-success BulkRequest" value="'.$data->id.'"><label for="ref_id_'.$data->sr_no.'"></label>';
        }
    } elseif ($data->trans_status == 3) {
        $reOpenParam = "{'postData':{'id' : ".$data->id.", 'trans_status' : 4},'fnsave':'changeReqStatus','message':'Are you sure want to Re-Open this Purchase Indent?'}";
        $reOpenBtn = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Re-Open" flow="down" onclick="confirmStore('.$reOpenParam.');"><i class="mdi mdi-replay"></i></a>';
    }

    $action = getActionButton($approveBtn.$rejectBtn.$shortClose.$reOpenBtn.$editButton.$deleteButton);

    return [$action,$data->sr_no,$selectBox,$data->trans_number,formatDate($data->trans_date),$data->project_name,(!empty($data->item_code) ? '[ '.$data->item_code.' ] ' : '').$data->item_name,$data->qty.' ('.$data->uom.')',(!empty($data->delivery_date) ? formatDate($data->delivery_date) : ''),$data->remark,$data->trans_status_label];
}

/* Purchase Desk Table Data */
function getPurchaseDeskData($data){
	$editButton = $deleteButton = $approveBtn = $rejectBtn = $quoteBtn = $orderButton = $regButton = "";
	$postData = ['trans_number'=>$data->trans_number, 'party_id'=>$data->party_id, 'is_regenerate'=>0];

	if($data->trans_status == 1):
		$editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('purchaseDesk/editEnquiry/'.encodeurl($postData)).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

		$deleteParam = "{'postData':{'id' : ".$data->id.",'trans_number' : '".$data->trans_number."'},'message' : 'Enquiry','fndelete':'deleteEnquiry'}";
		$deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
		
		$quoteParam = "{'postData':{'id' : '".$data->id."', 'trans_number' : '".$data->trans_number."', 'party_id' : ".$data->party_id.",'party_name':'".$data->party_name."','trans_date' : '".$data->trans_date."'}, 'modal_id' : 'modal-xl', 'form_id' : 'quoteConfirm', 'title' : 'Convert Quotation','call_function':'quoteConfirm','fnsave' : 'saveQuotation'}";
		$quoteBtn = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Convert Quotation" flow="down" onclick="quoteConfirm('.$quoteParam.');"><i class="fa fa-file"></i></a>';
	elseif(($data->trans_status == 2 || $data->trans_status == 5) && $data->pending_qty > 0):        
        $ordParam = "{'postData':{'party_id' : ".$data->party_id."},'modal_id' : 'modal-lg', 'form_id' : 'getEnquiryList', 'title' : 'Create Purchase Order', 'call_function' : 'getEnquiryList', 'controller' : 'purchaseOrders', 'savebtn_text' : 'Create Order', 'js_store_fn' : 'createOrder'}";
        $orderButton = ' <a class="btn btn-dark btn-edit permission-modify" href="javascript:void(0)" datatip="Create Order" flow="down" onclick="modalAction('.$ordParam.')"><i class="fas fa-file"></i></a>';
	
		$rejectParam = "{'postData':{'id' : ".$data->id.",'enq_id': ".$data->enq_id.",'val' : 3,'msg':'Rejected'},'fnsave':'chageEnqStatus','message':'Are you sure want to Reject this Quotation?'}";
		$rejectBtn = '<a class="btn btn-dark permission-modify" href="javascript:void(0)" datatip="Reject" flow="down" onclick="confirmStore('.$rejectParam.');"><i class="mdi mdi-close"></i></a>';
	
	elseif($data->trans_status == 4 && $data->feasible == 1):
		$approveParam = "{'postData':{'id' : ".$data->quote_id.",'enq_id': ".$data->enq_id.",'val' : 2,'msg':'Approved'},'fnsave':'chageEnqStatus','message':'Are you sure want to Approve this Quotation?'}";
		$approveBtn = '<a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Approve" flow="down" onclick="confirmStore('.$approveParam.');"><i class="mdi mdi-check"></i></a>';

		$rejectParam = "{'postData':{'id' : ".$data->id.",'enq_id': ".$data->enq_id.",'val' : 3,'msg':'Rejected'},'fnsave':'chageEnqStatus','message':'Are you sure want to Reject this Quotation?'}";
		$rejectBtn = '<a class="btn btn-dark permission-modify" href="javascript:void(0)" datatip="Reject" flow="down" onclick="confirmStore('.$rejectParam.');"><i class="mdi mdi-close"></i></a>';
	endif;

	if($data->feasible == 2):
		$postData = ['trans_number'=>$data->trans_number, 'party_id'=>$data->party_id, 'is_regenerate'=>1];
		$regButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('purchaseDesk/editEnquiry/'.encodeurl($postData)).'" datatip="Regenerate" flow="down" ><i class="fas fa-sync-alt"></i></a>';
	endif;

    $printBtn = '<a class="btn btn-dribbble" href="'.base_url('purchaseDesk/printEnquiry/'.encodeurl($postData)).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print"></i></a>';

	$action = getActionButton($printBtn.$approveBtn.$rejectBtn.$quoteBtn.$orderButton.$regButton.$editButton.$deleteButton);
	
	return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->indent_number,$data->party_name,$data->project_name,(!empty($data->item_code) ? '['.$data->item_code.'] ' : '').$data->item_name,$data->uom,floatval($data->qty),(!empty($data->moq) ? floatval($data->moq) : ''),(!empty($data->po_qty) ? floatval($data->po_qty) : ''),(!empty($data->pending_qty) ? floatval($data->pending_qty) : ''),(!empty($data->price) ? floatval($data->price) : ''),$data->lead_time,$data->quote_no,formatDate($data->quote_date),formatDate($data->delivery_date),((!empty($data->feasible) && $data->feasible == 1) ? 'Yes' : ((!empty($data->feasible) && $data->feasible == 2) ? '<span class="text-danger">No</span>' : '')),$data->item_remark,$data->quote_remark];
}

/* Agreement Table Data */
function getAgreementData($data){
    $editButton = $deleteButton = '';

    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'agreement', 'title' : 'Update Agreement','call_function':'edit'}";
    $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

    $viewParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'agreement', 'title' : 'View Agreement Settlement','call_function':'viewAgreementAdjustment','button':'close'}";
    $viewButton = '<a class="btn btn-info btn-edit permission-modify" href="javascript:void(0)" datatip="View" flow="down" onclick="modalAction('.$viewParam.');"><i class="fas fa-eye"></i></a>';

    if(empty(floatval($data->rec_qty))):
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Agreement'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;

    $action = getActionButton($viewButton.$editButton.$deleteButton);
    return [$action,$data->sr_no,$data->company_code,$data->agreement_number,formatDate($data->agreement_date),$data->party_name,$data->item_name,floatval($data->qty),floatval($data->price),floatval($data->rec_qty),$data->remark];
}
?>