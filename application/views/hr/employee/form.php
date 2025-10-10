<form autocomplete="off">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
            <div class="col-md-12 form-group">
                <label for="emp_name">Employee Name</label>
                <input type="text" name="emp_name" class="form-control text-capitalize req" value="<?=(!empty($dataRow->emp_name))?$dataRow->emp_name:""; ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="emp_code">Employee Code</label>
                <input type="text" name="emp_code" class="form-control text-capitalize req" value="<?=(!empty($dataRow->emp_code))?$dataRow->emp_code:""; ?>" />
            </div>
           
            <div class="col-md-4 form-group">
                <label for="emp_mobile_no">Mobile No.</label>
                <input type="text" name="emp_mobile_no" class="form-control numericOnly req"  value="<?=(!empty($dataRow->emp_mobile_no))?$dataRow->emp_mobile_no:""?>" />
            </div>

            <div class="col-md-4 form-group">
                <label for="emp_role">Role</label>
                <select name="emp_role" id="emp_role" class="form-control basic-select2 req">
                    <option value = "">Select Role</option>
                    <?php
                        foreach($this->empRole as $key=>$value):
                            $selected = (!empty($dataRow->emp_role) && floatVal($dataRow->emp_role) == $key)?"selected":"";
                            echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
        </div>
    </div>
</form>