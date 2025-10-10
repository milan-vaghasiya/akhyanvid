<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getQualityDtHeader($page){

    /* Instrument Header */
    $masterInstSelect = '<input type="checkbox" id="masterInstSelect" class="filled-in chk-col-success BulkInstChallan" value=""><label for="masterInstSelect">ALL</label>';
    
    $data['instrumentChk'][] = ["name"=>"Action","style"=>"width:5%;"];
	$data['instrumentChk'][] = ["name"=>"#","style"=>"width:5%;"];
    $data['instrumentChk'][] = ["name"=>$masterInstSelect,"style"=>"width:10%;","textAlign"=>"center","orderable"=>"false"];
	$data['instrumentChk'][] = ["name"=>"Code"];
    $data['instrumentChk'][] = ["name"=>"Description"];
    $data['instrumentChk'][] = ["name"=>"Make"];
    $data['instrumentChk'][] = ["name"=>"Required"];
    $data['instrumentChk'][] = ["name"=>"Frequency<br>(Months)"];
    $data['instrumentChk'][] = ["name"=>"Location"];
	$data['instrumentChk'][] = ["name"=>"Cal Date","style"=>"width:80px;"];
	$data['instrumentChk'][] = ["name"=>"Due Date","style"=>"width:80px;"];
	$data['instrumentChk'][] = ["name"=>"Plan Date","style"=>"width:80px;"];
	$data['instrumentChk'][] = ["name"=>"Inward Date","style"=>"width:80px;"];
	
	/* Instrument Header Without Checkbox*/
	$data['instrument'][] = ["name"=>"Action","style"=>"width:5%;"];
	$data['instrument'][] = ["name"=>"#","style"=>"width:5%;"];
	$data['instrument'][] = ["name"=>"Code"];
    $data['instrument'][] = ["name"=>"Description"];
    $data['instrument'][] = ["name"=>"Make"];
    $data['instrument'][] = ["name"=>"Required"];
    $data['instrument'][] = ["name"=>"Frequency<br>(Months)"];
    $data['instrument'][] = ["name"=>"Location"];
	$data['instrument'][] = ["name"=>"Cal Date","style"=>"width:80px;"];
	$data['instrument'][] = ["name"=>"Due Date","style"=>"width:80px;"];
	$data['instrument'][] = ["name"=>"Plan Date","style"=>"width:80px;"];
	$data['instrument'][] = ["name"=>"Inward Date","style"=>"width:80px;"];
	
	/* Instrument Header Rejected */
    $data['instrumentRej'][] = ["name"=>"Action","style"=>"width:5%;"];
	$data['instrumentRej'][] = ["name"=>"#","style"=>"width:5%;"];
	$data['instrumentRej'][] = ["name"=>"Code"];
    $data['instrumentRej'][] = ["name"=>"Description"];
    $data['instrumentRej'][] = ["name"=>"Make"];
    $data['instrumentRej'][] = ["name"=>"Required"];
    $data['instrumentRej'][] = ["name"=>"Frequency<br>(Months)"];
    $data['instrumentRej'][] = ["name"=>"Location"];
	$data['instrumentRej'][] = ["name"=>"Reject Date"];
	$data['instrumentRej'][] = ["name"=>"Reject Reason"];

    /* In Challan Header */
    $data['qcChallan'][] = ["name"=>"Action","style"=>"width:5%;"];
    $data['qcChallan'][] = ["name"=>"#","style"=>"width:5%;"];
    $data['qcChallan'][] = ["name"=>"Ch. No."];
    $data['qcChallan'][] = ["name"=>"Ch. Date"];
    $data['qcChallan'][] = ["name"=>"Ch. Type"];
    $data['qcChallan'][] = ["name"=>"Handover To"];
    $data['qcChallan'][] = ["name"=>"Issue To"];
    $data['qcChallan'][] = ["name"=>"Code"];
    $data['qcChallan'][] = ["name"=>"Description"];
    $data['qcChallan'][] = ["name"=>"Remark"];

    /* Calibration Item Details*/
    $data['calibrationData'][] = ["name"=>"Action","style"=>"width:5%;"];
    $data['calibrationData'][] = ["name"=>"#","style"=>"width:5%;"];
    $data['calibrationData'][] = ["name"=>"Calibration Agency"];
    $data['calibrationData'][] = ["name"=>"Calibration No."];
    $data['calibrationData'][] = ["name"=>"Certificate File"];
    $data['calibrationData'][] = ["name"=>"Remark"];

    /* QC Purchase Request Header */
    $data['qcPurchaseRequest'][] = ["name"=>"Action","style"=>"width:5%;"];
    $data['qcPurchaseRequest'][] = ["name"=>"#","style"=>"width:5%;"];
    $data['qcPurchaseRequest'][] = ["name"=>"Request Date"];
    $data['qcPurchaseRequest'][] = ["name"=>"Request No"];
    $data['qcPurchaseRequest'][] = ["name"=>"Category"];
    $data['qcPurchaseRequest'][] = ["name"=>"Qty"];
    $data['qcPurchaseRequest'][] = ["name"=>"Size"];
    $data['qcPurchaseRequest'][] = ["name"=>"Make"];
    $data['qcPurchaseRequest'][] = ["name"=>"Required Date"];    
    $data['qcPurchaseRequest'][] = ["name"=>"Remark"];

    $masterQcCheckBox = '<input type="checkbox" id="masterQcSelect" class="filled-in chk-col-success BulkQcRequest" value=""><label for="masterQcSelect">ALL</label>';
    /* QC Indent Header */
    $data['qcIndent'][] = ["name"=>"Action","style"=>"width:5%;"];
	$data['qcIndent'][] = ["name"=>"#","style"=>"width:5%;"];
    $data['qcIndent'][] = ["name"=>$masterQcCheckBox,"style"=>"width:10%;","textAlign"=>"center","orderable"=>"false"];
    $data['qcIndent'][] = ["name"=>"Request Date"];
    $data['qcIndent'][] = ["name"=>"Request No"];
    $data['qcIndent'][] = ["name"=>"Category"];
    $data['qcIndent'][] = ["name"=>"Qty"];
    $data['qcIndent'][] = ["name"=>"Size"];
    $data['qcIndent'][] = ["name"=>"Make"];
    $data['qcIndent'][] = ["name"=>"Required Date"];

    /* QC Purchase Header */
    $data['qcPurchase'][] = ["name"=>"Action","style"=>"width:5%;"];
	$data['qcPurchase'][] = ["name"=>"#","style"=>"width:5%;"];
	$data['qcPurchase'][] = ["name"=>"Order No."];
    $data['qcPurchase'][] = ["name"=>"Order Date"];
    $data['qcPurchase'][] = ["name"=>"Supplier"];
    $data['qcPurchase'][] = ["name"=>"Category Name"];
    $data['qcPurchase'][] = ["name"=>"Rate"];
    $data['qcPurchase'][] = ["name"=>"Order Qty"];
    $data['qcPurchase'][] = ["name"=>"Received Qty"];
    $data['qcPurchase'][] = ["name"=>"Pending Qty"];
    $data['qcPurchase'][] = ["name"=>"Delivery Date"];
 
    /* Running Jobs Header */
    $data['runningJobs'][] = ["name"=>"Action","style"=>"width:5%;"];
    $data['runningJobs'][] = ["name"=>"#","style"=>"width:5%;"];
    $data['runningJobs'][] = ["name"=>"Prc No."];
    $data['runningJobs'][] = ["name"=>"Prc Date"];
    $data['runningJobs'][] = ["name"=>"Process"];
    $data['runningJobs'][] = ["name"=>"Item Name"];

    /* Line Inspection Header */
    $data['lineInspection'][] = ["name"=>"Action","style"=>"width:5%;"];
    $data['lineInspection'][] = ["name"=>"#","style"=>"width:5%;"];
    $data['lineInspection'][] = ["name"=>"Inspection Date"];
    $data['lineInspection'][] = ["name"=>"Inspection Time"];
    $data['lineInspection'][] = ["name"=>"Prc Date"];
    $data['lineInspection'][] = ["name"=>"Prc No."];
    $data['lineInspection'][] = ["name"=>"Process"];
    $data['lineInspection'][] = ["name"=>"Item Name"];
    $data['lineInspection'][] = ["name"=>"Operator"];
    $data['lineInspection'][] = ["name"=>"Machine Name"];
    $data['lineInspection'][] = ["name"=>"Sampling Qty"];
	
	/* Pending Fir Header */
    $data['pendingFir'][] = ["name"=>"Action","style"=>"width:5%;"];
    $data['pendingFir'][] = ["name"=>"#","style"=>"width:5%;"];
    $data['pendingFir'][] = ["name"=>"Prc No."];
    $data['pendingFir'][] = ["name"=>"Item Name"];
    $data['pendingFir'][] = ["name"=>"Qty"];

    /* Final Inspection Header */
    $data['finalInspection'][] = ["name"=>"Action","style"=>"width:5%;"];
    $data['finalInspection'][] = ["name"=>"#","style"=>"width:5%;"];
    $data['finalInspection'][] = ["name"=>"Inspection Date"];
    $data['finalInspection'][] = ["name"=>"Fir No"];
    $data['finalInspection'][] = ["name"=>"Prc No."];
    $data['finalInspection'][] = ["name"=>"Item Name"];
    $data['finalInspection'][] = ["name"=>"Ok Qty"];
    $data['finalInspection'][] = ["name"=>"Rej Qty"];
    $data['finalInspection'][] = ["name"=>"Total Qty"];
	
    return tableHeader($data[$page]);
}

/* Instrument Data */
function getInstrumentData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Instrument'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $inward=''; $reject=''; $editButton = '';
    if(empty($data->status)){
        $inwardParam = "{'postData':{'id' : ".$data->id.",'status' : '1'}, 'modal_id' : 'bs-right-lg-modal', 'form_id' : 'inwardGauge', 'title' : 'Inward Gauge', 'call_function':'inwardGauge', 'fnsave' : 'save'}";
        $inward = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Inward Gauge" flow="down" onclick="modalAction('.$inwardParam.');"><i class="mdi mdi-reply" ></i></a>';
    }elseif($data->status == 1){
        $reject = '<a href="javascript:void(0)" class="btn btn-dark rejectGauge permission-modify" data-id="'.$data->id.'" data-gauge_code="'.$data->item_code.'" datatip="Reject" flow="down"><i class="fa fa-close" ></i></a>';
    
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editGauge', 'title' : 'Update Gauge', 'fnsave' : 'save', 'call_function' : 'edit'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    }
    $selectBox = '<input type="checkbox" name="ref_id[]" id="ref_id_'.$data->sr_no.'" class="filled-in chk-col-success BulkInstChallan" value="'.$data->id.'"><label for="ref_id_'.$data->sr_no.'"></label>';
    
    $calPrintBtn = '<a class="btn btn-warning btn-edit permission-read" href="'.base_url('instrument/printCalHistoryCardData/'.$data->id).'" target="_blank" datatip="Calibration History Card" flow="down"><i class="fas fa-print" ></i></a>';
    $issuePrintBtn = '<a class="btn btn-dark btn-edit permission-read" href="'.base_url('instrument/printIssueHistoryCardData/'.$data->id).'" target="_blank" datatip="Issue History Card" flow="down"><i class="fas fa-print" ></i></a>';
     
    $deleteButton='';
    $action = getActionButton($issuePrintBtn.$calPrintBtn.$reject.$inward.$editButton.$deleteButton);    
    
    $lcd = (!empty($data->last_cal_date)) ? date('d-m-Y',strtotime($data->last_cal_date)) : '';
    $ncd = (!empty($data->next_cal_date)) ? date('d-m-Y',strtotime($data->next_cal_date. "-1 days")) : '';
    $pdate = (!empty($data->next_cal_date)) ? date('d-m-Y',strtotime($data->next_cal_date. "-".($data->cal_reminder+1)." days")) : '';
    if(!empty($ncd) AND (strtotime($ncd) <= strtotime(date('d-m-Y')))){$ncd = '<strong class="text-danger">'.$ncd.'</strong>';}
	if(!empty($pdate) AND (strtotime($pdate) <= strtotime(date('d-m-Y')))){$pdate = '<strong style="color:#ffbc34;">'.$pdate.'</strong>';}
	
    $itemCode = '<a href="'.base_url("instrument/calibrationData/".$data->id).'" datatip="View Details" flow="down">'.$data->item_code.'</a>';

	if(in_array($data->status,[1,5])){	
        return [$action,$data->sr_no,$selectBox,$itemCode,$data->item_name,$data->make_brand,$data->cal_required,$data->cal_freq,$data->location,$lcd,$ncd,$pdate,formatDate($data->created_at)];
	}elseif(in_array($data->status,[4])){
		return [$action,$data->sr_no,$itemCode,$data->item_name,$data->make_brand,$data->cal_required,$data->cal_freq,$data->location,formatDate($data->rejected_at,'d-m-Y h:i'),$data->reject_reason];
	}else{
	    return [$action,$data->sr_no,$itemCode,$data->item_name,$data->make_brand,$data->cal_required,$data->cal_freq,$data->location,$lcd,$ncd,$pdate,formatDate($data->created_at)];
	}
}

/* In-Challan Data */
function getQcChallanData($data){
    $returnBtn=''; $caliBtn=''; $edit=''; $delete='';
    
    if(empty($data->trans_status)){
        if(empty($data->receive_by)){
            $edit = '<a href="'.base_url($data->controller.'/edit/'.$data->challan_id).'" class="btn btn-success btn-edit permission-modify" datatip="Edit" flow="down"><i class="fa fa-edit"></i></a>';

            $deleteParam = "{'postData':{'id' : ".$data->challan_id."},'message' : 'Challan'}";
            $delete = '<a href="javascript:void(0)" class="btn btn-danger btn-delete permission-remove" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

            if($data->challan_type != 3){
                $rtnParam = "{'id' : ".$data->id.", 'modal_id' : 'bs-right-lg-modal', 'button':'close', 'form_id' : 'returnChallan', 'title' : 'Return Challan', 'fnedit' : 'returnChallan'}";
                $returnBtn = '<a href="javascript:void(0)" class="btn btn-info permission-write" onclick="returnQcChallan('.$rtnParam.');" datatip="Return" flow="down"><i class="mdi mdi-reply"></i></a>';
            }else{
                $calParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-lg-modal','button' : 'both', 'form_id' : 'calibration', 'title' : 'Calibration ', 'call_function' : 'getCalibration', 'fnsave' : 'saveCalibration', 'controller' : 'qcChallan'}";
                $caliBtn = '<a class="btn btn-info permission-write" href="javascript:void(0)" datatip="Calibration" flow="down" onclick="modalAction('.$calParam.');"><i class="fas fa-tachometer-alt"></i></a>';
            }
        }
    }

    $data->party_name = (!empty($data->party_name))? $data->party_name : 'IN-HOUSE';
    $data->challan_type = (($data->challan_type==1)? 'IN-House Issue' : (($data->challan_type==2) ? 'Vendor Issue':'Calibration'));
        
    $printBtn = '<a class="btn btn-info btn-edit" href="'.base_url('qcChallan/printChallan/'.$data->challan_id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';
    
    $action = getActionButton($printBtn.$caliBtn.$returnBtn.$edit.$delete);
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->challan_type,$data->handover_to,$data->party_name,$data->item_code,$data->item_name,$data->item_remark];
}

/* QC Purchase Table Data */
function getQCPRData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'QC Purchase Request'}";
    $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-md-modal', 'form_id' : 'editQCPR', 'title' : 'Update QC PR', 'fnsave' : 'save', 'call_function' : 'edit'}";
    
    $edit = "";$delete = "";
    
    if($data->status == 0):
        $edit = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        $delete = '<a href="javascript:void(0)" class="btn btn-danger btn-delete permission-remove" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;
	
	$action = getActionButton($edit.$delete);
		
    return [$action,$data->sr_no,$data->req_date,$data->req_number,$data->category_name,$data->qty,$data->size,$data->make,formatDate($data->delivery_date),$data->remark];
}

/* Qc Indent Data  */
function getQCIndentData($data){
    $rejParam = "{ 'postData' : {'id' : ".$data->id."}, 'modal_id' : 'bs-right-md-modal', 'fnsave' : 'closePurReq', 'title' : 'Reject QC PR'}";
    $rejectBtn="";
    if($data->status == 0):
        $rejectBtn = '<a class="btn btn-dark btn-edit permission-modify" href="javascript:void(0)" datatip="Reject QC PR" flow="down" onclick="confirmStore('.$rejParam.');"><i class="fa fa-close"></i></a>';
    endif;
    $action = getActionButton($rejectBtn);
    $selectBox = '<input type="checkbox" name="ref_id[]" id="ref_id_'.$data->sr_no.'" class="filled-in chk-col-success BulkQcRequest" value="'.$data->id.'"><label for="ref_id_'.$data->sr_no.'"></label>';
    
    return [$action,$data->sr_no,$selectBox,$data->req_date,$data->req_number,$data->category_name,$data->qty,$data->size,$data->make,formatDate($data->delivery_date)];
}

/* QC Purchase Table Data */
function getQCPurchaseData($data){
    $deleteParam = "{'postData':{'id' : ".$data->trans_main_id."},'message' : 'QC Purchase Order'}";
    $grn = "";$edit = "";$delete = ""; $receive = "";
    
    if($data->trans_status == 0): 
        $receive = '<a href="javascript:void(0)" class="btn btn-primary purchaseReceive permission-modify" data-po_id="'.$data->trans_main_id.'" datatip="Receive" flow="down"><i class="mdi mdi-reply" ></i></a>';

        $edit = '<a href="'.base_url($data->controller.'/edit/'.$data->trans_main_id).'" class="btn btn-success btn-edit permission-modify" datatip="Edit" flow="down"><i class="mdi mdi-square-edit-outline"></i></a>';

        $delete = '<a href="javascript:void(0)" class="btn btn-danger btn-delete permission-remove" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;
	
	$printBtn = '<a class="btn btn-info btn-edit permission-approve" href="'.base_url($data->controller.'/printQP/'.$data->trans_main_id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';
	
	$action = getActionButton($printBtn.$receive.$grn.$edit.$delete);	
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,'['.$data->category_code.'] '.$data->category_name,floatval($data->price),floatval($data->qty),floatval($data->receive_qty),floatval($data->pending_qty),formatDate($data->cod_date)];
}

/* Get Calibration Table Data */
function getCalibration($data){ 
    $caliParam = "{ 'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editCalibrationData', 'title' : 'Calibration', 'button' : 'both','call_function' : 'editCalibrationData', 'fnsave' : 'saveCalibrationData'}";
    $caliBtn = '<a class="btn btn-success btn-contact permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$caliParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    $download = ((!empty($data->certificate_file))?'<a href="'.base_url('assets/uploads/instrument/'.$data->certificate_file).'" target="_blank"><i class="fa fa-download"></i></a>':'');                                
    
    $action = getActionButton($caliBtn);  
    return[$action,$data->sr_no,$data->cal_agency_name,$data->cal_certi_no,$download,$data->remark];
}


/*Running Jobs Table Data */
function getRunningJobsData($data){ 
	$reportParam = "{'postData':{'prc_id' : ".$data->prc_id.",'process_id' : ".$data->process_id."},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'iirInsp', 'title' : 'Line Inspection','call_function':'AddLineInspection','fnsave':'saveLineInspection'}";
	$lineInspection = '<a href="javascript:void(0)" type="button" class="btn btn-info permission-modify" datatip="Line Inspection" flow="down" onclick="modalAction('.$reportParam.');"><i class="fa fa-file-alt"></i></a>';

    $action = getActionButton($lineInspection);  
    return[$action,$data->sr_no,$data->prc_number,formatDate($data->prc_date),$data->process_name,$data->item_name];
}

/*Line Inspection Table Data */
function getLineInspectionData($data){ 
	
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Line Inspection'}";
    $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-xl-modal', 'form_id' : 'editIPR', 'title' : 'Update Line Inspection', 'fnsave' : 'saveLineInspection', 'call_function' : 'editLineInspection'}";
   
    $edit = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    $delete = '<a href="javascript:void(0)" class="btn btn-danger btn-delete permission-remove" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $printBtn = '<a class="btn btn-info btn-edit" href="'.base_url('lineInspection/printLineInspection/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';
   
	$action = getActionButton($printBtn.$edit.$delete);
		
    return[$action,$data->sr_no,formatDate($data->insp_date),$data->insp_time,$data->prc_number,formatDate($data->prc_date),$data->process_name,$data->item_name,$data->emp_name,$data->machine_name,floatval($data->sampling_qty).' '.$data->uom]; 
}

/*Pending Fir Table Data */
function getPendingFirData($data){ 
	$reportParam = "{'postData':{'id' : ".$data->id.",'main_ref_id':".$data->main_ref_id."},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'firInsp', 'title' : 'Final Inspection','call_function':'AddFinalInspection','fnsave':'saveFinalInspection'}";
	$finalInspection = '<a href="javascript:void(0)" type="button" class="btn btn-info permission-modify" datatip="Final Inspection" flow="down" onclick="modalAction('.$reportParam.');"><i class="fa fa-file-alt"></i></a>';

    $action = getActionButton($finalInspection);  
    return[$action,$data->sr_no,$data->prc_number,$data->item_name,$data->qty];
}

/*Final Inspection Table Data */
function getFinalInspectionData($data){ 
	$totlQty = "";
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Final Inspection'}";
    $delete = '<a href="javascript:void(0)" class="btn btn-danger btn-delete permission-remove" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $printBtn = '<a class="btn btn-info btn-edit" href="'.base_url('finalInspection/printFinalInspection/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';
	$totlQty = ($data->ok_qty + $data->rej_found);
   
	$action = getActionButton($printBtn.$delete);
    return[$action,$data->sr_no,formatDate($data->insp_date),$data->trans_number,$data->prc_number,$data->item_name,$data->ok_qty,$data->rej_found,$totlQty];
}
?>