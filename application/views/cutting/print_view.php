<style>table, .table{width:100% !important;border-collapse:collapse !important;}td,th{border-collapse:collapse !important;}</style>
<div class="row">
	<div class="col-12">
		<table class="table" style="border-bottom:1px solid #000000;margin-top: 25px; !important;">
			<tr>
			    <td style="width:20%;"></td>
				<td class="text-uppercase text-center" style="font-size:1.3rem;font-weight:bold;width:40%;">Cutting Job</td>
				<td class="text-uppercase text-right" style="font-size:1.3rem;font-weight:bold;width:20%;"></td>
			</tr>
		</table>
		<table class="table  item-list-bb " style="margin-top: 25px; !important;">
			<tr class="text-center bg-light">
				<th style="font-size:0rem !important;width:25%">PRC No.</th>
				<th style="font-size:0rem !important;width:25%">PRC Quantity</th>
				<th style="font-size:0rem !important;width:25%">PRC Date</th>
				<th style="font-size:0rem !important;width:25%">Cut Weight</th>
			</tr>
            <tr class="text-center" >
				<td style="font-size:0rem !important;"><?= $prcData->prc_number ?></td>
				<td style="font-size:0rem !important;"><?= floatval($prcData->prc_qty) ?></td>
				<td style="font-size:0rem !important;"><?= formatDate($prcData->prc_date) ?></td>
				<td style="font-size:0rem !important;"><?= floatval($prcData->cut_weight) ?></td>
			</tr>
            <tr class="text-left ">
                <th class=" bg-light" style="font-size:0rem !important;">Product Name</th>
                <td colspan="3" style="font-size:0rem !important;"><?= $prcData->item_name ?></td>
            </tr>
            <tr class="text-left ">
                <th class=" bg-light" style="font-size:0rem !important;">Customer</th>
                <td colspan="3" style="font-size:0rem !important;"><?=$prcData->party_name?></td>
            </tr>
           <tr  class="text-left ">
                <th class=" bg-light" style="font-size:0rem !important;">So No.</th>
                <td style="font-size:0rem !important;"><?=$prcData->so_number?></td>
                <th class=" bg-light" style="font-size:0rem !important;">Po No</th>
                <td style="font-size:0rem !important;"><?=$prcData->doc_no?></td>
           </tr>
           <tr  class="text-left ">
                <th class=" bg-light" style="font-size:0rem !important;">Cutting Length</th>
                <td style="font-size:0rem !important;"><?=floatval($prcData->cutting_length)?></td>
                <th class=" bg-light" style="font-size:0rem !important;">Cutting Dia. </th>
                <td style="font-size:0rem !important;"><?=floatval($prcData->cutting_dia)?></td>
           </tr>
            <tr class="text-left">
				<th class=" bg-light" style="font-size:0rem !important;">Remark</th>
				<td colspan="2" style="font-size:0rem !important;"><?= $prcData->remark ?></td>
			</tr>
		</table>
		<h2 class="row-title"  style="margin-top: 25px; !important;">Material Detail:</h2>
		<table class="table item-list-bb">
			<tr class="thead-gray">
				<th style="font-size:0rem !important;"> Item Description</th>
				<th class="text-center  fs-35" style="width:15%;font-size:0rem !important;">Issued Qty</th>
			</tr>
			<?php
			if (!empty($prcMaterialData)) :
                $i = 1;
				foreach ($prcMaterialData as $row) :
                    echo '<tr>';
                    echo '<td style="font-size:0rem !important;">' . $row->item_name . '</td>';
                    echo '<td class="text-center" style="font-size:0rem !important;">' . floatVal(abs($row->issue_qty)) . '</td>';
                    echo '</tr>';
				endforeach;
			else :
				echo '<tr><th class="text-center" colspan="2" style="font-size:0rem !important;">Record Not Found !</th></tr>';
			endif;
			?>
		</table>
		
	</div>
</div>