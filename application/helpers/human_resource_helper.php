<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getHrDtHeader($page){
	
    /* Employee Header */
	$data['employees'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['employees'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['employees'][] = ["name"=>"Emp Code"];
    $data['employees'][] = ["name"=>"Employee Name"];
    $data['employees'][] = ["name"=>"Mobile No."];
    $data['employees'][] = ["name"=>"Role"];

    return tableHeader($data[$page]);
}

/* Employee Table Data */
function getEmployeeData($data){
    
    $activeButton = ''; $editButton = ''; $deleteButton = ''; $resetPsw = ''; 
    
    if($data->is_active == 1):
        $activeParam = "{'postData':{'id' : ".$data->id.", 'is_active' : 0},'fnsave':'activeInactive','message':'Are you sure want to De-Active this Employee?'}";
        $activeButton = '<a class="btn btn-youtube permission-modify" href="javascript:void(0)" datatip="De-Active" flow="down" onclick="confirmStore('.$activeParam.');"><i class="fa fa-ban"></i></a>';    

		$editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editEmployee', 'title' : 'Update Employee','call_function':'edit'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        
		$deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Employee'}";
		$deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
        
	else:
        $activeParam = "{'postData':{'id' : ".$data->id.", 'is_active' : 1},'fnsave':'activeInactive','message':'Are you sure want to Active this Employee?'}";
        $activeButton = '<a class="btn btn-success permission-remove" href="javascript:void(0)" datatip="Active" flow="down" onclick="confirmStore('.$activeParam.');"><i class="fa fa-check"></i></a>';
    endif;
    
    $CI = & get_instance();
    $userRole = $CI->session->userdata('role');
    if(in_array($userRole,[-1,1])):
        $resetParam = "{'postData':{'id' : ".$data->id."},'fnsave':'resetPassword','message':'Are you sure want to Change ".$data->emp_name." Password?'}";
        $resetPsw='<a class="btn btn-danger" href="javascript:void(0)" onclick="confirmStore('.$resetParam.');" datatip="Reset Password" flow="down"><i class="fa fa-key"></i></a>';
    endif;
    
    $action = getActionButton($resetPsw.$activeButton.$editButton.$deleteButton);
    return [$action,$data->sr_no,$data->emp_code,$data->emp_name,$data->emp_mobile_no,$data->emp_role];
}


?>