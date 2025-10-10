$(document).ready(function(){

    var bulkPrintBtn = $("<button>Bulk Print</button>");
    bulkPrintBtn.attr("type", "button");
	bulkPrintBtn.attr("id", "bulk-print-btn");
	bulkPrintBtn.attr("class", "btn btn-outline-dark hidden");
    $(".dt-buttons").append(bulkPrintBtn);

    $(document).on('click','.nav-tab',function(){
        $(".dt-buttons").append(bulkPrintBtn);
    });

    $(document).on('click keyup','.paginate_button, .nav-tab, .nav-tab-refresh, .buttons-page-length, .columnSearchInput, #salesInvoiceTable_filter input[type="search"]',function(){
        $("#bulk-action, .row-action-check").prop('checked',false);
        $("#bulk-print-btn").addClass("hidden");
    });

    $(document).on('click','.bulk-action-check, .row-action-check',function(){       
        //When Check Master Check Box then Sub Check Box Checked
        if($(this).attr('id') == "bulk-action"){
            if($(this).prop('checked') == true){
                $(".row-action-check").prop('checked',true);
            }else{
                $(".row-action-check").prop('checked',false);
            }
        }

        //When All Sub Check Box Checked then master check box autho checked
        if($(this).hasClass('row-action-check') == true){
            if($(".row-action-check").length == $(".row-action-check:checked").length){
                $(".bulk-action-check").prop('checked',true);
            }else{
                $(".bulk-action-check").prop('checked',false);
            }
        } 
        
        //Bulk Print button hide show
        if($(".row-action-check:checked").length > 0){
            var ids = $(".row-action-check:checked").map(function () { return $(this).val(); }).get();
            $("#bulk-print-btn").removeClass("hidden");
        }else{
            $("#bulk-print-btn").addClass("hidden");
        }

    });

    $(document).on('click','#bulk-print-btn',function(){
        var ids = $(".row-action-check:checked").map(function () { return $(this).val(); }).get();
        var postData = {ids : ids};
        var url = base_url + controller + '/bulkPrint/' + encodeURIComponent(window.btoa(JSON.stringify(postData)));
		window.open(url);
    });
});