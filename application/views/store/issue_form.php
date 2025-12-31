<form>
    <div class="col-md-12">
        <div class="row">
            <div class="row">
                <div class="col-md-12 form-group">													
                    <label><i class="fa fa-qrcode text-primary"></i> SCAN QR CODE</label>
                    
                    <input type="text" id="scan_qr_item" value="" class="form-control"  style="background:#93d2ff;color:#000000;font-weight:bold;" placeholder="SCAN QR CODE" autocomplete="off">
                </div>
                
                <div class="col-md-3 form-group">
                    <label for="challan_no">Issue No.</label>
                    <div class="input-group">
                        <input type="text" name="issue_number" class="form-control" value="<?= $issue_number ?>" readOnly />
                        <input type="hidden" name="issue_no" value="<?= $issue_no ?>" readOnly />
                        <input type="hidden" name="id" value="" />
                    </div>
                </div>

                <div class="col-md-3 form-group">
                    <label for="issue_date">Issue Date</label>
                    <input type="date" name="issue_date" id="issue_date" class="form-control" max="<?=date('Y-m-d')?>" value="<?=date("Y-m-d")?>">
                </div>

                <div class="col-md-6 form-group">
                    <label for="project_id">Project</label>
                    <select name="project_id" id="project_id" class="form-control basic-select2 req">
                        <option value="">Select Project</option>
                        <?php
                            if(!empty($projectList)){
                                foreach ($projectList as $row) {
									echo "<option value='".$row->id."'  >".$row->project_name."</option>";
                                }
							}                            
                        ?>
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label for="product_type">Product Type</label>
                    <select name="product_type" id="product_type" class="form-control basic-select2">
                        <option value="">Select Product Type</option>
                        <option value="1">Quote Wise</option>
                        <option value="2">Other</option>
                    </select>
                </div>

                <div class="col-md-8 form-group">
                    <label for="item_id">Product</label>
                    <div class="float-right"><a class="text-primary font-bold " href="javascript:void(0)" id="stock_qty">Stock</a></div>
                    <select name="item_id" id="item_id" class="form-control basic-select2 req getStock">
                        <option value="">Select Item Name</option>
                    </select>
                    <div class="error item_err"></div>
                </div>

                <div class="col-md-4 form-group">
                    <label for="batch_no">Serial No</label>
                    <select id="batch_no" name="batch_no" class="form-control select2 req">
                        <option value="">Select Serial No</option>
                        <?php echo (!empty($batchNo)? $batchNo :'')?>
                    </select>
                </div> 

                <div class="col-md-4 form-group">
                    <label for="issue_qty">Issue Qty</label>
                    <input type="text" name="issue_qty" id="issue_qty" class="form-control floatOnly req" >
                </div>

				<div class="col-md-4 form-group">
                    <label for="issued_to">Issued To</label>
                    <select name="issued_to" id="issued_to" class="form-control basic-select2">
                        <option value="">Select Issued To</option>
                        <?php
                            if(!empty($empData)){
                                foreach ($empData as $row) {
                                    echo "<option value='".$row->id."'>".$row->emp_name."</option>";
                                }
                            }
                        ?>
                    </select>
                    <div class="error issued_to"></div>
                </div>

                <div class="col-md-12 form-group">
                    <label for="remark">Remark</label>
                    <textarea name="remark" id="remark" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){
        setTimeout(function(){ $('#scan_qr_item').focus(); }, 1000);

        /** LOAD SCANNED(QR) ITEM ON ENTER KEY */
        $(document).on('keypress','#scan_qr_item',function(e){ 
            if(e.which == 13) {
                $("#tbodyData").html("");
                var scan_id = $("#scan_qr_item").val();
                var project_id = $("#project_id").val();
                var product_type = $("#product_type").val();
                
                $(".error").html("");
                if(scan_id){
                    $.ajax({
                        type: "POST",
                        url: base_url + 'store/getScanedItemStock',
                        data:{scan_id:scan_id,project_id:project_id,product_type:product_type},
                        dataType:'json'
                    }).done(function (response) {
                        if(response.status == 1){  
                            $(".error").html("");
                            setTimeout(() => {
                                $("#item_id").val(response.item_id).trigger('change');
                                $("#item_id").select2();
                            }, 400);

                            setTimeout(() => {
                                $("#batch_no").val(response.batch_no).trigger('change');
                                $("#batch_no").select2();
                            }, 1000);
                        }else{
                            $(".error").html("");
                            $.each( response.message, function( key, value ) {$("."+key).html(value);});
                        }                        
                    })
                    $('#scan_qr_item').val('');
                }
            }
        });  

        $(document).on('change', '#product_type', function (e) {
            e.stopImmediatePropagation();e.preventDefault();

            var product_type = $("#product_type").val();
            var project_id = $("#project_id").val();

            if(product_type !== "" && project_id !== ""){
                $.ajax({
                    url: base_url + controller + "/getItemListDetail",
                    type: 'post',
                    data: { product_type: product_type, project_id:project_id},
                    dataType: 'json',
                    success: function(data) {
                        $("#item_id").html(data.options);
                        initSelect2();
                    }
                });
            }else{
                $("#item_id").html("");
            }
        });

        $(document).on('change', '#project_id', function () {
            $("#item_id").html("");  
            $("#product_type").val("").trigger('change');
        });

        $(document).on('change', '.getStock', function (e) {
            e.stopImmediatePropagation();
            e.preventDefault();

            var item_id = $("#item_id").val();

            $("#issue_qty").val(""); 

            if (item_id != '') {
                $.ajax({
                    url: base_url + controller + "/getItemStock",
                    type: 'post',
                    data: { item_id: item_id },
                    dataType: 'json',
                    success: function(data) {
                        $("#stock_qty").html('Stock : ' + data.stock_qty);
                        $("#batch_no").html(data.batchNo);
                    }
                });
            }
        });
    });
</script>