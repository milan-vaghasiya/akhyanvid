<form>
    <div class="row">
        <div class="col-md-12">
            <div class="crm-desk-left">
                <h4>Item Detail : </h4>
                <h6 class="text-primary text-bold">Click on item name to get stock details</h6>
                <div class="table-responsive">
                    <button class="btn btn-outline-dark bulkPI mb-3 float-right"  type="button" style="display:none">Bulk Purchase Request</button>
                    <table class="table  table-bordered" id="itemTable">
                        <thead class="thead-info">
                            <tr>
                                <th><input type="checkbox" id="masterSelect" class="filled-in chk-col-success BulkRequest" value=""><label for="masterSelect">ALL</label></th>
                                <th>Item</th>
                                <th>Required Qty</th>
                                <th>Issue Qty</th>
                                <th>Pending Issue Qty</th>
                                <th>WIP Qty</th>
                                <th>Stock Qty</th>
                                <th>Pending Request</th>
                                <th>Pending PO</th>
                                <th>Pending GRN QC</th>
                                <th>Shortage Qty</th>
                            </tr>
                        </thead>
                        <tbody id="itemTbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <h4>Issue Data : </h4>
            <div class="col-md-12">
                <div class="row">
                    <input type="hidden" name="issue_no" value="<?= $issue_no ?>"  />
                    <input type="hidden" name="issue_type" id="issue_type" value="<?=2?>">
                    <input type="hidden" name="prc_id" id="prc_id" value="<?=((!empty($prc_id))?$prc_id:'')?>">
                    <input type="hidden" name="item_id" id="item_id">
                    <input type="hidden" name="issued_to" id="issued_to" value="<?=$this->loginId?>">
                    <h6 id="item_name" class="text-primary"></h6>
                </div>
                
            </div>
            <div class="col-md-12 form-group mt-4">
                <div class="error general_batch_no"></div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-info">
                            <th>Location</th>
                            <!-- <th>Batch No.</th> -->
                            <!-- <th>Heat No.</th> -->
                            <th>Stock Qty.</th>
                            <th>Issue Qty.</th>
                        </thead>
                        <tbody id="tbodyData">
                            <tr>
                                <th colspan="4" class="text-center">No data available</th>
                            </tr>
                        </tbody>
                    </table>
                    <div class="error table_err"></div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var tbodyData = false;
    $(document).ready(function(){
        if(!tbodyData){
            var postData = {'postData':{'prc_id':$("#prc_id").val(),'issue_type':$("#issue_type").val(),'req_number':$("#req_number").val()},'table_id':"itemTable",'tbody_id':'itemTbody','tfoot_id':'','fnget':'getItemsForIssue','controller':'dispatchPlan'};
            getItemsForIssue(postData);
            tbodyData = true;
        }

        $(document).on('click', '.itemDetail', function (e) {
            e.stopImmediatePropagation();e.preventDefault();
            var item_id = $(this).data('item_id');
            var item_name = $(this).data('item_name');
            var item_type = $(this).data('item_type');
            var req_id = $(this).data('req_id') || 0;
            if(req_id > 0){
                var prc_id = $(this).data('prc_id') || 0;
                $('#prc_id').val(prc_id);
            }
            $('#req_id').val(req_id);
            $('#item_id').val(item_id);
            $('#item_name').html(item_name);
			if(item_id){
				$.ajax({
					url:base_url  + "store/getBatchWiseStock",
					type:'post',
					data:{item_id:item_id,item_type:item_type},
					dataType:'json',
					success:function(data){
						if(data.status == 1){
							$('#tbodyData').html('');
							$('#tbodyData').html(data.tbodyData);
						}
					}
				});
			}
        });

        $(document).on('click', '.BulkRequest', function() {
            if ($(this).attr('id') == "masterSelect") {
                if ($(this).prop('checked') == true) {
                    $(".bulkPI").show();
                    $("input[name='ref_id[]']").prop('checked', true);
                } else {
                    $(".bulkPI").hide();
                    $("input[name='ref_id[]']").prop('checked', false);
                }
            } else {
                if ($("input[name='ref_id[]']").not(':checked').length != $("input[name='ref_id[]']").length) {
                    $(".bulkPI").show();
                    // $(".bulkEnq").show();
                    $("#masterSelect").prop('checked', false);
                } else {
                    $(".bulkPI").hide();
                }

                if ($("input[name='ref_id[]']:checked").length == $("input[name='ref_id[]']").length) {
                    $("#masterSelect").prop('checked', true);
                    $(".bulkPI").show();
                }
                else{$("#masterSelect").prop('checked', false);}
            }
        });

        $(document).on('click', '.bulkPI', function() {
            var ref_id = []; var qty = []; var sendData = [];
            $("input[name='ref_id[]']:checked").each(function() {
                ref_id = $(this).val();
                qty = $(this).data('qty');
                item_name = $(this).data('item_name');
                sendData.push({ item_id:ref_id, qty:qty,item_name:item_name });
            });
            if(sendData.length > 0){
                var itemData = encodeURIComponent(JSON.stringify(sendData));
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Are you sure want to generate PO?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Do it!',
                }).then(function(result) {
                    if (result.isConfirmed){	
                        var piParam = {postData:{itemData:sendData},modal_id : 'bs-right-lg-modal', call_function:'addPurchaseRequest',form_id: 'addPurchaseRequest',title: 'Add Purchase Request',controller:'purchaseIndent','js_store_fn':'customStore'};		
                        modalAction(piParam);	
                        // window.open(base_url + 'purchaseIndent/addPOFromForecast/' + postData, '_self');
                    }
                });
            }
        });

    });

function getItemsForIssue(data){
	var postData = data.postData || {};
	var fnget = data.fnget || "";
	var controllerName = data.controller || controller;

	var table_id = data.table_id || "";
	var thead_id = data.thead_id || "";
	var tbody_id = data.tbody_id || "";
	var tfoot_id = data.tfoot_id || "";	

	if(thead_id != ""){
		$("#"+table_id+" #"+thead_id).html(data.thead);
	}
	
	$.ajax({
		url: base_url + controllerName + '/' + fnget,
		data:postData,
		type: "POST",
		dataType:"json",
		beforeSend: function() {
			if(table_id != ""){
				var columnCount = $('#'+table_id+' thead tr').first().children().length;
				$("#"+table_id+" #"+tbody_id).html('<tr><td colspan="'+columnCount+'" class="text-center">Loading...</td></tr>');
			}
		},
	}).done(function(res){
		$("#"+table_id+" #"+tbody_id).html('');
			$("#"+table_id+" #"+tbody_id).html(res.tbodyData);
			if(tfoot_id != ""){
				$("#"+table_id+" #"+tfoot_id).html('');
				$("#"+table_id+" #"+tfoot_id).html(res.tfootData);
			}
	});
}
function storeIssueMaterial(postData){
    setPlaceHolder();
    var formId = postData.formId;
    var fnsave = postData.fnsave || "save";
    var controllerName = postData.controller || controller;

    var form = $('#'+formId)[0];
    var fd = new FormData(form);
    $.ajax({
        url: base_url + controllerName + '/' + fnsave,
        data:fd,
        type: "POST",
        processData:false,
        contentType:false,
        dataType:"json",
    }).done(function(data){
        if(data.status==1){
            initTable(); 
            $("#item_id").val("");
            $("#item_name").html("");
            $("#required_qty").val("");
            $("#emp_dept_id").val("");
            $("#issued_to").val("");
            $("#tbodyData").html('<tr><th colspan="5" class="text-center">No Data Available</th></tr>');
            initSelect2();	
            Swal.fire({ icon: 'success', title: data.message});
            var postData = {'postData':{'prc_id':$("#prc_id").val(),'issue_type':$("#issue_type").val(),'req_number':$("#req_number").val()},'table_id':"itemTable",'tbody_id':'itemTbody','tfoot_id':'','fnget':'getItemsForIssue','controller':'dispatchPlan'};
            getItemsForIssue(postData);
        }else{
            if(typeof data.message === "object"){
                $(".error").html("");
                $.each( data.message, function( key, value ) {$("."+key).html(value);});
            }else{
                Swal.fire({ icon: 'error', title: data.message });
            }			
        }				
    });
}

function getIndentResponse(data,formId=""){
    if(data.status==1){
        if(formId){
            $('#'+formId)[0].reset(); closeModal(formId);
        }
        Swal.fire({ icon: 'success', title: data.message});
        var postData = {'postData':{'prc_id':$("#prc_id").val(),'issue_type':$("#issue_type").val(),'req_number':$("#req_number").val()},'table_id':"itemTable",'tbody_id':'itemTbody','tfoot_id':'','fnget':'getItemsForIssue','controller':'dispatchPlan'};
        getItemsForIssue(postData);
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