<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
                        <?php
                            $addParam = "{'postData':{'item_type':".$item_type."},'modal_id' : 'bs-right-lg-modal', 'call_function':'addItem', 'form_id' : 'addItem', 'title' : 'Add ".$this->itemTypes[$item_type]."'}";
                        ?>
                        <button type="button" class="btn waves-effect waves-light btn-outline-dark float-right permission-write press-add-btn" onclick="modalAction(<?=$addParam?>);"><i class="fa fa-plus"></i> Add <?=$this->itemTypes[$item_type]?></button>
                    </div>
                    <h4 class="card-title"><?=$this->itemTypes[$item_type]?></h4>
				</div>
            </div>
		</div>
        <div class="row">
            <div class="col-12">
				<div class="col-12">
					<div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id='itemTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows/<?=$item_type?>'></table>
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
    $('body').on('click', '.importExcel', function() {
        $(".msg").html("");
        $(this).attr("disabled", "disabled");
        var fd = new FormData();
        fd.append("item_excel", $("#item_excel")[0].files[0]);
        $.ajax({
            url: base_url + controller + '/importExcel',
            data: fd,
            type: "POST",
            processData: false,
            contentType: false,
            dataType: "json",
        }).done(function(data) {
            if (data.status === 0) {
                $(".msg").html("");
                var error='';
                $.each(data.message, function(key, value) {
                    error+=' '+value;
                });
                $(".msg").html(error);
            } else if (data.status == 1) {
                toastr.success(data.message, 'Success', {
                    "showMethod": "slideDown",
                    "hideMethod": "slideUp",
                    "closeButton": true,
                    positionClass: 'toastr toast-bottom-center',
                    containerId: 'toast-bottom-center',
                    "progressBar": true
                });
                initTable();
            }
         
            $(this).removeAttr("disabled");
            $("#item_excel").val(null);
        });
    });
});
</script>