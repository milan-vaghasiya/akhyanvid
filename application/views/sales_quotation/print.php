<html>
    <head>
        <title>Quotation</title>
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url();?>assets/images/favicon.png">
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
                        <td style="width:33%;" class="fs-18 text-center">Quotation</td>
                        <td style="width:33%;" class="fs-18 text-right"></td>
                    </tr>
                </table>
                
                <table class="table item-list-bb fs-22" style="margin-top:5px;">
                    <tr>
                        <td style="width:60%; vertical-align:top;" rowspan="3">
                            <b>M/S. <?=$dataRow->party_name?></b><br>
                            <?=(!empty($partyData->party_address) ? $partyData->party_address ." - ".$partyData->party_pincode : '')?><br>
                            <b>GSTIN</b> : <?=(!empty($partyData->gstin)) ? $partyData->gstin : ""?>

                        </td>
                        <td>
                            <b>Qtn. No. : <?=$dataRow->trans_number?></b>
                        </td>
                        <td>
                            Rev No. : <?=sprintf("%02d",$dataRow->quote_rev_no)?>  / <?=formatDate($dataRow->doc_date)?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:40%;" colspan="2">
                            <b>Qtn. Date</b> : <?=formatDate($dataRow->trans_date)?><br>
                            <!-- <b>Valid till</b> : <?=formatDate($dataRow->delivery_date)?><br> -->
                        </td>
                    </tr>
                </table>
                
                <table class="table item-list-bb" style="margin-top:10px;">
                    <thead>
                        <tr>
                            <th style="width:40px;">No.</th>
                            <th class="text-left">Description of Goods</th>
                            <th class="text-center">Image</th>
                            <th style="width:10%;">HSN/SAC</th>
                            <th style="width:100px;">Qty</th>
                            <th style="width:60px;">Rate<br><small>(<?=$partyData->currency?>)</small></th>
                            <th style="width:60px;">Disc (%)</th>
                            <th style="width:60px;">GST <small>(%)</small></th>
                            <th style="width:110px;">Amount<br><small>(<?=$partyData->currency?>)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;$totalQty = 0;$migst=0;$mcgst=0;$msgst=0;
                            if(!empty($dataRow->itemList)):
                                foreach($dataRow->itemList as $row):	
                                    $itemImage="";
                                     if(!empty($row->item_image)): 
				                        $imgPath = base_url('assets/uploads/item_image/'.$row->item_image);

                                        $itemImage = '<a href="'.$imgPath.'" class="lightbox" target="_blank">
                                                            <img src="'.$imgPath.'" alt="" class="img-fluid"  width="50" height="50" style="border-radius:0%;border: 0px solid #ccc;padding:3px;"/>
                                                        </a>';
                                     endif;		
                                    echo '<tr>';
                                        echo '<td class="text-center">'.$i++.'</td>';
                                        echo '<td>'.$row->item_name.'</td>';
                                        echo '<td class="text-center">'.$itemImage.'</td>';
                                        echo '<td class="text-center">'.$row->hsn_code.'</td>';
                                        echo '<td class="text-center">'.floatVal($row->qty).' ('.$row->unit_name.')</td>';
                                        echo '<td class="text-right">'.floatVal($row->price).'</td>';
                                        echo '<td class="text-right">'.floatVal($row->disc_per).'</td>';
                                        echo '<td class="text-center">'.$row->gst_per.'</td>';
                                        echo '<td class="text-right">'.$row->taxable_amount.'</td>';
                                    echo '</tr>';
                                    
                                    $totalQty += $row->qty;
                                    if($row->gst_per > $migst){$migst=$row->gst_per;$mcgst=$row->cgst_per;$msgst=$row->sgst_per;}
                                endforeach;
                            endif;

                            $blankLines = (10 - $i);
                            if($blankLines > 0):
                                for($j=1;$j<=$blankLines;$j++):
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
                                    </tr>';
                                endfor;
                            endif;

                            $rwspan= 0; $srwspan = '';
                            $beforExp = "";
                            $afterExp = "";
                            $invExpenseData = (!empty($dataRow->expenseData)) ? $dataRow->expenseData : array();
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

                            $taxHtml = ''; 
                            foreach ($taxList as $taxRow) :
                                $taxAmt = 0;
                                $taxAmt = floatVal($dataRow->{$taxRow->map_code.'_amount'});
                                if(!empty($taxAmt)):
                                    if($rwspan == 0):
                                        $taxHtml .= '<th colspan="2" class="text-right">'.$taxRow->name.' @'.(($dataRow->gst_type == 1)?floatVal($migst/2):$migst).'%</th>
                                        <td class="text-right">'.sprintf('%.2f',$taxAmt).'</td>';
                                    else:
                                        $taxHtml .= '<tr>
                                            <th colspan="2" class="text-right">'.$taxRow->name.' @'.(($dataRow->gst_type == 1)?floatVal($migst/2):$migst).'%</th>
                                            <td class="text-right">'.sprintf('%.2f',$taxAmt).'</td>
                                        </tr>';
                                    endif;
                                
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
                            <th colspan="4" class="text-right">Total Qty.</th>
                            <th class="text-right"><?=sprintf('%.3f',$totalQty)?></th>
                            <th></th>
                            <th colspan="2" class="text-right">Sub Total</th>
                            <th class="text-right"><?=sprintf('%.2f',$dataRow->taxable_amount)?></th>
                        </tr>
                        <tr >
                            <th class="text-left" colspan="6" rowspan="<?=$rwspan?>">
                                <b>Bank Name : </b> <?=$companyData->company_bank_name?><br>
                                <b>A/c. No. : </b><?=$companyData->company_acc_no?><br>
                                <b>IFSC Code : </b><?=$companyData->company_ifsc_code?><br>
                                <b>Branch : </b><?=$companyData->company_bank_branch?>
                                <hr>
                                <b>Note : </b> <?=$dataRow->remark?>
                            </th>

                            <?php if(empty($rwspan)): ?>
                                <th colspan="2" class="text-right">Round Off</th>
                                <td class="text-right"><?=sprintf('%.2f',$dataRow->round_off_amount)?></td>
                            <?php endif; ?>
                        </tr>
                        <?=$beforExp.$taxHtml.$afterExp?>
                        <tr>
                            <th class="text-left" colspan="6" rowspan="<?=$fixRwSpan?>">
                                Amount In Words : <br><?=numToWordEnglish(sprintf('%.2f',$dataRow->net_amount))?>
                            </th>	
                            
                            <?php if(empty($rwspan)): ?>
                                <th colspan="2" class="text-right">Grand Total</th>
                                <th class="text-right"><?=sprintf('%.2f',$dataRow->net_amount)?></th>
                            <?php else: ?>
                                <th colspan="2" class="text-right">Round Off</th>
                                <td class="text-right"><?=sprintf('%.2f',$dataRow->round_off_amount)?></td>
                            <?php endif; ?>
                        </tr>
                        
                        <?php if(!empty($rwspan)): ?>
                        <tr>
                            <th colspan="2" class="text-right">Grand Total</th>
                            <th class="text-right"><?=sprintf('%.2f',$dataRow->net_amount)?></th>
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
                    <table class="table top-table" style="margin-top:0px;border-top:1px solid #545454;">
                        <tr>
                            <td style="width:50%;"></td>
                            <td style="width:20%;"></td>
                            <th class="text-center">For, <?=$companyData->company_name?></th>
                        </tr>
                        <tr>
                            <td colspan="3" height="40"></td>
                        </tr>
                        <tr>
                            <td><br>This is a computer-generated quotation.</td>
                            <td class="text-center"><?=$dataRow->created_name?><br>Prepared By</td>
                            <td class="text-center"><br>Authorised By</td>
                        </tr>
                    </table>
                    <table class="table top-table" style="margin-top:0px;border-top:1px solid #545454;">
						<tr>
							<td style="width:25%;">Qtn. No. & Date : <?=$dataRow->trans_number.' ['.formatDate($dataRow->trans_date).']'?></td>
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