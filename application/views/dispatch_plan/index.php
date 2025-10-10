<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
                    <div class="float-start">
					    <ul class="nav nav-pills">
                            <li class="nav-item"> 
                                <a href="<?=base_url($headData->controller)?>"  id="pending_so" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" aria-expanded="false">Pending SO</a> 
                            </li>
                            <li class="nav-item"> 
                                <a  href="<?=base_url($headData->controller.'/dispatchPlan/1')?>" id="complete_so" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" aria-expanded="false">Pend. Assembly Order</a> 
                            </li>
                            <li class="nav-item"> 
                                <a  href="<?=base_url($headData->controller.'/dispatchPlan/2')?>" id="complete_so" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" aria-expanded="false">Inprocess Assembly Order</a> 
                            </li>
                            <li class="nav-item"> 
                                <a  href="<?=base_url($headData->controller.'/dispatchPlan/3')?>" id="close_so" class="nav-tab btn waves-effect waves-light btn-outline-primary" style="outline:0px" aria-expanded="false">Completed Assembly Order</a> 
                            </li>
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
                                <table id='planTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows'></table>
                            </div>
                        </div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>