<form data-res_function="taskResponse" >
    <div class="col-md-12">
        <div class="row">      
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id) ? $dataRow->id : "")?>" />
            <input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($dataRow->ref_id)) ? $dataRow->ref_id : (!empty($ref_id) ? $ref_id : "")?>" />
            <input type="hidden" name="task_type" id="task_type" value="<?=((!empty($dataRow->task_type)) ? $dataRow->task_type : "1")?>" />       

            <div class="col-md-8 form-group">
                <label for="task_title">Task Title</label>
                <input type="text" name="task_title" id="task_title" class="form-control req" value="<?=(!empty($dataRow->task_title) ? $dataRow->task_title : "")?>" />
            </div>
            <div class="col-md-2 form-group">
                <label for="priority">Priority</label>
                <select name="priority" id="priority" class="form-control basic-select2 req">
                    <option value="NA" <?=((!empty($dataRow->priority) && $dataRow->priority == 'NA') ? 'selected' : '')?>>NA</option>
                    <option value="High" <?=((!empty($dataRow->priority) && $dataRow->priority == 'High') ? 'selected' : '')?>>High</option>
                    <option value="Low" <?=((!empty($dataRow->priority) && $dataRow->priority == 'Low') ? 'selected' : '')?>>Low</option>
                </select>
            </div>
            <div class="col-md-2 form-group">
                <label for="group_id">Group</label>
                <select name="group_id" id="group_id" data-selected="<?=(!empty($dataRow->group_id) ? $dataRow->group_id : '')?>" class="form-control basic-select2 req group_id">
                    <option value="0">Individual</option>
                    <?php
                    if(!empty($groupList)){
                        foreach($groupList as $row){
                            $selected = (!empty($dataRow->group_id) && $dataRow->group_id == $row->id) ? 'selected' : '';
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->group_name.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-12 form-group">
                <label for="notes">Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="3"><?= (!empty($dataRow->notes)) ? $dataRow->notes : "" ?></textarea>
            </div>

            <div class="col-md-4 form-group">
                <label for="assign_to">Assign To</label>
                <select name="assign_to" id="assign_to" data-selected="<?=(!empty($dataRow->assign_to) ? $dataRow->assign_to : '')?>" class="form-control basic-select2 req">
                    <option value="<?=(!empty($loginId) ? $loginId : '')?>">Self</option>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <label for="task_file">Attachment</label>
                <div class="input-group">
                    <input type="file" name="task_file" class="form-control" style="width:<?=(!empty($dataRow->task_file)) ? "75%" : "" ?>"  />
                    <?php
                    if(!empty($dataRow->task_file)){
                    ?>
                        <div class="input-group-append">
                          <a href="<?=$dataRow->task_file?>" class="btn btn-outline-primary" download><i class="fa fa-download"></i></a>
                        </div>
                    <?php
                    }
                    ?>
                </div>
				<div class="error task_file"></div>
            </div>
			
            <div class="col-md-4 form-group">
                <label for="due_date">Due On</label>
                <input type="datetime-local" name="due_date" id="due_date" class="form-control req" value="<?=(!empty($dataRow->due_date) ? date('Y-m-d\TH:i:s',strtotime($dataRow->due_date)) : date('Y-m-d\TH:i:s'))?>" min="<?=date('Y-m-d\TH:i:s')?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="remind_at">Remind On</label>
                <input type="datetime-local" name="remind_at" id="remind_at" class="form-control" value="<?=(!empty($dataRow->remind_at ) ? date('Y-m-d\TH:i:s',strtotime($dataRow->remind_at))  : "")?>" />
            </div>
            <div class="col-md-2 form-group">
                <label for="repeat_type">Repeat</label>
                <select name="repeat_type" id="repeat_type" class="form-control basic-select2">
                    <option value="">Select</option>
                    <option value="Daily" <?=((!empty($dataRow->repeat_type) && $dataRow->repeat_type == 'Daily') ? 'selected' : '')?>>Daily</option>
                    <option value="Weekly" <?=((!empty($dataRow->repeat_type) && $dataRow->repeat_type == 'Weekly') ? 'selected' : '')?>>Weekly</option>
                    <option value="Monthly" <?=((!empty($dataRow->repeat_type) && $dataRow->repeat_type == 'Monthly') ? 'selected' : '')?>>Monthly</option>
                    <option value="Yearly" <?=((!empty($dataRow->repeat_type) && $dataRow->repeat_type == 'Yearly') ? 'selected' : '')?>>Yearly</option>
                </select>
            </div>
            <div class="col-md-2 form-group">
                <label for="start_on">Starts On</label>
                <input type="date" name="start_on" id="start_on" class="form-control" value="<?=(!empty($dataRow->start_on ) ? date('Y-m-d',strtotime($dataRow->start_on))  : "")?>" />
            </div>
            <div class="col-md-4 form-group">
                <label for="tags">Tags</label>
				<input name="tags" id="tags" class="form-control" value="<?= (!empty($dataRow->tags)) ? $dataRow->tags : "" ?>" data-role="tagsinput">
            </div>
        </div>
    </div>
	<div class="" style="position:absolute;width:100%;bottom:20px;left:0;z-index:9785;">
		<div class="row bg-light" style="margin:0px;padding:8px 10px;">
			<div class="col-md-4"></div>
			<div class="col-md-2">
				<button type="reset" class="btn btn-dark btn-block">Reset</button>
			</div>
			<div class="col-md-2">
				<button type="button" class="btn btn-success btn-block btn-save save-form" onclick="" ><i class="far fa-save"></i> Save</button>
			</div>
			<div class="col-md-4"></div>
		</div>
	</div>
</form>

<script>
$(document).ready(function() {
	setTimeout(function(){ $("#group_id").trigger("change"); }, 50);
	
	$(document).off('change').on('change',"#group_id",function(){
		let group_id = $("#group_id").data('selected') || $("#group_id").val();
		let assign_to = $("#assign_to").data('selected') || $("#assign_to").val();
		$("#group_id").data('selected','');
		
		$.ajax({
			url: base_url  + 'taskManager/getMemberList',
			data:{group_id : group_id, assign_to:assign_to},
			type: "POST",
			dataType:"json",
		}).done(function(response){
			$("#assign_to").html(response.memberList);
			$("#assign_to").val(assign_to);
			initSelect2();
		});
	});
	
	$(document).on('change',"#assign_to",function(){
		$("#assign_to").data('selected','');
	});

});

</script>