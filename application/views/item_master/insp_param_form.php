<form id="inspection" data-res_function="inspectionHtml">
    <div class="row">
        <input type="hidden" name="id" id="id" class="id" value="" />
        <input type="hidden" name="item_id" id="item_id" class="item_id" value="<?=$item_id?>" />

        <div class="col-md-3 form-group">
            <label for="rev_no">Revision No.</label>
            <select name="rev_no" id="rev_no" class="form-control select2 req">
                <option value="">Select Revision No.</option>
                <?php
                    foreach($revisionList as $row):
                        echo '<option value="'.$row->rev_no.'">'.$row->rev_no.'</option>';
                    endforeach;
                ?>
            </select>
            <div class="error rev_no"></div>
        </div>
        <div class="col-md-3 form-group">
            <label for="process_id">Process</label>
            <select name="process_id" id="process_id" class="form-control select2 req">
                <option value="">Select Process</option>
                <?php
                    foreach($processList as $row):
                        echo '<option value="'.$row->process_id.'">'.$row->process_name.'</option>';
                    endforeach;
                ?>
            </select>
            <div class="error process_id"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="parameter">Parameter</label>
            <input type="text" name="parameter" id="parameter" class="form-control req" value="" />
        </div>
        <div class="col-md-6 form-group">
            <label for="specification">Specification</label>
            <input type="text" name="specification" id="specification" class="form-control req" value="" />
        </div>
        <div class="col-md-6 form-group">
            <label for="instrument">Instrument</label>
            <input type="text" name="instrument" id="instrument" class="form-control">
        </div>
        <div class="col-md-10 form-group">
            <label for="control_method">Control Method</label>
            <select name="control_method[]" id="control_method" class="form-control select2 req" multiple>
                <option value="IIR">IIR (Incoming Inspection Report)</option>
                <option value="SAR">SAR (Setup Approval Report)</option>
                <option value="IPR">IPR (Inprocess Inspection Report)</option>
                <option value="FIR">FIR (Final Inspection Report)</option>
            </select>
            <div class="error control_method"></div>
        </div>
        
        <div class="col-md-2 form-group">
            <?php $param = "{'formId':'inspection','fnsave':'saveInspection','controller':'items','res_function':'inspectionHtml'}"; ?>
            <button type="button" class="btn waves-effect waves-light btn-outline-success float-right mt-25 save-form btn-block" onclick="customStore(<?=$param?>)"><i class="fa fa-check"></i> Save</button>
        </div>
    </div>
</form>
<hr>
<div class="row">
        <div class="col-md-4">
            <a href="<?= base_url($headData->controller . '/createProductInspExcel/' . $item_id.'/' ) ?>" class="btn btn-block btn-info bg-info-dark mr-2" target="_blank">
                <i class="fa fa-download"></i>&nbsp;&nbsp;
                <span class="btn-label">Download Excel&nbsp;&nbsp;<i class="fa fa-file-excel"></i></span>
            </a>
        </div>
        <div class="col-md-4">
            <input type="file" name="insp_excel" id="insp_excel" class="form-control float-left" />
            <h6 class="col-md-12 msg text-primary text-center mt-1"></h6>
        </div>
        <div class="col-md-4">
            <a href="javascript:void(0);" class="btn btn-block btn-success bg-success-dark ml-2 importProductExcel" type="button">
                <i class="fa fa-upload"></i>&nbsp;
                <span class="btn-label">Upload Excel &nbsp;<i class="fa fa-file-excel"></i></span>
            </a>
        </div>
    </div>
<hr>
    <div class="row">
        <div class="table-responsive">
            <table id="inspectionId" class="table table-bordered align-items-center fhTable">
                <thead class="thead-info">
                    <tr>
                        <th style="width:5%;">#</th>
                        <th>Rev No</th>
                        <th>Process</th>
                        <th>Parameter</th>
                        <th>Specification</th>
                        <th>Instrument</th>
                        <th>Control Method</th>
                        <th class="text-center" style="width:10%;">Action</th>
                    </tr>
                </thead>
                <tbody id="inspectionBody" class="scroll-tbody scrollable maxvh-60">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
var tbodyData = false;
$(document).ready(function(){
    if(!tbodyData){
        var postData = {'postData':{'item_id':$("#item_id").val()},'table_id':"inspectionId",'tbody_id':'inspectionBody','tfoot_id':'','fnget':'inspectionHtml'};
        getTransHtml(postData);
        tbodyData = true;
    }
    $(document).on('click', '.importProductExcel', function(e) {
            e.stopImmediatePropagation();e.preventDefault();
            $(this).attr("disabled", "disabled");
            var fd = new FormData();
            fd.append("insp_excel", $("#insp_excel")[0].files[0]);
            fd.append("item_id", $("#item_id").val());
            $.ajax({
                url: base_url + controller + '/importProductExcel',
                data: fd,
                type: "POST",
                processData: false,
                contentType: false,
                dataType: "json",
            }).done(function(data) {
                $(".msg").html(data.message);
                $(this).removeAttr("disabled");
                $("#insp_excel").val(null);
                if (data.status == 1) {
                    inspectionHtml(data);
                    // initTable(0);
                }
            });
        });
});

function inspectionHtml(data,formId="inspection"){ 
    if(data.status==1){
        // $('#'+formId)[0].reset();
        $("#parameter").val("");
        $("#specification").val("");
        $("#instrument").val("");
        $("#id").val("");
        var postData = {'postData':{'item_id':$("#item_id").val()},'table_id':"inspectionId",'tbody_id':'inspectionBody','tfoot_id':'','fnget':'inspectionHtml'};
        getTransHtml(postData);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            initTable();
            Swal.fire({ icon: 'error', title: data.message });
        }			
    }
}

function editInspParam(data, button) {
	$.each(data, function (key, value) { $("#inspection #" + key).val(value); });
    $.each(data.control_method.split(","), function(i,e){ $("#control_method option[value='" + e + "']").prop("selected", true); });
    $(".select2").select2();
}
</script>