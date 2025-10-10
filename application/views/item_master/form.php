<form class="itemMasterForm" enctype="multipart/form-data">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
            <input type="hidden" name="item_type" id="item_type" value="<?=(!empty($dataRow->item_type))?$dataRow->item_type:$item_type?>">
            <input type="hidden" name="stock_type" id="stock_type" value="1">

             <div class="col-md-4 form-group">
                <label for="item_code">Item Code</label>
                <input type="text" name="item_code" class="form-control" value="<?=(!empty($dataRow->item_code))?$dataRow->item_code:""; ?>" />
            </div>
            <?php $itmtp = (!empty($dataRow->item_type))?$dataRow->item_type:$item_type; ?>
			
            <div class="<?=($itmtp == 3)?'col-md-5':'col-md-8'?> form-group">
                <label for="item_name">Item Name</label>
				<input type="text" name="item_name" class="form-control req" value="<?=htmlentities((!empty($dataRow->item_name)) ? $dataRow->item_name : "")?>" />
            </div>
            <?php if($itmtp == 3): ?>
				<div class="col-md-3 form-group">
					<label for="grade_id">Material Grade</label>
					<select name="grade_id" id="grade_id" class="form-control select2">
						<option value="">Select Material Grade</option>
						<?php
							foreach($materialGrade as $row):
								$selected = (!empty($dataRow->grade_id) && $dataRow->grade_id == $row->id)?"selected":"";
								echo '<option value="'.$row->id.'" '.$selected.'>'.$row->material_grade.'</option>';
							endforeach;
						?>
					</select>
				</div>
            <?php endif; ?>

			<div class="col-md-3 form-group">
                <label for="size">Size</label>
                <input type="text" name="size" id="size" class="form-control calMRP floatOnly" value="<?=(!empty($dataRow->size))?$dataRow->size:""?>">
            </div> 
			
            <div class="col-md-3 form-group">
                <label for="category_id">Category</label>
                <select name="category_id" id="category_id" class="form-control select2 req">
                    <option value="0">Select</option>
                    <?php
                        foreach ($categoryList as $row) :
                            $selected = (!empty($dataRow->category_id) && $dataRow->category_id == $row->id) ? "selected" : "";
                            echo '<option value="' . $row->id . '" ' . $selected . '>' . $row->category_name . '</option>';
                        endforeach;
                    ?>
                </select>
            </div>     
            
			<div class="col-md-3 form-group">
                <label for="unit_id">Unit</label>
                <select name="unit_id" id="unit_id" class="form-control select2 req">
                    <option value="0">--</option>
                    <?=getItemUnitListOption($unitData,((!empty($dataRow->unit_id))?$dataRow->unit_id:""))?>
                </select>
            </div>
            
			<div class="col-md-3 form-group">
                <label for="hsn_code">HSN Code</label>
                <select name="hsn_code" id="hsn_code" class="form-control select2">
                    <option value="">Select HSN Code</option>
                    <?=getHsnCodeListOption($hsnData,((!empty($dataRow->hsn_code))?$dataRow->hsn_code:""))?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="gst_per">GST (%)</label>
                <select name="gst_per" id="gst_per" class="form-control calMRP select2">
                    <?php
                        foreach($this->gstPer as $per=>$text):
                            $selected = (!empty($dataRow->gst_per) && floatVal($dataRow->gst_per) == $per)?"selected":"";
                            echo '<option value="'.$per.'" '.$selected.'>'.$text.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-3 form-group">
                <label for="price">Price <small>(Exc. Tax)</small></label>
                <input type="text" name="price" id="price" class="form-control calMRP floatOnly" value="<?=(!empty($dataRow->price))?$dataRow->price:"0"?>">
            </div>  

            <div class="col-md-3 form-group">
                <label for="mrp">MRP <small>(Inc. Tax)</small></label>
                <input type="text" name="mrp" id="mrp" class="form-control calMRP floatOnly" value="<?=(!empty($dataRow->mrp))?$dataRow->mrp:"0"?>">
            </div>    
            <div class="col-md-3 form-group">
                <label for="wt_pcs">Weight Per Pcs </label>
                <input type="text" name="wt_pcs" id="wt_pcs" class="form-control floatOnly" value="<?=(!empty($dataRow->wt_pcs))?$dataRow->wt_pcs:"0"?>">
            </div>
            <div class="col-md-3 form-group">
                <label for="min_qty">Min. Stock Qty</label>
                <input type="text" name="min_qty" class="form-control floatOnly" value="<?= (!empty($dataRow->min_qty)) ? $dataRow->min_qty : "" ?>" />
            </div>

            <!-- <div class="col-md-4 form-group">
                <label for="make_brand">Brand</label>
                <input type="text" name="make_brand" class="form-control" value="<?= (!empty($dataRow->make_brand)) ? $dataRow->make_brand : "" ?>" />
            </div>  

            <div class="col-md-3 form-group">
                <label for="stock_type">Stock Type</label>
				<select name="stock_type" id="stock_type" class="form-control select2 req">
                    <option value="1" <?=((!empty($dataRow->stock_type) && $dataRow->stock_type == 1) ? "selected" : "")?> >Batch Wise</option>
                    <option value="2" <?=((!empty($dataRow->stock_type) && $dataRow->stock_type == 2) ? "selected" : "")?> >Serial Wise</option>
                </select>
            </div> --> 
            <div class="col-md-3 form-group">
                <label for="com_unit_id">Commercial Unit</label>
                <select name="com_unit_id" id="com_unit_id" class="form-control select2 req">
                    <option value="0">--</option>
                    <?=getItemUnitListOption($unitData,((!empty($dataRow->com_unit_id))?$dataRow->com_unit_id:""))?>
                </select>
            </div>
            <div class="col-md-3 form-group">
                <label for="conversion_value">Conversion Value</label>
                <input type="text" name="conversion_value" class="form-control floatOnly" value="<?= (!empty($dataRow->conversion_value)) ? $dataRow->conversion_value : "" ?>" />
            </div>

			<div class="col-md-3 form-group <?=($itmtp != 1)?"hidden":""?>" >
                <label for="is_packing">Packing Type</label>
                <select name="is_packing" id="is_packing" class="form-control select2">
                    <option value="0" >No</option>
					<option value="1" <?= ((!empty($dataRow) && $dataRow->is_packing == "1")?"selected":"");?>>Primary + Final Packing </option>
					<option value="2" <?= ((!empty($dataRow) && $dataRow->is_packing == "2")?"selected":"");?>>Only Final Packing</option>
				</select>
            </div>

            <div class="col-md-12 form-group">
                <label for="description">Product Description</label>
                <textarea name="description" id="description" class="form-control" rows="1"><?=(!empty($dataRow->description))?$dataRow->description:""?></textarea>
            </div>
			<?php if($itmtp == 1): ?>
				<div class="col-md-6 form-group">
					<label for="item_image1">Product Image</label>
					<div class="input-group">
						<div class="custom-file" style="width:100%;">
							<input type="file" class="form-control custom-file-input" name="item_image" id="item_image" accept=".jpg, .jpeg, .png" />
						</div>
					</div>
					<div class="error item_image"></div>
				</div>
			<?php endif; ?>
            <?php if(!empty($dataRow->item_image)): ?>
                <div class="col-md-2 form-group text-center m-t-20">
                    <img src="<?=base_url("assets/uploads/item_image/".$dataRow->item_image)?>" class="img-zoom" alt="IMG"><br>
                </div>
            <?php endif; ?>
        </div>

        <?php if(!empty($customFieldList)): ?>
            <h4 class="fs-15 text-primary border-bottom-sm">Custom Fields</h4>
            <div class="row">
                <?php
                    
                    foreach($customFieldList as $field):
                        ?>
                        <div class="col-md-4 form-group">
                            <label for="wt_pcs"><?=$field->field_name?></label>
                            <?php
                            if($field->field_type == 'SELECT'):
                                ?>
                                <select name="customField[f<?=$field->field_idx?>]" id="f<?=$field->field_idx?>" class="form-control select2">
                                    <option value="">Select</option>
                                <?php
                                foreach($customOptionList as $row):
                                    if($row->type == $field->id):
                                        $selected = (!empty($customData) && !empty(htmlentities($customData->{'f'.$field->field_idx}) && htmlentities($customData->{'f'.$field->field_idx}) == htmlentities($row->title)))?'selected':'';
                                        ?>
                                        <option value="<?=htmlentities($row->title)?>" <?=$selected?>><?=$row->title?></option>
                                        <?php
                                    endif;
                                endforeach;
                            elseif($field->field_type == 'TEXT'):
                                ?>
                                <input type="text" name="customField[f<?=$field->field_idx?>]" id="f<?=$field->field_idx?>" class="form-control" value="<?=(!empty($customData) && !empty($customData->{'f'.$field->field_idx}))?$customData->{'f'.$field->field_idx}:''?>">
                                <?php
                            elseif($field->field_type == 'NUM'):
                                ?>
                                <input type="text" name="customField[f<?=$field->field_idx?>]" id="f<?=$field->field_idx?>" class="form-control floatOnly" value="<?=(!empty($customData) && !empty($customData->{'f'.$field->field_idx}))?$customData->{'f'.$field->field_idx}:''?>">
                                <?php
                            endif;
                            ?>
                            </select>
                        </div>
                        <?php
                    endforeach;
                ?>                
            </div>
        <?php endif; ?>
    </div>
</form>
<script>
$(document).ready(function(){
    $(document).on('change','.itemMasterForm #hsn_code',function(){
        $(".itemMasterForm #gst_per").val(($(this).find(':selected').data('gst_per') || 0));
        initSelect2();
    });

    $(document).on('change','.itemMasterForm .calMRP',function(){
        var gst_per = $(".itemMasterForm #gst_per").val() || 0;
        var price = $(".itemMasterForm #price").val() || 0;
        var mrp = $(".itemMasterForm #mrp").val() || 0;
        if(gst_per > 0){
            if($(this).attr('id') == "price" && price > 0){
                var tax_amt = parseFloat( (parseFloat(price) * parseFloat(gst_per) ) / 100 ).toFixed(2);
                var new_mrp = parseFloat( parseFloat(price) + parseFloat(tax_amt) ).toFixed(2);
                $(".itemMasterForm #mrp").val(new_mrp);
                return true;
            }

            if(($(this).attr('id') == "mrp" || $(this).attr('id') == "gst_per") && mrp > 0){
                var gstReverse = parseFloat(( ( parseFloat(gst_per) + 100 ) / 100 )).toFixed(2);
                var new_price = parseFloat( parseFloat(mrp) / parseFloat(gstReverse) ).toFixed(2);
    		    $(".itemMasterForm #price").val(new_price);
                return true;
            }
        }else{
            if($(this).attr('id') == "price" && price > 0){
                $(".itemMasterForm #mrp").val(price);
                return true;
            }

            if(mrp > 0){
                $(".itemMasterForm #price").val(mrp);
                return true;
            }
        }
    });

    $(document).on('change','#unit_id',function(){ 
        var com_unit_id = $('#unit_id').val();
        $('#com_unit_id').val(com_unit_id);
        initSelect2();
    });
});
</script>
