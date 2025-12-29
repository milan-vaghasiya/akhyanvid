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

    /* Gate Inward Pending/Compeleted Tab Header */
    $data['gateInward'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['gateInward'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['gateInward'][] = ["name"=> "GRN No.", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "GRN Date", "textAlign" => "center"];
    $data['gateInward'][] = ["name" => "Supplier"];
    $data['gateInward'][] = ["name" => "Item Name"];
    $data['gateInward'][] = ["name" => "Serial No"];
    $data['gateInward'][] = ["name" => "Qty"]; 

    /* Material Issue Table Header */
    $data['materialIssue'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['materialIssue'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['materialIssue'][] = ["name" => "Issue No"];
    $data['materialIssue'][] = ["name" => "Issue Date"]; 
    $data['materialIssue'][] = ["name" => "Item"];
    $data['materialIssue'][] = ["name" => "Issue Qty"];
    $data['materialIssue'][] = ["name" => "Return Qty"];
    $data['materialIssue'][] = ["name" => "Issue By"];
    $data['materialIssue'][] = ["name" => "Remark"];
	
	/*Opening Stock Table Header */
    $data['openingStock'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['openingStock'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['openingStock'][] = ["name" => "Item"];
    $data['openingStock'][] = ["name" => "Qty"];
    $data['openingStock'][] = ["name" => "Created By"];
    $data['openingStock'][] = ["name" => "Created At"];
  
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

/* GateInward Data Data  */
function getGateInwardData($data){
    $deleteButton = $editButton = '';
    $iirTagPrint = '<a href="'.base_url('gateInward/ir_print/'.$data->id).'" type="button" class="btn btn-primary" datatip="Pending QC Tag" flow="down" target="_blank"><i class="fas fa-print"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id.", 'grn_id' : '".$data->grn_id."'},'message' : 'Gate Inward'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $editParam = "{'postData':{'id' : ".$data->id.", 'grn_id' : '".$data->grn_id."'}, 'modal_id' : 'bs-right-xl-modal', 'form_id' : 'editGateInward', 'title' : 'Update Gate Inward', 'call_function' : 'edit', 'fnsave' : 'updateGRN'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

    $printBtn = '<a class="btn btn-dribbble" href="'.base_url('gateInward/printGateInward/'.$data->grn_id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print"></i></a>';

    $action = getActionButton($iirTagPrint.$printBtn.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->party_name,$data->item_name,$data->batch_no,$data->qty];
}

/* Material Issue Table Data */
function getMaterialIssueData($data){    
    $deleteButton = $returnButton = '';
    if($data->return_qty <= 0){
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Material Issue','fndelete':'deleteIssuedItem'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';    
    }
        
    if($data->is_return == 1){
        $returnParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'materialReturn', 'title' : 'Material Return ( Issue Qty : ".$data->issue_qty." )','call_function':'materialReturn','fnsave':'saveMaterialReturn'}";
        $returnButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Material Return" flow="down" onclick="modalAction('.$returnParam.');"><i class="mdi mdi-reply"></i></a>';
    }

	$action = getActionButton($returnButton.$deleteButton);
	return [$action,$data->sr_no,$data->issue_number,formatDate($data->issue_date),$data->item_name,floatval($data->issue_qty), $data->return_qty,$data->issued_to_name,$data->remark];
}

function getOpeningStockData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Stock','fndelete':'deleteOpeningStock'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    
    $action = getActionButton($deleteButton);

    return [$action,$data->sr_no,$data->item_name,floatval($data->qty),$data->created_name,formatDate($data->created_at)];
} 
?>