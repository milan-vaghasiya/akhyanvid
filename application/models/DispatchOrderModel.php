<?php
class DispatchOrderModel extends MasterModel{
    private $dispatchOrder = "dispatch_order";
    private $soMaster = "so_master";
    private $soTrans = "so_trans";
    private $stockTrans = "stock_trans";
    private $palletTrans = "pallet_packing_trans";

    public function getDTRows($data){
        $data['tableName'] = $this->dispatchOrder;
        $data['select'] = "dispatch_order.id, dispatch_order.order_number, dispatch_order.order_prefix, dispatch_order.order_no, dispatch_order.order_date, dispatch_order.party_id, so_master.trans_number as so_number, so_master.trans_date as so_date, so_trans.cod_date as delivery_date, dispatch_order.party_id, party_master.party_name, dispatch_order.item_id, item_master.item_code, item_master.item_name, dispatch_order.order_qty, dispatch_order.link_qty, (dispatch_order.order_qty - dispatch_order.link_qty) as pending_qty, dispatch_order.remark,dispatch_order.disp_trans_id, IFNULL(ppt.pp_qty,0) as pallet_pck_qty, (dispatch_order.order_qty - IFNULL(ppt.pp_qty,0)) as pp_pending_qty";

        $data['leftJoin']['party_master'] = "party_master.id = dispatch_order.party_id";
        $data['leftJoin']['item_master'] = "item_master.id = dispatch_order.item_id";
        $data['leftJoin']['so_master'] = "so_master.id = dispatch_order.so_id";
        $data['leftJoin']['so_trans'] = "so_trans.id = dispatch_order.so_trans_id";
        $data['leftJoin']['(SELECT pallet_packing_trans.do_id, SUM(st.opt_qty * pallet_packing_trans.box_qty) as pp_qty FROM pallet_packing_trans LEFT JOIN stock_trans as st ON st.id = pallet_packing_trans.ref_id WHERE pallet_packing_trans.is_delete = 0 GROUP BY pallet_packing_trans.do_id) as ppt'] = "ppt.do_id = dispatch_order.id";

        if($data['status'] == 0):
            //$data['where']['(dispatch_order.order_qty - dispatch_order.link_qty) >'] = 0;
            $data['where']['(dispatch_order.order_qty - IFNULL(ppt.pp_qty,0)) >'] = 0;
            $data['where']['dispatch_order.disp_trans_id'] = 0;
            $data['where']['dispatch_order.order_date <='] = $this->endYearDate;
        elseif($data['status'] == 1):
            //$data['where']['(dispatch_order.order_qty - dispatch_order.link_qty) <='] = 0;
            $data['where']['(dispatch_order.order_qty - IFNULL(ppt.pp_qty,0)) <='] = 0;
            $data['where']['dispatch_order.disp_trans_id'] = 0;
            $data['where']['dispatch_order.order_date <='] = $this->endYearDate;
        elseif($data['status'] == 2):
            $data['where']['dispatch_order.disp_trans_id >'] = 0;
            $data['where']['dispatch_order.order_date >='] = $this->startYearDate;
            $data['where']['dispatch_order.order_date <='] = $this->endYearDate;
        endif;

        $data['order_by']['dispatch_order.order_date'] = "DESC";
        $data['order_by']['dispatch_order.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "dispatch_order.order_number";
        $data['searchCol'][] = "DATE_FORMAT(dispatch_order.order_date,'%d-%m-%Y')";
        $data['searchCol'][] = "so_master.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(so_master.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "DATE_FORMAT(so_trans.cod_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "dispatch_order.order_qty";
        $data['searchCol'][] = "(dispatch_order.order_qty - dispatch_order.link_qty)";
        $data['searchCol'][] = "dispatch_order.remark";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            foreach($data['itemData'] as $row):
                $row['order_prefix'] = $data['order_prefix'];
                $row['order_no'] = $data['order_no'];
                $row['order_number'] = $data['order_number'];
                $row['order_date'] = $data['order_date'];
                $row['entry_type'] = $data['entry_type'];
                $row['party_id'] = $data['party_id'];

                $result = $this->store($this->dispatchOrder,$row,'Dispatch Order');
            endforeach;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getDispatchOrderItemList($data = array()){
        $queryData = [];
        $queryData['tableName'] = $this->dispatchOrder;
        $queryData['select'] = "dispatch_order.id, dispatch_order.item_id, dispatch_order.so_trans_id, dispatch_order.party_id, so_master.trans_number as so_number, so_master.trans_date as so_date, so_trans.cod_date as delivery_date, item_master.item_code, item_master.item_name, item_master.uom,dispatch_order.entry_type,dispatch_order.order_qty, (dispatch_order.order_qty - dispatch_order.link_qty) as pending_qty";

        $queryData['leftJoin']['item_master'] = "item_master.id = dispatch_order.item_id";
        $queryData['leftJoin']['so_master'] = "so_master.id = dispatch_order.so_id";
        $queryData['leftJoin']['so_trans'] = "so_trans.id = dispatch_order.so_trans_id";

        if(!empty($data['order_number'])){ $queryData['where']['dispatch_order.order_number'] = $data['order_number']; }
		if(!empty($data['ref_id'])){ $queryData['where_in']['dispatch_order.id'] = $data['ref_id']; }

        $result = $this->rows($queryData);

        return $result;
    }

    public function getDispatchOrderItem($data = array()){
        $queryData = [];
        $queryData['tableName'] = $this->dispatchOrder;

        $queryData['select'] = "dispatch_order.*, so_master.trans_number as so_number, so_master.trans_date as so_date, IFNULL(DATE_FORMAT(so_trans.cod_date,'%d-%m-%Y'),'') as delivery_date, party_master.party_name, item_master.item_code, item_master.item_name, (dispatch_order.order_qty - dispatch_order.link_qty) as pending_qty";

        $queryData['leftJoin']['party_master'] = "party_master.id = dispatch_order.party_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = dispatch_order.item_id";
        $queryData['leftJoin']['so_master'] = "so_master.id = dispatch_order.so_id";
        $queryData['leftJoin']['so_trans'] = "so_trans.id = dispatch_order.so_trans_id";

        $queryData['where']['dispatch_order.id'] = $data['id'];

        $result = $this->row($queryData);

        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $orderItem = $this->getDispatchOrderItem(['id'=>$id]);

            if(!empty(floatval($orderItem->link_qty))):
                return ['status'=>0,'message'=>'Packing already linked. You can not remove this item.'];
            endif;

            $queryData = [];
            $queryData['tableName'] = $this->palletTrans;
            $queryData['where']['do_id'] = $id;
            $palletTransDetail = $this->row($queryData);
            if(!empty($palletTransDetail)):
                return ['status'=>0,'message'=>'Final Packing Reference found. You can not remove this item.'];
            endif;

            $result = $this->trash($this->dispatchOrder,['id'=>$id],"Item");

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function savePackingLinkDetails($data){
        try{
            $this->db->trans_begin();
            
            $totalQty = 0;
            foreach($data['batchDetail'] as $row):
                if(!empty($row['batch_qty'])):
                    /* Stock Minus From Ready Material */
                    $stockTransData = [
                        'id' => '',
                        'entry_type' => $this->data['entryData']->id,
                        'ref_date' => date('Y-m-d'),
                        'ref_no' => $data['order_number'],
                        'main_ref_id' => $data['id'],
                        'location_id' => $row['location_id'],
                        'batch_no' => $row['batch_no'],
                        'party_id' => $data['party_id'],
                        'item_id' => $data['item_id'],
                        'p_or_m' => -1,
                        'qty' => $row['batch_qty'],
                        'opt_qty' => $row['opt_qty'],
                    ];
                    $transRow = $this->store($this->stockTrans,$stockTransData);

                    /* Stock Plus In Dispatch Area */
                    $stockTransData['location_id'] = $this->DISP_STORE->id;
                    $stockTransData['p_or_m'] = 1;
                    $stockTransData['ref_unique_id'] = $transRow['id'];
                    $stockTransData['child_ref_id'] = $data['so_trans_id'];
                    $stockTransData['remark'] = $data['order_number'].'-'.$data['id'];
                    $result = $this->store($this->stockTrans,$stockTransData);

                    $totalQty += $row['batch_qty'];
                endif;
            endforeach;

            /* Update Link Qty */
            $setData = array();
            $setData['tableName'] = $this->dispatchOrder;
            $setData['where']['id'] = $data['id'];
            $setData['set']['link_qty'] = 'link_qty, + '.$totalQty;
            $this->setValue($setData);

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function removePackingLink($id){
        try{
            $this->db->trans_begin();

            $stockTransRow = $this->itemStock->getStockTrans(['id'=>$id]);

            $itemRef = $this->getLinkedItemList(['ref_id'=>$id]);
            if(!empty($itemRef)):
                return ['status'=>0,'message'=>'Packing Annexure reference found. You can not remove this entry.'];
            endif;

            $postData = ['location_id' => $stockTransRow->location_id,'batch_no' => $stockTransRow->batch_no,'item_id' => $stockTransRow->item_id, 'opt_qty' => $stockTransRow->opt_qty,'stock_required'=>1, 'single_row'=>1];   
            $stockData = $this->itemStock->getItemStockBatchWise($postData); 

            $stockQty = (!empty($stockData->qty))?floatVal($stockData->qty):0;

            if(empty($stockQty)):
                return ['status'=>0,'message'=>'Stock not available. You can not remove this entry.'];
            else:
                if(floatval($stockTransRow->qty) > floatval($stockQty)):
                    return ['status'=>0,'message'=>'Stock not available. You can not remove this entry.'];
                endif;
            endif;

            /* Update Link Qty */
            $setData = array();
            $setData['tableName'] = $this->dispatchOrder;
            $setData['where']['id'] = $stockTransRow->main_ref_id;
            $setData['set_value']['link_qty'] = 'IF(`link_qty` - '.floatval($stockTransRow->qty).' >= 0, `link_qty` - '.floatval($stockTransRow->qty).', 0)';
            $this->setValue($setData);

            $this->remove($this->stockTrans,['id'=>$stockTransRow->ref_unique_id]);
            $result = $this->remove($this->stockTrans,['id'=>$id],"Item");

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getLinkedItemList_old($data){
        $queryData = [];
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_trans.*,ROUND((stock_trans.qty / stock_trans.opt_qty),2) as box_qty, item_master.item_code, item_master.item_name, IFNULL(ppt.box_qty,0) as packed_box, ROUND(((stock_trans.qty / stock_trans.opt_qty) - IFNULL(ppt.box_qty,0)),2) as pending_qty";

        $queryData['leftJoin']['dispatch_order'] = "stock_trans.main_ref_id = dispatch_order.id AND dispatch_order.is_delete = 0";
        $queryData['leftJoin']['item_master'] = "item_master.id = stock_trans.item_id";
        $queryData['leftJoin']['(SELECT ref_id,do_id,SUM(box_qty) as box_qty FROM pallet_packing_trans WHERE is_delete = 0 AND order_number = "'.$data['order_number'].'" GROUP BY ref_id,do_id) as ppt'] = 'ppt.ref_id = stock_trans.id AND ppt.do_id = stock_trans.main_ref_id';

        $queryData['where']['dispatch_order.order_number'] = $data['order_number'];
        $queryData['where']['stock_trans.entry_type'] = $this->data['entryData']->id;        
        $queryData['where']['stock_trans.p_or_m'] = 1;
        $queryData['where']['stock_trans.location_id'] = $this->DISP_STORE->id;

        if(!empty($data['ref_id'])):
            $queryData['where']['stock_trans.id'] = $data['ref_id'];
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

    public function getLinkedItemList($data){
        $queryData = [];
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_trans.id,stock_trans.batch_no,ROUND((dispatch_order.order_qty / stock_trans.opt_qty),2) as box_qty, item_master.item_code, item_master.item_name, IFNULL(ppt.box_qty,0) as packed_box, ROUND(((dispatch_order.order_qty / stock_trans.opt_qty) - IFNULL(ppt.box_qty,0)),2) as pending_qty,dispatch_order.id as main_ref_id";

        $queryData['leftJoin']['dispatch_order'] = "stock_trans.item_id = dispatch_order.item_id AND dispatch_order.is_delete = 0";
        $queryData['leftJoin']['item_master'] = "item_master.id = stock_trans.item_id";
        $queryData['leftJoin']['(SELECT ref_id,do_id,SUM(box_qty) as box_qty FROM pallet_packing_trans WHERE is_delete = 0 AND order_number = "'.$data['order_number'].'" GROUP BY ref_id,do_id) as ppt'] = 'ppt.ref_id = stock_trans.id AND ppt.do_id = dispatch_order.id';

        $queryData['where']['dispatch_order.order_number'] = $data['order_number'];
        $queryData['where']['stock_trans.batch_no'] = "OPSTOCK";
        $queryData['where']['stock_trans.trans_type'] = 'OPS';
        $queryData['where']['stock_trans.p_or_m'] = 1;
        $queryData['where']['stock_trans.location_id'] = $this->DISP_STORE->id;

        if(!empty($data['ref_id'])):
            $queryData['where']['stock_trans.id'] = $data['ref_id'];
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

    public function getCartoonNoList($data){
        $queryData['tableName'] = $this->palletTrans;
        $queryData['select'] = "cartoon_no,box_id,box_weight";
        $queryData['where']['order_number'] = $data['order_number'];
        $queryData['order_by']['cartoon_no'] = "ASC";
        $queryData['group_by'][] = "cartoon_no";
        $result = $this->rows($queryData);
        return $result;
    }

    public function getAnnexureDetail($data){
        $queryData = [];
        $queryData['tableName'] = $this->palletTrans;
        $queryData['select'] = "pallet_packing_trans.id, pallet_packing_trans.cartoon_no, pallet_packing_trans.box_id as cartoon_id, cartoon_master.item_name as cartoon_name, cartoon_master.size as cartoon_size, pallet_packing_trans.box_weight as cartoon_weight, stock_trans.item_id, item_master.item_code, item_master.item_name, item_master.wt_pcs, stock_trans.batch_no, stock_trans.opt_qty, stock_trans.qty, pallet_packing_trans.box_qty, packing_master.box_weight";

        $queryData['leftJoin']['stock_trans'] = "stock_trans.id = pallet_packing_trans.ref_id";
        $queryData['leftJoin']['packing_master'] = "packing_master.packing_number = stock_trans.batch_no";
        $queryData['leftJoin']['item_master'] = "item_master.id = stock_trans.item_id";
        $queryData['leftJoin']['item_master as cartoon_master'] = "cartoon_master.id = pallet_packing_trans.box_id";

        $queryData['where']['pallet_packing_trans.order_number'] = $data['order_number'];
        $queryData['group_by'][] = "pallet_packing_trans.id";

        $queryData['order_by']['pallet_packing_trans.cartoon_no'] = "ASC";
        $queryData['order_by']['pallet_packing_trans.id'] = "ASC";
        //$queryData['order_by']['stock_trans.item_id'] = "ASC";

        $result = $this->rows($queryData);
        return $result;
    }

    public function getPackingListDetail($data = array()){
        $queryData = [];
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_trans.batch_no, SUM(stock_trans.qty) as qty, stock_trans.opt_qty, item_master.item_code, item_master.item_name, item_master.wt_pcs, box_master.size as box_size, packing_master.box_weight";

        $queryData['leftJoin']['item_master'] = "item_master.id = stock_trans.item_id";
        $queryData['leftJoin']['packing_master'] = "packing_master.packing_number = stock_trans.batch_no AND stock_trans.item_id = packing_master.item_id AND stock_trans.p_or_m = 1 AND packing_master.is_delete = 0";
        $queryData['leftJoin']['item_master as box_master'] = "box_master.id = packing_master.box_id";

        $queryData['where']['stock_trans.ref_no'] = $data['order_number'];
        $queryData['where']['stock_trans.entry_type'] = $this->data['entryData']->id;
        $queryData['where']['stock_trans.p_or_m'] = 1;
        $queryData['where']['stock_trans.location_id'] = $this->DISP_STORE->id;

        $queryData['group_by'][] = "box_master.id,stock_trans.item_id,stock_trans.opt_qty,packing_master.box_weight";
        
        return $this->rows($queryData);
    }

    public function saveFinalPacking($data){
        try{
            $this->db->trans_begin();
            
            $newBox = (empty($data['cartoon_no']))?1:0;
            $newNo = $data['cartoon_no'];
            if(empty($data['cartoon_no'])):
                $noList = $this->getCartoonNoList(['order_number'=>$data['order_number']]);
                $nextNo = (!empty($noList))?(max(array_column($noList,'cartoon_no'))):0;
            endif;
            $cartoon_qty = $data['cartoon_qty'];unset($data['cartoon_qty']);

            for($i=1;$i<=$cartoon_qty;$i++):
                if(empty($newNo)):
                    $data['cartoon_no'] = $nextNo = ($nextNo + 1);
                endif;

                $result = $this->store($this->palletTrans,$data,'Annexure Item');
            endfor;

            if($newBox):
                /* Box Stock Deduction */
                $stockTransData = array();
                $stockTransData = [
                    'id' => '',
                    'trans_type' =>'FPK',
                    'main_ref_id' => $result['id'],
                    'trans_date' => date("Y-m-d"),
                    'ref_no' => $data['order_number'],
                    'location_id' => $this->PACKING_STORE->id,
                    'batch_no' => "GB",
                    'item_id' => $data['box_id'],
                    'p_or_m' => -1,
                    'qty' => $cartoon_qty,
                    'remark' => 'PALLET PACKING'
                ];
                $this->store($this->stockTrans,$stockTransData);
            endif;

            if($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function removeAnnexureItem($id){
        try{
            $this->db->trans_begin();

            $this->remove($this->stockTrans,['main_ref_id'=>$id,'entry_type'=>$this->data['entryData']->id,'remark'=>'PALLET PACKING']);
            $result = $this->trash($this->palletTrans,['id'=>$id],"Annexure Item");

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getPendingDispatchOrders($data){
        $queryData['tableName'] = $this->dispatchOrder;
        $queryData['select'] = "so_trans.*, dispatch_order.order_number, dispatch_order.party_id, so_master.trans_number as so_number, so_master.trans_date as so_date, so_trans.cod_date as delivery_date, so_master.doc_no, item_master.item_code, item_master.item_name, dispatch_order.link_qty,(so_trans.qty - IFNULL(dt.dispatch_qty, 0)) as pending_qty,dispatch_order.entry_type as main_entry_type,IFNULL(ppt.pp_qty,0) as pallet_pck_qty, (dispatch_order.order_qty - IFNULL(ppt.pp_qty,0)) as pp_pending_qty,item_master.uom,so_master.doc_date,dispatch_order.so_id, 1 as stock_eff,trans_details.t_col_3 as delivery_address,party_master.party_name";
		
		if(!empty($data['group_by'])){
			$queryData['select'] .= ",GROUP_CONCAT(dispatch_order.id) as request_id";
		}else{
			$queryData['select'] .= ",dispatch_order.id as request_id";
		}
 
        $queryData['leftJoin']['party_master'] = "party_master.id = dispatch_order.party_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = dispatch_order.item_id";
        $queryData['leftJoin']['so_master'] = "so_master.id = dispatch_order.so_id AND so_master.is_delete = 0";
        $queryData['leftJoin']['so_trans'] = "so_trans.id = dispatch_order.so_trans_id  AND so_trans.is_delete = 0";
        $queryData['leftJoin']['(SELECT SUM(qty) AS dispatch_qty, ref_id FROM trans_child WHERE is_delete = 0 AND from_entry_type = 14 GROUP BY ref_id) AS dt'] = "dt.ref_id = so_trans.id"; 
        $queryData['leftJoin']['(SELECT pallet_packing_trans.do_id, SUM(st.opt_qty * pallet_packing_trans.box_qty) as pp_qty FROM pallet_packing_trans LEFT JOIN stock_trans as st ON st.id = pallet_packing_trans.ref_id WHERE pallet_packing_trans.is_delete = 0 GROUP BY pallet_packing_trans.do_id) as ppt'] = "ppt.do_id = dispatch_order.id";
        $queryData['leftJoin']['trans_details'] = "so_master.id = trans_details.main_ref_id AND trans_details.description = 'SO MASTER DETAILS' AND trans_details.table_name = '".$this->soMaster."'";
		
        $queryData['where']['dispatch_order.disp_trans_id'] = 0;
        $queryData['where']['(so_trans.qty - IFNULL(dt.dispatch_qty, 0)) >'] = 0;
        $queryData['where']['IFNULL(ppt.pp_qty,0) >'] = 0;
        
		if(!empty($data['party_id'])){ $queryData['where']['dispatch_order.party_id'] = $data['party_id']; }
		if(!empty($data['ref_id'])){ $queryData['where_in']['dispatch_order.id'] = $data['ref_id']; }
		
        if(!empty($data['group_by'])){
			$queryData['group_by'][] = $data['group_by'];
		} 
        $result = $this->rows($queryData);

        if(!empty($data['batchDetail'])):
            foreach($result as &$row):
                $postData = ["item_id" => $row->item_id, "party_id" => $row->party_id,'location_id'=>$this->DISP_STORE->id, 'stock_required'=>1, 'group_by'=>'location_id,batch_no,opt_qty'];
                $batchData = $this->itemStock->getItemStockBatchWise($postData);
                
                $batchDetail = [];$pendingQty = $row->pending_qty;
                foreach($batchData as $batch):
                    $batchId = trim(preg_replace('/[^A-Za-z0-9]/', '', $batch->batch_no)).floatval($batch->opt_qty).$batch->location_id.$batch->item_id;
                    $location_name = '['.$batch->store_name.'] '.$batch->location;
                    
                    if($pendingQty > 0):
                        $batchQty = ($pendingQty > $batch->qty)?$batch->qty:$pendingQty;
                        $batchDetail[] = [
                            'batch_qty' => $batchQty,
                            'opt_qty' => $batch->opt_qty,
                            'location_id' => $batch->location_id,
                            'batch_no' => $batch->batch_no,
                            'remark' => $batchId,
                            'box_qty' => floatval(($batchQty / $batch->opt_qty))
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

	public function pendingSODTRows($data){
        $data['tableName'] = $this->soTrans;
        $data['select'] = "so_trans.*,party_master.party_name,IFNULL(dt.dispatch_qty, 0.000) AS dispatch_qty,(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) as pending_qty,so_master.entry_type as main_entry_type,so_master.trans_number,so_master.trans_date,so_master.doc_no,so_master.party_id,item_master.item_name,ifnull(dispatch_order.do_qty,0) as do_qty, (so_trans.qty - IFNULL(dispatch_order.do_qty, 0)) as pending_do_qty,stock.stock_qty";

        $data['leftJoin']['so_master'] = "so_trans.trans_main_id = so_master.id";
		$data['leftJoin']['party_master'] = "so_master.party_id = party_master.id";
        $data['leftJoin']['item_master'] = "so_trans.item_id = item_master.id";
        $data['leftJoin']['(SELECT SUM(qty) AS dispatch_qty, ref_id FROM trans_child WHERE is_delete = 0 AND from_entry_type = 14 GROUP BY ref_id) AS dt'] = "dt.ref_id = so_trans.id";

        $data['leftJoin']['(SELECT so_trans_id,SUM(order_qty) as do_qty FROM dispatch_order WHERE is_delete = 0 GROUP BY so_trans_id) as dispatch_order'] = "so_trans.id = dispatch_order.so_trans_id";
		$data['leftJoin']['(SELECT SUM(stock_trans.qty * stock_trans.p_or_m) AS stock_qty,item_id FROM stock_trans WHERE is_delete = 0 AND location_id ='.$this->DISP_STORE->id.' GROUP BY item_id) as stock'] = "stock.item_id = so_trans.item_id";

        $data['where']['so_master.is_approve > '] = 0;
		$data['where']['so_trans.trans_status != '] = 2;
        $data['where']['(so_trans.qty - IFNULL(dispatch_order.do_qty, 0)) >'] = 0;
        $data['where']['(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) >'] = 0;

        $data['searchCol'][] = "";
		$data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "so_master.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(so_master.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "DATE_FORMAT(so_trans.cod_date,'%d-%m-%Y')";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "so_trans.qty";
        $data['searchCol'][] = "(so_trans.qty - dt.dispatch_qty)";
        $data['searchCol'][] = "(so_trans.qty - dispatch_order.do_qty)";
        $data['searchCol'][] = "stock.stock_qty";
		
        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getPendingDODTRows($data){
        $data['tableName'] = $this->dispatchOrder;
        $data['select'] = "dispatch_order.id, dispatch_order.order_number, dispatch_order.order_prefix, dispatch_order.order_no, dispatch_order.order_date, dispatch_order.party_id, so_master.trans_number as so_number, so_master.trans_date as so_date, so_trans.cod_date as delivery_date, dispatch_order.party_id, party_master.party_name, dispatch_order.item_id, item_master.item_code, item_master.item_name, dispatch_order.order_qty, dispatch_order.link_qty,(so_trans.qty - IFNULL(dt.dispatch_qty, 0)) as pending_qty";

        $data['leftJoin']['party_master'] = "party_master.id = dispatch_order.party_id";
        $data['leftJoin']['item_master'] = "item_master.id = dispatch_order.item_id";
        $data['leftJoin']['so_master'] = "so_master.id = dispatch_order.so_id";
        $data['leftJoin']['so_trans'] = "so_trans.id = dispatch_order.so_trans_id";
		$data['leftJoin']['(SELECT SUM(qty) AS dispatch_qty, ref_id FROM trans_child WHERE is_delete = 0 AND from_entry_type = 14 GROUP BY ref_id) AS dt'] = "dt.ref_id = so_trans.id";
		$data['leftJoin']['(SELECT pallet_packing_trans.do_id, SUM(st.opt_qty * pallet_packing_trans.box_qty) as pp_qty FROM pallet_packing_trans LEFT JOIN stock_trans as st ON st.id = pallet_packing_trans.ref_id WHERE pallet_packing_trans.is_delete = 0 GROUP BY pallet_packing_trans.do_id) as ppt'] = "ppt.do_id = dispatch_order.id";
    
        $data['where']['dispatch_order.disp_trans_id'] = 0;
        $data['where']['(so_trans.qty - IFNULL(dt.dispatch_qty, 0)) >'] = 0;
        $data['where']['(IFNULL(ppt.pp_qty,0)) > '] = 0;
        $data['order_by']['dispatch_order.order_date'] = "DESC";
        $data['order_by']['dispatch_order.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "dispatch_order.order_number";
        $data['searchCol'][] = "DATE_FORMAT(dispatch_order.order_date,'%d-%m-%Y')";
        $data['searchCol'][] = "so_master.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(so_master.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "DATE_FORMAT(so_trans.cod_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "(so_trans.qty - IFNULL(dt.dispatch_qty, 0))";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }
}
?>