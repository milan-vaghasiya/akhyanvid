<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />
			
			<div class="col-md-12 form-group">
                <label for="group_name">Group Name</label>
                <input type="text" name="group_name" class="form-control req" value="<?=(!empty($dataRow->group_name))?$dataRow->group_name:""?>" />
            </div>
			
			<div class="col-md-12 form-group">
                <label for="label">Label</label>
                <input type="text" name="label" id="label" class="form-control" value="<?=(!empty($dataRow->label))?$dataRow->label:""?>" />
            </div>
			
            <div class="col-md-12 form-group">
                <label for="member_ids">Group Member</label>
                <select id="memberIds" data-input_id="member_ids" class="form-control jp_multiselect req" multiple="multiple">
                    <?php
                        foreach($empData as $row):
							$selected='';
							$memberArr = (!empty($dataRow->member_ids)) ? explode(',',$dataRow->member_ids) : array();
                            if(!empty($dataRow->member_ids) && in_array($row->id,$memberArr)){ $selected = "selected";}
							
                            echo '<option value="'.$row->id.'" '.$selected.'>[ '.$row->emp_code.' ] '.$row->emp_name.'</option>';
                        endforeach;
                    ?>
                </select>
                <input type="hidden" name="member_ids" id="member_ids" value="<?= (!empty($dataRow->member_ids)) ? $dataRow->member_ids : "" ?>" />
                <div class="error member_ids"></div>
            </div>
			
		</div>
	</div>
</form>
<script>
$(document).ready(function(){
	$('#label').typeahead({
		source: function(query, result)
		{
			$.ajax({
				url:base_url + controller +'/labelSearch',
				method:"POST",
				global:false,
				data:{query:query},
				dataType:"json",
				success:function(data){result($.map(data, function(label){return label;}));}
			});
		}
	 });
});
</script>