<form>
    <div class="col-md-12">
        <div class="row">
        <input type="hidden"  id="entryType" value="<?=(!empty($entry_type))?$entry_type:""?>">

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>DO. No.</th>
                            <th>Vou. No.</th>
                            <th>Vou. Date</th>
                            <th>Po. No.</th>
                            <!-- <th>Item Name</th>
                            <th>Pending Qty.</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;
                            foreach($orderItems as $row):
                                $row->from_entry_type = $row->entry_type;
                                //$row->ref_id = $row->so_id;
                                $row->ref_id = $row->request_id;
                                unset($row->id,$row->entry_type);
                                $row->row_index = "";
                                $row->entry_type = "";
                                //$row->pending_qty = $row->link_qty;
                                $row->link_qty = $row->pending_qty;

                                $row->taxable_amount = $row->amount = round(($row->link_qty * $row->price),2);
                                $row->gst_amount = $row->igst_amount = round((($row->gst_per * $row->taxable_amount) / 100),2);

                                $row->sgst_amount = $row->cgst_amount = round(($row->gst_amount/2),2);

                                $row->net_amount =  $row->taxable_amount + $row->gst_amount;

                                $jsonData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                                echo "<tr>
                                    <td class='text-center'>
										<input type='checkbox' id='md_checkbox_" . $i . "' name = 'ref_id[]' class='filled-in chk-col-success orderItem' value='". $row->ref_id . "' data-row='".$jsonData."' ><label for='md_checkbox_" . $i . "' class='mr-3 check" . $row->ref_id . "'></label>
                                        <input type='hidden' name='entry_type' id='entry_type' value='".$row->main_entry_type."' />
                                    </td>
                                    <td>".$row->order_number."</td>
                                    <td>".$row->so_number."</td>
                                    <td>".formatDate($row->so_date)."</td>
                                    <td>".$row->doc_no."</td>
                                   <!-- <td>".$row->item_name."</td>
                                    <td>".($row->pallet_pck_qty)."</td> -->
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
    }};
</script>