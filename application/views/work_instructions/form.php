<?php
	$workType = ((!empty($dataRow->work_type)) ? $dataRow->work_type : $work_type);
?>
<form id="addWorkInstructions">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : "" ?>" />
            <input type="hidden" name="work_type" value="<?= (!empty($workType) ? $workType : 0) ?>" />

            <div class="col-md-12 form-group">
                <label for="work_title"><?=(($workType == 3) ? 'Step Label' : 'Work Title')?></label>
                <input type ="text" name="work_title" id="work_title" class="form-control " value="<?= (!empty($dataRow->work_title)) ? $dataRow->work_title : "" ?>"></input>
            </div>
            <?php if(($workType != 3)): ?>
            <div class="col-md-12 form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control req" rows="2"><?= (!empty($dataRow->description)) ? $dataRow->description : "" ?></textarea>
            </div>

            <div class="col-md-12 form-group">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="2"><?= (!empty($dataRow->notes)) ? $dataRow->notes : "" ?></textarea>
            </div>
			<?php endif; ?>
        </div>
    </div>
</form>
