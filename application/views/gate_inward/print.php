<html>
    <head>
        <title>GRN</title>
        <!-- <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url();?>assets/images/favicon.png">/ -->
    </head>
    <body>
        <div class="row">
            <div class="col-12">
				<table>
					<tr>
						<td>
							<img src="<?=$letter_head?>" class="img">
						</td>
					</tr>
				</table>

				<table class="table bg-light-grey">
					<tr class="" style="letter-spacing: 2px;font-weight:bold;padding:2px !important; border-bottom:1px solid #000000;">
						<td style="width:33%;" class="fs-18 text-left">
							GSTIN: <?=$companyData->company_gst_no?>
						</td>
						<td style="width:33%;" class="fs-18 text-center">Gate Receipt Note</td>
						<td style="width:33%;" class="fs-18 text-right"></td>
					</tr>
				</table>               
                
                <table class="table item-list-bb fs-22" style="margin-top:5px;">
                    <tr >
                        <td rowspan="4" style="width:67%;vertical-align:top;">
                            <b>M/S. <?=$dataRow->party_name?></b><br>
                            <?=(!empty($dataRow->delivery_address) ? $dataRow->delivery_address ." - ".$dataRow->delivery_pincode : $partyData->party_address ." - ".$partyData->party_pincode)?><br>
                            <b>Kind. Attn. : <?=$dataRow->contact_person?></b> <br>
                            Contact No. : <?=$dataRow->party_mobile?><br>
                            Email : <?=$partyData->party_email?><br><br>
                            GSTIN : <?=$dataRow->gstin?>
                        </td>
                        <td>
                            <b>GI No.</b>
                        </td>
                        <td>
                            <?=$dataRow->trans_number?>
                        </td>
                    </tr>
                    <tr>
				        <th class="text-left">GI Date</th>
                        <td><?=formatDate($dataRow->trans_date)?></td>
                    </tr>
                    <tr>
                        <th class="text-left">CH/Inv. No.</th>
                        <td><?=$dataRow->inv_no?></td>
                    </tr>
                    <tr>
                        <th class="text-left">CH/Inv. Date</th>
                        <td><?=(!empty($dataRow->inv_date)) ? formatDate($dataRow->inv_date) : ""?></td>
                    </tr>
                </table>
                
                <table class="table item-list-bb" style="margin-top:10px;">
					<thead>
						<tr>
							<th style="width:30px;">No.</th>
							<th class="text-left">Item Description</th>
							<th style="width:80px;">PO No.</th>
							<th style="width:120px;">Location</th>
							<th style="width:80px;">Qty</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i=1;$totalQty = 0;
							if(!empty($dataRow->itemData)):
								foreach($dataRow->itemData as $row):
									echo '<tr>';
										echo '<td class="text-center">'.$i++.'</td>';
										echo '<td>'.$row->item_name.'</td>';
										echo '<td class="text-center">'.$row->po_number.'</td>';
                                        echo '<td class="text-center">'.$row->location_name.'</td>';
										echo '<td class="text-right">'.sprintf('%0.2f',$row->qty).' '.$row->uom.'</td>'; 
									$totalQty += $row->qty;
								endforeach;
							endif;
						?>
						<tr>
							<th colspan="4" class="text-right">Total Qty.</th>
							<th class="text-right"><?=sprintf('%.3f',$totalQty)?></th>
						</tr>
					</tbody>
                </table>
                
				<htmlpagefooter name="lastpage">
					<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
						<tr>
							<td style="width:50%;" rowspan="4"></td>
							<th colspan="2">For, <?=$companyData->company_name?></th>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"><?=$dataRow->prepareBy?></td>
							<td style="width:25%;" class="text-center"><?=$dataRow->approveBy?>'</td>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"><b>Prepared By</b></td>
							<td style="width:25%;" class="text-center"><b>Authorised By</b></td>
						</tr>
					</table>
					<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
						<tr>
							<td style="width:25%;">GI No. & Date : <?=$dataRow->trans_number.' ['.formatDate($dataRow->trans_date).']'?></td>
							<td style="width:25%;"></td>
							<td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
						</tr>
					</table>
                </htmlpagefooter>
				<sethtmlpagefooter name="lastpage" value="on" />
            </div>
        </div>        
    </body>
</html>
