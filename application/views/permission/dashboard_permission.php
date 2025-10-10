<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
    <div class="container-fluid">
        <form id="empDashboardPermission" data-res_function="resDashboardPermission">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="float-start">
                            <ul class="nav nav-pills">
                                <a href="<?= base_url($headData->controller) ?>" class="btn waves-effect waves-light btn-outline-primary  permission-write"> General Permission</a>
                                <a href="<?= base_url($headData->controller . "/reportPermission/") ?>" class="btn waves-effect waves-light btn-outline-warning permission-write"> Report Permission</a>
                                <a href="<?= base_url($headData->controller . "/dashboardPermission/") ?>" class="btn waves-effect waves-light btn-outline-info permission-write active"> Dashboard Permission</a>
                                <!-- <button type="button" class="btn waves-effect waves-light btn-outline-success float-center permission-write" onclick="modalAction({'modal_id' : 'modal-md', 'form_id' : 'copyPermission','call_function':'copyPermission','fnsave':'saveCopyPermission', 'title' : 'Copy Permission','js_store_fn':'confirmStore'});">Copy Permission</button> -->
                                <a href="<?= base_url($headData->controller . "/appPermission/") ?>" class="btn waves-effect waves-light btn-outline-warning permission-write"> App Permission</a>
                            </ul>
                        </div>
                        <div class="float-end" style="width:30%;">
                            <select name="emp_id" id="dashboard_emp_id" class="form-control basic-select2">
                                <option value="">Select Employee</option>
                                <?php
                                    foreach ($empList as $row) :
                                        $empName = (!empty($row->emp_code))?'[' . $row->emp_code . '] ' . $row->emp_name:$row->emp_name;
                                        echo '<option value="' . $row->id . '">' . $empName . '</option>';
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="min-height:75vh">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-left">Widget Name</th>
                                                <th class="text-center">Permission</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach($dashboardPermission as $row):
                                                    echo '<tr>
                                                        <td class="text-left">'.$row->widget_name.'</td>
                                                        <td class="text-center">
                                                            <input type="hidden" name="permission['.$row->id.'][id]" id="permission_'.$row->id.'" class="permissionIdReset" value="">
                                                            <input type="hidden" name="permission['.$row->id.'][widget_id]" value="'.$row->id.'">
                                                            <input type="hidden" name="permission['.$row->id.'][sys_class]" value="'.$row->sys_class.'">

                                                            <input type="checkbox" name="permission['.$row->id.'][is_read]" id="widget_'.$row->id.'" class="filled-in chk-col-success" value="1">
                                                            <label for="widget_'.$row->id.'"></label>
                                                        </td>
                                                    </tr>';
                                                endforeach;
                                            ?>
                                        </tbody>
                                    </table>
                                </div>							
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="bottomBtn bottom-25 right-25 permission-write">
    <button type="button" class="btn btn-primary btn-rounded font-bold permission-write save-form" style="letter-spacing:1px;" onclick="customStore({'formId':'empDashboardPermission','fnsave':'saveDashboardPermission'});">SAVE PERMISSION</button>
</div>

<?php $this->load->view('includes/footer'); ?>
<script src="<?php echo base_url();?>assets/js/custom/emp-permission.js?v=<?=time()?>"></script>
