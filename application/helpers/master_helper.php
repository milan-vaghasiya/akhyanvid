<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getMasterDtHeader($page){
    /* Customer Header */
    $data['customer'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['customer'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"]; 
    $data['customer'][] = ["name"=>"Company Code"];
	$data['customer'][] = ["name"=>"Company Name"];
	$data['customer'][] = ["name"=>"Contact Person"];
    $data['customer'][] = ["name"=>"Contact No."];
    $data['customer'][] = ["name"=>"GSTIN"];
    //$data['customer'][] = ["name"=>"Address"];

    /* Supplier Header */
    $data['supplier'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['supplier'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"]; 
    $data['supplier'][] = ["name"=>"Company Code"];
	$data['supplier'][] = ["name"=>"Company Name"];
	$data['supplier'][] = ["name"=>"Contact Person"];
    $data['supplier'][] = ["name"=>"Contact No."];
    $data['supplier'][] = ["name"=>"GSTIN"];
    //$data['supplier'][] = ["name"=>"Address"];
    
    /* Vendor Header */
    $data['vendor'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['vendor'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"]; 
    $data['vendor'][] = ["name"=>"Company Code"];
	$data['vendor'][] = ["name"=>"Company Name"];
	$data['vendor'][] = ["name"=>"Contact Person"];
    $data['vendor'][] = ["name"=>"Contact No."];
    $data['vendor'][] = ["name"=>"GSTIN"];
    //$data['vendor'][] = ["name"=>"Address"];

    /* Ledger Header */
    $data['ledger'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['ledger'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"];
    $data['ledger'][] = ["name"=>"Ledger Name"];
    $data['ledger'][] = ["name"=>"Group Name"];
    $data['ledger'][] = ["name"=>"Op. Balance"];
    $data['ledger'][] = ["name"=>"Cl. Balance"];

    /* Item Category Header */
    $data['itemCategory'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['itemCategory'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"];
    $data['itemCategory'][] = ["name"=>"Category Name"];
    $data['itemCategory'][] = ["name"=>"Parent Category"];
    $data['itemCategory'][] = ["name"=>"Is Final ?"];
    $data['itemCategory'][] = ["name"=>"Remark"];

    /* Finish Goods Header */
    $data['finish_goods'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['finish_goods'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"];
    $data['finish_goods'][] = ["name"=>"Item Code"];
    $data['finish_goods'][] = ["name"=>"Item Name"];
    $data['finish_goods'][] = ["name"=>"Category"];
    $data['finish_goods'][] = ["name"=>"Unit"];
    //$data['finish_goods'][] = ["name"=>"Price"];
    $data['finish_goods'][] = ["name"=>"HSN Code"];
    $data['finish_goods'][] = ["name"=>"GST (%)"];

    /* Row Material Header */
    $data['raw_material'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['raw_material'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"];
    $data['raw_material'][] = ["name"=>"Item Code"];
    $data['raw_material'][] = ["name"=>"Item Name"];
    $data['raw_material'][] = ["name"=>"Category"];
    $data['raw_material'][] = ["name"=>"Unit"];
    //$data['raw_material'][] = ["name"=>"Price"];
    $data['raw_material'][] = ["name"=>"HSN Code"];
    $data['raw_material'][] = ["name"=>"GST (%)"];
	
	/* Semi Finish Header */
    $data['semi_finish'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['semi_finish'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"];
    $data['semi_finish'][] = ["name"=>"Item Code"];
    $data['semi_finish'][] = ["name"=>"Item Name"];
    $data['semi_finish'][] = ["name"=>"Category"];
    $data['semi_finish'][] = ["name"=>"Unit"];
    //$data['semi_finish'][] = ["name"=>"Price"];
    $data['semi_finish'][] = ["name"=>"HSN Code"];
    $data['semi_finish'][] = ["name"=>"GST (%)"];

    /* Consumable Header */
    $data['consumable'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['consumable'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"];
    $data['consumable'][] = ["name"=>"Item Code"];
    $data['consumable'][] = ["name"=>"Item Name"];
    $data['consumable'][] = ["name"=>"Category"];
    $data['consumable'][] = ["name"=>"Unit"];
    //$data['consumable'][] = ["name"=>"Price"];
    $data['consumable'][] = ["name"=>"HSN Code"];
    $data['consumable'][] = ["name"=>"GST (%)"];
    
	/* Machine Master Header */
    $data['machineries'][] = ["name"=>"Action","class"=>"no_filter noExport","sortable"=>FALSE,"textAlign"=>"center"];
	$data['machineries'][] = ["name"=>"#","class"=>"no_filter","sortable"=>FALSE,"style"=>"width:3%;","textAlign"=>"center"];
    $data['machineries'][] = ["name"=>"Machine Code"];
    $data['machineries'][] = ["name"=>"Machine Name"];
    $data['machineries'][] = ["name"=>"Make/Brand"];
    $data['machineries'][] = ["name"=>"Serial No."];
    $data['machineries'][] = ["name"=>"Capacity"];
    $data['machineries'][] = ["name"=>"Installation Year"];

	/* Countries Table Header */
    $data['country'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['country'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"];
    $data['country'][] = ["name"=>"Country"];

    /* states Table Header */
    $data['states'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['states'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"];
    $data['states'][] = ["name"=>"States"];

    /* cities Table Header */
    $data['cities'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['cities'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"];
    $data['cities'][] = ["name"=>"Country"];
	$data['cities'][] = ["name"=>"States"];
	$data['cities'][] = ["name"=>"Cities"];

    /** Custom Field Data */
    $data['customField'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['customField'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"];
    $data['customField'][] = ["name"=>"Field"];
    $data['customField'][] = ["name"=>"Field Type"];

    /* Custom Option Header */
    $data['customOption'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>"width:5%;"];
	$data['customOption'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>"width:5%;"];
    $data['customOption'][] = ["name"=>"Type"];
    $data['customOption'][] = ["name"=>"Title"];

    /* Item Price Structure Header */
    $data['itemPriceStructure'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE,"style"=>""];
	$data['itemPriceStructure'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE,"style"=>""];
    $data['itemPriceStructure'][] = ["name"=>"Structure Name"];
    $data['itemPriceStructure'][] = ["name"=>"Item Namne"];
    $data['itemPriceStructure'][] = ["name"=>"Category Name"];
    $data['itemPriceStructure'][] = ["name"=>"GST (%)"];
    $data['itemPriceStructure'][] = ["name"=>"MRP"];
    $data['itemPriceStructure'][] = ["name"=>"Price"];
    $data['itemPriceStructure'][] = ["name"=>"Dealer MRP"];
    $data['itemPriceStructure'][] = ["name"=>"Dealer Price"];
    $data['itemPriceStructure'][] = ["name"=>"Retail MRP"];
    $data['itemPriceStructure'][] = ["name"=>"Retail Price"];

    
	/* Packing Material Header */
    $data['packing_material'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['packing_material'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['packing_material'][] = ["name"=>"Item Code"];
    $data['packing_material'][] = ["name"=>"Item Name"];
    $data['packing_material'][] = ["name"=>"Category"];
    $data['packing_material'][] = ["name"=>"UOM"];
    $data['packing_material'][] = ["name"=>"HSN Code"];
    $data['packing_material'][] = ["name"=>"GST (%)"];
    // $data['packing_material'][] = ["name"=>"Created By/At"];
    // $data['packing_material'][] = ["name"=>"Updated By/At"];

    return tableHeader($data[$page]);
}

function getPartyData($data){
    
	$editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : '".(($data->table_status!=4)?"bs-right-lg-modal":"bs-right-md-modal")."', 'form_id' : 'edit".$data->party_category_name."', 'title' : 'Update ".$data->party_category_name."','call_function':'edit'}";
    $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    
	$deleteParam = "{'postData':{'id' : ".$data->id."},'message' : '".$data->party_category_name."'}";
	$deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $gstJsonBtn="";$contactBtn="";
    /* if($data->party_category == 1):
        //$gstParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-lg','button' : 'close', 'form_id' : 'gstDetail', 'title' : 'GST Detail', 'fnedit' : 'gstDetail', 'fnsave' : 'saveGstDetail','js_store_fn' : 'customStore'}";
        //$gstJsonBtn = '<a class="btn btn-warning btn-contact permission-modify" href="javascript:void(0)" datatip="GST Detail" flow="down" onclick="modalAction('.$gstParam.');"><i class="fab fa-google"></i></a>';

        //$contactParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-lg','button' : 'close', 'form_id' : 'contactDetail', 'title' : 'Contact Detail', 'fnedit' : 'contactDetail', 'fnsave' : 'saveContactDetail','js_store_fn' : 'customStore'}";
        //$contactBtn = '<a class="btn btn-info btn-contact permission-modify" href="javascript:void(0)" datatip="Contact Detail" flow="down" onclick="modalAction('.$contactParam.');"><i class="fa fa-address-book"></i></a>';
    endif; */

    $action = getActionButton($contactBtn.$gstJsonBtn.$editButton.$deleteButton);

    if($data->table_status != 4):
        $responseData = [$action,$data->sr_no,$data->party_code,$data->party_name,$data->contact_person,$data->party_mobile,$data->gstin];
    else:
        if($data->system_code != ""):
            $gstJsonBtn = $editButton = $deleteButton = "";
        endif;

        if(in_array($data->group_code,["SC","SD"])):
            $gstJsonBtn = $editButton = $deleteButton = "";
        endif;

        $action = getActionButton($contactBtn.$gstJsonBtn.$editButton.$deleteButton);

        $responseData = [$action,$data->sr_no,$data->party_name,$data->group_name,$data->op_balance,$data->cl_balance];
    endif;

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

    $cat_code ='';
	if($data->ref_id ==6 || $data->ref_id == 7):
        $cat_code = (!empty($data->tool_type))?'['.str_pad($data->tool_type,3,'0',STR_PAD_LEFT).'] ':'';
    endif;

    if($data->final_category == 0):
        $data->category_name = $cat_code.'<a href="' . base_url("itemCategory/list/" . $data->id) . '">' . $data->category_name . '</a>';
    else:
        $data->category_name = $cat_code.$data->category_name;
    endif;

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->category_name,$data->parent_category_name,$data->is_final_text,$data->remark];
}

function getProductData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : '".$data->item_type_text."'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editItem', 'title' : 'Update ".$data->item_type_text."','call_function':'edit'}";
    $revisionParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'Item_revision', 'title' : 'Set Item Revision','call_function':'addItemRevision','button':'close','fnedit':'addItemRevision'}";
    
	$editButton = "";$deleteButton="";$revisionButton ="";$inspBtn="";
    
    if($data->item_type == 1){
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
        $revisionButton  = '<a class="btn btn-primary permission-modify" href="javascript:void(0)" datatip="Item Revision" flow="down" onclick="modalAction('.$revisionParam.');"><i class="fa fa-retweet"></i></a>';
    }elseif($data->item_type == 3){
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    }else{
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    }
	
	if($data->item_type == 1 OR $data->is_inspection == 1){
		$inspParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'inspection', 'title' : 'Inspection Parameter For ".$data->item_name."','call_function':'addInspectionParameter','button':'close'}";
		$inspBtn  = '<a class="btn btn-info permission-modify" href="javascript:void(0)" datatip="Inspection Parameters" flow="down" onclick="modalAction('.$inspParam.');"><i class="mdi mdi-file-check"></i></a>';
	}

    $action = getActionButton($inspBtn.$revisionButton.$editButton.$deleteButton); // 21-02-2024
    $itemName = ((!empty($data->item_code)) ? '['.$data->item_code.'] ' : '');
    $itemName .= $data->item_name;
	
    if($data->item_type == 5):
        return [$action,$data->sr_no,$data->item_code,$data->item_name,$data->make_brand,$data->part_no,$data->size,$data->installation_year];
	else:
        return [$action,$data->sr_no,$data->item_code,$data->item_name,$data->category_name,$data->unit_name,$data->hsn_code,floatVal($data->gst_per)];
    endif;
}

/* Countries Table Data */
function getCountriesData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Cusstom Field','fndelete':'delete'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editcustomField', 'title' : 'Update Field Option','fnsave':'save','fnedit':'edit'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->name];
}

/* State Table Data */
function getStatesData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Cusstom Field','fndelete':'deleteState'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'right_modal', 'form_id' : 'editState', 'title' : 'Update Field Option','fnsave':'saveState','fnedit':'editState'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="edit('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->name];
}

/* Cities Table Data */
function getCitiesData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Cities','fndelete':'deleteCities'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';


    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editProcess', 'title' : 'Update Process','call_function':'editCities','fnsave':'saveCities'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    
    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->country_name,$data->state_name,$data->name];
}

/* Custom Field Table Data */
function getCustomFieldData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Cusstom Field','fndelete':'deleteCustomField'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editcustomField', 'title' : 'Update Field Option','fnedit':'editCustomField'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton);
    return [$action,$data->sr_no,$data->field_name,$data->field_type];
}

/* Custom Option Table Data */
function getCustomOptionData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Custom Option'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editCustomOption', 'title' : 'Update Custom Option'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline" ></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->field_name,$data->title];
}

function getItemPriceStructureData($data){
    $deleteParam = "{'postData':{'id' : ".$data->structure_id."},'message' : 'Price Structure'}";
    $editParam = "{'postData':{'structure_id' : ".$data->structure_id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editPriceStructure', 'title' : 'Update Price Structure','call_function':'edit'}";
    $copyParam = "{'postData':{'structure_id' : ".$data->structure_id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'copyPriceStructure', 'title' : 'Copy Price Structure','call_function':'copyStructure'}";

    $copyButton = '<a class="btn btn-warning btn-edit permission-write" href="javascript:void(0)" datatip="Copy" flow="down" onclick="modalAction('.$copyParam.');"><i class="fas fa-clone"></i></a>';
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($copyButton.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->structure_name,$data->item_name,$data->category_name,floatval($data->gst_per),floatval($data->mrp),floatval($data->price),floatval($data->dealer_mrp),floatval($data->dealer_price),floatval($data->retail_mrp),floatval($data->retail_price)]; 
}
?>