<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/* get Pagewise Table Header */
function getLogisticsDtHeader($page){
    /* Vehicle Master header */
    $data['vehicleMaster'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['vehicleMaster'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['vehicleMaster'][] = ["name"=>"Vehicle Type"];
    $data['vehicleMaster'][] = ["name"=>"Vehicle No."];
    $data['vehicleMaster'][] = ["name"=>"RC. No."];
    $data['vehicleMaster'][] = ["name"=>"Policy Expiry"];
    $data['vehicleMaster'][] = ["name"=>"PUC Expiry"];
    $data['vehicleMaster'][] = ["name"=>"Fitness Expiry"];
    $data['vehicleMaster'][] = ["name"=>"Fuel Type"];
    $data['vehicleMaster'][] = ["name"=>"Incharge"];

    /* Driver Master Header */
    $data['driverMaster'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['driverMaster'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['driverMaster'][] = ["name"=>"Driver Name"];
    $data['driverMaster'][] = ["name"=>"Contact No."];
    $data['driverMaster'][] = ["name"=>"Licence No."];
    $data['driverMaster'][] = ["name"=>"Licence Expiry"];

    /* Trip Header */
    $data['trips'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['trips'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['trips'][] = ["name"=>"Trip No."];
    $data['trips'][] = ["name"=>"Driver Name"];
    $data['trips'][] = ["name"=>"Contact No."];
    $data['trips'][] = ["name"=>"Vehicle No."];
    $data['trips'][] = ["name"=>"Inv No."];
    $data['trips'][] = ["name"=>"City"];
    $data['trips'][] = ["name"=>"Delivery Status"];
    $data['trips'][] = ["name"=>"Start Reading (Km.)"];
    $data['trips'][] = ["name"=>"Start Trip At"];
    $data['trips'][] = ["name"=>"End Reading (Km.)"];
    $data['trips'][] = ["name"=>"End Trip At"];
    $data['trips'][] = ["name"=>"Remark"];

    /* Fuel Header */
    $data['fuelVoucher'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['fuelVoucher'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['fuelVoucher'][] = ["name"=>"Fuel Date"];
    $data['fuelVoucher'][] = ["name"=>"Driver Name"];
    $data['fuelVoucher'][] = ["name"=>"Vehicle No."];
    $data['fuelVoucher'][] = ["name"=>"Km. Reading"];
    $data['fuelVoucher'][] = ["name"=>"Fuel Qty."];
    $data['fuelVoucher'][] = ["name"=>"Fuel Price"];
    $data['fuelVoucher'][] = ["name"=>"Amount"];
    $data['fuelVoucher'][] = ["name"=>"Remark"];

    /* Passenger Vehicle Header */
    $data['passengerVehicle'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['passengerVehicle'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['passengerVehicle'][] = ["name"=>"Vehicle Name"];
    $data['passengerVehicle'][] = ["name"=>"Contact No."];
    $data['passengerVehicle'][] = ["name"=>"Vehicle Capacity"];
    $data['passengerVehicle'][] = ["name"=>"Vehicle Rent"];
    $data['passengerVehicle'][] = ["name"=>"Remark"];

    return tableHeader($data[$page]);
}

/* Vehicle Master Table Data */
function getVehicleData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Vehicle'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editVehicle', 'title' : 'Update Vehicle','call_function':'edit'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->vehicle_type,$data->vehicle_no,$data->rc_no,$data->policy_expiry_date,$data->puc_expiry_date,$data->fitness_expiry_date,$data->fuel_type,$data->incharge_name];
}

/* Driver Master Table Data */
function getDriverData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Employee'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editDriver', 'title' : 'Update Driver','call_function':'edit'}";
    $editButton = "";$deleteButton ="";
    if($data->is_active == 1):
        $activeParam = "{'postData':{'id' : ".$data->id.", 'is_active' : 0},'controller':'hr/employees','fnsave':'activeInactive','message':'Are you sure want to De-Active this Employee?'}";
        $activeButton = '<a class="btn btn-youtube permission-modify" href="javascript:void(0)" datatip="De-Active" flow="down" onclick="confirmStore('.$activeParam.');"><i class="fa fa-ban"></i></a>';

        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

        //$empName = '<a href="'.base_url("hr/employees/empProfile/".$data->id).'" datatip="View Profile" flow="down">'.$data->emp_name.'</a>';
        $empName = $data->emp_name;
    else:
        $activeParam = "{'postData':{'id' : ".$data->id.", 'is_active' : 1},'controller':'hr/employees','fnsave':'activeInactive','message':'Are you sure want to Active this Employee?'}";
        $activeButton = '<a class="btn btn-success permission-remove" href="javascript:void(0)" datatip="Active" flow="down" onclick="confirmStore('.$activeParam.');"><i class="fa fa-check"></i></a>';  
          
        $empName = $data->emp_name;
    endif;

    $action = getActionButton($activeButton.$editButton.$deleteButton);

    return [$action,$data->sr_no,$empName,$data->emp_contact,$data->licence_no,$data->licence_expiry_date];
}

/* Trip Table Data */
function getTripData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Trip'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editDriver', 'title' : 'Update Trip','call_function':'edit'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $whatsapp = "";
    $whatsapp = '<a href="javascript:void(0)" class="btn btn-success sendTripDetailInWhatsapp" datatip="Send Message" flow="down" data-ref_id="'.$data->id.'" data-subject="Trip Detail" data-js_fn_name="sendTripDetailInWhatsapp" data-doc_name="TaxInv"><i class="fab fa-whatsapp" style="font-size:18px;"></i></a>';

    if($data->trip_status == 0):
        $tripParam = "{'postData':{'id' : ".$data->id.", 'trip_status' : 1},'fnsave':'changeTripStatus','message':'Are you sure want to start this Trip?'}";
        $tripButton = '<a class="btn btn-warning permission-remove" href="javascript:void(0)" datatip="Start Trip" flow="down" onclick="confirmStore('.$tripParam.');"><i class="far fa-play-circle"></i></a>';  
    elseif($data->trip_status == 1):
        $editButton = $deleteButton = "";

        $tripParam = "{'postData':{'id' : ".$data->id.", 'trip_status' : 2},'fnsave':'changeTripStatus','message':'Are you sure want to end this Trip?'}";
        $tripButton = '<a class="btn btn-danger permission-remove" href="javascript:void(0)" datatip="End Trip" flow="down" onclick="confirmStore('.$tripParam.');"><i class="far fa-stop-circle"></i></a>';  
    else:
        $whatsapp = $editButton = $deleteButton = $tripButton = "";
    endif;    
    
    $action = getActionButton($whatsapp.$tripButton.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,$data->driver_name,$data->emp_contact,$data->vehicle_no,$data->inv_no,$data->city_name,$data->delivered_on,$data->start_km_reading,$data->start_trip_at,$data->end_km_reading,$data->end_trip_at,$data->remark];
}

/* Fuel Table data */
function getFuelData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Fuel Voucher'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editFuelVoucher', 'title' : 'Update Fuel Voucher','call_function':'edit'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    
    return [$action,$data->sr_no,$data->trans_date,$data->vehicle_no,$data->driver_name,$data->km_reading,$data->fuel_qty,$data->fuel_price,$data->fuel_amount,$data->remark];
}

function getPassengerVehicleData($data){
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Passenger Vehicle'}";
    $editParam = "{'postData':{'id' : ".$data->id."},'modal_id' : 'bs-right-md-modal', 'form_id' : 'editFuelVoucher', 'title' : 'Update Passenger Vehicle','call_function':'edit'}";

    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
	
	$action = getActionButton($editButton.$deleteButton);
    
    return [$action,$data->sr_no,$data->vh_name,$data->contact_no,$data->vh_capacity,$data->vh_rent,$data->remark];
}
?>