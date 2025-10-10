<form>
    <div class="col-md-12">
        <div class="error item_name"></div>
        <div class="row">
            <input type="hidden" name="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>" />
            <input type="hidden" name="status" value="<?=(!empty($dataRow->status))?$dataRow->status:$status?>" />
            <input type="hidden" name="unit_id" value="25" />
            <input type="hidden" name="item_code" id="item_code" class="form-control" value="<?= (!empty($dataRow->item_code)) ?$dataRow->item_code   : ""; ?>" style="letter-spacing:1px;" />
            <input type="hidden" name="cat_code" id="cat_code" value="<?= (!empty($dataRow->cat_code)) ? $dataRow->cat_code : ""; ?>" />
            <input type="hidden" name="cat_name" id="cat_name" value="<?= (!empty($dataRow->category_name)) ?$dataRow->category_name : ""; ?>" />
           
            <?php if(!empty($dataRow->id)): ?>
                <div class="col-md-3 form-group">
                    <label for="category_id">Category</label>
                    <input type="text" id="category_name" value="<?=(!empty($dataRow->category_name))?'['.$dataRow->cat_code.'] '.$dataRow->category_name:""?>" class="form-control req" readOnly>
                    <input type="hidden" name="category_id" id="category_id" value="<?=(!empty($dataRow->category_id))?$dataRow->category_id:""?>" />
                </div>
            <?php else: ?>    
                <div class="col-md-3 form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id" class="form-control select2 req">
                        <option value="">Select Category</option>
                        <?php
                            foreach ($categoryList as $row) :
                                $selected = (!empty($dataRow->category_id) && $dataRow->category_id == $row->id) ? "selected" : "";
                                echo '<option value="'. $row->id .'" '.$selected.' data-cat_code="'.$row->category_code.'" data-cat_name="'.$row->category_name.'">'.((!empty($row->category_code))?'['.$row->category_code.'] '.$row->category_name:$row->category_name).'</option>';
                            endforeach;
                        ?>
                    </select>
                </div>
            <?php endif; ?>
            
            <div class="col-md-6 form-group">
                <label for="size">Range</label>                    
                <input type="text" name="size" id="size" class="form-control" value="<?=(!empty($dataRow->size))?$dataRow->size:""?>" />
            </div>
            
            <div class="col-md-3 form-group">
                <label for="least_count">Least Count</label>
                <input type="text" name="least_count" id="least_count" class="form-control" value="<?=(!empty($dataRow->least_count))?$dataRow->least_count:""?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="make_brand">Make</label>
                <input type="text" name="make_brand" class="form-control" value="<?=(!empty($dataRow->make_brand))?$dataRow->make_brand:""?>" />
            </div>
           
           <div class="col-md-3 form-group">
                <label for="location_id">Location</label>
                <select name="location_id" id="location_id" class="form-control select2">
					<option value="">Select Location</option>
                    <?php
						foreach ($locationList as $row) :
							$selected = (!empty($dataRow->location_id) && $dataRow->location_id == $row->id) ? "selected" : "";
							echo '<option value="' . $row->id . '" ' . $selected . '>[' .$row->store_name. '] '.$row->location.'</option>';
						endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="permissible_error">Permissible Error</label>
                <input type="text" name="permissible_error" class="form-control" value="<?=(!empty($dataRow->permissible_error))?$dataRow->permissible_error:""?>" />
            </div>
            <div class="col-md-3 form-group">
                <label for="cal_required">Cal. Required</label>
                <select name="cal_required" id="cal_required" class="form-control select2 req" >
                    <option value="YES" <?=(!empty($dataRow->cal_required) && $dataRow->cal_required == "YES")?"selected":""?>>YES</option>
                    <option value="NO" <?=(!empty($dataRow->cal_required) && $dataRow->cal_required == "NO")?"selected":""?>>NO</option>
                </select>
            </div>

            <div class="col-md-6 form-group">
                <div class="input-group">
                    <label for="cal_freq" style="width: 50%;">Freq. <small>(Months)</small></label>
                    <label for="cal_reminder">Reminder <small>(Days)</small></label>
                </div>
                <div class="input-group">
                    <input type="text" name="cal_freq" class="form-control floatOnly"  value="<?=(!empty($dataRow->cal_freq))?$dataRow->cal_freq:""?>" />
                    <input type="text" name="cal_reminder" class="form-control floatOnly" value="<?=(!empty($dataRow->cal_reminder))?$dataRow->cal_reminder:""?>" />
                </div>
            </div>

            <div class="col-md-6 form-group">
                <label for="mfg_sr">Mfg Serial No.</label>
                <input type="text" name="mfg_sr" class="form-control" value="<?=(!empty($dataRow->mfg_sr))?$dataRow->mfg_sr:""?>" />
            </div>
            
            <div class="col-md-12 form-group">
                <label for="description">Remark</label>
                <textarea name="description" id="description" class="form-control"><?=(!empty($dataRow->description))?$dataRow->description:""?></textarea>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function(){
    
    $("#category_id").on('change',function(){
        var cat_code = $(this).find(":selected").data('cat_code');
        var cat_name = $(this).find(":selected").data('cat_name');
        $('#cat_code').val(cat_code);
        $('#cat_name').val(cat_name);
    });

    $(".generateCode").on('click',function(){
        var cat_code = $("#category_id").find(":selected").data('cat_code');
        var category_id = $("#category_id").val();
        var least_count = $("#least_count").val();
        var valid = 1;
        if(cat_code == ''){
            $(".category_id").html("Category code is required");
            valid = 0;
        }
        if(least_count == ''){
            $(".least_count").html("Enter Least Count");
            valid = 0;
        }

        if(valid){
            $.ajax({
                url:base_url + controller + "/getInstrumentCode",
                type:'post',
                data:{category_id:category_id,cat_code:cat_code,least_count:least_count},
                dataType:'json',
                success:function(data){
                    $("#item_code").val(data.item_code);
                    $("#store_id").val(data.part_no);
                    $("#cat_code").val(cat_code);
                }
            });
        }
    });
});
</script>