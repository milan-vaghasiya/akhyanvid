<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="row">
						<div class="col-md-2">
							<select name="status" id="status" class="form-control basic-select2">
								<option value="">ALL Status</option>
								<option value="0,3">Pending</option>
								<option value="1">Completed</option>
								<option value="2">Short Close</option>
							</select>
						</div>
						<div class="col-md-3">
							<select name="party_id" id="party_id" class="form-control basic-select2">
								<option value="">ALL Party</option>
								<?=getPartyListOption($partyList)?>
							</select>
						</div>
						<div class="col-md-3">
							<select name="project_id" id="project_id" class="form-control basic-select2">
								<option value="">ALL Project</option>
								<?php 
								if (!empty($projectList)) :
									foreach ($projectList as $row) :
										echo '<option value="'.$row->id.'">'.$row->project_name.'</option>';
									endforeach;
								endif;
								?>
							</select>
						</div>
						<div class="col-md-2">   
							<input type="date" name="from_date" id="from_date" class="form-control" max="<?=date('Y-m-d')?>" value="<?=date('Y-m-01')?>" />
							<div class="error fromDate"></div>
						</div>     
						<div class="col-md-2">  
							<div class="input-group">
								<input type="date" name="to_date" id="to_date" class="form-control" value="<?=date('Y-m-d')?>" />
								<div class="input-group-append">
									<button type="button" class="btn waves-effect waves-light btn-success float-right refreshReportData loadData" title="Load Data">
										<i class="fas fa-sync-alt"></i> Load
									</button>
								</div>
							</div>
							<div class="error toDate"></div>
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
								<thead class="thead-info" id="theadData">
									<tr class="text-center">
										<th colspan="16">Purchase Monitoring Register</th>
									</tr>
									<tr class="text-center">
										<th rowspan="2">#</th>
										<th rowspan="2" style="min-width:80px;">Order No.</th>
										<th rowspan="2" style="min-width:80px;">Order Date</th>
										<th rowspan="2" style="min-width:100px;">Supplier's Name</th>
										<th rowspan="2" style="min-width:100px;">Project</th>
										<th rowspan="2" style="min-width:100px;">Item Description</th>
										<th rowspan="2" style="min-width:50px;">Order Qty.</th>
										<th rowspan="2" style="min-width:50px;">Order Price</th>
										<th colspan="8">Receipt Details</th>
									</tr>
									<tr class="text-center">
										<th style="min-width:80px;">Date</th>
										<th style="min-width:80px;">GRN No</th>
										<th style="min-width:80px;">CH/INV Date</th>
										<th style="min-width:50px;">CH/INV No</th>
										<th style="min-width:50px;">Qty</th>
										<th style="min-width:50px;">Pend. Qty</th>
										<th style="min-width:50px;">Price</th>
										<th style="min-width:50px;">Total Amount</th>
									</tr>
								</thead>
								<tbody id="tbodyData"></tbody>								
								<tfoot id="tfootData"> 
									<tr class="thead-info">
										<th colspan="5" class="text-right">Total</th>
										<th class="text-center">0</th> 
										<th colspan="5"></th>
										<th class="text-center">0</th> 
										<th class="text-center">0</th> 
										<th class="text-center">0</th>
										<th class="text-center">0</th>
									</tr>
								</tfoot>
							</table>
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
    setTimeout(function() { $(".loadData").trigger('click'); }, 500);

    $(document).on('click','.loadData',function(e){
		e.stopImmediatePropagation();e.preventDefault();
		$(".error").html("");
		var valid = 1;
		var status = $('#status').val();
		var party_id = $('#party_id').val();
		var project_id = $('#project_id').val();
		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();
		if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
		if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
		if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}

		if(valid)
		{
            $.ajax({
                url: base_url + controller + '/getPurchaseMonitoring',
                data: { party_id:party_id, project_id:project_id, from_date:from_date, to_date:to_date, status:status },
				type: "POST",
				dataType:'json',
				success:function(data){
                    $("#reportTable").dataTable().fnDestroy();
					$("#tbodyData").html(data.tbody);
					$("#tfootData").html(data.tfoot);
					reportTable();
                }
            });
        }
    });   
});
</script>