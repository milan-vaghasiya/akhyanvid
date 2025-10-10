<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="row">
                        <div class="col-md-2 form-group">
                            <label for="executive_id">Select Executive</label>
                            <select id="executive_id" name="executive_id" class="form-control basic-select2">
                                <option value="">Select Executives</option>
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
                            <label for="business_type">Business Type</label>
                            <select name="business_type" id="business_type" class="form-control basic-select2">
                                <option value = "">Select Business Type</option>
                                <option value="Builder">Builder</option>
                                <option value="Individuals">Individuals</option>
                            </select>   
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="lead_stage">Lead Stages</label>
                            <select name="lead_stage" id="lead_stage" class="form-control basic-select2">
                                <option value="">Select Stage</option>
                                <option value="1">Won</option>
                                <option value="2">New</option>
                                <option value="3">Qualified</option>
                                <option value="4">Lost</option>
                            </select>
                        </div>
                        <div class="col-md-2 form-group">
                            <label for="mode">Mode</label>
                            <select name="mode" id="mode" class="form-control basic-select2">
                                <option value="">Select Mode</option>
                                <?php
                                    foreach($this->appointmentMode as $key=>$mode):
                                        echo '<option value="'.$mode.'">'.$mode.'</option>';
                                    endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="from_date">Date</label>
                            <div class="input-group">
                                <input type="date" name="from_date" id="from_date" class="form-control" value="<?=$startDate?>"/>  
                                <input type="date" name="to_date" id="to_date" class="form-control" value="<?=$endDate?>"/>
                                <button type="button" class="btn waves-effect waves-light btn-success loadData" data-pdf="0" title="Load Data">
                                    <i class="fas fa-sync-alt"></i> Load
                                </button>	
                                <button type="button" class="btn waves-effect waves-light btn-warning float-right loadData" data-pdf="1" title="Load Data">
                                    <i class="fas fa-sync-alt"></i> PDF
                                </button>
                            </div>
                            <div class="error fromDate"></div>
                            <div class="error toDate"></div>
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
                                    <thead id="theadData" class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Party Name</th>
											<th>Sales Executives</th>
											<th>Mode</th>
											<th>Business Type</th>
											<th>Contact Person</th>
                                            <th>Contact No</th>
                                            <th>Whatsapp No</th>
                                            <th>Email</th>
                                            <th>GSTIN</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Country</th>
                                            <th>Address</th>
                                            <th>Created By</th>
                                            <th>Created Date</th>
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
    setTimeout(function(){$(".loadData").trigger('click');},500);
    
    $(document).on('click','.loadData',function(e){
		$(".error").html("");
		var valid = 1;
        var is_pdf = $(this).data('pdf');
        var executive_id = $("#executive_id").val();
        var business_type = $("#business_type").val();
        var lead_stage = $("#lead_stage").val();
        var mode = $("#mode").val();
        var from_date = $('#from_date').val();
	    var to_date = $('#to_date').val();
        if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
	    if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
	    if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}

        var postData = {executive_id:executive_id,from_date:from_date,to_date:to_date,business_type:business_type,lead_stage:lead_stage,mode:mode,is_pdf:is_pdf};
		if(valid){
            if(is_pdf == 0){
                $.ajax({
                    url: base_url + controller + '/getCustomerDetailList',
                    data: postData,
                    type: "POST",
                    dataType:'json',
                    success:function(data){
                        $("#reportTable").DataTable().clear().destroy();
                        $("#tbodyData").html(data.tbody);
                        reportTable();
                    }
                });
            }else{
                var url = base_url + controller + '/getCustomerDetailList/' + encodeURIComponent(window.btoa(JSON.stringify(postData)));
                window.open(url);
            }
        }
    });
});
</script>