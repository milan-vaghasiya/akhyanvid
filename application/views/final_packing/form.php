<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
				<div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form autocomplete="off" id="saveFinalPacking" data-res_function="resFinalPacking" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="hiddenInput">
                                            <input type="hidden" name="id"  value="<?=(!empty($dataRow[0]->main_id))?$dataRow[0]->main_id:""?>">
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="trans_number">Pck. No.</label>
                                            <input type="text" name="trans_number" id="trans_number" class="form-control numericOnly" value="<?=(!empty($dataRow[0]->trans_number))?$dataRow[0]->trans_number:((!empty($trans_number))?$trans_number:"")?>" readonly>
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="trans_date">Pck. Date</label>
                                            <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?=(!empty($dataRow[0]->trans_date))?$dataRow[0]->trans_date:getFyDate()?>">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label for="party_id">Customer Name</label>
                                    
                                            <select name="party_id" id="party_id" class="form-control select2 req  partyOptions" data-party_category="1" >
                                                <option value="">Select Party</option>
                                                <?=getPartyListOption($partyList,((!empty($dataRow[0]->party_id))?$dataRow[0]->party_id:(!empty($party_id) ? $party_id : 0)))?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label for="transport_id">Transport Name</label>
                                            <select name="transport_id" id="transport_id" class="form-control select2">
                                                <option value="">Select Transporter</option>
                                                <?php
                                                    foreach($transportList as $row):
                                                        $selected = (!empty($dataRow[0]->transport_id) && $dataRow[0]->transport_id == $row->id)?"selected":"";
                                                        echo '<option value="'.$row->id.'" data-t_id="'.$row->transport_id.'" '.$selected.'>'.$row->transport_name.'</option>';
                                                    endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label for="vehicle_no">Vehicle No</label>
                                            <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" value="<?=(!empty($dataRow[0]->vehicle_no))?$dataRow[0]->vehicle_no:""?>">
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label for="lr_no">LR No.</label>
                                            <input type="text" name="lr_no" id="lr_no" class="form-control" value="<?=(!empty($dataRow[0]->lr_no))?$dataRow[0]->lr_no:""?>">
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="lr_date">LR. Date</label>
                                            <input type="date" name="lr_date" id="lr_date" class="form-control" value="<?=(!empty($dataRow[0]->lr_date))?$dataRow[0]->lr_date:getFyDate()?>">
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label for="port_of_loading">Port of Loading</label>
                                            <input type="text" name="port_of_loading" id="port_of_loading" class="form-control " value="<?=(!empty($dataRow[0]->port_of_loading))?$dataRow[0]->port_of_loading:""?>" >
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label for="port_of_discharge">Port of Discharge</label>
                                            <input type="text" name="port_of_discharge" id="port_of_discharge" class="form-control" value="<?=(!empty($dataRow[0]->port_of_discharge))?$dataRow[0]->port_of_discharge:""?>" >
                                        </div>
                                        <div class="col-md-2 form-group">
                                            <label for="method_of_dispatch">Method of Dispatch</label>
                                            <input type="text" name="method_of_dispatch" id="method_of_dispatch" class="form-control" value="<?=(!empty($dataRow[0]->method_of_dispatch))?$dataRow[0]->method_of_dispatch:""?>">
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="type_of_shipment">Type Of Shipment</label>
                                            <input type="text" name="type_of_shipment" id="type_of_shipment" class="form-control" value="<?=(!empty($dataRow[0]->type_of_shipment))?$dataRow[0]->type_of_shipment:""?>">
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="country_of_origin">Country of Origin</label>
                                            <input type="text" name="country_of_origin" id="country_of_origin" class="form-control" value="<?=(!empty($dataRow[0]->country_of_origin))?$dataRow[0]->country_of_origin:""?>">
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="country_of_fd">Country of Final Destination</label>
                                            <input type="text" name="country_of_fd" id="country_of_fd" class="form-control" value="<?=(!empty($dataRow[0]->country_of_fd))?$dataRow[0]->country_of_fd:""?>">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="terms_of_delivery">Terms of Delivery</label>
                                            <input type="text" name="terms_of_delivery" id="terms_of_delivery" class="form-control" value="<?=(!empty($dataRow[0]->terms_of_delivery))?$dataRow[0]->terms_of_delivery:""?>" >
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="fright_terms">Fright Terms</label>
                                            <input type="text" name="fright_terms" id="fright_terms" class="form-control" value="<?=(!empty($dataRow[0]->fright_terms))?$dataRow[0]->fright_terms:""?>" >
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12" id="itemForm">
                                        <div class="row form-group">
                                            <div id="itemInputs">
                                                <input type="hidden"  id="id" value="" class="itemFormInput"/>
                                                <input type="hidden"  id="row_index" value="" class="itemFormInput">
                                                <input type="hidden"  id="box_count" value="0" class="itemFormInput">
                                                <input type="hidden"  id="dc_trans_id" value="" class="itemFormInput">
                                            </div>	
                                            <div class="col-md-4 form-group itemDiv">
                                                <label for="item_id">Product </label>
                                                <select  id="item_id" class="form-control select2 itemList req itemFormInput">
                                                    <?php
														if(!empty($itemList)){
															
															echo '<option value="" data-item_id="0">Select Product</option>';
															
															foreach($itemList as $row):
																$itemName = (!empty($row->item_code))?"[ ".$row->item_code." ] ".$row->item_name : $row->item_name;
																$itemName .= " | CH. Qty.:".floatval($row->qty);
																echo '<option value="'.$row->item_id.'" data-dc_trans_id="'.$row->id.'" data-ch_qty="'.$row->qty.'" data-item_name="'.$row->item_name.'">'.$itemName.'</option>';
															endforeach;
															
														}else{
															echo '<option value="" data-item_id="0">Select Product</option>';
														}
                                                    ?>
                                                    
                                                </select>
                                            </div>
											<div class="col-md-4 form-group">
												<div class="input-group">
													<div style="width:35%;">
														<label for="box_size">Cartoon No.</label>
													</div>
													<div style="width:35%;">
														<label for="box_size">Total Box</label>
													</div>
													<div style="width:30%;">
														<label for="box_size">Qty Per Box</label>
													</div>
												</div>
												<div class="input-group">
													<div style="width:35%;">
														<select  id="box_no" class="form-control select2 req itemFormInput">
															<?php
																for($i = 1; $i <= 100; $i++):
																	echo '<option value="'.sprintf("%02d",$i).'">'.sprintf("%02d",$i).'</option>';
																endfor;
															?>
														</select>
													</div>
													<input type="text" id="total_box" class="form-control numericOnly req itemFormInput" value="" placeholder="No. of Box" style="width:35%;" />
													<input type="text" id="qty_box" class="form-control floatOnly req itemFormInput" value="" placeholder="Qty/Box" style="width:30%;" />
												</div>                
											</div>

											<div class="col-md-4 form-group">
												<label for="box_size">Box Detail</label>
												<div class="input-group">
													<input type="text" id="box_size" class="form-control req itemFormInput" value="" placeholder="Size" style="width:38%;" />
													<input type="text" id="box_wt" class="form-control floatOnly req itemFormInput" value="" placeholder="Weight" style="width:38%;" />
													<button type="button" class="btn btn-success saveItem float-right" style="line-height: 1.8;width:24%"><i class="fa fa-plus"></i> Add</button>
												</div>                
											</div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="col-md-12 row">
                                        <div class="col-md-6"><h4>Item Details : </h4></div>
                                        <!-- <div class="col-md-6">
                                            <button type="button" class="btn btn-outline-success waves-effect float-right add-item"><i class="fa fa-plus"></i> Add Item</button>
                                        </div> -->
                                    </div>

                                    <div class="col-md-12 mt-3">
                                        <div class="error itemData"></div>
                                        <div class="row form-group">
                                            <div class="table-responsive">
                                                <table id="packingListItems" class="table table-striped table-borderless">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th style="width:5%;">#</th>
                                                            <th>Item Name</th>
                                                            <th>Cartoon No.</th>
                                                            <th>Total Box</th>
                                                            <th>Qty Per Box</th>
                                                            <th>Box Size</th>
                                                            <th>Box Weight</th>
                                                            <th class="text-center" style="width:10%;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tempItem" class="temp_item">
                                                        <tr id="noData">
                                                            <td colspan="8" class="text-center">No data available in table</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <label for="remark">Remark</label>
                                            <input type="text" name="remark" id="remark" class="form-control" value="<?=(!empty($dataRow[0]->remark))?$dataRow[0]->remark:""?>">
                                        </div>                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer bg-facebook">
                            <div class="col-md-12"> 
                                <button type="button" class="btn waves-effect waves-light btn-success float-right save-form" onclick="customStore({'formId':'saveFinalPacking'});" ><i class="fa fa-check"></i> Save </button>

                                <button type="button" class="btn btn-secondary press-close-btn btn-close-modal save-form float-right m-r-10" onclick="window.location.href='<?=base_url('deliveryChallan')?>'"><i class="fa fa-times"></i> Cancel</button><!--04-04-25-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script src="<?php echo base_url(); ?>assets/js/custom/final-packing-form.js?v=<?= time() ?>"></script>
<?php
if(!empty($dataRow)):
    foreach($dataRow as $row):
        $row->row_index = "";
        echo '<script>AddRow('.json_encode($row).');</script>';
    endforeach;
endif;
?>