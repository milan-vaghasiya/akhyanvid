<form>
    <div class="col-md-12">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-info">
                        <tr class="text-center">
                            <th style="width:70%">Complaint File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=1;
                        if (!empty($complaint_file)) {
                             $custFiles = explode(',',$complaint_file);
                            foreach ($custFiles as $key=>$val) {
                                 $filePath = base_url('assets/uploads/cust_complaint/'.$val);
                                echo '<tr class="text-center">
                                        <td>
                                            <a href="'.$filePath.'" target="_blank" download>
                                                <img src="'.$filePath.'" style="width:125px;height:125px;border:1px solid #000;border-radius:10px;">
                                            </a>
                                        </td>
                                    </tr>';
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
