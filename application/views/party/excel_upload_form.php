<form>
    <div class="col-md-12">
        <!-- Excel Config Section Start -->
        <!-- <div class="row form-group">
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary btn-block" title="Click Me" data-bs-toggle="collapse" href="#import_excel_section" role="button" aria-expanded="false" aria-controls="import_excel"> Excel Config.</button>
            </div>
            <div class="col-md-10">
                <hr>
            </div>
        </div> -->

        <!-- <section class="collapse multi-collapse"> -->
            <div class="row" id="input_excel_column">
                <div class="table-responsive">
                    <table class="table jpExcelTable">
                        <thead class="thead-info">
                            <tr class="text-center">
                                <th>Company/Trade Name</th>
                                <th>Sales Executive</th>
                                <th>Contact Person</th>
                                <th>Contact No.</th>
                                <th>Party Email</th>
                                <th>Credit Days</th>
                                <th>Registration Type</th>
                                <th>Party GSTIN</th>
                                <th>Party PAN</th>
                                <th>Currency</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Pincode</th>
                                <th>Address</th>
                                <th>Delivery Country</th>
                                <th>Delivery State</th>
                                <th>Delivery City</th>
                                <th>Delivery Pincode</th>
                                <th>Delivery Address</th>
                                <th>Start Row</th>
                            </tr>                    
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" id="party_name_column" class="form-control text-center" value="A"></td>
                                <td><input type="text" id="sales_executive_column" class="form-control text-center" value="B"></td>
                                <td><input type="text" id="contact_person_column" class="form-control text-center" value="C"></td>
                                <td><input type="text" id="party_mobile_column" class="form-control text-center" value="D"></td>
                                <td><input type="text" id="party_email_column" class="form-control text-center" value="E"></td>
                                <td><input type="text" id="credit_days_column" class="form-control text-center" value="F"></td>
                                <td><input type="text" id="registration_type_column" class="form-control text-center" value="G"></td>
                                <td><input type="text" id="gstin_column" class="form-control text-center" value="H"></td>
                                <td><input type="text" id="pan_no_column" class="form-control text-center" value="I"></td>
                                <td><input type="text" id="currency_column" class="form-control text-center" value="J"></td>
                                <td><input type="text" id="country_id_column" class="form-control text-center" value="K"></td>
                                <td><input type="text" id="state_id_column" class="form-control text-center" value="L"></td>
                                <td><input type="text" id="city_id_column" class="form-control text-center" value="M"></td>
                                <td><input type="text" id="party_pincode_column" class="form-control text-center" value="N"></td>
                                <td><input type="text" id="party_address_column" class="form-control text-center" value="O"></td>
                                <td><input type="text" id="delivery_country_id_column" class="form-control text-center" value="P"></td>
                                <td><input type="text" id="delivery_state_id_column" class="form-control text-center" value="Q"></td>
                                <td><input type="text" id="delivery_city_id_column" class="form-control text-center" value="R"></td>
                                <td><input type="text" id="delivery_pincode_column" class="form-control text-center" value="S"></td>
                                <td><input type="text" id="delivery_address_column" class="form-control text-center" value="T"></td>
                                <td><input type="text" id="start_row" class="form-control text-center numericOnly" value="2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <!-- </section> -->
        <!-- Excel Config Section End -->
        <hr>

        <div class="row">
            <input type="hidden" id="id" value="">
            <input type="hidden" id="party_category" name="party_category" value="<?= $party_category?>">
            <div class="col-md-6 form-group">
                <label for="">Select File</label>
                <div class="input-group">
                    <a href="<?=base_url("assets/uploads/defualt/party_excel.xlsx")?>" class="btn btn-outline-info" title="Download Example File" download><i class="fa fa-download"></i></a>
                    <div class="input-group-append">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input form-control" id="excelFile" accept=".xlsx, .xls">
                        </div>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" id="readButton" type="button">Read Excel</button>
                    </div>
                </div>
                <div class="error excel_file"></div>
            </div>
        </div>
    </div>
    
    <hr>

    <div class="col-md-12">
        <div class="error itemData"></div>
        <p class="text-primary font-bold text-right">Can not save duplicate party. duplicate parties are shown with red color.</p>
        <div class="table-responsive">
            <table id="partyDetails" class="table jpExcelTable">
                <thead class="thead-info">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Company/Trade Name</th>
                        <th>Sales Executive</th>
                        <th>Contact Person</th>
                        <th>Contact No.</th>
                        <th>Party Email</th>
                        <th>Credit Days</th>
                        <th>Registration Type</th>
                        <th>Party GSTIN</th>
                        <th>Party PAN</th>
                        <th>Currency</th>
                        <th>Country</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Pincode</th>
                        <th>Address</th>
                        <th>Delivery Country</th>
                        <th>Delivery State</th>
                        <th>Delivery City</th>
                        <th>Delivery Pincode</th>
                        <th>Delivery Address</th>
                    </tr>                    
                </thead>
                <tbody>
                    <tr id="noData">
                        <td colspan="20" class="text-center">No data available in table</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>

<script src="<?php echo base_url(); ?>assets/js/xlsx.full.min.js?v=<?=time()?>"></script>
<script>
var clickedTr = 0;
$(document).ready(function() {
    $(document).on("click",'#readButton',function() {
        var inputArray = [
            "party_name",
            "sales_executive",
            "contact_person",
            "party_mobile",
            "party_email",
            "credit_days",
            "registration_type",
            "gstin",
            "pan_no",
            "currency",
            "country_id",
            "state_id",
            "city_id",
            "party_pincode",
            "party_address",
            "delivery_country_id",
            "delivery_state_id",
            "delivery_city_id",
            "delivery_pincode",
            "delivery_address"
        ];

        const alphaVal = (s) => s.toLowerCase().charCodeAt(0) - 97 + 1;
        
        var start_row = $("#input_excel_column #start_row").val();

        $("#input_excel_column .error").html("");

        $.each(inputArray,function(key,column){
            var alpha_val = $("#"+column+"_column").val();
            var input_val = alphaVal(alpha_val);

            if(input_val == ""){ $("#input_excel_column ."+column+"_column").html("Please input column no."); }

            if(input_val == 0){ $("#input_excel_column ."+column+"_column").html("Please input column no."); }
        });

        if(start_row == ""){ $("#input_excel_column .start_row").html("Please input row no."); }
        if(start_row < 2){ $("#input_excel_column .start_row").html("Please input minimum row no. 2"); }

        var fileInput = document.getElementById('excelFile');
        var file = fileInput.files[0];
        $(".excel_file").html("");
        
        if(file){
            var errorCount = $('#input_excel_column .error:not(:empty)').length;

            if(errorCount == 0){
                var columnCount = $('table#partyDetails thead tr').first().children().length;
                $("table#partyDetails > TBODY").html('<tr><td id="noData" colspan="'+columnCount+'" class="text-center">Loading...</td></tr>'); 

                setTimeout(function(){
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var data = new Uint8Array(e.target.result);
                        var workbook = XLSX.read(data, { type: 'array' });

                        var sheetName = workbook.SheetNames[0]; // Assuming the first sheet
                        var worksheet = workbook.Sheets[sheetName];

                        var jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                        var fileData = [];
                        // Process the data or display it in the table

                        //Remove blank line.
                        $('table#partyDetails > TBODY').html("");              

                        var postData = [];
                        $.each(jsonData,function(ind,row){ 
                            postData = [];
                            if(ind >= (start_row - 1)){
                                var party_id = "";
                                if(row[1]){
                                    row[1] = row[1] || -1;

                                    $.each(inputArray,function(key,column){
                                        var alpha_val = $("#"+column+"_column").val();
                                        var input_val = alphaVal(alpha_val);

                                        if(input_val != ""){ 
                                            postData[column] = row[input_val]  || "";
                                        }
                                    });
                                    $.ajax({
                                        url : base_url + 'parties/checkPartyDuplicate',
                                        type : 'post',
                                        data : { party_name : postData['party_name'], party_mobile : postData['party_mobile']},
                                        global:false,
                                        async:false,
                                        dataType:'json'
                                    }).done(function(res){
                                        party_id = res.party_id;
                                    }); 
                                    postData['party_id'] = party_id;
                                    postData = Object.assign({}, postData);
                                    AddRow(postData);
                                } 
                            } 
                        });
                    };

                    reader.readAsArrayBuffer(file); 
                },200);
            }
        }else{
            $(".excel_file").html("Please Select File.");
        }         
    });
});

function AddRow(data){

    var tblName = "partyDetails";

    //Remove blank line.
	$('table#'+tblName+' tr#noData').remove();

    //Get the reference of the Table's TBODY element.
	var tBody = $("#" + tblName + " > TBODY")[0];    
    var ind = -1 ;
	row = tBody.insertRow(ind);
    $(row).attr('style',((data.party_id == "")?"background:#8ce1d3;":"background:#F88379;"));

    var disabled = ((data.party_id == "")?false:true);

    //Add index cell
	var countRow = ($('#' + tblName + ' tbody tr:last').index() + 1);
	var cell = $(row.insertCell(-1));
	cell.html(countRow);
	cell.attr("style", "width:5%;");

    $(row).attr('id',countRow);

    var mainIdInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][id]", value: $("#id").val() ,disabled:disabled});
    var categoryInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][party_category]",  value: $("#party_category").val() ,disabled:disabled});
    var partyNameInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][party_name]",  value: data.party_name ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.party_name);
    cell.append(partyNameInput);
    cell.append(mainIdInput);
    cell.append(categoryInput);

    var salesExecutiveInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][sales_executive]",  value: data.sales_executive,disabled:disabled });
    cell = $(row.insertCell(-1));
    cell.html(data.sales_executive);
    cell.append(salesExecutiveInput);

    var cPersonInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][contact_person]",  value: data.contact_person ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.contact_person);
    cell.append(cPersonInput);

    var cPhoneInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][party_mobile]",  value: data.party_mobile,disabled:disabled });
    cell = $(row.insertCell(-1));
    cell.html(data.party_mobile);
    cell.append(cPhoneInput);

    var pEmailInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][party_email]",  value: data.party_email ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.party_email);
    cell.append(pEmailInput);

    var creditInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][credit_days]",  value: data.credit_days ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.credit_days);
    cell.append(creditInput);

    var regTypeInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][registration_type]",  value: data.registration_type ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.registration_type);
    cell.append(regTypeInput);
  
    var gstInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][gstin]",  value: data.gstin,disabled:disabled });
    cell = $(row.insertCell(-1));
    cell.html(data.gstin);
    cell.append(gstInput);

    var panNoInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][pan_no]",  value: data.pan_no ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.pan_no);
    cell.append(panNoInput);

    var currencyInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][currency]",  value: data.currency,disabled:disabled });
    cell = $(row.insertCell(-1));
    cell.html(data.currency);
    cell.append(currencyInput);

    var countryIdInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][country_id]",  value: data.country_id,disabled:disabled });
    cell = $(row.insertCell(-1));
    cell.html(data.country_id);
    cell.append(countryIdInput);

    var stateInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][state_id]",  value: data.state_id ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.state_id);
    cell.append(stateInput);

    var cityInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][city_id]",  value: data.city_id ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.city_id);
    cell.append(cityInput);

    var pincodeInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][party_pincode]",  value: data.party_pincode ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.party_pincode);
    cell.append(pincodeInput);  

    var addressInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][party_address]",  value: data.party_address ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.party_address);
    cell.append(addressInput);  

    var deliveryCountryInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][delivery_country_id]",  value: data.delivery_country_id ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.delivery_country_id);
    cell.append(deliveryCountryInput);

    var deliveryStateInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][delivery_state_id]",  value: data.delivery_state_id ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.delivery_state_id);
    cell.append(deliveryStateInput);

    var deliveryCityInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][delivery_city_id]",  value: data.delivery_city_id ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.delivery_city_id);
    cell.append(deliveryCityInput);

    var deliveryPincodeInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][delivery_pincode]",  value: data.delivery_pincode ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.delivery_pincode);
    cell.append(deliveryPincodeInput);

    var deliveryAddressInput = $("<input/>", { type: "hidden", name: "itemData["+countRow+"][delivery_address]",  value: data.delivery_address ,disabled:disabled});
    cell = $(row.insertCell(-1));
    cell.html(data.delivery_address);
    cell.append(deliveryAddressInput);
}
</script>