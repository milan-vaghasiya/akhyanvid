<form>
    <div class="col-md-12">
        <div class="row">            
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />
            <input type="hidden" name="type" id="type" value="<?=(!empty($dataRow->type) ? $dataRow->type : $type)?>" />
			
            <div class="col-md-12 form-group">
                <label for="label">Option</label>
                <input type="text" name="label" id="label" class="form-control req" value="<?=(!empty($dataRow->label) ? $dataRow->label : "")?>" />
            </div>
			
			<div class="col-md-12 form-group">
                <label for="remark">Remark</label>
                <textarea name="remark" id="remark" class="form-control"><?=(!empty($dataRow->remark) ? $dataRow->remark : "")?></textarea>
            </div>
        </div>
    </div>
</form>