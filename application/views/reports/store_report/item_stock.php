<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="card-title pageHeader"><?=$pageHeader?></h4>
                        </div>       
                        <div class="col-md-8 float-right">  
                            <div class="input-group">
                                <div class="input-group-append" style="width:30%;">
                                    <select id="item_type" class="form-control select2">
                                        <?php
                                            foreach($this->itemTypes as $type=>$typeName):
                                                echo '<option value="'.$type.'">'.$typeName.'</option>';
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                                <div class="input-group-append" style="width:30%;">
                                    <select id="location_id" class="form-control select2">
                                        <option value="">ALL Location</option>
                                        <?=getLocationListOption($locationList)?>
                                    </select>  
                                </div>
                                <div class="input-group-append" style="width:30%;">
                                    <select id="stock_type" class="form-control select2" >
                                        <option value="0">ALL</option>
                                        <option value="1">With Stock</option>
                                        <option value="2">Without Stock</option>
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <button type="button" class="btn waves-effect waves-light btn-success refreshReportData loadData" title="Load Data">
                                        <i class="fas fa-sync-alt"></i> Load
                                    </button>
                                </div>
                            </div>
                        </div>                  
                    </div>                                         
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card"> 
                            <div class="card-body reportDiv" style="min-height:75vh">
                                <div class="table-responsive">
                                    <table id='reportTable' class="table table-bordered">
                                        <thead class="thead-dark" id="theadData">
                                            <tr class="text-center">
                                                <th colspan="4">Stock Register</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-left">Item Description</th>
                                                <th class="text-right">Balance Qty.</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyData"></tbody>
                                    </table>
                                </div>
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
	reportTable();
    setTimeout(function(){$(".loadData").trigger('click');},500);
    
    $(document).on('click','.loadData',function(e){
        e.stopImmediatePropagation();e.preventDefault();

		$(".error").html("");
		var valid = 1;
		var item_type = $('#item_type').val();
		var stock_type = $('#stock_type').val();
		var location_id = $('#location_id').val();
		if($("#item_type").val() == ""){$(".item_type").html("Item Type is required.");valid=0;}
		if(valid){
            $.ajax({
                url: base_url + controller + '/getStockRegisterData',
                data: { item_type:item_type, stock_type:stock_type, location_id:location_id },
				type: "POST",
				dataType:'json',
				success:function(data){
                    $("#reportTable").DataTable().clear().destroy();
					$("#tbodyData").html(data.tbody);
					reportTable();
                }
            });
        }
    });  

	$(document).on('click', '.stockTransactions', function() {
		var button = "close";
		var item_id = $(this).data("item_id");
		var item_name = $(this).data("item_name");
		
		$.ajax({
			type: "POST",
			url: base_url + controller + '/stockTransactions',
			data: {
				item_id: item_id
			}
		}).done(function(response) {
			$("#modal-md").modal();
			$('#modal-md .modal-title').html(item_name);
			$('#modal-md .modal-body').html(response);

			$("#modal-md .modal-footer .btn-close").show();
			$("#modal-md .modal-footer .btn-save").hide();
		});
	});
});
</script>