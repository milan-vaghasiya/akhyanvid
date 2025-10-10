<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-end">
                    <button type="button" class="btn waves-effect waves-light btn-outline-success float-right addNew permission-write" data-button="both" data-modal_id="modal-lg" data-function="addInstrument" data-form_title="Add Instrument"><i class="fa fa-plus"></i> Add Instrument</button>
					</div>
                    <ul class="nav nav-pills">    
                        <li class="nav-item"> <a href="<?= base_url($headData->controller . "/index/1") ?>" class="btn waves-effect waves-light btn-outline-info <?=($status == 1)?'active':''?>">In Stock</a> </li>
                        <li class="nav-item"> <a href="<?= base_url($headData->controller . "/index/0") ?>" class="btn waves-effect waves-light btn-outline-info <?=($status == 0)?'active':''?>">New Inward</a> </li>
                        <li class="nav-item"> <a href="<?= base_url($headData->controller . "/indexUsed/2") ?>" class="btn waves-effect waves-light btn-outline-info <?=($status == 2)?'active':''?>">Issued</a> </li>
                        <li class="nav-item"> <a href="<?= base_url($headData->controller . "/indexUsed/3") ?>" class="btn waves-effect waves-light btn-outline-info <?=($status == 3)?'active':''?>">In Calibration</a> </li>
                        <li class="nav-item"> <a href="<?= base_url($headData->controller . "/index/4") ?>" class="btn waves-effect waves-light btn-outline-info <?=($status == 4)?'active':''?>">Rejected</a> </li>
                        <li class="nav-item"> <a href="<?= base_url($headData->controller . "/index/5") ?>" class="btn waves-effect waves-light btn-outline-info <?=($status == 5)?'active':''?>">Due For Calibration</a> </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='instrumentTable' class="table table-bordered ssTable" data-url='/getChallanDTRows/<?=$status?>'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script>
$(document).on('click','.confirmChallan',function(){
    var id = $(this).data('id');
    var challan_id = $(this).data('challan_id');
    var item_id = $(this).data('item_id');
    var send_data = { id:id,challan_id:challan_id,item_id:item_id };
    $.confirm({
		title: 'Confirm!',
		content: 'Are you sure want to Confirm this Challan?',
		type: 'green',
		buttons: {   
			ok: {
				text: "ok!",
				btnClass: 'btn waves-effect waves-light btn-outline-success',
				keys: ['enter'],
				action: function(){
					$.ajax({
						url: base_url + 'qcChallan/confirmChallan',
						data: send_data,
						type: "POST",
						dataType:"json",
						success:function(data){
							if(data.status==0){
								Swal.fire( 'Success', data.message, 'success' );
							}else{
								initTable(); initMultiSelect();
								Swal.fire( 'Sorry...!', data.message, 'error' );
							}
						}
					});
				}
			},
			cancel: {
                btnClass: 'btn waves-effect waves-light btn-outline-secondary',
                action: function(){

				}
            }
		}
	});
});

function returnQcChallan(data){
	var button = data.button;if(button == "" || button == null){button="both";};
	var fnEdit = data.fnedit;if(fnEdit == "" || fnEdit == null){fnEdit="edit";}
	var fnsave = data.fnsave;if(fnsave == "" || fnsave == null){fnsave="save";}
	var savebtn_text = data.savebtn_text;if(savebtn_text == "" || savebtn_text == null){savebtn_text="Save";}
	var sendData = {id:data.id};
	$.ajax({ 
		type: "POST",   
		url: base_url + 'qcChallan/returnChallan',   
		data: sendData,
	}).done(function(response){
		$("#"+data.modal_id).modal('show');
		$("#"+data.modal_id+' .modal-body').html('');
		$("#"+data.modal_id+' .modal-title').html(data.title);
		$("#"+data.modal_id+' .modal-body').html(response);
		$("#"+data.modal_id+" .modal-body form").attr('id',data.form_id);
		$("#"+data.modal_id+" .modal-footer .btn-save").attr('onclick',"store('"+data.form_id+"','"+fnsave+"');");
		$("#"+data.modal_id+" .modal-footer .btn-save-close").attr('onclick',"store('"+data.form_id+"','"+fnsave+"','save_close');");
		$("#"+data.modal_id+" .modal-footer .btn-close").attr('data-modal_id',data.form_id);
		if(button == "close"){
			$("#"+data.modal_id+" .modal-footer .btn-close").show();
			$("#"+data.modal_id+" .modal-footer .btn-save").hide();
			$("#"+data.modalId+" .modal-footer .btn-save-close").hide();
		}else if(button == "save"){
			$("#"+data.modal_id+" .modal-footer .btn-close").hide();
			$("#"+data.modal_id+" .modal-footer .btn-save").show();
            $("#"+data.modalId+" .modal-footer .btn-save-close").show();
		}else{
			$("#"+data.modal_id+" .modal-footer .btn-close").show();
			$("#"+data.modal_id+" .modal-footer .btn-save").show();
            $("#"+data.modalId+" .modal-footer .btn-save-close").show();
		}
		initModalSelect();
		$(".single-select").select2();
		$('.model-select2').select2({ dropdownParent: $('.model-select2').parent() });
		initMultiSelect();setPlaceHolder();
	});
}

function trashQcChallan(id,name='Record'){
	var send_data = { id:id };
	$.confirm({
		title: 'Confirm!',
		content: 'Are you sure want to delete this '+name+'?',
		type: 'red',
		buttons: {   
			ok: {
				text: "ok!",
				btnClass: 'btn waves-effect waves-light btn-outline-success',
				keys: ['enter'],
				action: function(){
					$.ajax({
						url: base_url + 'qcChallan/delete',
						data: send_data,
						type: "POST",
						dataType:"json",
						success:function(data)
						{
							if(data.status==0)
							{
								Swal.fire( 'Success', data.message, 'success' );
							}
							else
							{
								initTable(); initMultiSelect();
								Swal.fire( 'Sorry...!', data.message, 'error' );
							}
						}
					});
				}
			},
			cancel: {
                btnClass: 'btn waves-effect waves-light btn-outline-secondary',
                action: function(){

				}
            }
		}
	});
}

function saveReturnChallan(formId){
    var form = $('#'+formId)[0];
	var fd = new FormData(form);
    $.ajax({
		url: base_url + 'qcChallan/saveReturnChallan',
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(data){
		if(data.status===0){
			$(".error").html("");
			$.each( data.message, function( key, value ) {$("."+key).html(value);});
		}else if(data.status==1){
			initTable(); $(".modal").modal('hide');
			Swal.fire( 'Success', data.message, 'success' );
		}else{			
            initTable(); $(".modal").modal('hide');
			Swal.fire( 'Sorry...!', data.message, 'error' );
		}			
	});
}
</script>