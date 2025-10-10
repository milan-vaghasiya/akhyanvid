var itemCount = 0;

$(document).ready(function(){
    $(document).on('click', '.saveItem', function () {
		var formData = {};
        $.each($(".itemFormInput"),function() { formData[$(this).attr("id")] = $(this).val(); });
        $("#itemForm .error").html("");

        if (formData.item_id == "") { $(".item_id").html("Item Name is required.") }

        if (formData.total_crt == "") { $(".total_crt").html("Cartoon No is required."); }

		if (formData.qty_crt == "" || parseFloat(formData.qty_crt) <= 0) { $(".qty_crt").html("Qty is required."); }
        
        var errorCount = $('#itemForm .error:not(:empty)').length;

		if (errorCount == 0) {
			formData.item_name = $("#item_id :selected").data('item_name');
			
			AddRow(formData);
			
			var selectedItem = $('#itemForm #item_id option:selected');
			$('#itemForm #row_index').val("");
			$("#itemForm #total_box").val('');
			$("#itemForm #qty_box").val('');
			$("#itemForm #box_size").val('');
			$("#itemForm #box_wt").val('');
			setTimeout(function(){
				setTimeout(function(){
					$("#itemForm #item_id").focus();
				},150);
			},100);
        }
	});

	$(document).on('change', '#item_id', function () {
		var item_id = $("#item_id").val();
		if (item_id) {
			var dc_trans_id = $("#item_id :selected").data('dc_trans_id');
			$("#dc_trans_id").val(dc_trans_id);
		}
	});
});

function AddRow(data) {
    var tblName = "packingListItems";

    //Remove blank line.
	$('table#'+tblName+' tr#noData').remove();

	//Get the reference of the Table's TBODY element.
	var tBody = $("#" + tblName + " > TBODY")[0];

	//Add Row.
	if (data.row_index != "") {
		var trRow = data.row_index;
		//$("tr").eq(trRow).remove();
		$("#" + tblName + " tbody tr:eq(" + trRow + ")").remove();
	}

	var ind = (data.row_index == "") ? -1 : data.row_index;
	row = tBody.insertRow(ind);
	$(row).attr('id',itemCount);
	
	
	// Set Box Count
	var boxCount = parseFloat(data.box_count) || 0;
	var box_no = parseFloat(data.box_no) || 0;
	var total_box = parseFloat(data.total_box) || 0;
	
	var startBox = (boxCount + 1);
	var endBox = (boxCount + total_box);
	var newBoxCount = endBox;
	$("#box_count").val(endBox);
	
	console.log(boxCount +  " => " + startBox +  " => " + endBox +  " => " + newBoxCount);
	

    //Add index cell
	var countRow = (data.row_index == "") ? ($('#' + tblName + ' tbody tr:last').index() + 1) : (parseInt(data.row_index) + 1);
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style", "width:5%;");

    var idInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][id]", value: data.id });
    var itemIdInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][item_id]", class:"item_id", value: data.item_id });
    var dcInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][dc_trans_id]", value: data.dc_trans_id });
	
    cell = $(row.insertCell(-1));
    cell.html(data.item_name);
    cell.append(idInput, itemIdInput, dcInput);

	var boxNoInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][box_no]", class:"crtNo", value: data.box_no });
	cell = $(row.insertCell(-1));
	cell.html(data.box_no);
	cell.append(boxNoInput);

	var totalBoxInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][total_box]", class:"totalCrt", value: data.total_box });
	cell = $(row.insertCell(-1));
	cell.html(data.total_box);
	cell.append(totalBoxInput);

    var qtyInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][qty_box]", class:"itemQty", value: data.qty_box });
	var qtyErrorDiv = $("<div></div>", { class: "error qty" + itemCount });
	cell = $(row.insertCell(-1));
	cell.html(data.qty_box);
	cell.append(qtyInput,qtyErrorDiv);

    var boxSizeInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][box_size]", class:"boxSize", value: data.box_size });
	cell = $(row.insertCell(-1));
	cell.html(data.box_size);
	cell.append(boxSizeInput);

	var boxWtInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][box_wt]", class:"boxWt", value: data.box_wt });
	cell = $(row.insertCell(-1));
	cell.html(data.box_wt);
	cell.append(boxWtInput);
   

    //Add Button cell.
	cell = $(row.insertCell(-1));
	var btnRemove = $('<button><i class="mdi mdi-trash-can-outline"></i></button>');
	btnRemove.attr("type", "button");
	btnRemove.attr("onclick", "Remove(this);");
	btnRemove.attr("style", "margin-left:4px;");
	btnRemove.attr("class", "btn btn-outline-danger btn-sm waves-effect waves-light");

	var btnEdit = $('<button><i class="mdi mdi-square-edit-outline"></i></button>');
	btnEdit.attr("type", "button");
	btnEdit.attr("onclick", "Edit(" + JSON.stringify(data) + ",this);");
	btnEdit.attr("class", "btn btn-outline-warning btn-sm waves-effect waves-light");

	cell.append(btnEdit);
	cell.append(btnRemove);
	cell.attr("class", "text-center");
	cell.attr("style", "width:10%;");
	itemCount++;
}

function Edit(data, button) {
	var row_index = $(button).closest("tr").index();
	$.each(data, function (key, value) {
		$("#itemForm #" + key).val(value);
	});

	setTimeout(function(){
		$('#itemForm #qty').prop('readonly',true);
	},500);
	initSelect2();
	$("#itemForm #row_index").val(row_index);
}


function Remove(button) {
    var tableId = "packingListItems";

	//Determine the reference of the Row using the Button.
	var row = $(button).closest("TR");
	var table = $("#"+tableId)[0];
	table.deleteRow(row[0].rowIndex);

	$('#'+tableId+' tbody tr td:nth-child(1)').each(function (idx, ele) {
		ele.textContent = idx + 1;
	});

	var countTR = $('#'+tableId+' tbody tr:last').index() + 1;
	if (countTR == 0) {
		$("#tempItem").html('<tr id="noData"><td colspan="10" align="center">No data available in table</td></tr>');
	}

}

function resFinalPacking(data,formId){
    if(data.status==1){
        $('#'+formId)[0].reset();
        Swal.fire({ icon: 'success', title: data.message});
        window.location.href = base_url + '/deliveryChallan';
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            Swal.fire({ icon: 'error', title: data.message });
        }
    }
}
