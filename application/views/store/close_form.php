<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?=(!empty($id) ? $id : "") ?>">
            <input type="hidden" name="status" value="2">
            <div class="col-md-12 form-group">
                <label for="close_remark">Close Remark</label>
                <input type="text" name="close_remark" class="form-control" value="" />
            </div>
        </div>
    </div>
</form>