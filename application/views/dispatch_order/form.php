<form>
    <div class="col-md-12">
        <div class="row <?=(!empty($add_item))?"hidden":""?>">
            <div class="col-md-3 form-group">
                <label for="order_number">Order No.</label>
                <input type="text" name="order_number" id="order_number" class="form-control" value="<?=$order_number?>" readonly>

                <input type="hidden" name="order_prefix" id="order_prefix" value="<?=$order_prefix?>">
                <input type="hidden" name="order_no" id="order_no" value="<?=$order_no?>">
                <input type="hidden" name="add_item" id="add_item" value="<?=$add_item?>">
            </div>

            <div class="col-md-3 form-group">
                <label for="order_date">Order Date</label>
                <input type="date" name="order_date" id="order_date" class="form-control fyDates req" value="<?=(!empty($order_date))?$order_date:getFyDate()?>">
            </div>

            <div class="col-md-6 form-group">
                <label for="party_id">Customer Name</label>
                <div class="input-group">
                    <div class="input-group-append" style="width:75%;">
                        <select name="party_id" id="party_id" class="form-control select2 req">
                            <option value="">Select Customer</option>
                            <?=getPartyListOption($partyList,((!empty($party_id))?$party_id:""))?>
                        </select>
                    </div>
                    <div class="input-group-appen">
                        <button type="button" class="btn btn-success loadData">
                            <i class="fa fa-refresh"></i> Load
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="error orderError"></div>
                <div class="table table-responsive">
                    <table id="orderTable" class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>SO. No.</th>
                                <th>SO. Date</th>
                                <th>Delivery Date</th>
                                <th>Item Name</th>
                                <th>SO. Qty</th>
                                <th>Pending <br> Dis. Qty</th>
                                <th>Pending <br> DO. Qty.</th>
                                <th>Stock Qty.</th>
                                <th>DO. Qty.</th>
                            </tr>
                        </thead>
                        <tbody id="orderItems"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</form>
<script>
var tableOptions = {
    responsive: true,
    "autoWidth" : false,
    order:[],
    "columnDefs": [
        { type: 'natural', targets: 0 },
        { orderable: false, targets: "_all" }, 
        { className: "text-left", targets: [0,1] }, 
        { className: "text-center", "targets": "_all" } 
    ],
    paging : false,
    language: { search: "" },
    dom: "<'row'<'col-sm-7'B><'col-sm-5'f>>" +"<'row'<'col-sm-12't>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: {
        dom: {
            button: {
                className: "btn btn-outline-dark"
            }
        },
        buttons:[ 
            {
                extend: 'excel',
                exportOptions: {
                    columns: "thead th:not(.noExport)"
                }
            }
        ]
    },
};
$(document).ready(function(){
    reportTable("orderTable",tableOptions);

    if($("#party_id").val() != ""){
        setTimeout(function(){ $(".loadData").trigger('click'); },500);
    }

    $(document).on('click','.loadData',function(e){
        e.stopImmediatePropagation();
        e.preventDefault();

        var party_id = $("#party_id").val();

        $(".party_id").html("");
        if(party_id == ""){
            $(".party_id").html("Customer Name is required.");
            return flase;
        }

        $.ajax({
            url : base_url + controller + '/getPartyOrderItems',
            type : 'post',
            data : {party_id : party_id},
            dataType : 'json',
            beforeSend: function() {
				var columnCount = $('#orderTable thead tr').first().children().length;
				$("#orderTable TBODY").html('<tr><td colspan="'+columnCount+'" class="text-center">Loading...</td></tr>');
			},
        }).done(function(response){
            $("#orderTable").DataTable().clear().destroy();
            $("#orderItems").html("");
            $("#orderItems").html(response.tbody);
            reportTable('orderTable',tableOptions);
        });
    });

    $(document).on('keyup change','.checkStock',function(e){
        e.stopImmediatePropagation();e.preventDefault();
        var row_id = $(this).data('row_id');
        var stock_qty = parseFloat($(this).data('stock_qty')) || 0;
        var do_qty = parseFloat($(this).val()) || 0;
        $(".order_qty_"+row_id).html("");
        if(do_qty > stock_qty){
            $(".order_qty_"+row_id).html("Invalid Qty");
            $(this).val("");
        }

    });
});
</script>