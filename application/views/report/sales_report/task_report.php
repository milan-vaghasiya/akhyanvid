<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end" style="width:90%;">
						<div class="input-group">
							<div class="input-group-append" style="width:15%;"><label>Group</label></div>
							<div class="input-group-append" style="width:15%;"><label>Assigned To</label></div>
							<div class="input-group-append" style="width:15%;"><label>Created By</label></div>
							<div class="input-group-append" style="width:15%;"><label>Status</label></div>
							<div class="input-group-append" style="width:17%;"><label>Start Date</label></div>
							<div class="input-group-append" style="width:10%;"><label>End Date</label></div>
							<div class="input-group-append" style="width:13%;"><label>&nbsp;</label></div>
						</div>
					    <div class="input-group">
							<div class="input-group-append" style="width:15%;">
								<select name="group_id" id="group_id" class="form-control basic-select2">
									<option value="ALL">All Group</option>
									<?php
										if(!empty($groupList)){
											foreach($groupList as $row){
												echo '<option value="'.$row->id.'">'.$row->group_name.'</option>';
											}
										}
									?>
								</select>
							</div>
							<div class="input-group-append" style="width:15%;">
								<select name="assign_to" id="assign_to" class="form-control basic-select2">
									<option value="">All Assign To</option>
								</select>
							</div>
							<div class="input-group-append" style="width:15%;">
								<select name="created_by" id="created_by" class="form-control basic-select2">
									<option value="">All Created By</option>
									<option value=<?=(!empty($this->loginId) ? $this->loginId : '')?>>Self</option>
									<?php
										if(!empty($empData)){
											foreach($empData as $row){
												echo '<option value="'.$row->id.'">[ '.$row->emp_code.' ] '.$row->emp_name.'</option>';
											}
										}
									?>
								</select>
							</div>
							<div class="input-group-append" style="width:15%;">
								<select name="status" id="status" class="form-control basic-select2 req">
									<option value="ALL">All Status</option>
									<option value="1">Pending</option>
									<option value="2">Completed</option>
								</select>
							</div>
							<input type="date" name="from_date" id="from_date" class="form-control" value="<?=$startDate?>" style="width:10%;"/>                                    
							<input type="date" name="to_date" id="to_date" class="form-control" value="<?=$endDate?>" style="width:10%;"/>
							<div class="input-group-append">
								<button type="button" class="btn waves-effect waves-light btn-success float-right loadData" title="Load Data">
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
                                            <th>Task No.</th>
                                            <th>Group</th>
                                            <th>Assign To</th>
                                            <th>Title</th>
                                            <th>Notes</th>
                                            <th>Repeat Type</th>
                                            <th>Due Date</th>
                                            <th>Complete Date</th>
                                            <th>Delay Days</th>
                                            <th>Status</th>
                                            <th>Created By</th>
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
        var status = $("#status").val();
        var assign_to = $("#assign_to").val();
        var group_id = $("#group_id").val();
        var created_by = $("#created_by").val();
        var from_date = $('#from_date').val();
	    var to_date = $('#to_date').val();
        if($("#from_date").val() == ""){$(".fromDate").html("From Date is required.");valid=0;}
	    if($("#to_date").val() == ""){$(".toDate").html("To Date is required.");valid=0;}
	    if($("#to_date").val() < $("#from_date").val()){$(".toDate").html("Invalid Date.");valid=0;}

		if(valid){
            $.ajax({
                url: base_url + controller + '/getTaskManager',
                data: {assign_to:assign_to,group_id:group_id,from_date:from_date,to_date:to_date,status:status,created_by:created_by},
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
    
    setTimeout(function(){ $("#group_id").trigger("change"); }, 50);
	
	$(document).off('change').on('change',"#group_id",function(){
		let group_id = $("#group_id").data('selected') || $("#group_id").val();
		let assign_to = $("#assign_to").data('selected') || $("#assign_to").val();
		$("#group_id").data('selected','');
		
		$.ajax({
			url: base_url  + 'taskManager/getMemberList',
			data:{group_id : group_id, assign_to:assign_to},
			type: "POST",
			dataType:"json",
		}).done(function(response){
			$("#assign_to").html(response.memberList);
			$("#assign_to").val(assign_to);
			initSelect2();
		});
	});
	
	$(document).on('change',"#assign_to",function(){
		$("#assign_to").data('selected','');
	});
});
</script>