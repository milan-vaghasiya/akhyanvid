<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getSalesDtHeader($page){

    /* Sales Quotation Header */
    $data['salesQuotation'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['salesQuotation'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
	$data['salesQuotation'][] = ["name"=>"SQ. No."];
	$data['salesQuotation'][] = ["name"=>"SQ. Date"];
	$data['salesQuotation'][] = ["name"=>"Customer Name"];
	$data['salesQuotation'][] = ["name"=>"Project Type"];
	$data['salesQuotation'][] = ["name"=>"Project Description"];

    /* Project Master Header */
    $data['project'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>""];
    $data['project'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>""];
    $data['project'][] = ["name"=>"Project Name"];
    $data['project'][] = ["name"=>"Project Type"];
    $data['project'][] = ["name"=>"Customer Name"];
    $data['project'][] = ["name"=>"Project Loaction"];
    $data['project'][] = ["name"=>"Project Other info"];
	$data['project'][] = ["name"=>"SQ. No."];
	$data['project'][] = ["name"=>"AMC?"];
	
    /* Service Header */
	$data['service'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['service'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['service'][] = ["name"=>"Type"];
    $data['service'][] = ["name"=>"Service No."];
    $data['service'][] = ["name"=>"Service Date"];
    $data['service'][] = ["name"=>"Project"];
    $data['service'][] = ["name"=>"Customer"];
    $data['service'][] = ["name"=>"Problem"];
    $data['service'][] = ["name"=>"Technician Name"];
    $data['service'][] = ["name"=>"Start Date"];
    $data['service'][] = ["name"=>"Complete Date"];
    $data['service'][] = ["name"=>"Voice Notes"];

    /* Work Progress Header */
    $data['workProgress'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>""];
    $data['workProgress'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>""];
    $data['workProgress'][] = ["name"=>"Project Name"];
    $data['workProgress'][] = ["name"=>"Project Type"];
    $data['workProgress'][] = ["name"=>"Customer Name"];
    $data['workProgress'][] = ["name"=>"Project Loaction"];
    $data['workProgress'][] = ["name"=>"Project Other info"];
	$data['workProgress'][] = ["name"=>"SQ. No."];
	$data['workProgress'][] = ["name"=>"AMC?"];

    /* Customer Complaints Header */
	$data['customerComplaints'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['customerComplaints'][] = ["name"=>"#","class"=>"text-center no_filter noExport","sortable"=>FALSE]; 
    $data['customerComplaints'][] = ["name"=>"Date"];
    $data['customerComplaints'][] = ["name"=>"Project Name"];
    $data['customerComplaints'][] = ["name"=>"Remarks"];
    $data['customerComplaints'][] = ["name"=>"Voice Notes"];
	
    return tableHeader($data[$page]);
}

/* Sales Quotation Table data */
function getSalesQuotationData($data){
    $rejectButton = $editButton = $deleteButton = $confirmBtn = $projectBtn ="";  

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('salesQuotation/edit/'.encodeURL(['trans_number'=>$data->trans_number])).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'trans_number' : '".$data->trans_number."'},'message' : 'Sales Quotation'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
   
    $printBtn = '<a class="btn btn-success btn-edit " href="'.base_url('salesQuotation/printQuotation/'.encodeURL(['trans_number'=>$data->trans_number])).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';
    $printGstBtn = '<a class="btn btn-info btn-edit " href="'.base_url('salesQuotation/printQuotation/'.encodeURL(['trans_number'=>$data->trans_number,'pdf_type'=>'print_gst'])).'" target="_blank" datatip="Print GST" flow="down"><i class="fas fa-print" ></i></a>';

    $rejectParam = "{'postData':{'trans_number' : '".$data->trans_number."', 'trans_status' : 4,'msg':'Reject'},'fnsave':'changeQuotationStatus','message':'Are you sure want to Reject this Sales Quotation?'}";
    $rejectButton = '<a class="btn btn-dark permission-modify" href="javascript:void(0)" datatip="Reject" flow="down" onclick="confirmStore('.$rejectParam.');"><i class="fa fa-close"></i></a>';   
    
    $confirmParam = "{'postData':{'trans_number' : '".$data->trans_number."', 'trans_status' : 2,'msg':'Confirm'},'fnsave':'changeQuotationStatus','message':'Are you sure want to Confirm this Sales Quotation?'}";
    $confirmBtn = '<a class="btn btn-primary permission-modify" href="javascript:void(0)" datatip="Confirm" flow="down" onclick="confirmStore('.$confirmParam.');"><i class="fa fa-check"></i></a>';   

    if($data->trans_status == 2 && empty($data->project_id)):
        $projectParam = "{'postData' :{'trans_number' : '".$data->trans_number."','party_id' : '".$data->party_id."','project_type' : '".$data->project_type."'}, 'modal_id' : 'bs-right-md-modal', 'form_id' : 'createProject', 'title' : 'Create Project', 'call_function' : 'addProject', 'fnsave' : 'save','controller' : 'project'}";
        $projectBtn = '<a class="btn btn-primary permission-modify" href="javascript:void(0)" datatip="Create Project" flow="down" onclick="modalAction('.$projectParam.');"><i class="mdi mdi-briefcase"></i></a>';   
    endif;

    if($data->trans_status == 2):
        $confirmBtn = $editButton = $deleteButton = "";
    elseif(in_array($data->trans_status, [3,4])):
        $confirmBtn = $rejectButton = $editButton = $deleteButton = "";
    endif;

    $action = getActionButton($confirmBtn .$rejectButton .$projectBtn .$printBtn.$printGstBtn.$editButton.$deleteButton);


    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->project_type,$data->description];
}

/* Project Table data */
function getProjectData($data){
    $editButton = $deleteButton = $startBtn =  $onHoldBtn = $reOpenBtn = $handoverButton = "";

    //$total_service = $data->total_service."/".$data->no_of_service;

    if($data->trans_status == 1):
        $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-md-modal', 'form_id' : 'editProject', 'title' : 'Update Project'}";
        $editButton = '<a class="btn btn-success btn-sm btn-edit permission-modify" href="javascript:void(0)" onclick="modalAction('.$editParam.');" datatip="Edit" flow="down"><i class="mdi mdi-square-edit-outline"></i></a>';
        
        $deleteParam = "{'postData':{'id': ".$data->id."},'message' : 'Project'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

        $startParam = "{'postData':{'id' : ".$data->id.", 'trans_status' : 2,'msg' :'Start' ,'start_date':'". date('Y-m-d')."'},'fnsave':'changeProjectStatus','message':'Are you sure want to Start this Project?'}";
        $startBtn = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Start" flow="down" onclick="confirmStore('.$startParam.');"><i class="mdi mdi-play"></i></a>';

    elseif($data->trans_status == 2):
    
		$onHoldParam = "{'postData':{'id' : ".$data->id.", 'trans_status' : 3 ,'msg' :'On Hold' ,'start_date':'".$data->start_date."'},'fnsave':'changeProjectStatus','message':'Are you sure want to On Hold this Project?'}";
        $onHoldBtn = '<a class="btn btn-primary permission-modify" href="javascript:void(0)" datatip="On Hold" flow="down" onclick="confirmStore('.$onHoldParam.');"><i class="mdi mdi-pause"></i></a>';
    
		if($data->handover_date == NULL):
            $handoverParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-md-modal', 'form_id' : 'addHandover', 'title' : 'Add Handover Certificate','call_function':'addHandoverCertificate', 'fnsave':'saveHandover'}";
            $handoverButton = '<a class="btn btn-primary btn-sm btn-edit permission-modify" href="javascript:void(0)" onclick="modalAction('.$handoverParam.');" datatip="Handover Certificate" flow="down"><i class="mdi mdi-certificate"></i></a>';
        endif;
	
	elseif($data->trans_status == 3):
        $reOpenParam = "{'postData':{'id' : ".$data->id.", 'trans_status' : 2 ,'msg' :'Re Open' ,'start_date':'".$data->start_date."'},'fnsave':'changeProjectStatus','message':'Are you sure want to Re Open this Project?'}";
        $reOpenBtn = '<a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Re Open" flow="down" onclick="confirmStore('.$reOpenParam.');"><i class="mdi mdi-replay"></i></a>';
    endif;

    $paymentParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-lg-modal', 'form_id' : 'addPayment', 'title' : 'Add Payment','call_function':'addPayment', 'fnsave':'savePayment','button':'close'}";
    $paymentButton = '<a class="btn btn-info btn-sm btn-edit permission-modify" href="javascript:void(0)" onclick="modalAction('.$paymentParam.');" datatip="Payment" flow="down"><i class="mdi mdi-wallet"></i></a>';
       
    $ledgerPrint = '<a class="btn btn-success btn-edit " href="'.base_url('project/printLedger/'.$data->id).'" target="_blank" datatip="Print Ledger" flow="down"><i class="fas fa-print" ></i></a>';


    $projectDetail = '<a href="' . base_url('project/projectDetail/'.encodeURL($data->id)) . '" datatip="Project Detail" flow="down"><b>'.$data->project_name.'</b></a>';
    $action = getActionButton($startBtn.$onHoldBtn.$reOpenBtn.$paymentButton.$ledgerPrint.$handoverButton.$editButton.$deleteButton);

    return [$action,$data->sr_no,$projectDetail,($data->project_type),$data->party_name,$data->location,$data->other_info,$data->sq_no,$data->amc];
}

/* Service Table Data */
function getServiceData($data){ 
    $approveBtn = $rejectBtn = $shortClose = $editButton = $deleteButton = $acceptBtn = $assignTecBtn = $startBtn = $completeBtn = $reopenBtn = $printBtn = $completeDetailBtn = "";
    $CI = & get_instance();
	$userRole = $CI->session->userdata('role');
    if($data->status == 1):
        $approveParam = "{'postData':{'id' : ".$data->id.",'is_approve':1,'status' : 2,'msg':'Approved'},'fnsave':'approveService','message':'Are you sure want to Approve this Service?'}";
        $approveBtn = '<a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Approve Service" flow="down" onclick="confirmStore('.$approveParam.');"><i class="mdi mdi-check"></i></a>'; 

        $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-lg-modal', 'form_id' : 'edit', 'title' : 'Update Service'}";
        $editButton = '<a class="btn btn-success btn-sm btn-edit permission-modify" href="javascript:void(0)" onclick="modalAction('.$editParam.');" datatip="Edit" flow="down"><i class="mdi mdi-square-edit-outline"></i></a>';
    
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Service'}";
        $deleteButton = '<a href="javascript:void(0)" class="btn btn-danger btn-delete permission-remove" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;
 
    if($data->status == 2):
        if(in_array($userRole,[-1,1])){
            $rejectParam = "{'postData':{'id' : ".$data->id.",'is_approve' : 0, 'status' : 1},'fnsave':'approveService','message':'Are you sure want to Reject this Service?'}";
            $rejectBtn = '<a class="btn btn-dark permission-modify" href="javascript:void(0)" datatip="Un-Approve" flow="down" onclick="confirmStore('.$rejectParam.');"><i class="mdi mdi-close"></i></a>';

            if($data->technician_id == 0){
                $assignParam = "{'postData' :{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'assignTechnician', 'title' : 'Assign Technician', 'call_function' : 'assignTechnician', 'fnsave' : 'saveAssignTechnician'}";
                $assignTecBtn = '<a class="btn btn-info btn-edit permission-modify" href="javascript:void(0)" datatip="Assign Technician" flow="down" onclick="modalAction('.$assignParam.');"><i class="fa fa-address-book"></i></a>';
            }
            $shortCloseParam = "{'postData':{'id' : ".$data->id.", 'status' : 6},'fnsave':'changeOrderStatus','message':'Are you sure want to Short Close this Service?'}";
            $shortClose = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Short Close" flow="down" onclick="confirmStore('.$shortCloseParam.');"><i class="mdi mdi-close-circle-outline"></i></a>';

        }
    endif; 
    if($data->status == 3){
        if(in_array($userRole,[4])){
            $startParam = "{'postData':{'id' : ".$data->id.",'technician_id' : ".$data->loginId.",'status' : 4,'start_date' : '".date('Y-m-d H:i:s')."'},'fnsave':'changeOrderStatus','message':'Are you sure want to Start this Service?'}";
            $startBtn = '<a class="btn btn-warning permission-modify" href="javascript:void(0)" datatip="Start" flow="down" onclick="confirmStore('.$startParam.');"><i class="mdi mdi-play"></i></a>';
        }
    }

    if($data->status == 4 && $data->technician_id == $data->loginId):
        $shortCloseParam = "{'postData':{'id' : ".$data->id.", 'status' : 6},'fnsave':'changeOrderStatus','message':'Are you sure want to Short Close this Service?'}";
        $shortClose = '<a class="btn btn-instagram permission-modify" href="javascript:void(0)" datatip="Short Close" flow="down" onclick="confirmStore('.$shortCloseParam.');"><i class="mdi mdi-close-circle-outline"></i></a>';
        
        $completeParam = "{'postData' :{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'completeService', 'title' : 'Complete', 'call_function' : 'completeService', 'fnsave' : 'saveCompleteService'}";
        $completeBtn = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Complete" flow="down" onclick="modalAction('.$completeParam.');"><i class="mdi mdi-check"></i></a>';
    endif;

    if($data->status == 5):
        $completeDetailParam = "{'postData' :{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'completeService', 'title' : 'Complete Service Details', 'call_function' : 'completeServiceDetail','button' : 'close'}";
        $completeDetailBtn = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="Complete Service Details" flow="down" onclick="modalAction('.$completeDetailParam.');"><i class="fa fa-list"></i></a>';
    endif;

    if($data->status == 6 && $data->technician_id == $data->loginId):
        $reopenParam = "{'postData':{'id' : ".$data->id.", 'status' : 2},'fnsave':'changeOrderStatus','message' : 'Are you sure want to Reopen this Service ?'}";
        $reopenBtn = '<a class="btn btn-info permission-modify " href="javascript:void(0)" datatip="Reopen" flow="down" onclick="confirmStore('.$reopenParam.')"><i class="mdi mdi-restart"></i></a>';
    endif;


    $download = '';
    if(!empty($data->bfr_images)) { 
        $downloadParam = "{'postData' :{'id' : ".$data->id." ,'bfr_images': '".$data->bfr_images."'}, 'modal_id' : 'modal-md', 'form_id' : 'downloadForm', 'title' : 'Service File', 'call_function' : 'serviceViewFile', 'button' : 'close'}";
        $download = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="download" flow="down" onclick="modalAction('.$downloadParam.');"><i class="fa fa-download" ></i></a>';
    }

    $vnFile = '';
    if(!empty($data->voice_notes)):
        $vnPath = base_url('assets/uploads/voice_notes/'.$data->voice_notes);
        $vnFile='<audio controls style="height: 35px;width:250px;">
                    <source src="'.$vnPath.'" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>';
    endif;

    $action = getActionButton($download.$completeDetailBtn.$printBtn.$approveBtn.$rejectBtn.$shortClose.$editButton.$deleteButton.$acceptBtn.$assignTecBtn.$startBtn.$completeBtn.$reopenBtn);

	return [$action,$data->sr_no,$data->type,$data->trans_number,formatDate($data->trans_date,'d-m-Y H:i:s'),$data->project_name,$data->party_name,$data->problem,$data->emp_name,formatDate($data->start_date,'d-m-Y H:i:s'),formatDate($data->complete_date,'d-m-Y H:i:s'),$vnFile];//13-10-25
}

/* Work Progress Table data */
function getWorkProgressData($data){

    $updateParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editWorkProgress', 'title' : 'Update Work Progress' ,'call_function':'updateWorkProgress'}";
    $updateButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" onclick="modalAction('.$updateParam.');" datatip="Update Work" flow="down"><i class="mdi mdi-update"></i></a>';

    $action = getActionButton($updateButton);

    return [$action,$data->sr_no,$data->project_name,($data->project_type),$data->party_name,$data->location,$data->other_info,$data->sq_no,$data->amc];
}

/* Customer Complaints Table Data*/
function getCustomerComplaintsData($data){  
    
    $editButton="";$deleteButton="";$solution="";  $download =''; $serviceBtn="";
    if(($data->status == 1)){
        $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'customerComplaints', 'title' : 'Update Customer Complaints','call_function':'edit'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

        $deleteParam = "{'postData':{'id' : ".$data->id." ,'complaint_file': '".$data->complaint_file."'},'message' : 'Delete Customer Complaints'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
        
        $solParam = "{'postData' :{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'solutionForm', 'title' : 'Solution', 'call_function' : 'complaintSolution', 'fnsave' : 'save'}";
        $solution = '<a class="btn btn-info btn-edit permission-modify" href="javascript:void(0)" datatip="Solution" flow="down" onclick="modalAction('.$solParam.');"><i class="fas fa-check" ></i></a>';

        $serviceParam = "{'postData' : {'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'call_function':'addServiceRequest', 'form_id' : 'addService', 'title' : 'Add Service','fnsave':'saveServiceReq'}";
        $serviceBtn = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Service Request" flow="down" onclick="modalAction('.$serviceParam.');"><i class="fa fa-bell"></i></a>';			
    }
    
    if(!empty($data->complaint_file)) { 
        $downloadParam = "{'postData' :{'id' : ".$data->id." ,'complaint_file': '".$data->complaint_file."'}, 'modal_id' : 'modal-md', 'form_id' : 'downloadForm', 'title' : 'Complaint File', 'call_function' : 'complaintViewFile', 'button' : 'close'}";
        $download = '<a class="btn btn-primary btn-edit permission-modify" href="javascript:void(0)" datatip="download" flow="down" onclick="modalAction('.$downloadParam.');"><i class="fa fa-download" ></i></a>';
    }

	$vnFile = '';
    if(!empty($data->voice_note)):
        $vnPath = base_url('assets/uploads/voice_notes/'.$data->voice_note);
        $vnFile='<audio controls style="height: 35px;width:250px;">
                    <source src="'.$vnPath.'" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>'; 
    endif;
	
    $action = getActionButton($download. $serviceBtn.$solution.$editButton.$deleteButton);
	return [$action,$data->sr_no,formatDate($data->date),$data->project_name,$data->remark,$vnFile];
}

?>