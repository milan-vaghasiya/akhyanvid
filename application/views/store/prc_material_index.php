<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-start">
                        <ul class="nav nav-pills">
                            <li class="nav-item"> 
                                <a href="<?=base_url($headData->controller."/prcMaterial")?>" class="btn waves-effect waves-light btn-outline-info active mr-1"> PRC Material </a> 
                            </li>
                            <li class="nav-item"> 
                                <a href="<?=base_url($headData->controller."/issueRequisition/1/1")?>" class="btn waves-effect waves-light btn-outline-info mr-1"> Pending </a> 
                            </li>
                            <li class="nav-item"> 
                                <a href="<?=base_url($headData->controller."/issueRequisition/2/2")?>" class="btn waves-effect waves-light btn-outline-info mr-1"> Issued </a>
                            </li>
                        </ul>
                    </div>
                    <div class="float-end">
                        <?php
                            $addParam = "{'modal_id' : 'bs-right-lg-modal', 'call_function':'addIssueRequisition', 'form_id' : 'addIssueRequisition', 'title' : 'Material Issue' , 'fnsave' : 'saveIssueRequisition'}";
                        ?>
                        <button type="button" class="btn waves-effect waves-light btn-outline-dark float-right press-add-btn" onclick="modalAction(<?=$addParam?>);"><i class="fa fa-plus"></i> Material Issue</button>
					</div>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='issueRequisitionTable' class="table table-bordered ssTable" data-url='/getPrcMaterialDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script>
    function storeIssueMaterial(postData){
        setPlaceHolder();
        var formId = postData.formId;
        var fnsave = postData.fnsave || "save";
        var controllerName = postData.controller || controller;

        var form = $('#'+formId)[0];
        var fd = new FormData(form);
        $.ajax({
            url: base_url + controllerName + '/' + fnsave,
            data:fd,
            type: "POST",
            processData:false,
            contentType:false,
            dataType:"json",
        }).done(function(data){
            if(data.status==1){
                initTable(); 
                $("#item_id").val("");
                $("#required_qty").val("");
                $("#emp_dept_id").val("");
                $("#issued_to").val("");
                $("#tbodyData").html('<tr><th colspan="5" class="text-center">No Data Available</th></tr>');
                initSelect2();	
                Swal.fire({ icon: 'success', title: data.message});
            }else{
                if(typeof data.message === "object"){
                    $(".error").html("");
                    $.each( data.message, function( key, value ) {$("."+key).html(value);});
                }else{
                    Swal.fire({ icon: 'error', title: data.message });
                }			
            }				
        });
    }
</script>