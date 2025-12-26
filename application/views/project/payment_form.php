

<form id="addPayment" data-res_function="getPaymentHtml">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="" />
            <input type="hidden" name="project_id" id="project_id" value="<?=(!empty($project_id))?$project_id:""?>" />

            
            <div class="col-md-3  form-group">
                <label for="payment_date">Date</label>
                <input type="date" name="payment_date" id="payment_date" class="form-control req" value="<?=date('Y-m-d')?>" />
            </div>
             <div class="col-md-3 form-group ">
				<label for="pay_mode">Payment Mode</label>
                <select name="pay_mode" id="pay_mode" class="form-control basic-select2 req">
                    <option value="CASH" <?=(!empty($dataRow) && $dataRow->pay_mode == "CASH") ? "selected" : "";?>>CASH</option>
                    <option value="CHEQUE" <?=(!empty($dataRow) && $dataRow->pay_mode == "CHEQUE") ? "selected" : "";?>>CHEQUE</option>
                    <option value="RTGS" <?=(!empty($dataRow) && $dataRow->pay_mode == "RTGS") ? "selected" : "";?>>RTGS</option>
                    <option value="IMPS" <?=(!empty($dataRow) && $dataRow->pay_mode == "IMPS") ? "selected" : "";?>>IMPS</option>
                    <option value="UPI" <?=(!empty($dataRow) && $dataRow->pay_mode == "UPI") ? "selected" : "";?>>UPI</option>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="amount">Amount</label>
                <input type="text" name="amount" id="amount" class="form-control req floatOnly" value="" />
            </div> 
            <div class="col-md-3 form-group">
                <label for="ref_no">Ref No.</label>
                <input type="text" name="ref_no" id="ref_no" class="form-control req " value="" />
            </div>  
            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <div class="input-group">
                <input type="text" name="remark" id="remark" class="form-control" value="" />
                    <div class="input-group-append">
                        <?php
                            $param = "{'formId':'addPayment','fnsave':'savePayment','res_function':'getPaymentHtml'}";
                        ?>
                        <button type="button" class="btn waves-effect waves-light btn-outline-success btn-save save-form float-right" onclick="customStore(<?=$param?>)" style="height:36px"><i class="fa fa-check"></i> Save</button>
                    </div>
                </div>  
            </div>
        </div>
    </div>
     <hr>
    <div class="table-responsive">
        <table id="paymentDetail" class="table table-bordered align-items-center">
            <thead class="thead-dark">
                <tr>
                    <th style="width:5%;">#</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">Payment Mode</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Ref. No.</th>                        
                    <th class="text-center">Remark </th>
                    <th class="text-center" style="width:10%;">Action</th>
                </tr>
            </thead>
            <tbody id="paymentBody">
            </tbody>
        </table>
    </div>
</form>

<script>
$(document).ready(function(){
    var paymentTrans = {'postData':{'project_id':$("#addPayment #project_id").val()},'table_id':"paymentDetail",'tbody_id':'paymentBody','tfoot_id':'','fnget':'getPaymentHtml'};
    getTransHtml(paymentTrans);
});

function getPaymentHtml(data,formId ="addPayment"){ 
    if(data.status==1){ 
        $('#'+formId)[0].reset();
        $("#addPayment #id").val("");

        var postData = {'postData':{'project_id':$("#addPayment #project_id").val()},'table_id':"paymentDetail",'tbody_id':'paymentBody','tfoot_id':'','fnget':'getPaymentHtml'};
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

</script>