<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getMasterDtHeader($page){
    /* Lead Header */
    $data['lead'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>""];
	$data['lead'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>""]; 
	$data['lead'][] = ["name"=>"Party Name"];
    $data['lead'][] = ["name"=>"Contact Person"];
    $data['lead'][] = ["name"=>"Contact No."];
    $data['lead'][] = ["name"=>"Business Type"];
    $data['lead'][] = ["name"=>"Sales Executive"];

    /* Supplier Header */
    $data['supplier'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>""];
	$data['supplier'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>""]; 
	$data['supplier'][] = ["name"=>"Party Name"];
    $data['supplier'][] = ["name"=>"Contact Person"];
    $data['supplier'][] = ["name"=>"Contact No."];
    $data['supplier'][] = ["name"=>"Business Type"];
    $data['supplier'][] = ["name"=>"Sales Executive"];

    /* Item Category Header */
    $data['itemCategory'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>""];
	$data['itemCategory'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>""];
    $data['itemCategory'][] = ["name"=>"Category Name"];
    $data['itemCategory'][] = ["name"=>"Parent Category"];
    $data['itemCategory'][] = ["name"=>"Is Final ?"];
    $data['itemCategory'][] = ["name"=>"Remark"];

    /* Finish Goods Header */
	$data['finish_goods'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>""];
	$data['finish_goods'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>""];
    $data['finish_goods'][] = ["name"=>"Item Code"];
    $data['finish_goods'][] = ["name"=>"Category Name"];
    $data['finish_goods'][] = ["name"=>"Make/Brand Name"];
    $data['finish_goods'][] = ["name"=>"Full Item Name"];
    $data['finish_goods'][] = ["name"=>"Unit"];	

    return tableHeader($data[$page]);
}


function getPartyData($data){
    $CI = & get_instance();
	$userRole = $CI->session->userdata('role');

    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'edit".$data->party_category_name."', 'title' : 'Update ".$data->party_category_name."','call_function':'edit'}";
    $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : '".$data->party_category_name."'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);
	$responseData = [$action,$data->sr_no,$data->party_name,$data->contact_person,$data->party_phone,$data->business_type,$data->executive_name];
    return $responseData;
}

function getItemCategoryData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Item Category'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editItemCategory', 'title' : 'Update Item Category','call_function':'edit'}";

    $editButton=''; $deleteButton='';
	if(!empty($data->ref_id)):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;


    if($data->final_category == 0):
        $data->category_name = '<a href="' . base_url("itemCategory/list/" . $data->id) . '">' . $data->category_name . '</a>';
    else:
        $data->category_name = $data->category_name;
    endif;

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->category_name,$data->parent_category_name,$data->is_final_text,$data->remark];
}

function getProductData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : '".$data->item_type_text."'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editItem', 'title' : 'Update ".$data->item_type_text."','call_function':'edit'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->item_code,$data->category_name,$data->make_brand,$data->item_name,$data->uom];
}

// Lead Data
function getLeadData($data){
    $qualifiedBtn = ""; $lostStageBtn =""; $reOpenBtn="";

    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'addLead', 'title' : 'Update Lead','call_function':'edit'}";
    $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Lead'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $reminderParam = "{'postData': {'party_id' : ".$data->id.",'lead_stage':2}, 'modal_id' : 'modal-md', 'form_id' : 'reminder', 'title' : 'Reminder', 'call_function' : 'addReminder', 'fnsave' : 'saveReminder','res_function' : 'resReminder', 'button' : 'both'}";
    $reminderBtn = '<a class="btn btn-info" href="javascript:void(0)" datatip="Reminder" flow="down" onclick="modalAction('.$reminderParam.');"><i class="far fa-bell"></i></a>';
  
    $partyActivityParam = "{'postData':{'party_id':".$data->id.",'form_id':'activityModal'}, 'modal_id' : 'bs-right-md-modal', 'call_function' : 'partyActivity', 'fnsave' : 'savePartyActivity', 'button' : 'close', 'title' : '".$data->party_name."'}";
    $activityBtn = '<a href="javascript:void(0)" datatip="Activity Detail" flow="down" data-bs-backdrop="static" onclick="modalAction('.$partyActivityParam.');">'.$data->party_name.'</a>';

    if($data->party_type != 1) {
        if($data->party_type == 2 ){
            $qualifiedParam =  "{'postData':{'id':".$data->id.",'lead_stage':'3'},'fnsave':'changeLeadStages','message':'Are you sure want to Qualified Lead Stage ?'}";
            $qualifiedBtn = '<a class="btn btn-success permission-modify" href="javascript:void(0)" onclick="confirmStore('.$qualifiedParam.');" datatip="Qualified Lead" flow="down"><i class="fas fa-check"></i></a>';

            $lostleadParam =  "{'postData':{'id':".$data->id.",'lead_stage':'4'},'fnsave':'changeLeadStages','message':'Are you sure want to Lost Lead Stage ?'}";
            $lostStageBtn = '<a class="btn btn-dark permission-modify" href="javascript:void(0)" onclick="confirmStore('.$lostleadParam.');" datatip="Lost Lead" flow="down"><i class="mdi mdi-emoticon-sad"></i></a>';
      
        }elseif($data->party_type == 3){
            $lostleadParam =  "{'postData':{'id':".$data->id.",'lead_stage':'4'},'fnsave':'changeLeadStages','message':'Are you sure want to Lost Lead Stage ?'}";
            $lostStageBtn = '<a class="btn btn-dark permission-modify" href="javascript:void(0)" onclick="confirmStore('.$lostleadParam.');" datatip="Lost Lead" flow="down"><i class="mdi mdi-emoticon-sad"></i></a>';
        }
        elseif($data->party_type == 4){
            $reOpenParam =  "{'postData':{'id':".$data->id.",'lead_stage':'8'},'fnsave':'changeLeadStages','message':'Are you sure want to Re-Open Lead ?'}";
            $reOpenBtn = '<a class="btn btn-info permission-modify" href="javascript:void(0)" onclick="confirmStore('.$reOpenParam.');" datatip="Re-open Lead" flow="down"><i class="mdi mdi-close-circle"></i></a>';
        }
    }
   

    $action = getActionButton($lostStageBtn.$qualifiedBtn.$reOpenBtn.$reminderBtn.$editButton.$deleteButton);
    $responseData = [$action,$data->sr_no,$activityBtn,$data->contact_person,$data->party_phone,$data->business_type,$data->executive_name];
    return $responseData;
}


?>