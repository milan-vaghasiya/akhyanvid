<?php $this->load->view('includes/header'); ?>

<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-start">
					    <ul class="nav nav-pills">
                            <li class="nav-item">
								<a href="<?=base_url($headData->controller."/index/0")?>" class="nav-tab btn waves-effect waves-light btn-outline-success <?=($status == 0) ? "active" : "" ?>"> Invoice List </a>
							</li>
                            <li class="nav-item">
								<a href="<?=base_url($headData->controller."/index/1")?>" class="btn waves-effect waves-light btn-outline-danger <?=($status == 1) ? "active" : "" ?>">Canceled Inv. </a>
							</li> 
                            <li class="nav-item">
								<a href="<?=base_url($headData->controller."/index/2")?>" class="btn waves-effect waves-light btn-outline-info <?=($status == 2) ? "active" : "" ?>"> Pending Orders</a>
							</li>
							<?php if($status == 2): ?>
								<li class="nav-item" style="width:150px;">
									<select id="vou_type" class="form-control select2">
										<option value="">Select</option>
										<option value="1">Sales Order</option>
										<option value="2">Delivery challan</option>
										<option value="3">Dispatch Order</option>
									</select>
								</li>
							<?php endif; ?>
                        </ul>
					</div>
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id='reportTable' class="table table-bordered">
                                    <thead class="thead-info" id="theadData">
                                        <tr>
                                            <th>#</th>
                                            <th>DO. No./Vou. No.</th>
                                            <th>Vou. Date</th>
                                            <th>PO No.</th>
                                            <th>Item Name</th>
                                            <th>Pending Qty.</th>
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

<?php $this->load->view('includes/footer'); ?>

<script>
$(document).ready(function(){
	reportTable();
    $(document).on('change','#vou_type',function(e){
		$(".error").html("");
		var valid = 1;
		var vou_type = $('#vou_type').val();
		if(valid){
            $.ajax({
                url: base_url + controller + '/getPendingOrderData',
                data: {vou_type:vou_type},
				type: "POST",
				dataType:'json',
				success:function(data){
                    $("#reportTable").dataTable().fnDestroy();
					$("#tbodyData").html(data.tbody);
					reportTable();
                }
            });
        }
    });  
}); 
</script> 

