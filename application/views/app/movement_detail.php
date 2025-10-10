<div class="card">
    <div class="media align-items-center btn-group process-tags">
        <span class="badge bg-light-peach btn flex-fill" style="padding:5px">CP : <?=!empty($dataRow->current_process)?$dataRow->current_process:'Initial Stage'?></span>
        <span class="badge bg-light-teal btn flex-fill" style="padding:5px">NP : <?=$dataRow->next_process?></span>
    </div>                                       
</div>

<?php 
    if(!empty($movementData))
    {
        foreach($movementData as $row)
        {
            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Record','fndelete' : 'deletePRCMovement','controller':'sopDesk'}";
			$deleteBtn = '<a class="btn btn-outline-danger btn-sm btn-delete permission-remove" href="javascript:void(0)" onclick="trashSop('.$deleteParam.');" datatip="Remove" flow="down"><i class=" fas fa-trash-alt"></i></a>';

            echo '<div class=" grid_item" style="width:100%;">
                                <div class="card sh-perfect">
                                    <div class="card-body">                                    
                                        <div class="task-box">
                                            <div class="float-end">
                                                '.$deleteBtn.'
                                            </div>                                  
                                            <p class="mb-0 font-13"><span class="fw-semibold">Send To : </span>'.$row->send_to_name.'</p>                                      
                                            <p class="mb-0 font-13"><span class="fw-semibold">Processor : </span>'.$row->processor_name.'</p>                                      
                                            <p class="mb-0 font-13"><span class="fw-semibold">Remark : </span>'.$row->remark.'</p> 

                                            <hr class="hr-dashed mt-1 mb-2  my-5px">
                                            <div class="media align-items-center btn-group process-tags">
                                                <span class="badge bg-light-peach btn flex-fill" style="padding:5px">Date : '.formatDate($row->trans_date).'</span>
                                                <span class="badge bg-light-cream btn flex-fill" style="padding:5px">Qty. : '.floatval($row->qty).'</span>
                                                <span class="badge bg-light-raspberry btn flex-fill" style="padding:5px">Wt/Nos : '.floatval($row->wt_nos).'</span>
                                            </div>                                     
                                        </div>
                                    </div>
                                </div>
                            </div>';
        }
    }
?>