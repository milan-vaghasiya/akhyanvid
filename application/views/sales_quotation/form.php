<?php $this->load->view('includes/header'); ?>

<div class="page-content-tab">
	<div class="container-fluid">
        <div class="row">
            <div class="col-12">
				<div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form autocomplete="off" id="saveSalesQuotation" data-res_function="resSaveQuotation" enctype="multipart/form-data">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="hiddenInput">
                                            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow[0]->id))?$dataRow[0]->id:""?>">
                                            <input type="hidden" name="trans_no" id="trans_no" value="<?=(!empty($dataRow[0]->trans_no))?$dataRow[0]->trans_no:((!empty($trans_no))?$trans_no:"")?>">
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="trans_number">SQ. No.</label>
                                            <input type="text" name="trans_number" id="trans_number" class="form-control" value="<?=(!empty($dataRow[0]->trans_number))?$dataRow[0]->trans_number:((!empty($trans_number))?$trans_number:"")?>" readonly>
                                        </div>

                                        <div class="col-md-2 form-group">
                                            <label for="trans_date">SQ. Date</label>
                                            <input type="date" name="trans_date" id="trans_date" class="form-control" value="<?=(!empty($dataRow[0]->trans_date))?$dataRow[0]->trans_date:getFyDate()?>">
                                        </div>

                                        <div class="col-md-5 form-group">
                                            <label for="party_id">Customer Name</label>
                                            <select name="party_id" id="party_id" class="form-control basic-select2  req">
                                                <option value="">Select Party</option>
                                                <?=getPartyListOption($partyList,((!empty($dataRow[0]->party_id))?$dataRow[0]->party_id:0))?>
                                            </select>
                                        </div>

                                        <div class="col-md-3 form-group ">
                                            <label for="project_type">Project Type</label>
                                            <select name="project_type" id="project_type" class="form-control basic-select2">
                                                <option value="1" <?=(!empty($dataRow->project_type) && $dataRow->project_type == "1") ? "selected" : "";?>>Automation</option>
                                                <option value="2" <?=(!empty($dataRow->project_type) && $dataRow->project_type == "2") ? "selected" : "";?>>Theater</option>
                                            </select>
                                        </div>

										<div class="col-md-12 form-group">
                                            <label for="description">Project Description</label>
                                            <input type="text" id="description" name="description" class="form-control" value="<?=(!empty($dataRow[0]->description)) ? $dataRow[0]->description:""?>" >
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="col-md-12" id="itemForm">
                                        <div class="row form-group">
                                            <div id="itemInputs">
                                                <input type="hidden" id="trans_id" class="itemFormInput" value="" />
                                                <input type="hidden" id="row_index" class="itemFormInput" value="">
                                                <input type="hidden" id="item_code" class="itemFormInput" value="" />
                                                <input type="hidden" id="item_name" class="itemFormInput" value="" />
                                                <input type="hidden" id="item_class" class="itemFormInput" value="" />
                                            </div>

                                            <div class="col-md-4 form-group">
                                                <label for="item_id">Product Name</label>
                                               
                                                <select id="item_id" class="form-control basic-select2 itemDetails itemOptions itemFormInput partyReq" data-res_function="resItemDetail" data-item_type="1">
                                                    <option value="">Select Product Name</option>
                                                    <?=getItemListOption($itemList); ?>
                                                </select>
                                            </div>
                                             
                                            <div class="col-md-2 form-group">
                                                <label for="quot_option">Quotation Option</label>
                                                <select id="quot_option" class="form-control basic-select2 itemFormInput req">
                                                    <option value="">Select</option>
                                                    <option value="Option-1">Option-1</option>
                                                    <option value="Option-2">Option-2</option>
                                                    <option value="Option-3">Option-3</option>
                                                    <option value="Back End">Back End</option>
                                                    
                                                </select>
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label for="qty">QTY/Percentage</label>
                                                <input type="text" id="qty" class="form-control floatOnly req itemFormInput" value="0">
                                            </div>
                                                                                       
                                            <div class="col-md-2 form-group">
                                                <label for="price">Price</label>
                                                <input type="text" id="price" class="form-control floatOnly  itemFormInput" value="0" />
                                            </div>
                                            <div class="col-md-1 form-group">
                                                <label for="">&nbsp;</label>
                                                <button type="button" class="btn btn-outline-success btn-block saveItem" style="line-height: 1.8;"><i class="fa fa-plus"></i> Add</button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12 mt-3">
                                        <div class="error itemData"></div>
                                        <div class="row form-group">
                                            <div class="table-responsive">
                                                <table id="salesQuotationItems" class="table table-striped table-borderless">
                                                    <thead class="thead-dark">
                                                         <tr>
                                                            <th style="width:5%;">#</th>
                                                            <th>Item Name</th>
                                                            <th>Quotation Option</th>
                                                            <th>Qty/Percentage</th>
                                                            <th>Price</th>
                                                            <th>Amount </th>
                                                            <th class="text-center" style="width:10%;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tempItem" class="temp_item">
                                                        <tr id="noData">
                                                            <td colspan="7" class="text-center">No data available in table</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="card-footer bg-facebook">
                                        <div class="col-md-12"> 
                                            <button type="button" class="btn btn-success waves-effect show_terms" >Terms & Conditions</button>
                                            <span class="term_error text-danger font-bold"></span>

                                            <button type="button" class="btn waves-effect waves-light btn-success float-right save-form" onclick="customStore({'formId':'saveSalesQuotation','txt_editor':'conditions'});" ><i class="fa fa-check"></i> Save</button>
                                            <button type="button" class="btn btn-secondary press-close-btn btn-close-modal save-form float-right m-r-10" onclick="window.location.href='<?=base_url($headData->controller)?>'"><i class="fa fa-times"></i> Cancel</button>
                                        </div>
                                        <?php $this->load->view('includes/terms_form',['termsList'=>$termsList,'termsConditions'=>(!empty($dataRow[0]->conditions)) ? $dataRow[0]->conditions : array()])?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('includes/footer'); ?>

<script src="<?=base_url('assets/js/custom/sales-quotation-form.js')?>"></script>
<script src="<?=base_url();?>assets/js/custom/calculate.js?v<?=time()?>"></script>

<script src="<?=base_url('assets/plugins/tinymce/tinymce.min.js')?>"></script>
<script>
$(document).ready(function(){
	initEditor({
		selector: '#conditions',
		height: 400
	});
});
</script>

<?php

if(!empty($dataRow)):
    foreach($dataRow as $row):
        $row->row_index = "";
        echo '<script>AddRow('.json_encode($row).');</script>';
    endforeach;
endif;
?>