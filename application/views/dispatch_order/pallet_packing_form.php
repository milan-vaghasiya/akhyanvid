<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered,.select2-results__option{font-size:12px!important;}
    .select2-container .select2-selection--single{overflow: hidden;}
    .select2-selection__rendered { white-space: normal!important; word-break: break-all!important; }
</style>
<div class="col-md-12">
    <div class="row">
		<div class="col-md-2">
			<button type="button" class="btn btn-secondary btn-block" title="Click Me" data-toggle="collapse" href="#order_item_list" role="button" aria-expanded="false" aria-controls="rework"> Order Item List</button>
		</div>
		<div class="col-md-10">
			<hr>
		</div>
		
		<section class="collapse multi-collapse" id="order_item_list">
			<div class="table-responsive">
				<div class="table-responsive">
					<table class="table jpExcelTable">
						<thead class="thead-dark">
							<tr>
								<th>#</th>
								<th>Item</th>
								<th>Order Qty</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($orderItems)){ $i=1;
								foreach($orderItems as $row){ ?>
									<tr>
										<td><?=$i++?></td>
										<td><?=$row->item_name?></td>
										<td><?=$row->order_qty?></td>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
				</div>
            </div>
        </section>
    </div>
    <hr>
    <div class="row">
        <input type="hidden" id="order_no" value="<?=$do_no?>">
        <input type="hidden" id="order_number" value="<?=$order_number?>">
        <div class="col-md-12">
            <div class="table table-responsive">
                <form data-res_function="resPackingAnnexureDetails">
                    <table id="annexureDetail" class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Cartton No.</th>
                                <th>Packing Material</th>
                                <th>Cartoon Qty</th>
                                <th>Cartoon Weight</th>
                                <th>Item Name</th>
                                <th>Pending Qty</th>
                                <th>Box Qty.</th>
                                <th>Action</th>
                            </tr>                        
                            <tr>
                                <td style="width:10%;">
                                    <input type="hidden" name="id" id="id" value="">
                                    <input type="hidden" name="order_number" id="do_number" value="<?=$order_number?>">
                                    <input type="hidden" name="do_id" id="do_id" value="">
                                    <select name="cartoon_no" id="cartoon_no" class="form-control select2">
                                        <option value="">New</option>
                                    </select>
                                </td>
                                <td style="width:25%;">
                                    <div style="width:99%;">
                                        <select name="box_id" id="box_id" class="form-control select2 itemDetails" data-res_function="resBoxDetail">
                                            <option value="">Select Packing Material</option>
                                            <?=getItemListOption($packingMaterialList)?>
                                        </select>
                                    </div>
                                </td>
                                <td style="width:10%;">
                                    <input type="text" name="cartoon_qty" id="cartoon_qty" class="form-control numericOnly" value="1">
                                </td>
                                <td style="width:10%;">
                                    <input type="text" name="box_weight" id="box_weight" class="form-control floatOnly" value="">
                                </td>
                                <td style="width:25%;">
                                    <div style="width:99%;">
                                        <select name="ref_id" id="ref_id" class="form-control select2">
                                            <option value="">Select Item</option>
                                        </select>
                                    </div>
                                </td>
                                <td style="width:12%;">
                                    <input type="text" id="pending_qty" class="form-control" value="" readonly>
                                </td>
                                <td style="width:12%;">
                                    <input type="text" name="box_qty" id="box_qty" class="form-control floatOnly" value="">
                                </td>
                                <td style="width:5%;">
                                    <button type="button" class="btn btn-success" onclick="customStore({'formId':'finalPacking','fnsave':'saveFinalPacking','controller':'dispatchOrder'});"><i class="fas fa-check"></i></button>
                                </td>
                            </tr>                        
                            <tr>
                                <th>Cartton No.</th>
                                <th colspan="2">Packing Material</th>
                                <th>Cartoon Weight</th>
                                <th>Item Name</th>
                                <th>Packing No.</th>
                                <th>Box Qty.</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="annexureData">
                            <tr>
                                <td colspan="8" class="text-center">No data available in table</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    getCartoonNo();
    getItemList();

    var annexureDetail = {'postData':{'order_number':$("#order_number").val()},'table_id':"annexureDetail",'tbody_id':'annexureData','tfoot_id':'','fnget':'getAnnexureDetail'};
    getTransHtml(annexureDetail);

    $(document).on('change','#cartoon_no',function(){
        var cartoon_no = $(this).val();

        $("#box_id,#box_weight").val("");
        $("#cartoon_qty").val(1).prop('readonly',false);
        $("#box_weight").prop('readonly',false);
        $('#box_id option').prop('disabled', false);

        if(cartoon_no != ""){
            var box_id = $('#cartoon_no :selected').data('box_id');
            $("#box_id").val(box_id);
            $("#box_weight").val($('#cartoon_no :selected').data('box_weight')).prop('readonly',true);
            $("#cartoon_qty").val(1).prop('readonly',true);

            $('#box_id option[value="'+box_id+'"]').prop('disabled', false);
            $('#box_id option:not([value="'+box_id+'"])').prop('disabled', true);
        }
        initSelect2();
    });

    $(document).on('change','#ref_id',function(){
        var ref_id = $(this).val();

        $("#pending_qty,#do_id").val("");

        if(ref_id != ""){
            $("#pending_qty").val($('#ref_id :selected').data('pending_qty'));
            $("#do_id").val($('#ref_id :selected').data('do_id'));
        }
    });
});

function getCartoonNo(){
    $.ajax({
        url : base_url + controller + '/getCartoonNoList',
        type : 'post',
        data : {order_number : $("#order_number").val()},
        dataType : 'json'
    }).done(function(response){
        $("#cartoon_no").html("");
        $("#cartoon_no").html(response.cartoonNoList);
        $("#box_weight").prop('readonly',false);
        $('#box_id option').prop('disabled', false);
        initSelect2();
    });
}

function getItemList(){
    $.ajax({
        url : base_url + controller + '/getLinkedItemList',
        type : 'post',
        data : {order_number : $("#order_number").val()},
        dataType : 'json'
    }).done(function(response){
        $("#ref_id").html("");
        $("#ref_id").html(response.itemList);
        initSelect2();
    });
}

function resBoxDetail(response = ""){
    if(response != ""){
        var itemDetail = response.data.itemDetail;
        $("#box_weight").val(itemDetail.wt_pcs);
    }else{
        $("#box_weight").val("");
    }
}

function resPackingAnnexureDetails(data,formId){
    if(data.status==1){
        getCartoonNo();
        getItemList();
        $("#box_id,#box_weight,#pending_qty,#do_id,#box_qty").val("");
        $("#do_number").val($("#order_number").val());
        $("#cartoon_qty").val(1).prop('readonly',false);

        Swal.fire({ icon: 'success', title: data.message});

        var annexureDetail = {'postData':{'order_number':$("#order_number").val()},'table_id':"annexureDetail",'tbody_id':'annexureData','tfoot_id':'','fnget':'getAnnexureDetail'};
        getTransHtml(annexureDetail);

        initTable();
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            initTable();
            Swal.fire({ icon: 'error', title: data.message });
        }			
    }
}

function resRemoveAnnexureItem(response){
    if(response.status==0){
        Swal.fire( 'Sorry...!', response.message, 'error' );
    }else{ 
        getCartoonNo();
        getItemList();
        $("#pending_qty,#do_id,#box_qty").val("");
        $("#do_number").val($("#order_number").val());
        $("#cartoon_qty").val(1).prop('readonly',false);

        initTable();
        Swal.fire( 'Remove!', response.message, 'success' );

        var annexureDetail = {'postData':{'order_number':$("#order_number").val()},'table_id':"annexureDetail",'tbody_id':'annexureData','tfoot_id':'','fnget':'getAnnexureDetail'};
        getTransHtml(annexureDetail);
    }	
}
</script>