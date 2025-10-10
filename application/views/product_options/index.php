<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-8">
				<div class="page-title-box">
					<h4 class="page-title">Product Options</h4>
				</div>
			</div>
			
			<div class="col-sm-4">
				<select id="item_type_bom" class="form-control select2">
					<option value="1">Finish Goods</option>
					<option value="4">Semi Finish</option>
				</select>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='productOptionTable' class="table table-bordered ssTable" data-url='/getDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script src="<?php echo base_url();?>assets/js/custom/product.js"></script>
<script>
$(document).ready(function(){
	$(document).on('change','#item_type_bom', function() {
		var item_type = $(this).val();
		var postData = {item_type:item_type};
		initTable(postData);
	});
});
</script>
