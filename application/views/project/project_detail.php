<?php $this->load->view("includes/header"); ?>
<style>.input-group .select2-container{width:70%!important;}</style>
<div class="page-content-tab">
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-end">
                        <?php
							
							$addInchargeParam = "{'postData':{'project_id' : ".$project_id."}, 'modal_id' : 'modal-md', 'call_function':'addIncharge', 'fnsave':'saveIncharge', 'form_id' : 'addIncharge', 'title' : 'Add In-Charge'}";
						?>
						<button type="button" class="btn waves-effect waves-light btn-outline-dark permission-write press-add-btn" onclick="modalAction(<?=$addInchargeParam?>);"><i class="fa fa-plus"></i> Add In-Charge</button>
						<a href="<?=base_url("project")?>" class="btn btn-outline-dark"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="met-profile">
                            <div class="row">

                               <div class="col-lg-4 align-self-center mb-3 mb-lg-0">
                                    <div class="met-profile-main">
                                        <div class="d-flex align-items-center">
                                            <span class="thumb-xl justify-content-center d-flex align-items-center bg-soft-success rounded-circle me-2">
                                                <?php 
                                                 $initial = (!empty($dataRow->project_name)) ? substr($dataRow->project_name, 0, 1) : ''; 
                
                                                if (!empty($dataRow->drawing_file)): ?>
                                                    <a href="<?= base_url("assets/uploads/project/" . $dataRow->drawing_file) ?>" download class="text-white" target="_blank"  flow="down">
                                                        <?= $initial ?>
                                                    </a>
                                                <?php else: ?>
                                                    <?= $initial ?>
                                                <?php endif; ?>
                                            </span>
                                        </div>

                                        <div class="met-profile_user-detail">
                                            <h5 class="met-user-name"><?= $dataRow->project_name ?? '' ?></h5>                                                         
                                            <p class="mb-0 met-user-name-post"><?= $dataRow->project_type ?? '' ?></p>                                                         
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-lg-4 ms-auto align-self-center">
                                    <ul class="list-unstyled personal-detail mb-0">
										<li class="">
                                            <i class="las la-briefcase mr-2 text-secondary font-22 align-middle"></i> <b> Customer </b> : <?=$dataRow->party_name??''?>
                                        </li>
                                        <li class="">
                                            <i class="las la-user mr-2 text-secondary font-22 align-middle"></i> <b> Contect Person </b> : <?=$dataRow->contact_person??''?>
                                        </li>
                                        <li class="">
                                            <i class="las la-phone mr-2 text-secondary font-22 align-middle"></i> <b> phone </b> : <?=$dataRow->party_phone??''?>
                                        </li>
                                        <li class="mt-2">
                                            <i class="las la-envelope text-secondary font-22 align-middle mr-2"></i> <b> Email </b> : <?=$dataRow->party_email??''?>
                                        </li>                                                  
                                    </ul>
                                </div>

                                <div class="col-lg-4 align-self-center">
                                    <div class="row">
                                        <div class="col-auto text-end border-end">
                                            <p class="mb-0 fw-semibold">Project Location</p>
                                            <h4 class="m-0 fw-bold"><?=$dataRow->location??''?></h4>
                                        </div>
                                        <div class="col-auto">
                                            <p class="mb-0 fw-semibold">Project Other Info</p>
                                            <h4 class="m-0 fw-bold"><?=(!empty($dataRow->other_info))?($dataRow->other_info):""?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <ul class="nav nav-tabs " id="pills-tab" role="tablist">
							<li class="nav-item">
								<a class="nav-link  active" id="pills-specification-tab" data-bs-toggle="tab" href="#specification" role="tab" aria-controls="pills-specification" aria-selected="true" >Specification</a>
							</li>
							<li class="nav-item">
								<a class="nav-link " id="pills-agency-tab" data-bs-toggle="tab" href="#agency" role="tab" aria-controls="pills-agency" aria-selected="true" >Agency</a>
							</li>
                            <li class="nav-item">
								<a class="nav-link " id="pills-workplan-tab" data-bs-toggle="tab" href="#workplan" role="tab" aria-controls="pills-workplan" aria-selected="true" >Work Plan</a>
							</li>
                            <li class="nav-item">
								<a class="nav-link " id="pills-instruction-tab" data-bs-toggle="tab" href="#instruction" role="tab" aria-controls="pills-instruction" aria-selected="true" >Work Instruction</a>
							</li>
							
						</ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!--  specification Start -->
                            <div class="tab-pane fade show active" id="specification" role="tabpanel" aria-labelledby="pills-specification-tab">
								<form id="addSpecification" data-res_function="getSpecificationHtml">
									<div class="card-body">
										<div class="row">
                                            <input type="hidden" name="id" id="id" value="" />
											<input type="hidden" name="project_id" id="project_id" value="<?= (!empty($project_id)) ? $project_id : ""; ?>" />
											<table id="specificTable" class="table table-bordered">
												<!-- <thead class="thead-info">
													
												</thead> -->
												<tbody id="specificBody">
                                                   
												</tbody>
											</table>
											<div class="col-md-12">
												<?php 
													$param = "{'formId':'addSpecification','fnsave':'saveSpecification','res_function':'getSpecificationHtml'}"; 
												?>
												<button type="button" class="btn waves-effect waves-light btn-outline-success btn-save save-form float-right" onclick="customStore(<?=$param?>)" style="height:36px"><i class="fa fa-check"></i> Save</button>
											</div>
										</div>
									</div>
								</form>
							</div>
                            <!--  specification End -->

                            <!--  agency Start -->
                            <div class="tab-pane fade" id="agency" role="tabpanel" aria-labelledby="pills-agency-tab">
                                <form id="addAgency" data-res_function="resAgencyHtml">
                                    <div class="card-body">
                                        <div class="row">
                                            <input type="hidden" name="id" id="id" value="" />
                                            <input type="hidden" name="project_id" id="project_id" value="<?=(!empty($project_id))?$project_id:""?>" />

                                          
                                            <div class="col-md-3 form-group">
                                                <label for="agency_name">Agency Name</label>
                                                <input type="text" name="agency_name" id="agency_name" class="form-control req" value="" />
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label for="agency_contact">Contact No.</label>
                                                <input type="text" name="agency_contact" id="agency_contact" class="form-control req numericOnly" value="" />
                                            </div>  
                                            <div class="col-md-6 form-group">
                                                <label for="remark">Remark</label>
                                                <div class="input-group">
                                                <input type="text" name="remark" id="remark" class="form-control" value="" />
													<div class="input-group-append">
														<?php
															$param = "{'formId':'addAgency','fnsave':'saveAgency','res_function':'resAgencyHtml'}";
														?>
														<button type="button" class="btn waves-effect waves-light btn-outline-success btn-save save-form float-right" onclick="customStore(<?=$param?>)" style="height:36px"><i class="fa fa-check"></i> Save</button>
													</div>
												</div>  
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="agencyDetail" class="table table-bordered align-items-center">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th style="width:5%;">#</th>
                                                    <th class="text-center">Agency Name</th>
                                                    <th class="text-center">Contact No.</th>                        
                                                    <th class="text-center">Remark </th>
                                                    <th class="text-center" style="width:10%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="agencyBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--  agency End -->

                            <!--  Work Plan Start -->

                            <div class="tab-pane fade " id="workplan" role="tabpanel" aria-labelledby="pills-workplan-tab">
								<form id="addWorkPlan" data-res_function="getWorkPlanHtml">
									<div class="card-body">
										<div class="row">
                                            <input type="hidden" name="id" id="id" value="" />
											<input type="hidden" name="project_id" id="project_id" value="<?= (!empty($project_id)) ? $project_id : ""; ?>" />
											<table id="planTable" class="table table-bordered">
												<tbody id="planBody">
												</tbody>
											</table>
											<div class="col-md-12">
												<?php 
													$param = "{'formId':'addWorkPlan','fnsave':'saveWorkPlan','res_function':'getWorkPlanHtml'}"; 
												?>
												<button type="button" class="btn waves-effect waves-light btn-outline-success btn-save save-form float-right" onclick="customStore(<?=$param?>)" style="height:36px"><i class="fa fa-check"></i> Save</button>
											</div>
										</div>
									</div>
								</form>
							</div>
                            <!--  Work Plan End -->

                              <!--  Work Instruction Start -->

                            <div class="tab-pane fade " id="instruction" role="tabpanel" aria-labelledby="pills-instruction-tab">
								<form id="addWorkInstruction" data-res_function="getWorkInstructionHtml">
									<div class="card-body">
										<div class="row">
                                            <input type="hidden" name="project_id" id="project_id" value="<?= (!empty($project_id)) ? $project_id : ""; ?>" />
											<table id="instructionTable" class="table table-bordered">
												<tbody id="instructionBody">
												</tbody>
											</table>
											<div class="col-md-12">
												<?php 
													$param = "{'formId':'addWorkInstruction','fnsave':'saveWorkInstruction','res_function':'getWorkInstructionHtml'}"; 
												?>
												<button type="button" class="btn waves-effect waves-light btn-outline-success btn-save save-form float-right" onclick="customStore(<?=$param?>)" style="height:36px"><i class="fa fa-check"></i> Save</button>
											</div>
										</div>
									</div>
								</form>
							</div>
                            <!--  Work Instruction End -->
						</div>        
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
        

<?php $this->load->view("includes/footer"); ?>

<script>
$(document).ready(function(){
     var selectedTab = localStorage.getItem('projectSelectedTab');
	if (selectedTab != null) {
        $("#"+selectedTab).trigger('click');
    }

    $(document).on('click','.nav-link',function(){
        var id = $(this).attr('id');
        localStorage.setItem('projectSelectedTab', id);
    });

    var agencyTrans = {'postData':{'project_id':$("#addAgency #project_id").val()},'table_id':"agencyDetail",'tbody_id':'agencyBody','tfoot_id':'','fnget':'resAgencyHtml'};
    getTransHtml(agencyTrans);

    var specificData = {'postData':{'project_id':$("#addSpecification #project_id").val()},'table_id':"specificTable",'tbody_id':'specificBody','tfoot_id':'','fnget':'getSpecificationHtml'};
    getTransHtml(specificData);

    var planData = {'postData':{'project_id':$("#addWorkPlan #project_id").val()},'table_id':"planTable",'tbody_id':'planBody','tfoot_id':'','fnget':'getWorkPlanHtml'};
    getTransHtml(planData);

     var instructionData = {'postData':{'project_id':$("#addWorkInstruction #project_id").val()},'table_id':"instructionTable",'tbody_id':'instructionBody','tfoot_id':'','fnget':'getWorkInstructionHtml'};
    getTransHtml(instructionData);


});

function resAgencyHtml(data,formId ="addAgency"){ 
    if(data.status==1){ 
        $('#'+formId)[0].reset();
        $("#agency_name").val("");
        $("#agency_contact").val("");
        $("#remark").val("");
        $("#addAgency #id").val("");

        var postData = {'postData':{'project_id':$("#addAgency #project_id").val()},'table_id':"agencyDetail",'tbody_id':'agencyBody','tfoot_id':'','fnget':'resAgencyHtml'};
        getTransHtml(postData);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) {$("."+key).html(value);});
        }else{
            initTable();
            Swal.fire({ icon: 'error', title: data.message });	
        }
    }   
}


function getSpecificationHtml(data,formId){
    if(data.status==1){
        $('#'+formId)[0].reset();

        Swal.fire({ icon: 'success', title: data.message});

        var postData = {'postData':{'project_id':$("#addSpecification #project_id").val()},'table_id':"specificTable",'tbody_id':'specificBody','tfoot_id':'','fnget':'getSpecificationHtml'};
        getTransHtml(postData);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            Swal.fire({ icon: 'error', title: data.message });
        }			
    }
}

function editAgency(data) { 
    $.each(data, function (key, value) {
        $("#addAgency #" + key).val(value);
	});
}


function getWorkPlanHtml(data,formId){
    if(data.status==1){
        $('#'+formId)[0].reset();

        Swal.fire({ icon: 'success', title: data.message});

        var postData = {'postData':{'project_id':$("#addWorkPlan #project_id").val()},'table_id':"planTable",'tbody_id':'planBody','tfoot_id':'','fnget':'getWorkPlanHtml'};
        getTransHtml(postData);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            Swal.fire({ icon: 'error', title: data.message });
        }			
    }
}

 $(document).on('click', '.workInstruction', function() {
    if ($(this).attr('id') == "masterSelect") {
        if ($(this).prop('checked') == true) {
            $("input[name='wi_id[]']").prop('checked', true);
        } else {
            $("input[name='wi_id[]']").prop('checked', false);
        }
    } else {
        if ($("input[name='wi_id[]']").not(':checked').length != $("input[name='wi_id[]']").length) {
            $("#masterSelect").prop('checked', false);
        } else {                
        }
        if ($("input[name='wi_id[]']:checked").length == $("input[name='wi_id[]']").length) {
            $("#masterSelect").prop('checked', true);
        }
        else{$("#masterSelect").prop('checked', false);}
    }
});   

function getWorkInstructionHtml(data,formId){
    if(data.status==1){
        $('#'+formId)[0].reset();

        Swal.fire({ icon: 'success', title: data.message});

        var postData = {'postData':{'project_id':$("#addWorkInstruction #project_id").val()},'table_id':"instructionTable",'tbody_id':'instructionBody','tfoot_id':'','fnget':'getWorkInstructionHtml'};
        getTransHtml(postData);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            Swal.fire({ icon: 'error', title: data.message });
        }			
    }
}

</script>