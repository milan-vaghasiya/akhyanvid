<form data-res_function="getBomResponse">
    <input type="hidden" id="prc_id" name="prc_id" value="<?=$prc_id?>">
    <input type="hidden" id="prc_qty" name="prc_qty" value="<?=$prc_qty?>">
    <div class="error general_error"></div>
    <table id='bomTable' class="table table-bordered jpExcelTable mb-5">  
        <thead class="text-center">
            <tr>
                <th style="min-width:20px">#</th>
                <th style="min-width:100px">Item</th>
            </tr>
        </thead>
        <tbody id="bomTbodyData" class="text-center">
            <?php
            if(!empty($kitData)){
                $groupedkit = array_reduce($kitData, function($group, $kit) { $group[$kit->ref_item_id][] = $kit;  return $group; }, []);
               
                $i=1;
                foreach ($groupedkit as $group => $kitArray){
                    $options = '';
                    $bomkey = !empty($prcBom)?array_search($group,array_column($prcBom,'bom_group')):'';
                    $item_id = ""; $ppc_qty = ""; $process_id ="";$id="";$multi_heat = ""; $production_qty = 0;$item_name="";
                    if(!empty($prcBom)){
                        $item_id = $prcBom[$bomkey]->item_id; 
                        $item_name = $prcBom[$bomkey]->item_name; 
                        $ppc_qty = $prcBom[$bomkey]->ppc_qty; 
                        $process_id =$prcBom[$bomkey]->process_id;
                        $multi_heat =$prcBom[$bomkey]->multi_heat;
                        $production_qty =$prcBom[$bomkey]->production_qty;
                        $id=$prcBom[$bomkey]->id;
                    }
                    $selected = ((!empty($item_id) && $item_id == $kitArray[0]->ref_item_id)?'selected':'');
                    $options .= '<option value="'.$kitArray[0]->ref_item_id.'" data-bom_qty="'.$kitArray[0]->qty.'" data-process_id="'.$kitArray[0]->process_id.'" data-row_id="'.$i.'" '. $selected.'>'.$kitArray[0]->item_name.' [BOM Qty: '.$kitArray[0]->qty.']</option>';

                    foreach ($kitArray as $row){
                        if($row->alt_ref_item > 0){
                            $selected = ((!empty($item_id) && $item_id == $row->alt_ref_item)?'selected':'');
                            $options .= '<option value="'.$row->alt_ref_item.'" data-bom_qty="'.$row->alt_qty.'" data-process_id="'.$row->process_id.'" data-row_id="'.$i.'" '. $selected.'>'.$row->alt_item_name.' [BOM Qty: '.$row->alt_qty.']</option>';
                        }
                    }
                    
                    if($production_qty  == 0){
                        ?>
                         <tr>
                            <td><?=$i?></td>
                            <td>
                                <input type="hidden" name="id[]" id="id<?=$i?>" value="<?=$id?>">
                                <input type="hidden" name="bom_group[]" id="bom_group<?=$i?>" value="<?=$group?>">
                                <input type="hidden" name="ppc_qty[]" id="ppc_qty<?=$i?>" value="<?=!empty($ppc_qty)?$ppc_qty:$kitArray[0]->qty?>">
                                <input type="hidden" name="process_id[]" id="process_id<?=$i?>" value="<?=!empty($process_id)?$process_id:$kitArray[0]->process_id?>">
                                <input type="hidden" name="multi_heat[]" id="multi_heat<?=$i?>" value="Yes">
                                <select name="item_id[]" id="item_id<?=$i?>" class="form-control select2 itemChange">
                                    <?=$options?>
                                </select>
                            </td>
                        </tr>
                        <?php
                    }else{
                        ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$item_name?></td>
                        </tr>
                        <?php
                    }
                    $i++;
                }
            }else{
                ?>
                <th colspan="3" class="text-center">No data available.</th>
                <?php
            }
            ?>
        </tbody>
    </table>
</form>

<script>
    $(document).ready(function(){
        $(document).on('change','.itemChange',function() {
            var row_id = $(this).find(":selected").data('row_id');
            var bom_qty = $(this).find(":selected").data('bom_qty');
            var process_id = $(this).find(":selected").data('process_id');
            $("#ppc_qty"+row_id).val(bom_qty);
            $("#process_id"+row_id).val(process_id);
        });
    });
    
    function getBomResponse(data,formId="prcMaterial"){ 
        if(data.status==1){
            $('#'+formId)[0].reset();
            var postData = {'prc_id':$("#prc_id").val()};closeModal(formId);
            Swal.fire({
                title: "Success",
                text: data.message,
                icon: "success",
                showCancelButton: false,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ok!"
            }).then((result) => {
                loadProcessDetail(postData);
            });
            
        }else{
            if(typeof data.message === "object"){
                $(".error").html("");
                $.each( data.message, function( key, value ) {$("."+key).html(value);});
            }else{
                Swal.fire({ icon: 'error', title: data.message });
            }			
        }
    }
</script>