<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row"> 
                            <div class="col-md-6">   
							</div>
                            <div class="col-md-3">   
                                <select name="project_id" id="project_id" class="form-control basic-select2">
                                    <option value="">Select Project</option>
                                    <?php   
										foreach($projectList as $row): 
											echo '<option value="'.$row->id.'">'.$row->project_name.'</option>';
										endforeach; 
                                    ?>
                                </select>
								<div class="error project_id"></div>
                            </div>     
                            <div class="col-md-3">  
                                <div class="input-group">
                                    <div class="input-group-append ">
                                        <button type="button" class="btn waves-effect waves-light btn-success float-right loadData"  title="Load Data">
									        <i class="fas fa-sync-alt"></i> Load
								        </button>
                                    </div>
                                </div>
                            </div>                  
                        </div>                                         
                    </div>
                     <div class="row ">  
                        <div class="col-lg-6">
                            <div class="card party_activity">
                                <div class="card-body projectDiv">
                                </div>
                            </div>
                        </div>                    
                        <div class="col-lg-6">
                            <div class="card party_activity">
                                <div class="card-body workLogDiv">
                                </div>
                            </div>
                        </div>
                    </div>

					<!-- <div class="row">              
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 text-sm-right">
                            <div class="widget widget-activity-five no-border party_activity">                             
                                    <div class="salesLogDiv m-2">
                                    </div>
                                </div>
                            </div>   
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 text-sm-right">                            
                            <div class="widget widget-activity-five no-border party_activity">                            
                                    <div class="activityDiv m-2">
                                    </div>
                                </div>
                            </div>    
                        </div> -->
                </div>
            </div>
        </div>        
    </div>
</div>


<?php $this->load->view('includes/footer'); ?>
<script>
$(document).ready(function() {
    
    $(document).on('click','.loadData',function(){
		$(".error").html("");
        var valid = 1;
        var project_id = $('#project_id').val();
        if($("#project_id").val() == 0){$(".project_id").html("Project is required.");valid=0;}

        if(valid){
            $.ajax({
            url: base_url + controller + '/getProjectTrackingData',
            data: { project_id:project_id },
            type: "POST",
            dataType:'json',
                success:function(data){
                    $(".projectDiv").html("");
                    $(".workLogDiv").html("");
                    $(".projectDiv").html(data.html);
                    $(".workLogDiv").html(data.html2);
                }
            });
        }
	});
    
    // $('.party_activity').each((index, element) => {
    //     new PerfectScrollbar(element);
    // });

});
</script>