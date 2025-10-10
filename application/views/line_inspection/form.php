
<form>
    <div class="col-md-12">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Prc No.</th>
                            <th>Prc Date </th>
                            <th>Item Name</th>
                            <th>Process</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=(!empty($dataRow->prc_number))?$dataRow->prc_number:((!empty($lineInspData->prc_number))?$lineInspData->prc_number:"")?></td>
                            <td><?=(!empty($dataRow->prc_date))?formatDate($dataRow->prc_date):((!empty($lineInspData->prc_date))?$lineInspData->prc_date:"")?></td>
                            <td><?=(!empty($dataRow->item_name))?$dataRow->item_name:((!empty($lineInspData->item_name))?$lineInspData->item_name:"")?></td>
                            <td><?=(!empty($dataRow->current_process))?$dataRow->current_process:((!empty($lineInspData->process_name))?$lineInspData->process_name:"")?></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <div class="row">
                    <input type="hidden" name="id" id="id" value="<?=(!empty($lineInspData->id))?$lineInspData->id:""?>" />
                    <input type="hidden" name="prc_id" id="prc_id" value="<?=(!empty($dataRow->prc_id))?$dataRow->prc_id:((!empty($lineInspData->prc_id))?$lineInspData->prc_id:"")?>" />
                    <input type="hidden" name="item_id" id="item_id" value="<?=(!empty($dataRow->item_id))?$dataRow->item_id:((!empty($lineInspData->item_id))?$lineInspData->item_id:"")?>" />
                    <input type="hidden" name="process_id" id="process_id" value="<?=(!empty($dataRow->process_id))?$dataRow->process_id:((!empty($lineInspData->process_id))?$lineInspData->process_id:"")?>" />
                    <input type="hidden" name="report_type" id="report_type" value="1" />
                    
                    <div class="col-md-2 form-group">
                        <label for="insp_date">Date</label>
                        <input type="date" name="insp_date" id="insp_date" class="form-control req" value="<?=(!empty($lineInspData->insp_date))?$lineInspData->insp_date:date('Y-m-d')?>" />
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="insp_time">Time</label>
                        <input type="time" name="insp_time" id="insp_time" class="form-control req" value="<?=(!empty($lineInspData->insp_time))?$lineInspData->insp_time:date('H:i')?>" />
                    </div>  
                    <div class="col-md-3 form-group">
                        <label for="operator_id">Operator</label>
                        <select name="operator_id" id="operator_id" class="form-control select2">
                            <option value="">Select Operator</option>
                            <?php
                                foreach ($operatorList as $row) :
                                    $selected = (!empty($lineInspData->operator_id) && $lineInspData->operator_id == $row->id) ? "selected" : "";
                                    echo '<option value="' . $row->id . '" ' . $selected . '>' . $row->emp_name . '</option>';
                                endforeach;
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="machine_id">Machine</label>
                        <select name="machine_id" id="machine_id" class="form-control select2">
                        <option value="">Select Machine</option>
                            <?php 
                                  foreach ($machineList as $row) :
                                    $selected = (!empty($lineInspData->machine_id) && $lineInspData->machine_id == $row->id) ? "selected" : "";
                                    echo '<option value="' . $row->id . '" ' . $selected . '>' . $row->item_name . '</option>';
                                endforeach;   
                            ?>
                        </select>
                    </div>  
                    <div class="col-md-2 form-group">  
                        <label for="sampling_qty">Sampling Qty.</label>
                        <div class="input-group">
                            <input type="text" name="sampling_qty" id="sampling_qty" class="form-control floatOnly " value="<?=(!empty($lineInspData->sampling_qty))?$lineInspData->sampling_qty:""?>" />
                            
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="preDispatchtbl" class="table table-bordered generalTable">
                            <thead class="thead-info" id="theadData">
                                <tr style="text-align:center;">
                                    <th style="width:5%;">#</th>
                                    <th style="width:30%">Parameter</th>
                                    <th style="width:30%">Specification</th>
                                    <th style="width:20%">Instrument</th>
                                    <th style="width:15%">Result</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyData">
                                <?php
                                $i=1;  $tbodyData="";
                                if(!empty($paramData)):
                                    foreach($paramData as $row):
                                        $obj = new StdClass;
                                        if(!empty($lineInspData)):
                                            $obj = json_decode($lineInspData->observation_sample); 
                                        endif;
                                        $tbodyData .= '<tr class="text-center">
                                                <td>'.$i++.'</td>
                                                <td style="width:20px;">'.$row->parameter.'</td>
                                                <td style="width:20px;">'.$row->specification.'</td>
                                                <td style="width:20px;">'.$row->instrument.'</td>';
                                        if(!empty($obj->{$row->id})):
                                            $checked = "";
                                            $checked = !empty($obj->{$row->id}[0])?"checked":'';
                                            if(!empty($row->check_type)){
                                                $tbodyData .= '<td class="text-center"> <input type="checkbox" id="result'.$row->id.'" name="result_'.$row->id.'" class="filled-in chk-col-success"  value="'.$obj->{$row->id}[0].'" '.$checked.'><label for="result'.$row->id.'" class="mr-3"></label></td>';
                                                
                                            }else{
                                                $tbodyData .= '<td style="width:150px;"><input type="text" name="result_'.$row->id.'" class="form-control text-center"  value="'.$obj->{$row->id}[0].'"></td></tr>';
                                            }
                                        else:
                                            if(!empty($row->check_type)){
                                                $tbodyData .= '<td class="text-center"> <input type="checkbox" id="result'.$row->id.'" name="result_'.$row->id.'" class="filled-in chk-col-success" value="1"><label for="result'.$row->id.'" class="mr-3"></label></td>';
                                            }else{
                                                $tbodyData .= '<td style="width:150px;"><input type="text" name="result_'.$row->id.'" class="form-control text-center value=""></tr>';
                                            }
                                        endif;
                                    endforeach;
                                else:
                                    $tbodyData .=  '<td class="text-center" colspan="11">No data available.</td>';
                                endif;
                                echo $tbodyData;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>