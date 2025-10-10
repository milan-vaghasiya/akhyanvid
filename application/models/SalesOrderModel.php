<?php
class SalesOrderModel extends MasterModel{
    private $soMaster = "so_master";
    private $soTrans = "so_trans";
    private $transExpense = "trans_expense";
    private $transDetails = "trans_details";
    private $orderBom = "order_bom";
    private $purchseReq = "purchase_request";

    public function getDTRows($data){
        $data['tableName'] = $this->soTrans;
        $data['select'] = "so_trans.id as trans_child_id,so_trans.item_id,so_trans.price,item_master.item_name,so_trans.qty, IFNULL(dt.dispatch_qty, 0.000) AS dispatch_qty, IF((so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) < 0, 0, (so_trans.qty - IFNULL(dt.dispatch_qty, 0.000))) as pending_qty, so_master.id,so_master.trans_number,DATE_FORMAT(so_master.trans_date,'%d-%m-%Y') as trans_date,party_master.party_name,so_trans.trans_status,party_master.sales_executive,so_master.party_id,(CASE WHEN party_master.sales_executive = so_master.party_id THEN 'Client' ELSE 'Office' END) as ordered_by,so_master.is_approve,so_master.attachment_file,item_master.uom,trans_details.t_col_3 as delivery_address"; 

		$data['select'] .= ",(SELECT IFNULL(SUM(qty * p_or_m),0) FROM stock_trans WHERE stock_trans.is_delete = 0 AND so_trans.item_id = stock_trans.item_id GROUP BY stock_trans.item_id) as stock_qty";
		
        $data['leftJoin']['(SELECT SUM(qty) AS dispatch_qty, ref_id FROM trans_child WHERE is_delete = 0 AND from_entry_type = 14 GROUP BY ref_id) AS dt'] = "dt.ref_id = so_trans.id";
		
        $data['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id";
		$data['leftJoin']['item_master'] = "item_master.id = so_trans.item_id";
        $data['leftJoin']['party_master'] = "party_master.id = so_master.party_id";

        $data['leftJoin']['trans_details'] = "so_master.id = trans_details.main_ref_id AND trans_details.description = 'SO MASTER DETAILS' AND trans_details.table_name = '".$this->soMaster."'";

        $data['where']['so_trans.entry_type'] = $data['entry_type'];

        if($data['status'] == 0):
            $data['where']['(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) >'] = 0;
            $data['where']['so_trans.trans_status != '] = 2;
            //$data['where']['so_master.trans_date <='] = $this->endYearDate;
        elseif($data['status'] == 1):
            $data['where']['(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) <='] = 0;
            $data['where']['so_trans.trans_status != '] = 2;
            $data['where']['so_master.trans_date >='] = $this->startYearDate;
            $data['where']['so_master.trans_date <='] = $this->endYearDate;
		elseif($data['status'] == 2):
			$data['where']['so_trans.trans_status'] = 2;
            $data['where']['so_master.trans_date >='] = $this->startYearDate;
            $data['where']['so_master.trans_date <='] = $this->endYearDate;
        endif;

        $data['order_by']['so_master.trans_date'] = "DESC";
        $data['order_by']['so_master.id'] = "DESC";

        $data['group_by'][] = "so_trans.id";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        //$data['searchCol'][] = "(CASE WHEN so_master.sales_executive = so_master.party_id THEN 'Client' ELSE 'Office' END)";
        $data['searchCol'][] = "so_master.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(so_master.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";        
        $data['searchCol'][] = "trans_details.t_col_3";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "so_trans.price";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "so_trans.qty";
        $data['searchCol'][] = "IFNULL(dt.dispatch_qty, 0.000)";
        $data['searchCol'][] = "(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000))";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if($this->checkDuplicate($data) > 0):
                $errorMessage['trans_number'] = "SO. No. is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;

            if(!empty($data['id'])):
                $dataRow = $this->getSalesOrder(['id'=>$data['id'],'itemList'=>1]);
                
				foreach($dataRow->itemList as $row):
                    if(!empty($row->ref_id)):
                        $setData = array();
                        $setData['tableName'] = $this->soTrans;
                        $setData['where']['id'] = $row->ref_id;
                        $setData['update']['trans_status'] = 0;
                        $this->setValue($setData);
                    endif;

                    $this->trash($this->soTrans,['id'=>$row->id]);
                endforeach;

                $this->trash($this->transExpense,['trans_main_id'=>$data['id']]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->soMaster,'description'=>"SO TERMS"]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->soMaster,'description'=>"SO MASTER DETAILS"]);
            endif;
            $masterDetails = $data['masterDetails'];
            $itemData = $data['itemData'];

            $transExp = getExpArrayMap(((!empty($data['expenseData']))?$data['expenseData']:array()));
			$expAmount = $transExp['exp_amount'];
            $termsData = (!empty($data['conditions']))?$data['conditions']:array();

            unset($transExp['exp_amount'],$data['itemData'],$data['expenseData'],$data['conditions'],$data['masterDetails']);		

			/* auto approve NYN 16-07-2024 */ 
			$data['is_approve'] = $this->loginId;
			$data['approve_date'] = date('Y-m-d');
				
            $result = $this->store($this->soMaster,$data,'Sales Order');

            $masterDetails['id'] = "";
            $masterDetails['main_ref_id'] = $result['id'];
            $masterDetails['table_name'] = $this->soMaster;
            $masterDetails['description'] = "SO MASTER DETAILS";
            $this->store($this->transDetails,$masterDetails);

            $expenseData = array();
            if($expAmount <> 0):				
				$expenseData = $transExp;
                $expenseData['id'] = "";
				$expenseData['trans_main_id'] = $result['id'];
                $this->store($this->transExpense,$expenseData);
			endif;

            if(!empty($termsData)):
                $termsData = [
                    'id' =>"",
                    'table_name' => $this->soMaster,
                    'description' => "SO TERMS",
                    'main_ref_id' => $result['id'],
                    't_col_1' => $termsData
                ];
                $this->store($this->transDetails,$termsData);
            endif;

            $i=1;
            foreach($itemData as $row):
                $row['entry_type'] = $data['entry_type'];
                $row['trans_main_id'] = $result['id'];
                $row['cod_date'] = (!empty($row['cod_date']))?$row['cod_date']:NULL;
                $row['is_delete'] = 0;
                $this->store($this->soTrans,$row);

                if(!empty($row['ref_id'])):
                    $setData = array();
                    $setData['tableName'] = 'sq_trans';
                    $setData['where']['id'] = $row['ref_id'];
                    $setData['update']['trans_status'] = "1";
                    $this->setValue($setData);
                endif;
            endforeach;
            
			if(!empty($data['ref_id'])):
                $refIds = explode(",",$data['ref_id']);
                foreach($refIds as $main_id):
                    $setData = array();
                    $setData['tableName'] = 'sq_master';
                    $setData['where']['id'] = $main_id;
                    $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status <> 0, 1, 0)) ,1 , 0 ) as trans_status FROM so_trans WHERE trans_main_id = ".$main_id." AND is_delete = 0)";
                    $this->setValue($setData);
                endforeach;
            endif;

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function checkDuplicate($data){
        $queryData['tableName'] = $this->soMaster;
        $queryData['where']['trans_number'] = $data['trans_number'];

        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

		$queryData['where']['so_master.trans_date >='] = $this->startYearDate;
		$queryData['where']['so_master.trans_date <='] = $this->endYearDate;
        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function getSalesOrder($data){
        $queryData = array();
        $queryData['tableName'] = $this->soMaster;
        //$queryData['select'] = "so_master.*,trans_details.t_col_1 as contact_person,trans_details.t_col_2 as contact_no,trans_details.t_col_3 as ship_address,,trans_details.t_col_4 as ship_pincode,employee_master.emp_name as created_name,party_master.party_name";
		$queryData['select'] = "so_master.*,trans_details.t_col_1 as contact_person,trans_details.t_col_2 as contact_no,trans_details.t_col_3 as delivery_address,trans_details.t_col_4 as delivery_pincode,employee_master.emp_name as created_name,party_master.party_name,st.party_name as ship_party_name,st.party_address as ship_address,st.party_pincode as ship_pincode";
      
        $queryData['leftJoin']['trans_details'] = "so_master.id = trans_details.main_ref_id AND trans_details.description = 'SO MASTER DETAILS' AND trans_details.table_name = '".$this->soMaster."'";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = so_master.created_by";
        $queryData['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
		$queryData['leftJoin']['party_master AS st'] = "st.id = so_master.ship_to";

        $queryData['where']['so_master.id'] = $data['id'];
        $result = $this->row($queryData);

        if($data['itemList'] == 1):
            $result->itemList = $this->getSalesOrderItems($data);
        endif;

        $queryData = array();
        $queryData['tableName'] = $this->transExpense;
        $queryData['where']['trans_main_id'] = $data['id'];
        $result->expenseData = $this->row($queryData);

        $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "t_col_1 as condition";
        $queryData['where']['main_ref_id'] = $data['id'];
        $queryData['where']['table_name'] = $this->soMaster;
        $queryData['where']['description'] = "SO TERMS";
        $result->termsConditions = $this->row($queryData);

        return $result;
    }
	
	public function getSalesOrderItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->soTrans;
        $queryData['select'] = "so_trans.*,tmref.trans_number as ref_number,item_master.item_name,item_master.uom,item_master.hsn_code,unit_master.unit_name";
        $queryData['leftJoin']['so_trans as tcref'] = "tcref.id = so_trans.ref_id";
        $queryData['leftJoin']['so_master as tmref'] = "tcref.trans_main_id = tmref.id";
        $queryData['leftJoin']['item_master'] = "item_master.id = so_trans.item_id";
        $queryData['leftJoin']['unit_master'] = "unit_master.id = item_master.unit_id";
        
		if(!empty($data['id'])){
            $queryData['where']['so_trans.trans_main_id'] = $data['id'];
        } else {
            $queryData['select'] .= ",so_master.trans_date,so_master.party_id, party_master.party_name, trans_details.t_col_3 as ship_address, trans_details.t_col_4 as delivery_pincode";
            $queryData['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id";
            $queryData['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
            $queryData['leftJoin']['trans_details'] = "trans_details.main_ref_id = so_master.id AND trans_details.table_name = 'so_master' AND trans_details.description='SO MASTER DETAILS'";
        }
		
		if(!empty($data['ref_id'])){ $queryData['where_in']['so_trans.id'] = $data['ref_id']; }
		
		if(!empty($data['item_id'])){ $queryData['where']['so_trans.item_id'] = $data['item_id']; }
		
		if(!empty($data['limit'])){ 
			$queryData['limit'] = $data['limit']; 
			$queryData['order_by']['so_trans.id'] = 'DESC';
		}
        $result = $this->rows($queryData);
        return $result;
    }

    public function getSalesOrderItem($data){
        $queryData = array();
        $queryData['tableName'] = $this->soTrans;
        $queryData['select'] = "so_trans.*,so_master.party_id";
        $queryData['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id";
        $queryData['where']['so_trans.id'] = $data['id'];
        $result = $this->row($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $postData["table_name"] = $this->soMaster;
            $postData['where'] = [['column_name'=>'from_entry_type','column_value'=>$this->data['entryData']->id]];
            $postData['find'] = [['column_name'=>'ref_id','column_value'=>$id]];
            $checkRef = $this->checkEntryReference($postData);
            if($checkRef['status'] == 0):
                $this->db->trans_rollback();
                return $checkRef;
            endif;

            $dataRow = $this->getSalesOrder(['id'=>$id,'itemList'=>1]);
            foreach($dataRow->itemList as $row):
				if(!empty($row->ref_id)):
                    $setData = array();
                    $setData['tableName'] = 'sq_trans';
                    $setData['where']['id'] = $row->ref_id;
                    $setData['update']['trans_status'] = 0;
                    $this->setValue($setData);
                endif;

                $this->trash($this->soTrans,['id'=>$row->id]);
            endforeach;

			if(!empty($dataRow->ref_id)):
                $oldRefIds = explode(",",$dataRow->ref_id);
                foreach($oldRefIds as $main_id):
                    $setData = array();
                    $setData['tableName'] = 'sq_master';
                    $setData['where']['id'] = $main_id;
                    $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status <> 0, 1, 0)) ,1 , 0 ) as trans_status FROM so_trans WHERE trans_main_id = ".$main_id." AND is_delete = 0)";
                    $this->setValue($setData);
                endforeach;
            endif;

            $this->trash($this->transExpense,['trans_main_id'=>$id]);
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->soMaster,'description'=>"SO TERMS"]);
            $result = $this->trash($this->soMaster,['id'=>$id],'Sales Order');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getPendingOrderItems($data=array()){
        $queryData = array();
        $queryData['tableName'] = $this->soTrans;
        $queryData['select'] = "so_trans.*,IFNULL(dt.dispatch_qty, 0.000) AS dispatch_qty,(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) as pending_qty,so_master.party_id,so_master.entry_type as main_entry_type,so_master.trans_number,so_master.trans_date,so_master.doc_no,item_master.item_name,party_master.party_name,party_master.party_code,so_master.doc_date,item_master.is_packing,item_master.uom"; 

        $queryData['leftJoin']['so_master'] = "so_trans.trans_main_id = so_master.id";
        $queryData['leftJoin']['party_master'] = "so_master.party_id = party_master.id";
        $queryData['leftJoin']['item_master'] = "so_trans.item_id = item_master.id";
        $queryData['leftJoin']['(SELECT SUM(qty) AS dispatch_qty, ref_id FROM trans_child WHERE is_delete = 0 AND from_entry_type = 14 GROUP BY ref_id) AS dt'] = "dt.ref_id = so_trans.id";

        if(!empty($data['dispatch_order'])):
            $queryData['select'] .= ", ifnull(dispatch_order.do_qty,0) as do_qty, (so_trans.qty - IFNULL(dispatch_order.do_qty, 0)) as pending_do_qty";

            $queryData['leftJoin']['(SELECT so_trans_id,SUM(order_qty) as do_qty FROM dispatch_order WHERE is_delete = 0 GROUP BY so_trans_id) as dispatch_order'] = "so_trans.id = dispatch_order.so_trans_id";

            $queryData['where']['so_master.is_approve >'] = 0;
            $queryData['where']['(so_trans.qty - IFNULL(dispatch_order.do_qty, 0)) >'] = 0;
        endif;
        
		if(!empty($data['stock_data'])){
            $queryData['select'] .= ",stock.stock_qty";
            $queryData['leftJoin']['(SELECT SUM(  stock_trans.qty * stock_trans.p_or_m) AS stock_qty,item_id FROM stock_trans WHERE is_delete = 0 AND location_id ='.$this->DISP_STORE->id.' GROUP BY item_id) as stock'] = "stock.item_id = so_trans.item_id";
        }
		if(!empty($data['party_id'])):
			$queryData['where']['so_master.party_id'] = $data['party_id'];
        endif;
		
        if(!empty($data['completed_order'])):
            $queryData['where']['(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) <='] = 0;
        else:
            $queryData['where']['(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) >'] = 0;
        endif;

		$queryData['where']['so_trans.trans_status !='] = 2;
        $queryData['where']['so_master.is_delete'] = 0;

		if(!empty($data['group_by'])):
            $queryData['group_by'][] = $data['group_by'];
        endif;

        $result = $this->rows($queryData);

        if(!empty($data['batchDetail'])):
            foreach($result as &$row):
                $postData = ["item_id" => $row->item_id, "party_id" => $row->party_id,'location_id'=>$this->DISP_STORE->id, 'stock_required'=>1, 'group_by'=>'location_id,size'];
                $batchData = $this->itemStock->getItemStockBatchWise($postData);

                $batchDetail = [];$pendingQty = $row->pending_qty;
                foreach($batchData as $batch):
                    $batchId =floatval($batch->size).$batch->location_id.$batch->item_id;
                    $location_name = '['.$batch->store_name.'] '.$batch->location;
                    
                     if($pendingQty > 0):
                        $batchQty = ($pendingQty > $batch->qty)?$batch->qty:$pendingQty;
                        $batchDetail[] = [
                            'batch_qty' => $batchQty,
                            'size' => $batch->size,
                            'location_id' => $batch->location_id,
                            'remark' => $batchId,
                            'box_qty' => floatval(($batchQty / $batch->size))
                        ];
                    endif;
                    
                    $pendingQty -= $batch->qty;
                    if($pendingQty <= 0): break; endif;
                endforeach;

                $row->batch_detail = $batchDetail;
            endforeach;
        endif;

        return $result;
    }

    /* Party Order Start */
    public function getPartyOrderDTRows($data){
        $data['tableName'] = $this->soTrans;
        $data['select'] = "so_trans.id as trans_child_id,item_master.item_name,so_trans.qty,so_trans.dispatch_qty,(so_trans.qty - so_trans.dispatch_qty) as pending_qty,so_master.id,so_master.trans_number,DATE_FORMAT(so_master.trans_date,'%d-%m-%Y') as trans_date,party_master.party_name,so_trans.trans_status,so_trans.brand_name,party_master.sales_executive,so_master.party_id,if(so_master.is_approve > 0,'Accepted','Pending') as order_status";

        $data['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id";
        $data['leftJoin']['party_master'] = "party_master.id = so_master.party_id";

        $data['where']['so_trans.entry_type'] = $data['entry_type'];
        $data['where']['so_trans.created_by'] = $this->loginId;
        $data['customWhere'][] = "so_master.party_id = party_master.sales_executive";

        if($data['status'] == 0):
            $data['where']['so_trans.trans_status'] = 0;
            $data['where']['so_master.trans_date <='] = $this->endYearDate;
        elseif($data['status'] == 1):
            $data['where']['so_trans.trans_status'] = 1;
            $data['where']['so_master.trans_date >='] = $this->startYearDate;
            $data['where']['so_master.trans_date <='] = $this->endYearDate;
        endif;

        $data['order_by']['so_master.trans_date'] = "DESC";
        $data['order_by']['so_master.id'] = "DESC";

        $data['group_by'][] = "so_trans.id";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "if(so_master.is_approve > 0,'Accepted','Pending')";
        $data['searchCol'][] = "so_master.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(so_master.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "so_trans.brand_name";
        $data['searchCol'][] = "so_trans.qty";
        $data['searchCol'][] = "so_trans.dispatch_qty";
        $data['searchCol'][] = "(so_trans.qty - so_trans.dispatch_qty)";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }
    /* Party Order End */	
	
	public function changeOrderStatus($postData){ 
        try{
            $this->db->trans_begin();

            $result = $this->edit($this->soTrans,['trans_main_id'=>$postData['trans_main_id']],['trans_status'=>$postData['trans_status']],'Sales Order');
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

	//App by Order
    public function getSalesOrderByApp($data){
        $queryData = array();
        $queryData['tableName'] = $this->soMaster;
        $queryData['select'] = "so_master.*,party_master.party_name,so_trans.qty,item_master.item_name";
        $queryData['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
        $queryData['leftJoin']['so_trans'] = "so_trans.trans_main_id = so_master.id";
        $queryData['leftJoin']['item_master'] = "item_master.id = so_trans.item_id";
        $queryData['where']['so_master.entry_type'] = $this->data['entryData']->id;
        $queryData['where']['so_master.is_approve'] = 0;
        $queryData['where']['so_trans.trans_status'] = 0;
        $queryData['group_by'][] = "so_master.id";
        $queryData['order_by']['so_master.id'] = "DESC";
        $result = $this->rows($queryData);
        return $result;
    }

    public function approveSalesOrder($data) {
        try{
            $this->db->trans_begin();

            if(!empty($data['source_id']) && $data['source_id'] == 1){
                $leadData = $this->leads->getLead(['id'=>$data['party_id']]);

                $partyData = Array();
                $partyData['id'] = "";
                $partyData['party_category'] = 1;
                $partyData['party_code'] = $leadData->party_code;
                $partyData['party_name'] = $leadData->party_name;
                $partyData['whatsapp_no'] = $leadData->whatsapp_no;
                $partyData['registration_type'] = $leadData->registration_type;
                $partyData['gstin'] = $leadData->gstin;
                $partyData['credit_days'] = $leadData->credit_days;
                $partyData['sales_executive'] = $leadData->sales_executive;
                $resultData = $this->store('party_master', $partyData, 'Party');

                $this->edit("lead_master",['id'=>$leadData->id],['party_id'=>$resultData['insert_id'],'party_type'=>1]);

                $this->edit('party_detail',['lead_id'=>$leadData->id],['party_id'=>$resultData['insert_id']]);
                
                $this->edit($this->soMaster,['id'=>$data['id']],['party_id'=>$resultData['insert_id'],'source_id'=>2]);
            }

            $date = ($data['is_approve'] == 1) ? date('Y-m-d') : "";
            $isApprove =  ($data['is_approve'] == 1) ? $this->loginId : 0;
            
            $this->store($this->soMaster, ['id'=> $data['id'], 'is_approve' => $isApprove, 'approve_date'=>$date]);
            $result = ['status' => 1, 'message' => 'Sales Order ' . $data['msg'] . ' successfully.'];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	    
    }

    public function getOrderItem($data=array()){
        $queryData['tableName'] = $this->soTrans;  
        $queryData['select'] = 'so_trans.*,IFNULL(dt.dispatch_qty, 0.000) AS dispatch_qty,item_master.item_name,item_master.hsn_code,unit_master.unit_name';

        $queryData['leftJoin']['item_master'] = "item_master.id = so_trans.item_id";
        $queryData['leftJoin']['unit_master'] = "unit_master.id = item_master.unit_id";
        $queryData['leftJoin']['(SELECT SUM(qty) AS dispatch_qty, ref_id FROM trans_child WHERE is_delete = 0 AND from_entry_type = 14 GROUP BY ref_id) AS dt'] = "dt.ref_id = so_trans.id";

        if(!empty($data['dispatch_order'])):
            $queryData['select'] .= ", ifnull(dispatch_order.do_qty,0) as do_qty, (so_trans.qty - IFNULL(dispatch_order.do_qty, 0)) as pending_do_qty";

            $queryData['leftJoin']['(SELECT so_trans_id,SUM(order_qty) as do_qty FROM dispatch_order WHERE is_delete = 0 GROUP BY so_trans_id) as dispatch_order'] = "so_trans.id = dispatch_order.so_trans_id";
        endif;

        $queryData['where']['so_trans.id'] = $data['id'];
        return $this->row($queryData);
    }

	public function getTransDetailData($data = array()){
		$queryData['tableName'] = $this->transDetails;
		$queryData['select'] = "trans_details.t_col_3 as ship_address, trans_details.t_col_4 as delivery_pincode";
		if(!empty($data['trans_main_id']))
			$queryData['where']['trans_details.main_ref_id'] = $data['trans_main_id'];
		if(!empty($data['tableName']))
			$queryData['where']['table_name'] = $data['tableName'];
		if(!empty($data['description']))
			$queryData['where']['trans_details.description'] = $data['description'];
	
		if(!empty($data['single_row'])){
			return $this->row($queryData);
		}else{
			return $this->rows($queryData);
		}
	}

}
?>