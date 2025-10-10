$(document).ready(function(){
	
	var enqListPageLimit = 15;
    setTimeout(function(){ loadDesk(); }, 50);
    
	$(document).on('click','.stageFilter',function(){
        var postdata = $(this).data('postdata') || {};
		$('#next_page').val('0');
		postdata.start = 0;
		postdata.length = parseFloat(enqListPageLimit);
		postdata.page = 0;
		
		loadHtmlData({'fnget':'getPurchaseEnqList','rescls':'enqList','postdata':postdata});
	});
	
	$('.quicksearch').keyup(delay(function (e) {
		//if(e.which === 13 && !e.shiftKey) {
			e.preventDefault();
			$('#next_page').val('0');
			var postdata = $('.stageFilter.active').data('postdata') || {};
			delete postdata.page;delete postdata.start;delete postdata.length;
			postdata.limit = parseFloat(enqListPageLimit);
			postdata.skey = $(this).val();
			loadHtmlData({'fnget':'getPurchaseEnqList','rescls':'enqList','postdata':postdata});
		//}
	}));

	const scrollEle = $('#purchaseBoard .simplebar-content-wrapper');
	var ScrollDebounce = true;
	$(scrollEle).scroll(function() {
		if($(this).scrollTop() + $(this).innerHeight() >= ($(this)[0].scrollHeight - 10)) {
			if(ScrollDebounce){
				ScrollDebounce = false;
				var postdata = $('.stageFilter.active').data('postdata') || {};
    			var np = parseFloat($('#next_page').val()) || 0;
    			postdata.start = np * parseFloat(enqListPageLimit);
    			postdata.length = enqListPageLimit;
    			postdata.page = np;
    			loadHtmlData({'fnget':'getPurchaseEnqList','rescls':'enqList','postdata':postdata,'scroll_type':1});
				setTimeout(function () { ScrollDebounce = true; }, 500);		
			}
		}
	});

	$(document).on('change','#item_type',function(){
		var item_type = $(this).val();
		if(item_type){
			$.ajax({
				url:base_url + controller + '/getItemList',
				data:{item_type:item_type},
				method:"POST",
				dataType:"json",
				success:function(data){
					$("#item_id").html('');
					$("#item_id").html(data.options);
				}
			});
		}
	}); 

	$(document).on('change','#item_id',function(){
		var item_id = $(this).val();
		if(item_id == '-1'){
			$('.newItem').show();
		}else{
			$('.newItem').hide();
			$('#item_name').val($('#item_id :selected').text());
			$.ajax({
				url:base_url + controller + '/getItemDetails',
				data:{id:item_id},
				method:"POST",
				dataType:"json",
				success:function(data){
					$("#unit_id").html('');
					$("#unit_id").html(data.options);
				}
			});
		}
	}); 

	$(document).on('change','#compare_item',function(){
		var compare_item = $(this).val();
		if(compare_item){
			$.ajax({
				url:base_url + controller + '/getCompareList',
				data:{item_id:compare_item},
				method:"POST",
				dataType:"json",
				success:function(data){
					$("#compareItemList").html('');
					$("#compareItemList").html(data.itemList);
				}
			});
		}
	}); 
	
	$(document).on('click','.compareBtn',function(){
		
		var partyIdArray = $(".partyCheck").map(function () { 
			if(this.checked){
				return $(this).val(); 
			}
		}).get();
		
		if(partyIdArray){
			$.ajax({
				url:base_url + controller + '/getPartyComparison',
				data:{party_id:partyIdArray},
				method:"POST",
				dataType:"json",
				success:function(data){
					$("#partyData").html('');
					$("#partyData").html(data.partyData);
				}
			});
		}

	});
});

function initForm(postData,response){
	var button = postData.button;if(button == "" || button == null){button="both";};
	var fnedit = postData.fnedit;if(fnedit == "" || fnedit == null){fnedit="edit";}
	var fnsave = postData.fnsave;if(fnsave == "" || fnsave == null){fnsave="save";}
	var controllerName = postData.controller;if(controllerName == "" || controllerName == null){controllerName=controller;}
	var savebtn_text = postData.savebtn_text;
	var savebtn_icon = postData.savebtn_icon || "";
	if(savebtn_text == "" || savebtn_text == null){savebtn_text='<i class="fa fa-check"></i> Save';}
	else{ savebtn_text = ((savebtn_icon != "")?'<i class="'+savebtn_icon+'"></i> ':'')+savebtn_text; }

	var resFunction = postData.res_function || "";
	var jsStoreFn = postData.js_store_fn || 'storeEnquiry';
	var txt_editor = postData.txt_editor || '';

	var fnJson = "{'formId':'"+postData.form_id+"','fnsave':'"+fnsave+"','controller':'"+controllerName+"','txt_editor':'"+txt_editor+"'}";

	$("#"+postData.modal_id).modal('show');
	$("#"+postData.modal_id).addClass('modal-i-'+zindex);
	$('.modal-i-'+(zindex - 1)).removeClass('show');
	$("#"+postData.modal_id).css({'z-index':zindex,'overflow':'auto'});
	$("#"+postData.modal_id).addClass(postData.form_id+"Modal");
	$("#"+postData.modal_id+' .modal-title').html(postData.title);
	$("#"+postData.modal_id+' .modal-body').html('');
	$("#"+postData.modal_id+' .modal-body').html(response);
	$("#"+postData.modal_id+" .modal-body form").attr('id',postData.form_id);
	if(resFunction != ""){
		$("#"+postData.modal_id+" .modal-body form").attr('data-res_function',resFunction);
	}
	$("#"+postData.modal_id+" .modal-footer .btn-save").html(savebtn_text);
	$("#"+postData.modal_id+" .modal-footer .btn-save").attr('onclick',jsStoreFn+"("+fnJson+");");
	$("#"+postData.modal_id+" .btn-custom-save").attr('onclick',jsStoreFn+"("+fnJson+");");

	$("#"+postData.modal_id+" .modal-header .btn-close").attr('data-modal_id',postData.modal_id);
	$("#"+postData.modal_id+" .modal-header .btn-close").attr('data-modal_class',postData.form_id+"Modal");
	$("#"+postData.modal_id+" .modal-footer .btn-close-modal").attr('data-modal_id',postData.modal_id);
	$("#"+postData.modal_id+" .modal-footer .btn-close-modal").attr('data-modal_class',postData.form_id+"Modal");

	if(button == "close"){
		$("#"+postData.modal_id+" .modal-footer .btn-close-modal").show();
		$("#"+postData.modal_id+" .modal-footer .btn-save").hide();
	}else if(button == "save"){
		$("#"+postData.modal_id+" .modal-footer .btn-close-modal").hide();
		$("#"+postData.modal_id+" .modal-footer .btn-save").show();
	}else{
		$("#"+postData.modal_id+" .modal-footer .btn-close-modal").show();
		$("#"+postData.modal_id+" .modal-footer .btn-save").show();
	}
	
	setTimeout(function(){ 
		initMultiSelect();setPlaceHolder();setMinMaxDate();initSelect2();		
	}, 5);
	setTimeout(function(){
		$('#'+postData.modal_id+'  :input:enabled:visible:first, select:first').focus();
	},500);
	zindex++;
}

function loadform(data){
	var call_function = data.call_function;
	if(call_function == "" || call_function == null){call_function="edit";}

	var fnsave = data.fnsave;
	if(fnsave == "" || fnsave == null){fnsave="save";}

	var controllerName = data.controller;
	if(controllerName == "" || controllerName == null){controllerName=controller;}	

	$.ajax({ 
		type: "POST",   
		url: base_url + controllerName + '/' + call_function,   
		data: data.postData,
	}).done(function(response){
		initForm(data,response);
	});
}

/***** GET DYNAMIC DATA *****/
function loadHtmlData(data){
	
	var postData = data.postdata || {};
	var fnget = data.fnget || "";
	var controllerName = data.controller || controller;
	var rescls = data.rescls || "dynamicData";
	var scrollType = data.scroll_type || "";
	
	$.ajax({
		url: base_url + controllerName + '/' + fnget,
		data:postData,
		type: "POST",
		dataType:"json",
		global:false,
		/*beforeSend: function() {
			if(rescls != ""){
				$("."+rescls).html('<h4 class="text-center">Loading...</h4>');
			}
		},*/
	}).done(function(res){
		$("#next_page").val(res.next_page);
		if(!scrollType){$("."+rescls).html(res.enqList);}else{$("."+rescls).append(res.enqList);}
		loading = true;
		var img = base_url + 'assets/images/background/dnf_1.png';
		var img2 = base_url + 'assets/images/background/dnf_2.png';

		// $(".enqDetail").html('<div class="cd-header"><h6 class="m-0">ENQUIRY DETAIL</h6></div><div class="sop-body" data-simplebar><div class="activity"><img src="'+img+'" style="width:100%;"><h3 class="text-danger text-center font-24 fw-bold line-height-lg">Sorry!<br><span class="text-dark">Data Not Found</span></h3><div class="text-center text-muted font-16 fw-bold pt-3 pb-1">Pleasae click any <strong>ENQUIRY</strong> to see Data</div></div></div>');

		// $(".itemDetail").html('<div class="text-center"><img src="'+img2+'" style="width:100%;"><div class="text-center text-muted font-16 fw-bold">Pleasae click any <strong>ENQUIRY</strong> to see Data</div></div>');
	});
}

function storeEnquiry(postData){
	setPlaceHolder();
	postData.txt_editor = postData.txt_editor || "";	
	if(postData.txt_editor !== "")
	{
    	var myContent = tinymce.get(postData.txt_editor).getContent();
    	$("#" + postData.txt_editor).val(myContent);
	}

	var formId = postData.formId;
	var fnsave = postData.fnsave || "save";
	var controllerName = postData.controller || controller;
	var resFunctionName =$("#"+formId).data('res_function') || "";
	var form = $('#'+formId)[0];
	var fd = new FormData(form);
	$.ajax({
		url: base_url + controllerName + '/' + fnsave,
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(data){
		if(resFunctionName != ""){
			console.log(resFunctionName);
			window[resFunctionName](data,formId);
		}else{
			if(data.status==1){
				$('#'+formId)[0].reset(); closeModal(formId); $(".stageFilter.active").trigger("click");
				Swal.fire({ icon: 'success', title: data.message});	
			}else{
				if(typeof data.message === "object"){
					$(".error").html("");
					$.each( data.message, function( key, value ) {$("."+key).html(value);});
				}else{
					Swal.fire({ icon: 'error', title: data.message });
				}			
			}
		}				
	});
}

function delay(callback, ms=500) {
	var timer = 0;
	return function() {
		var context = this, args = arguments;
		clearTimeout(timer);
		timer = setTimeout(function () { callback.apply(context, args); }, ms || 0);
	};
}

function initSelect2(){
	//$(".select2").select2({with:null});
	$(".select2").each(function () {
		$(this).select2();
	});	

	$(".modal .select2").each(function () {
		$(this).select2({
			dropdownParent: $('#'+$(this).closest('.modal').attr('id')),
		});
	});	
}

function confirmPurchaseStore(data){
	setPlaceHolder();

	var formId = data.formId || "";
	var fnsave = data.fnsave || "save";
	var controllerName = data.controller || controller;

	if(formId != ""){
		var form = $('#'+formId)[0];
		var fd = new FormData(form);
		var resFunctionName = $("#"+formId).data('res_function') || "";
		var msg = "Are you sure want to save this record ?";
		var ajaxParam = {
			url: base_url + controllerName + '/' + fnsave,
			data:fd,
			type: "POST",
			processData:false,
			contentType:false,
			dataType:"json"
		};
	}else{
		var fd = data.postData;
		var resFunctionName = data.res_function || "";
		var msg = data.message || "Are you sure want to save this change ?";
		var ajaxParam = {
			url: base_url + controllerName + '/' + fnsave,
			data:fd,
			type: "POST",
			dataType:"json"
		};
	}
	Swal.fire({
		title: 'Are you sure?',
		text: msg,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, Do it!',
	}).then(function(result) {
		if (result.isConfirmed){
			$.ajax(ajaxParam).done(function(response){
				if(formId != ""){$('#'+formId)[0].reset(); closeModal(formId);}
				if(resFunctionName != ""){
					window[resFunctionName](response,formId);
				}else{
					if(response.status==1){
						initTable();
						Swal.fire( 'Success', response.message, 'success' );
					}else{
						if(typeof response.message === "object"){
							$(".error").html("");
							$.each( response.message, function( key, value ) {$("."+key).html(value);});
						}else{
							initTable();
							Swal.fire( 'Sorry...!', response.message, 'error' );
						}			
					}	
				}			
			});
		}
	});
}

function loadDesk(){
	$(".stageFilter.active").trigger("click");
}

function loadItemDetail(data,form_id=""){
	var enq_id = data.enq_id;
	$.ajax({
		url: base_url + controller + '/getEnqDetail',
		type:'post',
		data:{id:enq_id},
		dataType:'json',
		success:function(data){
			$(".enqDetail").html(data.enqDetail);
			$(".itemDetail").html(data.itemDetail);
			$(".quoteDetail").html(data.quoteDetail);
		}
	});
}

function trashEnquiry(data){
	var controllerName = data.controller || controller;
	var fnName = data.fndelete || "delete";
	var msg = data.message || "Record";
	var send_data = data.postData;
	var resFunctionName = data.res_function || "";
	
	Swal.fire({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, delete it!',
	}).then(function(result) {
		if (result.isConfirmed){
			$.ajax({
				url: base_url + controllerName + '/' + fnName,
				data: send_data,
				type: "POST",
				dataType:"json",
			}).done(function(response){
				if(resFunctionName != ""){
					window[resFunctionName](response);
				}else{
					if(response.status==0){
						Swal.fire( 'Sorry...!', response.message, 'error' );
					}else{
						initTable();
						Swal.fire( 'Deleted!', response.message, 'success' );
					}	
				}
			});
		}
	});
	
}


function getPurchaseResponse(data,formId=""){ 
	if(data.status==1){
		if(formId){
			$('#'+formId)[0].reset();
			closeModal(formId);
		}
		Swal.fire({
			title: "Success",
			text: data.message,
			icon: "success",
			showCancelButton: false,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ok!"
		}).then((result) => {
			loadItemDetail(data);
			loadDesk();
		});
		
	}else{
		if(typeof data.message === "object"){
			$(".error").html("");
			$.each( data.message, function( key, value ) {$("."+key).html(value);});
		}else{
			Swal.fire({ icon: 'error', title: data.message });
		}			
	}
}

function compareResponse(data,formId=""){ 
	if(data.status==1){
		
		Swal.fire({
			title: "Success",
			text: data.message,
			icon: "success",
			showCancelButton: false,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ok!"
		}).then((result) => {
			$(".compareBtn").trigger("click");
			loadItemDetail(data);
			loadDesk();
		});
		
	}else{
		if(typeof data.message === "object"){
			$(".error").html("");
			$.each( data.message, function( key, value ) {$("."+key).html(value);});
		}else{
			Swal.fire({ icon: 'error', title: data.message });
		}			
	}
}