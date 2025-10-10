<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
                        <?php
                            // if()
                            $addParam = "{'postData':{'work_type' : 1},'modal_id' : 'bs-right-md-modal', 'call_function':'addWorkInstructions', 'form_id' : 'addWorkInstructions', 'title' : 'Add Work Instructions'}";
                        ?>
                        <button type="button" id="addbtn" class="btn waves-effect waves-light btn-outline-dark permission-write float-right press-add-btn" onclick="modalAction(<?=$addParam?>);"><i class="fa fa-plus"></i> Add Work Instructions</button>
					</div>
                    <ul class="nav nav-pills">
						<li><button onclick="statusTab('workInstructionsTable',1);" data-work_type="1"  class="btn btn-outline-info statusTabChange active" data-bs-toggle="tab">Work Instruction</button></li>
						<li><button onclick="statusTab('workInstructionsTable',2);" data-work_type="2"  class="btn btn-outline-info statusTabChange " data-bs-toggle="tab">Work Plan</button></li>
						<li><button onclick="statusTab('workInstructionsTable',3);" data-work_type="3"  class="btn btn-outline-info statusTabChange " data-bs-toggle="tab">Work Steps</button></li>
					</ul>
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id='workInstructionsTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows'></table>
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
	$(document).on('click',".statusTabChange",function(){
		var work_type = $(this).data('work_type');
		var titleText = $(this).text();
        //var titleText = (work_type == 1) ? "Work Instructions" : "Work Plan";
		$("#addbtn").attr("onclick","modalAction({'postData':{'work_type' : '"+work_type+"'},'modal_id' : 'bs-right-md-modal', 'call_function':'addWorkInstructions', 'form_id' : 'addWorkInstructions', 'title' :' Add " +titleText+" '})");
		$('#addbtn').html('<i class="fa fa-plus"></i> Add ' + titleText);
        
	});
});
</script>