<form>
    <div class="col-md-12">
        <div class="row">
            <div class="row">                        
                <input type="hidden" name="issue_id" value="<?=$issue_id?>" />
                
                <div class="col-md-12 form-group">
                    <label for="return_qty">Return Qty</label>
                    <input type="text" name="return_qty" id="return_qty" class="form-control floatOnly req" >
                </div>
				
                <div class="col-md-12 form-group">
                    <label for="remark">Remark</label>
                    <textarea name="remark" id="remark" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </div>
</form>
