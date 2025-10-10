<?php $this->load->view('includes/header'); ?>

<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-start">
                        <ul class="nav nav-pills">
                            <!-- <li class="nav-item"> 
                                <button onclick="statusTab('salesInvoiceTable',2,'getSalesDtHeader','pendingSO');" id="pending_so" class="nav-tab btn waves-effect waves-light btn-outline-primary active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending SO</button> 
                            </li> -->
                            <!-- <li class="nav-item"> 
                                <button onclick="statusTab('salesInvoiceTable',3,'getSalesDtHeader','pendingDO');" id="pending_do" class="nav-tab btn waves-effect waves-light btn-outline-danger" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending DO</button> 
                            </li> -->
                            <li class="nav-item"> 
                                <button onclick="statusTab('salesInvoiceTable',4,'getSalesDtHeader','pendingChallan');" id="pending_dch" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending DCH</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('salesInvoiceTable',0,'getAccountingDtHeader','salesInvoice');" id="complete_dch" class="nav-tab btn waves-effect waves-light btn-outline-info active" style="outline:0px" data-toggle="tab" aria-expanded="false">Invoice List</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('salesInvoiceTable',1,'getAccountingDtHeader','salesInvoice');" id="complete_dch" class="nav-tab btn waves-effect waves-light btn-outline-info" style="outline:0px" data-toggle="tab" aria-expanded="false">Canceled Inv.</button> 
                            </li>
                        </ul>
					</div>
					<div class="float-end">
					    <a href="javascript:void(0)" class="btn waves-effect waves-light btn-outline-dark float-right permission-write press-add-btn" data-txt_editor="conditions"
                        onclick="window.location.href='<?=base_url($headData->controller.'/addInvoice')?>'"><i class="fa fa-plus"></i> Add Invoice</a>
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
                                <table id='salesInvoiceTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows'></table>
                            </div>
                        </div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script src="<?=base_url()?>assets/js/custom/typehead.js"></script> 
<script src="<?=base_url()?>assets/js/custom/e-bill.js?v=<?=time()?>"></script>