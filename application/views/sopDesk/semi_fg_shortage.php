<?php $this->load->view('includes/header'); ?>
<style>li.nav-item{padding:0px 3px;}.process-tags span{font-size:0.75rem;color:#000;box-shadow: 0px 1px 1px rgba(9, 30, 66, 0.25), 0px 0px 1px 1px rgba(9, 30, 66, 0.13);}.prcList{padding:0.4rem;}</style>
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
                            <table id='planTable' class="table table-bordered ssTable ssTable-cf" data-url='/getSemiFgShortageDtRows/<?=$prc_type?>'></table>
                            </div>
                        </div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script src="<?=base_url()?>assets/js/custom/sop_desk.js?v=<?=time()?>"></script>
<script>
$(document).ready(function() {
	initbulkPIButton();
    $(document).on('click', '.BulkRequest', function() {
		if ($(this).attr('id') == "masterSelect") {
			if ($(this).prop('checked') == true) {
				$(".bulkPI").show();
				$("input[name='ref_id[]']").prop('checked', true);
			} else {
				$(".bulkPI").hide();
				$("input[name='ref_id[]']").prop('checked', false);
			}
		} else {
			if ($("input[name='ref_id[]']").not(':checked').length != $("input[name='ref_id[]']").length) {
				$(".bulkPI").show();
				$("#masterSelect").prop('checked', false);
			} else {
				$(".bulkPI").hide();
			}

			if ($("input[name='ref_id[]']:checked").length == $("input[name='ref_id[]']").length) {
				$("#masterSelect").prop('checked', true);
				$(".bulkPI").show();
			}
			else{$("#masterSelect").prop('checked', false);}
		}
	});
	
	$(document).on('click', '.bulkPI', function() {
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
function reportTable()
{
	var reportTable = $('#sopTable').DataTable( 
	{
		responsive: true,
		scrollY: '55vh',
        scrollCollapse: true,
		"scrollX": true,
		"scrollCollapse":true,
		"autoWidth" : false,
		order:[],
		"columnDefs": 	[
							{ type: 'natural', targets: 0 },
							{ orderable: false, targets: "_all" }, 
							{ className: "text-left", targets: [0,1] }, 
							{ className: "text-center", "targets": "_all" } 
						],
		pageLength:25,
		language: { search: "" },
		lengthMenu: [
            [ 10, 25, 50, 100, -1 ],[ '10 rows', '25 rows', '50 rows', '100 rows', 'Show all' ]
        ],
		dom: "<'row'<'col-sm-7'B><'col-sm-5'f>>" +"<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
		buttons: [ 'pageLength', 'excel', {text: 'Refresh',action: function ( e, dt, node, config ) {loadData();}}]
	});
	reportTable.buttons().container().appendTo( '#sopTable_wrapper toolbar' );
	$('.dataTables_filter .form-control-sm').css("width","97%");
	$('.dataTables_filter .form-control-sm').attr("placeholder","Search.....");
	$('.dataTables_filter').css("text-align","left");
	$('.dataTables_filter label').css("display","block");
	$('.btn-group>.btn:first-child').css("border-top-right-radius","0");
	$('.btn-group>.btn:first-child').css("border-bottom-right-radius","0");
	return reportTable;
}

function loadData(){
    $.ajax({
        url: base_url + controller + '/getSemiShortageData',
        data: {},
        type: "POST",
        dataType:'json',
        success:function(data){
            $("#sopTable").DataTable().clear().destroy();
            $("#tbodyData").html(data.tbody);
            reportTable();
        }
    });
}

function getSopResponse(data,formId="addPRC"){
	if(data.status==1){
        $('#'+formId)[0].reset();closeModal(formId);
        loadData();
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            Swal.fire({ icon: 'error', title: data.message });
        }			
    }
}

function initbulkPIButton() {
	var bulkPIBtn = '<button class="btn btn-outline-dark bulkPI" tabindex="0" aria-controls="planTable" type="button"><span>Bulk Purchase Request</span></button>';
	$("#planTable_wrapper .dt-buttons").append(bulkPIBtn);
	$(".bulkPI").hide();
}

function getIndentResponse(data,formId=""){
    if(data.status==1){
		initTable();
        if(formId){
            $('#'+formId)[0].reset(); closeModal(formId);
        }
        Swal.fire({ icon: 'success', title: data.message});
		setTimeout(function(){ 
			initbulkPIButton();
		}, 50);
		
    }else{
            if(typeof data.message === "object"){
                $(".error").html("");
                $.each( data.message, function( key, value ) {$("."+key).html(value);});
            }else{
                Swal.fire({ icon: 'error', title: data.message });
            }			
        }	
    
}
</script>
