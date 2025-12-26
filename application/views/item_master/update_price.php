<form id="updatePrice">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 form-group">
                <select name="category_id" id="category_id" class="form-control basic-select2">
                    <option value="0">Select Category</option>
                    <?php
                        foreach ($categoryList as $row) :
                            $selected = (!empty($dataRow->category_id) && $dataRow->category_id == $row->id) ? "selected" : "";
                            echo '<option value="' . $row->id . '" ' . $selected . ' data-category_name = '.$row->category_name.'>' . $row->category_name . '</option>';
                        endforeach;
                    ?>
                </select>
            </div>  
        </div>  
        <hr>
        <div class="row">
            <div class="error general_error"></div>
            <div class="table-responsive">
                <table id="priceTbl" class="table table-bordered align-items-center">
                    <thead class="thead-info">
                        <tr class="text-center">
                            <th style="width:5%;">#</th>
                            <th>Item Name</th>
                            <th>Gst Per</th>
                            <th>Price</th>
                            <th>MRP</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyData">
                        <tr>
                            <td colspan="5" class="text-center">No data available in table</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>  
    </div>
</form>

<script> 
$(document).ready(function(){
    $(document).on('change',"#category_id",function(e){
        e.stopImmediatePropagation();e.preventDefault();

        var category_id = $('#category_id').val() || 0;
        $.ajax({
            url : base_url + controller + '/updatePriceData',
            type : 'post',
            data : { category_id:category_id},
            dataType : 'json'
        }).done(function(response){
            $("#tbodyData").html(response.tbody);
        });
        initSelect2();
    });

    $(document).on('change','.calculateMRP',function(){ 
        var rowId = $(this).closest('tr').index() + 1; 
        var gst_per = parseFloat($("#gst_per_" + rowId).val()) || 0;  
        var price = parseFloat($("#price_" + rowId).val()) || 0;
        var inc_price = parseFloat($("#inc_price_" + rowId).val()) || 0;

        if(gst_per > 0){
            if($(this).attr('id') == "price_" + rowId && price > 0){
                var tax_amt = (price * gst_per) / 100;
                var new_mrp = price + tax_amt;
                $("#inc_price_" + rowId).val(new_mrp.toFixed(2));
            }

            if(($(this).attr('id') == "inc_price_" + rowId || $(this).attr('id') == "gst_per_" + rowId) && inc_price > 0){
                var gstReverse = ( (gst_per + 100) / 100 );
                var new_price = inc_price / gstReverse;
                $("#price_" + rowId).val(new_price.toFixed(2));
            }
        } else {
            if($(this).attr('id') == "price_" + rowId && price > 0){
                $("#inc_price_" + rowId).val(price.toFixed(2));
            }

            if(inc_price > 0){
                $("#price_" + rowId).val(inc_price.toFixed(2));
            }
        }
    });

});
</script>