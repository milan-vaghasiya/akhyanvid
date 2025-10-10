<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
    <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<!--
                    <div class="float-start">
						
                        <ul class="nav nav-pills">
                            <li class="nav-item"> 
                                <a href="<?=base_url("companyInfo")?>" class="nav-tab btn waves-effect waves-light btn-outline-primary active">Company Info</a>
                            </li>
                            <li class="nav-item"> 
                                <a href="<?=base_url("companyInfo/generalSetting")?>" class="nav-tab btn waves-effect waves-light btn-outline-primary">General Settings</a>
                            </li>
                        </ul>
						
                    </div>
					<div class="float-end <?=($this->cm_id_count == 1)?"hidden":""?>" style="width:10%;" >                  
                        <select id="cm_id" class="form-control" >
                            <option value="1">UNIT-1</option>
                            <option value="2">UNIT-2</option>
                            <option value="3">UNIT-3</option>
                        </select>
					</div>     
					-->					
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body">
                            <form id="addCompanyInfo" data-res_function="companyInfoRes">
                                <div class="col-md-12">
                                    <div class="row">
                                        <input type="hidden" name="id" id="id" class="form-control" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />

                                        <div class="col-md-6 form-group">
                                            <label for="company_name">Company Name</label>
                                            <input type="text" name="company_name" id="company_name" class="form-control req" value="<?= (!empty($dataRow->company_name)) ? $dataRow->company_name : "" ?>">
                                        </div> 
										
										<div class="col-md-3 form-group">
                                            <label for="company_gst_no">Company GST No.</label>
                                            <input name="company_gst_no" id="company_gst_no" class="form-control" value="<?= (!empty($dataRow->company_gst_no)) ? $dataRow->company_gst_no : "" ?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="company_pan_no">Company Pan No.</label>
                                            <input name="company_pan_no" id="company_pan_no" class="form-control" value="<?= (!empty($dataRow->company_pan_no)) ? $dataRow->company_pan_no : "" ?>">
                                        </div>
										
										<div class="col-md-3 form-group">
                                            <label for="company_slogan">Company Slogan</label>
                                            <input name="company_slogan" id="company_slogan" class="form-control" value="<?= (!empty($dataRow->company_slogan)) ? $dataRow->company_slogan : "" ?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="company_contact">Company Contact</label>
                                            <input name="company_contact" id="company_contact" class="form-control req" value="<?= (!empty($dataRow->company_contact)) ? $dataRow->company_contact : "" ?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="company_email">Company Email</label>
                                            <input type="text" name="company_email" id="company_email" class="form-control req" value="<?= (!empty($dataRow->company_email)) ? $dataRow->company_email : "" ?>">
                                        </div> 

                                        <div class="col-md-3 form-group">
                                            <label for="company_phone">Company Phone</label>
                                            <input name="company_phone" id="company_phone" class="form-control" value="<?= (!empty($dataRow->company_phone)) ? $dataRow->company_phone : "" ?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="company_country_id">Company Country</label>
                                            <select name="company_country_id" id="company_country_id" class="form-control country_list basic-select2 req"  data-state_id="company_state_id" data-selected_state_id="">
                                                <option value="">Select Country</option>
                                                <?php foreach($countryData as $row):
                                                    $selected = (!empty($dataRow->company_country_id) && $dataRow->company_country_id == $row->id)?"selected":"";
                                                ?>
                                                    <option value="<?=$row->id?>" <?=$selected?>><?=$row->name?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="company_state_id">Company State</label>
                                            <select name="company_state_id" id="company_state_id" class="form-control state_list basic-select2 req" data-city_id="company_city_id" data-selected_city_id="" >
                                                <option value="">Select State</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="company_city_id">Company District</label>
                                            <select name="company_city_id" id="company_city_id" class="form-control basic-select2 req" >
                                                <option value="">Select District</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="company_village_name">Company City/Village</label>
                                            <input type="text" name="company_village_name" id="company_village_name" class="form-control typeahead req" value="<?=(!empty($dataRow->company_village_name))?$dataRow->company_village_name:""?>">
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="company_pincode">Company Pincode</label>
                                            <input name="company_pincode" id="company_pincode" class="form-control req" value="<?= (!empty($dataRow->company_pincode)) ? $dataRow->company_pincode : "" ?>">
                                        </div>

                                        <div class="col-md-12 form-group">
                                            <label for="company_address">Company Address</label>
                                            <input name="company_address" id="company_address" class="form-control req" value="<?= (!empty($dataRow->company_address)) ? $dataRow->company_address : "" ?>">
                                        </div>

                                        

                                        <div class="col-md-2 form-group">
                                            <label for="company_bank_name">Company Bank Name</label>
                                            <input name="company_bank_name" id="company_bank_name" class="form-control" value="<?= (!empty($dataRow->company_bank_name)) ? $dataRow->company_bank_name : "" ?>">
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="company_bank_branch">Company Bank Branch</label>
                                            <input name="company_bank_branch" id="company_bank_branch" class="form-control" value="<?= (!empty($dataRow->company_bank_branch)) ? $dataRow->company_bank_branch : "" ?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="company_acc_name">Company Account Name</label>
                                            <input name="company_acc_name" id="company_acc_name" class="form-control" value="<?= (!empty($dataRow->company_acc_name)) ? $dataRow->company_acc_name : "" ?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="company_acc_no">Company Account No.</label>
                                            <input name="company_acc_no" id="company_acc_no" class="form-control" value="<?= (!empty($dataRow->company_acc_no)) ? $dataRow->company_acc_no : "" ?>">
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="company_ifsc_code">Company IFSC Code</label>
                                            <input name="company_ifsc_code" id="company_ifsc_code" class="form-control" value="<?= (!empty($dataRow->company_ifsc_code)) ? $dataRow->company_ifsc_code : "" ?>">
                                        </div> 
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer bg-facebook">
                            <div class="col-md-12"> 
                                <button type="button" class="btn waves-effect waves-light btn-success float-right save-form" onclick="customStore({'formId':'addCompanyInfo','fnsave':'save'});" ><i class="fa fa-check"></i> Save </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function(){
    $("#cm_id").val(($("#company_id :selected").val() || 1));
	setTimeout(function(){$("#cm_id").trigger('change');},500);

    $("#company_country_id").data("selected_state_id",<?=(!empty($dataRow->company_state_id))?$dataRow->company_state_id:""?>);
    $("#company_state_id").data("selected_city_id",<?=(!empty($dataRow->company_city_id))?$dataRow->company_city_id:""?>);
    $("#company_country_id").trigger('change');

    $("#delivery_country_id").data("selected_state_id",<?=(!empty($dataRow->company_state_id))?$dataRow->company_state_id:""?>);
    $("#delivery_state_id").data("selected_city_id",<?=(!empty($dataRow->company_city_id))?$dataRow->company_city_id:""?>);
    $("#delivery_country_id").trigger('change');

    $(document).on('change','#cm_id',function(){
        var id = $(this).val();
        $.ajax({
            url : base_url + controller + '/getCompanyInfo',
            type : 'post',
            data : {id : id},
            dataType : 'json',
        }).done(function(response){
           
            if(typeof response.companyInfo === "object" && response.companyInfo != null){
                $.each( response.companyInfo, function( key, value ) {
                    if($.inArray(key,["company_country_id","company_state_id","company_city_id","delivery_country_id","delivery_state_id","delivery_city_id"])  === -1){                        
                        $("#"+key).val(value);
                    }
                });

                $("#company_country_id").val(response.companyInfo.company_country_id);
                $("#company_country_id").data("selected_state_id",response.companyInfo.company_state_id);
                $("#company_state_id").data("selected_city_id",response.companyInfo.company_city_id);
                $("#company_country_id").trigger('change');

                response.companyInfo.delivery_country_id = (response.companyInfo.delivery_country_id > 0)?response.companyInfo.delivery_country_id:"";
                $("#delivery_country_id").val(response.companyInfo.delivery_country_id);
                $("#delivery_country_id").data("selected_state_id",response.companyInfo.delivery_state_id);
                $("#delivery_state_id").data("selected_city_id",response.companyInfo.delivery_city_id);                
                $("#delivery_country_id").trigger('change');

                initSelect2();

                /* setTimeout(function(){
                    $("#company_state_id").val(response.companyInfo.company_state_id);
                    $("#company_city_id").val(response.companyInfo.company_city_id);

                    $("#delivery_state_id").val(response.companyInfo.delivery_state_id);
                    $("#delivery_city_id").val(response.companyInfo.delivery_city_id);

                    initSelect2();
                },2000); */
            }else{
                $('#addCompanyInfo .form-control').val("");
                initSelect2();
            }
        });
    });

    $('#company_village_name').typeahead({
		source: function(query, result){
			$.ajax({
				url:base_url + 'parties/getVillageList',
				method:"POST",
				global:false,
				data:{villange_name:query,country_id:$("#company_country_id :selected").val(),state_id:$("#company_state_id :selected").val(),city_id:$("#company_city_id :selected").val()},
				dataType:"json",
				success:function(data){
					result($.map(data, function(row){ return {name:row.name,id:row.id,country_id:row.country_id,state_id:row.state_id,city_id:row.city_id}; }));
                    /* $("#city_id").val("");
                    initSelect2(); */
				}
			});
		},
		updater: function(item) {
            if(item.city_id != ""){
                $("#company_city_id").val(item.city_id || "");
                initSelect2();
            }            
			return item;
        }
	});

    $('#delivery_village_name').typeahead({
		source: function(query, result){
			$.ajax({
				url:base_url + 'parties/getVillageList',
				method:"POST",
				global:false,
				data:{villange_name:query,country_id:$("#delivery_country_id :selected").val(),state_id:$("#delivery_state_id :selected").val(),city_id:$("#delivery_city_id :selected").val()},
				dataType:"json",
				success:function(data){
					result($.map(data, function(row){ return {name:row.name,id:row.id,country_id:row.country_id,state_id:row.state_id,city_id:row.city_id}; }));
                    /* $("#city_id").val("");
                    initSelect2(); */
				}
			});
		},
		updater: function(item) {
            if(item.city_id != ""){
                $("#delivery_city_id").val(item.city_id || "");
                initSelect2();
            }            
			return item;
        }
	});
});

function companyInfoRes(data,formId){
    if(data.status==1){
        Swal.fire({ icon: 'success', title: data.message});

        $("#cm_id").trigger('change');
        //window.location.reload();
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            Swal.fire({ icon: 'error', title: data.message });
        }			
    }			
}
</script>