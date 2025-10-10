<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
                    <div class="float-start">
                        <ul class="nav nav-pills">
                            <li class="nav-item"> 
                                <a href="<?=base_url($headData->controller.'/index');?>" class=" btn waves-effect waves-light btn-outline-info " style="outline:0px"  aria-expanded="false">Pending</a>
                            </li>
                            <li class="nav-item"> 
                                <a href="<?=base_url($headData->controller.'/reviewedIndex');?>" class=" btn waves-effect waves-light btn-outline-info active" style="outline:0px"  aria-expanded="false">Reviewed</a>
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='cftTable' class="table table-bordered ssTable" data-url='/getReviewDTRows/'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script>
     $(document).on("change", "#source", function() {
        var source = $("#source").val();
        $("#cftTable").attr("data-url",'/getReviewDTRows/'+source);
        ssTable.state.clear();initTable(1);
    });
</script>