<form id="PartReplaceForm" data-res_function="resPartReplace">
    <div class="col-md-12">
        <div class="row">            
            <input type="hidden" name="id" id="id" value="" />
            <input type="hidden" name="service_id" id="service_id" value="<?=$service_id?>" />

            <div class="col-md-12 form-group">
                <label for="item_id">Item Name</label>
                <select name="item_id" id="item_id" class="form-control select2 req">
                    <option value="">Select Item</option>
                    <?=getItemListOption($itemList)?>
                </select>               
            </div>
            <div class="col-md-6 form-group">
                <label for="qty">Qty.</label>
                <input type="text" name="qty" id="qty" class="form-control req floatonly" value="">
            </div>
            <div class="col-md-6 form-group">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" class="form-control req floatonly" value="">
            </div>
			<div class="col-md-9 form-group">
                <label for="reason">Reason</label>
                <textarea name="reason" id="reason" rows="1" class="form-control req"></textarea>
            </div>
            <div class="col-md-3 form-group">
                <label for="">&nbsp;</label>
                <?php
                $param = "{'formId':'PartReplaceForm','fnsave':'savePartReplace','controller':'service','res_function':'resPartReplace'}";
                ?>
                <button type="button" class="btn btn-block btn-success save-form float-right" onclick="customStore(<?=$param?>)" style="height:35px"><i class="fa fa-check"></i> Save</button>
            </div>
           
        </div>
    </div>
    <hr>
    <div class="col-md-12">
        <div class="row">
            <div class="table-responsive">
                <table id="partReplaceId" class="table table-bordered align-items-center">
                    <thead class="thead-info">
                        <tr>
                            <th style="width:5%;">#</th>
                            <th style="width:30%;">Item</th>
                            <th style="width:20%;">Qty.</th>
                            <th style="width:20%;">price</th>
                            <th style="width:10%;">Reason</th>
                            <th class="text-center" style="width:5%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyData">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
<script>
var tbodyData = false;
$(document).ready(function(){
    if(!tbodyData){
        var postData = {'postData':{'service_id':$("#service_id").val()},'table_id':"partReplaceId",'tbody_id':'tbodyData','tfoot_id':'','fnget':'partReplaceHtml'};
        getTransHtml(postData);
        tbodyData = true;
    } 
});

function resPartReplace(data,formId="PartReplaceForm"){ 
    if(data.status==1){
        $('#qty').val('');
        $('#price').val('');
        $('#reason').val('');

        var postData = {'postData':{'service_id':$("#service_id").val()},'table_id':"partReplaceId",'tbody_id':'tbodyData','tfoot_id':'','fnget':'partReplaceHtml'};
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