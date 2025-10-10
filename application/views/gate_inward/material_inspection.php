<form>
    <div class="col-md-12">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered">
                        <tr>
                            <th style="width:25%">Item Name</th>
                            <td> <?= (!empty($dataRow->item_code)?$dataRow->item_code:"").$dataRow->item_name ?></td>
                        </tr>
                        <tr>
                            <th >Location</th>
                            <td ><?= $dataRow->location_name ?></td>
                        </tr>
                        <tr>
                            <th>Grn Qty</th>
                            <td > <?= floatVal($dataRow->qty).' '.$dataRow->uom ?></td> 
                        </tr>
                </table>
                <hr>
                <div class="row">
                    <input type="hidden" name="id" id="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : '' ?>" />
                    <input type="hidden" name="mir_id" id="mir_id" value="<?= (!empty($dataRow->mir_id)) ? $dataRow->mir_id : '' ?>" />
                    <input type="hidden" name="inspection_date" id="inspection_date" value="<?= (!empty($dataRow->inspection_date)) ? $dataRow->inspection_date : getFyDate() ?>" />
                
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr class="bg-light">
                                <td>Ok Qty</td>
                                <td>
                                    <input type="text" id="ok_qty" name="ok_qty" class="form-control floatOnly" value="<?= (!empty($dataRow->ok_qty)) ? floatval($dataRow->ok_qty) : '' ?>" >
                                </td>
                            </tr>
                            <tr class="bg-light">
                                <td>Reject Qty</td>
                                <td>
                                    <input type="text" name="reject_qty" id="reject_qty" class="form-control floatOnly" value="<?= (!empty($dataRow->reject_qty)) ? floatval($dataRow->reject_qty) : '' ?>">
                                </td>
                            </tr>
                            <tr class="bg-light">
                                <td>Short Qty.</td>
                                <td>
                                    <input type="text" name="short_qty" id="short_qty" class="form-control floatOnly" value="<?= (!empty($dataRow->short_qty)) ? floatval($dataRow->short_qty) : '' ?>">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>