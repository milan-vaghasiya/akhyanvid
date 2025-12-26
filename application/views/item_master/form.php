<form enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="item_type" id="item_type" value="<?=(!empty($dataRow->item_type))?$dataRow->item_type:$item_type?>">  
            
            <div class="col-md-12 form-group">
                <div class="input-group">
                    <label for="item_code" style="width:33%;">Item Code<span class="text-danger">*</span></label>
                    <label for="category_id" style="width:33%;">Category <span class="text-danger">*</span></label>
                    <label for="make_brand" style="width:34%;">Make/Brand Name <span class="text-danger">*</span></label>
                </div>
                
                <div class="input-group">
                    <div class="input-group-append" style="width:33%;">
                       	<input type="text" name="item_code" id="item_code" class="form-control" value="<?= (!empty($dataRow->item_code)) ? $dataRow->item_code : ""; ?>" placeholder="Item Code" />
                    </div>
                    <div class="input-group-append" style="width:33%;">
                        <select name="category_id" id="category_id" class="form-control basic-select2">
                            <option value="0">Select</option>
                            <?php
                                foreach ($categoryList as $row) :
                                    $selected = (!empty($dataRow->category_id) && $dataRow->category_id == $row->id) ? "selected" : "";
                                    echo '<option value="' . $row->id . '" ' . $selected . ' data-category_name = '.$row->category_name.'>' . $row->category_name . '</option>';
                                endforeach;
                            ?>
                            <input type="hidden" name="category_name" id="category_name" class="form-control" value="<?=(!empty($dataRow->category_name))?$dataRow->category_name:""?>" /> 
                        </select>
                    </div>  
                    <div class="input-group-append" style="width:34%;">
                        <input type="text" name="make_brand" id="make_brand" class="form-control" value="<?=(!empty($dataRow->make_brand))?$dataRow->make_brand:""?>" placeholder="Make/Brand Name">
                    </div>
                </div>
                <div class="error item_name"></div>
			</div>

            <div class="col-md-4 form-group">
                <label for="uom">UOM</label>
                <select name="uom" id="uom" class="form-control basic-select2 req">
                    <option value="0">--</option>
                    <?=getItemUnitListOption($unitData,((!empty($dataRow->uom))?$dataRow->uom:""))?>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="hsn_code">HSN Code</label>
                <input type="text" name="hsn_code" id="hsn_code" class="form-control" value="<?=(!empty($dataRow->hsn_code))?$dataRow->hsn_code:""?>">
            </div>
			
			<div class="col-md-4 form-group">
                <label for="gst_per">GST (%)</label>
                <select name="gst_per" id="gst_per" class="form-control basic-select2">
                    <?php
                        foreach($this->gstPer as $per=>$text):
                            $selected = (!empty($dataRow->gst_per) && floatVal($dataRow->gst_per) == $per)?"selected":"";
                            echo '<option value="'.$per.'" '.$selected.'>'.$text.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>   
            <div class="col-md-4 form-group">
                <label for="price">Price <small>(Exc. Tax)</small></label>
                <input type="text" name="price" id="price" class="form-control calMRP floatOnly" value="<?=(!empty($dataRow->price))?$dataRow->price:""?>">
            </div>
            
            <div class="col-md-4 form-group">
                <label for="inc_price">MRP <small>(Inc. Tax)</small></label>
                <input type="text" name="inc_price" id="inc_price" class="form-control calMRP floatOnly" value="<?=(!empty($dataRow->inc_price))?$dataRow->inc_price:""?>">
            </div>

            <div class="col-md-4 form-group">
                <label for="min_qty">Min. Stock Qty</label>
                <input type="text" name="min_qty" class="form-control floatOnly" value="<?= (!empty($dataRow->min_qty)) ? $dataRow->min_qty : "" ?>" />
            </div>
            <div class="col-md-4 form-group ">
                <label for="item_class">Item Class</label>
                <select name="item_class" id="item_class" class="form-control basic-select2">
                    <option value="Goods" <?=(!empty($dataRow) && $dataRow->item_class == "Goods") ? "selected" : "";?>>Goods</option>
                    <option value="Service" <?=(!empty($dataRow) && $dataRow->item_class == "Service") ? "selected" : "";?>>Service</option>
                </select>
            </div>
            <div class="col-md-8 form-group">
                <label for="description">Product Description</label>
                <textarea name="description" id="description" class="form-control" rows="1"><?=(!empty($dataRow->description))?$dataRow->description:""?></textarea>
            </div>
        </div>
    </div>
</form>