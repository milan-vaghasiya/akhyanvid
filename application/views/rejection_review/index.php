<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
                    <div class="float-start">
                        <ul class="nav nav-pills">
                            <li class="nav-item"> 
                                <a href="<?=base_url($headData->controller.'/index');?>" class=" btn waves-effect waves-light btn-outline-info active" style="outline:0px"  aria-expanded="false">Pending</a>
                            </li>
                            <li class="nav-item"> 
                                <a href="<?=base_url($headData->controller.'/reviewedIndex');?>" class=" btn waves-effect waves-light btn-outline-info " style="outline:0px"  aria-expanded="false">Reviewed</a>
                            </li>
                        </ul>
                    </div>
					<div class="float-end">
                        <!-- <select name="source" id="source" class="form-control">
                            <option value="MFG">Manufacturing</option>
                            <option value="FIR">Final Inspection</option>
                        </select> -->
					</div>
				</div>
            </div>
			
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='cftTable' class="table table-bordered ssTable" data-url='/getDTRows/'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
function cftTab(tableId,entry_type,operation_type,srnoPosition=0){
    $("#"+tableId).attr("data-url",'/getDTRows/'+entry_type+'/'+operation_type);
    ssTable.state.clear();initTable(1);
}
$(document).ready(function() {
    $(document).on("change", "#source", function() {
        var source = $("#source").val();
        $("#cftTable").attr("data-url",'/getDTRows/'+source);
        ssTable.state.clear();initTable(1);
    });


    $(document).on("change", "#rr_stage", function() {
        var process_id = $("#rr_stage").val();
        var prc_id = $("#prc_id").val();
        var log_id = $("#log_id").val();
        var rr_type = $("#rr_type").val();
        $("#rr_by").html("");
        if (process_id) {
            $.ajax({
                url: base_url  + 'rejectionReview/getRRByOptions',
                type: 'post',
                data: {
                    process_id: process_id,
                    prc_id: prc_id,
                    rr_type: rr_type,
                },
                dataType: 'json',
                success: function(data) {
                    $("#rr_by").html(data.rejOption);
                }
            });
        } 
        $("#rr_by").select2();
    });

    $(document).on("change", "#rr_type", function() {
        $("#rr_stage").trigger("change");
    });
});

</script>
