$(document).ready(function(){	

  	$(document).on('click', '.saveItem', function () {
		var formData = {};
        
		$.each($(".itemFormInput"),function() {
            formData[$(this).attr("id")] = $(this).val();
        });
        $("#itemForm .error").html("");

        if (formData.item_id == "") {
			$(".item_id").html("Item Name is required.");
		}
		if(formData.item_class == "Goods"){
			if (formData.qty == "" || parseFloat(formData.qty) == 0) {
				$(".qty").html("Qty is required.");
			}
		}
		if(formData.item_class == "Service"){
			if (formData.qty > 100) {
				$(".qty").html("QTY/Percentage must be less than or equal to 100.");
			}
		}

        var errorCount = $('#itemForm .error:not(:empty)').length;

		if (errorCount == 0) {			
			formData.id = formData.trans_id;
            var itemData = calculateItem(formData);
            AddRow(itemData);
           
			var selectedItem = $('#itemForm #item_id option:selected');
			$.each($('.itemFormInput'),function(){ $(this).val(""); });

            $("#itemForm input:hidden").val('')
            $('#itemForm #row_index').val("");
			$('#itemForm #item_id option').prop('disabled', false);
			$('#itemForm #qty').prop('readonly',false);
            initSelect2('itemModel');
			setTimeout(function(){
				selectedItem.next().attr('selected', 'selected');
				initSelect2();
				$('.itemDetails').trigger('change');
				setTimeout(function(){
					$("#itemForm #item_id").focus();
				},150);
			},100);	
        }
	});

	$(document).on('input', '#qty', function () {
		var item_type = $('#itemForm #item_class').val();
		if(item_type == "Service"){
			$('#price').val(1);
		}
	});

	$(document).on('input', '#price', function () {
		var item_type = $('#itemForm #item_class').val();
		if(item_type == "Service"){
			$('#qty').val(1);
		}
	});
});

var itemCount = 0;
function AddRow(data) { 
    var tblName = "salesQuotationItems";
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
	var countRow = (data.row_index == "") ? ($('#' + tblName + ' tbody tr:last').index() + 1) : (parseInt(data.row_index) + 1);
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style", "width:5%;");
    var idInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][id]", value: data.id, "class": "item_type", "data-item_type": data.item_class, "data-item_name": data.item_name });
    var itemIdInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][item_id]", class:"item_id", value: data.item_id });
	cell = $(row.insertCell(-1));
    cell.html(data.item_name);
    cell.append(idInput);
    cell.append(itemIdInput);

	var qtyInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][qty]", class:"item_qty", value: data.qty });
	var qtyErrorDiv = $("<div></div>", { class: "error qty" + itemCount });
	cell = $(row.insertCell(-1));

	cell.html(data.qty);
	if(data.item_class == "Service" && data.price > 1){
		cell.html('--');
	}
	cell.append(qtyInput);
	cell.append(qtyErrorDiv);

	var priceInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][price]", value: data.price});
	var priceErrorDiv = $("<div></div>", { class: "error price" + itemCount });
	cell = $(row.insertCell(-1));

	cell.html(data.price);
	if(data.item_class == "Service" && data.qty > 1){
		cell.html('--');
	}
	cell.append(priceInput);
	cell.append(priceErrorDiv);
	
    var amountInput = $("<input/>", { type: "hidden", name: "itemData["+itemCount+"][amount]", class:"amount", value: data.amount });
	cell = $(row.insertCell(-1));
	cell.html(data.amount);
	if(data.item_class == "Service" && data.qty > 1){
		cell.html('--');
	}
	cell.append(amountInput);

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
    claculateColumn();
	itemCount++;
}

function Edit(data, button) {
	var row_index = $(button).closest("tr").index();
	$("#itemModel").modal('show');
	$("#itemModel .btn-save").hide();
	
	$.each(data, function (key, value) {
		 $("#itemForm #" + key).val(value);
	});
	
	$("#itemForm #trans_id").val(data.id);
	$("#itemForm #row_index").val(row_index);

	initSelect2();
}

function Remove(button) {
    var tableId = "salesQuotationItems";
	//Determine the reference of the Row using the Button.
	var row = $(button).closest("TR");
	var table = $("#"+tableId)[0];
	table.deleteRow(row[0].rowIndex);
	$('#'+tableId+' tbody tr td:nth-child(1)').each(function (idx, ele) {
		ele.textContent = idx + 1;
	});
	var countTR = $('#'+tableId+' tbody tr:last').index() + 1;
	if (countTR == 0) {
		$("#tempItem").html('<tr id="noData"><td colspan="15" align="center">No data available in table</td></tr>');
	}

	claculateColumn();
}

function resItemDetail(response = ""){
    if(response != ""){
        var itemDetail = response.data.itemDetail;

        $("#itemForm #item_name").val(itemDetail.item_name);
        $("#itemForm #item_code").val(itemDetail.item_code);
		$("#itemForm #price").val(itemDetail.price);
		$("#itemForm #item_class").val(itemDetail.item_class);
		if(itemDetail.item_class == "Service"){
			$("#itemForm #qty").val(1);
			// $('#itemForm #qty').prop('readonly',true);
		}

    }else{
        $("#itemForm #item_name").val("");
		$("#itemForm #item_class").val("");
		$("#itemForm #price").val("");
    }
	initSelect2();
}

function resSaveQuotation(data,formId){
    if(data.status==1){
        $('#'+formId)[0].reset();
		Swal.fire({ icon: 'success', title: data.message});
        window.location = base_url + controller;
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
			Swal.fire({ icon: 'error', title: data.message });
        }			
    }	
}