<form>
    <div class="row">
        <div class="col-md-4 form-group">
            <input type="hidden" name="id">
            <input type="hidden" id="prc_id" name="prc_id" value="<?= (!empty($dataRow->prc_id) ? $dataRow->prc_id : '') ?>">
            <input type="hidden" id="log_id" name="log_id" value="<?= (!empty($dataRow->id) ? $dataRow->id : '') ?>">
            <input type="hidden" id="decision_type" name="decision_type" value="1">
            <input type="hidden" id="source" name="source" value="<?=$source?>">
            <label for="qty">Rej Qty</label>
            <input type="text" id="qty" name="qty" class="form-control req numericOnly">
        </div>
        <div class="col-md-4 form-group">
            <label for="rr_reason">Rejection Reason</label>
            <select id="rr_reason" name="rr_reason" class="form-control select2 req">
                <option value="">Select Reason</option>
                <?php
                foreach ($rejectionComments as $row) :
                    $code = (!empty($row->code)) ? '[' . $row->code . '] - ' : '';
                    echo '<option value="' . $row->id . '" data-code="' . $row->code . '" data-reason="' . $row->remark . '" >' . $code . $row->remark . '</option>';

                endforeach;
                ?>
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label for="rr_type">Rejection Type</label>
            <select id="rr_type" name="rr_type" class="form-control req">
                <option value="">Select type</option>
                <option value="Machine">Machine</option>
                <option value="Raw Material">Raw Material</option>
            </select>
            <div class="error rr_type"></div>
        </div>
        <div class="col-md-6 form-group">
            <label for="rr_stage">Rejection Stage</label>
            <select id="rr_stage" name="rr_stage" class="form-control select2 req">
                <?php if (empty($dataRow->stage)) { ?> <option value="">Select Stage</option> <?php } else {
                                                                                                echo $dataRow->stage;
                                                                                            } ?>
            </select>
        </div>
        <div class="col-md-6 form-group">
            <label for="rr_by">Rejection By <span class="text-danger">*</span></label>
            <select id="rr_by" name="rr_by" class="form-control select2 req">
                <option value="">Select Rej. From</option>
            </select>
        </div>
        <div class="col-md-12 form-group">
            <label for="rr_comment">Note</label>
            <textarea id="rr_comment" name="rr_comment" class="form-control" value=""></textarea>
        </div>
    </div>
</form>
