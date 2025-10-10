<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getProductionDtHeader($page){

    /* Process Header */
    $data['process'][] = ["name"=>"Action","class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
	$data['process'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center"];
    $data['process'][] = ["name"=>"Process Name"];
    $data['process'][] = ["name"=>"Remark"];

    /* Rejection Header */
    $data['rejectionComments'][] = ["name"=>"Action","class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
	$data['rejectionComments'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center"];
    $data['rejectionComments'][] = ["name"=>"Code"];
    $data['rejectionComments'][] = ["name"=>"Reason"];

    /* Estimation & Design Header */
    $data['estimation'][] = ["name"=>"Action","sortable"=>"FALSE","textAlign"=>"center"];
	$data['estimation'][] = ["name"=>"#","sortable"=>"FALSE","textAlign"=>"center"]; 
	$data['estimation'][] = ["name"=>"Job No."];
	$data['estimation'][] = ["name"=>"Job Date"];
	$data['estimation'][] = ["name"=>"Customer Name"];
	$data['estimation'][] = ["name"=>"Item Name"];
    $data['estimation'][] = ["name"=>"Order Qty"];
    $data['estimation'][] = ["name"=>"Bom Status"];
    $data['estimation'][] = ["name"=>"Priority"];
    $data['estimation'][] = ["name"=>"FAB. PRODUCTION NOTE"];
    $data['estimation'][] = ["name"=>"POWER COATING NOTE"];
    $data['estimation'][] = ["name"=>"ASSEMBALY NOTE"];
    $data['estimation'][] = ["name"=>"GENERAL NOTE"];

    /* Job Card Header */
    $data['jobcard'][] = ["name"=>"Action","class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
	$data['jobcard'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center"];
    $data['jobcard'][] = ["name"=>"Job No.","textAlign"=>"center"];
    $data['jobcard'][] = ["name"=>"Job Date","textAlign"=>"center"];
    $data['jobcard'][] = ["name"=>"Customer","textAlign"=>"center"];
    $data['jobcard'][] = ["name"=>"Product","textAlign"=>"center"];
    $data['jobcard'][] = ["name"=>"Order Qty.","textAlign"=>"center"];
    $data['jobcard'][] = ["name"=>"Status","textAlign"=>"center"];
    $data['jobcard'][] = ["name"=>"Remark"];
    $data['jobcard'][] = ["name"=>"Last Activity"];


    /* Product Option Header */
    $data['productOption'][] = ["name"=>"#","textAlign"=>"center", "srnoPosition" => 0];
    $data['productOption'][] = ["name"=>"Part Code"];
    $data['productOption'][] = ["name"=>"Part Name"];
    $data['productOption'][] = ["name"=>"BOM","textAlign"=>"center"];
    $data['productOption'][] = ["name"=>"Process","textAlign"=>"center"];
    $data['productOption'][] = ["name"=>"Cycle Time","textAlign"=>"center"]; 
    $data['productOption'][] = ["name"=>"Action","textAlign"=>"center"];

    /** Outsource */
    $data['outsource'][] = ["name" => "Action", "textAlign" => "center", "srnoPosition" => ''];
    $data['outsource'][] = ["name" => "#", "textAlign" => "center"];
    $data['outsource'][] = ["name" => "Challan Date", "textAlign" => "center"];
    $data['outsource'][] = ["name" => "Challan No.", "textAlign" => "center"];
    $data['outsource'][] = ["name" => "Prc No.", "textAlign" => "center"];
    $data['outsource'][] = ["name" => "Vendor"];
    $data['outsource'][] = ["name" => "Product"];
    $data['outsource'][] = ["name" => "Process"];
    $data['outsource'][] = ["name" => "Challan Qty", "textAlign" => "center"];
    $data['outsource'][] = ["name" => "Received Qty", "textAlign" => "center"];
    $data['outsource'][] = ["name" => "Pending Qty", "textAlign" => "center"];

    /* Pending Rejection Review */
    $data['pendingReview'][] = ["name" => "Action", "textAlign" => "center"];
    $data['pendingReview'][] = ["name"=>"#","textAlign"=>"center"];
    $data['pendingReview'][] = ["name"=>"PRC No.","textAlign"=>"center"];
    $data['pendingReview'][] = ["name"=>"Product","textAlign"=>"center"];
    $data['pendingReview'][] = ["name"=>"Date","textAlign"=>"center"];
    $data['pendingReview'][] = ["name"=>"Process","textAlign"=>"center"];
    $data['pendingReview'][] = ["name"=>"Machine/Vendor","textAlign"=>"center"];
    $data['pendingReview'][] = ["name"=>"Operator/Inspector","textAlign"=>"center"];
    $data['pendingReview'][] = ["name"=>"Qty","textAlign"=>"center"];
    $data['pendingReview'][] = ["name"=>"Reviewed Qty","textAlign"=>"center"];
    $data['pendingReview'][] = ["name"=>"Pending Qty","textAlign"=>"center"];


    /* Pending Rejection Review */
    $data['rejectionReview'][] = ["name" => "Action", "textAlign" => "center"];
    $data['rejectionReview'][] = ["name"=>"#","textAlign"=>"center"];
    $data['rejectionReview'][] = ["name"=>"Source","textAlign"=>"center"];
    $data['rejectionReview'][] = ["name"=>"PRC No.","textAlign"=>"center"];
    $data['rejectionReview'][] = ["name"=>"Product","textAlign"=>"center"];
    $data['rejectionReview'][] = ["name"=>"Process","textAlign"=>"center"];
    $data['rejectionReview'][] = ["name"=>"Decision Date","textAlign"=>"center"];
    $data['rejectionReview'][] = ["name"=>"Decision","textAlign"=>"center"];
    $data['rejectionReview'][] = ["name"=>"Reviewed Qty","textAlign"=>"center"];


    /*** Cutting Header */
    $data['cutting'][] = ["name" => "Action", "textAlign" => "center"];
    $data['cutting'][] = ["name"=>"#","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"PRC No.","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"PRC Date","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"Product","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"Plan Qty","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"Cutting Lenght","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"Cutting Dia.","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"Cut Weight","textAlign"=>"center"];
    $data['cutting'][] = ["name"=>"Remark","textAlign"=>"center"];

    /*** Machine BreakDown Header */
    $data['machineBreakdown'][] = ["name" => "Action", "textAlign" => "center"];
    $data['machineBreakdown'][] = ["name"=>"#","textAlign"=>"center"];
    $data['machineBreakdown'][] = ["name"=>"Breakdown Time","textAlign"=>"center"];
    $data['machineBreakdown'][] = ["name"=>"End Time","textAlign"=>"center"];
    $data['machineBreakdown'][] = ["name"=>"PRC No.","textAlign"=>"center"];
    $data['machineBreakdown'][] = ["name"=>"Machine","textAlign"=>"center"];
    $data['machineBreakdown'][] = ["name"=>"Idle Reason","textAlign"=>"center"];
    $data['machineBreakdown'][] = ["name"=>"Remark","textAlign"=>"center"];
    
    /* SOP Header */
    $data['sop'][] = ["name"=>"Action","class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
	$data['sop'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center"];
    $data['sop'][] = ["name"=>"PRC No.","textAlign"=>"center"];
    $data['sop'][] = ["name"=>"PRC Date","textAlign"=>"center"];
    $data['sop'][] = ["name"=>"Product","textAlign"=>"center"];
    $data['sop'][] = ["name"=>"Qty.","textAlign"=>"center"];
    $data['sop'][] = ["name"=>"Remark"];

    /* SOP Header */
	$data['productionShortage'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center",'srnoposition'=>1];
    $data['productionShortage'][] = ["name"=>"Product","textAlign"=>"left"];
    $data['productionShortage'][] = ["name"=>"Customer","textAlign"=>"left"];
    $data['productionShortage'][] = ["name"=>"SO Number","textAlign"=>"center"];
    $data['productionShortage'][] = ["name"=>"Qty.","textAlign"=>"center"];
    $data['productionShortage'][] = ["name"=>"Total Qty.","textAlign"=>"center"];
    $data['productionShortage'][] = ["name"=>"Dispatch Qty.","textAlign"=>"center"];
    $data['productionShortage'][] = ["name"=>"WIP Qty"];
    $data['productionShortage'][] = ["name"=>"Production Finished"];
    $data['productionShortage'][] = ["name"=>"RTD Qty"];
    $data['productionShortage'][] = ["name"=>"Shortage Qty"];

    
    $masterCheckBox = '<input type="checkbox" id="masterSelect" class="filled-in chk-col-success BulkRequest" value=""><label for="masterSelect">ALL</label>';
    $data['semiFgShortage'][] = ["name"=>"Action","class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
    $data['semiFgShortage'][] = ["name"=>$masterCheckBox,"class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
	$data['semiFgShortage'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center"];
    $data['semiFgShortage'][] = ["name"=>"Item","textAlign"=>"left"];
    $data['semiFgShortage'][] = ["name"=>"Required Qty","textAlign"=>"center"];
    $data['semiFgShortage'][] = ["name"=>"WIP Qty"];
    $data['semiFgShortage'][] = ["name"=>"Stock Qty"];
    $data['semiFgShortage'][] = ["name"=>"Pending Request"];
    $data['semiFgShortage'][] = ["name"=>"Pending PO"];
    $data['semiFgShortage'][] = ["name"=>"Pending GRN QC"];
    $data['semiFgShortage'][] = ["name"=>"Shortage Qty"];

    /*** PRC LOG */
    $data['prcLog'][] = ["name"=>"Action","class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
	$data['prcLog'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center"];
    $data['prcLog'][] = ["name"=>"PRC No","textAlign"=>"center"];
    $data['prcLog'][] = ["name"=>"PRC Date","textAlign"=>"center"];
    $data['prcLog'][] = ["name"=>"Item","textAlign"=>"center"];
    $data['prcLog'][] = ["name"=>"Process From","textAlign"=>"center"];
    $data['prcLog'][] = ["name"=>"Unaccepted","textAlign"=>"center"];
    $data['prcLog'][] = ["name"=>"In","textAlign"=>"center"];
    $data['prcLog'][] = ["name"=>"Ok","textAlign"=>"center"];
    $data['prcLog'][] = ["name"=>"Rej. Found"];
    $data['prcLog'][] = ["name"=>"Rej."];
    $data['prcLog'][] = ["name"=>"Pending Prod."];
    $data['prcLog'][] = ["name"=>"Stock"];

    /*** Semi Finished LOG */
    $data['semiFinishedLog'][] = ["name"=>"Action","class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
    $data['semiFinishedLog'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center"];
    $data['semiFinishedLog'][] = ["name"=>"PRC No","textAlign"=>"center"];
    $data['semiFinishedLog'][] = ["name"=>"PRC Date","textAlign"=>"center"];
    $data['semiFinishedLog'][] = ["name"=>"Item","textAlign"=>"center"];
    $data['semiFinishedLog'][] = ["name"=>"Inward","textAlign"=>"center"];
    $data['semiFinishedLog'][] = ["name"=>"Moved","textAlign"=>"center"];
    $data['semiFinishedLog'][] = ["name"=>"Pending","textAlign"=>"center"];

    /** Production Store Demand */
    $data['mfgStoreDemand'][] = ["name"=>"Action","class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
    $data['mfgStoreDemand'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center"];
    $data['mfgStoreDemand'][] = ["name"=>"Req No","textAlign"=>"center"];
    $data['mfgStoreDemand'][] = ["name"=>"Req Date","textAlign"=>"center"];
    $data['mfgStoreDemand'][] = ["name"=>"Demand","textAlign"=>"center"];
    $data['mfgStoreDemand'][] = ["name"=>"Item","textAlign"=>"center"];
    $data['mfgStoreDemand'][] = ["name"=>"Qty","textAlign"=>"center"];
    $data['mfgStoreDemand'][] = ["name"=>"Issued","textAlign"=>"center"];
    $data['mfgStoreDemand'][] = ["name"=>"Pending","textAlign"=>"center"];

    /** Production Store Request */
    $data['mfgStoreRequest'][] = ["name"=>"Action","class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
    $data['mfgStoreRequest'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center"];
    $data['mfgStoreRequest'][] = ["name"=>"Req No","textAlign"=>"center"];
    $data['mfgStoreRequest'][] = ["name"=>"Req Date","textAlign"=>"center"];
    $data['mfgStoreRequest'][] = ["name"=>"Request To","textAlign"=>"center"];
    $data['mfgStoreRequest'][] = ["name"=>"Item","textAlign"=>"center"];
    $data['mfgStoreRequest'][] = ["name"=>"Qty","textAlign"=>"center"];
    $data['mfgStoreRequest'][] = ["name"=>"Issued","textAlign"=>"center"];
    $data['mfgStoreRequest'][] = ["name"=>"Pending","textAlign"=>"center"];

    /** Production Store Stock */
    $data['mfgStoreStock'][] = ["name"=>"Action","class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
	$data['mfgStoreStock'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"textAlign"=>"center"];
    $data['mfgStoreStock'][] = ["name"=>"PRC No","textAlign"=>"center"];
    $data['mfgStoreStock'][] = ["name"=>"PRC Date","textAlign"=>"center"];
    $data['mfgStoreStock'][] = ["name"=>"Item","textAlign"=>"center"];
    $data['mfgStoreStock'][] = ["name"=>"Process From","textAlign"=>"center"];
    $data['mfgStoreStock'][] = ["name"=>"Type","textAlign"=>"center"];
    $data['mfgStoreStock'][] = ["name"=>"Stock"];

    return tableHeader($data[$page]);
}

/* Process Table Data */
function getProcessData($data){
    $deleteButton = $editButton = '';
    if($data->is_system == 0 ){
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Process'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editProcess', 'title' : 'Update Process','call_function':'edit'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    }
    
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->process_name,$data->remark];
}

/* Rejection Comment Table Data */
function getRejectionCommentData($data){
    $rejection_type = ($data->type == 1 ? "Rejection Reason": ($data->type == 2 ? "Idle Reason":"Rework Reason"));

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : '".$rejection_type."'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editRejection', 'title' : 'Update  ".$rejection_type."','call_function':'edit'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->code,$data->remark];
}

function getEstimationData($data){

    $soBomParam = "{'postData':{'trans_main_id' : ".$data->trans_main_id.",'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xxl', 'form_id' : 'addOrderBom', 'fnedit':'orderBom', 'fnsave':'saveOrderBom','title' : 'Order Bom','res_function':'resSaveOrderBom','js_store_fn':'customStore'}";
    $soBom = '<a class="btn btn-info btn-delete permission-write" href="javascript:void(0)" onclick="edit('.$soBomParam.');" datatip="SO Bom" flow="down"><i class="fa fa-database"></i></a>';

    $viewBomParam = "{'postData':{'trans_child_id':".$data->trans_child_id."},'modal_id' : 'modal-xl','fnedit':'viewOrderBom','title' : 'View Bom [Item Name : ".$data->item_name."]','button':'close'}";
    $viewBom = '<a class="btn btn-primary permission-read" href="javascript:void(0)" onclick="edit('.$viewBomParam.');" datatip="View Item Bom" flow="down"><i class="fa fa-eye"></i></a>';

    $reqParam = "{'postData':{'trans_child_id':".$data->trans_child_id.",'trans_number':'".$data->trans_number."','item_name':'".$data->item_name."'},'modal_id' : 'modal-xl', 'form_id' : 'addOrderBom', 'fnedit':'purchaseRequest', 'fnsave':'savePurchaseRequest','title' : 'Send Purchase Request'}";
    $reqButton = '<a class="btn btn-info btn-delete permission-write" href="javascript:void(0)" onclick="edit('.$reqParam.');" datatip="Purchase Request" flow="down"><i class="fa fa-paper-plane"></i></a>';

    $estimationParam = "{'postData':{'id':'".$data->id."','trans_child_id':".$data->trans_child_id.",'trans_main_id':'".$data->trans_main_id."'},'modal_id' : 'modal-xl', 'form_id' : 'estimation', 'fnedit':'addEstimation', 'fnsave':'saveEstimation','title' : 'Estimation & Design'}";
    $estimationButton = '<a class="btn btn-success permission-write" href="javascript:void(0)" onclick="edit('.$estimationParam.');" datatip="Estimation" flow="down"><i class="fa fa-plus"></i></a>';

    if($data->priority == 1):
        $data->priority_status = '<span class="badge badge-pill badge-danger m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 2):
        $data->priority_status = '<span class="badge badge-pill badge-warning m-1">'.$data->priority_status.'</span>';
    elseif($data->priority == 3):
        $data->priority_status = '<span class="badge badge-pill badge-info m-1">'.$data->priority_status.'</span>';
    endif;

    $data->bom_status = '<span class="badge badge-pill badge-'.(($data->bom_status == "Generated")?"success":"danger").' m-1">'.$data->bom_status.'</span>';

    $action = getActionButton($soBom.$viewBom.$reqButton.$estimationButton);

    return [$action,$data->sr_no,$data->job_number,$data->trans_date,$data->party_name,$data->item_name,$data->qty,$data->bom_status,$data->priority_status,$data->fab_dept_note,$data->pc_dept_note,$data->ass_dept_note,$data->remark];
}

/* Product Option Data */
function getProductOptionData($data){
    $bomParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'addProductKitItems', 'title' : 'Create Material BOM [ ".htmlentities($data->item_name)." ]','call_function':'addProductKitItems','button':'close'}";

    $itemProcessParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'viewProductProcess', 'title' : 'Set Product Process [ ".htmlentities($data->item_name)." ]','call_function':'viewProductProcess','button':'close'}";

    $cycleTimeParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'cycleTime', 'title' : 'Set Cycle Time [ ".htmlentities($data->item_name)." ]','call_function':'addCycleTime','button':'both','fnsave':'saveCT'}";

    $packingParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'addPackingStandard', 'title' : 'Add Packing Standard [ ".htmlentities($data->item_name)." ]','call_function':'addPackingStandard','button':'close'}";

	$btn = '<div class="btn-group" role="group" aria-label="Basic example">
	
			<button type="button" class="btn btn-twitter permission-modify" onclick="addProcess('.$itemProcessParam .')"  datatip="View Process" flow="down"><i class="fa fa-list"></i></button>

			<button type="button" class="btn btn-info permission-modify" onclick="modalAction('.$bomParam .')" datatip="BOM" flow="down" ><i class="fas fa-dolly-flatbed"></i></button>

			<button type="button" class="btn btn-twitter permission-modify" onclick="modalAction('.$cycleTimeParam .')" datatip="Cycle Time" flow="down"><i class="fa fa-clock"></i></button>

			<a href="'.base_url('productOption/productOptionPrint/'.$data->id).'" type="button" class="btn btn-info" datatip="Print" flow="down" target="_blank"><i class="fas fa-print"></i></a>
			
            <button type="button" class="btn btn-twitter permission-modify" onclick="modalAction('.$packingParam .')" datatip="Packing Standard" flow="down"><i class="fas fa-plus"></i></button>

		</div>';

    return [$data->sr_no,$data->item_code,$data->item_name,$data->bom,$data->process,$data->cycleTime,$btn];
}

/* Outsource Table Data */
function getOutsourceData($data){
    
    $logParam = "{'postData':{'process_id' : ".$data->process_id.",'prc_id' : ".$data->prc_id.",'ref_trans_id':".$data->id.",'challan_id':".$data->challan_id.",'processor_id':".$data->party_id.",'process_by':'3','trans_type':".$data->trans_type.",'process_from':".$data->process_from."},'modal_id' : 'bs-right-lg-modal', 'call_function':'prcLog', 'form_id' : 'addPrcLog', 'title' : 'Receive Challan', 'js_store_fn' : 'storeSop', 'fnsave' : 'savePRCLog','controller':'sopDesk','button':'close'}";
    $logBtn = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Receive Challan" flow="down" onclick="modalAction('.$logParam.')"><i class=" fas fa-paper-plane"></i></a>';

    $pending_qty = $data->qty - ($data->ok_qty+$data->rej_qty);
    $deleteButton = "";
    if($pending_qty > 0){
        $deleteParam = "{'postData':{'id' : ".$data->challan_id."}}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    }
    
    $print = '<a href="'.base_url('outsource/outSourcePrint/'.$data->out_id).'" type="button" class="btn btn-primary" datatip="Print" flow="down" target="_blank"><i class="fas fa-print"></i></a>';

    $print2 = '<a href="'.base_url('outsource/jobworkOutChallan/'.$data->out_id).'" type="button" class="btn btn-primary" datatip="Print 2" flow="down" target="_blank"><i class="fas fa-print"></i></a>';

    $action = getActionButton($print.$print2.$logBtn.$deleteButton);

    return [$action,$data->sr_no,date('d-m-Y',strtotime($data->ch_date)),$data->ch_number,$data->prc_number,$data->party_name,$data->item_name,$data->process_name,floatVal($data->qty).' '.$data->uom,floatVal($data->ok_qty+$data->rej_qty).' '.$data->uom,floatVal($pending_qty).' '.$data->uom]; 
}


/* Get Pending Rejection Review Data */
function getPendingReviewData($data){
    $rwBtn="";
    $title = '[ Pending Decision : '.floatval($data->pending_qty).' ]';
    $okBtnParam="{'postData':{'id' : " . $data->id . ",'source':'".$data->source."'} ,'modal_id' : 'bs-right-md-modal', 'form_id' : 'okOutWard', 'title' : 'Ok ".$title."','button' : 'both','call_function' : 'convertToOk','fnsave' : 'saveReview'}";
    $rejBtnParam="{'postData':{'id' : " . $data->id . ",'source':'".$data->source."'} ,'modal_id' : 'bs-right-lg-modal', 'form_id' : 'rejOutWard', 'title' : 'Rejection ".$title." ','button' : 'both','call_function' : 'convertToRej','fnsave' : 'saveReview'}";
    $rwBtnParam="{'postData':{'id' : " . $data->id . ",'source':'".$data->source."'} ,'modal_id' : 'bs-right-lg-modal', 'form_id' : 'rwOutWard', 'title' : 'Rework ".$title." ','button' : 'both','call_function' : 'convertToRw','fnsave' : 'saveReview'}";

	$okBtn = '<a  onclick="modalAction('. $okBtnParam . ')"  class="btn btn-success btn-edit permission-modify" datatip="Ok" flow="down"><i class="mdi mdi-check"></i></a>';
    $rejBtn = '<a onclick="modalAction(' . $rejBtnParam . ')"  class="btn btn-danger btn-edit permission-modify" datatip="Rejection" flow="down"><i class="mdi mdi-close"></i></a>';
    if($data->source == 'MFG'){
        $rwBtn = '<a  onclick="modalAction('. $rwBtnParam . ')"  class="btn btn-info btn-edit permission-modify" datatip="Rework" flow="down"><i class=" fas fa-retweet"></i></a>';
    }
	$rejTag = '<a href="' . base_url('sopDesk/printPRCRejLog/' . $data->id) . '" target="_blank" class="btn btn-sm btn-dark waves-effect waves-light mr-1" title="Rejection Tag"><i class="fas fa-print"></i></a>';
	
    $action = getActionButton($okBtn.$rejBtn.$rwBtn.$rejTag);
    $process_name = ($data->source == 'FIR')?'Final Inspection':$data->process_name;
    return [$action,$data->sr_no,$data->prc_number,'['.$data->item_code.'] '.$data->item_name,formatDate($data->trans_date),$process_name,(!empty($data->processor_name)?$data->processor_name:''),$data->emp_name,floatval($data->rej_found).' '.$data->uom,floatval($data->review_qty).' '.$data->uom,floatval($data->pending_qty).' '.$data->uom];
}

/* Get Rejection Review Data */
function getRejectionReviewData($data){
    
    $deleteParam = "{'postData':{'id' : ".$data->id."},'fndelete':'deleteReview'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    $rejTag = "";
    if($data->decision_type == 1){
        $rejTag = '<a href="' . base_url('rejectionReview/printRejTag/' . $data->id) . '" target="_blank" class="btn btn-sm btn-dark waves-effect waves-light mr-1" title="Rejection Tag"><i class="fas fa-print"></i></a>';
    }
    $action = getActionButton($rejTag.$deleteButton);
 
    return [$action,$data->sr_no,$data->source,$data->prc_number,'['.$data->item_code.'] '.$data->item_name,formatDate($data->created_at),$data->process_name,$data->decision,floatval($data->qty).' '.$data->uom];
}

/* Cutting PRC Table Data */
function getCuttingData($data){
    $deleteButton = ""; $editButton=""; $startButton = ""; $logButton = "";
    if($data->status == 1){
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Process'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editPRC', 'title' : 'Update Cutting PRC','call_function':'editCutting','fnsave':'saveCutting'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    
        $startParam = "{'postData':{'id' : ".$data->id."},'message' : 'Are you sure you want to start PRC ? once you start you can not edit or delete','fnsave':'startPRC'}";
        $startButton = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Start" flow="down" onclick="confirmStore('.$startParam.');"><i class=" fas fa-play"></i></a>';
    }else{
        $logParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'addCuttingLog', 'title' : 'Cutting Log','call_function':'addCuttingLog','controller':'sopDesk','button':'close'}";
        $logButton = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Log" flow="down" onclick="modalAction('.$logParam.');"><i class=" fas fa-paper-plane
        "></i></a>';
    }
    $mtParam = "{'postData':{'id' : ".$data->id.",'item_id':".$data->item_id.",'prc_qty':".$data->prc_qty."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'prcMaterial', 'title' : 'Required Material For PRC', 'fnsave' : 'savePrcMaterial', 'js_store_fn' : 'store','call_function':'requiredMaterial','controller':'sopDesk'}";
    $materialBtn = '<a class="btn btn-info btn-edit permission-modify" href="javascript:void(0)" datatip="Required Material" flow="down" onclick="modalAction('.$mtParam.');"><i class="fas fa-clipboard-check"></i></a>';

    $mtParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'prcMaterial', 'title' : 'Material Detail', 'fnsave' : 'savePrcMaterial', 'js_store_fn' : 'store','call_function':'getMaterialDetail','controller':'sopDesk','button':'close'}";
    $issueMaterialBtn = '<a class="btn btn-info btn-edit permission-modify" href="javascript:void(0)" datatip="Material Detail" flow="down" onclick="modalAction('.$mtParam.');"><i class="fas fa-th"></i></a>';

    $print = '<a href="'.base_url('sopDesk/cuttingPrint/'.$data->id).'" type="button" class="btn btn-primary" datatip="Print" flow="down" target="_blank"><i class="fas fa-print"></i></a>';

	$action = getActionButton($logButton.$startButton.$issueMaterialBtn.$materialBtn.$print.$editButton.$deleteButton);
    return [$action,$data->sr_no,$data->prc_number,formatDate($data->prc_date),$data->item_name,floatval($data->prc_qty),floatval($data->cutting_length),floatval($data->cutting_dia),floatval($data->cut_weight),$data->job_instruction];
}

/* Machine Breakdown Table Data */
function getMachineBreakdownData($data){
    $solutionBtn = $editButton = $deleteButton = '';
    if(empty($data->end_date)){
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Process'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editMachineBreakdown', 'title' : 'Update Machine Breakdown','call_function':'edit','fnsave':'save'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        
        $solutionParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'addSolution', 'title' : 'Add Solution','call_function':'addSolution','fnsave':'saveSolution'}";
        $solutionBtn = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Solution" flow="down" onclick="modalAction('.$solutionParam.');"><i class="far fa-check-square"></i></a>';
    }
  
	$action = getActionButton($solutionBtn.$editButton.$deleteButton);
    return [$action,$data->sr_no,date('d-m-Y H:i:s',strtotime($data->trans_date)),date('d-m-Y H:i:s',strtotime($data->end_date)),$data->prc_number,$data->machine_name,(!empty($data->idle_reason)?'['.$data->code.'] '.$data->idle_reason:''),$data->remark];
}

function getSopData($data){
    $materialBtn = $startButton = $editButton = $deleteButton = $holdBtn = $shortBtn = $restartBtn = $updateQty="";
	$prc_number = '<a href="'.base_url("sopDesk/prcDetail/".$data->id).'">'.$data->prc_number.'</a>';

    $mtParam = "{'postData':{'id' : ".$data->id.",'prc_qty' : ".$data->prc_qty.",'item_id':".$data->item_id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'prcMaterial', 'title' : 'Material Request For : ".$data->prc_number."', 'fnsave' : 'savePrcMaterial','call_function':'requiredMaterial'}";
    $materialBtn = ' <a class="btn btn-primary permission-modify" href="javascript:void(0)" datatip="Material Request" flow="down" onclick="modalAction('.$mtParam.')"><i class="far fa-paper-plane"></i></a>';
    if($data->status == 1 ){
        $startTitle = 'Start PRC : '.$data->prc_number;
        $startParam = "{'postData':{'id' : ".$data->id.",'item_id':".$data->item_id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editPrc', 'title' : '".$startTitle."', 'fnsave' : 'startPrc', 'js_store_fn' : 'confirmStore','call_function':'setPrcProcesses'}";
        $startButton = ' <a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Start" flow="down" onclick="modalAction('.$startParam.')"><i class="fas fa-play-circle"></i></a>';

        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editPrc', 'title' : 'Update PRC', 'fnsave' : 'savePRC'}";
        $editButton= ' <a class="btn btn-success permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.')"><i class="far fa-edit"></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'PRC'}";
        $deleteButton = ' <a class="btn btn-danger permission-remove" href="javascript:void(0)" datatip="Delete" flow="down" onclick="trash('.$deleteParam.')"><i class="mdi mdi-trash-can-outline"></i></a>';
    }elseif($data->status == 2){

        /*** IF PRC IS IN PROGRSS THEN PROCESS BUTTON */
        $startTitle = 'PRC Process: '.$data->prc_number;
        $startParam = "{'postData':{'id' : ".$data->id.",'item_id':".$data->item_id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editPrc', 'title' : '".$startTitle."', 'fnsave' : 'startPrc', 'js_store_fn' : 'confirmStore','call_function':'setPrcProcesses'}";
        $startButton = ' <a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="PRC Process" flow="down" onclick="modalAction('.$startParam.')"><i class="fas fa-play-circle"></i></a>';
        $updateQtyParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'updatePrcQty', 'title' : 'Update PRC Qty [".$data->prc_number."] ', 'call_function' : 'updatePrcQty', 'button' : 'close'}";
        $updateQty= '<a class="btn btn-dark permission-modify" href="javascript:void(0)" datatip="Update PRC Qty." flow="down" onclick="modalAction(' . $updateQtyParam . ');"><i class="far fa-plus-square"></i> </a>';
        
        $holdParam = "{'postData':{'id' : ".$data->id.", 'status' : 4},'message' : 'Are you sure want to Hold this PRC ?', 'fnsave' : 'changePrcStage'}";
        $holdBtn= ' <a class="btn btn-warning permission-modify" href="javascript:void(0)" datatip="Hold" flow="down" onclick="confirmStore('.$holdParam.')"><i class="far fa-pause-circle"></i></a>';
        
        $shortParam = "{'postData':{'id' : ".$data->id.", 'status' : 5},'message' : 'Are you sure want to Short Close this PRC ?', 'fnsave' : 'changePrcStage'}";
        $shortBtn = ' <a class="btn btn-danger permission-modify " href="javascript:void(0)" datatip="Short Close" flow="down" onclick="confirmStore('.$shortParam.')"><i class="fas mdi mdi-close-circle-outline"></i></a>';
    }
    elseif($data->status == 4){
        $restartParam = "{'postData':{'id' : ".$data->id.", 'status' : 2},'message' : 'Are you sure want to Restart this PRC ?', 'fnsave' : 'changePrcStage'}";
        $restartBtn = ' <a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Restart" flow="down" onclick="confirmStore('.$restartParam.')"><i class="mdi mdi-restart"></i></a>';
    }
    $action = getActionButton($materialBtn.$startButton.$holdBtn.$shortBtn.$restartBtn.$updateQty.$editButton.$deleteButton);
    return [$action,$data->sr_no,$prc_number,formatDate($data->prc_date),$data->item_code.' '.$data->item_name,floatval($data->prc_qty).' '.$data->uom,$data->remark];
}

function getProductionShortageData($data){
    $addParam = "{'postData':{'item_id' : ".$data->item_id."},'modal_id' : 'bs-right-lg-modal', 'call_function':'addPRC', 'form_id' : 'addPRC', 'title' : 'New PRC', 'fnsave' : 'savePRC'}";
    $prcBtn= '<a class="btn btn-dark permission-modify" href="javascript:void(0)" datatip="Add PRC" flow="down" onclick="modalAction(' . $addParam . ');"><i class="far fa-plus-square"></i> </a>';
    
	$sort_qty = ($data->total_qty - ($data->total_dispatch_qty+$data->wip_qty+$data->prd_finish_Stock+$data->rtd_Stock));
	$sortage_qty = (($sort_qty>0)?$sort_qty:0);
	
	
	$action = getActionButton($prcBtn);
    return [$data->sr_no,$data->item_code.' '.$data->item_name,$data->party_name,$data->so_number,floatval($data->qty).' '.$data->uom,floatval($data->total_qty).' '.$data->uom,floatval($data->total_dispatch_qty).' '.$data->uom,floatval($data->wip_qty).' '.$data->uom,floatval($data->prd_finish_Stock).' '.$data->uom,floatval($data->rtd_Stock).' '.$data->uom,floatval($sortage_qty).' '.$data->uom];
}

function getSemiFgShortageData($data){
	$sort_qty = ($data->required_qty - ($data->wip_qty+$data->stock_qty+$data->pending_req + $data->pending_po + $data->pending_grn));
	$sortage_qty = (($sort_qty>0)?$sort_qty:0);

    $addParam = "{'postData':{'item_id' : ".$data->id.", 'qty' : '".$sortage_qty."'},'modal_id' : 'bs-right-lg-modal', 'call_function':'addPRC', 'form_id' : 'addPRC', 'title' : 'New PRC', 'fnsave' : 'savePRC'}";
    $prcBtn= '<a class="btn btn-dark permission-modify" href="javascript:void(0)" datatip="Add PRC" flow="down" onclick="modalAction(' . $addParam . ');"><i class="far fa-plus-square"></i> </a>';    
	
    $selectBox = '<input type="checkbox" name="ref_id[]" id="ref_id_'.$data->sr_no.'" class="filled-in chk-col-success BulkRequest" value="'.$data->id.'" data-qty="'.$sortage_qty.'" data-item_name="'.$data->item_name.'"><label for="ref_id_'.$data->sr_no.'"></label>';

	$action = getActionButton($prcBtn);
    return [$action,$selectBox,$data->sr_no,$data->item_code.' '.$data->item_name,floatval($data->required_qty).' '.$data->uom,floatval($data->wip_qty).' '.$data->uom,floatval($data->stock_qty).' '.$data->uom,floatval($data->pending_req).' '.$data->uom,floatval($data->pending_po).' '.$data->uom,floatval($data->pending_grn).' '.$data->uom,floatval($sortage_qty).' '.$data->uom]; 
}

function getPrcLogData($data){
    $in_qty = (!empty($data->accepted_qty)?$data->accepted_qty:0);
    $ok_qty = !empty($data->ok_qty)?$data->ok_qty:0;
    $rej_found_qty = !empty($data->rej_found)?$data->rej_found:0;
    $rej_qty = !empty($data->rej_qty)?$data->rej_qty:0;
    $rw_qty = !empty($data->rw_qty)?$data->rw_qty:0;
    $pendingReview = $rej_found_qty - $data->review_qty;
    $pending_production =($in_qty) - ($ok_qty+$rej_qty+$rw_qty+$pendingReview);
    $movement_qty =!empty($data->movement_qty)?$data->movement_qty:0;
    $short_qty =!empty($data->short_qty)?$data->short_qty:0;
    $pending_movement = ($ok_qty - $movement_qty);
    $pending_accept =$data->inward_qty - $data->accepted_qty;

    $logBtn = "";$movementBtn="";$chReqBtn="";$receiveBtn="";$firButton = "";
    if($data->process_id == 2){
        $reportParam = "{'postData':{'process_id' : ".$data->process_id.",'prc_id':".$data->prc_id.",'trans_type':".$data->move_type.",'process_from':".$data->process_from."},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'firInsp', 'title' : 'Final Inspection','call_function':'addFinalInspection','fnsave':'savePrcLog', 'js_store_fn' : 'customStore'}";
	    $firButton = '<a href="javascript:void(0)" type="button" class="btn btn-info permission-modify" datatip="Final Inspection" flow="down" onclick="modalAction('.$reportParam.');"><i class="fa fa-file-alt"></i></a>';
    }else{
        $logParam = "{'postData':{'process_id' : ".$data->process_id.",'prc_id':".$data->prc_id.",'trans_type':".$data->move_type.",'process_from':".$data->process_from."},'modal_id' : 'bs-right-lg-modal', 'call_function':'prcLog', 'form_id' : 'addPrcLog', 'title' : 'PRC LOG', 'fnsave' : 'savePRCLog','button':'close'}";
        $logBtn = '<a href="javascript:void(0)" onclick="modalAction('.$logParam.')" class="btn btn-success permission-modify" datatip="Add Log" flow="down"><i class="fas fa-clipboard-list"></i></a>';
    
        $title = '[Pending Qty : '.floatval($pending_production).']';
        $chReqParam = "{'postData':{'process_id' : ".$data->process_id.",'prc_id':".$data->prc_id.",'trans_type':".$data->move_type.",'process_from':".$data->process_from."},'modal_id' : 'bs-right-md-modal', 'call_function':'challanRequest', 'form_id' : 'addChallanRequest', 'title' : 'Challan Request ".$title ."', 'fnsave' : 'saveAcceptedQty','button':'close'}";
        $chReqBtn = '<a href="javascript:void(0)" class="btn btn-warning permission-modify" datatip="Challan Request" flow="down" onclick="modalAction('.$chReqParam .')"><i class="fab fa-telegram-plane"></i></a>';
    }
    
    $movementParam = "{'postData':{'process_id' : ".$data->process_id.",'prc_id':".$data->prc_id.",'move_type':".$data->move_type.",'process_from':".$data->process_from."},'modal_id' : 'bs-right-lg-modal', 'call_function':'prcMovement', 'form_id' : 'addPrcMovement', 'title' : 'PRC Movement', 'fnsave' : 'savePRCMovement','button':'close'}";
    $movementBtn = '<a href="javascript:void(0)" class="btn btn-info permission-modify" datatip="Movement" flow="down" onclick="modalAction('.$movementParam.')"><i class="fa fa-step-forward"></i></a>';
    $acceptBtn="";
    if($pending_accept > 0 || $in_qty > 0){
        $title = '[Pending Qty : '.floatval($pending_accept).']';
        $acceptParam = "{'postData':{'process_id' : ".$data->process_id.",'prc_id':".$data->prc_id.",'trans_type':".$data->move_type.",'process_from':".$data->process_from."},'modal_id' : 'bs-right-md-modal', 'call_function':'prcAccept', 'form_id' : 'addPrcAccept', 'title' : 'Accept For Production ".$title."', 'fnsave' : 'saveAcceptedQty','button':'close'}";
        $acceptBtn = '<a href="javascript:void(0)" class="btn btn-dark permission-modify" datatip="Accept" flow="down" onclick="modalAction('.$acceptParam .')"><i class="far fa-check-circle"></i></a>';
    }

    
    $action = getActionButton($acceptBtn.$firButton.$logBtn.$chReqBtn.$movementBtn);
    return [$action,$data->sr_no,$data->prc_number,formatDate($data->prc_date),$data->item_code.' '.$data->item_name,
    (!empty($data->from_process_name)?$data->from_process_name:'Initial Stage'),
    floatval($pending_accept).' '.$data->uom,
    floatval($in_qty).' '.$data->uom,
    floatval($ok_qty).' '.$data->uom,
    floatval($rej_found_qty).' '.$data->uom,
    floatval($rej_qty).' '.$data->uom,
    floatval($pending_production).' '.$data->uom,
    floatval($pending_movement).' '.$data->uom];
}

function getSemiFinishedLogData($data){
    $in_qty = (!empty($data->in_qty)?$data->in_qty:0);
    $pending_accept =!empty($data->pending_accept)?$data->pending_accept:0;

    $movementParam = "{'postData':{'process_id' : 1,'prc_id':".$data->prc_id."},'modal_id' : 'bs-right-lg-modal', 'call_function':'semiFinishMovement', 'form_id' : 'addPrcMovement', 'title' : 'PRC Movement', 'js_store_fn' : 'storeSop', 'fnsave' : 'savePRCMovement','button':'close'}";
    $movementBtn = '<a href="javascript:void(0)" class="btn btn-info permission-modify" datatip="Movement" flow="down" onclick="modalAction('.$movementParam.')"><i class="fa fa-step-forward"></i></a>';
    $action = getActionButton($movementBtn);
    return [$action,$data->sr_no,$data->prc_number,formatDate($data->prc_date),$data->item_code.' '.$data->item_name,floatval($pending_accept).' '.$data->uom,floatval($in_qty).' '.$data->uom,""]; 
}

function getMfgStoreData($data){
    $editButton = $deleteButton = $issueButton = "";$demand = '';
    if($data->issue_qty <= 0 && $data->current_process == $data->req_from){
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editRequest', 'title' : 'Update Request', 'fnsave' : 'saveMfgRequest','call_function':'editMfgRequest'}";
        $editButton= ' <a class="btn btn-success btn-edit" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.')"><i class="far fa-edit"></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Request','fndelete' : 'deleteMfgRequest'}";
        $deleteButton = ' <a class="btn btn-danger btn-edit" href="javascript:void(0)" datatip="Delete" flow="down" onclick="trash('.$deleteParam.')"><i class="mdi mdi-trash-can-outline"></i></a>';
        
    }
    if($data->current_process == $data->req_from){
        $demand = $data->req_to_process;
    }
    if($data->current_process == $data->req_to){
        $issueparam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'issueItem', 'title' : 'Issue Item', 'fnsave' : 'saveIssuedItem','call_function':'issueRequestedItem'}";
        $issueButton= ' <a class="btn btn-success btn-edit" href="javascript:void(0)" datatip="Issue" flow="down" onclick="modalAction('.$issueparam.')"><i class="far fa-paper-plane"></i></a>';
        $demand = $data->req_from_process;
    }
    $action = getActionButton($issueButton.$editButton.$deleteButton);
    $pending_qty = $data->qty - $data->issue_qty;
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$demand,$data->item_code.' '.$data->item_name,floatval($data->qty).' '.$data->uom,floatval($data->issue_qty).' '.$data->uom,$pending_qty.' '.$data->uom]; 
}

function getMfgStoreStockData($data){
    $ok_qty = !empty($data->ok_qty)?$data->ok_qty:0;
    $movement_qty =!empty($data->movement_qty)?$data->movement_qty:0;
    $pending_movement = ($ok_qty - $movement_qty);

    $movementBtn="";
    
    $movementParam = "{'postData':{'process_id' : ".$data->process_id.",'prc_id':".$data->prc_id.",'move_type':".$data->trans_type.",'process_from':".$data->process_from."},'modal_id' : 'bs-right-lg-modal', 'call_function':'prcMovement', 'form_id' : 'addPrcMovement', 'title' : 'PRC Movement', 'fnsave' : 'savePRCMovement','button':'close'}";
    $movementBtn = '<a href="javascript:void(0)" class="btn btn-info permission-modify" datatip="Movement" flow="down" onclick="modalAction('.$movementParam.')"><i class="fa fa-step-forward"></i></a>';
   
    
    $action = getActionButton($movementBtn);
    return [$action,$data->sr_no,$data->prc_number,formatDate($data->prc_date),$data->item_code.' '.$data->item_name,(!empty($data->from_process_name)?$data->from_process_name:'Initial Stage'),(($data->trans_type == 1)?'Regular':'Rework'), floatval($pending_movement).' '.$data->uom]; 
}
?>