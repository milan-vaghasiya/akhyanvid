<form>
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:((!empty($trans_prefix))?$trans_prefix:"")?>">
            <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:((!empty($trans_no))?$trans_no:"")?>">
            <input type="hidden" name="trans_number" id="trans_number" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:((!empty($trans_number))?$trans_number:"")?>">
            <input type="hidden" name="type" id="type" value="<?=(!empty($dataRow->type))?$dataRow->type:"Customer Complaints"?>">
            <input type="hidden" name="party_id">
            <input type="hidden" name="bfr_images" id="bfr_images" value="<?=(!empty($dataRow->complaint_file))?$dataRow->complaint_file:""?>">
            <input type="hidden" name="voice_notes" id="voice_notes" value="<?=(!empty($dataRow->voice_note))?$dataRow->voice_note:""?>">

            <div class="col-md-4 form-group">
                <label for="trans_date">Date</label>
                <input type="datetime-local" name="trans_date" id="trans_date" class="form-control req" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:getFyDate("Y-m-d H:i:s")?>">
			</div>

            <div class="col-md-4 form-group">
                <label for="project_id">Project</label>
                <select name="project_id" id="project_id" class="form-control basic-select2 req">
                    <option value="">Select Project</option>
					<?php
                        foreach($projectList as $row){
                            $selected = (!empty($dataRow->project_id) && $dataRow->project_id == $row->id)?"selected":"";
                            echo '<option value="'.$row->id.'" '.$selected.' data-customer_id="'.$row->party_id.'" data-is_amc="'.$row->amc.'">'.$row->project_name.' ['.$row->party_name.']'.'</option>';
                        }
                    ?>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="service_type">Service Type</label>
                <select name="service_type" id="service_type" class="form-control basic-select2">
                    <option value="FOC">FOC</option>
                    <option value="PAID">PAID</option>
                </select>
            </div>
            <div class="col-md-12 form-group">
                <label for="problem">Problem</label>
                <textarea name="problem" id="problem" class="form-control req" rows="3"><?=(!empty($dataRow->remark) ? $dataRow->remark : "")?></textarea>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $(document).on('change', '[name="project_id"]', function(){
            var customer_id = $(this).find(':selected').data('customer_id');
            var is_amc = $(this).find(':selected').data('is_amc');
            $('[name="party_id"]').val(customer_id);
            
            if(is_amc == 'Yes'){
                $('#service_type option[value="AMC"]').prop('disabled', false);
            }else{
                $('#service_type option[value="AMC"]').prop('disabled', true);
            }
        });
        $('[name="project_id"]').trigger('change');
    });
</script>