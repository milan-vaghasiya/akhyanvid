<form>
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" value="<?=(!empty($gateInwardData->id))?$gateInwardData->id:""?>">
            <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($gateInwardData->trans_prefix))?$gateInwardData->trans_prefix:(!empty($trans_prefix) ? $trans_prefix :"")?>">
            <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($gateInwardData->trans_no))?$gateInwardData->trans_no:(!empty($trans_no) ? $trans_no :"") ?>">

            <div class="col-md-2 form-group">
                <label for="trans_no">GRN No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control" value="<?=(!empty($gateInwardData->trans_number))?$gateInwardData->trans_number:(!empty($trans_number) ? $trans_number :"")?>" readonly>
            </div>

            <div class="col-md-2 form-group">
                <label for="trans_date">GRN Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?=(!empty($gateInwardData->trans_date)?$gateInwardData->trans_date:date("Y-m-d"))?>">
            </div>
            <div class="col-md-2 form-group">
                <label for="doc_no">Inv./Ch No.</label>
                <input type="text" name="doc_no" id="doc_no" class="form-control text-uppercase" value="<?=(!empty($gateInwardData->doc_no))?$gateInwardData->doc_no:((!empty($gateEntryData->doc_no))?$gateEntryData->doc_no:"")?>">
            </div>
			<div class="col-md-2 form-group">
                <label for="doc_date">Inv./Ch Date</label>
                <input type="date" name="doc_date" id="doc_date" class="form-control" value="<?=(!empty($gateInwardData->doc_date))?$gateInwardData->doc_date:date("Y-m-d")?>" >
            </div>
            <div class="col-md-4 form-group">
                <label for="party_id">Party Name</label>
                <select name="party_id" id="party_id" class="form-control basic-select2 req">
                    <option value="">Select Party Name</option>
                    <?=getPartyListOption($partyList,((!empty($gateInwardData->party_id))?$gateInwardData->party_id:((!empty($gateEntryData->party_id))?$gateEntryData->party_id:"")))?>
                </select>                
            </div>
            
        </div>
        <hr style="border: 1.5px solid #dcdcdc;">
        <div class="row" id="itemForm">
            <input type="hidden" id="id" class="itemFormInput">
            <input type="hidden" id="row_index" class="itemFormInput" value="">

            <div class="col-md-6 form-group">
                <label for="item_id">Item Name</label>
                <select id="item_id" class="form-control itemDetails basic-select2 req itemFormInput" data-res_function="resItemDetail">
                    <option value="">Select Item Name</option>
                    <?=getItemListOption($itemList)?>
                </select>
            </div>
            <div class="col-md-2 form-group">
                <label for="qty">Qty.</label>
                <input type="text" id="qty" class="form-control floatOnly calculateQty req itemFormInput" value="0">
            </div>
            <div class="col-md-2 form-group">
                <label for="price">Price</label>
                <input type="text" id="price" class="form-control floatVal itemFormInput" value="">
            </div>
            <div class="col-md-2 form-group">
                <label for="batch_no">Serial No. </label>
                <input type="text" id="batch_no" class="form-control itemFormInput" value="">
            </div>
            <div class="col-md-12 form-group">
                <label for="item_remark">Remark</label>
                <div class="input-group">
                    <input type="text"  id="item_remark" class="form-control itemFormInput" value="">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-success float-right addBatch"><i class="fa fa-plus"></i> Add</button>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="error batch_details"></div>
            <div class="table-responsive">
                <table id="batchTable" class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">#</th>
                            <th style="width:25%;">Item Name</th>
                            <th style="width:10%;">Qty</th>
                            <th style="width:10%;">Price</th>
                            <th style="width:10%;">Serial No</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="batchData">                            
                        <tr id="noData">
                            <td class="text-center" colspan="8">No data available in table</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
<script src="<?php echo base_url();?>assets/js/custom/gate-inward-form.js?v=<?=time()?>"></script>
<?php
    if(!empty($gateInwardData->itemData)):

        foreach($gateInwardData->itemData as $row):
			$row->row_index = "";
            echo "<script>AddBatchRow(".json_encode($row).");</script>";
        endforeach;
    endif;
?>