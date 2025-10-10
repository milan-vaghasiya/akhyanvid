$(document).ready(function(){
    $("#party_id").trigger('change');
    $(document).on('change',"#party_id",function(){
        var party_id = $(this).val();
        getPoList(party_id);
    });

    $(document).on('change',"#po_id",function(){
        var po_id = $(this).val();
        getItemList(po_id);
    });

	$(document).on('click','.addBatch',function(e){
        e.stopImmediatePropagation();
        e.preventDefault();
        
        var formData = {};

        formData.mir_id = "";
        formData.id = "";

        formData.po_number = $("#po_id :selected").data('po_no');
        formData.item_name = $("#item_id :selected").text();
        formData.qty = $("#qty").val();
        formData.po_trans_id = $("#po_trans_id").val();
        formData.po_id = $("#po_id").val();
        formData.item_id = $("#item_id").val();
		formData.location_id = $("#location_id").val();
		formData.unit_id = $("#unit_id").val();
		formData.conversion_value = $("#conversion_value").val();
        formData.trans_status = 0;        
        var smplWt = $(".avg_weight").map(function(){return $(this).val();}).get();
        implodedArray = smplWt.join(",");
        
        formData.weight_sample = implodedArray;        

        $(".error").html("");

        if(formData.item_id == ""){ 
            $('.item_id').html("Item Name is required.");
        }
		if(formData.location_id == ""){ 
            $('.location_id').html("Location is required.");
        }
        if(formData.qty == "" || parseFloat(formData.qty) == 0){ 
            $('.qty').html("Qty is required.");
        }
        if(formData.po_id == ""){ 
            $('.po_id').html("Purchase Order is required.");
        }

        var errorCount = $('.error:not(:empty)').length;

		if(errorCount == 0){
            AddBatchRow(formData);
            $("#qty").val("");
            $("#item_id").val("");$("#item_id").select2();
            $("#location_id").val("");$("#location_id").select2();
            $("#po_trans_id").val("");
            $("#po_id").val("");$("#po_id").select2();     
            $("#unit_id").val("");
            $("#conversion_value").val("");
            $(".error").html("");
            initSelect2();
        }
    });

    $(document).on('keyup change','.avg_weight',function(){
        var weightArray = $(".avg_weight").map(function(){return $(this).val();}).get();
        var weightSum = 0;var count = 0;
        $.each(weightArray,function(){weightSum += parseFloat(this) || 0; if(parseFloat(this) > 0){ count += 1; } });
        var avg_weight = 0;
        avg_weight = (parseFloat(weightSum) > 0)?parseFloat(parseFloat(weightSum) / parseFloat(count)).toFixed(3):0;
        $("#avg_weight").html(avg_weight);
    }); 
   

    $(document).on('change','#unit_id',function(){
        var conValue = $("#unit_id").find(":selected").data('conversion_value') || 0;
		$("#conversion_value").val(conValue);
        initSelect2();
    });
});

function resItemDetail(response = ""){

    if(response != ""){
        var itemDetail = response.data.itemDetail;
        if($("#po_id").find(":selected").val() == ""){
            $("#po_trans_id").val("");
            
			var location_id = (itemDetail.location_id || ''); 
            $("#location_id").val(location_id);
            
            
        }else{
           
            $("#po_trans_id").val(($("#item_id").find(":selected").data('po_trans_id') || 0));
			
			var location_id = ($("#item_id").find(":selected").data('location_id') || ''); 
            $("#location_id").val(location_id);
           
        }        
        if(itemDetail.unit_id != itemDetail.com_unit_id && parseFloat(itemDetail.com_unit_id) > 0){
			html = '<option value="'+itemDetail.unit_id+'" data-conversion_value="'+1+'">'+itemDetail.unit_name+'</option><option value="'+itemDetail.com_unit_id+'" data-conversion_value="'+itemDetail.conversion_value+'">'+itemDetail.com_unit+'</option>';
		}else{
			html = '<option value="'+itemDetail.unit_id+'" data-conversion_value="'+1+'">'+itemDetail.unit_name+'</option>';
		}
    }else{
        
        $("#po_trans_id").val("");
    }
    $("#unit_id").html(html);
    initSelect2();
}

function getPoList(party_id){
    if(party_id){
        $.ajax({
            url : base_url + controller + '/getPoNumberList',
            type : 'post',
            data : {party_id : party_id},
            dataType : 'json'
        }).done(function(response){
            $("#po_id").html(response.poOptions);
        });
    }else{
        $("#po_id").html('<option value="">Select Purchase Order</option>');
    }
    initSelect2();
}

function getItemList(po_id){
    $.ajax({
        url : base_url + controller + '/getItemList',
        type : 'post',
        data : {po_id : po_id},
        dataType : 'json'
    }).done(function(response){
        $("#item_id").html(response.itemOptions);
    });
    initSelect2();
}

var itemCount = 0;
function AddBatchRow(data){
    $('table#batchTable tr#noData').remove();
    //Get the reference of the Table's TBODY element.
	var tblName = "batchTable";
	
	var tBody = $("#"+tblName+" > TBODY")[0];
	
	//Add Row.
	row = tBody.insertRow(-1);
    //Add index cell
	var countRow = $('#'+tblName+' tbody tr:last').index() + 1;
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style","width:5%;");	

    var poIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][po_id]",value:data.po_id});
    var poTransIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][po_trans_id]",value:data.po_trans_id});
    var itemIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][item_id]",value:data.item_id});
    var locationIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][location_id]",value:data.location_id});
    var weightSampleInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][weight_sample]",value:data.weight_sample});
    var unitInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][unit_id]",value:data.unit_id});
    var conversionValueInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][conversion_value]",value:data.conversion_value});
    
    var cell = $(row.insertCell(-1));
	cell.html(data.po_number);
    cell.append(poIdInput);
	cell.append(poTransIdInput);
	cell.append(locationIdInput);
	cell.append(itemIdInput);
	cell.append(weightSampleInput);
	cell.append(conversionValueInput);
	cell.append(unitInput);

    var cell = $(row.insertCell(-1));
	cell.html(data.item_name);

    var mirIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][mir_id]",value:data.mir_id});
    var mirTransIdInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][id]",value:data.id});
    cell.append(mirIdInput);
    cell.append(mirTransIdInput);
	
    
    var batchQtyInput = $("<input/>",{type:"hidden",name:"batchData["+itemCount+"][qty]",value:data.qty});   
    cell = $(row.insertCell(-1));
	cell.html(data.qty);
    cell.append(batchQtyInput);

    //Add Button cell.	
    var btnRemove = $('<button><i class="mdi mdi-trash-can-outline"></i></button>');
	btnRemove.attr("type", "button");
	btnRemove.attr("onclick", "batchRemove(this);");
	btnRemove.attr("style", "margin-left:4px;");
	btnRemove.attr("class", "btn btn-outline-danger btn-sm waves-effect waves-light");
    
    cell = $(row.insertCell(-1));
    if(data.trans_status == 0){
    	cell.append(btnRemove);
    }
    else{
    	cell.append('');
    }
    cell.attr("class","text-center");
    cell.attr("style","width:10%;");

    itemCount++;
}

function batchRemove(button){
    var row = $(button).closest("TR");
	var table = $("#batchTable")[0];
	table.deleteRow(row[0].rowIndex);

	$('#batchTable tbody tr td:nth-child(1)').each(function(idx, ele) {
        ele.textContent = idx + 1;
    });
	var countTR = $('#batchTable tbody tr:last').index() + 1;

    if (countTR == 0) {
        $("#batchTable tbody").html('<tr id="noData"><td colspan="9" align="center">No data available in table</td></tr>');
    }
}
