<?php
class DeliveryChallanModel extends MasterModel{
    private $transMain = "trans_main";
    private $transChild = "trans_child";
    private $transDetails = "trans_details";
    private $stockTrans = "stock_trans";

    public function getDTRows($data){
        $data['tableName'] = $this->transMain;
        $data['select'] = "trans_main.id,trans_main.trans_number,trans_main.trans_date,trans_main.party_name,trans_main.remark,trans_main.trans_status";

        $data['where']['trans_main.entry_type'] = $data['entry_type'];

        if($data['status'] == 0):
            $data['where']['trans_main.trans_status'] = 0;
            $data['where']['trans_main.trans_date <='] = $this->endYearDate;
        elseif($data['status'] == 1):
            $data['where']['trans_main.trans_status'] = 1;
            $data['where']['trans_main.trans_date >='] = $this->startYearDate;
            $data['where']['trans_main.trans_date <='] = $this->endYearDate;
        endif;

        $data['order_by']['trans_main.trans_date'] = "DESC";
        $data['order_by']['trans_main.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_main.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "trans_main.party_name";
        $data['searchCol'][] = "trans_main.remark";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
            if($this->checkDuplicate($data) > 0):
                $errorMessage['trans_number'] = "DC. No. is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;

            if(!empty($data['id'])):
                $dataRow = $this->getDeliveryChallan(['id'=>$data['id'],'itemList'=>1]);

                foreach($dataRow->itemList as $row):
                    if(!empty($row->ref_id)):
                        $setData = array();
                        $setData['tableName'] = 'so_trans';
                        $setData['where']['id'] = $row->ref_id;
                        $setData['set_value']['dispatch_qty'] = 'IF(`dispatch_qty` - '.$row->qty.' >= 0, `dispatch_qty` - '.$row->qty.', 0)';
                        $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                        $this->setValue($setData);
                    endif;

                    if(!empty($row->request_id)):
                        $setData = array();
                        $setData['tableName'] = 'dispatch_order';
                        $setData['where']['id'] = $row->request_id;
                        $setData['update']['disp_trans_id'] = 0;
                        $this->setValue($setData);
                    endif;

                    $this->trash($this->transChild,['id'=>$row->id]);
                endforeach;

                if(!empty($dataRow->ref_id)):
                    $oldRefIds = explode(",",$dataRow->ref_id);
                    foreach($oldRefIds as $main_id):
                        $setData = array();
                        $setData['tableName'] = 'so_master';
                        $setData['where']['id'] = $main_id;
                        $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status <> 0, 1, 0)) ,1 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = ".$main_id." AND is_delete = 0)";
                        $this->setValue($setData);
                    endforeach;
                endif;                
                
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"DC TERMS"]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"DC MASTER DETAILS"]);
                $this->remove($this->stockTrans,['main_ref_id'=>$data['id'],'trans_type'=>'DLC']);
            endif;
            
            $masterDetails = $data['masterDetails'];
            $itemData = $data['itemData'];
            $termsData = (!empty($data['conditions']))?$data['conditions']:array();

            unset($data['itemData'],$data['masterDetails'],$data['termsData']);		

            $result = $this->store($this->transMain,$data,'Delivery Challan');

            if(!empty($masterDetails)):
                $masterDetails['id'] = "";
                $masterDetails['main_ref_id'] = $result['id'];
                $masterDetails['table_name'] = $this->transMain;
                $masterDetails['description'] = "DC MASTER DETAILS";
                $this->store($this->transDetails,$masterDetails);
            endif;

            if(!empty($termsData)):
                $termsData = [
                    'id' =>"",
                    'table_name' => $this->transMain,
                    'description' => "DC TERMS",
                    'main_ref_id' => $result['id'],
                    't_col_1' => $termsData
                ];
                $this->store($this->transDetails,$termsData);
            endif;

            $i=1;
            foreach($itemData as $row):
                $row['entry_type'] = $data['entry_type'];
                $row['trans_main_id'] = $result['id'];
                $row['is_delete'] = 0;
                $batchDetail = $row['batch_detail']; unset($row['batch_detail']);

                $itemTrans = $this->store($this->transChild,$row);

                if($row['stock_eff'] == 1):
                    $batchDetail = json_decode($batchDetail,true);
                    foreach($batchDetail as $batch):
                        if(floatval($batch['batch_qty']) > 0):
                            $stockData = [
                                'id' => "",
                                'trans_type' => 'DLC',
                                'trans_date' => $data['trans_date'],
                                'ref_no' => $data['trans_number'],
                                'main_ref_id' => $result['id'],
                                'child_ref_id' => $itemTrans['id'],
                                'location_id' => $batch['location_id'],
                                'party_id' => $data['party_id'],
                                'item_id' => $row['item_id'],
                                'p_or_m' => -1,
                                'qty' => $batch['batch_qty'],
                                'opt_qty' => $batch['opt_qty'],
                                'price' => $row['price'],
                                'remark' => $batch['remark']
                            ];
        
                            $this->store($this->stockTrans,$stockData);
                        endif;
                    endforeach;
                endif;

                if(!empty($row['ref_id'])):
                    $setData = array();
                    $setData['tableName'] = 'so_trans';
                    $setData['where']['id'] = $row['ref_id'];
                    $setData['set']['dispatch_qty'] = 'dispatch_qty, + '.$row['qty'];
                    $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                    $this->setValue($setData);
                endif;

                if(!empty($row['request_id'])):
                    $setData = array();
                    $setData['tableName'] = 'dispatch_order';
                    $setData['where']['id'] = $row['request_id'];
                    $setData['update']['disp_trans_id'] = $itemTrans['id'];
                    $this->setValue($setData);
                endif;
            endforeach;
            
            if(!empty($data['ref_id'])):
                $refIds = explode(",",$data['ref_id']);
                foreach($refIds as $main_id):
                    $setData = array();
                    $setData['tableName'] = 'so_master';
                    $setData['where']['id'] = $main_id;
                    $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status <> 0, 1, 0)) ,1 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = ".$main_id." AND is_delete = 0)";
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
        $queryData['tableName'] = $this->transMain;
        $queryData['where']['entry_type'] = $data['entry_type'];
        $queryData['where']['trans_number'] = $data['trans_number'];
        
        $queryData['where']['trans_date >='] = $this->startYearDate;
        $queryData['where']['trans_date <='] = $this->endYearDate;

        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function getDeliveryChallan($data){
        $queryData = array();
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.*,employee_master.emp_name as created_name, transport_master.transport_id, transport_master.transport_name";
        
		$queryData['select'] .= ",trans_details.i_col_1 as transport_id,trans_details.t_col_3 as ship_address, trans_details.t_col_4 as delivery_pincode";
        $queryData['leftJoin']['trans_details'] = "trans_details.main_ref_id = trans_main.id AND trans_details.description = 'DC MASTER DETAILS' AND trans_details.table_name = 'trans_main'";    

        $queryData['leftJoin']['employee_master'] = "employee_master.id = trans_main.created_by";
        $queryData['leftJoin']['transport_master'] = "transport_master.id = trans_details.i_col_1";
        
        $queryData['where']['trans_main.id'] = $data['id'];
        $result = $this->row($queryData);

        if(!empty($data['itemList'])):
            $result->itemList = $this->getDeliveryChallanItems($data);
        endif;

		if(!empty($data['packingList'])):
            $data['dc_id'] = $data['id'];
            $result->packingList = $this->getPackingDetail($data);
        endif;

        $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "t_col_1 as condition";
        $queryData['where']['main_ref_id'] = $data['id'];
        $queryData['where']['table_name'] = $this->transMain;
        $queryData['where']['description'] = "DC TERMS";
        $result->termsConditions = $this->row($queryData);

        return $result;
    }

    public function getDeliveryChallanItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.*,so_master.doc_no as cust_po_no,so_master.trans_number as so_no";
        $queryData['leftJoin']['so_trans'] = "so_trans.id = trans_child.ref_id";
        $queryData['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id"; 
        $queryData['where']['trans_child.trans_main_id'] = $data['id'];
        $result = $this->rows($queryData);

        foreach($result as &$row):
            $queryData = [];
            $queryData['tableName'] = $this->stockTrans;
            $queryData['select'] = "location_id,opt_qty,qty as batch_qty, remark";
            $queryData['where']['trans_type'] = 'DLC';
            $queryData['where']['main_ref_id'] = $row->trans_main_id;
            $queryData['where']['child_ref_id'] = $row->id;
            $queryData['where']['item_id'] = $row->item_id;
            $row->batch_detail = json_encode($this->rows($queryData));
        endforeach;
        
        return $result;
    }

    public function getDeliveryChallanItem($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.*";
        $queryData['where']['trans_child.id'] = $data['id'];
        $result = $this->row($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $postData["table_name"] = $this->transMain;
            $postData['where'] = [['column_name'=>'from_entry_type','column_value'=>$this->data['entryData']->id]];
            $postData['find'] = [['column_name'=>'ref_id','column_value'=>$id]];
            $checkRef = $this->checkEntryReference($postData);
            if($checkRef['status'] == 0):
                $this->db->trans_rollback();
                return $checkRef;
            endif;

            $dataRow = $this->getDeliveryChallan(['id'=>$id,'itemList'=>1]);

            foreach($dataRow->itemList as $row):
                if(!empty($row->ref_id)):
                    $setData = array();
                    $setData['tableName'] = 'so_trans';
                    $setData['where']['id'] = $row->ref_id;
                    $setData['set_value']['dispatch_qty'] = 'IF(`dispatch_qty` - '.$row->qty.' >= 0, `dispatch_qty` - '.$row->qty.', 0)';
                    $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 0 END)";
                    $this->setValue($setData);
                endif;

                if(!empty($row->request_id)):
                    $setData = array();
                    $setData['tableName'] = 'dispatch_order';
                    $setData['where']['id'] = $row->request_id;
                    $setData['update']['disp_trans_id'] = 0;
                    $this->setValue($setData);
                endif;

                $this->trash($this->transChild,['id'=>$row->id]);
            endforeach;

            if(!empty($dataRow->ref_id)):
                $oldRefIds = explode(",",$dataRow->ref_id);
                foreach($oldRefIds as $main_id):
                    $setData = array();
                    $setData['tableName'] = 'so_master';
                    $setData['where']['id'] = $main_id;
                    $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status <> 0, 1, 0)) ,1 , 0 ) as trans_status FROM trans_child WHERE trans_main_id = ".$main_id." AND is_delete = 0)";
                    $this->setValue($setData);
                endforeach;
            endif;                
                
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transMain,'description'=>"DC TERMS"]);
            $this->remove($this->transDetails,['main_ref_id'=>$id,'table_name'=>$this->transMain,'description'=>"DC MASTER DETAILS"]);
            $this->remove($this->stockTrans,['main_ref_id'=>$id,'trans_type'=>'DLC']);

            $result = $this->trash($this->transMain,['id'=>$id],'Delivery Challan');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getPendingChallanItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['select'] = "trans_child.*,trans_main.party_id,(trans_child.qty - trans_child.dispatch_qty) as pending_qty,trans_main.entry_type as main_entry_type,trans_main.trans_number,trans_main.trans_date,trans_main.doc_no,trans_child.unit_name as uom";

        $queryData['leftJoin']['trans_main'] = "trans_child.trans_main_id = trans_main.id";

        if(!empty($data['party_id'])){$queryData['where']['trans_main.party_id'] = $data['party_id'];}
        
        
        if(!empty($data['ref_id'])){ $queryData['where_in']['trans_child.id'] = $data['ref_id']; }

		$queryData['where']['trans_child.entry_type'] =177;
		
        $queryData['where']['trans_main.trans_status'] = 0;

        $queryData['where']['(trans_child.qty - trans_child.dispatch_qty) >'] = 0;

        $queryData['order_by']['trans_main.trans_no'] = "ASC";
        
        $result = $this->rows($queryData);

        foreach($result as &$row):
            $queryData = [];
            $queryData['tableName'] = $this->stockTrans;
            $queryData['select'] = "location_id,opt_qty,qty as batch_qty, remark";
            $queryData['where']['trans_type'] = 'DLC';
            $queryData['where']['main_ref_id'] = $row->trans_main_id;
            $queryData['where']['child_ref_id'] = $row->id;
            $queryData['where']['item_id'] = $row->item_id;
            $row->batch_detail = json_encode($this->rows($queryData));
        endforeach;
        
        return $result;
    }

    public function getPendingChallanDTRows($data){
        $data['tableName'] = $this->transChild;
        $data['select'] = "trans_child.*,trans_main.id,trans_main.trans_number,trans_main.trans_date,trans_main.party_name,trans_main.party_id";
        $data['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";

        $data['where']['trans_child.entry_type'] = 177;
        $data['where']['trans_child.trans_status'] = 0;
        $data['where']['trans_main.trans_date <='] = $this->endYearDate;
    
        $data['order_by']['trans_main.trans_date'] = "DESC";
        $data['order_by']['trans_child.trans_main_id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "trans_main.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "trans_main.party_name";
        $data['searchCol'][] = "trans_child.item_name";
        $data['searchCol'][] = "trans_child.qty";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getPackingItemDetail($data = [] ){
        $queryData['tableName'] ='final_packing_trans';
        $queryData['select'] = "final_packing_trans.*";
        //Master data
        $queryData['select'] .= ',final_packing_master.trans_number,final_packing_master.trans_date,final_packing_master.method_of_dispatch,final_packing_master.port_of_loading,final_packing_master.port_of_discharge,final_packing_master.type_of_shipment,final_packing_master.country_of_origin,final_packing_master.country_of_fd,final_packing_master.terms_of_delivery,final_packing_master.id as main_id';

        //Party Detail
        $queryData['select'] .= ',party_master.party_name,party_master.party_address,party_master.party_mobile,party_master.contact_person';

        //Item Detail
        $queryData['select'] .= ',item_master.item_name,item_master.wt_pcs';

        //DC Data
        $queryData['select'] .= ',trans_main.doc_no,trans_main.doc_date';

        $queryData['leftJoin']['final_packing_master'] = "final_packing_master.id = final_packing_trans.packing_id";
        $queryData['leftJoin']['party_master'] = "party_master.id = final_packing_master.party_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = final_packing_trans.item_id";
        $queryData['leftJoin']['trans_child'] = "trans_child.id = final_packing_trans.dc_trans_id";
        $queryData['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
      
        if(!empty($data['dc_id'])){
            $queryData['where']['trans_child.trans_main_id'] = $data['dc_id'];
        }
        
        $result = $this->rows($queryData);
        
        return $result ;
    }

	public function getPackingDetail($data = [] ){
        $queryData['tableName'] ='final_packing_trans';
        
        $queryData['select'] = 'SUM(final_packing_trans.box_wt) as boxWt,MAX(final_packing_trans.box_no) as boxNo,GROUP_CONCAT(DISTINCT final_packing_master.method_of_dispatch) as method_of_dispatch';

        $queryData['leftJoin']['final_packing_master'] = "final_packing_master.id = final_packing_trans.packing_id";
        $queryData['leftJoin']['trans_child'] = "trans_child.id = final_packing_trans.dc_trans_id";
        $queryData['leftJoin']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
      
        if(!empty($data['dc_id'])){
            $queryData['where']['trans_child.trans_main_id'] = $data['dc_id'];
        }
        $queryData['group_by'][]= 'trans_main.id';
        
        $result = $this->row($queryData);
        return $result;
    }
	
}
?>