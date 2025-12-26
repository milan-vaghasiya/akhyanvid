<html>
    <head>
        <title>Gate Inward</title>
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url();?>assets/images/favicon.png">
    </head>
    <body>
        <div class="row">
            <div class="col-12">
				<table>
					<tr>
						<td>
							<?php if(!empty($letter_head)): ?>
                                <img src="<?=$letter_head?>" class="img">
                            <?php endif;?>
						</td>
					</tr>
				</table>

				<table class="table">
					<tr class="" style="letter-spacing: 2px;font-weight:bold;padding:2px !important; border-bottom:1px solid #000000;">
						<td style="width:30%;" class="fs-18 text-left"></td>
						<th style="width:40%;" class="fs-18 text-center">GOODS RECEIPT NOTE</th>
						<td style="width:30%;" class="fs-17 text-right"><?=(!empty($dataRow->project_name) ? strtoupper($dataRow->project_name) : '')?></td>
					</tr>
				</table>               
                
                <table class="table item-list-bb fs-22" style="margin-top:3px;">
                    <tr>
                        <td rowspan="5" style="width:67%;vertical-align:top;">
                            <b>M/S. <?=$dataRow->party_name?></b><br>
                            <?=(!empty($partyData->party_address) ? $partyData->party_address ." - ".$partyData->party_pincode : '')?><br>
                            <b>Kind. Attn. : <?=$partyData->contact_person?></b> <br>
                            Contact No. : <?=$partyData->party_phone?><br>
                            Email : <?=$partyData->party_email?><br><br>
                            GSTIN : <?=$partyData->gstin?><br>
                        </td>
                        <td><b>GRN No.</b></td>
                        <td><?=$dataRow->trans_number?></td>
                    </tr>
                    <tr>
				        <th class="text-left">GRN Date</th>
                        <td><?=formatDate($dataRow->trans_date)?></td>
                    </tr>
                    <tr>
                        <th class="text-left">Inv./Ch No.</th>
                        <td><?=(!empty($dataRow->doc_no) ? $dataRow->doc_no : '')?></td>
                    </tr>
                    <tr>
                        <th class="text-left">Inv./Ch Date</th>
                        <td><?=(!empty($dataRow->doc_date)) ? formatDate($dataRow->doc_date) : ""?></td>
                    </tr>
                </table>
                
                <table class="table item-list-bb" style="margin-top:10px;">
					<thead>
						<tr class="bg-light">
							<th style="width:40px;">No.</th>
							<th class="text-left">Item Description</th>
							<th style="width:80px;">Qty</th>
							<th style="width:75px;">Rate</th>
							<th style="width:110px;">Taxable Amount</th>
						</tr>
					</thead>
					<tbody>
						<?php
                        $i=1; $totalQty=0; $totalAmt=0;
                        if (!empty($dataRow->itemData)) {
                            foreach ($dataRow->itemData as $row) {
                                $amount = ($row->qty * $row->price);
								$notes = '';$notesRSPN = 1;
								if(!empty($row->item_remark)){$notes = '<tr><td colspan="4"><i>Notes:</i> '.$row->item_remark.'</td></tr>';$notesRSPN = 2;}
                                echo '<tr>
                                    <td class="text-center" rowspan="'.$notesRSPN.'">'.$i++.'</td>
                                    <td>'.(!empty($row->item_code) ? '['.$row->item_code.'] ' : '').$row->item_name.'</td>
                                    <td class="text-center">'.floatval($row->qty).' <small>'.$row->uom.'</small></td>
                                    <td class="text-right">'.moneyFormatIndia($row->price).'</td>
                                    <td class="text-right">'.moneyFormatIndia($amount).'</td>
                                </tr>';
								echo $notes;
                                $totalQty += $row->qty;
                                $totalAmt += $amount;
                            }
                        }
						?>
						<tr>
							<th colspan="2" class="text-right">Total Qty.</th>
							<th class="text-right"><?=floatval($totalQty)?></th>
							<th class="text-right">Sub Total</th>
							<th class="text-right"><?=moneyFormatIndia($totalAmt)?></th>
						</tr>
						<tr>
							<th class="text-left" colspan="5">
								Amount In Words : <?=numToWordEnglish(round($totalAmt))?>
							</th>							
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
							<td style="width:25%;" class="text-center"></td>
							<td style="width:25%;" class="text-center"><?=$dataRow->prepareBy?><br>(<?=formatDate($dataRow->created_at)?>)</td>
						</tr>
						<tr>
							<td style="width:25%;" class="text-center"><b></b></td>
							<td style="width:25%;" class="text-center"><b>Prepared By</b></td>
						</tr>
					</table>
					<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
						<tr>
							<td style="width:25%;">GRN No. & Date : <?=$dataRow->trans_number.' ['.formatDate($dataRow->trans_date).']'?></td>
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
