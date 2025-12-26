<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="project_id" id="project_id" value="<?=(!empty($project_id) ? $project_id :'')?>">

            <div class="col-md-3 form-group">
                <label for="step_no">Step</label>
                <select name="step_no" id="step_no" class="form-control basic-select2"> 
                <option value="N/A" >N/A</option>
                    <?php
                        if(!empty($workStepData)){
                            foreach ($workStepData as $row) { 
                                echo '<option value="'.$row->work_title.'" >'.$row->work_title.'</option>'; 
                            }
                        }
                    ?>
                </select>
            </div>
             <div class="col-md-9 form-group">
                <label for="notes">Notes</label>
                <input type ="text" name="notes" id="notes" class="form-control" value=""></input>
            </div>
        </div>
       
        <hr>
        <div class="row">
            <div class="error general_error"></div>
            <div class="table-responsive">
                <table id='workTable' class="table table-bordered table-striped">
                    <tbody id="tbodyData">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>


<script>
$(document).ready(function() {

    setTimeout(function(){$("#step_no").trigger('change');},500);

    $(document).on('click', '.workInstruction', function() {
        if ($(this).attr('id') == "masterSelect") {
            if ($(this).prop('checked') == true) {
                $("input[name='work_id[]']").prop('checked', true);
            } else {
                $("input[name='work_id[]']").prop('checked', false);
            }
        } else {
            if ($("input[name='work_id[]']").not(':checked').length != $("input[name='work_id[]']").length) {
                $("#masterSelect").prop('checked', false);
            } else {                
            }
            if ($("input[name='work_id[]']:checked").length == $("input[name='work_id[]']").length) {
                $("#masterSelect").prop('checked', true);
            }
            else{$("#masterSelect").prop('checked', false);}
        }
    });   
    
    $(document).on('change', '#step_no', function (e){
    e.stopImmediatePropagation();e.preventDefault();
        var step_no = $('#step_no').val();
        var project_id = $('#project_id').val();

        if(step_no){
            $.ajax({
                url:base_url + controller + "/getWorkProgressData",
                type:'post',
                data:{step_no:step_no,project_id:project_id},
                dataType:'json',
                success:function(data){
                    if(data.status == 1){ 
                        initTable();
                        $('#tbodyData').html('');
                        $('#tbodyData').html(data.tbodyData);
                    }
                }
            });
        }
    });
    
});
</script>