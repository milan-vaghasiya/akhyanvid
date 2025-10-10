<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />
            <input type="hidden" name="trans_no" value="<?= (!empty($dataRow->trans_no)) ? $dataRow->trans_no : $trans_no; ?>" />
            <input type="hidden" name="trans_prefix" value="<?= (!empty($dataRow->trans_prefix)) ? $dataRow->trans_prefix : $trans_prefix; ?>" />

            <div class="col-md-6 form-group">
                <label for="trans_number">Req. No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control req" value="<?= (!empty($dataRow->trans_number)) ? $dataRow->trans_number : $trans_number ?>" readOnly>
            </div>
           
            <div class="col-md-6 form-group">
                <label for="trans_date">Req. Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?= (!empty($dataRow->trans_date)) ? $dataRow->trans_date : getFyDate() ?>" />
            </div>

            <div class="col-md-12 form-group">
                <label for="item_id">Item </label>
                <select name="item_id" id="item_id" class="form-control select2 req">
                    <option value="">Select Item</option>
                    <?php
                        foreach ($itemList as $row) :
                            $selected = (!empty($dataRow->item_id) && $dataRow->item_id == $row->id) ? "selected" : "";
                            echo '<option value="'. $row->id .'" '.$selected.'>'.$row->item_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="qty">Qty</label>
                <input type="text" name="qty" id="qty" class="form-control req" value="<?= (!empty($dataRow->qty)) ? $dataRow->qty : "" ?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="delivery_date">Delivery Date</label>
                <input type="date" name="delivery_date" id="delivery_date" class="form-control" value="<?= (!empty($dataRow->delivery_date)) ? $dataRow->delivery_date : getFyDate() ?>" />
            </div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" rows="1" value="<?= (!empty($dataRow->remark)) ? $dataRow->remark : "" ?>">
            </div>
        </div>
    </div>
</form>
