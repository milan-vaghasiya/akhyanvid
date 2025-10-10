<form data-res_function="getIndentResponse">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />
            <input type="hidden" name="trans_no" value="<?= (!empty($dataRow->trans_no)) ? $dataRow->trans_no : $trans_no; ?>" />
            <input type="hidden" name="trans_prefix" value="<?= (!empty($dataRow->trans_prefix)) ? $dataRow->trans_prefix : $trans_prefix; ?>" />
            <input type="hidden" name="entry_type" value="<?= (!empty($dataRow->entry_type)) ? $dataRow->entry_type : $entry_type; ?>" />

            <div class="col-md-4 form-group">
                <label for="trans_number">Indent No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control req" value="<?= (!empty($dataRow->trans_number)) ? $dataRow->trans_number : $trans_number ?>" readonly>
            </div>
           
            <div class="col-md-4 form-group">
                <label for="trans_date">Indent Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?= (!empty($dataRow->trans_date)) ? $dataRow->trans_date : getFyDate() ?>" />
            </div>

            <hr>
            <div class="row" id="itemForm">
                <input type="hidden" id="id" class="itemFormInput">
                <input type="hidden" id="row_index" class="itemFormInput">
                <div class="col-md-6 form-group">
                    <label for="item_id">Item </label>
                    <select id="item_id" class="form-control select2 req itemFormInput">
                        <option value="">Select Item</option>
                        <?php
                            foreach ($itemList as $row) :
                                echo '<option value="'. $row->id .'" >'.$row->item_name.'</option>';
                            endforeach;
                        ?>
                    </select>
                </div>
                
                <div class="col-md-3 form-group">
                    <label for="qty">Qty</label>
                    <input type="text"  id="qty" class="form-control req  itemFormInput" >
                </div>
                <div class="col-md-3 form-group">
                    <label for="delivery_date">Delivery Date</label>
                    <input type="date"  id="delivery_date" class="form-control itemFormInput" value="<?= getFyDate() ?>" />
                </div>

                <div class="col-md-12 form-group">
                    <label for="remark">Remark</label>
                    <div class="input-group">
                        <input type="text"  id="remark" class="form-control itemFormInput" rows="1" style="width:85%" value="">
                    
                        <button type="button" class="btn btn-info float-right addIndentItem" style="width:15%" ><i class="fa fa-plus"></i> Add</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <hr>
    <div class="col-md-12">
        <div class="error general_error"></div>
        <div class="table-responsive">
            <table class="table jpExcelTable" id="indentItemTable">
                <thead class="thead-info">
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Delivery Date</th>
                        <th>Remark</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="indentItemTbody">

                </tbody>
            </table>
        </div>
    </div>
</form>

<script>
$(document).ready(function(){
    $(document).on('click','.addIndentItem',function(e){
        e.stopImmediatePropagation(); e.preventDefault();
        
        var formData = {};
        $.each($(".itemFormInput"),function() {  formData[$(this).attr("id")] = $(this).val();  });

        $("#itemForm .error").html("");

        if (formData.item_id == "") { $(".item_id").html("Item Name is required."); 	}
        if (formData.qty == "" || parseFloat(formData.qty) == 0) {  $(".qty").html("Qty is required.");   }
     
        $(".error").html("");
        var errorCount = $('.error:not(:empty)').length;

		if(errorCount == 0){
            formData.item_name = $("#item_id :selected").text();
            addIndentItemRow(formData);
            $.each($('.itemFormInput'),function(){ $(this).val(""); });
            $("#itemForm input:hidden").val('')
            $('#itemForm #row_index').val("");
            $(".error").html("");
            initSelect2();
        }
    });
});
var itemCount = 0;
function addIndentItemRow(data){
    //Get the reference of the Table's TBODY element.
	var tblName = "indentItemTable";
    //Remove blank line.
	$('table#'+tblName+' tr#noData').remove();

	//Get the reference of the Table's TBODY element.
	var tBody = $("#" + tblName + " > TBODY")[0];

	//Add Row.
	if (data.row_index != "") {
		var trRow = data.row_index;
		$("#" + tblName + " tbody tr:eq(" + trRow + ")").remove();
	}
	var ind = (data.row_index == "") ? -1 : data.row_index;
	row = tBody.insertRow(ind);
    //Add index cell
	var countRow = $('#'+tblName+' tbody tr:last').index() + 1;

	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style","width:5%;");	

    var itemIdInput = $("<input/>",{type:"hidden",name:"itemData["+itemCount+"][item_id]",value:data.item_id});
    var transIdTp = $("<input/>",{type:"hidden",name:"itemData["+itemCount+"][id]",value:data.id});
    
	var cell = $(row.insertCell(-1));
	cell.html(data.item_name);
	cell.append(itemIdInput);
    cell.append(transIdTp);

    var qtyTp = $("<input/>",{type:"hidden",name:"itemData["+itemCount+"][qty]",value:data.qty});    
    cell = $(row.insertCell(-1));
	cell.html(data.qty);
    cell.append(qtyTp);
    cell.append("<div class='error qty_"+countRow+"'></div>");
	
    var delTp = $("<input/>",{type:"hidden",name:"itemData["+itemCount+"][delivery_date]",value:data.delivery_date});    
    cell = $(row.insertCell(-1));
	cell.html(data.delivery_date);
    cell.append(delTp);
	

    var remarkInput = $("<input/>",{type:"hidden",name:"itemData["+itemCount+"][remark]",value:data.remark}); 
    cell = $(row.insertCell(-1));
	cell.html(data.remark);
    cell.append(remarkInput);

    //Add Button cell.	

    var btnEdit = $('<button><i class="mdi mdi-square-edit-outline"></i></button>');
	btnEdit.attr("type", "button");
	btnEdit.attr("onclick", "editIndentItem(" + JSON.stringify(data) + ",this);");
	btnEdit.attr("class", "btn btn-warning btn-sm waves-effect waves-light");

    var btnRemove = $('<button><i class="mdi mdi-trash-can-outline"></i></button>');
	btnRemove.attr("type", "button");
	btnRemove.attr("onclick", "itemRemove(this);");
	btnRemove.attr("style", "margin-left:4px;");
	btnRemove.attr("class", "btn btn-outline-danger btn-sm waves-effect waves-light");
    
    cell = $(row.insertCell(-1));
    cell.append(btnEdit);
    cell.append(btnRemove);
    cell.attr("class","text-center");
    cell.attr("style","width:10%;");

    itemCount++;
}
function itemRemove(button){
    var row = $(button).closest("TR");
	var table = $("#indentItemTable")[0];
	table.deleteRow(row[0].rowIndex);

	$('#indentItemTable tbody tr td:nth-child(1)').each(function(idx, ele) {
        ele.textContent = idx + 1;
    });
	var countTR = $('#indentItemTable tbody tr:last').index() + 1;

    if (countTR == 0) {
        $("#indentItemTable tbody").html('<tr id="noData"><td colspan="10" align="center">No data available in table</td></tr>');
    }
}

function editIndentItem(data, button) {
	var row_index = $(button).closest("tr").index();
	$.each(data, function (key, value) {
		$("#" + key).val(value);
	});
	$("#row_index").val(row_index);
	initSelect2();
}
</script>
<?php
    if(!empty($indentItems)):
        foreach($indentItems as $row):
            $row->row_index = "";
            echo "<script>addIndentItemRow(".json_encode($row).");</script>";
        endforeach;
    endif;

    if(!empty($reqItems)):
        foreach($reqItems as $row):
            $row = (object)$row;
            $row->row_index = "";
            echo "<script>addIndentItemRow(".json_encode($row).");</script>";
        endforeach;
    endif;
?>