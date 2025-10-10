<?php $this->load->view('app/includes/header'); ?>
<style>
.sh-perfect{box-shadow: 0px 4px 8px -2px rgba(9, 30, 66, 0.25), 0px 0px 0px 1px rgba(9, 30, 66, 0.08);}
.bg-light-teal{ background-color: #EFFBF9!important; }
.bg-light-peach{ background-color: #F0F5FB!important; }
.bg-light-greyish{ background-color: #D1D7E2!important; }
.bg-light-sky{ background-color: #F0F5FB!important; }
.bg-light-pigeon{ background-color: #AFBACC!important; }
.bg-light-airforce{ background-color: #598BAF!important; }
.bg-light-thunder{ background-color: #DDD4C0!important; }
.bg-light-harbor{ background-color: #C7C6C1!important; }
.bg-light-laurel{ background-color: #A9BA9D!important; }
.bg-light-cream{ background-color: #FEF9ED!important; }
.bg-light-raspberry{ background-color: #FCEBF1!important; }
</style>
	<!-- Header -->
	<header class="header">
		<div class="main-bar">
			<div class="container">
				<div class="header-content">
					<div class="left-content">
						<a href="javascript:void(0);" class="menu-toggler me-2">
    						<!-- <i class="fa-solid fa-bars font-16"></i> -->
    						<svg class="text-dark" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 0 24 24" width="30px" fill="#000000"><path d="M13 14v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1zm-9 7h6c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1zM3 4v6c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1zm12.95-1.6L11.7 6.64c-.39.39-.39 1.02 0 1.41l4.25 4.25c.39.39 1.02.39 1.41 0l4.25-4.25c.39-.39.39-1.02 0-1.41L17.37 2.4c-.39-.39-1.03-.39-1.42 0z"></path></svg>
    					</a>
						<h6 class="title mb-0 text-nowrap"><?=$prcData->prc_number?><small> <?= date("d M Y", strtotime($prcData->prc_date)) ?></small></h6>
					</div>
					<div class="mid-content">
					</div>
					<div class="right-content">
                        <?= floatval($prcData->prc_qty) ?>NOS
					</div>
				</div>
			</div>
		</div>
	</header>
	<!-- Header -->
    <div class="container bg-light-sky">
        <div class=" order-box mb-0 mt-0" >
		    <div class="mb-0  mt-0">
    			<div class="order-content mb-0  mt-0">
    				<div class="right-content">
    					<h6 class="order-number">  <?= (!empty($prcData->item_code) ? '['.$prcData->item_code.'] '.$prcData->item_name : $prcData->item_name ) ?></h6>
    					<ul>
    					    <li> <h6 class="order-time"><?= (!empty($prcData->party_name)?$prcData->party_name:'Self') ?></h6> 	</li>
    						<li> <p class="order-name"> <?= $prcData->remark ?></p> </li>
    					</ul>
    				</div>
    			</div>
		    </div>
	    </div>
    </div>
	<div class="page-content"  id="prcBoard" style="overflow:scroll !important;height:80vh;">
		<div class="content-inner pt-0" >
			<div class="container">
				<div class=" prcProcess">
				</div>
			</div>
		</div>
	</div>
<script>
	
</script>
<?php $this->load->view('app/includes/bottom_menu'); ?>
<?php $this->load->view('app/includes/footer'); ?>
<?php $this->load->view('app/includes/sidebar'); ?>


<script>
	loadPrcDetail();
	function loadPrcDetail(){
		console.log("Helloooo");
        var id = <?=$prcData->id?>;
		$.ajax({
            url: base_url  + 'app/sop/getPrcDetailHtml',
            data:{id:id},
            type: "POST",
            dataType:"json",
        }).done(function(response){
            $(".prcProcess").html(response.processDetail);                   
        });
	}

	
function prcResponse(data,formId=""){
	if(data.status==1){	
		closeModal(formId); 
		Swal.fire( 'Success', data.message, 'success' );
		loadPrcDetail();
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            Swal.fire({ icon: 'error', title: data.message });
        }			
    }	
}

function getPRCAcceptHtml(data){
	var postData = data.postData || {};
	var fnget = data.fnget || "";
	var controllerName = data.controller || controller;

	var table_id = data.table_id || "";
	var thead_id = data.thead_id || "";
	var tbody_id = data.tbody_id || "";
	var tfoot_id = data.tfoot_id || "";	

	if(thead_id != ""){
		$("#"+table_id+" #"+thead_id).html(data.thead);
	}
	
	$.ajax({
		url: base_url  + 'sopDesk/' + fnget,
		data:postData,
		type: "POST",
		dataType:"json",
		beforeSend: function() {
			if(table_id != ""){
				var columnCount = $('#'+table_id+' thead tr').first().children().length;
				$("#"+table_id+" #"+tbody_id).html('<tr><td colspan="'+columnCount+'" class="text-center">Loading...</td></tr>');
			}
		},
	}).done(function(res){
		$("#"+table_id+" #"+tbody_id).html('');
			$("#"+table_id+" #"+tbody_id).html(res.tbodyData);
			loadPrcDetail();
			initSelect2();
			if(tfoot_id != ""){
				$("#"+table_id+" #"+tfoot_id).html('');
				$("#"+table_id+" #"+tfoot_id).html(res.tfootData);
			}
	});
}

// 28-05-2024
function getPRCMovementHtml(data){
	var postData = data.postData || {};
	var fnget = data.fnget || "";
	var controllerName = data.controller || controller;

	var table_id = data.table_id || "";
	var thead_id = data.thead_id || "";
	var tbody_id = data.tbody_id || "";
	var tfoot_id = data.tfoot_id || "";	

	if(thead_id != ""){
		$("#"+table_id+" #"+thead_id).html(data.thead);
	}
	
	$.ajax({
		url: base_url  + 'sopDesk/' + fnget,
		data:postData,
		type: "POST",
		dataType:"json",
		beforeSend: function() {
			if(table_id != ""){
				var columnCount = $('#'+table_id+' thead tr').first().children().length;
				$("#"+table_id+" #"+tbody_id).html('<tr><td colspan="'+columnCount+'" class="text-center">Loading...</td></tr>');
			}
		},
	}).done(function(res){
		$("#"+table_id+" #"+tbody_id).html('');
			$("#"+table_id+" #"+tbody_id).html(res.tbodyData);
			$("#pending_movement_qty").html("Pending Qty : "+res.pendingQty);
			// loadProcessDetail(postData);
			
			initSelect2();
			if(tfoot_id != ""){
				$("#"+table_id+" #"+tfoot_id).html('');
				$("#"+table_id+" #"+tfoot_id).html(res.tfootData);
			}
	});
}

function trashSop(data){
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
</script>