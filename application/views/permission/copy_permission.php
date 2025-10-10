<form data-res_function="resCopyPermission">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="from_id">From User :</label>
                <select name="from_id" id="from_id" class="form-control basic-select2">
                    <option value="">Select Employee</option>
                    <?php
                        foreach($fromList as $row):
                            $empName = (!empty($row->emp_code))?'[' . $row->emp_code . '] ' . $row->emp_name:$row->emp_name;
                            echo '<option value="' . $row->id . '">' . $empName . '</option>';
                        endforeach;
                    ?>
                </select>
            </div>  
            
            <div class="col-md-6 form-group">
                <label for="to_id">To User :</label>
                <select name="to_id" id="to_id" class="form-control basic-select2">
                    <option value="">Select Employee</option>
                    <?php
                        foreach($toList as $row):
                            $empName = (!empty($row->emp_code))?'[' . $row->emp_code . '] ' . $row->emp_name:$row->emp_name;
                            echo '<option value="' . $row->id . '">' . $empName . '</option>';
                        endforeach;
                    ?>
                </select>
            </div>
        </div>
    </div>
</form>  