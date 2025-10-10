<form data-res_function="getReqResponse">
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" value="<?= (!empty($dataRow->id)) ? $dataRow->id : ""; ?>" />
            <input type="hidden" name="trans_no" value="<?= (!empty($dataRow->trans_no)) ? $dataRow->trans_no : $trans_no; ?>" />

            <div class="col-md-4 form-group">
                <label for="trans_number">Req. No.</label>
                <input type="text" name="trans_number" id="trans_number" class="form-control req" value="<?= (!empty($dataRow->trans_number)) ? $dataRow->trans_number : $trans_number ?>" readonly>
            </div>
           
            <div class="col-md-4 form-group">
                <label for="trans_date">Req. Date</label>
                <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?= (!empty($dataRow->trans_date)) ? $dataRow->trans_date : getFyDate() ?>" />
            </div>

            <hr>
            <div class="row" id="itemForm">
                <input type="hidden" id="id" class="itemFormInput">
                <input type="hidden" id="row_index" class="itemFormInput">

                <div class="col-md-8 form-group">
                    <label for="item_id">Item </label>
                    <select id="item_id" class="form-control select2 req itemFormInput">
                        <option value="">Select Item</option>
                        <?php
                            if(!empty($itemData)){
                                foreach ($itemData as $row) {
                                    echo '<option value="'.$row->id.'">'.(!empty($row->item_code) ? '['.$row->item_code.'] ' : '').$row->item_name.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
                
                <div class="col-md-4 form-group">
                    <label for="req_qty">Req. Qty</label>
                    <input type="text" id="req_qty" class="form-control req itemFormInput" >
                </div>

                <div class="col-md-12 form-group">
                    <label for="remark">Remark</label>
                    <div class="input-group">
                        <input type="text"  id="remark" class="form-control itemFormInput" rows="1" style="width:85%" value="">
                    
                        <button type="button" class="btn btn-info float-right addReqItem" style="width:15%" ><i class="fa fa-plus"></i> Add</button>
                    </div>
                </div>
            </div>            
        </div>
    </div>

    <hr>
    <div class="col-md-12">
        <div class="error general_error"></div>
        <div class="table-responsive">
            <table class="table jpExcelTable" id="reqItemTable">
                <thead class="thead-info">
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Req. Qty</th>
                        <th>Remark</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="reqItemTbody">
                </tbody>
            </table>
        </div>
    </div>
</form>

<script>
$(document).ready(function(){
    $(document).on('click','.addReqItem',function(e){
        e.stopImmediatePropagation(); e.preventDefault();
        
        var formData = {};
        $.each($(".itemFormInput"),function() {  formData[$(this).attr("id")] = $(this).val();  });

        $(".error").html("");

        if (formData.item_id == "") { 
            $(".item_id").html("Item Name is required."); 
        }
        if (formData.req_qty == "" || parseFloat(formData.req_qty) == 0) {  
            $(".req_qty").html("Req. Qty is required."); 
        }     

        var errorCount = $('.error:not(:empty)').length;

        if(errorCount == 0){
            formData.item_name = $("#item_id :selected").text();
            addReqItemRow(formData);
            $("#item_id").val("");$("#item_id").select2();
            $("#req_qty").val("");
            $("#remark").val("");
            $(".error").html("");
            initSelect2();
        }
    });
});
var itemCount = 0;
function addReqItemRow(data){
    //Get the reference of the Table's TBODY element.
	var tblName = "reqItemTable";
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

    var qtyTp = $("<input/>",{type:"hidden",name:"itemData["+itemCount+"][req_qty]",value:data.req_qty});    
    cell = $(row.insertCell(-1));
	cell.html(data.req_qty);
    cell.append(qtyTp);
	
    var remarkInput = $("<input/>",{type:"hidden",name:"itemData["+itemCount+"][remark]",value:data.remark}); 
    cell = $(row.insertCell(-1));
	cell.html(data.remark);
    cell.append(remarkInput);

    //Add Button cell.	
    var btnEdit = $('<button><i class="mdi mdi-square-edit-outline"></i></button>');
	btnEdit.attr("type", "button");
	btnEdit.attr("onclick", "editReqItem(" + JSON.stringify(data) + ",this);");
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
	var table = $("#reqItemTable")[0];
	table.deleteRow(row[0].rowIndex);

	$('#reqItemTable tbody tr td:nth-child(1)').each(function(idx, ele) {
        ele.textContent = idx + 1;
    });
	var countTR = $('#reqItemTable tbody tr:last').index() + 1;

    if (countTR == 0) {
        $("#reqItemTable tbody").html('<tr id="noData"><td colspan="5" align="center">No data available in table</td></tr>');
    }
}

function editReqItem(data, button) {
	var row_index = $(button).closest("tr").index();
	$.each(data, function (key, value) {
		$("#" + key).val(value);
	});
	$("#row_index").val(row_index);
	initSelect2();
}
</script>

<?php
    if(!empty($reqItemList)):
        foreach($reqItemList as $row):
            if($row->status == 1):
                $row->row_index = "";
                $row->item_name = (!empty($row->item_code) ? '['.$row->item_code.'] ' : '').$row->item_name;
                echo "<script>addReqItemRow(".json_encode($row).");</script>";
            endif;
        endforeach;
    endif;
?>