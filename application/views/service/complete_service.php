<form>
    <div class="col-md-12">
        <div class="row">            
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
            <input type="hidden" name="status" id="status" value="5" />
            
            <div class="col-md-12 form-group">
                <label for="action_detail">Action Taken</label>
				<textarea class="form-control req" name="action_detail" id="action_detail" rows="3"></textarea>
            </div>

            <div class="col-md-12 form-group">
                <label for="aft_images">Images</label>
                <input type="file" name="aft_images[]" id="aft_images" class="form-control" multiple="multiple" >                
            </div>

        </div>
    </div>
</form>