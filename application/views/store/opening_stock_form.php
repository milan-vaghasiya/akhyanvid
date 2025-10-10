<form>
    <div class="col-md-12">
        <div class="row">
            <div class="row">
                <input type="hidden" name="id" value="" />

                <div class="col-md-12 form-group">
                    <label for="item_id">Items</label>
                    <select name="item_id" id="item_id" class="form-control basic-select2 req">
                        <option value="">Select Item</option>
                        <?php
                            if(!empty($itemList)){
                                foreach ($itemList as $row) {
									echo "<option value='".$row->id."'  >".$row->item_name."</option>";
                                }
							}                            
                        ?>
                    </select>
                    <div class="error item_err"></div>
                </div>
                <div class="col-md-12 form-group">
                    <label for="qty">Stock Qty</label>
                    <input type="text" name="qty" id="qty" class="form-control floatOnly req" >
                </div>
            </div>
        </div>
    </div>
</form>
