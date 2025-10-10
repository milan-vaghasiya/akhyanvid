<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getConfigDtHeader($page){
    /* terms header */
    $data['terms'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['terms'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['terms'][] = ["name"=>"Title"];
    $data['terms'][] = ["name"=>"Type"];
    $data['terms'][] = ["name"=>"Conditions"];
	
	/* Select Option Header */
    $data['selectOption'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['selectOption'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['selectOption'][] = ["name"=>"Option"];
	$data['selectOption'][] = ["name"=>"Remark"];
	
    /* Work Instructions Header */
    $data['workInstructions'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['workInstructions'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['workInstructions'][] = ["name"=>"Work Title"];
    $data['workInstructions'][] = ["name"=>"Description"];
    $data['workInstructions'][] = ["name"=>"Notes"];


    return tableHeader($data[$page]);
}

/* Terms Table Data */
function getTermsData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Terms'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editTerms', 'title' : 'Update Terms','call_function':'edit','txt_editor' : 'conditions'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->title,$data->type,$data->conditions];
}

/* Select Option Table Data */
function getSelectOptionData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Option'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editOption', 'title' : 'Update Option'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->label,$data->remark];
}

/* Work Instructions Table Data */
function getWorkInstructionsData($data){
    $editParam = "{'postData':{'id' : '".$data->id."'},'modal_id' : 'bs-right-md-modal', 'form_id' : 'addWorkInstructions', 'title' : 'Update Work Instructions', 'fnsave' : 'save'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';

	
	$action = getActionButton($editButton);
    return [$action,$data->sr_no,$data->work_title,$data->description,$data->notes];
}


?>