<form enctype="multipart/form-data">
<div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />   
            <div class="col-md-6 form-group">
                <label for="cal_agency_name">Cali. Agency</label>
                <input type="text" name="cal_agency_name" class="form-control " value="<?= (!empty($dataRow->cal_agency_name)) ? $dataRow->cal_agency_name : "" ?>" />
            </div>
            <div class="col-md-6 form-group">
                <label for="cal_certi_no">Certificate No.</label>
                <input type="text" name="cal_certi_no" class="form-control req" value="<?= (!empty($dataRow->cal_certi_no)) ? $dataRow->cal_certi_no : "" ?>" />
            </div>
            <div class="col-md-6 form-group">
                <label for="certificate_file">Certificate File</label>
                <input type="file" name="certificate_file" class="form-control-file" />
            </div>
            <div class="col-md-6 form-group">
                <label for="remark">Remark</label>
                <input type="text" name="remark" class="form-control" value="<?= (!empty($dataRow->remark)) ? $dataRow->remark : "" ?>" />
            </div>
        </div>
    </div>
</form>
