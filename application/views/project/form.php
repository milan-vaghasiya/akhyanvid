<form>
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>">
            <input type="hidden" name="party_id" id="party_id" value="<?=(!empty($dataRow->party_id) ? $dataRow->party_id : $party_id)?>">
            <input type="hidden" name="sq_no" id="sq_no" value="<?=(!empty($dataRow->sq_no) ? $dataRow->sq_no : $sq_no)?>">
            <input type="hidden" name="project_type" id="project_type" value="<?=(!empty($dataRow->project_type) ? $dataRow->project_type : $project_type)?>">

            <div class="col-md-12 form-group">
                <label for="project_name">Project Name</label>
                <input type="text" class="form-control req" name="project_name" id="project_name" value="<?=(!empty($dataRow->project_name) ? $dataRow->project_name : "")?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="project_cost">Project Cost</label>
                <input type="text" class="form-control " name="project_cost" id="project_cost" value="<?=(!empty($dataRow->project_cost) ? $dataRow->project_cost : "")?>">
            </div>

            <div class="col-md-6 form-group ">
                <label for="payment_conditions">Payment Conditions</label>
                <select name="payment_conditions" id="payment_conditions" class="form-control basic-select2 ">
                    <option value="CASH" <?=(!empty($dataRow) && $dataRow->payment_conditions == "CASH") ? "selected" : "";?>>CASH</option>
                    <option value="BANK" <?=(!empty($dataRow) && $dataRow->payment_conditions == "BANK") ? "selected" : "";?>>BANK</option>
                    <option value="CB" <?=(!empty($dataRow) && $dataRow->payment_conditions == "CB") ? "selected" : "";?>>CB</option>
                </select>
            </div>

            <div class="col-md-6 form-group ">
                <label for="amc">AMC?</label>
                <select name="amc" id="amc" class="form-control basic-select2 req">
                    <option value="Yes" <?=(!empty($dataRow) && $dataRow->amc == "Yes") ? "selected" : "";?>>Yes</option>
                    <option value="No" <?=(!empty($dataRow) && $dataRow->amc == "No") ? "selected" : "";?>>No</option>
                </select>
            </div>

            <div class="col-md-6 form-group">
                <label for="amc_validity">AMC Validity <small>(Months)</small></label>
                <input type="text" min="0" class="form-control numericOnly req" name="amc_validity" id="amc_validity" maxlength="3" value="<?=(!empty($dataRow->amc_validity) ? $dataRow->amc_validity : 0)?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="drawing_file">Drawing File</label>
                <input type="file" name="drawing_file" id="drawing_file" class="form-control " />       
            </div>
        

            <div class="col-md-12 form-group">
                <label for="location">Project Location</label>
                <textarea name="location" id="location" class="form-control" rows="1" ><?=(!empty($dataRow->location) ? $dataRow->location : "")?></textarea>
            </div>

            <div class="col-md-12 form-group">
                <label for="other_info">Project Other Info</label>
                <textarea name="other_info" id="other_info" class="form-control" rows="1" ><?=(!empty($dataRow->other_info) ? $dataRow->other_info : "")?></textarea>
            </div>
        </div>
    </div>
</form>