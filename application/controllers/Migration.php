<?php
class Migration extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }

    /* public function addColumnInTable(){
        $result = $this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'ascent' AND TABLE_NAME NOT IN ( SELECT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = 'updated_at' AND TABLE_SCHEMA = 'ascent' )")->result();


        foreach($result as $row):
            if(!in_array($row->TABLE_NAME,["instrument"])):
                $this->db->query("ALTER TABLE ".$row->TABLE_NAME." ADD `updated_at` INT NOT NULL DEFAULT '0' AFTER `updated_by`;");
            endif;
        endforeach;

        echo "success";exit;
    } */

    public function defualtLedger(){
        $accounts = [
            ['name' => 'Sales Account', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESACC'],
            
            ['name' => 'Sales Account GST', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESGSTACC'],

            ['name' => 'Sales Account IGST', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESIGSTACC'],

            ['name' => 'Sales Account Tax Free', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESTFACC'],

            ['name' => 'Exempted Sales (Nill Rated)', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESEXEMPTEDTFACC'],

            ['name' => 'Sales Account GST JOBWORK', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESJOBGSTACC'],

            ['name' => 'Sales Account IGST JOBWORK', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SALESJOBIGSTACC'],

            ['name' => 'Export With Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'EXPORTGSTACC'],

            ['name' => 'Export Without Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'EXPORTTFACC'],

            ['name' => 'SEZ Supplies With Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SEZSGSTACC'],

            ['name' => 'SEZ Supplies Without Payment', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'SEZSTFACC'],

            ['name' => 'Deemed Export', 'group_name' => 'Sales Account', 'group_code' => 'SA', 'system_code' => 'DEEMEDEXP'],
            
            ['name' => 'CGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'CGSTOPACC'],
            
            ['name' => 'SGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'SGSTOPACC'],
            
            ['name' => 'IGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'IGSTOPACC'],
            
            ['name' => 'UTGST (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'UTGSTOPACC'],
            
            ['name' => 'CESS (O/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TCS ON SALES', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'Purchase Account', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURACC'],
            
            ['name' => 'Purchase Account GST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURGSTACC'],

            ['name' => 'Purchase Account IGST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURIGSTACC'],

            ['name' => 'Purchase Account URD GST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURURDGSTACC'],

            ['name' => 'Purchase Account URD IGST', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURURDIGSTACC'],

            ['name' => 'Purchase Account Tax Free', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURTFACC'],

            ['name' => 'Exempted Purchase (Nill Rated)', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PUREXEMPTEDTFACC'],

            ['name' => 'Purchase Account GST JOBWORK', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURJOBGSTACC'],

            ['name' => 'Purchase Account IGST JOBWORK', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'PURJOBIGSTACC'],

            ['name' => 'Import', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'IMPORTACC'],

            ['name' => 'Import of Services', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'IMPORTSACC'],

            ['name' => 'Received from SEZ', 'group_name' => 'Purchase Account', 'group_code' => 'PA', 'system_code' => 'SEZRACC'],
            
            ['name' => 'CGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'CGSTIPACC'],
            
            ['name' => 'SGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'SGSTIPACC'],
            
            ['name' => 'IGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'IGSTIPACC'],
            
            ['name' => 'UTGST (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => 'UTGSTIPACC'],
            
            ['name' => 'CESS (I/P)', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TCS ON PURCHASE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TDS PAYABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'TDS RECEIVABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'GST PAYABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'GST RECEIVABLE', 'group_name' => 'Duties & Taxes', 'group_code' => 'DT', 'system_code' => ''],
            
            ['name' => 'ROUNDED OFF', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => 'ROFFACC'],
            
            ['name' => 'CASH ACCOUNT', 'group_name' => 'Cash-In-Hand', 'group_code' => 'CS', 'system_code' => 'CASHACC'],
            
            ['name' => 'ELECTRICITY EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'OFFICE RENT EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'GODOWN RENT EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'TELEPHONE AND INTERNET CHARGES', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'PETROL EXP', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'SALES INCENTIVE', 'group_name' => 'Expenses (Direct)', 'group_code' => 'ED', 'system_code' => ''],
            
            ['name' => 'INTEREST PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'INTEREST RECEIVED', 'group_name' => 'Income (Indirect)', 'group_code' => 'II', 'system_code' => ''],
            
            ['name' => 'SAVING BANK INTEREST', 'group_name' => 'Income (Indirect)', 'group_code' => 'II', 'system_code' => ''],
            
            ['name' => 'DISCOUNT RECEIVED', 'group_name' => 'Income (Indirect)', 'group_code' => 'II', 'system_code' => ''],
            
            ['name' => 'DISCOUNT PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'SUSPENSE A/C', 'group_name' => 'Suspense A/C', 'group_code' => 'AS', 'system_code' => ''],
            
            ['name' => 'PROFESSIONAL FEES PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'AUDIT FEE', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'ACCOUNTING CHARGES PAID', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'LEGAL FEE', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'SALARY', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'WAGES', 'group_name' => 'Expenses (Direct)', 'group_code' => 'ED', 'system_code' => ''],
            
            ['name' => 'FREIGHT CHARGES', 'group_name' => 'Expenses (Direct)', 'group_code' => 'ED', 'system_code' => ''],
            
            ['name' => 'PACKING AND FORWARDING CHARGES', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'REMUNERATION TO PARTNERS', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'TRANSPORTATION CHARGES', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'DEPRICIATION', 'group_name' => 'Expenses (Indirect)', 'group_code' => 'EI', 'system_code' => ''],
            
            ['name' => 'PLANT AND MACHINERY', 'group_name' => 'Fixed Assets', 'group_code' => 'FA', 'system_code' => ''],
            
            ['name' => 'FURNITURE AND FIXTURES', 'group_name' => 'Fixed Assets', 'group_code' => 'FA', 'system_code' => ''],
            
            ['name' => 'FIXED DEPOSITS', 'group_name' => 'Deposits (Assets)', 'group_code' => 'DA', 'system_code' => ''],
            
            ['name' => 'RENT DEPOSITS', 'group_name' => 'Deposits (Assets)', 'group_code' => 'DA', 'system_code' => '']	            
        ];
        try{
            $this->db->trans_begin();
            $accounts = (object) $accounts;
            foreach($accounts as $row):
                $row = (object) $row;

                $groupData = $this->db->where('group_code',$row->group_code)->get('group_master')->row();

                $ledgerData = [
                    'party_category' => 4,
                    'group_name' => $groupData->name,
                    'group_code' => $groupData->group_code,
                    'group_id' => $groupData->id,
                    'party_name' => $row->name,                    
                    'system_code' => $row->system_code
                ];

                $this->db->where('party_name',$row->name);
                $this->db->where('is_delete',0);
                $this->db->where('party_category',4);
                $checkLedger = $this->db->get('party_master');

                if($checkLedger->num_rows() > 0):
                    $id = $checkLedger->row()->id;
                    $this->db->where('id',$id);
                    $this->db->update('party_master',$ledgerData);
                else:
                    $this->db->insert('party_master',$ledgerData);
                endif;
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Defualt Ledger Migration Success.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }
    
    public function updateLedgerClosingBalance(){
        try{
            $this->db->trans_begin();

            $partyData = $this->db->where('is_delete',0)->get("party_master")->result();
            foreach($partyData as $row):
                //Set oprning balance as closing balance
                $this->db->where('id',$row->id);
                $this->db->update('party_master',['cl_balance'=>'opening_balance']);

                //get ledger trans amount total
                $this->db->select("SUM(amount * p_or_m) as ledger_amount");
                $this->db->where('vou_acc_id',$row->id);
                $this->db->where('is_delete',0);
                $ledgerTrans = $this->db->get('trans_ledger')->row();
                $ledgerAmount = (!empty($ledgerTrans->ledger_amount))?$ledgerTrans->ledger_amount:0;

                //update colsing balance
                $this->db->set("cl_balance","`cl_balance` + ".$ledgerAmount,FALSE);
                $this->db->where('id',$row->id);
                $this->db->update('party_master');
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Closing Balance Migration Success.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }

    /* public function importItemMaster(){
        try{
            $this->db->trans_begin();

            $this->db->select("kuber_item_master.*,(CASE WHEN item_type_name IN ('Finish Product','Semi Finish') THEN 1 WHEN item_type_name = 'Raw Material' THEN 3 WHEN item_type_name = 'Packing' THEN 2 ELSE 0 END) as item_type,(CASE WHEN item_type_name = 'Finish Product' THEN 12 WHEN item_type_name = 'Semi Finish' THEN 41 WHEN item_type_name = 'Raw Material' THEN 37 WHEN item_type_name = 'Packing' THEN 18 ELSE 0 END) as category_id,(CASE WHEN unit_name = 'Box' THEN 6 WHEN unit_name = 'Bunch' THEN 8 WHEN unit_name = 'Kg' THEN 21 WHEN unit_name = 'Nog.' THEN 25 WHEN unit_name = 'pouche' THEN 26 ELSE 0 END) as unit_id");
            $this->db->where('import_status',0);
            $this->db->where_not_in("item_type_name",["Wastage","Machinery"]);
            $result = $this->db->get('kuber_item_master')->result();

            $i=1;
            foreach($result as $row):
                $itemData = [
                    'item_name' => $row->item_name,
                    'item_code' => $row->item_code,
                    'hsn_code' => "",
                    'gst_per' => $row->gst_per,
                    'unit_id' => $row->unit_id,
                    'category_id' => $row->category_id,
                    'item_type' => $row->item_type,
                    'price' => $row->price,
                    'mrp' => $row->mrp,
                    'description' => $row->description
                ];

                $this->db->insert('item_master',$itemData);

                $this->db->where('id',$row->id)->update('kuber_item_master',['import_status'=>1]);
                $i++;
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Item Master import Successfully. no. of items : ".$i;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }

    public function importPartyMaster(){
        try{
            $this->db->trans_begin();

            $this->db->select("kuber_party_master.*,group_master.group_code,group_master.name as group_name,group_master.id as group_id,cities.id as city_id,cities.state_id,cities.country_id");
            $this->db->join('group_master','group_master.group_code = kuber_party_master.group_code','left');
            $this->db->join('cities','(LOWER(cities.name) = LOWER(kuber_party_master.city_name))','left',false);
            $this->db->where('kuber_party_master.import_status',0);
            $this->db->where_in("kuber_party_master.group_code",["SD","SC"]);
            $this->db->limit(500, 6000);
            $result = $this->db->get('kuber_party_master')->result();

            //print_r($this->db->last_query());exit;

            $i=1;
            foreach($result as $row):
                $party_address = [];
                if(!empty($row->address1)): $party_address[] = $row->address1; endif; 
                if(!empty($row->address2)): $party_address[] = $row->address2; endif; 
                if(!empty($row->address3)): $party_address[] = $row->address3; endif; 

                if(!empty($party_address)):
                    $party_address = implode(", ",$party_address);
                else:
                    $party_address = "";
                endif;
                //print_r($row->address1);print_r("<hr>");

                $row->city_id = (!empty($row->city_id))?$row->city_id:0;
                $row->state_id = (!empty($row->state_id))?$row->state_id:0;
                $row->country_id = (!empty($row->country_id))?$row->country_id:0;

                if($row->group_code == "SD"):
                    $row->party_category = 1;
                elseif($row->group_code == "SC"):
                    $row->party_category = 2;
                else:
                    $row->party_category = 4;
                endif;

                $partyData = [
                    'count' => $i,
                    'party_code' => $row->party_code,
                    'party_name' => $row->party_name,
                    'party_category' => $row->party_category,
                    'contact_person' => $row->contact_person,
                    'party_mobile' => $row->party_mobile,
                    'party_email' => $row->party_email,
                    'credit_days' => $row->credit_days,
                    'gstin' => $row->gstin,
                    'pan_no' => $row->pan_no,
                    'group_id' => $row->group_id,
                    'group_name' => $row->group_name,
                    'group_code' => $row->group_code,
                    'party_address' => $party_address,
                    'party_pincode' => $row->party_pincode,
                    'city_id' => $row->city_id,
                    'state_id' => $row->state_id,
                    'country_id' => $row->country_id,
                    'opening_balance' => $row->opening_balance
                ];

                print_r($partyData);print_r("<hr>");
                //$this->db->insert('party_master',$partyData);

                //$this->db->where('id',$row->id)->update('kuber_party_master',['import_status'=>1]);
                $i++;
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Party Master import Successfully. no. of items : ".$i;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }

    public function kuberPartyMaster(){
        try{
            $this->db->trans_begin();
            
            $postData = $this->input->post();

            $i=1;
            foreach($postData['data'] as $row):
                $row = (object) $row; 
                $partyData = [
                    'party_code' => "",
                    'party_name' => $row->AccountName,
                    'contact_person' => $row->ContactPerson,
                    'party_mobile' => $row->MobileNo,
                    'party_email' => $row->Email,
                    'credit_days' => $row->CreditDays,
                    'gstin' => $row->GSTIN,
                    'pan_no' => $row->PANno,
                    'group_name' => $row->AccountGroup,
                    'group_code' => $row->AccountGroupType,
                    'address1' => $row->AddressLine1,
                    'address2' => $row->AddressLine2,
                    'address3' => $row->AddressLine3,
                    'party_pincode' => $row->Pincode,
                    'city_name' => $row->CityName,
                    'opening_balance' => $row->closingBalance
                ];

                $this->db->insert('kuber_party_master',$partyData);
                $i++;
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Party Master import Successfully. no. of party : ".$i;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    } */

    /* public function importItemMaster(){
        try{
            $this->db->trans_begin();

            $this->db->select("items.*,item_category.id as category_id,item_master.id as item_id,unit_master.id as unit_id,ps.id as packing_standard_id");
            $this->db->join("item_category","item_category.category_name = items.category_name","left");
            $this->db->join("item_master","item_master.item_name = items.item_name AND item_master.category_id = item_category.id","left");
            $this->db->join("unit_master","unit_master.unit_name = items.unit_name","left");
            $this->db->join("packing_standard as ps","CONCAT('[ ',ROUND(ps.packing_qty,0),' - ',ps.packing_unit,' ] ',ps.packing_in) = items.packing_std","left");
            $this->db->where('items.import_status',0);
            $result = $this->db->get('items')->result();

            $i=1;
            foreach($result as $row):
                $itemData = [
                    'item_name' => $row->item_name,
                    'item_code' => $row->item_code,
                    'full_name' => $row->item_code.' - '.$row->item_name,
                    'item_type' => 1,
                    'hsn_code' => $row->hsn_code,
                    'gst_per' => $row->gst_per,
                    'unit_id' => $row->unit_id,
                    'category_id' => $row->category_id,
                    'packing_standard' => $row->packing_standard_id
                ];

                $this->db->where('hsn',$row->hsn_code);
                $hsnData = $this->db->get('hsn_master')->row();
                if(empty($hsnData)):
                    $hsnCodeData = [
                        'type' => "HSN",
                        'hsn' => $row->hsn_code,
                        'cgst' => round(($row->gst_per/2),2),
                        'sgst' => round(($row->gst_per/2),2),
                        'igst' => round($row->gst_per,2),
                        'gst_per' => round($row->gst_per,2),
                    ];

                    $this->db->insert("hsn_master",$hsnCodeData);
                endif;

                if(empty($row->item_id)):
                    $this->db->insert('item_master',$itemData);

                    $this->db->where('id',$row->id)->update('items',['import_status'=>$this->db->insert_id()]);
                else:
                    $this->db->where('id',$row->item_id);
                    $this->db->update('item_master',$itemData);

                    $this->db->where('id',$row->id)->update('items',['import_status'=>$row->item_id]);
                endif;
                $i++;
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                echo "Item Master import Successfully. no. of items : ".$i;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    } */

    /* NYN Migration/migrateOpBalancePartyData */
    /* public function migrateOpBalancePartyData(){
        try{
            $this->db->trans_begin();
            
            $this->db->reset_query();
            $this->db->where('is_delete',0);
            $this->db->where('party_id',0);
            $opData = $this->db->get('opening_balance')->result();
            
			$i=0;
            foreach($opData as $row):
                $this->db->reset_query();
			    $this->db->where('is_delete',0);
			    $this->db->where('party_category !=',4);
                $this->db->where('party_name',trim($row->party_name));
                $partyData = $this->db->get('party_master')->row();
                
                if(!empty($partyData->id)):
                    $updateData = [
						'party_id' => $partyData->id,
						'system_code' => $partyData->system_code,
						'group_id' => $partyData->group_id
					];
					$this->db->reset_query();
                    $this->db->where('id',$row->id);
                    $this->db->update('opening_balance',$updateData);
					
					$i++;
                endif;
            endforeach; 
            exit;
            if($this->db->trans_status() !== FALSE):
                //$this->db->trans_commit();
                echo "Opening Balance PartyData Migrate Successfully. ".$i;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    } */

    /* public function migrateBillWise(){
        try{
            $this->db->trans_begin();

            $this->db->reset_query();
            $this->db->where('op_balance <>', 0);
            $this->db->where('is_delete',0);
            $opBalanceData = $this->db->get('party_balance')->result();

            foreach($opBalanceData as $row):
                //Save Bill Wise New Reference
				$transBillWiseData = [
                    'id'=>"",
                    'entry_type'=>0,
                    'trans_main_id'=>0,
                    'trans_date'=>"2024-04-01",
                    'trans_number'=>"OpBal",
                    'party_id'=>$row->party_id,
                    'amount'=>abs($row->op_balance),
                    'c_or_d'=>(($row->op_balance > 0)?"CR":"DR"),
                    'p_or_m'=>(($row->op_balance > 0)?1:-1),
                    'ref_type'=>1,
                    'cm_id'=>$row->cm_id
                ];

				$this->db->insert("trans_billwise",$transBillWiseData);
            endforeach;

            $this->db->reset_query();
            $this->db->where('is_delete',0);
            $this->db->where_in('vou_name_s',["Purc","Sale","C.N.","D.N.","GExp","GInc","BCRct","BCPmt"]);
            $transactions = $this->db->get('trans_main')->result();

            foreach($transactions as $row):
                //Save Bill Wise New Reference
				$transBillWiseData = [
                    'id'=>"",
                    'entry_type'=>$row->entry_type,
                    'trans_main_id'=>$row->id,
                    'trans_date'=>$row->trans_date,
                    'trans_number'=>$row->trans_number,
                    'party_id'=>$row->party_id,
                    'amount'=>$row->net_amount,
                    'ref_type'=>1,
                    'cm_id'=>$row->cm_id
                ];

                $cord = getCrDrEff($row->vou_name_s);
                if(in_array($row->vou_name_s,["BCRct","BCPmt"])):
                    $transBillWiseData['c_or_d'] = $cord['opp_type'];                    
                    $transBillWiseData['amount'] = ($row->round_off_amount <> 0)?($row->net_amount + $row->round_off_amount):$row->net_amount;
                else:
                    $transBillWiseData['c_or_d'] = $cord['vou_type'];
                    $transBillWiseData['amount'] = ($row->tds_amount <> 0)?($row->net_amount - abs($row->tds_amount)):$row->net_amount;
                endif;

                $transBillWiseData['p_or_m'] = ($transBillWiseData['c_or_d'] == "DR")?-1:1;

				$this->db->insert("trans_billwise",$transBillWiseData);
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                //$this->db->trans_rollback();
                echo "Bill Wise new ref. added Successfully.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    } */

    /* public function removeStock(){
        try{
            $this->db->trans_begin();

            $this->db->select("item_id, SUM(qty * p_or_m) as qty, SUM(strip_qty * p_or_m) as strip_qty, SUM(total_box * p_or_m) as total_box, unique_id, batch_no,  location_id, cm_id");
            $this->db->where('is_delete',0);
            $this->db->group_by("item_id,unique_id,batch_no,location_id,cm_id");
            $this->db->having("SUM(stock_transaction.qty * stock_transaction.p_or_m) > 0");
            $this->db->order_by('item_id');
            $result = $this->db->get("stock_transaction")->result();

            foreach($result as $row):
                //print_r($row);print_r('<hr>');

                $stData = [
                    'id' => '',
                    'entry_type' => 999,
                    'unique_id' => $row->unique_id,
                    'ref_date' => date("Y-m-d"),
                    'main_ref_id' => 0,
                    'location_id' => $row->location_id,
                    'batch_no' => $row->batch_no,
                    'item_id' => $row->item_id,
                    'p_or_m' => -1,
                    'total_box' => $row->total_box,
                    'strip_qty' => $row->strip_qty,
                    'qty' => $row->qty,
                    'cm_id' => $row->cm_id,
                    'remark' => 'MIGRATION',
                ];
                //print_r($stData);print_r('<hr>');

                $this->db->insert("stock_transaction",$stData);
            endforeach;

            if($this->db->trans_status() !== FALSE):
                //$this->db->trans_commit();
                $this->db->trans_rollback();
                echo "Stock Removed Successfully.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    } */

    /* public function migrateStock(){
        try{
            $this->db->trans_begin();

            $this->db->select("stock_transaction.item_id, ROUND(SUM(stock_transaction.qty * stock_transaction.p_or_m),3) as qty, ROUND(SUM(stock_transaction.strip_qty * stock_transaction.p_or_m),2) as strip_qty, ROUND(SUM(stock_transaction.total_box * stock_transaction.p_or_m),2) as total_box, stock_transaction.unique_id, stock_transaction.batch_no,  stock_transaction.location_id, stock_transaction.cm_id");
            $this->db->join('item_master','item_master.id = stock_transaction.item_id','left');
            $this->db->where('stock_transaction.is_delete',0);
            $this->db->where('stock_transaction.cm_id',2);
            $this->db->where_in('item_master.item_type',[4]);
            $this->db->group_by("stock_transaction.item_id,stock_transaction.unique_id,stock_transaction.batch_no,stock_transaction.location_id,cm_id");
            $this->db->having("SUM(stock_transaction.qty * stock_transaction.p_or_m) <> 0");
            $this->db->order_by('stock_transaction.item_id');
            $result = $this->db->get("stock_transaction")->result();

            foreach($result as $row):
                $p_or_m = (floatval($row->qty) > 0)?-1:1;
                $stData = [
                    'id' => '',
                    'entry_type' => 999,
                    'ref_no' => 'MIGRATION',
                    'unique_id' => $row->unique_id,
                    'ref_date' => "2024-04-01",//date("Y-m-d"),
                    'main_ref_id' => 0,
                    'location_id' => $row->location_id,
                    'batch_no' => $row->batch_no,
                    'item_id' => $row->item_id,
                    'p_or_m' => $p_or_m,
                    'total_box' => abs($row->total_box),
                    'strip_qty' => abs($row->strip_qty),
                    'qty' => abs($row->qty),
                    'cm_id' => $row->cm_id,
                    'remark' => 'MIGRATION',
                ];

                $this->db->insert("stock_transaction",$stData);
            endforeach;

            if($this->db->trans_status() !== FALSE):
                //$this->db->trans_commit();
                $this->db->trans_rollback();
                echo "Stock migrated Successfully.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    } */

    /* public function invoiceStockMigrate(){
        try{
            $this->db->trans_begin();

            $this->db->select('trans_main.entry_type,trans_child.cm_id,trans_main.trans_date,trans_main.trans_number,trans_child.trans_main_id,trans_child.id,trans_main.party_id,trans_child.item_id,trans_child.qty,trans_child.strip_qty,trans_child.total_box,trans_child.price,trans_child.org_price');
            $this->db->join('trans_main','trans_main.id = trans_child.trans_main_id','left');
            $this->db->where('trans_main.trans_status !=',3);
            $this->db->where('trans_main.entry_type',32);
            $this->db->where('trans_child.ref_id >',0);
            $this->db->where('trans_child.stock_eff',0);
            $this->db->where('trans_child.is_delete',0);
            $result = $this->db->get("trans_child")->result();

            foreach($result as $row):
                $stockData = [
                    'id' => "",
                    'entry_type' => $row->entry_type,
                    'unique_id' => $row->cm_id,
                    'ref_date' => $row->trans_date,
                    'ref_no' => $row->trans_number,
                    'main_ref_id' => $row->trans_main_id,
                    'child_ref_id' => $row->id,
                    'location_id' => 18,
                    'batch_no' => "GB",
                    'party_id' => $row->party_id,
                    'item_id' => $row->item_id,
                    'p_or_m' => -1,
                    'qty' => $row->qty,
                    'strip_qty' => $row->strip_qty,
                    'total_box' => $row->total_box,
                    'price' => $row->price,
                    'mrp' => $row->org_price,
                    'cm_id' => $row->cm_id
                ];

                $this->db->insert("stock_transaction",$stockData);

                $this->db->where('id',$row->id)->update('trans_child',['stock_eff'=>1]);
            endforeach;            

            if($this->db->trans_status() !== FALSE):
                //$this->db->trans_commit();
                $this->db->trans_rollback();
                echo "Stock migrated Successfully.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    } */

    public function semiFinishMigration(){
        try{
            $this->db->trans_begin();

            $this->db->select('id,entry_type');
            $this->db->where_in('item_id',[430,431,432,434,435,436,437,438,439,440,441,442,443,444,445,446,447,448,452]);
            $this->db->where('ref_no IS NULL');
            $this->db->where('cm_id',2);
            $result = $this->db->get('stock_transaction')->result();

            foreach($result as $row):
                $this->db->select('GROUP_CONCAT(id) as id');
                $this->db->where('main_ref_id',$row->id);
                $this->db->where('entry_type',$row->entry_type);
                $this->db->where('cm_id',2);
                $this->db->group_by('main_ref_id');
                $bomItem = $this->db->get('stock_transaction')->row();

                /* print_r($row);
                print_r("<br>*****************************<br>");
                print_r($bomItem);
                print_r("<br>*****************************<br>");
                print_r("<hr>"); */
                $this->db->where_in('id',[$bomItem->id])->delete('stock_transaction');

                $this->db->where('id',$row->id)->delete('stock_transaction');
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                //$this->db->trans_rollback();
                echo "Stock migrated Successfully.";
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }

    public function updateItemMaster(){
        try{
            $this->db->trans_begin();

            $this->db->select("item_master.id,item_master.item_name,item_master.category_name,item_category.id as category_id");
            $this->db->join("item_category","item_category.category_name = item_master.category_name","left");
            $this->db->order_by('id','asc');
            $result = $this->db->get('item_master')->result();
            $i=1;
            foreach($result as $row):
                $this->db->where('id',$row->id)->update('item_master',['item_type' => 1,'category_id'=>$row->category_id]);
                $i++;
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                
                echo "Category updated Successfully. no. of items : ".$i;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            echo $e->getMessage();exit;
        }
    }
}
?>