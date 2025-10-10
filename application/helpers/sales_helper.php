<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getSalesDtHeader($page){

    /* Sales Enquiry Header */
    $data['salesEnquiry'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['salesEnquiry'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['salesEnquiry'][] = ["name"=>"SE. No."];
    $data['salesEnquiry'][] = ["name"=>"SE. Date"];
    $data['salesEnquiry'][] = ["name"=>"Customer Name"];
    $data['salesEnquiry'][] = ["name"=>"Item Name"];
    $data['salesEnquiry'][] = ["name"=>"Qty"];

    /* Sales Quotation Header */
    $data['salesQuotation'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['salesQuotation'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
	$data['salesQuotation'][] = ["name"=>"Rev. No.","textAlign"=>"center"];
	$data['salesQuotation'][] = ["name"=>"SQ. No."];
	$data['salesQuotation'][] = ["name"=>"SQ. Date"];
	$data['salesQuotation'][] = ["name"=>"Customer Name"];
	$data['salesQuotation'][] = ["name"=>"Item Name"];
    $data['salesQuotation'][] = ["name"=>"Qty"];
    $data['salesQuotation'][] = ["name"=>"Price"];
    $data['salesQuotation'][] = ["name"=>"Approved By"];
    $data['salesQuotation'][] = ["name"=>"Approved Date"];

    /* Sales Order Header */
    $data['salesOrders'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['salesOrders'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
	//$data['salesOrders'][] = ["name"=>"Ordered By"];
	$data['salesOrders'][] = ["name"=>"SO. No."];
	$data['salesOrders'][] = ["name"=>"SO. Date"];
	$data['salesOrders'][] = ["name"=>"Customer Name"];    
	$data['salesOrders'][] = ["name"=>"Party Address"];
	$data['salesOrders'][] = ["name"=>"Item Name"];
	$data['salesOrders'][] = ["name"=>"Price"];
	$data['salesOrders'][] = ["name"=>"Stock Qty"];
    $data['salesOrders'][] = ["name"=>"Order Qty"];
    $data['salesOrders'][] = ["name"=>"Dispatch Qty"];
    $data['salesOrders'][] = ["name"=>"Pending Qty"];

    /* Party Order Header */
    $data['partyOrders'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['partyOrders'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
	$data['partyOrders'][] = ["name"=>"Order Status"];
	$data['partyOrders'][] = ["name"=>"SO. No."];
	$data['partyOrders'][] = ["name"=>"SO. Date"];
	$data['partyOrders'][] = ["name"=>"Item Name"];
	$data['partyOrders'][] = ["name"=>"Brand Name"];
    $data['partyOrders'][] = ["name"=>"Order Qty"];
    $data['partyOrders'][] = ["name"=>"Received Qty"];
    $data['partyOrders'][] = ["name"=>"Pending Qty"];

    /* Dispatch Order Header */
    $data['dispatchOrder'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['dispatchOrder'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
	$data['dispatchOrder'][] = ["name"=>"DO. No."];
	$data['dispatchOrder'][] = ["name"=>"DO. Date"];
	$data['dispatchOrder'][] = ["name"=>"SO. No."];
	$data['dispatchOrder'][] = ["name"=>"SO. Date"];
	$data['dispatchOrder'][] = ["name"=>"Delivery Date"];
	$data['dispatchOrder'][] = ["name"=>"Customer Name"];
	$data['dispatchOrder'][] = ["name"=>"Item Name"];
	$data['dispatchOrder'][] = ["name"=>"Order Qty."];
	/* $data['dispatchOrder'][] = ["name"=>"Link Qty."];
	$data['dispatchOrder'][] = ["name"=>"Pending Qty."]; */
    $data['dispatchOrder'][] = ["name"=>"Final Packing Qty."];
	$data['dispatchOrder'][] = ["name"=>"Pending Qty."];
 
    /* Delivery Challan Header */
    $data['deliveryChallan'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['deliveryChallan'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
	$data['deliveryChallan'][] = ["name"=>"DC. No."];
	$data['deliveryChallan'][] = ["name"=>"DC. Date"];
	$data['deliveryChallan'][] = ["name"=>"Customer Name"];
	$data['deliveryChallan'][] = ["name"=>"Remark"];

    /* Estimate [Cash] Header */
    $data['estimate'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['estimate'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
	$data['estimate'][] = ["name"=>"Inv No."];
	$data['estimate'][] = ["name"=>"Inv Date"];
	$data['estimate'][] = ["name"=>"Customer Name"];
	$data['estimate'][] = ["name"=>"Taxable Amount"];
    $data['estimate'][] = ["name"=>"Net Amount"];

    /* Estimate Payments [Cash] Header */
    $data['estimatePayment'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['estimatePayment'][] = ["name"=>"#","class"=>"text-center no_filter noExport","sortable"=>FALSE]; 
	$data['estimatePayment'][] = ["name"=>"Vou. Date"];
	$data['estimatePayment'][] = ["name"=>"Customer Name"];
	$data['estimatePayment'][] = ["name"=>"Received By"];
	$data['estimatePayment'][] = ["name"=>"Reference No."];
    $data['estimatePayment'][] = ["name"=>"Amount"];
    $data['estimatePayment'][] = ["name"=>"Remark"];
	
	/* Pending SO Header */
    $data['pendingSO'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['pendingSO'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['pendingSO'][] = ["name"=>"Customer"];
	$data['pendingSO'][] = ["name"=>"SO. No."];
    $data['pendingSO'][] = ["name"=>"SO. Date"];
    $data['pendingSO'][] = ["name"=>"Delivery Date"];
    $data['pendingSO'][] = ["name"=>"Item Name"];
    $data['pendingSO'][] = ["name"=>"SO.Qty"];
    $data['pendingSO'][] = ["name"=>"Pending Dis. Qty"];
    $data['pendingSO'][] = ["name"=>"Pending DO. Qty."];
    $data['pendingSO'][] = ["name"=>"Stock Qty"];
	
	/* Pending Dispatch Order Header */
    $data['pendingDO'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['pendingDO'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['pendingDO'][] = ["name"=>"DO. No."];
    $data['pendingDO'][] = ["name"=>"DO. Date"];
    $data['pendingDO'][] = ["name"=>"SO. No."];
    $data['pendingDO'][] = ["name"=>"SO. Date"];
    $data['pendingDO'][] = ["name"=>"Delivery Date"];
    $data['pendingDO'][] = ["name"=>"Customer Name"];
    $data['pendingDO'][] = ["name"=>"Item Name"];
    $data['pendingDO'][] = ["name"=>"Pending Qty."];

    /* Pending Challan Order Header */
    $data['pendingChallan'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['pendingChallan'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['pendingChallan'][] = ["name"=>"Dc. No."];
    $data['pendingChallan'][] = ["name"=>"Dc. Date"];
    $data['pendingChallan'][] = ["name"=>"Customer Name"];
    $data['pendingChallan'][] = ["name"=>"Item Name"];
    $data['pendingChallan'][] = ["name"=>"Qty."];
	
	/* Production Request Header */
    $data['productionRequest'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['productionRequest'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['productionRequest'][] = ["name"=>"Req. No."];
    $data['productionRequest'][] = ["name"=>"Req. Date"];
    $data['productionRequest'][] = ["name"=>"Item Name"];
    $data['productionRequest'][] = ["name"=>"Req. Qty"];    
    $data['productionRequest'][] = ["name"=>"Delivery Date"];
    $data['productionRequest'][] = ["name"=>"Remark"];
    $data['productionRequest'][] = ["name"=>"Status"];
	
    /* packing Header */
    $data['packing'][] = ["name"=>"Action"];
    $data['packing'][] = ["name"=>"#","textAlign"=>"center"];
    $data['packing'][] = ["name"=>"Packing No."];
    $data['packing'][] = ["name"=>"Packing Date"];
    $data['packing'][] = ["name"=>"Product Name"];
    $data['packing'][] = ["name"=>"Box Capacity"];
    $data['packing'][] = ["name"=>"Total Box"];
    $data['packing'][] = ["name"=>"Total Qty."];
    $data['packing'][] = ["name"=>"Pending Dispatch"];
    $data['packing'][] = ["name"=>"Remark"];
    
    /* Packing Stock Header */
    $data['packing_stock'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['packing_stock'][] = ["name"=>"Item Description"];
    $data['packing_stock'][] = ["name"=>"PRC No"];
    $data['packing_stock'][] = ["name"=>"Batch No"];
    $data['packing_stock'][] = ["name"=>"Balance Qty."];

    /** Dispatch Plan */
    $data['pendDispatchPlan'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['pendDispatchPlan'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['pendDispatchPlan'][] = ["name"=>"SO. No."];
    $data['pendDispatchPlan'][] = ["name"=>"SO. Date"];
    $data['pendDispatchPlan'][] = ["name"=>"Customer Name"];
    $data['pendDispatchPlan'][] = ["name"=>"Item Name"];
    $data['pendDispatchPlan'][] = ["name"=>"Delivery Date"];
    $data['pendDispatchPlan'][] = ["name"=>"Stock Qty."];
    $data['pendDispatchPlan'][] = ["name"=>"Order Qty."];
    $data['pendDispatchPlan'][] = ["name"=>"Dispatch Qty"];
    $data['pendDispatchPlan'][] = ["name"=>"Pending Dispatch"];
    $data['pendDispatchPlan'][] = ["name"=>"Assembly Order Qty"];
    $data['pendDispatchPlan'][] = ["name"=>"Pending Qty"];

    /** Dispatch Plan */
    $data['dispatchPlan'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['dispatchPlan'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['dispatchPlan'][] = ["name"=>"Assembly Order No."];
    $data['dispatchPlan'][] = ["name"=>"Assembly Order Date"];
    $data['dispatchPlan'][] = ["name"=>"SO. No."];
    $data['dispatchPlan'][] = ["name"=>"SO. Date"];
    $data['dispatchPlan'][] = ["name"=>"Customer Name"];
    $data['dispatchPlan'][] = ["name"=>"Item Name"];
    $data['dispatchPlan'][] = ["name"=>" Qty."];

    /* Final Packing Header */
    $data['finalPacking'][] = ["name"=>"Action"];
    $data['finalPacking'][] = ["name"=>"#","textAlign"=>"center"];
    $data['finalPacking'][] = ["name"=>"Packing No."];
    $data['finalPacking'][] = ["name"=>"Packing Date"];
    $data['finalPacking'][] = ["name"=>"Party Name"];
    $data['finalPacking'][] = ["name"=>"So No"];
    $data['finalPacking'][] = ["name"=>"Product Name"];
    $data['finalPacking'][] = ["name"=>"Total Box"];
    $data['finalPacking'][] = ["name"=>"Total Qty."];


    return tableHeader($data[$page]);
}

/* Sales Enquiry Table data */
function getSalesEnquiryData($data){
    $quotationBtn=""; $editButton=""; $deleteButton=""; 
    if(empty($data->trans_status)):
        $editButton = '<a class="btn btn-success permission-modify" href="'.base_url('salesEnquiry/edit/'.$data->trans_main_id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->trans_main_id."},'message' : 'Sales Enquiry'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

        $quotationBtn = '<a href="'.base_url('salesQuotation/createQuotation/'.$data->trans_main_id).'" class="btn btn-primary permission-write" datatip="Create Quotation" flow="down"><i class="fa fa-file-alt"></i></a>';  
    endif;

    $action = getActionButton($quotationBtn.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,floatVal($data->qty).' '.$data->uom]; 
}

/* Sales Quotation Table data */
function getSalesQuotationData($data){
    $editButton = $deleteButton = $approveBtn = $rejectBtn = "";
    
    if(empty($data->is_approve)):
        $approveParam = "{'postData':{'id' : ".$data->trans_main_id.",'is_approve':1},'fnsave':'approveSalesQuotation','message':'Are you sure want to Approve this Sales Quotation?'}";
        $approveBtn = '<a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Approve SQ" flow="down" onclick="confirmStore('.$approveParam.');"><i class="mdi mdi-check"></i></a>';    

        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('salesQuotation/edit/'.$data->trans_main_id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->trans_main_id."},'message' : 'Sales Quotation'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    else:
        $rejectParam = "{'postData':{'id' : ".$data->trans_main_id.",'is_approve':0},'fnsave':'approveSalesQuotation','message':'Are you sure want to Reject this Sales Quotation?'}";
        $rejectBtn = '<a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Reject SQ" flow="down" onclick="confirmStore('.$rejectParam.');"><i class="mdi mdi-close"></i></a>';    
    endif;
 
    $revision = '<a href="'.base_url('salesQuotation/reviseQuotation/'.$data->trans_main_id).'" class="btn btn-primary btn-edit permission-modify" datatip="Revision" flow="down"><i class="fa fa-retweet"></i></a>';

    $orderBtn = '<a href="'.base_url('salesOrders/createOrder/'.$data->trans_main_id).'" class="btn btn-dark permission-write" datatip="Create Order" flow="down"><i class="fa fa-file-alt"></i></a>'; 

    $printBtn = '<a class="btn btn-dribbble btn-edit permission-approve" href="'.base_url('salesQuotation/printQuotation/'.$data->trans_main_id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';

    $printRevisionBtn = '<a class="btn btn-facebook btn-edit permission-approve createSalesQuotation" datatip="View Revised Quatation" data-id="'.$data->trans_main_id.'" data-sq_no="'.$data->trans_number.'" flow="down"><i class="fas fa-eye" ></i></a>';
  
    if($data->trans_status == 2):
        $revision = $editButton = $deleteButton = $approveBtn = $rejectBtn = "";
    endif;

    $action = getActionButton($printBtn.$orderBtn.$approveBtn.$rejectBtn.$revision.$editButton.$deleteButton);

    $rev_no = sprintf("%02d",$data->quote_rev_no);
    
    if($data->quote_rev_no != 0):
        $revParam = "{'postData' : {'trans_number' : '".$data->trans_number."'}, 'modal_id' : 'modal-md', 'form_id' : 'revisionList', 'title' : 'Quotation Revision History','call_function':'revisionHistory','button':'close'}";
        $rev_no = '<a href="javascript:void(0)" datatip="Revision History" flow="down" onclick="modalAction('.$revParam.');">'.sprintf("%02d",$data->quote_rev_no).'</a>';
    endif;

    return [$action,$data->sr_no,$rev_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,floatval($data->qty).' '.$data->uom,$data->price,$data->approve_by_name,((!empty($data->approve_date))?formatDate($data->approve_date):"")]; 
}

/* Sales Order Table data */
function getSalesOrderData($data){
	if(empty($data->use_for)):
		$approveBtn ="";
		if(empty($data->is_approve)):
			$approveParam = "{'postData':{'id' : ".$data->id.",'is_approve':1,'msg':'Approved'},'fnsave':'approveSalesOrder','message':'Are you sure want to Approve this Sales Order?'}";
			$approveBtn = '<a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Approve SO" flow="down" onclick="confirmStore('.$approveParam.');"><i class="mdi mdi-check"></i></a>';    
		endif;
		
		$shortCloseParam = "{'postData':{'trans_main_id' : ".$data->id.", 'trans_status' : 2},'fnsave':'changeOrderStatus','message':'Are you sure want to Short Close this Sales Order?'}";
		$shortClose = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Short Close" flow="down" onclick="confirmStore('.$shortCloseParam.');"><i class="mdi mdi-close-circle-outline"></i></a>';    
		
		$editButton = '<a class="btn btn-warning btn-edit permission-modify" href="'.base_url('salesOrders/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

		$deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Order'}";
		$deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

		$printBtn = '<a class="btn btn-info btn-edit permission-approve1" href="'.base_url('salesOrders/printOrder/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';

		$acceptButton = '';
		if($data->sales_executive == $data->party_id):
			$acceptButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('salesOrders/edit/'.$data->id.'/1').'" datatip="Accept Order" flow="down" ><i class="ti-check"></i></a>';
		endif;

		if($data->trans_status > 0):
			$acceptButton = $editButton = $deleteButton =  $shortClose = $approveBtn = "";
		endif;
		
		$download = ((!empty($data->attachment_file))?'<a class="btn btn-primary" href="'.base_url('assets/uploads/sales_order/'.$data->attachment_file).'" target="_blank" datatip="Download" flow="down"><i class="fa fa-download"></i></a>':'');    
			
		$bomBtn = '<a class="btn btn-info btn-edit permission-approve1" href="'.base_url('salesOrders/bomReport/'.$data->item_id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-sitemap" ></i></a>';
		
		$action = getActionButton($approveBtn.$shortClose.$bomBtn.$printBtn.$acceptButton.$download.$editButton.$deleteButton);
	else:
		$chParam = "{'postData':{'party_id': ".$data->party_id.",'entry_type': '".$data->entry_type."'},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'createChallan', 'title' : 'Create Challan [".str_replace("'","",$data->party_name)."]','call_function':'getPartyOrders','controller':'salesOrders','js_store_fn':'createChallan'}";
		$challanBtn = '<a class="btn btn-primary permission-write" href="javascript:void(0)" datatip="Create Challan" flow="down" onclick="modalAction('.$chParam.');"><i class="fas fa-plus"></i></a>';
		$action = getActionButton($challanBtn);
	endif;	

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->delivery_address,$data->item_name,$data->price,floatval($data->stock_qty).' '.$data->uom,floatval($data->qty).' '.$data->uom,floatval($data->dispatch_qty).' '.$data->uom,floatval($data->pending_qty).' '.$data->uom]; 
}

/* Party Order Table Data */
function getPartyOrderData($data){
    $action = getActionButton("");

    return [$action,$data->sr_no,$data->order_status,$data->trans_number,$data->trans_date,$data->item_name,$data->brand_name,$data->qty,$data->dispatch_qty,$data->pending_qty];
}

/* Dispatch Order Table Data */
function getDispatchOrderData($data){
    $printBtn = $linkButton = $deleteButton = '';

    /* $linkParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'linkPacking', 'title' : 'Packing List','call_function':'linkPacking','button':'close'}";
    $linkButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Link" flow="down" onclick="modalAction('.$linkParam.');"><i class="fas fa-link"></i></a>'; */

    $appParam = "{'postData':{'order_number' : '".$data->order_number."', 'order_prefix': '".$data->order_prefix."', 'order_no' : '".$data->order_no."', 'order_date' : '".$data->order_date."', 'party_id': ".$data->party_id."},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'dispatchOrder', 'title' : 'Add Dispatch Order','call_function':'addDispatchOrderItem'}";
    $addItemButton = '<a class="btn btn-primary permission-write" href="javascript:void(0)" datatip="Add Item" flow="down" onclick="modalAction('.$appParam.');"><i class="fas fa-plus"></i></a>';

    if(empty(floatval($data->pallet_pck_qty))):
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Order Item'}";
	    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    else:
        $printBtn = '<a class="btn btn-dark btn-edit permission-approve1" href="'.base_url('dispatchOrder/printPackingList/'.$data->order_number).'" target="_blank" datatip="Packing List Print" flow="down"><i class="fas fa-print" ></i></a>';
    endif;

    $finalPckButton = '';
    //if(!empty(floatval($data->link_qty)) && empty($data->disp_trans_id)):
    if(empty($data->disp_trans_id)):
        $finalPckParam = "{'postData':{'order_no' : ".$data->order_no.",'order_number' : '".$data->order_number."'},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'finalPacking', 'title' : 'Packing Annexure [".$data->order_number."]','call_function':'finalPacking','button':'close'}";
        $finalPckButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Final Packing" flow="down" onclick="modalAction('.$finalPckParam.');"><i class="fas fa-gift"></i></a>';
    endif;

    $action = getActionButton($printBtn.$addItemButton.$linkButton.$finalPckButton.$deleteButton);

    return [$action,$data->sr_no,$data->order_number,formatDate($data->order_date),$data->so_number,formatDate($data->so_date),((!empty($data->delivery_date))?formatDate($data->delivery_date):""),$data->party_name,$data->item_name,$data->order_qty/* ,$data->link_qty,$data->pending_qty */,$data->pallet_pck_qty,$data->pp_pending_qty];
}

/* Delivery Challan Table Data */
function getDeliveryChallanData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('deliveryChallan/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Delivery Challan'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$pack_url = encodeURL(['ref_id'=>$data->id]);
	$packButton = '<a class="btn btn-primary btn-edit permission-modify" href="'.base_url('finalPacking/addPacking/'.$pack_url).'" target="_blank" datatip="Packing Details" flow="down" ><i class="mdi mdi-dropbox"></i></a>';

    $printBtn = '<a class="btn btn-info btn-edit permission-approve1" href="'.base_url('deliveryChallan/printChallan/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print"></i></a>';

    $packingPrint = '<a class="btn btn-info btn-edit" href="'.base_url('deliveryChallan/packingListPrint/'.$data->id).'" target="_blank" datatip="Packing Print" flow="down"><i class="fas fa-print"></i></a>';
    if($data->trans_status > 0):
        $packButton = $editButton = $deleteButton = "";
    endif;

    $action = getActionButton($printBtn.$packButton.$packingPrint.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->remark];
}

/* Estimate [Cash] Table Data */
function getEstimateData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('estimate/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Estimate'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $printBtn = '<a class="btn btn-info btn-edit" href="'.base_url('estimate/printEstimate/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';

    if($data->trans_no == 0):
        $editButton = $deleteButton = "";
    endif;

    $action = getActionButton($printBtn.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->trans_date,$data->party_name,$data->taxable_amount,$data->net_amount];
}

/* Estimate Payment [Cash] Table Data */
function getEstimatePaymentData($data){
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'estimatePayment', 'title' : 'Payment Voucher','call_function':'estimatePayment','fnsave':'saveEstimatePayment'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Payment Voucher','fndelete':'deleteEstimatePayment'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,formatDate($data->entry_date),$data->party_name,$data->received_by,$data->reference_no,$data->amount,$data->remark];
}

function getPendingSOData($data){
    $addButton = $challanBtn = "";

    $pendingQty = $data->qty - $data->dispatch_qty;
    $pendingDoQty = ($data->qty - $data->do_qty);

    if($data->entry_type == 190){
        $appParam = "{'postData':{'party_id': ".$data->party_id."},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'dispatchOrder', 'title' : 'Add Dispatch Order','call_function':'addDispatchOrder'}";
        $addButton = '<a class="btn btn-primary permission-write" href="javascript:void(0)" datatip="Add Order" flow="down" onclick="modalAction('.$appParam.');"><i class="fas fa-plus"></i></a>';    
    }

    if($data->entry_type == 177 OR $data->entry_type == 20){
        $chParam = "{'postData':{'party_id': ".$data->party_id.",'entry_type': '".$data->entry_type."'},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'createChallan', 'title' : 'Create Challan [".str_replace("'","",$data->party_name)."]','call_function':'getPartyOrders','controller':'salesOrders','js_store_fn':'createChallan'}";
        $challanBtn = '<a class="btn btn-primary permission-write" href="javascript:void(0)" datatip="Create Challan" flow="down" onclick="modalAction('.$chParam.');"><i class="fas fa-plus"></i></a>';    
    }

    $action = getActionButton($addButton.$challanBtn);

    return [$action ,$data->sr_no,$data->party_name,$data->trans_number,formatDate($data->trans_date),formatDate($data->cod_date),$data->item_name,floatVal($data->qty),floatVal($pendingQty),floatVal($pendingDoQty),floatVal($data->stock_qty)];
}

/* Pending Dispatch Order Table Data */
function getPendingDOData($data){
    $challanBtn='';

     if($data->entry_type == 177 OR $data->entry_type == 20):
        $chParam = "{'postData':{'party_id': ".$data->party_id.",'entry_type': '".$data->entry_type."'},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'createChallan', 'title' : 'Create Invoice [".$data->party_name."]','call_function':'getPartyOrders','controller':'dispatchOrder','js_store_fn':'createChallan','savebtn_text':'Create'}";
        $challanBtn = '<a class="btn btn-primary permission-write" href="javascript:void(0)" datatip="Create Invoice" flow="down" onclick="modalAction('.$chParam.');"><i class="fas fa-plus"></i></a>';    
    endif;
    
    $action = getActionButton($challanBtn);

    return [$action,$data->sr_no,$data->order_number,formatDate($data->order_date),$data->so_number,formatDate($data->so_date),((!empty($data->delivery_date))?formatDate($data->delivery_date):""),$data->party_name,$data->item_name,$data->pending_qty];
}

/* Pending Delivery Challan Table Data */
function getPendingChallanData($data){
    $challanBtn='';

    if($data->entry_type == 20):
        $chParam = "{'postData':{'party_id': ".$data->party_id.",'entry_type': '".$data->entry_type."'},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'createChallan', 'title' : 'Create Invoice [".$data->party_name."]','call_function':'getPartyChallan','controller':'deliveryChallan','js_store_fn':'createChallan'}";
        $challanBtn = '<a class="btn btn-primary permission-write" href="javascript:void(0)" datatip="Create Inv" flow="down" onclick="modalAction('.$chParam.');"><i class="fas fa-plus"></i></a>';    
    endif;
    
	$printBtn = '<a class="btn btn-info btn-edit permission-approve1" href="'.base_url('deliveryChallan/printChallan/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print"></i></a>';

    $packingPrint = '<a class="btn btn-info btn-edit" href="'.base_url('deliveryChallan/packingListPrint/'.$data->id).'" target="_blank" datatip="Packing Print" flow="down"><i class="fas fa-print"></i></a>';
  
    $action = getActionButton($challanBtn.$printBtn.$packingPrint);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,floatval($data->qty).' '.$data->unit_name]; 
}

/* Production Request Data  */
function getProductionRequestData($data){
    $shortClose =""; $editButton=""; $deleteButton =""; $prcButton="";

    if($data->order_status == 1){
        if($data->type == 1){
            $shortCloseParam = "{'postData':{'id' : ".$data->id.", 'order_status' : 3},'fnsave':'changeReqStatus','message':'Are you sure want to Short Close this Production Request?'}";
            $shortClose = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Short Close" flow="down" onclick="confirmStore('.$shortCloseParam.');"><i class="mdi mdi-close-circle-outline"></i></a>';    

            $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-md-modal', 'form_id' : 'editPurchaeRequest', 'title' : 'Update PurchaeRequest','call_function':'edit'}";
            $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        
            $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Production Request'}";
            $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
        }elseif($data->type == 2){
            // $prcParam = "{'postData':{'id' : ".$data->id.", 'item_id' : '".$data->item_id."', 'qty' : '".$data->qty."'}, 'modal_id' : 'bs-right-lg-modal', 'form_id' : 'addPRCFromRequest', 'title' : 'New PRC', 'call_function' : 'addPRCFromRequest', 'controller' : 'sopDesk', 'fnsave' : 'savePRC'}";
            // $prcButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Create PRC" flow="down" onclick="modalAction('.$prcParam.');"><i class="fa fa-plus"></i></a>';

            $prcParam = "{'postData':{'id' : ".$data->id.", 'item_id' : '".$data->item_id."', 'qty' : '".$data->qty."'},'fnsave':'createPRC','message':'Are you sure want to Create PRC?'}";
            $prcButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Create PRC" flow="down" onclick="confirmStore('.$prcParam.');"><i class="fa fa-plus"></i></a>';    
        }
    }
  
    $action = getActionButton($prcButton.$shortClose.$editButton.$deleteButton);
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->item_name,floatval($data->qty).' ('.$data->unit_name.')',formatDate($data->delivery_date),$data->remark,$data->order_status_label];
}

/* Packing Data */
function getPackingData($data){
    $edit = $delete = "";
    if($data->pending_dispatch == $data->total_qty){
        $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editPacking', 'title' : 'Update Packing'}";
        $edit = '<a class="btn btn-success btn-sm btn-edit permission-modify" href="javascript:void(0)" onclick="modalAction('.$editParam.');" datatip="Edit" flow="down"><i class="mdi mdi-square-edit-outline"></i></a>';
    
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Packing Order'}";
        $delete = '<a href="javascript:void(0)" class="btn btn-danger btn-delete permission-remove" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    }
    $printBtn = '<a class="btn btn-info btn-edit" href="'.base_url('packing/packedBoxSticker/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';
    $item_name = (!empty($data->item_code) ? "[".$data->item_code."] ".$data->item_name : $data->item_name);
    $action = getActionButton($printBtn.$edit.$delete);
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$item_name,$data->qty_per_box,$data->total_box,$data->total_qty,$data->pending_dispatch,$data->remark];
}

/* Packing Stock Data */
function getPackingStockData($data){
    $item_name = (!empty($data->item_code) ? "[".$data->item_code."] ".$data->item_name : $data->item_name);
    return [$data->sr_no,$item_name,$data->batch_no,$data->ref_batch,floatVal($data->stock_qty)];
}

function getPendingDispatchPlanData($data){
    $addParam = "{'postData':{'so_trans_id' : ".$data->trans_child_id."},'modal_id' : 'master-modal-md', 'call_function':'addDispatchPlan', 'form_id' : 'addDispatchPlan', 'title' : 'New Dispatch Plan', 'fnsave' : 'save'}";
    $planBtn= '<a class="btn btn-dark permission-modify" href="javascript:void(0)" datatip="Add Assembly Order" flow="down" onclick="modalAction(' . $addParam . ');"><i class="far fa-plus-square"></i> </a>';
    
    $isuueParam = "{'postData':{'item_id' : '".$data->item_id."','qty':'".($data->qty - $data->plan_qty)."'}, 'modal_id' : 'bs-right-xl-modal', 'form_id' : 'addIssueRequisition', 'title' : 'MRP For : ".$data->item_name." ', 'call_function':'getItemMrpdata', 'button':'close'}";
    $mrpBtn = '<a href="javascript:void(0)" type="button" class="btn btn-info permission-modify" datatip="MRP" flow="down" onclick="modalAction('.$isuueParam.');"><i class="fas fa-sitemap"></i></a>';  
    $action = getActionButton($planBtn.$mrpBtn);

    $pending_qty = $data->qty - $data->plan_qty;
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,formatDate($data->cod_date),floatval($data->stock_qty).' '.$data->uom,floatval($data->qty).' '.$data->uom,floatval($data->dispatch_qty).' '.$data->uom,floatval($data->pending_qty).' '.$data->uom,floatval($data->plan_qty).' '.$data->uom,floatval($pending_qty).' '.$data->uom]; 
}

function getDispatchPlanData($data){
    $materialBtn = $startButton = $editButton = $deleteButton = $holdBtn = $shortBtn = $restartBtn = $updateQty=$issueBtn="";
	$prc_number = '<a href="'.base_url("sopDesk/prcDetail/".$data->id).'">'.$data->prc_number.'</a>';

   
    if($data->status == 1 ){
        $startTitle = 'Start PRC : '.$data->prc_number;
        $startParam = "{'postData':{'id' : ".$data->id.",'item_id':".$data->item_id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editPrc', 'title' : '".$startTitle."', 'fnsave' : 'startPrc', 'js_store_fn' : 'confirmStore','call_function':'setPrcProcesses','controller':'sopDesk'}";
        $startButton = ' <a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Start" flow="down" onclick="modalAction('.$startParam.')"><i class="fas fa-play-circle"></i></a>';

        $mtParam = "{'postData':{'id' : ".$data->id.",'prc_qty' : ".$data->prc_qty.",'item_id':".$data->item_id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'prcMaterial', 'title' : 'Required Material For : ".$data->prc_number."', 'fnsave' : 'savePrcMaterial','call_function':'requiredMaterial','controller':'sopDesk'}";
        $materialBtn = ' <a class="btn btn-primary permission-modify" href="javascript:void(0)" datatip="Required Material" flow="down" onclick="modalAction('.$mtParam.')"><i class="far fa-paper-plane"></i></a>';
    }
    /* if(in_array($data->status,[1,2])){
        $isuueParam = "{'postData':{'prc_id' : ".$data->id.", 'item_id' : '".$data->item_id."'}, 'modal_id' : 'bs-right-xl-modal', 'form_id' : 'addIssueRequisition', 'title' : 'Material Issue For ".$data->prc_number." ', 'call_function':'addIssueRequisition', 'fnsave':'saveIssueRequisition','js_store_fn':'storeIssueMaterial'}";
        $issueBtn = '<a href="javascript:void(0)" type="button" class="btn btn-info permission-modify" datatip="Material Issue" flow="down" onclick="modalAction('.$isuueParam.');"><i class="fa fa-plus"></i></a>';  
    } */
    $prc_number = '<a href="'.base_url("sopDesk/prcDetail/".$data->id).'">'.$data->prc_number.'</a>';
    $action = getActionButton($materialBtn.$startButton.$issueBtn.$holdBtn.$shortBtn.$restartBtn.$updateQty.$editButton.$deleteButton);
    return [$action,$data->sr_no,$prc_number,formatDate($data->prc_date),$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,floatval($data->prc_qty).' '.$data->uom]; 
}

/* Final Packing Data */
function getFinalPackingData($data){
    $edit = $delete = "";
    if($data->status == 1){
    $edit = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('finalPacking/edit/'.$data->packing_id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';


    $deleteParam = "{'postData':{'id' : ".$data->packing_id."},'message' : 'Packing'}";
    $delete = '<a href="javascript:void(0)" class="btn btn-danger btn-delete permission-remove" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    }
    $printBtn = '<a class="btn btn-info btn-edit" href="'.base_url('finalPacking/finalPackingPrint/'.$data->packing_id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';
    $item_name = (!empty($data->item_code) ? "[".$data->item_code."] ".$data->item_name : $data->item_name);
    $action = getActionButton($printBtn.$edit.$delete);
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->so_number,$item_name,$data->total_box,$data->total_qty];
}
?>