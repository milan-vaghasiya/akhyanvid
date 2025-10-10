<form>
    <div class="row">
        <div class="col-md-12 form-group">
            <input type="hidden" name="id">
            <input type="hidden" id="prc_id" name="prc_id" value="<?= (!empty($dataRow->prc_id) ? $dataRow->prc_id : '') ?>">
            <input type="hidden" id="log_id" name="log_id" value="<?= (!empty($dataRow->id) ? $dataRow->id : '') ?>">
            <input type="hidden" id="decision_type" name="decision_type" value="5">
            <input type="hidden" id="source" name="source" value="<?=$source?>">
            <label for="qty">Qty</label>
            <input type="text" id="qty" name="qty" class="form-control req numericOnly">
        </div>
        <div class="col-md-12 form-group">
            <label for="rr_comment">Remark</label>
            <textarea id="rr_comment" name="rr_comment" class="form-control"></textarea>
        </div>
    </div>
</form>