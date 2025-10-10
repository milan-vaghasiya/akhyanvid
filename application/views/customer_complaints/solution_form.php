<form id="solutionForm">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
            <input type="hidden" name="status" value="2" />
           
            <div class="col-md-12 form-group">
                <label for="solution">Solutions</label>
                <textarea name="solution" id="solution" class="form-control req"><?=(!empty($dataRow->solution))?$dataRow->solution:""?></textarea>
            </div>
        </div>
    </div>
</form>