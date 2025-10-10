<form enctype="multpart/form-data">
    <div class="col-md-12">
        <div class="row">
            <h6 class="text-danger text-bold">Note: Updating the GST (%) will automatically apply to all products under the selected HSN code. Ensure the correct GST rate is entered before saving.</h6>
            
            <input type="hidden" name="id" id="id" value="" /> 
            <input type="hidden" name="hsn_id" id="hsn_id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" /> 

            <div class="col-md-12 form-group">
                <label for="hsn">HSN</label>
                <input type="text" name="hsn" id="hsn" class="form-control numericOnly" value="<?= (!empty($dataRow->hsn)) ? $dataRow->hsn : "" ?>" readOnly/>
            </div>

            <div class="col-md-6 form-group">
                <label for="gst_per">GST Per (%)</label>
                <input type="text" name="gst_per" id="gst_per" class="form-control" value="<?= (!empty($dataRow->gst_per)) ? $dataRow->gst_per : "" ?>" readOnly/>
            </div>

            <div class="col-md-6 form-group">
                <label for="new_gst_per">New GST Per (%)</label>
                <select name="new_gst_per" id="new_gst_per" class="form-control select2">
                    <option value="">Select</option>
                    <?php
                        foreach($this->gstPer as $key => $value):
                            $selected = (!empty($dataRow->new_gst_per) && floatVal($dataRow->new_gst_per) == $key)?"selected":"";
                            echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

        </div>
    </div>
</form>
