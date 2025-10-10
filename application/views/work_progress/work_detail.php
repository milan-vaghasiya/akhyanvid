<form >
    <div class="col-md-12">
         <div class="row">
            <div class="error general_error"></div>
            <div class="table-responsive">
                <table id='workTable' class="table table-bordered table-striped">
                    <thead class="thead-info"> 
                        <tr>
                            <th class="text-center" style="width:25%;" > Step No .<?=$step_no?> </th>
                        </tr>
                    </thead>
                    <tbody id="tbodyData">
                        <?php 
                            $html = "";  $i = 1; $groupedData = [];
                            foreach ($workList as $row) {
                                $groupedData[$row->work_title][] = $row;
                            }
                           
                            foreach ($groupedData as $workTitle => $instructions) {
                                echo  '<tr class="text-center ">
                                            <th class="text-center bg-light-grey" style="width:25%;" >'.$row->work_title.'</th>
                                        </tr>';

                                foreach ($instructions as $row) {
                                echo  '<tr> <td>' . $row->description . '</td></tr>';
                                    $i++;
                                }
                            }
                        ?>
                   
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
