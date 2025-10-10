<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
                    <div class="float-start">
                        <ul class="nav nav-pills">
                            <li class="nav-item"> 
                                <button onclick="statusTab('dispatchOrderTable',3,'getSalesDtHeader','pendingSO');" id="pending_sales_order" class="nav-tab btn waves-effect waves-light btn-outline-primary active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending SO</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('dispatchOrderTable',0,'getSalesDtHeader','dispatchOrder');" id="pending_do" class="nav-tab btn waves-effect waves-light btn-outline-danger" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending DO</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('dispatchOrderTable',1,'getSalesDtHeader','dispatchOrder');" id="complete_do" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Ready to Dispatch</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('dispatchOrderTable',2,'getSalesDtHeader','dispatchOrder');" id="dispatched" class="nav-tab btn waves-effect waves-light btn-outline-info" style="outline:0px" data-toggle="tab" aria-expanded="false">Dispatched</button> 
                            </li>
                        </ul>
					</div>
					<div class="float-end">
                        <?php
                            $addParam = "{'modal_id' : 'bs-right-xl-modal', 'call_function':'addDispatchOrder', 'form_id' : 'dispatchOrder', 'title' : 'Add Dispatch Order'}";
                        ?>
                        <button type="button" class="btn waves-effect waves-light btn-outline-dark permission-write float-right press-add-btn" onclick="modalAction(<?=$addParam?>);"><i class="fa fa-plus"></i> Add Order</button>
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
                                <table id='dispatchOrderTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows/'></table>
                            </div>
                        </div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>