<form>
    <div class="col-md-12">
        <div class="row">
            <input type="hidden" name="id" id="id" value="<?=(!empty($dataRow->id))?$dataRow->id:""; ?>" />  
			<input type="hidden" name="party_type" id="party_type" value="<?=(!empty($dataRow->party_type))?$dataRow->party_type:$party_type?>">
			<input type="hidden" name="party_category" id="party_category" value="<?=(!empty($dataRow->party_category))?$dataRow->party_category:$party_category?>">
        
            <div class="col-md-12 form-group">
                <label for="party_name">Party Name</label>
                <input type="text" name="party_name" id="party_name" class="form-control text-capitalize req" value="<?=(!empty($dataRow->party_name))?$dataRow->party_name:""; ?>" />
            </div>

            <div class="col-md-4 form-group">
                <label for="business_type">Business Type</label>
                <select name="business_type" id="business_type" class="form-control  basic-select2">
                    <option value = "">Select Business Type</option>
                    <option value="Builder" <?=(!empty($dataRow->business_type) && $dataRow->business_type == "Builder")?"selected":""?>>Builder</option>
                    <option value="Individual" <?=(!empty($dataRow->business_type) && $dataRow->business_type == "Individual")?"selected":""?>>Individual</option>
					<option value="Commercial" <?=(!empty($dataRow->business_type) && $dataRow->business_type == "Commercial")?"selected":""?>>Commercial</option>
                </select>
            </div>
            
            <div class="col-md-4 form-group">
                <label for="executive_id">Sales Executive</label>
                <select name="executive_id" id="executive_id" class="form-control  basic-select2">
                    <option value = "">Select Executive</option>
                    <?php 
                        foreach($salesExecutives as $row):
							$selected = (!empty($dataRow->executive_id) && $dataRow->executive_id == $row->id)?"selected":"";
							echo '<option value="'.$row->id.'" '.$selected.'>'.$row->emp_name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            
            <div class="col-md-4 form-group">
                <label for="contact_person">Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" class="form-control text-capitalize" value="<?=(!empty($dataRow->contact_person))?$dataRow->contact_person:""?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="party_phone">Mobile No.</label>
                <input type="text" name="party_phone" id="party_phone" class="form-control numericOnly req" value="<?=(!empty($dataRow->party_phone))?$dataRow->party_phone:""?>" />
            </div>

            <div class="col-md-3 form-group">
                <label for="whatsapp_no">Whatsapp No.</label>
                <input type="text" name="whatsapp_no" id="whatsapp_no" class="form-control numericOnly" value="<?=(!empty($dataRow->whatsapp_no))?$dataRow->whatsapp_no:""?>" />
            </div>
			
            <div class="col-md-3 form-group">
                <label for="party_email">E-mail</label>
                <input type="email" name="party_email" id="party_email" class="form-control" value="<?=(!empty($dataRow->party_email))?$dataRow->party_email:""; ?>" />
            </div>
          
            <div class="col-md-3 form-group">
                <label for="gstin">GSTIN</label>
                <input type="text" name="gstin" id="gstin" class="form-control text-uppercase" value="<?=(!empty($dataRow->gstin))?$dataRow->gstin:""; ?>" />
            </div>	
            
            <div class="col-md-4 form-group">
                <label for="country_id">Country</label>
                <select name="country_id" id="country_id" class="form-control country_list  basic-select2 req" data-state_id="state_id" data-selected_state_id="<?=(!empty($dataRow->state_id))?$dataRow->state_id:4030?>">
                    <option value="">Select Country</option>
                    <?php 
                        foreach($countryData as $row):
                            $selected = (!empty($dataRow->country_id) && $dataRow->country_id == $row->id)?"selected":((empty($dataRow) && $row->id == "101")?"selected":"");

                            echo '<option value="'.$row->id.'" '.$selected.'>'.$row->name.'</option>';
                        endforeach;
                    ?>
                </select>
            </div>

            <div class="col-md-4 form-group">
                <label for="state_id">State</label>
                <select name="state_id" id="state_id" class="form-control  basic-select2 req">
                    <option value="">Select State</option>
                </select>
            </div>  

            <div class="col-md-4 form-group">
                <label for="city_name">City</label>
                <input type="text" name="city_name" id="city_name" class="form-control  req" value="<?=(!empty($dataRow->city_name))?$dataRow->city_name:""; ?>" />
            </div>

            <div class="col-md-12 form-group">
                <label for="party_address">Address</label>
                <textarea name="party_address" id="party_address" class="form-control req" rows="2"><?=(!empty($dataRow->party_address))?$dataRow->party_address:""?></textarea>
            </div>

        </div>        
    </div>
</form>
<script>
$(document).ready(function(){    
	setTimeout(function(){
        $("#country_id").trigger('change');
    },500);
});
</script>