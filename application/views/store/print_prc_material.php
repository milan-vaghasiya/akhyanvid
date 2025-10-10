<div class="row">
	<div class="col-12">
		<table class="table"><tr><td class="fs-18 text-center" style="letter-spacing: 2px;font-weight:bold;padding:0px !important;">PRC REQ MATERIAL</td></tr></table>
		
		<table class="table item-list-bb" style="margin-top:5px;">
			<tr class="text-left">
				<th style="width:15%" class="bg-light">PRC No.</th>
				<td style="width:40%"><?=(!empty($prcData->prc_number) ? $prcData->prc_number : '')?></td>
				<th style="width:20%" class="bg-light">PRC Date</th>
				<td style="width:25%"><?=(!empty($prcData->prc_date) ? formatDate($prcData->prc_date) : '')?></td>
			</tr>
		    <tr class="text-left">
				<th class="bg-light">Product</th>
				<td><?=(!empty($prcData->item_code) ? '['.$prcData->item_code .'] ' : '').$prcData->item_name ?></td>
				<th class="bg-light">PRC Qty</th>
				<td><?=(!empty($prcData->prc_qty) ? floatval($prcData->prc_qty) : '').' '.$prcData->uom?></td>
			</tr>			
			<tr class="text-left">
				<th class="bg-light">SO No </th>
				<td><?=(!empty($prcData->so_number) ? $prcData->so_number : '')?></td>
				<th class="bg-light">Cust. PO No.</th>
				<td><?=(!empty($prcData->doc_no) ? $prcData->doc_no : '')?></td>
			</tr>
            <tr class="text-left">
				<th class="bg-light">Remark</th>
				<td colspan="3"><?=(!empty($prcData->remark) ? $prcData->remark : '')?></td>
			</tr>			
		</table>

		<h4>Material Detail:</h4>
        
		<table class="table item-list-bb">
			<tr class="thead-gray">
				<th style="width:40%;">Item</th>
				<th style="width:20%;">Required Qty</th>
				<th style="width:20%;">Issued Qty</th>
				<th style="width:20%;">Pending Qty</th>
			</tr>
			<?php            
            if(!empty($bomData)){
                foreach($bomData As $row){
                    $prcRq = ($row->ppc_qty * $row->prc_qty);
                    $prcIq = (!empty($row->issue_qty) ? $row->issue_qty : 0);
                    $prcPq = ($prcRq - $prcIq);

                    echo '<tr class="text-center">
                        <td>'.(!empty($row->item_code) ? $row->item_code.' ' : '').$row->item_name.'</td>
                        <td>'.(!empty($prcRq) ? floatval($prcRq) : '').' '.$row->uom.'</td>
                        <td>'.(!empty($prcIq) ? floatval($prcIq) : '').' '.$row->uom.'</td>
                        <td>'.(!empty($prcPq) ? floatval($prcPq) : '').' '.$row->uom.'</td>
                    </tr>';
                }
            }else{
                echo '<tr><th class="text-center" colspan="4">Record Not Found !</th></tr>';
            }
			?>
		</table>
				
	</div>
</div>  