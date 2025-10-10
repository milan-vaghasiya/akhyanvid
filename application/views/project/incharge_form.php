<form>
    <div class="col-md-12">
		<div class="row">
			<input type="hidden" name="id" id="id" value="<?=$project_id??''?>"/>

			<div class="col-md-12 form-group">
				<label for="incharge_ids">In-Charge</label>
				<select id="inchargeSelect" data-input_id="incharge_ids" class="form-control jp_multiselect req"  multiple="multiple" >
					<?php 
						foreach($employeeList as $row):
							$selected = '';
                            if (!empty($dataRow->incharge_ids)):
                                if(in_array($row->id, explode(',', $dataRow->incharge_ids))):
                                    $selected = "selected";
                                endif;
                            endif;
							echo '<option value="'.$row->id.'" ' . $selected . '>'.$row->emp_name.'</option>';
						endforeach;
					?>
				</select>
                <input type="hidden" name="incharge_ids" id="incharge_ids" value="<?= (!empty($dataRow->incharge_ids)) ? $dataRow->incharge_ids : "" ?>" />
			</div>
		</div>
	</div>
</form>