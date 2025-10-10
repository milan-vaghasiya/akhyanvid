<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
                    <div class="float-start">
                        <ul class="nav nav-pills">
                            <li class="nav-item"> 
                                <a href="<?=base_url("companyInfo")?>" class="nav-tab btn waves-effect waves-light btn-outline-primary">Company Info</a>
                            </li>
                            <li class="nav-item"> 
                                <a href="<?=base_url("companyInfo/generalSetting")?>" class="nav-tab btn waves-effect waves-light btn-outline-primary active">General Settings</a>
                            </li>
                        </ul>
                    </div>
                    <div class="float-end <?=($this->cm_id_count == 1)?"hidden":""?>" style="width:10%;">                  
                        <select id="cm_id" class="form-control" >
                            <option value="1">UNIT-1</option>
                            <option value="2">UNIT-2</option>
                            <option value="3">UNIT-3</option>
                        </select>
					</div>  
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body">
                            <form id="gerenalSetting" data-res_function="resSaveSettings">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4 class="card-title">General Settings</h4>
                                            <input type="hidden" name="account_setting[id]" id="id" class="form-control" value="<?=(!empty($accountSetting->id))?$accountSetting->id:""?>">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="tcs_limit">TCS Limit</label>
                                            <input type="text" name="account_setting[tcs_limit]" id="tcs_limit" class="form-control floatOnly" value="<?=(!empty($accountSetting->tcs_limit))?$accountSetting->tcs_limit:""?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="tds_limit">TDS Limit</label>
                                            <input type="text" name="account_setting[tds_limit]" id="tds_limit" class="form-control floatOnly" value="<?=(!empty($accountSetting->tds_limit))?$accountSetting->tds_limit:""?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="tcs_with_pan_per">TCS With PAN Per(%)</label>
                                            <input type="text" name="account_setting[tcs_with_pan_per]" id="tcs_with_pan_per" class="form-control floatOnly" value="<?=(!empty($accountSetting->tcs_with_pan_per))?$accountSetting->tcs_with_pan_per:""?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="tcs_without_pan_per">TCS Without PAN Per(%)</label>
                                            <input type="text" name="account_setting[tcs_without_pan_per]" id="tcs_without_pan_per" class="form-control floatOnly" value="<?=(!empty($accountSetting->tcs_without_pan_per))?$accountSetting->tcs_without_pan_per:""?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="closing_stock_type">Closing Stock Type</label>
                                            <select name="account_setting[closing_stock_type]" id="closing_stock_type" class="form-control">
                                                <option value="MANUAL" <?=(!empty($accountSetting->closing_stock_type) && $accountSetting->closing_stock_type == "MANUAL")?"selected":""?>>MANUAL</option>
                                                <option value="FIFO" <?=(!empty($accountSetting->closing_stock_type) && $accountSetting->closing_stock_type == "FIFO")?"selected":""?>>FIFO</option>
                                                <option value="LIFO" <?=(!empty($accountSetting->closing_stock_type) && $accountSetting->closing_stock_type == "LIFO")?"selected":""?>>LIFO</option>
                                                <option value="LASTPRICE" <?=(!empty($accountSetting->closing_stock_type) && $accountSetting->closing_stock_type == "LASTPRICE")?"selected":""?>>LAST PRICE</option>
                                                <option value="AVGPRICE" <?=(!empty($accountSetting->closing_stock_type) && $accountSetting->closing_stock_type == "AVGPRICE")?"selected":""?>>AVG. PRICE</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer bg-facebook">
                            <div class="col-md-12"> 
                                <button type="button" class="btn waves-effect waves-light btn-success float-right save-form" onclick="customStore({'formId':'gerenalSetting','fnsave':'saveSettings'});" ><i class="fa fa-check"></i> Save </button>
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

    $(document).on('change','#cm_id',function(){
        var id = $(this).val();
        $.ajax({
            url : base_url + controller + '/getGenralSetting',
            type : 'post',
            data : {id : id},
            dataType : 'json',
        }).done(function(response){
           
            if(typeof response.accountSetting === "object" && response.accountSetting != null){
                $.each( response.accountSetting, function( key, value ) {                   
                    $("#gerenalSetting #"+key).val(value);
                });

                initSelect2();
            }else{
                $('#gerenalSetting .form-control').val("");
                initSelect2();
            }
        });
    });
});
function resSaveSettings(data,formId){
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