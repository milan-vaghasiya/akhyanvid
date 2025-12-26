<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box"> 
					<div class="float-end">
                        <?php
                            $addParam = "{'postData':{'type' : 1},'modal_id' : 'bs-right-md-modal', 'call_function':'addSelectOption', 'form_id' : 'addSelectOption', 'title' : 'Add Option'}";
                        ?>
                        <button type="button" id="addbtn" class="btn waves-effect waves-light btn-outline-dark permission-write float-right press-add-btn" onclick="modalAction(<?=$addParam?>);"><i class="fa fa-plus"></i> Add Option</button>
					</div>
					<ul class="nav nav-pills">
						<li><button onclick="statusTab('selectOptionTable',1);" data-type="1" class="btn btn-outline-info statusTabChange active" data-bs-toggle="tab">Service Reason</button></li>						
						<li><button onclick="statusTab('selectOptionTable',2);" data-type="2" class="btn btn-outline-info statusTabChange" data-bs-toggle="tab">Project Specification</button></li>
					</ul>
					
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='selectOptionTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows'></table>
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
		var type = $(this).data('type');
		$("#addbtn").attr("onclick","modalAction({'postData':{'type' : '"+type+"'},'modal_id' : 'bs-right-md-modal', 'call_function':'addSelectOption', 'form_id' : 'addSelectOption', 'title' : 'Add Option'})");
		$('#addbtn').data('form_title','Add '+$(this).text());
	});
});
</script>
