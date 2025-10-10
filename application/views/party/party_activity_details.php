
<!-- <link href="<?=base_url()?>assets/css/bootstrap.css" rel="stylesheet" type="text/css" /> -->
<!-- <style>
    .bs-right-md-modal .modal-footer textarea {border-radius:2rem!important;padding:10px;}
    .bs-right-md-modal .modal-footer a {font-size:2rem;}
    .modal-footer-fixed {
  position: fixed;
  bottom: 0;
  width: 100%;
  background: #fff;
  border-radius: 0;
}
</style> -->

<div class="col-md-12 partyActivityBody" >
    <div class="row">
        <?php
            echo '<div class="party_activity">';
            echo '<input type="hidden" name="party_id" id="partyid" value="'.$party_id.'" >';
            foreach($activityDetails as $row):
                $link = $icon = '';$responseLink = '';

                if(in_array($row->lead_stage,[6,7])){ $link =' #'.$row->ref_no;}
                // if($row->lead_stage == 2 && empty($row->response)){
                //     $responseParam = "{'postData':{'id' : ".$row->id.",'party_id' : ".$row->party_id.",'response':'".$row->response."'},'modal_id' : 'modal-md', 'form_id' : 'response', 'title' : 'Reminder Response', 'call_function' : 'addReminder', 'fnsave' : 'saveReminder','res_function':'resReminder'}";
                //     $responseLink .= '<a class="dropdown-item btn text-dark permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$responseParam.');"><i class="mdi mdi-square-edit-outline fs-12"></i> Response</a>';
                // }
                if($row->lead_stage == 2){
                    $link = '<p class="text-muted fs-11"><strong>'.$row->mode.' : </strong> </p>';
                }
                if($row->lead_stage == 2 && empty($row->response)){
                    $responseLink = '<a type="button" class="text-link" data-bs-toggle="collapse" data-bs-target="#responseDiv'.$row->id.'"><i class="mdi mdi-square-edit-outline fs-12"></i> Give Response</a>
                    <div id="responseDiv'.$row->id.'" class="collapse">
                        <input type="text" name="response" data-id="'.$row->id.'" class="form-control responseInput" style="resize:none;width:90%;" placeholder="Response...">
                    </div>';
                }
				$icon = isset($this->iconClass[$row->lead_stage]) ? $this->iconClass[$row->lead_stage] : 'fa fa-dot-circle-o bg-soft-blue';

				
                echo '<div class="activity-info">
						<div class="item-timeline timeline-new">
							 <div class="icon-info-activity">
                                    <i class="'.$icon.'"></i>
                            </div>
							<div class="t-content">
								<div class="t-uppercontent">
									<h5 class="font-bold w-100">'.$row->notes.$link.'</h5>
								</div>
								'.(!empty($row->remark) ? '<p class="text-dark">'.$row->remark.'</p>' : '').'
								'.(!empty($row->response) ? '<p class="text-dark">'.$row->response.'</p>' : '').'
								<div class="timeline-bottom">
									<div class="tb-section-1">
										<p>'.date("d F, y",strtotime($row->ref_date)).'</p>
									</div>
								</div>
								'.(!empty($responseLink) ? '<p class="text-dark">'.$responseLink.'</p>' : '').'
							</div>
						</div>';

                    //    echo '<div class="activity-info">
                    //             <div class="icon-info-activity">
                    //                 <i class="'.$icon.'"></i>
                    //             </div>
                    //             <div class="activity-info-text">
                    //                 <div class="d-flex justify-content-between align-items-center">
                    //                     <h6 class="text-muted m-1 font-12">'.$row->notes.$link.'</h6>
                    //                 </div>
                    //                 <div class="d-flex justify-content-between">
                    //                     <span class="text-muted w-30 d-block font-12">
                    //                     '.date("d F,y",strtotime($row->ref_date)) .'
                    //                     </span>
                    //                 </div>
                    //                 <p class=" m-1 font-12"><i class="fa fa-user"></i> '.$row->created_by_name.'</p>
                    //                 '.(!empty($row->response) ? '<p class="text-muted font-11"> Res : '.$row->response.'</p>' : '').'
                    //             </div>
                    //         </div>';
            endforeach;
            echo '</div>';
        ?>
        
    </div>
    <!-- modal-footer-fixed -->
    <div class="modal-footer "> 
        <textarea type="text" rows="1" name="msg_content" id="msg_content" class="form-control" placeholder="Type a Message..." style="width:90%"></textarea>
    </div>
</div>
<script>
$(document).ready(function(){
    $(".modal-footer .btn-close-modal").hide();
    $(".modal-footer .btn-save").hide();

    $("#msg_content").keypress(function (e) {
		if(e.which === 13 && !e.shiftKey) {
			e.preventDefault();
			saveFollowups();
		}
	});

    $(".responseInput").keypress(function (e) {
		if(e.which === 13 && !e.shiftKey) {
			e.preventDefault();
			var party_id = $("#partyid").val();
			var responseVal = $(this).val();
			var id = $(this).data('id');
            var postdata = {id:id, response:responseVal,party_id:party_id};
			if(responseVal != ''){
				$.ajax({
					url: base_url + controller + '/saveResponse',
					data: postdata,
					type: "POST",
					global:false,
					dataType:"json",
				}).done(function(response){
					if(response.status==1){$(".response").val('');
                        $(".partyActivityBody").html(response.activityLogs);}
				});
			}
		}
	});
});


 function saveFollowups(){
	var party_id = $("#partyid").val();
	var notes = $("#msg_content").val();

	if(notes != ''){
		$.ajax({
			url: base_url + controller + '/saveFollowups',
			data: {party_id:party_id, notes:notes},
			type: "POST",
			global:false,
			dataType:"json",
		}).done(function(response){
			if(response.status==1){$("#msg_content").val('');
				$(".partyActivityBody").html(response.activityLogs);
			}
		});
	}
}
</script>


