<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2 form-group">
                                <label for="status">Status</label>
                                <select id="status" name="status" class="form-control basic-select2">
                                    <option value="">All</option>
                                    <option value="1">Pending</option>
                                    <option value="2">Completed</option>
                                    <option value="3">Delay</option>
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="executive_id">Select Executive</label>
                                <select id="executive_id" name="executive_id" class="form-control basic-select2">
                                    <option value="0">All Executive</option>
                                    <?php
                                    if(!empty($salesExecutives)){
                                        foreach($salesExecutives as $row){
                                            ?>
                                            <option value="<?=$row->id?>"><?=$row->emp_name?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="mode">Mode</label>
                                <select name="mode" id="mode" class="form-control basic-select2">
                                <option value="0">All Mode</option>
                                    <?php
                                        foreach($this->appointmentMode as $mode):
                                            echo '<option value="'.$mode.'">'.$mode.'</option>';
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="from_date">From Date</label>
                                <input type="date" id="from_date" class="form-control" value="<?=$startDate?>" /> 
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="to_date">To Date</label>  
                                <div class="input-group">                             
                                    <input type="date" id="to_date" class="form-control" value="<?=$endDate?>" />
                                    <div class="input-group-append">   
                                    <button type="button" class="btn waves-effect waves-light btn-success float-right loadData" style="width:100%;" title="Load Data">
                                        <i class="fas fa-sync-alt"></i> Load
                                    </button>
                                </div>  
                                <div class="error fromDate"></div>
                                <div class="error toDate"></div>
                            </div>                       
                        </div>                       
					</div>
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body reportDiv" style="min-height:75vh">
                            <div class="table-responsive">
                                <table id='reportTable' class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th> # </th>
                                            <th>Reminder Date</th>
                                            <th>Executive Name</th>
                                            <th>Party Name</th>
                                            <th>Mode</th>
                                            <th>Notes</th>
                                            <th>Response</th>
                                            <th>Response Date</th>
                                            <th>Due Days</th>
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
    setTimeout(function(){ $(".loadData").trigger('click'); },500);
    
    $(document).on('click','.loadData',function(e){
		$(".error").html("");
		var valid = 1;
		var status = $('#status').val();
		var executive_id = $('#executive_id').val();
		var mode = $('#mode').val();
        var from_date = $('#from_date').val();
	    var to_date = $('#to_date').val();
        if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
	    if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
	    if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}

		if(valid){
            $.ajax({
                url: base_url + controller + '/getAppointmentRegister',
                data: {status:status, executive_id:executive_id, mode:mode, from_date:from_date, to_date:to_date},
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
});
</script>

<?php $this->load->view('includes/footer'); ?>
