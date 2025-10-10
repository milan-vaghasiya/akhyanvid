<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getPurchaseDtHeader($page){

    /* Purchase Order Header */
    $data['purchaseOrders'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['purchaseOrders'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
	$data['purchaseOrders'][] = ["name"=>"PO. No."];
	$data['purchaseOrders'][] = ["name"=>"PO. Date"];
	$data['purchaseOrders'][] = ["name"=>"Party Name"];
	$data['purchaseOrders'][] = ["name"=>"Item Name"];
    $data['purchaseOrders'][] = ["name"=>"Order Qty"];
    $data['purchaseOrders'][] = ["name"=>"Remark"];
	
	 /* Purchase Request Header */
    $data['purchaseRequest'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['purchaseRequest'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['purchaseRequest'][] = ["name"=>"Indent No"];
    $data['purchaseRequest'][] = ["name"=>"Indent Date"];
    $data['purchaseRequest'][] = ["name"=>"Item Name"];
    $data['purchaseRequest'][] = ["name"=>"Req. Qty"];    
    $data['purchaseRequest'][] = ["name"=>"Delivery Date"];
    $data['purchaseRequest'][] = ["name"=>"Remark"];
    $data['purchaseRequest'][] = ["name"=>"Status"];

     /* Purchase Indent Header */
    $masterCheckBox = '<input type="checkbox" id="masterSelect" class="filled-in chk-col-success BulkRequest" value=""><label for="masterSelect">ALL</label>';
    
    $data['purchaseIndent'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['purchaseIndent'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['purchaseIndent'][] = ["name"=>$masterCheckBox,"style"=>"width:10%;","textAlign"=>"center","orderable"=>"false"];
    $data['purchaseIndent'][] = ["name"=>"Indent No"];
	$data['purchaseIndent'][] = ["name"=>"Indent Date"];
    $data['purchaseIndent'][] = ["name"=>"Item Name"];
    $data['purchaseIndent'][] = ["name"=>"Req. Qty"];    
    $data['purchaseIndent'][] = ["name"=>"Delivery Date"];
    $data['purchaseIndent'][] = ["name"=>"Remark"];
    $data['purchaseIndent'][] = ["name"=>"Status"];

    $masterCheckBox = '<input type="checkbox" id="masterSelect" class="filled-in chk-col-success BulkRequest" value=""><label for="masterSelect">ALL</label>';
    $data['purchaseShortage'][] = ["name"=>$masterCheckBox,"class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
	$data['purchaseShortage'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center"];
    $data['purchaseShortage'][] = ["name"=>"Item","textAlign"=>"left"];
    $data['purchaseShortage'][] = ["name"=>"Required Qty","textAlign"=>"center"];
    $data['purchaseShortage'][] = ["name"=>"Stock Qty"];
    $data['purchaseShortage'][] = ["name"=>"Pending Request"];
    $data['purchaseShortage'][] = ["name"=>"Pending PO"];
    $data['purchaseShortage'][] = ["name"=>"Pending GRN QC"];
    $data['purchaseShortage'][] = ["name"=>"Shortage Qty"];

    return tableHeader($data[$page]);
}

function getPurchaseOrderData($data){
    $shortClose =""; $editButton="";  $deleteButton =""; $printBtn = $approveBtn ="";
   
    if($data->trans_status == 0):
        if(empty($data->is_approve)):
            $approveParam = "{'postData':{'id' : ".$data->id.",'is_approve':1,'msg':'Approved'},'fnsave':'approvePurchaseOrder','message':'Are you sure want to Approve this Purchase Order?'}";
            $approveBtn = '<a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Approve PO" flow="down" onclick="confirmStore('.$approveParam.');"><i class="mdi mdi-check"></i></a>';    
        
            $shortCloseParam = "{'postData':{'id' : ".$data->po_trans_id.", 'trans_status' : 2},'fnsave':'changeOrderStatus','message':'Are you sure want to Short Close this Purchase Order?'}";
            $shortClose = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Short Close" flow="down" onclick="confirmStore('.$shortCloseParam.');"><i class="mdi mdi-close-circle-outline"></i></a>';    

            $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('purchaseOrders/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

            $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Purchase Order'}";
            $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
        endif;
    endif;
    
    if($data->trans_status == 3){
        $shortCloseParam = "{'postData':{'id' : ".$data->po_trans_id.", 'trans_status' : 2},'fnsave':'changeOrderStatus','message':'Are you sure want to Short Close this Purchase Order?'}";
        $shortClose = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Short Close" flow="down" onclick="confirmStore('.$shortCloseParam.');"><i class="mdi mdi-close-circle-outline"></i></a>'; 
    }
    
    $printBtn = '<a class="btn btn-success btn-info" href="'.base_url('purchaseOrders/printPO/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';

    $action = getActionButton($approveBtn.$shortClose.$printBtn.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->item_name,floatval($data->qty).' '.$data->unit_name,$data->item_remark];
}

/* Purchase Request Data  */
function getPurchaseRequestData($data){
    $shortClose =""; $editButton="";  $deleteButton ="";
    if($data->order_status == 1):
        $shortCloseParam = "{'postData':{'id' : ".$data->id.", 'order_status' : 3},'fnsave':'changeReqStatus','message':'Are you sure want to Short Close this Purchase Request?'}";
        $shortClose = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Short Close" flow="down" onclick="confirmStore('.$shortCloseParam.');"><i class="mdi mdi-close-circle-outline"></i></a>';    

        $editParam = "{'postData':{'trans_number' : '".$data->trans_number."'}, 'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editPurchaeRequest', 'title' : 'Update PurchaeRequest','call_function':'edit'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Purchase Request'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;
  
    $action = getActionButton($shortClose.$editButton.$deleteButton);
   return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->item_name,floatval($data->qty).' '.$data->uom,formatDate($data->delivery_date),$data->remark,$data->order_status_label]; 
}

/* Purchase Indent Data  */
function getPurchaseIndentData($data){
    $shortClose=""; $selectBox =""; $enqBtn="";
    if($data->order_status == 1):
		$selectBox = '<input type="checkbox" name="ref_id[]" id="ref_id_'.$data->sr_no.'" class="filled-in chk-col-success BulkRequest" value="'.$data->id.'"><label for="ref_id_'.$data->sr_no.'"></label>';   

        $shortCloseParam = "{'postData':{'id' : ".$data->id.", 'order_status' : 3},'fnsave':'changeReqStatus','message':'Are you sure want to Short Close this Purchase Request?'}";
        $shortClose = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Short Close" flow="down" onclick="confirmStore('.$shortCloseParam.');"><i class="mdi mdi-close-circle-outline"></i></a>'; 
        
        $enqParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-md-modal', 'call_function':'addEnquiryFromRequest', 'form_id' : 'addEnquiryFromRequest', 'title' : 'New Enquiry', 'fnsave' : 'saveEnquiry', 'txt_editor' : 'item_remark', 'controller' : 'purchaseDesk'}";
        $enqBtn = '<a class="btn btn-success permission-modify" href="javascript:void(0)" datatip="Add Enquiry" flow="down" onclick="modalAction('.$enqParam.');"><i class="fa fa-plus"></i></a>';
	endif;

    $action = getActionButton($shortClose.$enqBtn);
    return [$action,$data->sr_no,$selectBox,$data->trans_number,$data->trans_date,$data->item_name,floatval($data->qty).' '.$data->uom,(!empty($data->delivery_date) ? formatDate($data->delivery_date) : ''),$data->remark,$data->order_status_label]; 
}

function getPurchaseShortageData($data){
    $sort_qty = ($data->required_qty - ($data->stock_qty));
    $sortage_qty = $data->required_qty - ($data->stock_qty +$data->pending_req + $data->pending_po + $data->pending_grn);
	$selectBox = '<input type="checkbox" name="ref_id[]" id="ref_id_'.$data->sr_no.'" class="filled-in chk-col-success BulkRequest" value="'.$data->id.'" data-qty="'.$sortage_qty.'"  data-item_name="'.$data->item_name.'"><label for="ref_id_'.$data->sr_no.'"></label>';

    return [$selectBox,$data->sr_no,$data->item_code.' '.$data->item_name,floatval($data->required_qty).' '.$data->uom,floatval($data->stock_qty).' '.$data->uom,floatval($data->pending_req).' '.$data->uom,floatval($data->pending_po).' '.$data->uom,floatval($data->pending_grn).' '.$data->uom,floatval($sortage_qty).' '.$data->uom]; 
}
?>