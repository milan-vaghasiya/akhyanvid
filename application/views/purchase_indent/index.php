<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
                    <div class="float-start">
					    <ul class="nav nav-pills">
                            <li class="nav-item"> 
                                <button onclick="statusTab('purchaseIndentTable',1);" id="pending_pi" class="nav-tab btn waves-effect waves-light btn-outline-danger active" style="outline:0px" data-toggle="tab" aria-expanded="false">Pending</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('purchaseIndentTable',2);" id="complete_pi" class="nav-tab btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Completed</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('purchaseIndentTable',3);" id="close_pi" class="nav-tab btn waves-effect waves-light btn-outline-primary" style="outline:0px" data-toggle="tab" aria-expanded="false"> Closed</button> 
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
                                <table id='purchaseIndentTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows'></table>
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
	$(document).ready(function() {
		initbulkPOButton();
		$(document).on('click', '.BulkRequest', function() {
		if ($(this).attr('id') == "masterSelect") {
			if ($(this).prop('checked') == true) {
				$(".bulkPO").show();
				$("input[name='ref_id[]']").prop('checked', true);
			} else {
				$(".bulkPO").hide();
				$("input[name='ref_id[]']").prop('checked', false);
			}
		} else {
			if ($("input[name='ref_id[]']").not(':checked').length != $("input[name='ref_id[]']").length) {
				$(".bulkPO").show();
				$("#masterSelect").prop('checked', false);
			} else {
				$(".bulkPO").hide();
			}

			if ($("input[name='ref_id[]']:checked").length == $("input[name='ref_id[]']").length) {
				$("#masterSelect").prop('checked', true);
				$(".bulkPO").show();
			}
			else{$("#masterSelect").prop('checked', false);}
		}
	});
	
	$(document).on('click', '.bulkPO', function() {
		var ref_id = [];
		$("input[name='ref_id[]']:checked").each(function() {
			ref_id.push(this.value);
		});
		var ids = ref_id.join("~");
		var send_data = {
			ids
		};
		Swal.fire({
			title: 'Are you sure?',
			text: 'Are you sure want to generate PO?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Do it!',
		}).then(function(result) {
			if (result.isConfirmed){				
				window.open(base_url + 'purchaseOrders/addPOFromRequest/' + ids, '_self');
			}
		});
	});

		
	});
	// function initBulkInspectionButton() {
	// 	var bulkPOBtn = '<button class="btn btn-outline-primary bulkPO" tabindex="0" aria-controls="purchaseRequestTable" type="button"><span>Bulk PO</span></button>';
	// 	var bulkEnqBtn = '<button class="btn btn-outline-primary bulkEnq" tabindex="0" aria-controls="purchaseRequestTable" type="button"><span>Bulk Enquiry</span></button>';
	// 	$("#purchaseRequestTable_wrapper .dt-buttons").append(bulkPOBtn);
	// 	$("#purchaseRequestTable_wrapper .dt-buttons").append(bulkEnqBtn);
	// 	$(".bulkPO").hide();
	// 	$(".bulkEnq").hide();
	// }

	function initbulkPOButton() {
	var bulkPOBtn = '<button class="btn btn-outline-dark bulkPO" tabindex="0" aria-controls="purchaseIndentTable" type="button"><span>Bulk PO</span></button>';
	$("#purchaseIndentTable_wrapper .dt-buttons").append(bulkPOBtn);
	$(".bulkPO").hide();
}

</script>