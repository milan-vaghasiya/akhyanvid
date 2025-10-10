<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getStoreDtHeader($page){
    /* Location Master header */
    $data['storeLocation'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['storeLocation'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['storeLocation'][] = ["name"=>"Store Name"];
    $data['storeLocation'][] = ["name"=>"Location"];
    $data['storeLocation'][] = ["name"=>"Remark"];

    /* Gate Entry */
    $data['gateEntry'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['gateEntry'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['gateEntry'][] = ["name"=> "GE No.", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "GE Date", "textAlign" => "center"];
    $data['gateEntry'][] = ["name" => "Transport"];
    $data['gateEntry'][] = ["name" => "LR No."];
    $data['gateEntry'][] = ["name" => "Vehicle Type"];
    $data['gateEntry'][] = ["name" => "Vehicle No."];
    $data['gateEntry'][] = ['name' => "Invoice No."];
    $data['gateEntry'][] = ['name' => "Invoice Date"];
    $data['gateEntry'][] = ['name' => "Challan No."];
    $data['gateEntry'][] = ['name' => "Challan Date"];

    /* Gate Inward Pending GE Tab Header */
    $data['pendingGE'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['pendingGE'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['pendingGE'][] = ["name"=> "GE No.", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "GE Date", "textAlign" => "center"];
    $data['pendingGE'][] = ["name" => "Party Name"];
    $data['pendingGE'][] = ["name" => "Inv. No."];
    $data['pendingGE'][] = ["name" => "Inv. Date"];
    $data['pendingGE'][] = ['name' => "CH. NO."];
    $data['pendingGE'][] = ['name' => "CH. Date"];

    /* Gate Inward Pending/Compeleted Tab Header */
    $data['gateInward'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['gateInward'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['gateInward'][] = ["name"=> "GI No.", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "GI Date", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "Party Name"];
    $data['gateInward'][] = ["name" => "Item Name"];
    $data['gateInward'][] = ["name" => "Qty"];
    $data['gateInward'][] = ["name" => "PO. NO."]; 
    
    /* FG Stock Inward Table Header */
    $data['stockTrans'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['stockTrans'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['stockTrans'][] = ["name" => "Date"];
    $data['stockTrans'][] = ["name" => "Item Name"];
    $data['stockTrans'][] = ["name" => "Qty"];
    $data['stockTrans'][] = ["name" => "Remark"];

    /* LIST OF STOCK VERIFICATION  */
    $data['stockVerification'][] = ["name"=>"#","style"=>"width:5%;","textAlign"=>"center","srnoPosition"=>0];
    $data['stockVerification'][] = ["name"=>"Item Code."];
    $data['stockVerification'][] = ["name"=>"Item Name"];
    $data['stockVerification'][] = ["name"=>"Stock Register Qty."];
    $data['stockVerification'][] = ["name"=>"Action","style"=>"width:5%;","textAlign"=>"center"];

    /* Requisition Table Header */
    $data['requisition'][] = ["name" => "Action", "textAlign" => "center"];
    $data['requisition'][] = ["name" => "#", "textAlign" => "center"];
    $data['requisition'][] = ["name" => "Req. No."];
    $data['requisition'][] = ["name" => "Req. Date"];
    $data['requisition'][] = ["name" => "Item Name"];
    $data['requisition'][] = ["name" => "Req. Qty"];
    $data['requisition'][] = ["name" => "Issue Qty"];

    /* Return Requisition Table Header */
    $data['returnRequisition'][] = ["name" => "Action", "textAlign" => "center"];
    $data['returnRequisition'][] = ["name" => "#", "textAlign" => "center"];
    $data['returnRequisition'][] = ["name" => "Req Number"];
    $data['returnRequisition'][] = ["name" => "Req Date"];
    $data['returnRequisition'][] = ["name" => "Issue Number"];
    $data['returnRequisition'][] = ["name" => "Issue Date"];
    $data['returnRequisition'][] = ["name" => "Item Name"];
    $data['returnRequisition'][] = ["name" => "Issue Qty"];
    $data['returnRequisition'][] = ["name" => "Return Qty"];

    /* Pending Issue Requisition Table Header */
    $data['pendingRequisition'][] = ["name" => "Action", "textAlign" => "center"];
    $data['pendingRequisition'][] = ["name" => "#", "textAlign" => "center"];
    $data['pendingRequisition'][] = ["name" => "Req Number"];
    $data['pendingRequisition'][] = ["name" => "Req Date"];
    $data['pendingRequisition'][] = ["name" => "Item Name"];
    $data['pendingRequisition'][] = ["name" => "Req Qty"];
    $data['pendingRequisition'][] = ["name" => "Issue Qty"];
    $data['pendingRequisition'][] = ["name" => "Pending Qty"];
    $data['pendingRequisition'][] = ["name" => "Used For"];

    /* Issued Requisition Table Header */
    $data['issueRequisition'][] = ["name" => "Action", "textAlign" => "center"];
    $data['issueRequisition'][] = ["name" => "#", "textAlign" => "center"];
    $data['issueRequisition'][] = ["name" => "Issued Number"];
    $data['issueRequisition'][] = ["name" => "Issue Date"];
    $data['issueRequisition'][] = ["name" => "Request No"];
    $data['issueRequisition'][] = ["name" => "Prc Number"];
    $data['issueRequisition'][] = ["name" => "Item Name"];
    $data['issueRequisition'][] = ["name" => "Issue Qty"];

    /* Inspection Table Header */
    $data['inspection'][] = ["name" => "Action", "textAlign" => "center"];
    $data['inspection'][] = ["name" => "#", "textAlign" => "center"];
    $data['inspection'][] = ["name" => "Issued Number"];
    $data['inspection'][] = ["name" => "Trans Date"];
    $data['inspection'][] = ["name" => "Total Qty"];
    // $data['inspection'][] = ["name" => "Batch No"];
    $data['inspection'][] = ["name" => "Remark"];

    /* PRC Material Issue Table Header */
    $data['prcMaterialIssue'][] = ["name" => "Action", "textAlign" => "center"];
    $data['prcMaterialIssue'][] = ["name" => "#", "textAlign" => "center"];
    $data['prcMaterialIssue'][] = ["name" => "PRC No."];
    $data['prcMaterialIssue'][] = ["name" => "PRC Date"];
    $data['prcMaterialIssue'][] = ["name" => "GROUP"];
    $data['prcMaterialIssue'][] = ["name" => "Item"];
    $data['prcMaterialIssue'][] = ["name" => "Required Qty"];
    $data['prcMaterialIssue'][] = ["name" => "Issue Qty"];
	
	/* Other Item Stock Inward Table Header */
    $data['itemInward'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['itemInward'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['itemInward'][] = ["name" => "Item Name"];
    $data['itemInward'][] = ["name" => "Location"];
    $data['itemInward'][] = ["name" => "Qty"];
	
    /* PRC Material Issue Table Header */
    $data['prcMaterial'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['prcMaterial'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['prcMaterial'][] = ["name" => "PRC No."];
    $data['prcMaterial'][] = ["name" => "PRC Date"];
    $data['prcMaterial'][] = ["name" => "Item"];
    $data['prcMaterial'][] = ["name" => "Required Qty"];
    $data['prcMaterial'][] = ["name" => "Issue Qty"];

    return tableHeader($data[$page]);
}

/* Store Location Data */
function getStoreLocationData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Store Location'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editStoreLocation', 'title' : 'Update Store Location','call_function':'edit'}";

    $editButton = ''; $deleteButton = '';
    if(!empty($data->ref_id) && empty($data->store_type)):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;

    if($data->final_location == 0):
        $locationName = '<a href="' . base_url("storeLocation/list/" . $data->id) . '">' . $data->location . '</a>';
    else:
        $locationName = $data->location;
    endif;
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->store_name,$locationName,$data->remark];
}

/* Gate Entry Data  */
function getGateEntryData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Gate Entry'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editGateEntry', 'title' : 'Update Gate Entry','call_function':'edit'}";

    $editButton = "";
    $deleteButton = "";
    if($data->trans_status == 0):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->transport_name,$data->lr,$data->vehicle_type_name,$data->vehicle_no,$data->inv_no,((!empty($data->inv_date))?formatDate($data->inv_date):""),$data->doc_no,((!empty($data->doc_date))?formatDate($data->doc_date):"")];
}

/* GateInward Data Data  */
function getGateInwardData($data){
    $action = '';$editButton='';$deleteButton="";$pallatePrint="";
    
        // 02-05-2024
		// Gate Inward Pending/Completed Data

        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Gate Inward'}";
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'editGateInward', 'title' : 'Update Gate Inward','call_function':'edit'}";

        $editButton = $deleteButton = $inspection = $iirPrint = $iirInsp = $tcButton = ""; 
        
		if($data->trans_status == 0):
            $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

            $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
        
            $insParam = "{'postData':{'id' : ".$data->mir_trans_id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'materialInspection', 'title' : 'Material Inspection','call_function':'materialInspection','fnsave':'saveInspectedMaterial'}";
			$inspection = '<a href="javscript:voide(0);" type="button" class="btn btn-warning permission-modify" datatip="Inspection" flow="down" onclick="modalAction('.$insParam.');"><i class="fas fa-search"></i></a>';

            $testReport = "{'postData':{'id' : '".$data->mir_trans_id."','grn_id' : '".$data->id."'}, 'button' : 'close', 'modal_id' : 'bs-right-xl-modal', 'form_id' : 'testReport', 'title' : 'Test Report', 'call_function' : 'getTestReport'}";
            $tcButton = '<a class="btn btn-dark btn-salary permission-modify" href="javascript:void(0)" datatip="Test Report" flow="down" onclick="modalAction('.$testReport.');"><i class="mdi mdi-file-multiple"></i></a>';
		endif;
		if($data->is_inspection == 1):
			$iirParam = "{'postData':{'id' : ".$data->mir_trans_id."},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'iirInsp', 'title' : 'Dimensional Inspection','call_function':'getInwardQc','fnsave':'saveInwardQc'}";
			$iirInsp = '<a href="javascript:void(0)" type="button" class="btn btn-info permission-modify" datatip="Dimensional Inspection" flow="down" onclick="modalAction('.$iirParam.');"><i class="fa fa-file-alt"></i></a>';

			$iirPrint = '<a class="btn btn-dribbble btn-edit permission-modify" href="'.base_url($data->controller.'/inInspection_pdf/'.$data->mir_trans_id).'" target="_blank" datatip="Inspection Print" flow="down"><i class="fas fa-print" ></i></a>';
		endif;

	    $iirTagPrint = '<a href="'.base_url('gateInward/ir_print/'.$data->mir_trans_id).'" type="button" class="btn btn-primary" datatip="IIR Tag" flow="down" target="_blank"><i class="fas fa-print"></i></a>';

        $grnPrint = '<a class="btn btn-success btn-info" href="'.base_url('gateInward/printGRN/'.$data->id).'" target="_blank" datatip="GRN Print" flow="down"><i class="fas fa-print" ></i></a>';

	    $action = getActionButton($inspection.$iirTagPrint.$grnPrint.$tcButton.$iirInsp.$iirPrint.$editButton.$deleteButton);

        return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,floatval($data->qty).' '.$data->uom,$data->po_number];
}

/* FG Stock Inward Table Data */
function getStockTransData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Stock'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($deleteButton);

    return [$action,$data->sr_no,formatDate($data->trans_date),$data->item_name,$data->qty,$data->remark];
}

/* Stock Verification Table Data */
function getStockVerificationData($data){
 
    $editParam = "{'postData':{'id' : ".$data->id.",'item_id': ".$data->item_id."},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'editStock', 'title' : 'Update Stock','call_function':'editStock','fnsave':'save'}";
    $editButton = '<a href="javascript:void(0)" type="button" class="btn btn-sm btn-success permission-modify" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

    return [$data->sr_no,$data->item_code,$data->item_name,floatVal($data->stock_qty).' '.$data->uom,$editButton]; 
}

/* Return Requisition Table Data */
function getReturnRequisitionData($data){
    $returnButton = '';
    $issue_qty = floatval($data->issue_qty);
    $return_qty = floatval($data->return_qty);
    if($issue_qty > $return_qty){
        $returnParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'closeRequisition', 'fnedit' : 'return', 'call_function' : 'return', 'title' : 'Return Material', 'fnsave' : 'saveReturnReq'}";
        $returnButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Return" flow="down" onclick="modalAction('.$returnParam.');"><i class="fa fa-reply" ></i></a>';
    }

    $action = getActionButton($returnButton);
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->issue_number,formatDate($data->issue_date),$data->item_name,floatval($data->issue_qty).' '.$data->uom,floatval($data->return_qty).' '.$data->uom]; 
}

/* Pending Requisition Table Data */
function getPendingRequisitionData($data) {
    $closeBtn = $issueBtn = "";
    if($data->status == 1) {  
        $issueParam = "{'postData':{'id' : ".$data->id.", 'trans_no' : '".$data->trans_no."', 'issue_type' : 'REQ'},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'issueRequisition', 'fnedit' : 'addIssueRequisition', 'call_function': 'addIssueRequisition', 'title' : 'Issue Requisition', 'fnsave' : 'saveIssueRequisition'}";
        $issueBtn = '<a class="btn btn-success" href="javascript:void(0)" onclick="modalAction(' . $issueParam . ');" datatip="Material Issue" flow="down"><i class="fas fa-paper-plane"></i></a>';

        $closeParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'modal-md', 'form_id' : 'closeRequisition', 'fnedit' : 'close', 'call_function' : 'close', 'title' : 'Close Requisition', 'fnsave' : 'closeRequisition'}";
        $closeBtn = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Close" flow="down" onclick="modalAction('.$closeParam.');"><i class="fa fa-close" ></i></a>';
    }

    $action = getActionButton($issueBtn.$closeBtn);
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->item_name,abs($data->req_qty).' '.$data->uom,abs($data->issue_qty).' '.$data->uom,abs($data->pending_qty).' '.$data->uom,$data->prc_number]; 
}

/* Requisition Table Data */
function getRequisitionData($data){
    $editBtn = $deleteBtn = $closeBtn = "";
    if($data->status == 1) {
        if($data->issue_qty <= 0){
            $editParam = "{'postData':{'trans_number' : '".$data->trans_number."'},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editRequisition', 'title' : 'Update Requisition', 'fnsave' : 'save'}";
            $editBtn = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

            $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Requisition'}";
            $deleteBtn = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
        }

        $closeParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'closeRequisition', 'call_function' : 'close', 'fnedit' : 'close', 'title' : 'Close Requisition', 'fnsave' : 'closeRequisition'}";
        $closeBtn = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Close" flow="down" onclick="modalAction('.$closeParam.');"><i class="fa fa-close" ></i></a>';
    }

    $action = getActionButton($closeBtn.$editBtn.$deleteBtn);
    
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->item_name,abs($data->req_qty),abs($data->issue_qty)];
}

/* Issue Requisition Table Data */
function getIssueRequisitionData($data){
    $deleteButton = "";
    $return_qty = floatval($data->return_qty);
    if(empty($return_qty) ){
        $deleteParam = "{'postData':{'id' : ".$data->id."}, 'fndelete' : 'deleteIssueRequisition','message' : 'Stock'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    }
    $action = getActionButton($deleteButton);
    return [$action,$data->sr_no,$data->issue_number,formatDate($data->issue_date),$data->trans_number,$data->prc_number,$data->item_name,abs($data->issue_qty).' '.$data->uom];
}

/* Inspection Table Data */
function getInspectionData($data) {
    $inspectButton = "";
    if($data->trans_type == 1){
        $inspectParam = "{'postData':{'id' : ".$data->id.",'issue_id' : ".$data->issue_id."},'modal_id' : 'modal-md', 'fnedit' : 'addInspection', 'call_function':'addInspection','form_id' : 'addInspection', 'title' : 'Inspection', 'fnsave' : 'saveInspection'}";
        $inspectButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Inspect" flow="down" onclick="modalAction('.$inspectParam.');"><i class="fa fa-search" ></i></a>';
    }
    $action = getActionButton($inspectButton);
    return [$action,$data->sr_no,$data->issue_number,formatDate($data->trans_date),$data->total_qty.' '.$data->uom,$data->remark]; 
}

/* Prc Material Issue Table Data */
function getPrcMaterialIssueData($data){
    $action = getActionButton("");
    return [$action,$data->sr_no,$data->prc_number,formatDate($data->prc_date),$data->bom_group,$data->item_name,$data->req_qty,$data->issue_qty];
}

/* Other Item Stock Inward Table Data */
function getItemStockData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Stock'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($deleteButton);

    return [$action,$data->sr_no,$data->item_name,$data->location,floatval($data->qty).' '.$data->uom]; 
}

/* Prc Material Issue Table Data */
function getPrcMaterialData($data){
    $required_qty = $data->ppc_qty * $data->prc_qty;

    $printBtn = $issueBtn = '';
    if($data->prc_type == 1){        
        $isuueParam = "{'postData':{'prc_id' : ".$data->prc_id.", 'item_id' : '".$data->item_id."', 'required_qty' : '".$required_qty."'}, 'modal_id' : 'bs-right-lg-modal', 'form_id' : 'addIssueRequisition', 'title' : 'Material Issue', 'call_function':'addIssueRequisition', 'fnsave':'saveIssueRequisition','js_store_fn':'storeIssueMaterial'}";
        $issueBtn = '<a href="javascript:void(0)" type="button" class="btn btn-info permission-modify" datatip="Material Issue" flow="down" onclick="modalAction('.$isuueParam.');"><i class="fa fa-plus"></i></a>';  
    
        $printBtn = '<a class="btn btn-dribbble permission-modify" href="'.base_url('store/printPrcReqMaterial/'.$data->prc_id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print"></i></a>';
    }else{
        $isuueParam = "{'postData':{'prc_id' : ".$data->prc_id.", 'item_id' : '".$data->item_id."', 'required_qty' : '".$required_qty."'}, 'modal_id' : 'bs-right-xl-modal', 'form_id' : 'addIssueRequisition', 'title' : 'Material Issue', 'call_function':'addIssueRequisition', 'fnsave':'saveIssueRequisition','js_store_fn':'storeIssueMaterial','controller':'dispatchPlan'}";
        $issueBtn = '<a href="javascript:void(0)" type="button" class="btn btn-info" datatip="Material Issue" flow="down" onclick="modalAction('.$isuueParam.');"><i class="fa fa-plus"></i></a>';  
    }
   
	$action = getActionButton($printBtn.$issueBtn);
    
    return [$action,$data->sr_no,$data->prc_number,formatDate($data->prc_date),$data->item_name,floatval($required_qty).' '.$data->uom,(!empty($data->issue_qty)?floatval($data->issue_qty):0).' '.$data->uom];
}
?>