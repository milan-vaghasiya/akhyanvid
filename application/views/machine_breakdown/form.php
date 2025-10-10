<form>
    <div class="col-md-12">
        <div class="row">
            <div class="error general_error"></div>
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
                
            <div class="col-md-4 form-group">
                <label for="trans_date"> Date</label>
                <input type="date" name="trans_date" class="form-control req" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:date('Y-m-d'); ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="start_time"> Start Time</label>
                <input type="time" name="start_time" class="form-control req" value="<?=(!empty($dataRow->start_time))?$dataRow->start_time:""; ?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="end_time">End Time</label>
                <input type="time" name="end_time" class="form-control req" value="<?=(!empty($dataRow->end_time))?$dataRow->end_time:""; ?>" />
            </div>
            <div class="col-md-4 form-group">
				<label for="prc_id">PRC No.</label>
				<select name="prc_id" id="prc_id" class="form-control select2">
                    <option value="">Select PRC</option>
                    <?php 
                        foreach ($prcList as $row) :
                            $selected = (!empty($dataRow->prc_id) && $dataRow->prc_id == $row->id)?"selected":((!empty($prc_id) && $prc_id == $row->id)?"selected":"");

                            echo '<option value="' . $row->id . '" ' . $selected . '>' . $row->prc_number . '</option>';
                        endforeach;   
                    ?>
                </select>
			</div>
            <div class="col-md-4 form-group">
                <label for="machine_id">Machine</label>
                <select name="machine_id" id="machine_id" class="form-control select2 req">
                <option value="">Select Machine</option>
                    <?php 
                        foreach ($machineList as $row) :
                            $selected = (!empty($dataRow->machine_id) && $dataRow->machine_id == $row->id) ? "selected" : "";
                            echo '<option value="' . $row->id . '" ' . $selected . '>' . $row->item_name . '</option>';
                        endforeach;   
                    ?>
                </select>
            </div> 
            <div class="col-md-4 form-group">
                <label for="idle_reason">Idle Reason</label>
                <select name="idle_reason" id="idle_reason" class="form-control select2 req">
                <option value="">Select Idle Reason</option>
                    <?php 
                        foreach ($reasonList as $row) :
                            $selected = (!empty($dataRow->idle_reason) && $dataRow->idle_reason == $row->id) ? "selected" : "";
                            echo '<option value="' . $row->id . '" ' . $selected . '>['.$row->code.']' . $row->remark . '</option>';
                        endforeach;   
                    ?>
                </select>
            </div>  
            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" class="form-control" value="<?=(!empty($dataRow->remark))?$dataRow->remark:""; ?>" />
            </div>

        </div>
    </div>
</form>