<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden"  id="entryType" value="<?=(!empty($entry_type))?$entry_type:""?>">

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">
								<input type="checkbox" id="select_all" class="filled-in chk-col-success BulkRequest" name="select_all" value="1" />
								<label for="select_all">Select All</label>
							</th>
                            <th>Vou. No.</th>
                            <th>Vou. Date</th>
                            <th>Po. No.</th>
                            <th>Item Name</th>
                            <th>Pending Qty.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;
                            foreach($orderItems as $row):
                                $row->from_entry_type = $row->entry_type;
                                $row->ref_id = $row->id;
                                unset($row->id,$row->entry_type);
                                $row->row_index = "";
                                $row->entry_type = "";
								
                                $row->qty = $row->pending_qty;
                                $row->taxable_amount = $row->amount = ($row->qty * $row->price);
                                $row->disc_amount = 0;
                                $row->taxable_amount -= $row->disc_amount;
                                $row->gst_amount = $row->igst_amount = (floatval($row->gst_per) > 0)?round((($row->taxable_amount * $row->gst_per) / 100),2):0;
                                $row->cgst_amount = $row->sgst_amount = (floatval($row->gst_amount) > 0)?round(($row->gst_amount / 2),2):0;
                                $row->net_amount = round(($row->taxable_amount + $row->gst_amount),2);
								
                                $jsonData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                                echo "<tr>
                                    <td class='text-center'>
										<input type='checkbox' id='md_checkbox_" . $i . "' name='ref_id[]' class='filled-in chk-col-success orderItem' data-row='".$jsonData."' value='". $row->ref_id . "'><label for='md_checkbox_" . $i . "' class='mr-3 check" . $row->ref_id . "'></label>
                                        <input type='hidden' name='entry_type' id='entry_type' value='".$row->from_entry_type."' />
                                    </td>
                                    <td>".$row->trans_number."</td>
                                    <td>".formatDate($row->trans_date)."</td>
                                    <td>".$row->doc_no."</td>
                                    <td>".$row->item_name."</td>
                                    <td>".floatval($row->pending_qty).' '.$row->uom."</td>
                                </tr>";
                                $i++;
                            endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
<script>
$(document).ready(function(){
	$(document).on("change","#select_all",function(){
		var value = $(this).val();
		if ($('#select_all').prop('checked')) {
			$('.orderItem').prop('checked', true);
		}else{
			$('.orderItem').prop('checked', false);
		}
	});
});
function createChallan() {
	var ref_id = [];
	var entry_type = $('#entry_type').val();
	var entryType = $('#entryType').val();
	$("input[name='ref_id[]']:checked").each(function() {
		ref_id.push(this.value);
	});
	var ref_id = ref_id.join(","); 
	var send_data = { entry_type:entry_type,ref_id:ref_id };
	if(entryType == 177){
	    window.open(base_url + 'deliveryChallan/addChallan/' + encodeURIComponent(window.btoa(JSON.stringify(send_data))) , '_self');
    }
    if(entryType == 20){
	    window.open(base_url + 'salesInvoice/addInvoice/' + encodeURIComponent(window.btoa(JSON.stringify(send_data))) , '_self');
    }
};
</script>
                                  