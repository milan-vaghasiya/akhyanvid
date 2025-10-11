<?php $this->load->view('includes/header'); ?>

<div class="page-content-tab">
    <div class="container-fluid" style="padding:0px 10px; margin-bottom:5%;">
        
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-end">
                        <button type="button" class="btn btn-info waves-effect waves-light refreshBtn" onclick="loadDashboard();"><i class="fas fa-sync-alt"></i> Refresh</button>
                    </div>
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-12 col-lg-4 PENQ"> 
                        <div class="card bgl_green border border-light2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <span class="h5 fw-bold" id="totalPendingQuotation">0</span>      
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">Pending Quotation</h6>                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 ONGO"> 
                        <div class="card bgl_purple border border-light2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <span class="h5 fw-bold" id="totalOnGoingProjects">0</span>  
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">On Going Projects</h6>                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 PENS"> 
                        <div class="card bgl_cream border border-light2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <span class="h5 fw-bold" id="totalPendingServices">0</span>  
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">Pending Services</h6>                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 PENC">
                        <div class="card bgl_pink border border-light2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <span class="h5 fw-bold" id="totalPendingComplaint">0</span>
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">Pending Complaint</h6>                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 PENT"> 
                        <div class="card bgl_sky border border-light2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <span class="h5 fw-bold" id="totalPendingTask">0</span>      
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">Pending Task</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4"> 
                        <div class="card">
                            <div class="card-body border border-light2">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <i class="mdi mdi-currency-inr mdi-18px"></i><span class="h5 fw-bold" >0</span>  
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">--</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3 CONRATE hidden"> 
                        <div class="card bgl_orange border border-light2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <span class="h5 fw-bold" id="conversionRate">80</span> <b>%</b>
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">CONVERSION RATE</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    

                    <div class="col-12 col-lg-3 OSREC hidden"> 
                        <div class="card">
                            <div class="card-body border border-light2">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <i class="mdi mdi-currency-inr mdi-18px"></i><span class="h5 fw-bold" id="outstandingReceiveble">80</span>  
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">Outstanding Receivable</h6>                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 OSPAY hidden"> 
                        <div class="card">
                            <div class="card-body border border-light2">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <i class="mdi mdi-currency-inr mdi-18px"></i><span class="h5 fw-bold" id="outstandingPayable">80</span>  
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">Outstanding Payable</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="dashData">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ICSC hidden bgl_purple border border-light2 p-7" id="productCategory" style="position: fixed; bottom: 0px; z-index: 999; width:100%;border-right:0px;">
    <div class="text-slider">
        <ul class="list-inline move-text mb-0" id="productCategoryList"></ul>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>

<!-- Javascript  -->   
<script src="<?=base_url()?>assets/plugins/chartjs/chart.js"></script>
<script src="<?=base_url()?>assets/plugins/lightpicker/litepicker.js"></script>
<script src="<?=base_url()?>assets/plugins/apexcharts/apexcharts.min.js"></script>
<!-- <script src="<?=base_url()?>assets/pages/analytics-index.init.js"></script> -->

<script>
var todayDate = '<?=getFyDate()?>';
var dashboardPermission = '<?=$dashboardPermission?>';
dashboardPermission = (dashboardPermission != "")?dashboardPermission.split(","):[];
</script>

<script>
$(document).ready(function(){
    // if(dashboardPermission.length > 0){
    //     $(".refreshBtn").removeClass("hidden");
    //     $("#noPermission").remove();
    // }else{
    //     $(".refreshBtn").addClass("hidden");
        $(".dashData").html('<div class="col-md-12" id="noPermission"><div class="card"><div class="card-header fs-18 text-center">&#128522; ! WELCOME TO '+popupTitle+' ! &#128522;</div><div class="card-body text-center"><img src="'+base_url+'assets/images/logo.png" style="width:40%;height:auto;"></div></div></div>');
    // }

    $.each(dashboardPermission,function(key,widgetClass){
        $("."+widgetClass).removeClass("hidden");
    });    

    loadDashboard();
});

function loadDashboard(){
    var cm_id = ($("#company_id :selected").val() || "");
    $("#totalPendingQuotation").html(0);
    $("#totalOnGoingProjects").html(0);
    $("#totalPendingServices").html(0);
    $("#totalPendingComplaint").html(0);
    $("#totalPendingTask").html(0);

    /* Total Pending Quotation */
    if($.inArray("PENQ",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getPendingQuotation',
            type : 'post',
            data : {},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#totalPendingQuotation").html(response.totalPendingQuotation);
        });
    }

    /* Total On Going Projects */
    if($.inArray("ONGO",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getOnGoingProjects',
            type : 'post',
            data : {},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#totalOnGoingProjects").html(response.totalOnGoingProjects);
        });
    }

    /* Total Pending Service */
    if($.inArray("PENS",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getPendingServices',
            type : 'post',
            data : {},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#totalPendingServices").html(response.totalPendingServices);
        });
    }

    /* Total Pending Complaint */
    if($.inArray("PENC",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getPendingComplaint',
            type : 'post',
            data : {},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#totalPendingComplaint").html(response.totalPendingComplaint);
        });
    }

    /* Total Pending Task */
    if($.inArray("PENT",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getPendingTask',
            type : 'post',
            data : {},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#totalPendingTask").html(response.totalPendingTask);
        });
    }
}
</script>