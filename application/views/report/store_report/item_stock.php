<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title pageHeader"><?=$pageHeader?></h4>
                        </div>       
                        <div class="col-md-6 float-right">  
                            <div class="input-group">
                                <div class="input-group-append" style="width:40%;">
                                    <select id="item_id" class="form-control basic-select2">
                                        <option value="">All Item</option>
                                        <?php
                                        if (!empty($itemList)) :
                                            foreach ($itemList as $row) :
                                                echo '<option value="'.$row->id.'">'.(!empty($row->item_code) ? '[ '.$row->item_code.' ] ' : '').$row->item_name.'</option>';
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>
                                <div class="input-group-append">
                                    <button type="button" class="btn waves-effect waves-light btn-success refreshReportData loadData" title="Load Data">
                                        <i class="fas fa-sync-alt"></i> Load
                                    </button>
                                </div>
                            </div>
                        </div>                  
                    </div>                                         
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card"> 
                            <div class="card-body reportDiv" style="min-height:75vh">
                                <div class="table-responsive">
                                    <table id='reportTable' class="table table-bordered">
                                        <thead class="thead-dark" id="theadData">
                                            <tr class="text-center">
                                                <th colspan="4">Stock Register</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-left">Item Description</th>
                                                <th class="text-center">Unit</th>
                                                <th class="text-right">Balance Qty.</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyData"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<?php $this->load->view('includes/footer'); ?>

<script>
$(document).ready(function(){
	reportTable();
    setTimeout(function() { $(".loadData").trigger('click'); }, 500);
    
    $(document).on('click','.loadData',function(e){
		e.stopImmediatePropagation();e.preventDefault();
		var item_id = $('#item_id').val();

        $.ajax({
            url: base_url + controller + '/getStockRegisterData',
            data: { item_id:item_id },
            type: "POST",
            dataType:'json',
            success:function(data){
                $("#reportTable").DataTable().clear().destroy();
                $("#tbodyData").html(data.tbody);
                reportTable();
            }
        });
    });  
});
</script>