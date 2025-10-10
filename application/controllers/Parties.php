<?php
class Parties extends MY_Controller{
    private $index = "party/index";
    private $form = "party/form";
    private $ledgerForm = "party/ledger_form";
    private $gstFrom = "party/gst_form";
    private $contactFrom = "party/contact_form";
    private $opbal_index = "party/opbal_index";
    private $excel_upload_form = "party/excel_upload_form"; // 12-04-2024

    public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Party Master";
		$this->data['headData']->controller = "parties";        
    }

    public function list($type="customer"){
        $this->data['headData']->pageUrl = "parties/list/".$type;
        $this->data['type'] = $type;
        $this->data['party_category'] = $party_category = array_search(ucwords($type),$this->partyCategory);
		$this->data['headData']->pageTitle = $this->partyCategory[$party_category];
        $this->data['tableHeader'] = getMasterDtHeader($type);
        $this->load->view($this->index,$this->data);
    }

    public function getDTRows($party_category){
        $data=$this->input->post();$data['party_category'] = $party_category;
        $result = $this->party->getDTRows($data);
        $sendData = array();
        $i = ($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            $row->table_status = $party_category;
            $row->party_category_name = $this->partyCategory[$row->party_category];
            $sendData[] = getPartyData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addParty(){
        $data = $this->input->post();
        $this->data['party_category'] = $data['party_category'];

        $this->data['ledgerList'] = $this->party->getPartyList(['tax_type'=>"'TDS'"]);
        $this->data['tdsClassList'] = $this->party->getTDSClassList();

        if($data['party_category'] != 4):            
            $this->data['currencyData'] = $this->party->getCurrencyList();
            $this->data['countryData'] = $this->party->getCountries();
            $this->data['party_code'] = $this->getPartyCode($data['party_category']);
            $this->data['salesExecutives'] = $this->employee->getEmployeeList();
            $this->data['groupList'] = $this->group->getGroupList(['group_code'=>(($data['party_category'] == 1)?"'SD'":"'SC'")]);
            $this->data['priceStructureList'] = $this->itemPriceStructure->getPriceStructureList();
            $this->load->view($this->form, $this->data);
        else:
            $this->data['groupList'] = $this->group->getGroupList(['not_group_code'=>"'SD','SC'"]);
            $this->data['hsnList'] = $this->hsnModel->getHSNList();
            $this->load->view($this->ledgerForm,$this->data);
        endif;
    }

    /* Auto Generate Party Code */
    public function getPartyCode($party_category=""){
        $partyCategory = (!empty($party_category))?$party_category:$this->input->post('party_category');
        $code = $this->party->getPartyCode($partyCategory);
        $prefix = "AE";
        if($partyCategory == 1):
            $prefix = "C";
        elseif($partyCategory == 2):
            $prefix = "S";
        elseif($partyCategory == 3):
            $prefix = "V";
        endif;

        $party_code = $prefix.sprintf("%03d",$code);

        if(!empty($party_category)):
            return $party_code;
        else:
            $this->printJson(['status'=>1,'party_code'=>$party_code]);
        endif;
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if (empty($data['party_name']))
            $errorMessage['party_name'] = "Company name is required.";

        if (empty($data['party_category']))
            $errorMessage['party_category'] = "Party Category is required.";

        if (empty($data['group_id']))
            $errorMessage['group_id'] = "Group Name is required.";

        if($data['party_category'] != 4):
       
            if (empty($data['gstin']) && in_array($data['registration_type'],[1,2]))
                $errorMessage['gstin'] = 'Gstin is required.';

            if (empty($data['country_id']))
                $errorMessage['country_id'] = 'Country is required.';

            if (empty($data['state_id']))
                $errorMessage['state_id'] = 'State is required.';

            if (empty($data['city_id']))
                $errorMessage['city_id'] = 'City is required.';

            if (empty($data['party_address']))
                $errorMessage['party_address'] = "Address is required.";

            if (empty($data['party_pincode']))
                $errorMessage['party_pincode'] = "Pincode is required.";
                
        endif;

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $data['party_name'] = ucwords($data['party_name']);
            $data['gstin'] = (!empty($data['gstin']))?strtoupper($data['gstin']):"";
            $data['gst_reg_date'] = (!empty($data['gst_reg_date']))?$data['gst_reg_date']:NULL;
            $data['price_structure_id'] = (!empty($data['price_structure_id']))?$data['price_structure_id']:0;
            $this->printJson($this->party->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $result = $this->party->getParty($data);
        $this->data['dataRow'] = $result;

        $this->data['ledgerList'] = $this->party->getPartyList(['tax_type'=>"'TDS'"]);
        $this->data['tdsClassList'] = $this->party->getTDSClassList();

        if($result->party_category != 4):
            $this->data['currencyData'] = $this->party->getCurrencyList();
            $this->data['countryData'] = $this->party->getCountries();
            $this->data['salesExecutives'] = $this->employee->getEmployeeList();
            $this->data['groupList'] = $this->group->getGroupList(['group_code'=>(($result->party_category == 1)?"'SD'":"'SC'")]);      
            $this->data['priceStructureList'] = $this->itemPriceStructure->getPriceStructureList();
            $this->load->view($this->form, $this->data);
        else:
            $this->data['groupList'] = $this->group->getGroupList(['not_group_code'=>"'SD','SC'"]);
            $this->data['hsnList'] = $this->hsnModel->getHSNList();
            $this->load->view($this->ledgerForm,$this->data);
        endif;
    }

    public function delete(){
        $id = $this->input->post('id');
        if (empty($id)) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->party->delete($id));
        endif;
    }

    public function gstDetail(){
        $data = $this->input->post();
        $this->data['party_id'] = $data['id'];
        $this->load->view($this->gstFrom,$this->data);
    }

    public function getPartyGSTDetailHtml(){
        $data = $this->input->post();
        $result = $this->party->getPartyGSTDetail($data);

        $tbodyData = "";$i = 1;        
        if (!empty($result)) :
            foreach ($result as $row) :
                $deleteParam = "{'postData':{'id' : ".$row->id.",'party_id':".$row->party_id."},'message' : 'GST Detail','fndelete':'deleteGstDetail','res_function':'resTrashPartyGstDetail'}";
                $tbodyData .= '<tr>
                    <td>' .  $i++ . '</td>
                    <td>' . $row->gstin . '</td>
                    <td>' . $row->party_address . '</td>
                    <td>' . $row->party_pincode . '</td>
                    <td>' . $row->delivery_address . '</td>
                    <td>' . $row->delivery_pincode . '</td>
                    <td class="text-center">
                        <button type="button" onclick="trash('.$deleteParam.');" class="btn btn-outline-danger waves-effect waves-light btn-delete permission-remove"><i class="ti-trash"></i></button>
                    </td>
                </tr> ';
            endforeach;
        else :
            $tbodyData .= '<tr><td colspan="7" style="text-align:center;">No data available in table</td></tr>';
        endif;
        $this->printJson(['status'=>1,"tbodyData"=>$tbodyData]);
    }

    public function saveGstDetail(){
        $data = $this->input->post();
        $errorMessage = array();

        if (empty($data['gstin']))
            $errorMessage['gstin'] = "GST is required.";
		if (empty($data['party_address']))
            $errorMessage['party_address'] = "Party Address is required.";
        if (empty($data['party_pincode']))
            $errorMessage['party_pincode'] = "Party Pincode is required.";
        if (empty($data['delivery_address']))
            $errorMessage['delivery_address'] = "Delivery Address is required.";
        if (empty($data['delivery_pincode']))
            $errorMessage['delivery_pincode'] = "Delivery Pincode is required.";

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $this->printJson($this->party->saveGstDetail($data));
        endif;
    }

    public function deleteGstDetail(){
        $id = $this->input->post('id');
        if (empty($id)) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->party->deleteGstDetail($id));
        endif;
    }

    public function contactDetail(){
        $data = $this->input->post();
        $this->data['party_id'] = $data['id'];
        $this->load->view($this->contactFrom,$this->data);
    }

    public function getPartyContactDetailHtml(){
        $data = $this->input->post();
        $result = $this->party->getPartyContactDetail($data);

        $tbodyData = "";$i = 1;        
        if (!empty($result)) :
            foreach ($result as $row) :
                $deleteParam = "{'postData':{'id' : ".$row->id.",'party_id':".$row->party_id."},'message' : 'Contact Detail','fndelete':'deleteContactDetail','res_function':'resTrashPartyContactDetail'}";
                $tbodyData .= '<tr>
                    <td>' .  $i++ . '</td>
                    <td>' . $row->contact_person . '</td>
                    <td>' . $row->mobile_no . '</td>
                    <td>' . $row->contact_email . '</td>
                    <td class="text-center">
                        <button type="button" onclick="trash('.$deleteParam.');" class="btn btn-outline-danger waves-effect waves-light btn-delete permission-remove"><i class="ti-trash"></i></button>
                    </td>
                </tr> ';
            endforeach;
        else :
            $tbodyData .= '<tr><td colspan="5" style="text-align:center;">No data available in table</td></tr>';
        endif;
        $this->printJson(['status'=>1,"tbodyData"=>$tbodyData]);
    }

    public function saveContactDetail(){
        $data = $this->input->post();
		$errorMessage = array();

		if(empty($data['person']))
			$errorMessage['person'] = "Contact Person is required.";
        if(empty($data['mobile']))
			$errorMessage['mobile'] = "Contact Mobile is required.";
        if(empty($data['email']))
			$errorMessage['email'] = "Contact Email is required.";
		
		if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:
            $this->printJson($this->party->saveContactDetail($data));
		endif;
    }

    public function deleteContactDetail(){
        $id = $this->input->post('id');
        if (empty($id)) :
            $this->printJson(['status' => 0, 'message' => 'Somthing went wrong...Please try again.']);
        else :
            $this->printJson($this->party->deleteContactDetail($id));
        endif;
    }

    public function getPartyList(){
        $data = $this->input->post();
        $partyList = $this->party->getPartyList($data);
        $this->printJson(['status'=>1,'data'=>['partyList'=>$partyList]]);
    }

    /* Party Opening Balance Start */
    public function opBalIndex(){
        $this->data['grpData'] = $this->group->getGroupList();
        $this->load->view($this->opbal_index,$this->data);
    }

    public function getGroupWiseLedger(){
        $data = $this->input->post();
        $ledgerData = $this->party->getPartyOpBalance(['group_id'=>$data['group_id']]);

        $tbody="";$i=1;
        if(!empty($ledgerData)):
            foreach($ledgerData as $row):         
                $row->opb = $row->op_balance;       
                $crSelected = (!empty($row->op_balance_type) && $row->op_balance_type == "1")?"selected":"";
                $drSelected = (!empty($row->op_balance_type) && $row->op_balance_type == "-1")?"selected":"";

                $row->opBalanceInput = '<div class="input-group">
                    <select name="balance_type[]" id="balance_type_'.$row->id.'" class="form-control" style="width: 20%;">
                        <option value="1" '.$crSelected.'>CR</option>
                        <option value="-1" '.$drSelected.'>DR</option>
                    </select>
                    <input type="text" id="op_balance_'.$row->id.'" name="op_balance[]" class="form-control floatOnly" value="'.floatVal(abs($row->opb)).'" style="width: 40%;" />
                </div>
                <input type = "hidden"  id="party_id_'.$row->id.'" name="party_id[]" value="'.$row->id.'" >' ;                

                $tbody .= '<tr>
                    <td style="width: 5%;">'.$i++.'</td>
                    <td style="width: 25%;">'.$row->account_name.'</td>
                    <td class="text-right" style="width: 10%;" id="cur_op_'.$row->id.'">'.$row->opb.'</td>
                    <td style="width: 20%;">' .$row->opBalanceInput. '</td>
                    <td style="width: 5%;">
                        <button type="button" class="btn btn-success saveOp" datatip="Save" flow="left" data-id="'.$row->id.'"><i class="fa fa-check"></i></button>
                    </td>
                </tr>';
            endforeach;
        /* else:
            $tbody .= '<tr><td class="text-center" colspan="5">No data available in table</td></tr>'; */
        endif;
        $this->printJson(['status'=>1, 'count'=>$i, 'tbody'=>$tbody]);
    }

    public function saveOpeningBalance(){
        $data = $this->input->post();
        $this->printJson($this->party->saveOpeningBalance($data));
    }

    /* Party Opening Balance End */
    
    /* Party Excel Upload */ 
    /* Created By :- Avruti @12-04-2024 */
    public function addPartyExcel(){
        $data = $this->input->post();//print_r($data);exit;
        $this->data['party_category'] = $data['party_category'];
        $this->load->view($this->excel_upload_form,$this->data);
    }

    /* Created By :- Avruti @12-04-2024 */
    public function savePartyExcel(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Enter party detail";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            foreach($data['itemData'] as $row):
                $executiveData = $this->employee->getEmployee(['emp_name'=>$row['sales_executive']]); 

                $countryData = $this->party->getCountry(['name'=>$row['country_id']]);
                $stateData = $this->party->getState(['name'=>$row['state_id']]);
                $cityData = $this->party->getCity(['name'=>$row['city_id']]);

                $dCountryData = $this->party->getCountry(['name'=>$row['delivery_country_id']]);
                $dStateData = $this->party->getState(['name'=>$row['delivery_state_id']]);
                $dCityData = $this->party->getCity(['name'=>$row['delivery_city_id']]);

                $party_code = $this->getPartyCode($row['party_category']);
                $regType = array_search($row['registration_type'],$this->gstRegistrationTypes);

                $row['party_code'] = $party_code;  
                $row['sales_executive'] = !empty($executiveData->id) ? $executiveData->id : 0;

                $row['country_id'] = !empty($countryData->id) ? $countryData->id : 0;
                $row['state_id'] = !empty($stateData->id) ? $stateData->id : 0;
                $row['city_id'] = !empty($cityData->id) ? $cityData->id : 0;

                $row['delivery_country_id'] = !empty($dCountryData->id) ? $dCountryData->id : 0;
                $row['delivery_state_id'] = !empty($dStateData->id) ? $dStateData->id : 0;
                $row['delivery_city_id'] = !empty($dCityData->id) ? $dCityData->id : 0;
                $row['registration_type'] = $regType;

                $result = $this->party->save($row);
            endforeach;
           
            $this->printJson(['status'=>1,'message'=>'Party saved successfully.']);
        endif;
    }

    public function checkPartyDuplicate(){
        $data = $this->input->post();
        $customWhere = "party_name = '".$data['party_name']."' OR party_mobile = '".$data['party_mobile']."'";
        $partyData = $this->party->getParty(['customWhere'=>$customWhere]);
        $this->printJson(['status'=>1,'party_id'=>(!empty($partyData->id)?$partyData->id:"")]);
    }
}
?>