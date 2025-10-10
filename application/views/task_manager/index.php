<?php $this->load->view('includes/header'); ?>
<style>li.nav-item{padding:0px 3px;}.process-tags span{font-size:0.75rem;color:#000;box-shadow: 0px 1px 1px rgba(9, 30, 66, 0.25), 0px 0px 1px 1px rgba(9, 30, 66, 0.13);}.prcList{padding:0.4rem;}.custom-checkbox label{margin-bottom:0px;}</style>

<div class="page-content-tab" style="padding:10px 10px 10px 0px;">
	<div class="container-fluid">
		<div class="email-app border-top">
			<!-- Group List -->
			<div class="left-part bg-light-300">
				<a class="ti-menu ti-close btn btn-success show-left-part d-block d-md-none" href="javascript:void(0)"></a>
				<div class="scrollable" style="height:100%;">
					<div class="p-15">
						<button class="waves-effect waves-light btn btn-danger btn-block addNewTask permission-write press-add-btn" type="button" data-button="both" data-modal_id="modal-lg" data-function="addTask" data-controller="taskManager" data-form_title="Add Task" data-js_store_fn="saveTask" data-txt_editor="notes"><i class="fas fa-plus"></i> <b>Task</b></button>
					</div>
					<div class="divider"></div>
					<ul class="list-group group_list"></ul>
					
					<div class="p-15">
						<?php
							$addParam = "{'modal_id' : 'modal-md', 'call_function':'addGroup', 'form_id' : 'addGroup', 'title' : 'Add Group','fnsave' : 'saveGroup','res_function' : 'getGroupList','js_store_fn' : 'customStore','form_close' : 'close'}";
                        ?>
                        <button type="button" class="waves-effect waves-light btn btn-danger btn-block permission-write press-add-btn" onclick="modalAction(<?=$addParam?>);"><i class="fas fa-plus"></i> Add Group</button>
					</div>
					
				</div>
			</div>
			
			<!-- Task List -->
			<div class="right-part mail-list bg-white">
				<!-- <div class="p-15 b-b">
					<div class="d-flex align-items-center">
						<div class="group_heading">
							<h4>Taskbox </h4>
							<span>Here is the list of Task</span>
						</div>
						<div class="ml-auto">
							<div class="input-group" id="qs" style="min-width: 300px;">                               
								<input type="text" id="quick_search" class="form-control qs quicksearch form-control-sm" style="width:80%;" placeholder="Search...">
								<button type="button" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
							</div>
						</div>
					</div>
				</div> -->
				<div class="p-15 b-b">
					<div class="d-flex align-items-center justify-content-between flex-wrap">
					
						<!-- Left: Title and Subtitle -->
						<div class="group_heading">
							<h4 class="mb-0">Assigned To Me</h4>
							<span>Here is the list of Task</span>
						</div>

						<!-- Right: Search box -->
						<div style="min-width: 300px;">
							<div class="input-group input-group-sm">
								<input type="text" id="quick_search" class="form-control quicksearch" placeholder="Search...">
								<button type="button" class="btn btn-primary">
								<i class="fas fa-search"></i>
								</button>
							</div>
						</div>

					</div>
				</div>
				<div class="card jpPanel-widget">
					<div class="card-body row grid task_list" data-isotope='{ "itemSelector": ".grid_item" }' style="background-color:#e9edf2;min-height:85vh;">
					
					</div>
				</div>
			</div>
			
			<!-- Task Form -->
			<div class="right-part mail-compose bg-white" style="display: none; position:relative;">
				<div class="p-20 border-bottom">
					<div class="d-flex align-items-center">
						<div>
							<h4>Task Manager</h4>
							<span>Create New Task</span>
						</div>
						<div class="ml-auto">
							<button id="cancel_compose" onclick="changeScreen()" class="btn btn-dark">Back</button>
						</div>
					</div>
				</div>
				<div id="task_form" class="card-body task_form"></div>
			</div>
			
			<!-- Task Detail View -->
			<div class="right-part mail-details bg-white task_view" style="display: none;"></div>
		</div>
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script src="<?=base_url()?>assets/plugins/isotop/isotope.pkgd.min.js"></script>

<script>
var buttonFilter;
var qsRegex;
var isoOptions ={};
var $grid = '';
$(document).ready(function() {
	
	isoOptions = {
		itemSelector: '.grid_item',
		percentPosition: true,
		layoutMode: 'fitRows',
		filter: function() {
			var $this = $(this);
			var searchResult = qsRegex ? $this.text().match( qsRegex ) : true;
			var buttonResult = buttonFilter ? $this.is( buttonFilter ) : true;
			return searchResult && buttonResult;
		}
	};
	
	// init isotope
	$grid  = $('.grid').isotope( isoOptions );
	var $qs = $('.qs').keyup( debounce(function() {qsRegex = new RegExp( $qs.val(), 'gi' );$grid.isotope();}, 200 ) );
	
	// bind filter button click
	$('#buttonFilter').on( 'click', 'button', function() {
		var filterValue = $( this ).attr('data-filter');
		buttonFilter = filterValue;
		$grid.isotope();
	});
	
	getTasklist();
	changeScreen();
	getGroupList();
	
	$(document).on("change",".lbl_anm", function() {
        if($(this).val()){ $(this).parent().addClass("not-empty"); }
    });
	
	$(document).on("keydown", "#step_note", function(event) { 
		if(event.keyCode == 13) {
			//event.preventDefault();
			var step_note = $(this).val();
			var task_id = $(this).data('task_id');
			$.ajax({
				url: base_url + controller + '/saveTaskStep',
				data:{step_note:step_note, task_id:task_id},
				type: "POST",
				dataType:"json",
				global:false,
			}).done(function(response){
				$("#step_note").val("");
				if(response.status == 1)
				{
					$(".stepList").html("");
					$(".stepList").html(response.stepsList);
				}
			});
		}
	});
	
    var mail = $('.email-table .max-texts a');

    $(mail).on("click", function() {
        $('.right-part.mail-list').fadeOut("fast");
        $('.right-part.mail-details').fadeIn("fast");
    });

	$(document).on('click',".addNewTask",function(){
		
		changeScreen(2);
		
        var functionName = $(this).data("function");
        var modalId = $(this).data('modal_id');
        var button = $(this).data('button');
		var title = $(this).data('form_title');
		var formId = functionName.split('/')[0];
		var fnsave = $(this).data("fnsave"); if(fnsave == "" || fnsave == null){fnsave="save";}
		var txt_editor = $(this).data('txt_editor');
		var jsStoreFn = $(this).data('js_store_fn') || 'store';

		var fnJson = "{'formId':'"+formId+"','fnsave':'"+fnsave+"','txt_editor':'"+txt_editor+"','controller':'"+controller+"'}";

		var ref_id = $(this).data("ref_id");
		
        $.ajax({ 
            type: "POST",   
            url: base_url + controller + '/' + functionName,   
            data: {id:ref_id}
        }).done(function(response){
            $("#task_form").html("");
            $("#task_form").html(response);
			$("#task_form form").attr('id',formId);
			$("#"+formId+" .btn-save").attr('onclick',jsStoreFn+"("+fnJson+");");
			
			initSelect2();
			$("#processDiv").hide();
			setTimeout(function(){ initMultiSelect();setPlaceHolder(); }, 5);
        });
    });

	$(document).on('click',".group_item",function(){
		var group_id = $(this).data("id") || 0;
		var group_name = $(this).data("group_name") || 0;
		getTasklist(group_id,group_name);
	});

	$(document).on('click',".viewTask",function(){
		var id = $(this).data("id");
		$.ajax({
			url: base_url  + 'taskManager/getTaskDetail',
			data:{id : id},
			type: "POST",
			dataType:"json",
		}).done(function(response){
			changeScreen(3);
			$(".task_view").html(response.taskView);
		});
	});

	$(document).on('click',".changeTaskStatus",function(){
		var id = $(this).data("id");
		var status = $(this).data("status");
		var group_id = $(this).data("group_id");
		let send_data = { id:id,status:status,group_id:group_id };
		if(status == 3){cancelRecord(send_data,'changeTaskStatus','task_list');}
		else{changeStatus(send_data,'changeTaskStatus','task_list');}
	});

	$(document).on('click',".changeStepStatus",function(){
		var id = $(this).data("id");
		var status = $(this).data("status");
		var task_id = $(this).data("task_id");

		let send_data = { id:id,status:status,task_id:task_id };
		if(status == 3){cancelRecord(send_data,'changeTaskStepStatus','stepList');}
		else{changeStatus(send_data,'changeTaskStepStatus','stepList');}
	});

});

function debounce(fn,threshold ) {
	var timeout;
	threshold = threshold || 100;
	return function debounced() {
		clearTimeout( timeout );
		var args = arguments;
		var _this = this;
		function delayed() {fn.apply( _this, args );}
		timeout = setTimeout( delayed, threshold );
	};
}

function changeScreen(type=1){
	if(type == 1) // Show Task List
	{
		$('.right-part.mail-compose').fadeOut("fast");
		$('.right-part.mail-details').fadeOut("fast");
        $('.right-part.mail-list').fadeIn("fast");
		//getTasklist();
	}
	if(type == 2) // Create Task
	{
        $('.right-part.mail-list').fadeOut("fast");
		$('.right-part.mail-details').fadeOut("fast");
		$('.right-part.mail-compose').fadeIn("fast");
	}	
	if(type == 3) // View Task Detail
	{
		$('.right-part.mail-compose').fadeOut("fast");
        $('.right-part.mail-list').fadeOut("fast");
		$('.right-part.mail-details').fadeIn("fast");
	}
}

function getTasklist(group_id = 0,group_name="") {
	$.ajax({
		url: base_url  + 'taskManager/getTasklist',
		data:{group_id : group_id, group_name:group_name},
		type: "POST",
		dataType:"json",
	}).done(function(response){
		changeScreen(1);
		
		$(".group_heading").html(response.groupHead);
		$('.grid').isotope('destroy');	
		$(".grid").html(response.taskList);
		$grid = $('.grid').isotope( isoOptions );
	});
}

function saveTask(postData){
	setPlaceHolder();
	$(".btn-save").attr("disabled", true);	
	var formId = postData.formId;
	var fnsave = postData.fnsave || "save";
	var controllerName = postData.controller || controller;
	var form = $('#'+formId)[0];
	var fd = new FormData(form);
	var group_id = $("#group_id").val();
	
	$.ajax({
		url: base_url + controllerName + '/' + fnsave,
		data:fd,
		type: "POST",
		processData:false,
		contentType:false,
		dataType:"json",
	}).done(function(data){
		$(".btn-save").removeAttr("disabled");
	    if(data.status==1){
			$('#'+formId)[0].reset();getTasklist(group_id); getGroupList();
		
			initTable(); $('#'+formId)[0].reset(); closeModal(formId);
			Swal.fire({ icon: 'success', title: data.message});
			
		}else{
			if(typeof data.message === "object"){
				$(".error").html("");
				$.each( data.message, function( key, value ) {$("."+key).html(value);});
			}else{
				$cmonth = 'm'.intval(date('m'));  
			}
		}	        
	});
}

function taskEdit(data){
	var postData = data.postData || "";
	var button = data.button || "both";
	var functionName = data.functionName || "edit";
	var fnsave = data.fnsave || "save";
	var jsStoreFn = data.js_store_fn || 'store';
	var formId = data.form_id || "saveTask";
	var title = data.title || "";
	var txt_editor = data.txt_editor || "";

	changeScreen(2);

	var fnJson = "{'formId':'"+formId+"','fnsave':'"+fnsave+"','txt_editor':'"+txt_editor+"','controller':'"+controller+"'}";

	var ref_id = postData.id;
	
	$.ajax({ 
		type: "POST",   
		url: base_url + controller + '/' + functionName,   
		data: {id:ref_id}
	}).done(function(response){
		$("#task_form").html("");
		$("#task_form").html(response);
		$("#task_form form").attr('id',formId);
		$("#"+formId+" .btn-save").attr('onclick',jsStoreFn+"("+fnJson+");");
		
		initSelect2();
		$("#processDiv").hide();//$('#tags').tagsinput('refresh');
		setTimeout(function(){ initMultiSelect();setPlaceHolder(); }, 5);
	});
}

function getTaskDetail(postData = []){
	$.ajax({
		url: base_url  + 'taskManager/getTaskDetail',
		data:postData,
		type: "POST",
		dataType:"json",
	}).done(function(res){
		changeScreen(3);
		//$(".taskLog").html(res.taskLog);
	});
}

function trashTask(data){
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
				if(response.status==1){
					getTasklist();
					getGroupList();
				}else{
					if(response.status==0){ 
						Swal.fire( 'Sorry...!', response.message, 'error' );
					}else{
						Swal.fire( 'Deleted!', response.message, 'success' );
					}	
				}
			});
		}
	});
	
}

function changeStatus(send_data,fn="",resEle=""){
	if(send_data.status)
	{
		$.ajax({
			url: base_url + controller + '/' + fn,
			data: send_data,
			type: "POST",
			dataType:"json",
			global:false,
			success:function(response)
			{
				if(response.status==1)
				{
					$("."+resEle).html("");
					$("."+resEle).html(response.list);
					getGroupList();
				}
			}
		});
	}
}

// function cancelRecord(data){
// 	var controllerName = data.controller || controller;
// 	var fnName = data.fndelete || "delete";
// 	var msg = data.message || "Record";
// 	var send_data = data.postData;
// 	var resFunctionName = data.res_function || "";
	
	// Swal.fire({
	// 	title: 'Are you sure?',
	// 	text: "You won't be able to revert this!",
	// 	icon: 'warning',
	// 	showCancelButton: true,
	// 	confirmButtonColor: '#3085d6',
	// 	cancelButtonColor: '#d33',
	// 	confirmButtonText: 'Yes, delete it!',
	// }).then(function(result) {
	// 	if (result.isConfirmed){
	// 		$.ajax({
	// 			url: base_url + controllerName + '/' + fnName,
	// 			data: send_data,
	// 			type: "POST",
	// 			dataType:"json",
	// 		}).done(function(response){
	// 			if(resFunctionName != ""){
	// 				window[resFunctionName](response);
	// 			}else{
	// 				if(response.status==0){
	// 					Swal.fire( 'Sorry...!', response.message, 'error' );
	// 				}else{
	// 					initTable();
	// 					Swal.fire( 'Deleted!', response.message, 'success' );
	// 				}	
	// 			}
	// 		});
	// 	}
	// });
	
// }

function cancelRecord(send_data,fn="",resEle=""){
	if(send_data.status)
	{
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
					url: base_url + controller + '/' + fn,
					data: send_data,
					type: "POST",
					dataType:"json",
				}).done(function(response){
					if(response.status==1){
						$("."+resEle).html("");
						$("."+resEle).html(response.list);
					}else{
						if(response.status==0){ 
							Swal.fire( 'Sorry...!', response.message, 'error' );
						}else{
							Swal.fire( 'Deleted!', response.message, 'success' );
						}	
					}
					
				});
			}
		});
	}
}

function getGroupList(){
	$.ajax({
		url: base_url  + 'taskManager/getGroupList',
		data:{ },
		type: "POST",
		dataType:"json",
	}).done(function(response){
		changeScreen(1);
		$(".group_list").html(response.groupList);
	});
}

</script>