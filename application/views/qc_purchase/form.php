<?php $this->load->view('includes/header'); ?>

<div class="page-content-tab">
    <div class="container-fluid bg-container">
        <div class="row">
            <div class="col-12">
				<form autocomplete="off" id="savePurchaseOrder">
					<div class="card">
						<div class="card-body">

							<div class="row">
								<div class="hiddenInput">
									<input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>">
									<input type="hidden" name="entry_type" id="entry_type" value="<?=(!empty($dataRow->entry_type))?$dataRow->entry_type:$entry_type?>">
									<input type="hidden" name="sales_type" id="sales_type" value="2">
									<input type="hidden" name="from_entry_type" id="from_entry_type" value="<?=(!empty($dataRow->from_entry_type))?$dataRow->from_entry_type:((!empty($from_entry_type))?$from_entry_type:"")?>">
									<input type="hidden" name="ref_id" id="ref_id" value="<?=(!empty($dataRow->ref_id))?$dataRow->ref_id:((!empty($ref_id))?$ref_id:"")?>">

									<input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($dataRow->trans_prefix))?$dataRow->trans_prefix:((!empty($trans_prefix))?$trans_prefix:"")?>">
									<input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($dataRow->trans_no))?$dataRow->trans_no:((!empty($trans_no))?$trans_no:"")?>">

									<input type="hidden" name="party_name" id="party_name" value="<?=(!empty($dataRow->party_name))?$dataRow->party_name:""?>">
									<input type="hidden" name="gst_type" id="gst_type" value="<?=(!empty($dataRow->gst_type))?$dataRow->gst_type:""?>">
									<input type="hidden" name="party_state_code" id="party_state_code" value="<?=(!empty($dataRow->party_state_code))?$dataRow->party_state_code:""?>">
									<input type="hidden" name="apply_round" id="apply_round" value="<?=(!empty($dataRow->apply_round))?$dataRow->apply_round:"1"?>">

									<input type="hidden" name="ledger_eff" id="ledger_eff" value="0">
								</div>
								<div class="col-md-2 form-group">
									<label for="trans_number">PO. No.</label>
									<input type="text" name="trans_number" id="trans_number" class="form-control" value="<?=(!empty($dataRow->trans_number))?$dataRow->trans_number:((!empty($trans_number))?$trans_number:"")?>" readonly>
								</div>
								<div class="col-md-2 form-group">
									<label for="trans_date">PO. Date</label>
									<input type="date" name="trans_date" id="trans_date" class="form-control" value="<?=(!empty($dataRow->trans_date))?$dataRow->trans_date:getFyDate()?>">
								</div>
								<div class="col-md-5 form-group">
									<label for="party_id">Party Name</label>
									<select name="party_id" id="party_id" class="form-control select2 partyDetails partyOptions req" data-res_function="resPartyDetail">
										<option value="">Select Party</option>
										<?=getPartyListOption($partyList,((!empty($dataRow->party_id))?$dataRow->party_id:0))?>
									</select>
									<div class="error party_id"></div>
								</div>
								<div class="col-md-3 form-group">
									<label for="gstin">GST NO.</label>
									<select name="gstin" id="gstin" class="form-control select2">
										<option value="">Select GST No.</option>
										<?php
											if(!empty($dataRow->party_id)):
												foreach($gstinList as $row):
													$selected = ($dataRow->gstin == $row->gstin)?"selected":"";
													echo '<option value="'.$row->gstin.'" '.$selected.'>'.$row->gstin.'</option>';
												endforeach;
											endif;
										?>
									</select>
								</div>
								<div class="col-md-3 form-group">
									<label for="doc_no">Ref No.</label>
									<input type="text" name="doc_no" id="doc_no" class="form-control" value="<?=(!empty($dataRow->doc_no))?$dataRow->doc_no:""?>">
								</div>
								<div class="col-md-3 form-group">
									<label for="doc_date">Ref Date</label>
									<input type="date" name="doc_date" id="doc_date" class="form-control" value="<?=(!empty($dataRow->doc_date))?$dataRow->doc_date:""?>">
								</div>
								<div class="col-md-6 form-group">
									<label for="master_t_col_3">Delivery Address</label>
									<input type="text" name="masterDetails[t_col_3]" id="master_t_col_3" class="form-control" value="<?=(!empty($dataRow->delivery_address))?$dataRow->delivery_address:""?>">
									
									<input type="hidden" name="masterDetails[t_col_1]" id="master_t_col_1" value="<?=(!empty($dataRow->contact_person))?$dataRow->contact_person:""?>">
									<input type="hidden" name="masterDetails[t_col_2]" id="master_t_col_2" value="<?=(!empty($dataRow->contact_no))?$dataRow->contact_no:""?>">
								</div>
							</div>

							<hr>

                            <div class="col-md-12 row">
                                <div class="col-md-6"><h4>Item Details : <small class="error category_name"></small></h4></div>
                                <div class="col-md-6"><button type="button" class="btn btn-info btn-sm waves-effect float-right add-item"><i class="fa fa-plus"></i> Add Item</button></div>
                            </div>
							<div class="col-md-12 mt-3">
								<div class="error itemData"></div>
								<div class="row form-group">
									<div class="table-responsive ">
										<table id="purchaseOrderItems" class="table table-striped table-borderless">
											<thead class="thead-info">
												<tr>
													<th style="width:5%;">#</th>
													<th>Category Name</th>
													<th>Delivery Date</th>
													<th>Size</th>
													<th>Make</th>
													<th>Qty.</th>
													<th>Price</th>
													<th class="igstCol">IGST</th>
													<th class="cgstCol">CGST</th>
													<th class="sgstCol">SGST</th>
													<th>Disc.</th>
													<th class="amountCol">Amount</th>
													<th class="netAmtCol">Amount</th>
													<th class="text-center" style="width:10%;">Action</th>
												</tr>
											</thead>
											<tbody id="tempItem" class="temp_item">
												<tr id="noData">
													<td colspan="14" class="text-center">No data available in table</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>

							<?php $this->load->view('includes/tax_summary',['expenseList'=>$expenseList,'taxList'=>$taxList,'ledgerList'=>array(),'dataRow'=>((!empty($dataRow))?$dataRow:array())])?>
                                
							<?php $this->load->view('includes/terms_form',['termsList'=>$termsList,'termsConditions'=>(!empty($dataRow->termsConditions)) ? $dataRow->termsConditions : array()])?>

							<div class="card-footer bg-facebook">
								<div class="col-md-12">
									<button type="button" class="btn btn-info waves-effect show_terms">Terms & Conditions (<span id="termsCounter">0</span>)</button>
									<span class="term_error text-danger font-bold"></span>
									<button type="button" class="btn waves-effect waves-light btn-success float-right save-form" onclick="saveOrder('savePurchaseOrder');" ><i class="fa fa-check"></i> Save</button>
									<a href="<?=base_url($headData->controller)?>" class="btn bg-grey press-close-btn float-right save-form" style="margin-right:10px;"><i class="fa fa-times"></i> Cancel</a>
								</div>
							</div>

						</div>
					</div>
				</form>
            </div>
        </div>        
    </div>
</div>

<div class="modal fade" id="itemModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content animated slideDown">
            <div class="modal-header">
                <h4 class="modal-title">Add or Update Item</h4>
				<button type="button" class="btn-close press-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="qcItemForm">
                    <div class="col-md-12">
                        <div class="row form-group">
							<input type="hidden" name="request_id" id="request_id" value="" />
							<input type="hidden" name="row_index" id="row_index" value="" />
														
                            <div class="col-md-6 form-group">
                                <label for="category_id">Category Name</label>
                                <select name="category_id" id="category_id" class="form-control select2 req">
                                    <option value="">Select Item</option>
									<?php
										foreach ($categoryList as $row) {
											echo "<option value='".$row->id."'>".$row->category_name."</option>";
										}
									?>
                                </select>
                                <input type="hidden" name="category_name" id="category_name" value="" />
								<div class="error category_id"></div>
                            </div>
							<div class="col-md-6 form-group">
								<label for="cod_date">Delivery Date</label>
								<input type="date" name="cod_date" id="cod_date" class="form-control" value="<?=date("Y-m-d")?>" />
							</div>
							<div class="col-md-4 form-group">
								<label for="size">Size</label>
								<input type="text" name="size" id="size" class="form-control" value="" />
							</div>
							<div class="col-md-4 form-group">
								<label for="make">Make</label>
								<input type="text" name="make" id="make" class="form-control" value="" />
							</div>
							<div class="col-md-4 form-group">
								<label for="gst_per">GST Per.</label>
								<select name="gst_per" id="gst_per" class="form-control select2">
									<?php
									foreach($this->gstPer as $per=>$text):
										echo '<option value="'.$per.'">'.$text.'</option>';
									endforeach;
									?>
								</select>
							</div>
                            <div class="col-md-4 form-group">
                                <label for="qty">Quantity</label>
                                <input type="number" name="qty" id="qty" class="form-control floatOnly" value="0">
								<div class="error qty"></div>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="price">Price</label>
                                <input type="number" name="price" id="price" class="form-control floatOnly" value="0" />
                            </div>
                            <div class="col-md-4 form-group ">
                                <label for="disc_per">Disc Per.</label>
                                <input type="number" name="disc_per" id="disc_per" class="form-control floatOnly" value="0" />
                            </div>
                            <input type="hidden" name="item_gst" id="item_gst" value="" />
							<input type="hidden" name="hsn_code" id="hsn_code" value="" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn waves-effect waves-light btn-outline-success saveItem btn-save" data-fn="save"><i class="fa fa-check"></i> Save</button>
                <button type="button" class="btn waves-effect waves-light btn-outline-warning saveItem btn-save-close" data-fn="save_close"><i class="fa fa-check"></i> Save & Close</button>
				<button type="button" class="btn waves-effect waves-light btn-outline-secondary press-close-btn" data-bs-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script src="<?php echo base_url();?>assets/js/custom/qc-purchase-order-form.js?v=<?=time()?>"></script>
<script src="<?php echo base_url(); ?>assets/js/custom/calculate.js?v=<?= time() ?>"></script>
<?php
	if(!empty($dataRow->itemList)):
		foreach($dataRow->itemList as $row):
			$row->row_index = "";
			$row->gst_per = floatVal($row->gst_per);
			// print_r(json_encode($row));
			echo '<script>AddRow('.json_encode($row).');</script>';
		endforeach;
	endif;

	if(!empty($reqItemList)){
		$rflag = 1;$i = 0;
		foreach ($reqItemList as $reqItem) {
			$reqItem->row_index = $i++;
			$reqItem->request_id = $reqItem->id;
			$reqItem->gst_per = 18;
			$reqItem->igst_per = $reqItem->gst_per;
			$reqItem->sgst_per = $reqItem->cgst_per = round(($reqItem->gst_per / 2), 2);
			$reqItem->igst_amount = $reqItem->sgst_amount = $reqItem->cgst_amount = $reqItem->amount = $reqItem->net_amount = 0;
			$reqItem->disc_per = $reqItem->disc_amount = 0;
			$reqItem->cod_date = date('Y-m-d');
			$reqItem->price = (!empty($reqItem->price)) ? $reqItem->price : 0;
			$reqItem->taxable_amount = round(($reqItem->qty * $reqItem->price), 2);
			if ($reqItem->gst_per > 0) :
				$reqItem->igst_amount = round((($reqItem->taxable_amount * $reqItem->gst_per) / 100), 2);
				$reqItem->sgst_amount = $reqItem->cgst_amount = round(($reqItem->igst_amount / 2));
			endif;
			echo '<script>AddRow('.json_encode($reqItem).');</script>';
		}
	}
?>
