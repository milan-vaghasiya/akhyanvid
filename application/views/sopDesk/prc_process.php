<?php
    $prcProcess = '';
    if($status > 1)
    {
        $hidden = ($prcData->production_type == 1)?'hidden':'';
        if(!empty($prcProcessData))
        { ?>
            <div class="table-responsive">
                <table class="table jpExcelTable" style="margin-bottom:30px !important">
                    <thead class="bg-light-peach">
                        <tr  class="text-center">
                            <th style=" padding: 8px !important; ">#</th>
                            <th <?=$hidden?>>Action</th>
                            <th  class="text-left">Process</th>
                            <th >Unaccepted</th>
                            <th >In</th>
                            <th >Ok</th>
                            <th >Rej. Found</th>
                            <th >Rej.</th>
                            <th >Pending Prod.</th>
                            <th>Stock.</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i=1;$index=0;
                        foreach($prcProcessData as $row){
                            $currentProcess = !empty($row->current_process)?$row->current_process : 'Initial Stage';
                            $in_qty = (!empty($row->in_qty)?$row->in_qty:0);
                            $ok_qty = !empty($row->ok_qty)?$row->ok_qty:0;
                            $rej_found_qty = !empty($row->rej_found)?$row->rej_found:0;
                            $rej_qty = !empty($row->rej_qty)?$row->rej_qty:0;
                            $rw_qty = !empty($row->rw_qty)?$row->rw_qty:0;
                            $pendingReview = $rej_found_qty - $row->review_qty;
                            $pending_production =($in_qty) - ($ok_qty+$rej_qty+$rw_qty+$pendingReview);
                            $movement_qty =!empty($row->movement_qty)?$row->movement_qty:0;
                            $short_qty =!empty($row->short_qty)?$row->short_qty:0;
                            $pending_movement = $ok_qty - ($movement_qty);
                            $pending_accept =!empty($row->pending_accept)?$row->pending_accept:0;
                            $process_from = ($index>0)?$prcProcessData[$index-1]->process_id:0;

                            $logBtn = "";$movementBtn="";$chReqBtn="";$receiveBtn="";$firButton = "";
                            if($row->process_id == 2){
                                $reportParam = "{'postData':{'process_id' : ".$row->process_id.",'prc_id':".$prcData->prc_id.",'trans_type':".$move_type.",'process_from':".$process_from."},'modal_id' : 'bs-right-xl-modal', 'form_id' : 'firInsp', 'title' : 'Final Inspection','call_function':'addFinalInspection','fnsave':'savePrcLog', 'js_store_fn' : 'customStore'}";
                                $firButton = '<a href="javascript:void(0)" type="button" class="btn btn-info permission-modify" datatip="Final Inspection" flow="down" onclick="modalAction('.$reportParam.');"><i class="fa fa-file-alt"></i></a>';
                            }else{
                                $logParam = "{'postData':{'process_id' : ".$row->process_id.",'prc_id':".$prcData->prc_id.",'trans_type':".$move_type.",'process_from':".$process_from."},'modal_id' : 'bs-right-lg-modal', 'call_function':'prcLog', 'form_id' : 'addPrcLog', 'title' : 'PRC LOG', 'fnsave' : 'savePRCLog','button':'close'}";
                                $logBtn = '<a href="javascript:void(0)" onclick="modalAction('.$logParam.')" class="btn btn-success permission-modify" datatip="Add Log" flow="down"><i class="fas fa-clipboard-list"></i></a>';
                            
                                $title = '[Pending Qty : '.floatval($pending_production).']';
                                $chReqParam = "{'postData':{'process_id' : ".$row->process_id.",'prc_id':".$prcData->prc_id.",'trans_type':".$move_type.",'process_from':".$process_from."},'modal_id' : 'bs-right-md-modal', 'call_function':'challanRequest', 'form_id' : 'addChallanRequest', 'title' : 'Challan Request ".$title ."', 'fnsave' : 'saveAcceptedQty','button':'close'}";
                                $chReqBtn = '<a href="javascript:void(0)" class="btn btn-warning permission-modify" datatip="Challan Request" flow="down" onclick="modalAction('.$chReqParam .')"><i class="fab fa-telegram-plane"></i></a>';
                            }
                            
                            $movementParam = "{'postData':{'process_id' : ".$row->process_id.",'prc_id':".$prcData->prc_id.",'move_type':".$move_type.",'process_from':".$process_from."},'modal_id' : 'bs-right-lg-modal', 'call_function':'prcMovement', 'form_id' : 'addPrcMovement', 'title' : 'PRC Movement', 'fnsave' : 'savePRCMovement','button':'close'}";
                            $movementBtn = '<a href="javascript:void(0)" class="btn btn-info permission-modify" datatip="Movement" flow="down" onclick="modalAction('.$movementParam.')"><i class="fa fa-step-forward"></i></a>';
                            $acceptBtn="";
                            // if($pending_accept > 0 || $in_qty > 0){
                                $title = '[Pending Qty : '.floatval($pending_accept).']';
                                $acceptParam = "{'postData':{'process_id' : ".$row->process_id.",'prc_id':".$prcData->prc_id.",'trans_type':".$move_type.",'process_from':".$process_from."},'modal_id' : 'bs-right-md-modal', 'call_function':'prcAccept', 'form_id' : 'addPrcAccept', 'title' : 'Accept For Production ".$title."', 'fnsave' : 'saveAcceptedQty','button':'close'}";
                                $acceptBtn = '<a href="javascript:void(0)" class="btn btn-dark permission-modify" datatip="Accept" flow="down" onclick="modalAction('.$acceptParam .')"><i class="far fa-check-circle"></i></a>';
                            // }
                            $action = getActionButton($acceptBtn.$firButton.$logBtn.$chReqBtn.$movementBtn);
                            ?>
                            <tr class="text-center">
                                <td><?=$i++?></td>
                                
                                <td style="width:8%; padding: 6px !important;" <?=$hidden?>>
                                    <?=$action?>
                                
                                </td>
                                <td class="text-left" style="width:22%"><?=$currentProcess?> </td>
                                <td style="width:10%"><?=floatval($pending_accept)?> </td>
                                <td style="width:10%"><?=floatval($in_qty)?></td>
                                <td style="width:10%"><?=floatval($ok_qty)?> </td>
                                <td style="width:10%"><?=floatval($rej_found_qty)?> </td>
                                <td style="width:10%"><?=floatval($rej_qty)?> </td>
                                <td style="width:10%"><?=floatval($pending_production)?> </td>
                                <td style="width:10%"><?=floatval($pending_movement)?> </td>
                            </tr>
                            <?php
                            $index++;
                        }
                        if(!empty($semiProcessData)){
                            $in_qty = (!empty($semiProcessData->in_qty)?$semiProcessData->in_qty:0);
                            $ok_qty = !empty($semiProcessData->ok_qty)?$semiProcessData->ok_qty:0;
                            $rej_found_qty = !empty($semiProcessData->rej_found)?$semiProcessData->rej_found:0;
                            $rej_qty = !empty($semiProcessData->rej_qty)?$semiProcessData->rej_qty:0;
                            $rw_qty = !empty($semiProcessData->rw_qty)?$semiProcessData->rw_qty:0;
                            $pendingReview = $rej_found_qty - $semiProcessData->review_qty;
                            $pending_production =($in_qty) - ($ok_qty+$rej_qty+$rw_qty+$pendingReview);
                            $movement_qty =!empty($semiProcessData->movement_qty)?$semiProcessData->movement_qty:0;
                            $short_qty =!empty($semiProcessData->short_qty)?$semiProcessData->short_qty:0;
                            $pending_movement = $ok_qty - ($movement_qty);
                            $pending_accept =!empty($semiProcessData->pending_accept)?$semiProcessData->pending_accept:0;

                            $movementParam = "{'postData':{'process_id' : 1,'prc_id':".$prcData->prc_id."},'modal_id' : 'bs-right-lg-modal', 'call_function':'semiFinishMovement', 'form_id' : 'addPrcMovement', 'title' : 'PRC Movement', 'js_store_fn' : 'storeSop', 'fnsave' : 'savePRCMovement','button':'close'}";
                            $movementBtn = '<a href="javascript:void(0)" class="btn btn-info permission-modify" datatip="Movement" flow="down" onclick="loadform('.$movementParam.')"><i class="fa fa-step-forward"></i></a>';
                            ?>
                            <tr class="text-center">
                                <td><?=$i++?></td>
                                <td style="width:8%; padding: 6px !important;"  <?=$hidden?>>
                                    <!-- <div class="actionWrapper" style="position:relative;">
                                        <div class="actionButtons actionButtonsRight">
                                            <a class="mainButton btn-instagram" href="javascript:void(0)"><i class="fa fa-cog"></i></a>
                                            <div class="btnDiv" style="left:85%;">
                                                <?= $movementBtn?>
                                            </div>
                                        </div>
                                    </div> -->
                                
                                </td>
                                <td class="text-left" style="width:22%">Semi Finished Store</td>
                                <td style="width:10%"><?=floatval($pending_accept)?> </td>
                                <td style="width:10%"><?=floatval($in_qty)?></td>
                                <td style="width:10%"><?=floatval($in_qty)?> </td>
                                <td style="width:10%"></td>
                                <td style="width:10%"> </td>
                                <td style="width:10%"></td>
                                <td style="width:10%"> </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div> <?php
        }
    }
    else
    {
        if(!empty($prcProcessData))
        {
            $i=1;$prcProcess="";
            $prcProcess = '<div class="activity">';
            foreach($prcProcessData as $key=>$row){
                $prcProcess .='<div class="activity-info">
                                    <div class="icon-info-activity"><i class="las bg-soft-primary">'.$i++.'</i></div>
                                    <div class="activity-info-text">
                                        <div class="d-flex justify-content-between align-items-center"><h6 class="m-0  w-75 mt-2">'.$row->process_name.'</h6></div>
                                    </div>
                                </div>';
            }
            $prcProcess .= '</div>';
        }
    }
    
    echo $prcProcess;

?>