<form>
    <div class="col-md-12">
        <div class="error item_error"></div>
        <div class="row">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="p_or_m" id="p_or_m" value="1">
            <input type="hidden" name="item_type" id="item_type" value="">
            <input type="hidden" name="trans_date" id="trans_date" value="<?=date("Y-m-d")?>">
            
            <div class="col-md-12 form-group">
                <label for="item_id">Item Name</label>
                <select name="item_id" id="item_id" class="form-control select2 req">
                    <option value="">Select Item</option>
                    <?=getItemListOption($itemList)?>
                </select>               
            </div> 

            <div class="col-md-6 form-group">
                <label for="location_id">Location</label>
                <select id="location_id" name="location_id" class="form-control select2 req">
                    <option value="">Select Location</option>
                    <?=getLocationListOption($locationList)?>
                </select>  
            </div>
    
            <div class="col-md-6 form-group">
                <label for="qty">Qty</label>
                <input type="text" name="qty" id="qty" class="form-control floatOnly req" value="" >
            </div>

        </div>
    </div>
</form>
<script>
$(document).ready(function(){
    $(document).on('change','#item_id',function(e){
        e.stopImmediatePropagation();e.preventDefault();
        var location_id = ($("#item_id").find(":selected").data('location_id') || 0); 
        if(location_id != ""){
            $("#location_id").val(location_id);
        }else{
            $("#location_id").val('');
        }
        initSelect2();
    });
});

</script>