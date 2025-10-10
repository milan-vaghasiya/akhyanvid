var old_no = ""; var old_prefix = "";
$(document).ready(function(){
	$(".ledgerColumn").hide();gstin();
	$(".summary_desc").attr('style','width: 30%;');
	$(".cgstCol").show();$(".sgstCol").show();$(".igstCol").hide();
	$(".amountCol").hide();$(".netAmtCol").show();

	var partyId = $("#party_id").val();
	if(partyId != ""){
		$.ajax({
			url : base_url + '/parties/getPartyDetails',
			type:'post',
			data: {id:partyId},
			dataType : 'json',
		}).done(function(response){
			if(response != ""){
				var partyDetail = response.data.partyDetail;
				$("#itemForm #item_id").attr('data-price_structure_id',partyDetail.price_structure_id);
			}else{
				$("#itemForm #item_id").attr('data-price_stracture_id',"");
			}
		});
	}

	var gst_type = $("#gst_type").val() || 1;
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

	$(document).on('click','#tdsLedger,#tcsLedger',function(){
		if($(this).attr('id') == "tdsLedger"){
			if($("#tdsLedgerOptions").hasClass('ledgerColumn') == true){
				$("#tdsLedgerOptions").removeClass('ledgerColumn').show();
			}else{
				$("#tdsLedgerOptions").addClass('ledgerColumn').hide();
			}
		}

		if($(this).attr('id') == "tcsLedger"){
			if($("#tcsLedgerOptions").hasClass('ledgerColumn') == true){
				$("#tcsLedgerOptions").removeClass('ledgerColumn').show();
			}else{
				$("#tcsLedgerOptions").addClass('ledgerColumn').hide();
			}
		}
	});

	var numberOfChecked = $('.termCheck:checkbox:checked').length;
	$("#termsCounter").html(numberOfChecked);
	$(document).on("click", ".termCheck", function () {
		var id = $(this).data('rowid');
		var numberOfChecked = $('.termCheck:checkbox:checked').length;
		$("#termsCounter").html(numberOfChecked);
		if ($("#md_checkbox" + id).attr('check') == "checked") {
			$("#md_checkbox" + id).attr('check', '');
			$("#md_checkbox" + id).removeAttr('checked');
			$("#term_id" + id).attr('disabled', 'disabled');
			$("#term_title" + id).attr('disabled', 'disabled');
			$("#condition" + id).attr('disabled', 'disabled');
		} else {
			$("#md_checkbox" + id).attr('check', 'checked');
			$("#term_id" + id).removeAttr('disabled');
			$("#term_title" + id).removeAttr('disabled');
			$("#condition" + id).removeAttr('disabled');
		}
	});

	$(document).on('click',".show_terms",function(){$("#termModel").modal('show');});

    $(document).on('keyup', '.calculateSummary', function () { claculateColumn(); });
    $(document).on('change','#gstin', function(){ gstin(); });
	$(document).on('change',"#apply_round",function(){ claculateColumn(); });

	$(document).on('change keyup','#itemForm .calculateQty',function(){
		var packing_qty = $("#itemForm #packing_qty").val() || 0;
		var packing_unit_qty = $("#itemForm #packing_unit_qty").val() || 0;
		var total_box = 0,strip_qty = 0,total_qty = 0;

		if($(this).attr('id') == "total_box"){
			total_box = $("#itemForm #total_box").val() || 0;
			strip_qty = parseFloat(parseFloat(total_box) * parseFloat(packing_qty)).toFixed(2);
			total_qty = parseFloat(parseFloat(strip_qty) * parseFloat(packing_unit_qty)).toFixed(2);

			$("#itemForm #strip_qty").val(strip_qty);
			$("#itemForm #qty").val(total_qty);
		}

		if($(this).attr('id') == "strip_qty"){
			strip_qty = $("#itemForm #strip_qty").val() || 0;
			if(parseFloat(packing_qty) > 0){
				total_box = parseFloat(parseFloat(strip_qty) / parseFloat(packing_qty)).toFixed(2);
			}
			total_qty = parseFloat(parseFloat(strip_qty) * parseFloat(packing_unit_qty)).toFixed(2);

			$("#itemForm #total_box").val(total_box);
			$("#itemForm #qty").val(total_qty);
		}

		if($(this).attr('id') == "qty"){
			total_qty = $("#itemForm #qty").val() || 0;
			if(parseFloat(packing_qty) > 0 && parseFloat(packing_unit_qty) > 0){
				strip_qty = parseFloat(parseFloat(total_qty) / parseFloat(packing_unit_qty)).toFixed(2);
				total_box = parseFloat(parseFloat(strip_qty) / parseFloat(packing_qty)).toFixed(2);
			}

			$("#itemForm #strip_qty").val(strip_qty);
			$("#itemForm #total_box").val(total_box);
		}		
	});

	$(document).on('change blur',"#itemForm .calculatePrice",function(){
		var gst_per = $("#itemForm #gst_per").val() || 0;
		var disc_per = $("#itemForm #disc_per").val() || 0;
        var price = $("#itemForm #price").val() || 0;
        var mrp = $("#itemForm #org_price").val() || 0;

		if($(this).attr('id') == "price" && price > 0){
			var new_mrp = price;
			if(parseFloat(gst_per) > 0){
				var tax_amt = parseFloat( (parseFloat(price) * parseFloat(gst_per) ) / 100 ).toFixed(2);
				new_mrp = parseFloat( parseFloat(price) + parseFloat(tax_amt) ).toFixed(2);
			}
			$("#itemForm #org_price").val(new_mrp);
			return true;
		}

		if($.inArray($(this).attr('id'), ["org_price","gst_per","disc_per"]) >= 0  && mrp > 0){
			/* Use if enter discount per. */
			var disc_amount = 0;
			if(parseFloat(disc_per) > 0){
				disc_amount = parseFloat( (parseFloat(mrp) * parseFloat(disc_per) ) / 100 ).toFixed(2);
				mrp = parseFloat( parseFloat(mrp) - parseFloat(disc_amount) ).toFixed(3);
			}

			/* Use if enter discount amount */
			/* if(parseFloat(disc_amount) > 0){
				mrp = parseFloat( parseFloat(mrp) - parseFloat(disc_amount) ).toFixed(2);
			} */
			var new_price = mrp;

			if(parseFloat(gst_per) > 0){
				var gstReverse = parseFloat(( ( parseFloat(gst_per) + 100 ) / 100 )).toFixed(2);
				new_price = parseFloat( parseFloat(mrp) / parseFloat(gstReverse) ).toFixed(3);
				disc_amount = parseFloat( parseFloat(disc_amount) / parseFloat(gstReverse) ).toFixed(2);
				new_price = parseFloat( parseFloat(new_price) + parseFloat(disc_amount) ).toFixed(3);
			}
			$("#itemForm #price").val(new_price);
			return true;
		}
	});

	$(document).on('change','#tax_class_id',function(){
		var tax_class_id = $(this).val();
		var gst_type = $(this).find(":selected").data('gst_type');
		var sp_acc_id = $(this).find(":selected").data('sp_acc_id');
		var tax_class = $(this).find(":selected").data('tax_class');
		var paertStateCode = $("#party_state_code").val() || 24;
		var company_state_code = $("select[name='cm_id']").find(":selected").data('state_code') || 24;
		$("#tax_class").val(tax_class);
		$("#sp_acc_id").val(sp_acc_id);
		$("#gst_type").val(gst_type);		

		if($.inArray(tax_class, ["EXPORTGSTACC","EXPORTTFACC","IMPORTACC","IMPORTSACC"]) >= 0){
			$(".exportData").removeClass("hidden");
		}else{
			$(".exportData").addClass("hidden");
		}		

		$.ajax({ 
            type: "post",   
            url: base_url + controller + '/getAccountSummaryHtml',   
            data: {tax_class_id:tax_class_id,taxSummary:taxSummary}
        }).done(function(response){
            $("#taxSummaryHtml").html("");
            $("#taxSummaryHtml").html(response);

			if($("select[name='cm_id']").val() != 2){
				$("#exp6_per,#exp7_per").val("0");
			}

			if($(".trans_main_id").val() == ""){
				if($("#inv_type").val() == "PURCHASE"){
					var tds_applicable = $("#tds_applicable").val() || "NO";
					var defual_tds_per = $("#defual_tds_per").val() || 0;
					var defual_tds_acc_id =	$("#defual_tds_acc_id").val() || 0;

					if(tds_applicable != "NO"){	
						if(tds_applicable == "YES-FROM-START"){
							$("#taxSummaryHtml #tds_per").val(defual_tds_per);
							$("#taxSummaryHtml #tds_acc_id").val(defual_tds_acc_id);
							initSelect2();
						} 
						
						if(tds_applicable == "YES-FROM-LIMIT"){
							if(parseFloat(($("#turnover").val() || 0)) >= parseFloat(($("#tds_limit").val() || 0))){		
								$("#taxSummaryHtml #tds_per").val(defual_tds_per);
								$("#taxSummaryHtml #tds_acc_id").val(defual_tds_acc_id);
								initSelect2();
							}else{
								$("#taxSummaryHtml #tds_per").val(0);
							}
						}
					}
				}
				
				if($("#inv_type").val() == "SALES"){
					var tcs_applicable = $("#tcs_applicable").val() || "NO";
					var defual_tcs_per = $("#defual_tcs_per").val() || 0;
					
					if(tcs_applicable != "NO"){	
						if(parseFloat(($("#turnover").val() || 0)) >= parseFloat($("#tcs_limit").val() || 0)){
							$("#taxSummaryHtml #tcs_per").val(defual_tcs_per);
						}
					}
				}
			}	
			$("#taxSummaryHtml .select2").select2();
            
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

			$(".ledgerColumn").hide();
			$(".summary_desc").attr('style','width: 30%;');
			claculateColumn();
        });		

		$(".tax_class_id").html("");
		if(parseInt(paertStateCode) > 0 && $("#party_id").val() != ""){
			if(paertStateCode == company_state_code && gst_type == 2){
				$(".tax_class_id").html("Party State and Gst Type mismatch.");
			}
			if(paertStateCode != company_state_code && gst_type == 1){
				$(".tax_class_id").html("Party State and Gst Type mismatch.");
			}
		}

		claculateColumn();
		initSelect2();
	});

	if($("#company_id :selected").val() != ""){
		$("#cm_id").val(($("#company_id :selected").val() || 1));
		setTimeout(function(){$("#cm_id").trigger('change');},500);
	}	

	old_no = $('#trans_no').val();
	old_prefix = $('#trans_prefix').val();
	$(document).on('change','#cm_id',function(){
		var entry_type = $("#entry_type").val();
		var cm_id = $(this).val();		
		var selected_cm_id = $(this).data('selected_cm_id');
		var append_id = $(this).data('append_id') || "trans_number";		

		if(selected_cm_id == cm_id){
			$('#trans_no').val(old_no);
			$('#trans_prefix').val(old_prefix);
			$('#'+append_id).val(old_prefix+old_no);
		}else{
			$.ajax({
				url : base_url + controller + '/getNextTransNo',
				type : 'post',
				data : {cm_id : cm_id, entry_type : entry_type},
				dataType : 'json'
			}).done(function(response){
				$('#trans_no').val(response.next_no);
				$('#trans_prefix').val(old_prefix);
				$('#'+append_id).val(old_prefix+response.next_no);
			});
		}
		gstin();
	});
});

function checkPartyTurnover(partyDetail){

	var postData = {party_id : partyDetail.id, vou_name_s : partyDetail.vou_name_s, cm_id : partyDetail.cm_id, trans_date : partyDetail.trans_date, id : partyDetail.trans_main_id};

	$.ajax({
		url : base_url + controller + '/getPartyNetInvoiceSum',
		type : 'post',
		data : postData,
		dataType : 'json'
	}).done(function(resData){
		$("#turnover").val(resData.netInvoiceSum);
		$("#Turnover").html(inrFormat(resData.netInvoiceSum));

		if($.inArray($("#vou_name_s").val(),["Purc","GExp"]) >= 0){		
			$("#tds_limit").val(resData.accountSetting.tds_limit);
			$("#taxSummaryHtml #tds_per").val(0);
			$("#tds_applicable").val(partyDetail.tds_applicable);

			if(partyDetail.tds_applicable != "NO"){		
				$("#tds_applicable").val(partyDetail.tds_applicable);
				$("#defual_tds_per").val(partyDetail.tds_per);
				$("#defual_tds_acc_id").val(partyDetail.tds_acc_id);

				if(partyDetail.tds_applicable == "YES-FROM-START"){
					$("#taxSummaryHtml #tds_per").val(partyDetail.tds_per);
					$("#taxSummaryHtml #tds_acc_id").val(partyDetail.tds_acc_id);
					initSelect2();
				} 
				
				if(partyDetail.tds_applicable == "YES-FROM-LIMIT"){
					if(parseFloat(resData.netInvoiceSum) >= parseFloat(resData.accountSetting.tds_limit)){		
						$("#taxSummaryHtml #tds_per").val(partyDetail.tds_per);
						$("#taxSummaryHtml #tds_acc_id").val(partyDetail.tds_acc_id);
						initSelect2();
					}else{
						$("#taxSummaryHtml #tds_per").val(0);
					}
				}
			}
		}

		if($.inArray($("#vou_name_s").val(),["Sale","GInc"]) >= 0){	
			var tcs_per = (partyDetail.pan_no != "")?resData.accountSetting.tcs_with_pan_per:resData.accountSetting.tcs_without_pan_per;
			
			$("#taxSummaryHtml #tcs_per").val(0);
			$("#tcs_applicable").val(partyDetail.tcs_applicable);
			$("#defual_tcs_per").val(tcs_per);

			if(partyDetail.tcs_applicable == "YES-SALES"){				
				$("#tcs_limit").val(resData.accountSetting.tcs_limit);
				$("#tcs_applicable").val(partyDetail.tcs_applicable);

				if(parseFloat(resData.netInvoiceSum) >= parseFloat(resData.accountSetting.tcs_limit)){
					$("#taxSummaryHtml #tcs_per").val(tcs_per);
				}else{
					$("#taxSummaryHtml #tcs_per").val(0);
				}
			}
		}

		claculateColumn();
	});
}

function gstin(){
	var party_id = $("#party_id").val();
	var gst_type = $("#tax_class_id").find(":selected").data('gst_type') || "";
    var gstin = $("#gstin").find(":selected").val() || $("#gstin").val();	
    var paertStateCode = $("#party_state_code").val() || 24;
	var company_state_code = $("select[name='cm_id']").find(":selected").data('state_code') || 24;

	if(gst_type == ""){
		if(paertStateCode == company_state_code){
			gst_type = 1;
		}else{
			gst_type = 2;
		}
		$("#gst_type").val(gst_type);
	}
    
	if($(".trans_main_id").val() == ""){
		var inv_type = $("#inv_type").val(); 
		if(inv_type == "SALES"){
			$('#tax_class_id').find('option[data-tax_class="SALESGSTACC"]:first').prop('selected', true);
			$("#tax_class").val("SALESGSTACC");
		}else{
			$('#tax_class_id').find('option[data-tax_class="PURGSTACC"]:first').prop('selected', true);
			$("#tax_class").val("PURGSTACC");
		}
		
		if(party_id != ""){
			if(gstin != "" && gstin != "URP"){
				if(paertStateCode == company_state_code){
					if(inv_type == "SALES"){
						$('#tax_class_id').find('option[data-tax_class="SALESGSTACC"]:first').prop('selected', true);
						$("#tax_class").val("SALESGSTACC");
					}else{
						$('#tax_class_id').find('option[data-tax_class="PURGSTACC"]:first').prop('selected', true);
						$("#tax_class").val("PURGSTACC");
					}
				}else{
					if(inv_type == "SALES"){
						$('#tax_class_id').find('option[data-tax_class="SALESIGSTACC"]:first').prop('selected', true);
						$("#tax_class").val("SALESIGSTACC");
					}else{
						$('#tax_class_id').find('option[data-tax_class="PURIGSTACC"]:first').prop('selected', true);
						$("#tax_class").val("PURIGSTACC");
					}			
				}
			}else{
				if(inv_type == "PURCHASE"){
					$('#tax_class_id').find('option[data-tax_class="PURTFACC"]:first').prop('selected', true);
					$("#tax_class").val("PURTFACC");
				}else if(inv_type == "SALES"){
					if(paertStateCode == company_state_code){
						$('#tax_class_id').find('option[data-tax_class="SALESGSTACC"]:first').prop('selected', true);
						$("#tax_class").val("SALESGSTACC");
					}else{
						$('#tax_class_id').find('option[data-tax_class="SALESIGSTACC"]:first').prop('selected', true);
						$("#tax_class").val("SALESIGSTACC");
					}			
				}
			}
		}
	}

	setTimeout(function(){$("#tax_class_id").trigger('change');},10);

	if($.inArray(inv_type, ["PURCHASE","SALES"]) >= 0){
		var tax_class = $(this).find(":selected").data('tax_class');
		if($.inArray(tax_class, ["EXPORTGSTACC","EXPORTTFACC","IMPORTACC","IMPORTSACC"]) >= 0){
			$(".exportData").removeClass("hidden");
		}else{
			$(".exportData").addClass("hidden");
		}
	}

    if(gst_type == 1){ 
		$(".cgstCol").show();$(".sgstCol").show();$(".igstCol").hide();
		$(".amountCol").hide();$(".netAmtCol").show();
	}else if(gst_type == 2){
		$(".cgstCol").hide();$(".sgstCol").hide();$(".igstCol").show();
		$(".amountCol").hide();$(".netAmtCol").show();
	}else{
		$(".cgstCol").hide();$(".sgstCol").hide();$(".igstCol").hide();
		$(".amountCol").show();$(".netAmtCol").hide();
	}

	initSelect2();
    claculateColumn();
}

function calculatePrice(postData,returnType = "price"){
	if(returnType == "price" && parseFloat(postData.org_price) > 0){
		/* Use if enter discount per. */
		var disc_amount = 0;
		if(parseFloat(postData.disc_per) > 0){
			disc_amount = parseFloat( (parseFloat(postData.org_price) * parseFloat(postData.disc_per) ) / 100 ).toFixed(3);
			postData.org_price = parseFloat( parseFloat(postData.org_price) - parseFloat(disc_amount) ).toFixed(3);
		}

		/* Use if enter discount amount */
		/* if(parseFloat(postData.disc_amount) > 0){
			postData.org_price = parseFloat( parseFloat(postData.org_price) - parseFloat(postData.disc_amount) ).toFixed(3);
		} */
		var new_price = postData.org_price;

		if(parseFloat(postData.gst_per) > 0){
			var gstReverse = parseFloat(( ( parseFloat(postData.gst_per) + 100 ) / 100 )).toFixed(3);
			new_price = parseFloat( parseFloat(postData.org_price) / parseFloat(gstReverse) ).toFixed(3);
			disc_amount = parseFloat( parseFloat(disc_amount) / parseFloat(gstReverse) ).toFixed(3);
			new_price = parseFloat( parseFloat(new_price) + parseFloat(disc_amount) ).toFixed(3);
		}
		return new_price;
	}

	return 0;
}

function calculateItem(formData){
	formData.disc_per = (parseFloat(formData.disc_per) > 0)?formData.disc_per:0;
	var qty = formData.qty;
	var amount = 0; var taxable_amount = 0; var disc_amt = 0; var igst_amt = 0;
	var cgst_amt = 0; var sgst_amt = 0; var net_amount = 0; 
	var cgst_per = 0; var sgst_per = 0; var igst_per = 0;

	if (formData.disc_per == "" && formData.disc_per == "0") {
		taxable_amount = amount = parseFloat(parseFloat(qty) * parseFloat(formData.price)).toFixed(3);
	} else {
		amount = parseFloat(parseFloat(qty) * parseFloat(formData.price)).toFixed(3);
		disc_amt = parseFloat((amount * parseFloat(formData.disc_per)) / 100).toFixed(3);
		taxable_amount = parseFloat(amount - disc_amt).toFixed(3);
	}

	formData.gst_per = igst_per = parseFloat(formData.gst_per);
	formData.gst_amount = igst_amt = parseFloat((igst_per * taxable_amount) / 100).toFixed(3);

	cgst_per = parseFloat(parseFloat(igst_per) / 2).toFixed(2);
	sgst_per = parseFloat(parseFloat(igst_per) / 2).toFixed(2);

	cgst_amt = parseFloat((cgst_per * taxable_amount) / 100).toFixed(3);
	sgst_amt = parseFloat((sgst_per * taxable_amount) / 100).toFixed(3);

	net_amount = parseFloat(parseFloat(taxable_amount) + parseFloat(igst_amt)).toFixed(3);

	formData.qty = parseFloat(formData.qty).toFixed(2);
	formData.cgst_per = cgst_per;
	formData.cgst_amount = cgst_amt;
	formData.sgst_per = sgst_per;
	formData.sgst_amount = sgst_amt;
	formData.igst_per = igst_per;
	formData.igst_amount = igst_amt;
	formData.disc_amount = disc_amt;
	formData.amount = amount;
	formData.taxable_amount = taxable_amount;
	formData.net_amount = net_amount;
	formData.exp_taxable_amount = 0;
	formData.exp_gst_amount = 0;

	return formData;
}

function claculateColumn() {
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

	var amountArray = $(".amount").map(function () { return $(this).val(); }).get();
	var amountSum = 0;
	$.each(amountArray, function () { amountSum += parseFloat(this) || 0; });
	$("#total_amount").html(amountSum.toFixed(3));

	var taxableAmountArray = $(".taxable_amount").map(function () { return $(this).val(); }).get();
	var taxableAmountSum = 0;
	$.each(taxableAmountArray, function () { taxableAmountSum += parseFloat(this) || 0; });
	$("#taxable_amount").val(taxableAmountSum.toFixed(3));

	calculateSummary();
}

function calculateSummary() {
	$(".calculateSummary").each(function () {
		var row = $(this).data('row');

		var map_code = row.map_code;
		var amtField = $("#" + map_code + "_amt");
		var netAmountField = $("#" + map_code + "_amount");
		var perField = $("#" + map_code + "_per");
		var sm_type = amtField.data('sm_type');

		if (sm_type == "exp") {
			if (row.position == "1") {
				var itemGstArray = $(".gst_per").map(function () { return $(this).val(); }).get();
				var maxGstPer = Math.max.apply(Math, itemGstArray);
				maxGstPer = (maxGstPer != "" && !isNaN(maxGstPer)) ? maxGstPer : 0;

				if (row.calc_type == "1") {
					var amount = (amtField.val() != "") ? amtField.val() : 0;
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(3);
					netAmountField.val(amount);

					var gstAmount = parseFloat((parseFloat(maxGstPer) * parseFloat(amount)) / 100).toFixed(3);
					/* var gstAmount = 0, expGstAmt = 0;
					var itemCount = ($('#tempItem tr:last').attr('id') !== 'noData')?($('#tempItem tr:last').index() + 1):0;
					if(itemCount > 0){
						var expTaxableAmt = parseFloat(parseFloat(amount) / itemCount).toFixed(3);
						$.each($("#tempItem tr"),function(){
							formData = $(this).data('item_data');
							expGstAmt = 0;
							expGstAmt = (parseFloat(formData.gst_per) > 0)?parseFloat((parseFloat(formData.gst_per) * parseFloat(expTaxableAmt)) / 100).toFixed(3):0;
							gstAmount += parseFloat(expGstAmt);
						});
					} */
				} else {
					var taxable_amount = ($("#taxable_amount").val() != "") ? $("#taxable_amount").val() : 0;
					var per = (perField.val() != "") ? perField.val() : 0;

					var amount = parseFloat((parseFloat(taxable_amount) * parseFloat(per)) / 100).toFixed(3);
					amtField.val(amount);
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(3);
					netAmountField.val(amount);
					
					var gstAmount = parseFloat((parseFloat(maxGstPer) * parseFloat(amount)) / 100).toFixed(3);
					/* var gstAmount = 0, expGstAmt = 0;
					var itemCount = ($('#tempItem tr:last').attr('id') !== 'noData')?($('#tempItem tr:last').index() + 1):0;
					if(itemCount > 0){
						var expTaxableAmt = parseFloat(parseFloat(amount) / itemCount).toFixed(3);
						$.each($("#tempItem tr"),function(){
							formData = $(this).data('item_data');
							expGstAmt = 0;
							expGstAmt = (parseFloat(formData.gst_per) > 0)?parseFloat((parseFloat(formData.gst_per) * parseFloat(expTaxableAmt)) / 100).toFixed(3):0;
							gstAmount += parseFloat(expGstAmt);
						});
					} */
				}

				$("#other_" + map_code + "_amount").val(gstAmount);

			} else {
				if (row.calc_type == "1") {
					var amount = (amtField.val() != "") ? amtField.val() : 0;
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(3);
					netAmountField.val(amount);
				} else {
					var taxable_amount = ($("#taxable_amount").val() != "") ? $("#taxable_amount").val() : 0;
					var per = (perField.val() != "") ? perField.val() : 0;
					var amount = parseFloat((parseFloat(taxable_amount) * parseFloat(per)) / 100).toFixed(3);
					amtField.val(amount);
					amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(3);
					netAmountField.val(amount);
				}
			}
		}

		if (sm_type == "tax") {
			if(row.calculation_type == 2){
				var oldAmt = amtField.val();
				oldAmt = (parseFloat(oldAmt) > 0)?oldAmt:0;	
				var per = (perField.val() != "")?perField.val():0;
				calculateSummaryAmount();		

				var summaryAmtArray = $(".summaryAmount").map(function(){return $(this).val();}).get();
				var summaryAmtSum = 0;
				$.each(summaryAmtArray,function(){summaryAmtSum += parseFloat(this) || 0;});
				
				if(parseFloat(summaryAmtSum) > 0){
					summaryAmtSum = parseFloat(parseFloat(summaryAmtSum) - parseFloat(oldAmt)).toFixed(3);
				}else{
					amtField.val(0);
				}

				
				if(map_code == "tcs"){
					var tcs_applicable = $("#tcs_applicable").val() || "";	
					if(tcs_applicable == "YES-SALES"){
						var tcs_per = $("#defual_tcs_per").val() || $("#taxSummaryHtml #tcs_per").val();
						var turnover = parseFloat(($("#turnover").val() || 0)).toFixed(3);
						var tcs_limit = $("#tcs_limit").val() || 0;

						if(parseFloat(turnover) < parseFloat(tcs_limit)){
							var newTurnover = parseFloat(parseFloat(turnover) + parseFloat(summaryAmtSum)).toFixed(3);
							if(parseFloat(newTurnover) >= parseFloat(tcs_limit)){
								summaryAmtSum = parseFloat(parseFloat(newTurnover) - parseFloat(tcs_limit)).toFixed(3);
								per = tcs_per;

								$("#taxSummaryHtml #tcs_per").val(tcs_per);
							}/* else{
								$("#taxSummaryHtml #tcs_per").val(0);
								per = 0;
								amtField.val(0);
								netAmountField.val(0);
							} */
						}
					}
				}			
				
				var amount = parseFloat((parseFloat(summaryAmtSum) * parseFloat(per)) / 100).toFixed(3);				
				amtField.val(amount);
				amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(3);
				netAmountField.val(amount);
			}else if (row.calculation_type == 1) {
				var taxable_amount = ($("#taxable_amount").val() != "") ? $("#taxable_amount").val() : 0;
				var per = (perField.val() != "") ? perField.val() : 0;

				if(map_code == "tds"){
					var tds_applicable = $("#tds_applicable").val() || "";	
					if(tds_applicable == "YES-FROM-LIMIT"){
						var tds_per = $("#defual_tds_per").val() || $("#taxSummaryHtml #tds_per").val();
						var tds_acc_id = $("#defual_tds_acc_id").val() || $("#taxSummaryHtml #tds_acc_id").val();
						var turnover = parseFloat(($("#turnover").val() || 0)).toFixed(3);
						var tds_limit = $("#tds_limit").val() || 0;

						if(parseFloat(turnover) < parseFloat(tds_limit)){
							var newTurnover = parseFloat(parseFloat(turnover) + parseFloat(taxable_amount)).toFixed(3);
							if(parseFloat(newTurnover) >= parseFloat(tds_limit)){
								taxable_amount = parseFloat(parseFloat(newTurnover) - parseFloat(tds_limit)).toFixed(3);
								per = tds_per;

								$("#taxSummaryHtml #tds_per").val(tds_per);
								$("#taxSummaryHtml #tds_acc_id").val(tds_acc_id);
								initSelect2();
							}/* else{
								$("#taxSummaryHtml #tds_per").val(0);
								per = 0;
								amtField.val(0);
								netAmountField.val(0);
							} */
						}
					}
				}				
				
				var amount = parseFloat((parseFloat(taxable_amount) * parseFloat(per)) / 100).toFixed(3);
				amtField.val(amount);
				amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(3);
				netAmountField.val(amount);
			} else {
				var qtyArray = $(".item_qty").map(function () { return $(this).val(); }).get();
				var qtySum = 0;
				$.each(qtyArray, function () { qtySum += parseFloat(this) || 0; });

				var per = (perField.val() != "") ? perField.val() : 0;
				var amount = parseFloat(parseFloat(qtySum) * parseFloat(per)).toFixed(3);
				amtField.val(amount);
				amount = parseFloat(parseFloat(amount) * parseFloat(row.add_or_deduct)).toFixed(3);
				netAmountField.val(amount);
			}
		}

	});

	calculateSummaryAmount();
}

function calculateSummaryAmount() {
	var gst_type = $("#gst_type").val();

	$('#cgst_amount').val("0");
	$('#sgst_amount').val("0");
	if (gst_type == 1) {
		var cgstAmtArr = $(".cgst_amount").map(function () { return $(this).val(); }).get();
		var cgstAmtSum = 0;
		$.each(cgstAmtArr, function () { cgstAmtSum += parseFloat(this) || 0; });
		$('#cgst_amount').val(parseFloat(cgstAmtSum).toFixed(3));

		var sgstAmtArr = $(".sgst_amount").map(function () { return $(this).val(); }).get();
		var sgstAmtSum = 0;
		$.each(sgstAmtArr, function () { sgstAmtSum += parseFloat(this) || 0; });
		$('#sgst_amount').val(parseFloat(sgstAmtSum).toFixed(3));
	}

	$('#igst_amount').val("0");
	if (gst_type == 2) {
		var igstAmtArr = $(".igst_amount").map(function () { return $(this).val(); }).get();
		var igstAmtSum = 0;
		$.each(igstAmtArr, function () { igstAmtSum += parseFloat(this) || 0; });
		$('#igst_amount').val(parseFloat(igstAmtSum).toFixed(3));
	}

	var tdsAmount = $("#tds_amount").val() || 0;
	tdsAmount = parseFloat(tdsAmount).toFixed(0);
	$("#tds_amount").val(tdsAmount);

	var otherGstAmtArray = $(".otherGstAmount").map(function () { return $(this).val(); }).get();
	var otherGstAmtSum = 0;
	$.each(otherGstAmtArray, function () { otherGstAmtSum += parseFloat(this) || 0; });

	var cgstAmt = 0;
	var sgstAmt = 0;
	var igstAmt = 0;
	if (gst_type == 1) {
		cgstAmt = parseFloat(parseFloat(otherGstAmtSum) / 2).toFixed(3);
		sgstAmt = parseFloat(parseFloat(otherGstAmtSum) / 2).toFixed(3);

		cgstAmt = parseFloat(parseFloat($("#cgst_amount").val()) + parseFloat(cgstAmt)).toFixed(3);
		sgstAmt = parseFloat(parseFloat($("#sgst_amount").val()) + parseFloat((sgstAmt))).toFixed(3);
		$("#cgst_amount").val(cgstAmt);
		$("#sgst_amount").val(sgstAmt);
	} else if (gst_type == 2) {
		igstAmt = otherGstAmtSum;

		igstAmt = parseFloat(parseFloat($("#igst_amount").val()) + parseFloat((igstAmt))).toFixed(3);
		$("#igst_amount").val(igstAmt);
	}

	var summaryAmtArray = $(".summaryAmount").map(function () { return $(this).val(); }).get();
	var summaryAmtSum = 0;
	$.each(summaryAmtArray, function () { summaryAmtSum += parseFloat(this) || 0; });

	
	summaryAmtSum = parseFloat(parseFloat(summaryAmtSum) + (parseFloat(tdsAmount) * -1)).toFixed(3);

	var taxClass = $("#tax_class").val() || "";
	if($.inArray(taxClass, ["PURURDGSTACC","PURURDIGSTACC"]) >= 0){
		summaryAmtSum = parseFloat(parseFloat(summaryAmtSum) - parseFloat(cgstAmt) - parseFloat(sgstAmt) - parseFloat(igstAmt)).toFixed(3);
	}

	if ($("#roff_amount").length > 0) {
		var totalAmount = parseFloat(summaryAmtSum).toFixed(3);
		var decimal = totalAmount.split('.')[1];
		var roundOff = 0;
		var netAmount = 0;
		if (decimal !== 0) {
			if (decimal >= 500) {
				if ($('#apply_round').val() == "1") { roundOff = (1000 - decimal) / 1000; }
				netAmount = parseFloat(parseFloat(totalAmount) + parseFloat(roundOff)).toFixed(3);
			} else {
				if ($('#apply_round').val() == "1") { roundOff = (decimal - (decimal * 2)) / 1000; }
				netAmount = parseFloat(parseFloat(totalAmount) + parseFloat(roundOff)).toFixed(3);
			}
			$("#roff_amount").val(parseFloat(roundOff).toFixed(3));
		}
		$("#net_amount").val(netAmount);
	} else {
		$("#net_amount").val(parseFloat(summaryAmtSum).toFixed(3));
	}
}