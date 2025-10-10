$(document).ready(function(){

	$(".ledgerColumn").hide();
	$(".summary_desc").attr('style','width: 60%;');
	
    var gstType = $("#gst_type").val();
	if(gstType == 1){ 
		$(".cgstCol").show();$(".sgstCol").show();$(".igstCol").hide();
		$(".amountCol").hide();$(".netAmtCol").show();
	}else if(gstType == 2){
		$(".cgstCol").hide();$(".sgstCol").hide();$(".igstCol").show();
		$(".amountCol").hide();$(".netAmtCol").show();
	}else{
		$(".cgstCol").hide();$(".sgstCol").hide();$(".igstCol").hide();
		$(".amountCol").show();$(".netAmtCol").hide();
	}

    $(document).on('click','.saveItem',function(){
        var fd = $('#qcItemForm').serializeArray();
        var formData = {};
        $.each(fd,function(i, v) {
            formData[v.name] = v.value;
        });
        $(".category_id").html("");
		$(".qty").html("");
		$(".price").html("");
        if(formData.category_id == ""){
			$(".category_id").html("Category is required..");
		}else{
			var itemIds = $("input[name='category_id[]']").map(function(){return $(this).val();}).get();
			if ($.inArray(formData.item_id,itemIds) >= 0) {
				$(".item_id").html("Item already added.");
			}else {
				if(formData.qty == "" || formData.qty == "0" /* || formData.price == "" || formData.price == "0" */){
					if(formData.qty == "" || formData.qty == "0"){
						$(".qty").html("Qty is required.");
					}
					if(formData.price == "" || formData.price == "0"){
						$(".price").html("Price is required.");
					}
				}else{
					formData.price = (parseFloat(formData.price) > 0)?formData.price:0;
					
					var amount = 0;var total = 0;var disc_amount = 0;var igst_amount = 0;
					var cgst_amount = 0;var sgst_amount = 0;var net_amount = 0; var cgst_per = 0;var sgst_per = 0; var igst_per = 0;
					if(formData.disc_per == "" && formData.disc_per == "0"){
						taxable_amount = parseFloat(parseFloat(formData.qty) * parseFloat(formData.price)).toFixed(2);
					}else{
						total = parseFloat(parseFloat(formData.qty) * parseFloat(formData.price)).toFixed(2);
						disc_amount = parseFloat((total * parseFloat(formData.disc_per))/100).toFixed(2);
						taxable_amount = parseFloat(total - disc_amount).toFixed(2);
					}

					formData.gst_per = igst_per = parseFloat(formData.gst_per).toFixed(0);
            		formData.gst_amount = igst_amt = parseFloat((igst_per * taxable_amount) / 100).toFixed(2);
					
					cgst_per = parseFloat(parseFloat(formData.gst_per)/2).toFixed(2);
					sgst_per = parseFloat(parseFloat(formData.gst_per)/2).toFixed(2);
					
					cgst_amount = parseFloat((cgst_per * taxable_amount )/100).toFixed(2);
					sgst_amount = parseFloat((sgst_per * taxable_amount )/100).toFixed(2);
					
					igst_per = parseFloat(formData.gst_per).toFixed(2);
					igst_amount = parseFloat((igst_per * taxable_amount )/100).toFixed(2);
					
					net_amount = parseFloat(parseFloat(taxable_amount) + parseFloat(igst_amount)).toFixed(2);

                    formData.gst_type = $('#gst_type').val();
					formData.qty = parseFloat(formData.qty).toFixed(2);
					formData.cgst_per = cgst_per;
                    formData.cgst_amount = cgst_amount;
                    formData.sgst_per = sgst_per;
                    formData.sgst_amount = sgst_amount;
                    formData.igst_per = igst_per;
                    formData.igst_amount = igst_amount;
                    formData.disc_amount = disc_amount;
                    formData.taxable_amount = taxable_amount;
                    formData.net_amount = net_amount;
					
					AddRow(formData);
                    $('#qcItemForm')[0].reset();
                    if($(this).data('fn') == "save"){
						$("#qcItemForm #category_id").focus();
                    }else if($(this).data('fn') == "save_close"){
                        $("#itemModel").modal('hide');
                    }   
				}
			}
		}
    });

	$(document).on('click','.add-item',function(){
        $("#itemModel").modal('show');
		$(".btn-close").show();
    	$(".btn-save").show();
		$("#row_index").val("");
	});

    $(document).on('click','.btn-close',function(){
        $('#qcItemForm')[0].reset();
		$('#qcItemForm .error').html("");
    });
});

function resPartyDetail(response = ""){
    var html = '<option value="">Select GST No.</option>';
    if(response != ""){
        var partyDetail = response.data.partyDetail;
        $("#party_name").val(partyDetail.party_name);
		$("#master_t_col_1").val(partyDetail.contact_person);
        $("#master_t_col_2").val(partyDetail.party_mobile);
		
        var gstDetails = response.data.gstDetails;var i = 1;
        $.each(gstDetails,function(index,row){  
			if(row.gstin !=""){
				html += '<option value="'+row.gstin+'" '+((i==1)?"selected":"")+'>'+row.gstin+'</option>';
				i++;
			}
        });         
    }else{
        $("#party_name").val("");
		$("#master_t_col_1").val("");
		$("#master_t_col_2").val("");
    }
    $("#gstin").html(html);
	initSelect2('itemModel');
	gstin();
}

function AddRow(data) {
	console.log(data);
	var tblName = "purchaseOrderItems";

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

	//Add index cell
	var countRow = (data.row_index == "") ? ($('#' + tblName + ' tbody tr:last').index() + 1) : (parseInt(data.row_index) + 1);
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style", "width:5%;");

	var categoryIdInput = $("<input/>", {type:"hidden",name:"itemData["+countRow+"][category_id]",value: data.category_id});
	var reqIdInput = $("<input/>", {type:"hidden",name:"itemData["+countRow+"][request_id]",value:data.request_id});
	cell = $(row.insertCell(-1));
	cell.html(data.category_name);
	cell.append(categoryIdInput);	
	cell.append(reqIdInput);

	var deliveryDateInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][cod_date]",value:data.cod_date});
	cell = $(row.insertCell(-1));
	cell.html(data.cod_date);	
	cell.append(deliveryDateInput);

	var sizeInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][size]",value:data.size});
	cell = $(row.insertCell(-1));
	cell.html(data.size);	
	cell.append(sizeInput);
		
	var makeInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][make]",value:data.make});
	cell = $(row.insertCell(-1));
	cell.html(data.make);	
	cell.append(makeInput);

	var qtyInput = $("<input/>",{type:"hidden",name:"itemData["+countRow+"][qty]",value:data.qty});
	cell = $(row.insertCell(-1));
	cell.html(data.qty);	
	cell.append(qtyInput);

	var priceInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][price]", value: data.price});
	cell = $(row.insertCell(-1));
	cell.html(data.price);
	cell.append(priceInput);

	var cgstPerInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][cgst_per]", value: data.cgst_per });
	var cgstAmtInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][cgst_amount]", class:'cgst_amount', value: data.cgst_amount });
	cell = $(row.insertCell(-1));
	cell.html(data.cgst_amount + '(' + data.cgst_per + '%)');
	cell.append(cgstPerInput);
	cell.append(cgstAmtInput);
	cell.attr("class", "cgstCol");

	var sgstPerInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][sgst_per]", value: data.sgst_per });
	var sgstAmtInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][sgst_amount]", class:"sgst_amount", value: data.sgst_amount });
	cell = $(row.insertCell(-1));
	cell.html(data.sgst_amount + '(' + data.sgst_per + '%)');
	cell.append(sgstPerInput);
	cell.append(sgstAmtInput);
	cell.attr("class", "sgstCol");

	var gstPerInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][gst_per]", class:"gst_per", value: data.gst_per });
	var igstPerInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][igst_per]", value: data.igst_per });
	var gstAmtInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][gst_amount]", class:"gst_amount", value: data.gst_amount });
	var igstAmtInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][igst_amount]", class:"igst_amount", value: data.igst_amount });
	cell = $(row.insertCell(-1));
	cell.html(data.igst_amount + '(' + data.igst_per + '%)');
	cell.append(gstPerInput);
	cell.append(igstPerInput);
	cell.append(gstAmtInput);
	cell.append(igstAmtInput);
	cell.attr("class", "igstCol");

	var discPerInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][disc_per]", value: data.disc_per});
	var discAmtInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][disc_amount]", value: data.disc_amount });
	cell = $(row.insertCell(-1));
	cell.html(data.disc_amount + '(' + data.disc_per + '%)');
	cell.append(discPerInput);
	cell.append(discAmtInput);

	var amountInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][amount]", value: data.taxable_amount });
	var taxableAmountInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][taxable_amount]", class:"taxable_amount", value: data.taxable_amount });
	cell = $(row.insertCell(-1));
	cell.html(data.taxable_amount);
	cell.html(data.amount);
	cell.append(amountInput);
	cell.append(taxableAmountInput);
	cell.attr("class", "amountCol");

	var netAmtInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][net_amount]", value: data.net_amount });
	cell = $(row.insertCell(-1));
	cell.html(data.net_amount);
	cell.append(netAmtInput);
	cell.attr("class", "netAmtCol");

	//Add Button cell.
	cell = $(row.insertCell(-1));
	var btnRemove = $('<button><i class="mdi mdi-trash-can-outline"></i></button>');
	btnRemove.attr("type", "button");
	btnRemove.attr("onclick", "Remove(this);");
	btnRemove.attr("style", "margin-left:4px;");
	btnRemove.attr("class", "btn btn-sm btn-outline-danger waves-effect waves-light");

	var btnEdit = $('<button><i class="mdi mdi-square-edit-outline"></i></button>');
	btnEdit.attr("type", "button");
	btnEdit.attr("onclick", "Edit(" + JSON.stringify(data) + ",this);");
	btnEdit.attr("class", "btn btn-sm btn-outline-warning waves-effect waves-light");

	cell.append(btnEdit);
	cell.append(btnRemove);
	cell.attr("class", "text-center");
	cell.attr("style", "width:10%;");

	var gst_type = $("#gst_type").val();
	if (gst_type == 1) {
		$(".cgstCol").show(); $(".sgstCol").show(); $(".igstCol").hide();
		$(".amountCol").hide(); $(".netAmtCol").show();
	} else if (gst_type == 2) {
		$(".cgstCol").hide(); $(".sgstCol").hide(); $(".igstCol").show();
		$(".amountCol").hide(); $(".netAmtCol").show();
	} else {
		$(".cgstCol").hide(); $(".sgstCol").hide(); $(".igstCol").hide();
		$(".amountCol").show(); $(".netAmtCol").hide();
	}

	claculateColumn();
}

function Edit(data,button){
	var row_index = $(button).closest("tr").index();
    $("#itemModel").modal("show");
    $(".btn-close").hide();
    $(".btn-save").hide();
    var fnm = "";
    $.each(data,function(key, value) {
		$("#qcItemForm #"+key).val(value);
	}); 
	initSelect2('itemModel');
	$("#qcItemForm #row_index").val(row_index);	
}

function Remove(button) {
	var tableId = "purchaseOrderItems";
    //Determine the reference of the Row using the Button.
	var row = $(button).closest("TR");
	var table = $("#"+tableId)[0];
	table.deleteRow(row[0].rowIndex);
	$('#'+tableId+' tbody tr td:nth-child(1)').each(function(idx, ele) {
        ele.textContent = idx + 1;
    });
	var countTR = $('#'+tableId+' tbody tr:last').index() + 1;
	if(countTR == 0){
		$("#tempItem").html('<tr id="noData"><td colspan="9" align="center">No data available in table</td></tr>');
	}	
	claculateColumn();
}

function saveOrder(formId){
	var fd = $('#'+formId).serialize();
	$.ajax({
		url: base_url + controller + '/save',
		data:fd,
		type: "POST",
		dataType:"json",
	}).done(function(data){
		if(data.status===0){
			$(".error").html("");
			$.each( data.message, function( key, value ) {
				$("."+key).html(value);
			});
		}else if(data.status==1){
			Swal.fire( 'Success', data.message, 'success' );
            window.location = base_url + controller;
		}else{
			Swal.fire( 'Sorry...!', data.message, 'error' );
		}				
	});
}