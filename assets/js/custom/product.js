$(document).ready(function(){
    var item_id = $("#item_id").val();
    $('#group_name').typeahead({
		source: function(query, result)
		{
			$.ajax({
				url:base_url + 'productOption/groupSearch',
				method:"POST",
				global:false,
				data:{query:query, item_id:item_id},
				dataType:"json",
				success:function(data){
                    result($.map(data, function(group){return group;}));                    
                }
			});
		}
	});	 
});

function addProcess(data){
	var call_function = data.call_function;
	if(call_function == "" || call_function == null){call_function="edit";}

	var fnsave = data.fnsave;
	if(fnsave == "" || fnsave == null){fnsave="save";}

	var controllerName = data.controller;
	if(controllerName == "" || controllerName == null){controllerName=controller;}	

	$.ajax({ 
		type: "POST",   
		url: base_url + controllerName + '/' + call_function,   
		data: data.postData,
	}).done(function(response){
		initModal(data,response);

        $("#itemProcess tbody").sortable({
            items: 'tr',
            cursor: 'pointer',
            axis: 'y',
            dropOnEmpty: false,
            helper: fixWidthHelper,
            start: function (e, ui) {
                ui.item.addClass("selected");
            },
            stop: function (e, ui) {
                ui.item.removeClass("selected");
                $(this).find("tr").each(function (index) {
                    $(this).find("td").eq(2).html(index+1);
                });
            },
            update: function () 
            {
                var ids='';
                $(this).find("tr").each(function (index) {ids += $(this).attr("id")+",";});
                var lastChar = ids.slice(-1);
                if (lastChar == ',') {ids = ids.slice(0, -1);}
                
                $.ajax({
                    url: base_url + controller + '/updateProductProcessSequance',
                    type:'post',
                    data:{id:ids},
                    dataType:'json',
                    global:false,
                    success:function(data){}
                });
            }
        });  
        
	});
}

function fixWidthHelper(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
}