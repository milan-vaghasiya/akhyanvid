<form>
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="req_id" id="req_id" value="">
            <input type="hidden" name="prc_id" id="prc_id" value="">
            
            <div class="row">
                <div class="col-md-4 form-group">
                    <label for="challan_no">Issue No.</label>
                    <div class="input-group">
                        <input type="text" name="issue_number" class="form-control" value="<?= $issue_prefix ?>" readOnly />
                        <input type="hidden" name="issue_no" value="<?= $issue_no ?>" readOnly />
                    </div>
                </div>
                <div class="col-md-4 form-group">
                    <label for="issue_date">Issue Date</label>
                    <input type="date" name="issue_date" id="issue_date" class="form-control" max="<?=date('Y-m-d')?>" value="<?=date("Y-m-d")?>">
                </div>
                <div class="col-md-4 form-group">
                    <label for="item_id">Items</label>
                    <select name="item_id" id="item_id" class="form-control select2 req">
                        <option value="">Select Item</option>
                        <?php
                            if(!empty($reqData)){
                                foreach ($reqData as $row) {
                                    $item_name = (!empty($row->item_code)) ? "[".$row->item_code."] ".$row->item_name : $row->item_name;
                                    echo "<option value='".$row->item_id."' data-req_id=".$row->id." data-prc_id='".$row->prc_id."'>".$item_name.' | Req. Qty.: '.$row->req_qty."</option>";
                                }
                            }else{
                                foreach ($itemData as $row) {
                                    $item_name = (!empty($row->item_code)) ? "[".$row->item_code."] ".$row->item_name : $row->item_name;
                                    $selected = ((!empty($item_id) && $row->id == $item_id) ? 'selected' : '');
                                    echo "<option value='".$row->id."' ".$selected." >".$item_name."</option>";
                                }
                            }
                        ?>
                    </select>
                    <div class="error item_err"></div>
                </div>
				<div class="col-md-4 form-group">
                    <label for="issued_to">Issued To</label>
                    <select name="issued_to" id="issued_to" class="form-control select2 req">
                        <option value="">Select Issued To</option>
                        <?php
                            if(!empty($empData)){
                                foreach ($empData as $row) {
                                    echo "<option value='".$row->id."'>".$row->emp_name."</option>";
                                }
                            }
                        ?>
                    </select>
                    <div class="error item_err"></div>
                </div>
                <?php if(!empty($issue_type) && $issue_type == 'REQ'): ?>
                <div class="col-md-4 form-group">
                    <label for="to_location">From Location</label>
                    <select name="to_location" id="to_location" class="form-control select2">
                        <option value="">Select Location</option>
                        <?=getLocationListOption($locationList)?>
                    </select>  
                </div>
                <?php endif; ?>
            </div>

            <div class="col-md-12 form-group mt-4">
                <div class="error general_batch_no"></div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-info">
                            <th>Location</th>
                            <!-- <th>Batch No.</th> -->
                            <th>Stock Qty.</th>
                            <th>Issue Qty.</th>
                        </thead>
                        <tbody id="tbodyData">
                            <?php 
                            if(isset($batchData) && !empty($batchData)){
                                $i = 1;
                                foreach ($batchData as $value) {
                                    echo "<tr>";
                                    echo '<td>'.$value->location.'</td>';
                                    echo '<td>'.floatVal($value->qty).'</td>';
                                    echo '<td>
                                            <input type="text" name="batch_qty[]" class="form-control batchQty floatOnly" min="0" value="" />
                                            <div class="error batch_qty_' . $i . '"></div>
											<input type="hidden" name="location_id[]" id="location_' . $i . '" value="' . $value->location_id . '" />
                                        </td>
									</tr>';
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="error table_err"></div>
                </div>
            </div>

        </div>
    </div>
</form>
<script>
    $(document).ready(function(){
        initModalSelect();setTimeout(function(){ $('#item_id').trigger('change'); }, 10);
        $(document).on('change', '#item_id', function (e) {
            e.stopImmediatePropagation();e.preventDefault();
            var item_id = $(this).val();
            var req_id = $("option:selected", this).data('req_id');
            $('#req_id').val(req_id);
			
			if(item_id){
				$.ajax({
					url:base_url + controller + "/getBatchWiseStock",
					type:'post',
					data:{item_id:item_id},
					dataType:'json',
					success:function(data){
						if(data.status == 1){
							$('#tbodyData').html('');
							$('#tbodyData').html(data.tbodyData);
							$('#is_return').val(data.is_return)
						}
					}
				});
			}
        });
    });
</script>