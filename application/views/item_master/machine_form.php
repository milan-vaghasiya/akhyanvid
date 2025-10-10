<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="item_type" id="item_type" value="<?=(!empty($dataRow->item_type))?$dataRow->item_type:$item_type?>">

        <div class="col-md-4 form-group">
            <label for="item_code">Machine No.</label>
            <input type="text" name="item_code" class="form-control" value="<?= (!empty($dataRow->item_code)) ? $dataRow->item_code : ""; ?>"/>
        </div>
        <div class="col-md-4 form-group">
            <label for="item_name">Machine Name</label>
                <input type="text" name="item_name" class="form-control req" value="<?=((!empty($dataRow->item_name)) ? $dataRow->item_name : "")?>"/>
        </div>
        <div class="col-md-4 form-group">
            <label for="make_brand">Make/Brand</label>
            <input type="text" name="make_brand" class="form-control" value="<?= (!empty($dataRow->make_brand)) ? $dataRow->make_brand : "" ?>" />
        </div> 
        <div class="col-md-4 form-group">
            <label for="size">Capacity</label>
            <input type="text" name="size" class="form-control" value="<?= (!empty($dataRow->size)) ? $dataRow->size : "" ?>" />
        </div> 
        <div class="col-md-4 form-group">
            <label for="part_no">Serial No.</label>
            <input type="text" name="part_no" class="form-control" value="<?= (!empty($dataRow->part_no)) ? $dataRow->part_no : "" ?>" />
        </div> 
        <div class="col-md-4 form-group">
            <label for="installation_year">Installation Year</label>
            <input type="text" name="installation_year" class="form-control" value="<?= (!empty($dataRow->installation_year)) ? $dataRow->installation_year : "" ?>" />
        </div> 
        <div class="col-md-6 form-group">
            <label for="category_id">Category</label>
            <select name="category_id" id="category_id" class="form-control select2 req">
                <option value="0">Select</option>
                <?php
                    foreach ($categoryList as $row) :
                        $selected = (!empty($dataRow->category_id) && $dataRow->category_id == $row->id) ? "selected" : "";
                        echo '<option value="' . $row->id . '" ' . $selected . '>' . $row->category_name . '</option>';
                    endforeach;
                ?>
            </select>
        </div>     
        <div class="col-md-6 form-group">
            <label for="gst_per">GST (%)</label>
            <select name="gst_per" id="gst_per" class="form-control calMRP select2">
                <?php
                    foreach($this->gstPer as $per=>$text):
                        $selected = (!empty($dataRow->gst_per) && floatVal($dataRow->gst_per) == $per)?"selected":"";
                        echo '<option value="'.$per.'" '.$selected.'>'.$text.'</option>';
                    endforeach;
                ?>
            </select>
        </div>
    </div>
</form>