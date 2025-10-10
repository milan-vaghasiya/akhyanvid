<html>
    <head>
        <title>Packing List</title>
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url();?>assets/images/favicon.png">
    </head>
    <body>
        <?php
        $dataRow = $packingData[0];
        ?>
        <table class="table table-bordered">
            <tr>
                <th colspan="4" class="text-center" style="width:100%;"><h1>PACKING LIST</h1></th>
            </tr>
            <tr>
                <td colspan="2" rowspan="3" class="text-left" style="width:50%;">
                    <b>Exporter</b><hr style="margin:5px 0px;">
                    <b><?=$companyData->company_name?></b><br>
                    <?=$companyData->company_address."<br>".$companyData->company_city.", ".$companyData->company_state." - ".$companyData->company_pincode.", ".$companyData->company_country?><br>
                    Mobile No. : <?=$companyData->company_phone?><br>
                    Contact Person : <?=$companyData->company_contact_person?>
                </td>
                <td style="width:25%;">Invoice No. <br> <b><?=$dataRow->trans_number?></b></td>
                <td style="width:25%;">Date <br> <b><?=formatDate($dataRow->trans_date,"d F Y")?></b></td>
            </tr>
            <tr>
                <td>IEC <br> <b><?=$companyData->company_pan_no?></b></td>
                <td>GST <br> <b><?=$companyData->company_gst_no?></b></td>
            </tr>
            <tr>
                <td style="width:25%;">Po. No. <br> <b><?=$dataRow->doc_no?></b></td>
                <td style="width:25%;">Date <br> <b><?=(!empty($dataRow->doc_date)?$dataRow->doc_date:'')?></b></td>
            </tr>
            <tr>
                <td colspan="2" class="text-left" style="width:50%;">Consignee</td>
                <td colspan="2" class="text-left" style="width:50%;">Buyer (If not Consignee)</td>
            </tr>
            <tr>
                <td colspan="2" class="text-left" style="width:50%;vertical-align: top;">
                    <b><?=$dataRow->party_name?></b>
                </td>
                <td colspan="2" class="text-left" style="width:50%;vertical-align: top;height:80px;">
                   
                </td>
            </tr>
            <tr>
                <td class="text-left">
                    Metdod of Dispatch<br>
                    <b><?=$dataRow->method_of_dispatch?></b>
                </td>
                <td class="text-left">
                    Type of Shipment<br>
                    <b><?=$dataRow->type_of_shipment?></b>
                </td>
                <td class="text-left">
                    Country Of Origin<br>
                    <b><?=$dataRow->country_of_origin?></b>
                </td>
                <td class="text-left">
                    Country of Final Destination<br>
                    <b><?=$dataRow->country_of_fd?></b>
                </td>
            </tr>
            <tr>
                <td class="text-left">
                    Port of Loading<br>
                    <b><?=$dataRow->port_of_loading?></b>
                </td>
                <td class="text-left">
                    Date of Departure<br>
                    <b><?=(!empty($dataRow->date_of_departure))?formatDate($dataRow->date_of_departure):""?></b>
                </td>
                <td colspan="2" rowspan="2" class="text-left" style="vertical-align: top;">
                    Terms / Method of Payment<br>
                    <b><?=$dataRow->terms_of_delivery?></b>
                </td>
            </tr>
            <tr>
                <td class="text-left">
                    Port of Discharge<br>
                    <b><?=$dataRow->port_of_discharge?></b>
                </td>
                <td class="text-left">
                    Final Destination<br>
                    <b></b>
                </td>
            </tr>
        </table>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" style="width:10%">Package No</th>
                    <th class="text-center" style="width:20%">Box Detail</th>
                    <th class="text-center" style="width:40%">Description of Goods</th>
                    <th class="text-center" style="width:10%">Qty</th>
                    <th class="text-center" style="width:10%">Total Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i=1;$totalQty = $totalBox = $totalPallets = $totalNetWeight = $totalGrossWeight = 0;
                    $boxList = array_reduce($packingData , function($boxList, $item) { $boxList[$item->box_no][] = $item; return $boxList; }, []);
					
                    if(!empty($boxList)){ 
					
						ksort($boxList);
					
                        foreach($boxList as $package_no=>$item){
                            $qty = 0;
                            foreach($item AS $key=>$row){ 
								$row->total_box = (!empty($row->total_box)?$row->total_box:1);
								$qty += $row->total_box * $row->qty_box; 
							}
                            $firstRow = true;$grossWt=0;
							
                            foreach($item AS $key=>$row){
                                $lastTd = "";
                                echo '<tr>';
                                if($firstRow == true){
                                    $rowspan=count($item);
                                    echo '<td rowspan="'.$rowspan.'" class="text-center">'.$row->box_no.'</td>'; 
                                    echo '<td rowspan="'.$rowspan.'" class="text-center">'.$row->box_size.'</td>';
                                    $lastTd = '<td rowspan="'.$rowspan.'" class="text-center">'.$qty.'</td>';
                                    $firstRow = false;
                                }
                               
								$row->total_box = (!empty($row->total_box)?$row->total_box:1);
                                echo ' <td class="text-center">'.$row->item_name.'</td>
									<td class="text-center">'.floatval(($row->qty_box * $row->total_box)).'</td>
									'.$lastTd.'
                                </tr>';
								
                                $totalBox += floatval($row->total_box);
                                $grossWt += $row->wt_pcs * ($row->qty_box *  $row->total_box);
                                $i++;
                            }
							
                            $totalQty += ($qty);
                            $totalPallets ++;
                            $totalNetWeight += $grossWt + $item[0]->box_wt;
                            $totalGrossWeight +=$grossWt;
                        }
                    }

                    $blankLines = (15 - $i);
                    if($blankLines > 0):
                        for($j=0;$j<=$blankLines;$j++):
                            echo '<tr>
                                <td style="border-top:none;border-bottom:none;">&nbsp;</td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                                <td style="border-top:none;border-bottom:none;"></td>
                            </tr>';
                        endfor;
                    endif;
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-left" colspan="3"> Total Cartoon : <?=count($boxList)?></th>
                    <th class="text-left" colspan="2">Total Qty : <?=$totalQty?></th>
                </tr>
            </tfoot>
        </table>

        <table class="table table-bordered" style="page-break-inside: avoid;">
            <tr>
                <td colspan="2" class="text-left" style="width:50%;">
                    Additional Info
                </td>
                <td colspan="2" class="text-left" style="width:25%;">
                    Incoterms® 2020
                </td>
                <td style="width:25%;" class="text-center">
                    Date of Issue
                </td>
            </tr>
            <tr>
                <th colspan="2" style="width:50%;">
                    "I/We Shell Claim Rewards Under Merchandise Export From India Scheme. (RoDTEP)"
                </th>
                <th style="width:10%;">
                    <!-- <?=$dataRow->delivery_type?> -->
                </th>
                <th style="width:20%;">
                    <!-- <?=$dataRow->delivery_location?> -->
                </th>
                <th style="width:20%;">
                    <?=formatDate($dataRow->trans_date)?>
                </th>
            </tr>
            <tr>
                <th class="text-left" style="width:30%;">Total Pallets</th>
                <th class="text-left" style="width:10%;"><?=$totalPallets?></th>
                <th class="text-left" style="width:10%;">Nos</th>

                <td colspan="2" style="width:50%;">
                    Signatory Company<br>
                    <b><?=$companyData->company_name?></b>
                </td>
            </tr>
            <tr>
                <th class="text-left" style="width:30%;">Total Gross Weight</th>
                <th class="text-left" style="width:10%;"><?=sprintf("%.03f",$totalGrossWeight)?></th>
                <th class="text-left" style="width:10%;">Kgs</th>

                <td colspan="2" rowspan="2" style="width:50%;">
                    Name of Authorized Signatory<br>
                    <b></b>
                </td>
            </tr>
            <tr>
                <th class="text-left" style="width:30%;">Total Net Weight</th>
                <th class="text-left" style="width:10%;"><?=sprintf("%.03f",$totalNetWeight)?></th>
                <th class="text-left" style="width:10%;">Kgs</th>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="text-center" colspan="3" style="width:50%;height:90px;">

                </td>
            </tr>
        </table>

        <htmlpagefooter name="lastpage">
            <table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                <tr>
                    <td style="width:25%;"></td>
                    <td style="width:25%;"></td>
                    <td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
                </tr>
            </table>
        </htmlpagefooter>
        <sethtmlpagefooter name="lastpage" value="on" />
    </body>
</html>