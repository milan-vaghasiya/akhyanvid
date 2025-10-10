<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
            <input type="hidden" name="status" value="1" />
           

			<div class="col-md-4 form-group">
                <label for="date">Complaint Date</label>
                <input type="date" id="date" name="date" class=" form-control req" placeholder="dd-mm-yyyy" value="<?=(!empty($dataRow->date))?$dataRow->date:date('Y-m-d')?>" />	
			</div>

            <div class="col-md-8 form-group">
                <label for="project_id">Project</label>
                <select name="project_id" id="project_id" class="form-control basic-select2 req">
                    <option value="">Select Project</option>
					<?php
                        foreach($projectList as $row):
                            $selected = (!empty($dataRow->project_id) && $dataRow->project_id == $row->id)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->project_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-12 form-group">
                <label for="complaint_file">Complaint File</label>
                <input type="file" name="complaint_file[]" id="complaint_file" class="form-control "  multiple="multiple" accept=".jpg, .jpeg, .png" />       
            </div>

            <div class="col-md-12 form-group">
                <label for="voice_note">Voice Note</label>
                <input type="text" id="voice_note" name="voice_note" class=" form-control" placeholder="Voice Note" value="<?=(!empty($dataRow->voice_note))?$dataRow->voice_note:""?>" />	
			</div>

            <div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <textarea name="remark" id="remark" class="form-control "><?=(!empty($dataRow->remark))?$dataRow->remark:""?></textarea>
            </div>
        </div>
    </div>
</form>
