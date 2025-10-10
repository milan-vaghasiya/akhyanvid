<form id="reminderForm">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($id) ? $id:'') ?>" />
            <input type="hidden" name="party_id" id="party_id" value="<?=$party_id?>" />
            <input type="hidden" name="lead_stage" id="lead_stage" value="2" />


            <?php if(!empty($id)) :?> 
            <!-- <div class="col-md-12 form-group">
                <label for="response">Response</label>
                <textarea name="response" id="response" class="form-control" rows="3"></textarea>
            </div> -->
            <?php else:?>
                <div class="col-md-6 form-group">
                    <label for="ref_date">Date</label>
                    <input type="date" name="ref_date" id="ref_date" class="form-control req" value="<?=(!empty($dataRow->ref_date ))?$dataRow->ref_date :date("Y-m-d")?>" min="<?=date("Y-m-d")?>" />
                </div>

                <div class="col-md-6 form-group">
                    <label for="reminder_time">Time</label>
                    <input type="time" name="reminder_time" id="reminder_time" class="form-control req" value="<?=(!empty($dataRow->reminder_time))?date("h:i:s",strtotime($dataRow->reminder_time)):date("h:i:s")?>" min="<?=date("h:i:s")?>" />
                </div>
                <div class="col-md-12 form-group">
                    <label for="mode">mode</label>
                    <select name="mode" id="mode" class="form-control basic-select2 req">
                        <?php
                            foreach($this->appointmentMode as $key=>$mode):
                                $selected = (!empty($dataRow->mode) and $dataRow->mode == $mode)?"selected":"";
                                echo '<option value="'.$mode.'" '.$selected .'>'.$mode.'</option>';
                            endforeach;
                        ?>
                    </select>
                    <div class="error mode"></div>
                </div>

                <div class="col-md-12 form-group">
                    <label for="remark">Notes</label>
                    <textarea name="remark" class="form-control" rows="3"><?=(!empty($dataRow->remark))?$dataRow->remark:""?></textarea>
                </div>
            <?php endif;?>
        </div>        
    </div>
</form>