<form>
    <input type="hidden" name="id">
    <input type="hidden" name="so_id" value="<?=$soData->trans_main_id?>">
    <input type="hidden" name="so_trans_id" value="<?=$soData->id?>">
    <input type="hidden" name="party_id" value="<?=$soData->party_id?>">
    <input type="hidden" name="item_id" value="<?=$soData->item_id?>">
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="plan_number">Assembly  Number</label>
            <input type="text" name="plan_number" class="form-control" value="<?=$plan_number?>" readonly>
        </div>
        <div class="col-md-4 form-group">
            <label for="plan_date">Date</label>
            <input type="date" name="plan_date" id="plan_date" class="form-control" value="<?=date("Y-m-d")?>">
        </div>
        <div class="col-md-4 form-group">
            <label for="qty">Order Qty</label>
            <input type="text" name="qty" id="qty" class="form-control numericonly" value="">
        </div>
        <div class="col-md-12 form-group">
            <label for="remark">Note</label>
            <textarea name="remark" id="remark" rows="3" class="form-control"></textarea>
        </div>
    </div>
</form>