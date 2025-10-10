<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-end">
                        <?php
                            $addParam = "{'postData':{'id' : ''},'modal_id' : 'bs-right-md-modal', 'call_function':'addPurchaseRequest', 'form_id' : 'addPurchaseRequest', 'title' : 'Add Purchase Request'}";
                        ?>
                        <button type="button" class="btn waves-effect waves-light btn-outline-dark float-right press-add-btn" onclick="modalAction(<?=$addParam?>);"><i class="fa fa-plus"></i> Add Purchase Request</button>
					</div>
                    <ul class="nav nav-pills">
                        <li class="nav-item"> <button onclick="statusTab('qcprTable',0);" class=" btn waves-effect waves-light btn-outline-info active mr-1" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Pending</button> </li>
                        <li class="nav-item"> <button onclick="statusTab('qcprTable',1);" class=" btn waves-effect waves-light btn-outline-info mr-1" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Completed</button> </li>
                        <li class="nav-item"> <button onclick="statusTab('qcprTable',2);" class=" btn waves-effect waves-light btn-outline-info" style="outline:0px" data-bs-toggle="tab" aria-expanded="false">Close</button> </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='qcprTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>       
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
    function tabStatus(tableId, status) {
		$("#" + tableId).attr("data-url", '/getDTRows/' + status);
		ssTable.state.clear();
		initTable();
	}
</script>