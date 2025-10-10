<form>
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" id="id" value="<?=(!empty($project_id) ? $project_id : "")?>">

            <div class="col-md-12 form-group">
                <label for="handover_date">Handover Date</label>
                <input type="date" name="handover_date" id="handover_date" class="form-control req"  value="<?=date("Y-m-d")?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="handover_notes">Handover Notes</label>
                <textarea name="handover_notes" id="handover_notes" class="form-control" rows="2" ></textarea>
            </div>

        </div>
    </div>
</form>