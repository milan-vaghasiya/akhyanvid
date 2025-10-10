<?php
    $msme = '';$toprs = 3;$notes= '';$regDetail = '<b>GSTIN :</b> '.$companyData->company_gst_no;$refNo = '';
    if(!empty($companyData->company_reg_no))
    {
        $msme = '<tr><td colspan="2">MSME Number<br><b>'.$companyData->company_reg_no.'</b></td></tr>';
        $regDetail .= ' • <b>MSME No. : </b> '.$companyData->company_reg_no;
        //$toprs++;
    }
    if(!empty($companyData->company_pan_no)){$regDetail .= ' • <b>PAN No. : </b> '.$companyData->company_pan_no;}
    $regDetail .= '<hr>';
    if(!empty($invData->remark)){$notes = '<hr>'.$invData->remark;}
    if(!empty($invData->doc_no)){$refNo = $invData->doc_no;};
    if(!empty($invData->doc_no) AND !empty($invData->doc_date)){$refNo = $invData->doc_no." / ".formatDate($invData->doc_date,"d F Y");}
    
?>

<div class="row">
    <div class="col-12">
        <?php if(!empty($header_footer)): ?>
        <table>
            <tr>
                <td>
                    <?php if(!empty($letter_head)): ?>
                        <img src="<?=$letter_head?>" class="img">
                    <?php endif;?>
                </td>
            </tr>
        </table>
        <?php endif; ?>
        
        <table class="table bg-light-grey">
            <tr class="" style="letter-spacing: 2px;font-weight:bold;padding:2px !important; border-bottom:1px solid #000000;">
                <th style="width:33%;" class="fs-14 text-left">
                    GSTIN : <?=$companyData->company_gst_no?>
                    
                </th>
                <th style="width:33%;" class="fs-18 text-center">
                    TAX INVOICE
                </th>
                <th style="width:33%;" class="fs-14 text-right">
                    <?=$printType?>
                </th>
            </tr>
        </table>
        
        <?php if(!empty($invData->e_inv_no)): ?>
        <table class="table item-list-bb" style="margin-top:5px; page-break-inside: avoid;">
            <tr>
                <th colspan="7" class="text-center">E-Invoice Details</th>
            </tr>
            <tr>
                <th>ACK NO.</th>
                <td><?=$invData->e_inv_no?></td>

                <th>ACK Date</th>
                <td><?=date("d-m-Y H:i:s",strtotime($invData->e_inv_date))?></td>

                <th>EWB No.</th>
                <td><?=(!empty($invData->eway_bill_no))?$invData->eway_bill_no:'-'?></td>
                
                <td rowspan="2" class="text-center" style="padding:0px;width:20%;">
                    <img width="100" src="data:image/png;base64,'<?=$invData->e_inv_qr_code?>'">
                </td>
            </tr>
            <tr>
                <th>IRN</th>
                <td colspan="5">
                    <?=$invData->e_inv_irn?>
                </td>
            </tr>
        </table>
        <?php 
			endif;
			$state_name = (!empty($partyData->state_name) ? " - ".$partyData->state_name : "");
			$city_name = (!empty($partyData->city_name) ? " - ".$partyData->city_name : "");
			$party_pincode = (!empty($partyData->party_pincode) ? " - ".$partyData->party_pincode : "");
		?>
        <table class="table table-bordered">
            <tr>
                <td rowspan="<?=$toprs?>" colspan="4" class="text-left" style="width:56%;vertical-align: top;">
                    Buyer (Bill To)<br>
                    <b>M/s. <?=$partyData->party_name?></b><br><br>
					<?=(!empty($partyData->party_address) ? $partyData->party_address.$state_name.$city_name.$party_pincode : '')?><br>
					<b>Contact No:</b> <?=(!empty($partyData->party_mobile) ? $partyData->party_mobile : '')?><br>
					<b>Place of Supply:</b> <?=(!empty($partyData->state_code) ? $partyData->state_code.'-' : '')?> <?=(!empty($partyData->state_name) ? $partyData->state_name : '')?><br>
                    <b>GSTIN:</b> <?=(!empty($partyData->gstin) ? $partyData->gstin : '')?>
                </td>
                <td style="width:22%;">Invoice No. <br> <b><?=$invData->trans_number?></b></td>
                <td style="width:22%;">Date <br> <b><?=formatDate($invData->trans_date,"d F Y")?></b></td>
            </tr>
            <tr><td colspan="2">P.O. No. & Date<br><b><?=(!empty($refNo)) ? $refNo : "-"?></b></td></tr>
            <tr>
                <td>Payment <br> <b><?=(!empty($partyData->credit_days))?$partyData->credit_days." Days Credit":"-"?></b></td>
                <td style="width:25%;">Memo Type <br> <b><?=$invData->memo_type?></b></td>
            </tr>
            <tr>
                <td rowspan="<?=$toprs?>" colspan="4" class="text-left" style="width:56%;vertical-align: top;">
					Buyer (Ship To)<br>
                    <b>M/s. <?=$partyData->party_name?></b><br><br>
                    <?=(!empty($partyData->party_address) ? $partyData->party_address : '')?><br>
					<b>Contact No:</b> <?=(!empty($partyData->party_mobile) ? $partyData->party_mobile : '')?><br>
					<b>Place of Supply:</b> <?=(!empty($partyData->state_code) ? $partyData->state_code.'-' : '')?> <?=(!empty($partyData->state_name) ? $partyData->state_name : '')?><br>
                    <b>GSTIN:</b> <?=(!empty($partyData->gstin) ? $partyData->gstin : '')?>
				</td>
                <td colspan="2" style="vertical-align: top;">
					Transport <br> <b><?=(!empty($invData->transport_name))?$invData->transport_name." - ".$invData->transport_gst_no:" - "?></b><br><br>
					Vehicle No <br> <b><?=(!empty($invData->vehicle_no))?$invData->vehicle_no:" - "?></b>
				</td>
            </tr>
			<tr>
                <td>L. R. No. <br> <b><?=(!empty($invData->lr_no))?$invData->lr_no:" - "?></b></td>
                <td>L. R. Date <br> <b><?=(!empty($invData->lr_date))?$invData->lr_date:" - "?></b></td>
            </tr>
        </table>
        <table class="table item-list-bb" style="margin-top:5px;">
            <?php $thead = '<thead>
                    <tr>
                        <th style="width:20px;">No.</th>
                        <th class="text-left">Description of Goods</th>
                        <th style="width:10%;">PO. No.</th>
                        <th style="width:10%;">HSN/SAC</th>
                        <th style="width:50px;">Qty</th>
                        <th style="width:50px;">Unit</th>
                        <th style="width:50px;">Rate</th>
                        <th style="width:50px;">Disc<br><small>(%)</small></th>
                        <th style="width:50px;">GST<br><small>(%)</small></th>
                        <th style="width:90px;">Amount</th>
                    </tr>
                </thead>';
                echo $thead;
            ?>
            <tbody>
                <?php
                    $i=1;$totalBoxQty = $totalQty = 0;$migst=0;$mcgst=0;$msgst=0;
                    $rowCount = 1;$pageCount = 1;
                    if(!empty($invData->itemList)):
                        foreach($invData->itemList as $row):
                            $item_name = $row->item_name;
                            $item_name .= (!empty($row->brand) ? ' ('.$row->brand.')' : '');
                            $item_name .= (!empty($descriptionSetting->is_description)) ? '<br><small>'.$row->description.'</small>' : '';
                            //$item_name .= (!empty($row->ref_no) ? '<br>'.$row->ref_no : '');
                            echo '<tr>';
                                echo '<td class="text-center" style="border-top:none;border-bottom:none;">'.$i++.'</td>';
                                echo '<td style="border-top:none;border-bottom:none;">'.$item_name.'</td>';
                                echo '<td class="text-center" style="border-top:none;border-bottom:none;">'.$row->ref_no.'</td>';
                                echo '<td class="text-center" style="border-top:none;border-bottom:none;">'.$row->hsn_code.'</td>';
                                echo '<td class="text-right" style="border-top:none;border-bottom:none;">'.sprintf('%.2f',$row->qty).'</td>';
                                echo '<td class="text-right" style="border-top:none;border-bottom:none;">'.$row->unit_name.'</td>';
                                echo '<td class="text-right" style="border-top:none;border-bottom:none;">'.sprintf('%.2f',$row->price).'</td>';
                                echo '<td class="text-right" style="border-top:none;border-bottom:none;">'.sprintf('%.2f',$row->disc_per).'</td>';
                                echo '<td class="text-center" style="border-top:none;border-bottom:none;">'.sprintf('%.2f',$row->gst_per).'</td>';
                                echo '<td class="text-right" style="border-top:none;border-bottom:none;">'.sprintf('%.2f',$row->taxable_amount).'</td>';
                            echo '</tr>';
                            
                            $totalQty += $row->qty;
                            if($row->gst_per > $migst){$migst=$row->gst_per;$mcgst=$row->cgst_per;$msgst=$row->sgst_per;}
                        endforeach;
                    endif;

                    $blankLines = ($maxLinePP - $i);
                    if($blankLines > 0):
                        for($j=0;$j<=$blankLines;$j++):
                            echo '<tr>
                                <td style="border-top:none;border-bottom:none;">&nbsp;</td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                            </tr>';
                        endfor;
                    endif;
                    
                    $rwspan= 0; $srwspan = '';
                    $beforExp = "";
                    $afterExp = "";
                    $invExpenseData = (!empty($invData->expenseData)) ? $invData->expenseData : array();
                    foreach ($expenseList as $row) :
                        $expAmt = 0;
                        $amtFiledName = $row->map_code . "_amount";
                        if (!empty($invExpenseData) && $row->map_code != "roff") :
                            $expAmt = floatVal($invExpenseData->{$amtFiledName});
                        endif;

                        if(!empty($expAmt)):
                            if ($row->position == 1) :
                                if($rwspan == 0):
                                    $beforExp .= '<th colspan="2" class="text-right">'.$row->exp_name.'</th>
                                    <td class="text-right">'.sprintf('%.2f',$expAmt).'</td>';
                                else:
                                    $beforExp .= '<tr>
                                        <th colspan="2" class="text-right">'.$row->exp_name.'</th>
                                        <td class="text-right">'.sprintf('%.2f',$expAmt).'</td>
                                    </tr>';
                                endif;
                                $rwspan++;
                            endif;
                        endif;
                    endforeach;

                    $taxHtml = '';$totalTaxAmount = 0;
                    foreach ($taxList as $taxRow) :
                        $taxAmt = 0;
                        $taxAmt = floatVal($invData->{$taxRow->map_code.'_amount'});
                        if(!empty($taxAmt)):
                            if($rwspan == 0):
                                $taxHtml .= '<th colspan="2" class="text-right">'.$taxRow->name.' @'.(($invData->gst_type == 1)?floatVal($migst/2):$migst).'%</th>
                                <td class="text-right">'.sprintf('%.2f',$taxAmt).'</td>';
                            else:
                                $taxHtml .= '<tr>
                                    <th colspan="2" class="text-right">'.$taxRow->name.' @'.(($invData->gst_type == 1)?floatVal($migst/2):$migst).'%</th>
                                    <td class="text-right">'.sprintf('%.2f',$taxAmt).'</td>
                                </tr>';
                            endif;
                            $totalTaxAmount += $taxAmt;
                            $rwspan++;
                        endif;
                    endforeach;

                    foreach ($expenseList as $row) :
                        $expAmt = 0;
                        $amtFiledName = $row->map_code . "_amount";
                        if (!empty($invExpenseData) && $row->map_code != "roff") :
                            $expAmt = floatVal($invExpenseData->{$amtFiledName});
                        endif;

                        if(!empty($expAmt)):
                            if ($row->position == 2) :
                                if($rwspan == 0):
                                    $afterExp .= '<th colspan="2" class="text-right">'.$row->exp_name.'</th>
                                    <td class="text-right">'.sprintf('%.2f',$expAmt).'</td>';
                                else:
                                    $afterExp .= '<tr>
                                        <th colspan="2" class="text-right">'.$row->exp_name.'</th>
                                        <td class="text-right">'.sprintf('%.2f',$expAmt).'</td>
                                    </tr>';
                                endif;
                                $rwspan++;
                            endif;
                        endif;
                    endforeach;
                    $fixRwSpan = (!empty($rwspan))?3:0;
                ?>
                <tr>
                    <td colspan="3"> <b>Total Box : </b> <?=(!empty($invData->packingList->boxNo) ? $invData->packingList->boxNo : 0)?>
                    •  <b>Total Weight : </b><?= (!empty($invData->packingList->boxWt) ?$invData->packingList->boxWt :0)?> KGS </td>
                    <th class="text-right">Total Qty.</th>
                    <th class="text-right"><?=sprintf('%.2f',$totalQty)?></th>
                    <th></th>
                    <th></th>
                    <th colspan="2" class="text-right">Sub Total</th>
                    <th class="text-right"><?=numberFormatIndia(sprintf('%.2f',$invData->taxable_amount))?></th>
                </tr>
                <tr>
                    <td class="text-left" colspan="7" rowspan="<?=$rwspan?>">
                       
                        <?=$regDetail?>
                     
                        <b>Bank Name : </b> <?=$companyData->company_bank_name.", ".$companyData->company_bank_branch?><br>
                        <b>A/c. No. : </b><?=$companyData->company_acc_no?><br>
                        <b>IFSC Code : </b><?=$companyData->company_ifsc_code?>
                        <?=$notes?>
                    </td>
                    <?php if(empty($rwspan)): ?>
                        <th colspan="2" class="text-right">Round Off</th>
                        <td class="text-right"><?=sprintf('%.2f',$invData->round_off_amount)?></td>
                    <?php endif; ?>
                </tr>
                <?=$beforExp.$taxHtml.$afterExp?>
                <tr>
                    <td class="text-left" colspan="7" rowspan="<?=$fixRwSpan?>">
                        <b>GST Amount (In Words)</b> : <?=($totalTaxAmount > 0)?numToWordEnglish(sprintf('%.2f',$totalTaxAmount)):""?>
                        <hr>
                        <b>Bill Amount (In Words)</b> : <?=numToWordEnglish(sprintf('%.2f',$invData->net_amount))?>
                    </td>	
                    
                    <?php if(empty($rwspan)): ?>
                        <th colspan="2" class="text-right">Grand Total</th>
                        <th class="text-right"><?=sprintf('%.2f',$invData->net_amount)?></th>
                    <?php else: ?>
                        <th colspan="2" class="text-right">Round Off</th>
                        <td class="text-right"><?=sprintf('%.2f',$invData->round_off_amount)?></td>
                    <?php endif; ?>
                </tr>
                
                <?php if(!empty($rwspan)): ?>
                <tr>
                    <th colspan="2" class="text-right">Grand Total</th>
                    <th class="text-right"><?=numberFormatIndia(sprintf('%.2f',$invData->net_amount))?></th>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <table class="table top-table" style="margin-top:10px;">
            <tr>
                <th class="text-left">Terms & Conditions :-</th>
            </tr>
            <?php
                if(!empty($termsData->condition)):
                    echo '<tr>';
                        echo '<td class=" fs-10">'.$termsData->condition.'</td>';
                    echo '</tr>';
                endif;
            ?>
        </table>
        
        <htmlpagefooter name="lastpage">
            <table style="margin-top:5px;page-break-inside: avoid;">
                <tr>
                    <th colspan="2" style="vertical-align:bottom;text-align:right;font-size:1rem;padding:5px 2px;">
                        For, <?=$companyData->company_name?><br>
                    </th>
                </tr>
                <tr>
                    <td colspan="2" height="35"></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="vertical-align:bottom;text-align:right;font-size:1rem;padding:5px 2px;"><b>Authorised Signature</b></td>
                </tr>
				<?php if(!empty($header_footer)): ?>				
					<tr>
						<td colspan="2">
							<?php if(!empty($letter_foot)): ?>
								<img src="<?=$letter_foot?>" class="img">
							<?php endif;?>
						</td>
					</tr>
				<?php else: ?>
					<tr>
						<td colspan="2" height="35"></td>
					</tr>
				<?php endif; ?>
            </table>
        </htmlpagefooter>
		<sethtmlpagefooter name="lastpage" value="on" />  
    </div>
</div>