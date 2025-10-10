
<form>
    <div class="col-md-12">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Grn No </th>
                            <th>Grn Date </th>
                            <th>Party Name</th>
                            <th>Item Name</th>
                            <th>Location</th>
                            <th>Heat No.</th>
                            <th>Grn Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=$dataRow->trans_number?></td>
                            <td><?=formatDate($dataRow->trans_date)?></td>
                            <td><?=$dataRow->party_name?></td>
                            <td><?=(!empty($dataRow->item_code)?"[ ".$dataRow->item_code." ] ":"").$dataRow->item_name?></td>
                            <td><?=$dataRow->location_name?></td>
                            <td><?=$dataRow->heat_no?></td>
                            <td><?=floatVal($dataRow->qty)?></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div class="row">
                    <input type="hidden" name="id" id="id" value="<?=(!empty($inInspectData->id))?$inInspectData->id:""?>" />
                    <input type="hidden" name="mir_id" id="mir_id" value="<?=(!empty($dataRow->mir_id))?$dataRow->mir_id:""?>" />
                    <input type="hidden" name="mir_trans_id" id="mir_trans_id" value="<?=(!empty($dataRow->id))?$dataRow->id:""?>" />
                    <input type="hidden" name="item_id" id="item_id" value="<?=(!empty($dataRow->item_id))?$dataRow->item_id:""?>" />
                    <?php $sample_size = (!empty($inInspectData->sampling_qty)) ? floatval($inInspectData->sampling_qty) : 5 ;?>
                                
                    <div class="col-md-2 form-group">  
                        <label for="sampling_qty">Sampling Qty.</label>
                        <div class="input-group">
                            <input type="text" name="sampling_qty" id="sampling_qty" class="form-control floatOnly req" value="<?=$sample_size?>" />
                            <button type="button" class="btn waves-effect waves-light btn-success float-center loaddata" title="Load Data">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>   
                    <div class="table-responsive">
                        <table id="preDispatchtbl" class="table table-bordered generalTable">
                            <thead class="thead-info" id="theadData">
                                <tr style="text-align:center;">
                                    <th rowspan="2" style="width:5%;">#</th>
                                    <th rowspan="2" style="width:20%">Parameter</th>
                                    <th rowspan="2" style="width:20%">Specification</th>
                                    <th rowspan="2" style="width:10%">Tolerance</th>
                                    <th rowspan="2" style="width:15%">Instrument</th>
                                    <th colspan="<?= $sample_size?>">Observation on Samples</th>
                                    <th rowspan="2" style="width:10%">Result</th>
                                </tr>
                                <tr style="text-align:center;">
                                    <?php for($j=1;$j<=$sample_size;$j++):?> 
                                        <th><?= $j ?></th>
                                    <?php endfor;?>    
                                </tr>
                            </thead>
                            <tbody id="tbodyData"> </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        
		setTimeout(function(){ $('.loaddata').trigger('click');}, 500);
        $(document).on('click', '.loaddata', function(e) {
            e.stopImmediatePropagation();e.preventDefault();

            var mir_trans_id = $("#mir_trans_id").val();
            var item_id = $('#item_id').val();
            var sampling_qty = $("#sampling_qty").val();
            if (sampling_qty) {
                $.ajax({
                    url: base_url + controller + '/getIncomingInspectionData',
                    data: {
                        mir_trans_id: mir_trans_id,
                        item_id: item_id,
                        sampling_qty:sampling_qty
                    },
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $("#theadData").html(data.theadData);
                        $("#tbodyData").html(data.tbodyData);
                    }
                });
            }
        });

    });

</script>