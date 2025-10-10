$(document).ready(function(){
    $(document).on('click','.sendWhatsappMessage',function(){
		var postData = {ref_id:$(this).data('ref_id'),subject:$(this).data('subject'),jsFnName:$(this).data('js_fn_name'),docName:$(this).data('doc_name')};
        var result = checkWhatsappLoginStatus(postData);
    });

    $(document).on('click','.sendTripDetailInWhatsapp',function(){
        var postData = {ref_id:$(this).data('ref_id'),subject:$(this).data('subject'),jsFnName:$(this).data('js_fn_name'),docName:$(this).data('doc_name')};
        var result = checkWhatsappLoginStatus(postData);
    });
});

var isQRtimedOut = 0;
var loginSuccess = 0;

function checkWhatsappLoginStatus(postData=""){
	$(".wpLoginError").html("");
	$.ajax({
		url: base_url + 'whatsapp/getWpQrCode',
		type:'post',
		data:{},
		dataType:'json',
		success:function(response){
			if(response.status == 0){
				//toastr.error(response.message, 'Sorry...!', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

                Swal.fire( 'Sorry...!', response.message, 'error' );
			}else if(response.status == 2){
				whatsappQr(response.data,response.key,postData);
			}else{
				window[postData.jsFnName](postData);
			}
		}
	});
}

function whatsappQr(result,key,postData=""){
	$("#whatsapp-login").modal("show");
    $('#loginQRImage').attr('src',"");
	$('#loginQRImage').attr('src',result.Data.QRBase64);
    $('#loginQRImage').attr('style','');
	$('#requestID').val(key);
	whatsappQrCountDown(30,postData);
}

function whatsappQrCountDown(secs,postData=""){
	var seconds = secs;
	function tick() {
        if(loginSuccess==1){ return true;}

        getQR_Response($('#requestID').val(),postData);
        var counter = document.getElementById("counter");
        seconds--;
        counter.innerHTML = (seconds < 10 ? "0" : "") + String(seconds);
        if( seconds > 0 ) {
                setTimeout(tick, 1000);
        } else {
            // Timeout
            $('#loginQRImage').attr('src',"");
            $('#loginQRImage').attr('src',base_url+'/assets/images/small/img-1.gif');
            $('#loginQRImage').attr('style','width:30%;');
            $("#timeoutText").html("Reloading");
            if(isQRtimedOut==0){
                whatsappQrCountDown(5,postData);
                isQRtimedOut = 1;
            } else {
                checkWhatsappLoginStatus(postData);
            }
        }
	}
	tick();
}

function getQR_Response(requestID,postData=""){
	if(requestID != ""){
		$.ajax({
			url:base_url + 'whatsapp/getQrStatus',
			type : "POST",
			data:{key:requestID},
			global : false,
			dataType:"json",
            success: function(response){				
                if(response.result.Data.Status == "CONNECTED"){
					$("#whatsapp-login").modal("hide");
					loginSuccess = 1;
					window[postData.jsFnName](postData);
				}	
            }
		});
	}
}

function sendInvoiceInWhatsapp(postData){
	var ref_id = postData.ref_id;
	var subject = postData.subject;

    Swal.fire({
		title: 'Confirm!',
		text: 'Are you sure to send whatsapp message?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, Do it!',
	}).then(function(result) {
		if (result.isConfirmed){
            $.ajax({
                url: base_url + 'whatsapp/sendMessage',
                //data:{message_type:'Message',inv_id:ref_id,subject:subject},
                data:{message_type:'Document',inv_id:ref_id,subject:subject,docName:postData.docName,'original':1,'header_footer':1},
                type: "POST",
                dataType:"json",
                success:function(data){
                    if(data.status===0){
                        initTable();is_error=1;
                        //toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

                        Swal.fire( 'Sorry...!', data.message, 'error' );
                    }else if(data.status==1){
                        initTable(); 
                        //toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

                        Swal.fire( 'Success', data.message, 'success' );

                        //send document
                        /* $.ajax({
                            url: base_url + 'whatsapp/sendMessage',
                            data:{message_type:'Document',inv_id:ref_id,subject:subject,'original':1,'header_footer':1},
                            type: "POST",
                            dataType:"json",
                            success:function(data){
                                if(data.status===0){
                                    initTable();
                                    //toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

                                    Swal.fire( 'Sorry...!', data.message, 'error' );
                                }else if(data.status==1){
                                    initTable(); 
                                    //toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

                                    Swal.fire( 'Success', data.message, 'success' );
                                }else{
                                    initTable();
                                    //toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

                                    Swal.fire( 'Sorry...!', data.message, 'error' );
                                }
                            }
                        }); */
                    }else{
                        initTable();is_error=1;
                        //toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

                        Swal.fire( 'Sorry...!', data.message, 'error' );
                    }
                }
            });
        }
	});
}

function sendPaymentReminder(postData){
    Swal.fire({
		title: 'Confirm!',
		text: 'Are you sure to send Payment Reminder ?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, Do it!',
	}).then(function(result) {
        if (result.isConfirmed){
            $.ajax({
                url: base_url + 'whatsapp/sendMessage',
                data:{message_type:'Message',inv_id:postData.ref_id,subject:postData.subject},
                type: "POST",
                dataType:"json",
                success:function(data){
                    if(data.status===0){
                        //toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

                        Swal.fire( 'Sorry...!', data.message, 'error' );
                    }else if(data.status==1){
                        //toastr.success(data.message, 'Success', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

                        Swal.fire( 'Success', data.message, 'success' );
                    }else{
                        //toastr.error(data.message, 'Error', { "showMethod": "slideDown", "hideMethod": "slideUp", "closeButton": true, positionClass: 'toastr toast-bottom-center', containerId: 'toast-bottom-center', "progressBar": true });

                        Swal.fire( 'Sorry...!', data.message, 'error' );
                    }
                }
            });		
        }        
    });
}

function sendTripDetailInWhatsapp(postData){
    var ref_id = postData.ref_id;
	var subject = postData.subject;

    Swal.fire({
		title: 'Confirm!',
		text: 'Are you sure to send whatsapp message?',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, Do it!',
	}).then(function(result) {
		if (result.isConfirmed){
            $.ajax({
                url: base_url + 'whatsapp/sendTripMessage',
                data:{message_type:'Document',ref_id:ref_id,subject:subject,docName:postData.docName,'original':1,'header_footer':1},
                type: "POST",
                dataType:"json",
                success:function(data){
                    if(data.status===0){
                        initTable();is_error=1;
                        Swal.fire( 'Sorry...!', data.message, 'error' );
                    }else if(data.status==1){
                        initTable();
                        Swal.fire( 'Success', data.message, 'success' );
                    }else{
                        initTable();is_error=1;
                        Swal.fire( 'Sorry...!', data.message, 'error' );
                    }
                }
            });
        }
	});
}

function logoutWhatsapp(){
	$.ajax({
		url:base_url + 'whatsapp/whatsappLogout',
		type : "GET",
		dataType:"json",
		success: function(response){				
			if(response.status == 1){
				loginSuccess = 0;
				window.location.reload();
			}	
		}
	});
}