<form>
    <div class="col-md-12">
        <div class="error item_error"></div>
        <div class="row">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="p_or_m" id="p_or_m" value="1">
            
            <!-- <input type="hidden" name="batch_no" id="batch_no" value="OPSTOCK"> -->
            <input type="hidden" name="item_type" id="item_type" value="<?=(!empty($item_type) ? $item_type : "")?>">  
            
            <div class="col-md-4 form-group">
                <label for="trans_date">Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?=getFyDate()?>">
            </div>

            <div class="col-md-8 form-group">
                <label for="item_id">Item Name</label>
                <select name="item_id" id="item_id" class="form-control select2 itemDetails" data-res_function="resItemDetail">
                    <option value="">Select Item</option>
                    <?=getItemListOption($itemList)?>
                </select>               
            </div> 

            <div class="col-md-4 form-group">
                <label for="box_qty">Box Qty</label>
                <input type="text" id="box_qty" class="form-control floatOnly calculateQty req" value="">
            </div>
            
            <div class="col-md-4 form-group">
                <label for="opt_qty">Qty Per Box</label>
                <input type="text" name="opt_qty" id="opt_qty" class="form-control floatOnly calculateQty req" value="">
            </div>

            <div class="col-md-4 form-group">
                <label for="qty">Qty</label>
                <input type="text" name="qty" id="qty" class="form-control floatOnly calculateQty req" value="" readonly>
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" value="">
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function(){
    $(document).on('keyup change','.calculateQty',function(){
        var box_qty = $("#box_qty").val() || 0;
        var qty_per_box = $("#opt_qty").val() || 0;
        var qty = $("#qty").val() || 0;

        if($(this).attr('id') == "box_qty"){
            if(parseFloat(box_qty) > 0 && parseFloat(qty_per_box) > 0){
                qty = parseFloat(parseFloat(box_qty) * parseFloat(qty_per_box)).toFixed(2);
                $("#qty").val(qty);
                return true;
            } 
        }

        if($(this).attr('id') == "opt_qty"){
            if(parseFloat(qty_per_box) > 0 && parseFloat(box_qty) > 0){
                qty = parseFloat(parseFloat(box_qty) * parseFloat(qty_per_box)).toFixed(2);
                $("#qty").val(qty);
                return true;
            }

            if(parseFloat(qty_per_box) > 0 && parseFloat(qty) > 0){
                box_qty = parseFloat(parseFloat(qty) / parseFloat(qty_per_box)).toFixed(2);
                $("#box_qty").val(box_qty);
                return true;
            }
        }

        if($(this).attr('id') == "qty"){
            if(parseFloat(qty) > 0 && parseFloat(box_qty) > 0){
                qty_per_box = parseFloat(parseFloat(qty) / parseFloat(box_qty)).toFixed(2);
                $("#opt_qty").val(qty_per_box);
                return true;
            }

            if(parseFloat(qty) > 0 && parseFloat(qty_per_box) > 0){
                box_qty = parseFloat(parseFloat(qty) / parseFloat(qty_per_box)).toFixed(2);
                $("#box_qty").val(box_qty);
                return true;
            }
        }
    })
});
function resItemDetail(response){
    if(response != ""){
        var itemDetail = response.data.itemDetail;
        $("#opt_qty").val(itemDetail.packing_standard);
    }else{
        $("#opt_qty").val("");
    }
}
</script>