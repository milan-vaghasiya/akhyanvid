<form enctype="multipart/form-data" data-res_function="getTestReportHtml">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value=""/>
            <input type="hidden" name="grn_id" id="grn_id" value="<?= (!empty($grn_id)) ? $grn_id : ""; ?>"/>
            <input type="hidden" name="grn_trans_id" id="grn_trans_id" value="<?= (!empty($grn_trans_id)) ? $grn_trans_id : ""; ?>"/>

            <div class="col-md-3 form-group">
                <label for="name_of_agency">Name Of Agency</label>
                <select name="agency_id" id="agency_id" class="form-control select2 req">
                    <option value="">Select Agency</option>
                    <?php
                    if(!empty($supplierList)){
                        foreach($supplierList as $row){
                            echo '<option value="'.$row->id.'" >'.$row->party_name.'</option>';
                        }
                    }
                    ?>
                </select>
                <input type="hidden" name="name_of_agency" id="name_of_agency" class="form-control req" value="" />
            </div>
            <div class="col-md-4 form-group">
                <label for="test_description">Test Description</label>
                <input type="text" name="test_description" id="test_description" class="form-control req" value=""/>
            </div>
            <div class="col-md-2 form-group" >
                <label for="test_report_no">Test Report No</label>
                <input type="text" name="test_report_no" id="test_report_no" class="form-control" value="" />
            </div>
            <div class="col-md-3 form-group">
                <label for="inspector_name">Inspector Name</label>
                <input type="text" name="inspector_name" id="inspector_name" class="form-control" value="" />
            </div>
            <div class="col-md-3 form-group">
                <label for="sample_qty">Sample Qty</label>
                <input type="text" name="sample_qty" id="sample_qty" class="form-control floatOnly req" value="" />
            </div>
        
            <div class="col-md-2 form-group" >
                <label for="test_result">Test Result</label>
                <select name="test_result" id="test_result" class="form-control select2">
                    <option value="Ok">Ok</option>
                    <option value="Not Ok">Not Ok</option>
                </select>
            </div>
            <div class="col-md-4 form-group" >
                <label for="tc_file">T.C. File</label>
                <input type="file" name="tc_file" id="tc_file" class="form-control"  />
            </div>
            <div class="col-md-12 form-group" >
                <label for="test_remark">Test Remark</label>
                <div class="input-group">
                    <input type="text" name="test_remark" id="test_remark" class="form-control" value="" />
                    <div class="input-group-append">
                        <?php
                            $param = "{'formId':'testReport','fnsave':'saveTestReport','controller':'gateInward','res_function':'getTestReportHtml'}";
                        ?>
                        <button type="button" class="btn waves-effect waves-light btn-outline-success btn-save save-form float-right" onclick="customStore(<?=$param?>)" style="height:36px"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<hr>
<div class="row">
    <h6>Report Details : </h6>
    <div class="col-md-12 form-group">
        <div class="table-responsive">
            <table id="testReport" class="table table-bordered align-items-center">
                <thead class="thead-info">
                <tr>
                    <th style="width:5%;" rowspan="2">#</th>
                    <th>Name Of Agency</th>
                    <th>Test Description</th>
                    <th>Test Report No</th>
                    <th>Inspector Name</th>
                    <th>Sample Qty</th>
                    <th>Test Result</th>
                    <th>T.C. File</th>
                    <th>Test Remark</th>
                    <th class="text-center" style="width:10%;">Action</th>
                </tr>
                </thead>
                <tbody id="testReportBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var tbodyData = false;
$(document).ready(function(){
    setPlaceHolder();
    $(document).on('change',"#agency_id",function(){
        var party_name = $("#agency_id :selected").text();
        $("#name_of_agency").val(party_name);
    });

    if(!tbodyData){
        var postData = {'postData':{'grn_id':$("#grn_id").val()},'table_id':"testReport",'tbody_id':'testReportBody','tfoot_id':'','fnget':'testReportHtml'};
        getTransHtml(postData);
        tbodyData = true;
    }
});
function getTestReportHtml(data,formId="testReport"){ 
    if(data.status==1){
        $('#'+formId)[0].reset(); initSelect2();
        var postData = {'postData':{'grn_id':$("#grn_id").val()},'table_id':"testReport",'tbody_id':'testReportBody','tfoot_id':'','fnget':'testReportHtml'};
        getTransHtml(postData);
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