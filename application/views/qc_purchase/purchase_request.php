<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>" />
            <input type="hidden" name="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:(isset($trans_no) ? $trans_no : "")?>">
            <input type="hidden" name="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:(isset($trans_prefix)?$trans_prefix:"")?>">
            <input type="hidden" name="req_number" value="<?=(!empty($dataRow->req_number))?$dataRow->req_number:(isset($req_number)?$req_number:"")?>">
            <div class="col-md-6 form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control select2 req">
                    <option value="0">Select</option>
                    <?php
                        foreach ($categoryList as $row) :
                            $selected = (!empty($dataRow->category_id) && $dataRow->category_id == $row->id) ? "selected" : "";
                            echo '<option value="' . $row->id . '" ' . $selected . ' data-cat_name="'.$row->category_name.'"> ['. $row->category_code.'] ' . $row->category_name . '</option>';
                        endforeach;
                    ?>
                </select>
                <div class="error category_id"></div>
            </div>
            <div class="col-md-6 form-group">
                <label for="delivery_date">Required Date</label>
                <input type="date" name="delivery_date" class="form-control" value="<?=(!empty($dataRow->delivery_date))?$dataRow->delivery_date:date('Y-m-d')?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="make">Make</label>
                <input type="text" name="make" class="form-control" value="<?=(!empty($dataRow->make))?$dataRow->make:""?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="size">Size</label>
                <input type="text" name="size" id="size" class="form-control req" value="<?=(!empty($dataRow->size))?$dataRow->size:""?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="qty">Qty</label>
                <input type="text" name="qty" class="form-control floatOnly req" value="<?=(!empty($dataRow->qty))?$dataRow->qty:""?>" />
            </div>
            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""?>" />
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function(){
    initModalSelect();
		
	$(document).on('change',"#category_id",function(){
		var category = $(this).find(":selected").data('cat_name');
	});
})
</script>