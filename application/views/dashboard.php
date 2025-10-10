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

            <div class="col-lg-4">
                <div class="row">

                    <div class="col-12 col-lg-6 TOTREV hidden"> 
                        <div class="card bgl_green border border-light2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <i class="mdi mdi-currency-inr mdi-18px"></i><span class="h5 fw-bold" id="totalRevenue">24,500</span>      
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">Total Revenue</h6>                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 TOTEXP hidden"> 
                        <div class="card bgl_purple border border-light2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <i class="mdi mdi-currency-inr mdi-18px"></i><span class="h5 fw-bold" id="totalExpense">24,500</span>  
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">Total Expense</h6>                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 TODREV hidden"> 
                        <div class="card bgl_cream border border-light2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <i class="mdi mdi-currency-inr mdi-18px"></i><span class="h5 fw-bold" id="todayRevenue">24,500</span>  
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">TODAY'S REVENUE</h6>                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 ORDAV hidden">
                        <div class="card bgl_pink border border-light2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <i class="mdi mdi-currency-inr mdi-18px"></i><span class="h5 fw-bold" id="orderAvgValue">80</span>
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">ORDER AVG. VALUE</h6>                
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 TODORD hidden"> 
                        <div class="card bgl_sky border border-light2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col text-center">
                                        <span class="h5 fw-bold" id="todayOrders">500</span>      
                                        <h6 class="text-uppercase text-muted mt-2 m-0 font-11">TODAY'S ORDER</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 CONRATE hidden"> 
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

                    <div class="col-12 col-lg-6 OSREC hidden"> 
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

            <div class="col-lg-8">
                <div class="row INCEXP hidden">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">                      
                                        <h4 class="card-title">Income Vs Expense</h4>                      
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="">
                                    <div id="incomeVsExpense" class="apex-charts"></div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div> 
            </div>

        </div>

        <div class="row dashData">

            <div class="col-lg-4 T10SS hidden">
                <div class="card">
                    <div class="card-header status_bg3">
                        <div class="row align-items-center">
                            <div class="col">                      
                                <h4 class="card-title">Top 10 Selling States</h4>                  
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height:60vh; overflow-x: auto;" data-simplebar>                                    
                        <ul class="list-unsyled m-0 ps-0 transaction-history" id="topSellingStates">
                            <li class="align-items-center d-flex justify-content-between">
                                No Data Found.
                            </li>
                        </ul>                                    
                    </div>
                </div>
            </div>

            <div class="col-lg-4 T10SC hidden">
                <div class="card">
                    <div class="card-header status_bg7">
                        <div class="row align-items-center">
                            <div class="col">                      
                                <h4 class="card-title">Top 10 Selling Customer</h4>                  
                            </div>
                            <!-- <div class="col-auto">                      
                                <a href="" class="text-primary">View All</a>                 
                            </div> -->
                        </div>
                    </div>
                    <div class="card-body" style="height:60vh; overflow-x: auto;" data-simplebar>
                        <ul class="list-unsyled m-0 ps-0 transaction-history" id="topSellingCustomer">
                            <li class="align-items-center d-flex justify-content-between">
                                No Data Found.
                            </li>
                        </ul>                                
                    </div>
                </div>
            </div>  
            
            <div class="col-lg-4 T10SP hidden">
                <div class="card">
                    <div class="card-header status_bg4">
                        <div class="row align-items-center">
                            <div class="col">                      
                                <h4 class="card-title">Top 10 Selling Products</h4>                  
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height:60vh; overflow-x: auto;" data-simplebar>                                    
                        <ul class="list-unsyled m-0 ps-0 transaction-history" id="topSellingProducts">
                            <li class="align-items-center d-flex justify-content-between">
                                No Data Found.
                            </li>
                        </ul>                                    
                    </div>
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
    if(dashboardPermission.length > 0){
        $(".refreshBtn").removeClass("hidden");
        $("#noPermission").remove();
    }else{
        $(".refreshBtn").addClass("hidden");
        $(".dashData").html('<div class="col-md-12" id="noPermission"><div class="card"><div class="card-header fs-18 text-center">&#128522; ! WELCOME TO '+popupTitle+' ! &#128522;</div><div class="card-body text-center"><img src="'+base_url+'assets/images/logo.png" style="width:40%;height:auto;"></div></div></div>');
    }

    $.each(dashboardPermission,function(key,widgetClass){
        $("."+widgetClass).removeClass("hidden");
    });    

    loadDashboard();
});

function loadDashboard(){
    var cm_id = ($("#company_id :selected").val() || "");
    $("#totalRevenue").html(0);
    $("#totalExpense").html(0);
    $("#todayRevenue").html(0);
    $("#orderAvgValue").html(0);
    $("#todayOrders").html(0);
    $("#conversionRate").html(0);
    $("#outstandingReceiveble").html(0);
    $("#outstandingPayable").html(0);
    $("#topSellingStates").html("");
    $("#topSellingCustomer").html("");
    $("#topSellingProducts").html("");
    $("#productCategory").addClass("hidden");
    $('#productCategoryList').html("");

    /* Total Revenue */
    if($.inArray("TOTREV",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getRevenue',
            type : 'post',
            data : {from_date : '', to_date : '', cm_id : cm_id, vou_name_s : "'Sale','GInc'"},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#totalRevenue").html(response.totalRevenue);
        });
    }

    /* Total Expense */
    if($.inArray("TOTEXP",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getRevenue',
            type : 'post',
            data : {from_date : '', to_date : '', cm_id : cm_id, vou_name_s : "'Purc','GExp'"},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#totalExpense").html(response.totalRevenue);
        });
    }

    /* Today Revenue */
    if($.inArray("TODREV",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getRevenue',
            type : 'post',
            data : {from_date : todayDate, to_date : todayDate, cm_id : cm_id, vou_name_s : "'Sale','GInc'"},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#todayRevenue").html(response.totalRevenue);
        });
    }

    /* Order Avg. Value */
    if($.inArray("ORDAV",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getOrderAvgValue',
            type : 'post',
            data : {cm_id : cm_id, vou_name_s : "'SOrd'"},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#orderAvgValue").html(response.orderAvgValue);
        });
    }

    /* Today's Order */
    if($.inArray("TODORD",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getTodayOrder',
            type : 'post',
            data : {cm_id : cm_id, vou_name_s : "'SOrd'"},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#todayOrders").html(response.todayOrders);
        });
    }

    /* Conversion Rate */
    if($.inArray("CONRATE",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getConversionRate',
            type : 'post',
            data : {cm_id : cm_id},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#conversionRate").html(response.conversionRate);
        });
    }

    /* Outstanding */
    if($.inArray("OSREC",dashboardPermission) >= 0 || $.inArray("OSPAY",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getOutstanding',
            type : 'post',
            data : {cm_id : cm_id},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#outstandingReceiveble").html(response.receivable);
            $("#outstandingPayable").html(response.payable);
        });
    }

    /* Income Vs Expense */
    if($.inArray("INCEXP",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getIncomeVsExpense',
            type : 'post',
            data : {cm_id : cm_id},
            global:false,
            dataType : 'json'
        }).done(function(response){
            var options = {
                series: [
                    {
                        name : 'Income',
                        type: 'column',
                        data: response.income
                    }, 
                    {
                        name : 'Expense',
                        type: 'line',
                        data: response.expense
                    }
                ],
                chart: {
                    height: 320,
                    type: 'line',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '30%',
                    },
                },
                stroke: {
                    width: [1, 3],
                },

                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [0],
                    style: {
                        colors: ['rgba(255, 255, 255, .6)'],
                    },
                    background: {
                        enabled: true,
                        foreColor: '#b2bdcc',
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: '#000',
                        opacity: 1,
                    },
                    formatter: function(value, opts) {
                        return convertToShortNumber(value); // Use custom formatter
                    },
                },
                colors: ["#15ca20", "#fd3550"],
                xaxis: {
                    categories: response.monthList,
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            return convertToShortNumber(value); // Apply custom formatter for Y-axis labels
                        }
                    },
                    //title: {text: '₹ (Crores)'}
                },
                grid: {
                    row: {
                        colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 1,           
                    },
                    strokeDashArray: 2.5,
                },
            };
            var chartMain = new ApexCharts(document.querySelector("#incomeVsExpense"), options);
            chartMain.render();
        });
    }

    /* Top Selling State */
    if($.inArray("T10SS",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getTopSellingStateList',
            type : 'post',
            data : {cm_id : cm_id, vou_name_s : "'Sale'"},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#topSellingStates").html("");
            if(response.stateList != ""){
                $.each(response.stateList,function(key, row){
                    var li = $('<li>', { class: 'align-items-center d-flex justify-content-between' });

                    var mediaDiv = $('<div>', { class: 'media', style: 'width:70%;' });

                    var mediaBodyDiv = $('<div>', { class: 'media-body align-self-center ms-3' });

                    var transactionDataDiv = $('<div>', { class: 'transaction-data' });

                    var h5 = $('<h5>', { class: 'm-0 font-14', text: row.state_name });

                    transactionDataDiv.append(h5);
                    mediaBodyDiv.append(transactionDataDiv);
                    mediaDiv.append(mediaBodyDiv);
                    li.append(mediaDiv);

                    var span = $('<span>', { text: '₹ '+convertToShortNumber(parseFloat(row.amount).toFixed(2)) });
                    li.append(span);

                    $('#topSellingStates').append(li);
                });
            }else{
                var li = $('<li>', { class: 'align-items-center d-flex justify-content-between', text : 'No Data Found.' });
                $('#topSellingStates').append(li);
            }
        });
    }

    /* Top Selling Customers */
    if($.inArray("T10SC",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getTopSellingCustomerList',
            type : 'post',
            data : {cm_id : cm_id, vou_name_s : "'Sale'"},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#topSellingCustomer").html("");
            if(response.customerList != ""){
                $.each(response.customerList,function(key, row){
                    var li = $('<li>', { class: 'align-items-center d-flex justify-content-between' });

                    var mediaDiv = $('<div>', { class: 'media', style: 'width:70%;' });

                    var mediaBodyDiv = $('<div>', { class: 'media-body align-self-center ms-3' });

                    var transactionDataDiv = $('<div>', { class: 'transaction-data' });

                    var h5 = $('<h5>', { class: 'm-0 font-14', text: row.party_name });

                    transactionDataDiv.append(h5);
                    mediaBodyDiv.append(transactionDataDiv);
                    mediaDiv.append(mediaBodyDiv);
                    li.append(mediaDiv);

                    var span = $('<span>', { text: '₹ '+convertToShortNumber(parseFloat(row.amount).toFixed(2)) });
                    li.append(span);

                    $('#topSellingCustomer').append(li);
                });
            }else{
                var li = $('<li>', { class: 'align-items-center d-flex justify-content-between', text : 'No Data Found.' });
                $('#topSellingCustomer').append(li);
            }
        });
    }

    /* Top Selling Products */
    if($.inArray("T10SP",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getTopSellingProductList',
            type : 'post',
            data : {cm_id : cm_id, vou_name_s : "'Sale'"},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#topSellingProducts").html("");
            if(response.productList != ""){
                $.each(response.productList,function(key, row){
                    var li = $('<li>', { class: 'align-items-center d-flex justify-content-between' });

                    var mediaDiv = $('<div>', { class: 'media', style: 'width:70%;' });

                    var mediaBodyDiv = $('<div>', { class: 'media-body align-self-center ms-3' });

                    var transactionDataDiv = $('<div>', { class: 'transaction-data' });

                    var h5 = $('<h5>', { class: 'm-0 font-14', text: row.product_name });

                    transactionDataDiv.append(h5);
                    mediaBodyDiv.append(transactionDataDiv);
                    mediaDiv.append(mediaBodyDiv);
                    li.append(mediaDiv);

                    var span = $('<span>', { text: 'Qty. : '+convertToShortNumber(parseFloat(row.qty).toFixed(0)) });
                    li.append(span);

                    $('#topSellingProducts').append(li);
                });
            }else{
                var li = $('<li>', { class: 'align-items-center d-flex justify-content-between', text : 'No Data Found.' });
                $('#topSellingProducts').append(li);
            }
        });
    }

    /* Product Category Rate Difference */
    if($.inArray("ICSC",dashboardPermission) >= 0){
        $.ajax({
            url : base_url + controller + '/getProductCategoryList',
            type : 'post',
            data : {cm_id : cm_id, vou_name_s : "'Sale'"},
            global:false,
            dataType : 'json'
        }).done(function(response){
            $("#productCategory").addClass("hidden");
            $('#productCategoryList').html("");
            if(response.categoryList != ""){
                $.each(response.categoryList,function(key, row){                
                    var listItem = $('<li>', { class: 'list-inline-item', style : ' padding-right : 2px;' });
                    
                    var img = '';var arrow_img='up-arrow.png';

                    var amountSpan = $(' <span>', {
                        class: 'fw-semibold font-14',
                        text: ' ' + row.category_name + ' : ' + Math.abs(row.today_amount).toFixed(0)
                    });

                    var className = '', diffRate = '', arrowSign = '';
                    if(parseFloat(row.diff_per) > 0){
                        className = "text-success";
                        diffRate = ' (+'+parseFloat(Math.abs(row.diff_per)).toFixed(2)+'%)';
                        arrowSign = "up";arrow_img='up-arrow.png';
                    }else if(parseFloat(row.diff_per) < 0){
                        className = "text-danger";
                        diffRate = ' (-'+parseFloat(Math.abs(row.diff_per)).toFixed(2)+'%)';
                        arrowSign = "down";arrow_img='down-arrow.png';
                    }else{
                        className = "";
                        diffRate = ' (0%)';
                        arrowSign = "";
                    }
                    
                    var img = $('<img>', {
                        src: 'assets/images/small/'+arrow_img,
                        alt: '',
                        class: 'thumb-xs rounded'
                    });
                    
                    
                    var percentageSpan = $('<span>', {
                        class: 'mb-0 m-l-1 font-12 '+className
                    });
                    
                    var arrowIcon = $('<i>', { class: 'mdi mdi-arrow-'+arrowSign });
                    
                    listItem.append(img, amountSpan, percentageSpan.append(diffRate));
                    
                    $('#productCategoryList').append(listItem);
                });

                $("#productCategory").removeClass("hidden");
            }else{
                $("#productCategory").addClass("hidden");
            }
        });
    }
}

function convertToShortNumber(value) {
    if (value >= 10000000) {
        return (value / 10000000).toFixed(2) + ' Cr'; // Crores
    } else if (value >= 100000) {
        return (value / 100000).toFixed(2) + ' L'; // Lakhs
    } else if (value >= 1000) {
        return (value / 1000).toFixed(2) + ' K'; // Thousands
    } else {
        return value; // Less than 1000, no formatting
    }
}
</script>