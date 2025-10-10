<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id='forecastTable' class="table table-bordered ssTable ssTable-cf" data-url='/getPurchaseShortageDtRows'></table>
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
				$(".bulkReq").show();
				$("input[name='ref_id[]']").prop('checked', true);
			} else {
				$(".bulkReq").hide();
				$("input[name='ref_id[]']").prop('checked', false);
			}
		} else {
			if ($("input[name='ref_id[]']").not(':checked').length != $("input[name='ref_id[]']").length) {
				$(".bulkReq").show();
				$("#masterSelect").prop('checked', false);
			} else {
				$(".bulkReq").hide();
			}

			if ($("input[name='ref_id[]']:checked").length == $("input[name='ref_id[]']").length) {
				$("#masterSelect").prop('checked', true);
				$(".bulkReq").show();
			}
			else{$("#masterSelect").prop('checked', false);}
		}
	});

	$(document).on('click', '.bulkReq', function() {
		var ref_id = []; var qty = []; var sendData = [];
		$("input[name='ref_id[]']:checked").each(function() {
			ref_id = $(this).val();
			qty = $(this).data('qty');
			item_name = $(this).data('item_name');
			sendData.push({ item_id:ref_id, qty:qty,item_name:item_name });
		});
		if(sendData.length > 0){
			var postData = encodeURIComponent(JSON.stringify(sendData));
			
			Swal.fire({
				title: 'Are you sure?',
				text: 'Are you sure want to generate Request?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Do it!',
			}).then(function(result) {
				if (result.isConfirmed){				
					var piParam = {postData:{itemData:sendData},modal_id : 'bs-right-lg-modal', call_function:'addPurchaseRequest',form_id: 'addPurchaseRequest',title: 'Add Purchase Request',controller:'purchaseIndent'};		
					modalAction(piParam);	
				}
			});
		}
	});
});

function initbulkPOButton() {
	var bulkReqBtn = '<button class="btn btn-outline-dark bulkReq" tabindex="0" aria-controls="forecastTable" type="button"><span>Bulk REQ</span></button>';
	$("#forecastTable_wrapper .dt-buttons").append(bulkReqBtn);
	$(".bulkReq").hide();
}
</script>