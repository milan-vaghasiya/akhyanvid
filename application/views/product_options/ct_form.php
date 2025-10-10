<form>
    <div class="col-md-12">
        <div class="row">
            <h6 style="color:#ff0000;font-size:1rem;"><i>Note : Cycle Time Per Piece In Seconds</i></h6>
            <table class="table excel_table table-bordered">
                <thead class="thead-info">
                    <tr>
                        <th style="width:10%;text-align:center;">#</th>
                        <th style="width:30%;">Process Name</th>
                        <th style="width:20%;">Cycle Time</th>
                        <th style="width:20%;">Finished Weight</th>
                        <th style="width:20%;">Conversion Ratio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($processData)) :
                        $i = 1;
                        $html = "";
                        foreach ($processData as $row) :
                            $pid = (!empty($row->id)) ? $row->id : "";
                            $ct = (!empty($row->cycle_time)) ? $row->cycle_time : "";
                            $fgwt = (!empty($row->finish_wt)) ? $row->finish_wt : "";
                            $conv_ratio = (!empty($row->conv_ratio)) ? $row->conv_ratio : "";
                            echo '<tr id="' . $row->id . '">
                                <td class="text-center">' . $i++ . '</td>
                                <td>' . $row->process_name . '</td>
                                <td class="text-center">
                                    <input type="text" name="cycle_time[]" class="form-control numericOnly" step="1" value="' . $ct . '" />
                                    <input type="hidden" name="id[]" value="' . $pid . '" />
                                </td>
                                <td class="text-center">
                                    <input type="text" name="finish_wt[]" class="form-control floatOnly" step="1" value="' . $fgwt . '" />
                                </td> 
                                <td class="text-center">
                                    <input type="text" name="conv_ratio[]" class="form-control floatOnly" step="1" value="' . $conv_ratio . '" />
                                </td>                                 
                              </tr>';
                        endforeach;
                    else :
                        echo '<tr><td colspan="4" class="text-center">No Data Found.</td></tr>';
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</form>
