<div class="col-md-12">
    <table class="table" style="border-radius:15px;box-shadow: 1px 2px 2px 0 rgb(0 0 0 / 70%);">
        <tr class=""> 
            <th class="text-center text-white" style="background:#aeaeae;width:10%;padding:0.25rem 0.5rem;border-top-left-radius:15px;border-bottom-left-radius:15px;border:0px;">Code</th>
            <th class="text-left" style="background:#f3f2f2;width:10%;padding:0.25rem 0.5rem;"><?=$dataRow->item_code?></th>
            <th class="text-center text-white" style="background:#aeaeae;width:10%;padding:0.25rem 0.5rem;">Item Name</th>
            <th class="text-left" style="background:#f3f2f2;width:15%;padding:0.25rem 0.5rem;border-top-right-radius:15px; border-bottom-right-radius:15px;border:0px;"><?=$dataRow->item_name?></th>
        </tr>
    </table>
</div>
<hr>
<form>
    <div class="col-md-12">
        <div class="row"> 
            <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ''; ?>" />
            <input type="hidden" name="item_id" id="item_id" value="<?= (!empty($dataRow->item_id)) ? $dataRow->item_id : ''; ?>" />
            <input type="hidden" name="challan_id" id="challan_id" value="<?= (!empty($dataRow->challan_id)) ? $dataRow->challan_id : ''; ?>" />
            <input type="hidden" name="challan_trans_id" id="challan_trans_id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ''; ?>" />
            <input type="hidden" name="batch_no" id="batch_no" value="<?= (!empty($dataRow->item_code)) ? $dataRow->item_code : ''; ?>" />
            <input type="hidden" name="cal_agency" id="cal_agency" value="<?= (!empty($dataRow->party_id)) ? $dataRow->party_id : ''; ?>" />
            <input type="hidden" name="cal_agency_name" id="cal_agency_name" value="<?= (!empty($dataRow->party_name)) ? $dataRow->party_name : 'IN-HOUSE'; ?>" />

            <div class="col-md-3 form-group">
                <label for="cal_date">Calibration Date</label>
                <input type="date" name="cal_date" id="cal_date" class="form-control floatOnly req" value="<?= (!empty($dataRow->cal_date)) ? $dataRow->cal_date :date("Y-m-d") ?>">
            </div>
            <div class="col-md-3 form-group">
                <label for="cal_certi_no">Certificate No.</label>
                <input type="text" name="cal_certi_no" id="cal_certi_no" class="form-control" value="<?= (!empty($dataRow->cal_certi_no)) ? $dataRow->cal_certi_no : ''; ?>">
            </div>
            <div class="col-md-3 form-group">
                <label for="certificate_file">Certificate File</label>
                <input type="file" name="certificate_file" class="form-control" />
            </div>
            <div class="col-md-3 form-group">
                <label for="to_location">Receive Location</label>
                <select name="to_location" id="to_location" class="form-control select2">
                    <option value="">Select Location</option>
                    <?php
                        foreach ($locationList as $row) :
                            $selected = (!empty($dataRow->to_location) && $dataRow->to_location == $row->id) ? 'selected' : '';
                            echo '<option value="' . $row->id . '" '.$selected.'>[' .$row->store_name. '] '.$row->location.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
        </div>
    </div>
</form>
<script>

$(document).ready(function(){
    initModalSelect();
})
</script>

