
<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4 form-group"></div>
                            <div class="col-md-2 form-group">
                                <label for="">Customer </label>
                                <select id="party_id" name="party_id" class="form-control basic-select2">
                                    <option value="0">All Customer</option>
                                    <?php
                                    if(!empty($partyList)){
                                        foreach($partyList as $row){
                                            ?>
                                            <option value="<?=$row->id?>"><?=$row->party_name?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                               <label for="">Business Type </label>
                                  <select name="business_type" id="business_type" class="form-control  basic-select2">
                                    <option value ="">Select Business Type</option>
                                    <option value="Builder">Builder</option>
                                    <option value="Individuals">Individuals</option>
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
                                            <th>Date</th>
                                            <th>Executive Name</th>
                                            <th>Party Name</th>
                                            <th>Business Type</th>
                                            <th>FollowUp Massage</th>
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
		var party_id = $('#party_id').val();
		var business_type = $('#business_type').val();
        var from_date = $('#from_date').val();
	    var to_date = $('#to_date').val();
        if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
	    if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
	    if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}

		if(valid){
            $.ajax({
                url: base_url + controller + '/getFollowUpRegister',
                data: {party_id:party_id, business_type:business_type,from_date:from_date, to_date:to_date},
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
</script>2

<?php $this->load->view('includes/footer'); ?>