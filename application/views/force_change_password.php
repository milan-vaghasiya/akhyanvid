<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>assets/images/favicon.png">
    <title>Login - <?=(!empty(SITENAME))?SITENAME:""?></title>
    
	<link href="<?=base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="<?=base_url();?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
	
	<!-- Custom CSS -->
    <link href="<?=base_url()?>assets/css/jp_helper.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/login.css" rel="stylesheet">
</head>

<body>
	<div class="auth-page">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-xxl-3 col-lg-4 col-md-5">
                    <div class="auth-full-page-content d-flex p-sm-5 p-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100">
                                <div class="text-center">
                                    <a href="index.html" class="d-block auth-logo">
                                        <img src="<?=base_url()?>assets/images/logo.png" alt="logo" width="50%" />
                                    </a>
                                </div>
                                <div class="auth-content my-auto">
                                    <div class="text-center">
                                        <h5 class="mb-0">Change Password</h5>
                                    </div>

                                    <form class="custom-form mt-4 pt-2" id="forceChangePSW" data-res_function="changePasswordRes">

                                        <div class="mb-3">
                                            <label for="old_password">Old Password</label>
                                            <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password" />
                                            <div class="error old_password"></div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="new_password">New Password</label>
                                            <div class="input-group"> 
                                                <input type="password" name="new_password" id="new_password" class="form-control pswType" placeholder="Enter Password" value="">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn waves-effect waves-light btn-outline-primary pswHideShow" style="height:35px;"><i class="fa fa-eye"></i></button>
                                                </div>
                                            </div>
                                            <div class="error new_password"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="cpassword">Confirm Password</label>
                                            <input type="text" name="cpassword" id="cpassword" class="form-control" placeholder="Confirm Password" />
                                            <div class="error cpassword"></div>
                                        </div>

                                        <div class="mb-3">
                                            <button class="btn btn-facebook btn-round btn-outline-dashed w-100 p-2" type="button" onclick="customStore({'formId':'forceChangePSW','fnsave':'changePassword','controller':'hr/employees'});">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="login-poweredby font-medium pad-5">NATIVEBIT TECHNOLOGIES</div>
                </div>
                <!-- end col -->
                <div class="col-xxl-9 col-lg-8 col-md-7 auth-bg">
                    <div class=" pt-md-5 p-4 d-flex">
                        <div class="bg-overlay bg-primary1"></div>
                        <ul class="bg-bubbles">
                            <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
                        </ul>
                        <!-- end bubble effect -->
                        <!-- <h4 class="col-xxl-12 p-0 p-sm-4 px-xl-0 text-white text-center">NATIVEBIT TECHNOLOGIES</h4> -->
                    </div>
                </div>
            </div>
        </div>
    </div>	
    

    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
	<script src="<?=base_url()?>assets/js/jquery/dist/jquery.min.js"></script>
	<script src="<?=base_url()?>assets/js/app.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
</body>

</html>

<script>
var base_url = '<?=base_url();?>'; 
$(document).ready(function(){
    $(document).on('click','.pswHideShow',function(){
		var type = $('.pswType').attr('type');
		if(type == "password"){
			$(".pswType").attr('type','text');
			$(this).html('<i class="fa fa-eye-slash"></i>');
		}else{
			$(".pswType").attr('type','password');
			$(this).html('<i class="fa fa-eye"></i>');
		}
	});
});

function customStore(postData){		
	var formId = postData.formId;
	var fnsave = postData.fnsave || "save";
	var controllerName = postData.controller || controller;

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
		if(data.status==1){
            window.location.href = base_url;
		}else{
			if(typeof data.message === "object"){
				$(".error").html("");
				$.each( data.message, function( key, value ) {$("."+key).html(value);});
			}else{
				Swal.fire({ icon: 'error', title: data.message });
			}			
		}				
	});
}
</script>