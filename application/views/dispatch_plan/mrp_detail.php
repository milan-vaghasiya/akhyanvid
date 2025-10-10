<div class="table-responsive">
    <table class="table  table-bordered" id="itemTable">
        <thead class="thead-info">
            <tr>
                <th>Sr.</th>
                <th>Item</th>
                <th>Required Qty</th>
                <th>WIP Qty</th>
                <th>Stock Qty</th>
                <th>Pending Request</th>
                <th>Pending PO</th>
                <th>Pending GRN QC</th>
                <th>Shortage Qty</th>
            </tr>
        </thead>
        <tbody id="itemTbody">
            <?php
            if(!empty($bomData)){
                $i=1;
                foreach($bomData As $row){
                    $required_qty = $row->qty*$qty;
                    
    
                    $sort_qty = ($required_qty - ($row->wip_qty+$row->stock_qty+$row->pending_req + $row->pending_po + $row->pending_grn));
                    $sortage_qty = (($sort_qty>0)?$sort_qty:0);
                    
                    echo '<tr>
                        <td>'.$i++.'</td>
                        <td><a href="javascript:void(0)" class="itemDetail" data-item_id = "'.$row->item_id.'" data-item_type = "'.$row->item_type.'"   data-item_name = "'.(((!empty($row->item_code))?$row->item_code.' ':'').$row->item_name).'">'.((!empty($row->item_code))?$row->item_code.' ':'').$row->item_name.' </td>
                        <td class="text-center">'.$required_qty.' '.$row->uom.'</td>
                        <td class="text-center">'.floatval($row->wip_qty).' '.$row->uom.'</td>
                        <td class="text-center">'.floatval($row->stock_qty).' '.$row->uom.'</td>
                        <td class="text-center">'.floatval($row->pending_req).' '.$row->uom.'</td>
                        <td class="text-center">'.floatval($row->pending_po).' '.$row->uom.'</td>
                        <td class="text-center">'.floatval($row->pending_grn).' '.$row->uom.'</td>
                        <td class="text-center">'.floatval($sortage_qty).' '.$row->uom.'</td>
                    </tr>';
                }
            }else{
                echo  '<tr><th colspan="4" class="text-center">No data available.</th></tr>';
            }
            
            ?>
        </tbody>
    </table>
</div>