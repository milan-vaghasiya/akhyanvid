<html>
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
                        <td style="width:33%;" class="fs-16 text-left">GSTIN: <?=$companyData->company_gst_no?></td>
                        <td style="width:34%;" class="fs-18 text-center">QUOTATION</td>
                        <td style="width:33%;" class="fs-16 text-right"></td>
                    </tr>
                </table>
                
                <table class="table item-list-bb fs-22" style="margin-top:5px;">
                    <tr>
                        <td style="width:60%; vertical-align:top;font-size:13px;" rowspan="4">
                            <b>M/S. <?=$dataRow[0]->party_name?></b><br>
                            <?=(!empty($partyData->party_address) ? $partyData->party_address ." ".$partyData->party_pincode : '')?><br>
							
							<br><b>Kind. Attn.: <?=$partyData->contact_person?></b><br>
							Contact No.: <?=$partyData->party_phone?><br>
							Email: <?=$partyData->party_email?>
                        </td>
                        <td colspan="2" style="width:40%;font-size:13px;">
                            <b>Qtn. No. :</b> <?=$dataRow[0]->trans_number?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:40%;font-size:13px;" colspan="2">
                            <b>Qtn. Date</b> : <?=formatDate($dataRow[0]->trans_date)?><br>
                        </td>
                    </tr>
					<tr>
                        <td style="width:40%;font-size:13px;" colspan="2">
                            <b>Description</b> :<br><?=$dataRow[0]->description?><br>
                        </td>
                    </tr>
                </table>
                
                <table class="table item-list-bb" style="margin-top:10px;">
                    <thead>
                        <tr class="bg-light-grey">
                            <th style="width:40px;">SRN.</th>
                            <th class="text-center"  style="width:40px;">PARTICULARS</th>
                            <th style="width:100px;">BRAND</th>
                            <th style="width:80px;">MODEL NO</th>
                            <th style="width:90px;">QTY </th>
                            <th style="width:90px;">RATE </th>
                            <th style="width:60px;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $i=1;$totalAmt = 0;
                            if(!empty($dataRow)):
                                foreach($dataRow as $row):	
                                    echo '<tr>';
                                        echo '<td class="text-center">'.$i++.'</td>';
                                        if ($row->item_class == "Service") {
                                            echo '<td colspan="5">' . $row->item_name . '</td>';
                                        } else {
                                            echo '<td class="text-center">' . $row->item_name . '</td>';
                                            echo '<td>' . $row->make_brand . '</td>';
                                            echo '<td class="text-center">' . $row->item_code . '</td>';
                                            echo '<td class="text-center">' . sprintf('%.2f', $row->qty) . '</td>';
                                            echo '<td class="text-right">' . sprintf('%.2f', $row->price) . '</td>';
                                        }
                                            echo '<td class="text-right">' . moneyFormatIndia($row->amount) . '</td>';
                                            echo '</tr>';
                                    
                                    $totalAmt += $row->amount;
                                endforeach;
                            endif;
                        ?>
                        <tr>
                            <th colspan="6" class="text-right" style="font-size:13px;">Net Amount </th>
                            <th class="text-right" style="font-size:13px;"><?=moneyFormatIndia($totalAmt)?></th>
                            
                        </tr>
                    </tbody>
                </table>
                
                <div style="font-size:12px;padding-left:10px;" style="margin-top:10px;">
                    <strong class="text-left">Terms & Conditions :-</strong><br>
                    <?php
                        if(!empty($dataRow[0]->conditions)):
                                echo $dataRow[0]->conditions;
                        endif;
                    ?>
                </div>
				<htmlpagefooter name="lastpage">
                    <table class="table top-table" style="margin-top:0px;border-top:1px solid #545454;">
                        <tr>
                            <td style="width:50%;"></td>
                            <th class="text-center">For, <?=$companyData->company_name?></th>
                        </tr>
                        <tr>
                            <td colspan="2" height="40"></td>
                        </tr>
                        <tr>
                            <td class="text-center"><?=$dataRow[0]->created_name?><br>Prepared By</td>
                            <td class="text-center"><br>Authorised By</td>
                        </tr>
                    </table>
                    <table class="table top-table" style="margin-top:0px;border-top:1px solid #545454;">
						<tr>
							<td style="width:25%;"><i>This is a computer-generated quotation.</i></td>
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