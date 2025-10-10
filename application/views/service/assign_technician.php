<form>
    <div class="col-md-12">
        <div class="row">            
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
            <input type="hidden" name="status" id="status" value="3" />
            
            <div class="col-md-12 form-group">
                <label for="technician_id">Technician Name</label>
                <select name="technician_id" id="technician_id" class="form-control basic-select2 req">
                    <option value="">Select Technician Name</option>
                    <?php
                        if(!empty($empList)){
                            foreach ($empList as $row) {
                                $selected = (!empty($dataRow->technician_id) && $dataRow->technician_id == $row->id)?"selected":"";
                                echo "<option value='".$row->id."' ".$selected.">".$row->emp_name."</option>";
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
    </div>
</form>