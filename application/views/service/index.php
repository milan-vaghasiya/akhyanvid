<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
                    <div class="float-start">
					    <ul class="nav nav-pills">
                            <li class="nav-item"> 
                                <button onclick="statusTab('serviceTable',1);" id="pending_service" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('serviceTable',2);" id="approve_service" class="nav-tab btn waves-effect waves-light btn-outline-warning" style="outline:0px" data-toggle="tab" aria-expanded="false">Approved</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('serviceTable',3);" id="response_service" class="nav-tab btn waves-effect waves-light btn-outline-info" style="outline:0px" data-toggle="tab" aria-expanded="false">Accepted (Tech.)</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('serviceTable',4);" id="progress_service" class="nav-tab btn waves-effect waves-light btn-outline-primary" style="outline:0px" data-toggle="tab" aria-expanded="false">In Progress</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('serviceTable',5);" id="close_service" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('serviceTable',6);" id="hold_service" class="nav-tab btn waves-effect waves-light btn-outline-secondary " style="outline:0px" data-toggle="tab" aria-expanded="false">On Hold</button> 
                            </li>
                        </ul>
					</div>
					<div class="float-end">
                        <?php
                            $addParam = "{'modal_id' : 'bs-right-lg-modal', 'call_function':'addService', 'form_id' : 'addService', 'title' : 'Add Service'}";
                        ?>
                        <button type="button" class="btn waves-effect waves-light btn-outline-dark permission-write float-right press-add-btn" onclick="modalAction(<?=$addParam?>);"><i class="fa fa-plus"></i> Add Service</button>
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
                                <table id='serviceTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows'></table>
                            </div>
                        </div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>

