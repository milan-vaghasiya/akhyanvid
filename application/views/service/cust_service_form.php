<!DOCTYPE html>
<html dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url();?>assets/images/favicon.png">

        <title>SERVICE - <?=(!empty(SITENAME))?SITENAME:""?></title>
        
        <link href="<?=base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=base_url();?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="<?=base_url();?>assets/css/app.min.css" rel="stylesheet" type="text/css" />	
        <link href="<?=base_url();?>assets/plugins/datatables/bootstrap-datatable/css/dataTables.bootstrap4.css" rel="stylesheet">    
        
        <!-- Sweet Alert -->
        <link href="<?=base_url();?>assets/plugins/sweet-alert2/sweetalert2.min.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url();?>assets/plugins/animate/animate.min.css" rel="stylesheet" type="text/css">
        
        <!-- Select 2 -->
        <link href="<?=base_url()?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url()?>assets/plugins/select2/css/select2-bootstrap4.min.css" rel="stylesheet" type="text/css">
        <link href="<?=base_url()?>assets/js/pages/multiselect/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css">

        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="<?=base_url("assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css")?>">
        
        <!-- Custom CSS -->
        <link href="<?=base_url();?>assets/css/bootstrap-side-modals.css" rel="stylesheet">
        <link href="<?=base_url();?>assets/css/jp_helper.css?v=<?=time()?>" rel="stylesheet">	
        <link href="<?=base_url();?>assets/css/cm-style.css?v=<?=time()?>" rel="stylesheet">	
    </head>
    <body>
        <div class="page-content-tab">
	        <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">Service</h4>
                            </div>
                            <div class="card-body">
                                <form id="custService">
                                    <div class="col-md-12">
                                        <div class="row">

                                            <input type="hidden" name="id" id="id" value="">
                                            <input type="hidden" name="trans_prefix" id="trans_prefix" value="<?=((!empty($trans_prefix))?$trans_prefix:"")?>">
                                            <input type="hidden" name="trans_no" id="trans_no" value="<?=((!empty($trans_no))?$trans_no:"")?>">
                                            <input type="hidden" name="trans_number" id="trans_number" value="<?=((!empty($trans_number))?$trans_number:"")?>">
                                            <input type="hidden" name="project" id="project" value="<?=((!empty($party_id))?$party_id:"")?>">
                                            <input type="hidden" name="type" id="type" value="CUST">
                                            <input type="hidden" name="trans_date" id="trans_date" value="<?=date('Y-m-d')?>">

                                            <div class="col-md-12 form-group">
                                                <label for="service_reason">Service Reason</label>
                                                <select name="service_reason" id="service_reason" class="form-control select2 req">
                                                    <option value="">Select Reason</option>
                                                    <?php
                                                        foreach($serviceReasons as $row):
                                                            echo '<option value="'.$row->label.'">'.$row->label.'</option>';
                                                        endforeach;
                                                    ?>
                                                </select>
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label for="problem">Problem</label>
                                                <textarea name="problem" id="problem" class="form-control req" rows="3"></textarea>
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <label for="bfr_images">Images</label>
                                                <input type="file" name="bfr_images[]" id="bfr_images" class="form-control" multiple="multiple" >
                                            </div> 

                                        </div>
                                    </div>
                                </form>
                                <div class="col-md-12 form-group">
                                    <button type="button" class="btn waves-effect waves-light btn-success btn-block" onclick="storeCustService('custService');"><i class="fa fa-check"></i> Save Request</button>
                                </div>
                                <div class="col-md-12 form-group">
                                    <div class="row">
                                        <div class="serviceRes">
                                            <?=(!empty($resData) ? $resData : '')?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </body>
</html>

<script src="<?=base_url()?>assets/js/jquery/dist/jquery.min.js"></script>
<script src="<?=base_url()?>assets/js/pages/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/plugins/select2/js/select2.full.min.js"></script>
<script src="<?=base_url()?>assets/plugins/sweet-alert2/sweetalert2.min.js"></script>

<script>
setPlaceHolder();
var base_url = '<?=base_url();?>';

function setPlaceHolder(){
	var label="";
	$('input').each(function () {
		if(!$(this).hasClass('combo-input') && $(this).attr("type")!="hidden" )
		{
			label="";
			inputElement = $(this).parent();
			if($(this).parent().hasClass('input-group')){inputElement = $(this).parent().parent();}else{inputElement = $(this).parent();}
			label = inputElement.children("label").text();
			label = label.replace('*','');
			label = $.trim(label);
			if($(this).hasClass('req')){inputElement.children("label").html(label + ' <strong class="text-danger">*</strong>');}
			if(!$(this).attr("placeholder")){if(label){$(this).attr("placeholder", label);}}
			$(this).attr("autocomplete", 'off');
			var errorClass="";
			var nm = $(this).attr('name');
			if($(this).attr('id')){errorClass=$(this).attr('id');}else{errorClass=$(this).attr('name');if(errorClass){errorClass = errorClass.replace("[]", "");}}
			if(inputElement.find('.'+errorClass).length <= 0){inputElement.append('<div class="error '+ errorClass +'"></div>');}
		}
		else{$(this).attr("autocomplete", 'off');}
	});
	$('textarea').each(function () {
		label="";
		label = $(this).parent().children("label").text();
		label = label.replace('*','');
		label = $.trim(label);
		if($(this).hasClass('req')){$(this).parent().children("label").html(label + ' <strong class="text-danger">*</strong>');}
		if(label){$(this).attr("placeholder", label);}
		$(this).attr("autocomplete", 'off');
		var errorClass="";
		var nm = $(this).attr('name');
		if($(this).attr('name')){errorClass=$(this).attr('name');}else{errorClass=$(this).attr('id');}
		if($(this).parent().find('.'+errorClass).length <= 0){$(this).parent().append('<div class="error '+ errorClass +'"></div>');}
	});
	$('select').each(function () {
		let string =String($(this).attr('name'));
		if(string.indexOf('[]') === -1)
		{
			label="";
			var selectElement = $(this).parent();
			if($(this).hasClass('single-select')){selectElement = $(this).parent().parent();}
			label = selectElement.children("label").text();
			label = label.replace('*','');
			label = $.trim(label);
			if($(this).hasClass('req')){selectElement.children("label").html(label + ' <strong class="text-danger">*</strong>');}
			var errorClass="";
			var nm = $(this).attr('name');
			
			if($(this).attr('name')){errorClass=$(this).attr('name');}else{errorClass=$(this).attr('id');}
			if(selectElement.find('.'+errorClass).length <= 0){selectElement.append('<div class="error '+ errorClass +'"></div>');}
		}
	});
}

function storeCustService(formId){
    var form = $('#'+formId)[0];
	var fd = new FormData(form);

	$.ajax({
		url: base_url + 'custService/save',
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(data){
        console.log(data);
		if(data.status==1){
            $('#'+formId)[0].reset();
			Swal.fire({ 
                icon: 'success', title: data.message 
            }).then(function(result) {
                if (result.isConfirmed){	
                    $('.serviceRes').html(data.resData);
                }
            });            
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