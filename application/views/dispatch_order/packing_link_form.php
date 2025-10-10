<form data-res_function="resPackingDetails">
    <div class="col-md-12">
        <div class="row">
            <!-- <input type="hidden" name="id" id="id" value="<?=$orderDetail->id?>"> -->
            <input type="hidden" name="party_id" id="party_id" value="<?=$orderDetail->party_id?>">
            <input type="hidden" name="item_id" id="item_id" value="<?=$orderDetail->item_id?>">
            <input type="hidden" name="so_trans_id" id="so_trans_id" value="<?=$orderDetail->so_trans_id?>">

            <div class="col-md-6 form-group">
                <label for="party_name">Customer Name</label>
                <input type="text" id="party_name" class="form-control" value="<?=$orderDetail->party_name?>" readonly>
            </div>

            <div class="col-md-6 form-group">
                <label for="item_name">Item Name</label>
                <!-- <input type="text" id="item_name" class="form-control" value="<?=$orderDetail->item_name?>" readonly> -->
                <select name="id" id="id" class="form-control select2 orderItemDetail">
                    <option value="">Select Item</option>
                    <?php
                        foreach($orderItemList as $row):
                            $selected = (!empty($orderDetail->id) && $row->id == $orderDetail->id)?"selected":"";
                            $itemName = (!empty($row->item_code))?"[".$row->item_code."] ":"";
                            $itemName .= $row->item_name;
                            echo '<option value="'.$row->id.'" '.$selected.'>'.$itemName.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
 
            <div class="col-md-2 form-group">
                <label for="order_number">Order No.</label>
                <input type="text" name="order_number" id="order_number" class="form-control" value="<?=$orderDetail->order_number?>" readonly>
            </div>

            <div class="col-md-2 form-group">
                <label for="so_number">SO. No.</label>
                <input type="text" id="so_number" class="form-control" value="<?=$orderDetail->so_number?>" readonly>
            </div>

            <div class="col-md-2 form-group">
                <label for="delivery_date">Delivery Date</label>
                <input type="text" id="delivery_date" class="form-control" value="<?=(!empty($orderDetail->delivery_date))?formatDate($orderDetail->delivery_date):""?>" readonly>
            </div>            

            <div class="col-md-2 form-group">
                <label for="order_qty">Qty</label>
                <input type="text" id="order_qty" class="form-control" value="<?=floatval($orderDetail->order_qty)?>" readonly>
            </div>

            <div class="col-md-2 form-group">
                <label for="link_qty">Link Qty</label>
                <input type="text" id="link_qty" class="form-control" value="<?=floatval($orderDetail->link_qty)?>" readonly>
            </div>

            <div class="col-md-2 form-group">
                <label for="pending_qty">Pending Qty</label>
                <input type="text" id="pending_qty" class="form-control" value="<?=floatval($orderDetail->pending_qty)?>" readonly>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12"><h4>Packing Details :</h4></div>

            <div class="error batchDetail"></div>
            <div class="col-md-12">
                <div class="table table-responsive">
                    <table id="batchDetail" class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Location</th>
                                <th>Batch No.</th>
                                <th>Stock (Box Qty)</th>
                                <th>Box Qty</th>
                            </tr>
                        </thead>
                        <tbody id="batchTrans">
                            <tr>
                                <td colspan="4" class="text-center">No data available in table</td>
                            </tr>
                        </tbody>
                        <tfoot class="thead-dark">
                            <tr>
                                <th colspan="3" class="text-right">Total</th>
                                <th>
                                    <span id="total_box">0</span>
                                    <input type="hidden" id="total_qty" value="0">
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <hr>

            <div class="col-md-12">
                <button type="button" class="btn btn-success btn-save save-form float-right" onclick="customStore({'formId':'linkPacking','fnsave':'savePackingLinkDetails','controller':'dispatchOrder','txt_editor':'','form_close':''});"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </div>
</form>

<hr>

<div class="col-md-12">
    <div class="row">
        <div class="col-md-12"><h4>Dispatch Details :</h4></div>

        <div class="col-md-12">
            <div class="table table-responsive">
                <table id="dispatchDetail" class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Location</th>
                            <th>Batch No.</th>
                            <th>Box Qty.</th>
                            <th>Total Qty.</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="dispatchTrans">
                        <tr>
                            <td colspan="6" class="text-center">No data available in table</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    setTimeout(function(){
        var batchDetail = {'postData':{'item_id':$("#item_id").val()},'table_id':"batchDetail",'tbody_id':'batchTrans','tfoot_id':'','fnget':'getItemStock'};
        getTransHtml(batchDetail);

        var dispatchDetail = {'postData':{'item_id':$("#item_id").val(), 'order_number':$("#order_number").val(), 'id':$("#id").val()},'table_id':"dispatchDetail",'tbody_id':'dispatchTrans','tfoot_id':'','fnget':'getDispatchOrderTransaction'};
        getTransHtml(dispatchDetail);
    },500);

    $(document).on('change','.orderItemDetail',function(){
        var id = $(this).val();

        $(".id").html("");
        if(id == ""){
            $(".id").html("Item Name is required.");
            return false;
        }

        $("#party_id, #party_name, #item_id, #so_trans_id, #order_number, #so_number, #delivery_date, #order_qty, #link_qty, #pending_qty").val("");       

        $.ajax({
            url : base_url + controller + '/getDispatchOrderItem',
            type : 'post',
            data : {id:id},
            dataType : 'json'
        }).done(function(response){
            var itemDetail = response.orderItemDetail;
            $("#party_id").val(itemDetail.party_id);
            $("#party_name").val(itemDetail.party_name);
            $("#item_id").val(itemDetail.item_id);
            $("#so_trans_id").val(itemDetail.so_trans_id);
            $("#order_number").val(itemDetail.order_number);
            $("#so_number").val(itemDetail.so_number);
            $("#delivery_date").val(itemDetail.delivery_date);
            $("#order_qty").val(itemDetail.order_qty);
            $("#link_qty").val(itemDetail.link_qty);
            $("#pending_qty").val(itemDetail.pending_qty);

            var batchDetail = {'postData':{'item_id':itemDetail.item_id},'table_id':"batchDetail",'tbody_id':'batchTrans','tfoot_id':'','fnget':'getItemStock'};
            getTransHtml(batchDetail);

            var dispatchDetail = {'postData':{'item_id':itemDetail.item_id, 'order_number':itemDetail.order_number, 'id':id},'table_id':"dispatchDetail",'tbody_id':'dispatchTrans','tfoot_id':'','fnget':'getDispatchOrderTransaction'};
            getTransHtml(dispatchDetail);

            $('#total_box').html(0);
            $('#total_qty').val(0);
        });
    });

    $(document).on('keyup change','.calculateBoxQty',function(){
        var row_id = $(this).data('srno');
        var box_qty = $(this).val() || 0;
        var stock_qty = $("#batch_stock_"+row_id).val();
        var size = $("#size_"+row_id).val();
        var batch_qty = 0;

        batch_qty = parseFloat((parseFloat(box_qty) * parseFloat(size))).toFixed(2);
        $("#batch_qty_"+row_id).val(batch_qty);

        $(".batch_qty_"+row_id).html('');
        if(parseFloat(batch_qty) > parseFloat(stock_qty)){
            $(".batch_qty_"+row_id).html('Invalid Qty.');
            $("#batch_qty_"+row_id).val(0);
            $(this).val("");
        }   
        
        var boxQtyArr = $(".calculateBoxQty").map(function(){return $(this).val();}).get();
        var boxQtySum = 0;
        $.each(boxQtyArr,function(){boxQtySum += parseFloat(this) || 0;});
        $('#total_box').html(boxQtySum);

        var batchQtyArr = $(".calculateBatchQty").map(function(){return $(this).val();}).get();
        var batchQtySum = 0;
        $.each(batchQtyArr,function(){batchQtySum += parseFloat(this) || 0;});
        $('#total_qty').val(batchQtySum);
    });
});

function resPackingDetails(data,formId){
    if(data.status==1){
        $('#total_box').html(0);
        $('#total_qty').val(0);
        Swal.fire({ icon: 'success', title: data.message});

        /* var batchDetail = {'postData':{'item_id':$("#item_id").val()},'table_id':"batchDetail",'tbody_id':'batchTrans','tfoot_id':'','fnget':'getItemStock'};
        getTransHtml(batchDetail);

        var dispatchDetail = {'postData':{'item_id':$("#item_id").val(), 'order_number':$("#order_number").val(), 'id':$("#id").val()},'table_id':"dispatchDetail",'tbody_id':'dispatchTrans','tfoot_id':'','fnget':'getDispatchOrderTransaction'};
        getTransHtml(dispatchDetail); */
        $(".orderItemDetail").trigger('change');

        initTable();
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            initTable();
            Swal.fire({ icon: 'error', title: data.message });
        }			
    }	
}

function resRemovePackingLink(response){
    if(response.status==0){
        Swal.fire( 'Sorry...!', response.message, 'error' );
    }else{
        $('#total_box').html(0);
        $('#total_qty').val(0);
        
        initTable();
        Swal.fire( 'Remove!', response.message, 'success' );

        /* var batchDetail = {'postData':{'item_id':$("#item_id").val()},'table_id':"batchDetail",'tbody_id':'batchTrans','tfoot_id':'','fnget':'getItemStock'};
        getTransHtml(batchDetail);

        var dispatchDetail = {'postData':{'item_id':$("#item_id").val(), 'order_number':$("#order_number").val(), 'id':$("#id").val()},'table_id':"dispatchDetail",'tbody_id':'dispatchTrans','tfoot_id':'','fnget':'getDispatchOrderTransaction'};
        getTransHtml(dispatchDetail); */
        $(".orderItemDetail").trigger('change');
    }	
}
</script>