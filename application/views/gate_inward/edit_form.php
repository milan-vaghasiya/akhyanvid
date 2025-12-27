<form>
    <div class="col-md-12">
        <div class="row">

            <input type="hidden" name="id" value="<?=(!empty($grnData->id))?$grnData->id:""?>">
            <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=(!empty($grnData->trans_prefix))?$grnData->trans_prefix:(!empty($trans_prefix) ? $trans_prefix :"")?>">
            <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($grnData->trans_no))?$grnData->trans_no:(!empty($trans_no) ? $trans_no :"") ?>">

            <div class="col-md-2 form-group">
                <label for="trans_no">GRN No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control" value="<?=(!empty($grnData->trans_number))?$grnData->trans_number:(!empty($trans_number)?$trans_number:'')?>" readonly>
            </div>

            <div class="col-md-2 form-group">
                <label for="trans_date">GRN Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?=(!empty($grnData->trans_date)?$grnData->trans_date:date("Y-m-d"))?>">
            </div>

            <div class="col-md-2 form-group">
                <label for="doc_no">Inv./Ch No.</label>
                <input type="text" name="doc_no" id="doc_no" class="form-control text-uppercase" value="<?=(!empty($grnData->doc_no))?$grnData->doc_no:""?>">
            </div>

			<div class="col-md-2 form-group">
                <label for="doc_date">Inv./Ch Date</label>
                <input type="date" name="doc_date" id="doc_date" class="form-control" value="<?=(!empty($grnData->doc_date))?$grnData->doc_date:date("Y-m-d")?>" >
            </div>

            <div class="col-md-4 form-group">
                <label for="party_id">Party Name</label>
                <select name="party_id" id="party_id" class="form-control basic-select2">
                    <option value="">Select Party Name</option>
                    <?php
                    if (!empty($partyList)) {
                        foreach ($partyList as $row) {
                            $selected = (!empty($grnData->party_id) && $grnData->party_id == $row->id) ? 'selected' : ((!empty($gateEntryData->party_id) && $gateEntryData->party_id == $row->id) ? 'selected' : '');

                            $disabled = (!empty($grnData->party_id) && $grnData->party_id == $row->id) ? '' : ((!empty($gateEntryData->party_id) && $gateEntryData->party_id == $row->id) ? '' : 'disabled');

                            echo '<option value="'.$row->id.'" '.$selected.' '.$disabled.'>'.$row->party_name.'</option>';
                        }
                    }
                    ?>
                </select>                
            </div>           
        </div>

        <hr style="border: 1.5px solid #dcdcdc;">
        <div class="row" id="itemForm">
            <input type="hidden" id="id" name="itemData[trans_id]" value="<?=(!empty($grnTransData->id)?$grnTransData->id:"")?>" class="itemFormInput">

            <div class="col-md-6 form-group">
                <label for="item_id">Item Name</label>
                <select id="item_id" name="itemData[item_id]" class="form-control itemDetails basic-select2 req itemFormInput" data-res_function="resItemDetail">
                    <option value="">Select Item Name</option>
                    <?php
                    if (!empty($itemList)) {
                        foreach ($itemList as $row) {
                            $selected = ((!empty($grnTransData->item_id) && $grnTransData->item_id == $row->id) ? 'selected' : '');
                            $disabled = ((!empty($grnTransData->item_id) && $grnTransData->item_id == $row->id) ? '' : 'disabled');

                            echo '<option value="'.$row->id.'" '.$selected.' '.$disabled.'>'.(!empty($row->item_code) ? '['.$row->item_code.'] ' : '').$row->item_name.'</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-2 form-group">
                <label for="qty">Qty.</label>
                <input type="text" id="qty" name="itemData[qty]" class="form-control floatOnly calculateQty req itemFormInput" value="<?=(!empty($grnTransData->qty)?$grnTransData->qty:"")?>" readonly>
            </div>

            <div class="col-md-2 form-group">
                <label for="price">Price</label>
                <input type="text" id="price" name="itemData[price]" class="form-control floatVal itemFormInput" value="<?=(!empty($grnTransData->price)?$grnTransData->price:"")?>">
            </div>

            <div class="col-md-2 form-group">
                <label for="batch_no">Serial No. </label>
                <input type="text" id="batch_no" name="itemData[batch_no]" class="form-control itemFormInput" value="<?=(!empty($grnTransData->batch_no)?$grnTransData->batch_no:"")?>">
            </div>

            <div class="col-md-12 form-group">
                <label for="item_remark">Remark</label>
                <input type="text"  id="item_remark" name="itemData[item_remark]" class="form-control itemFormInput" value="<?=(!empty($grnTransData->item_remark)?$grnTransData->item_remark:"")?>">
            </div>
        </div>
    </div>
</form>
<script src="<?php echo base_url();?>assets/js/custom/gate-inward-form.js?v=<?=time()?>"></script>