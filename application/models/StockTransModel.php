<?php
class StockTransModel extends MasterModel{
    private $stockTrans = "stock_trans";

    public function getDTRows($data){
        $data['tableName'] = $this->stockTrans;
        $data['select'] = "stock_trans.*,item_master.item_code,item_master.item_name";

        $data['leftJoin']['item_master'] = "item_master.id = stock_trans.item_id";

        $data['where']['stock_trans.p_or_m'] = 1;

		if(!empty($data['trans_type']))$data['where']['stock_trans.trans_type'] = $data['trans_type'];
        if(!empty($data['item_type']))$data['where']['item_master.item_type'] = $data['item_type'];
        $data['where']['stock_trans.trans_date >='] = $this->startYearDate;
        $data['where']['stock_trans.trans_date <='] = $this->endYearDate;
        
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "DATE_FORMAT(stock_trans.trans_date,'%d-%m-%Y')";
        //$data['searchCol'][] = "item_master.item_code";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "stock_trans.qty";
        //$data['searchCol'][] = "stock_trans.size";
        $data['searchCol'][] = "stock_trans.remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            unset($data['item_type']);
            $result = $this->store($this->stockTrans,$data,'Stock');
        
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $transData = $this->getStockTrans(['id'=>$id]);
            $itemStock = $this->getItemCurrentStock(['item_id'=>$transData->item_id]);
            if($transData->qty > $itemStock->qty):
                return ['status'=>0,'message'=>'Item Stock Used. You cant delete this record.'];
            endif;

            $result = $this->trash($this->stockTrans,['id'=>$id],'Stock');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getStockTrans($data){
        $queryData['tableName'] = $this->stockTrans;
        $queryData['where']['id'] = $data['id'];
        return $this->row($queryData);
    }

    // Get Single Item Stock From Stock Transaction
    public function getItemCurrentStock($data){
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "SUM(qty * p_or_m) as qty";
        $queryData['where']['item_id'] = $data['item_id'];
        return $this->row($queryData);
    }

    /* Created At : 09-12-2022 [Milan Chauhan] */
    public function getItemStockBatchWise($data){
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_trans.id, stock_trans.trans_date, stock_trans.item_id, item_master.item_code, item_master.item_name, SUM(stock_trans.qty * stock_trans.p_or_m) as qty, stock_trans.opt_qty, stock_trans.location_id, lm.location, lm.store_name, stock_trans.remark";
        
        $queryData['leftJoin']['location_master as lm'] = "lm.id=stock_trans.location_id";
        $queryData['leftJoin']['item_master'] = "stock_trans.item_id = item_master.id";


        if(!empty($data['item_id'])): 
            $queryData['where']['stock_trans.item_id'] = $data['item_id'];           
        endif;

        if(!empty($data['location_id'])):
            $queryData['where']['stock_trans.location_id'] = $data['location_id'];
        endif;

        if(!empty($data['location_ids'])):
            $queryData['where_in']['stock_trans.location_id'] = $data['location_ids'];
        endif;

        // if(!empty($data['batch_no'])):
        //     $queryData['where']['stock_trans.batch_no'] = $data['batch_no'];
        // endif;

        if(!empty($data['opt_qty'])):
            $queryData['where']['stock_trans.opt_qty'] = $data['opt_qty'];
        endif;
        
        if(!empty($data['p_or_m'])):
            $queryData['where']['stock_trans.p_or_m'] = $data['p_or_m'];
        endif;

        if(!empty($data['trans_type'])):
            $queryData['where_in']['stock_trans.trans_type'] = $data['trans_type'];
        endif;

        if(!empty($data['entry_type'])):
            $queryData['where_in']['stock_trans.trans_type'] = $data['entry_type'];
        endif;
        
        if(!empty($data['main_ref_id'])):
            $queryData['where']['stock_trans.main_ref_id'] = $data['main_ref_id'];
        endif;

        if(!empty($data['child_ref_id'])):
            $queryData['where']['stock_trans.child_ref_id'] = $data['child_ref_id'];
        endif;

        if(!empty($data['ref_no'])):
            $queryData['where']['stock_trans.ref_no'] = $data['ref_no'];
        endif;

        if(!empty($data['remark'])):
            $queryData['where']['stock_trans.remark'] = $data['remark'];
        endif;

        if(!empty($data['location_ids'])):
            $queryData['where_in']['stock_trans.location_id'] = $data['location_ids'];
        endif;
        
        if(!empty($data['customWhere'])):
            $queryData['customWhere'][] = $data['customWhere'];
        endif;
        
        if(!empty($data['stock_required'])):
            if(!empty($data['customHaving'])):
                $queryData['having'][] = $data['customHaving'];
            else:
                $queryData['having'][] = 'SUM(stock_trans.qty * stock_trans.p_or_m) > 0';
            endif;
        endif;

        //$queryData['where']['lm.final_location'] = 0;
        if(!empty($data['group_by'])){
            $queryData['group_by'][] = $data['group_by'];
        }

        if(isset($data['semi_stock']) && $data['semi_stock'] ==1):
            $queryData['where']['stock_trans.location_id !='] = $this->RTD_STORE->id;
        endif;
    
      
        $queryData['order_by']['lm.location'] = "ASC";

        if(isset($data['single_row']) && $data['single_row'] == 1):
            $stockData = $this->row($queryData);
        else:
            $stockData = $this->rows($queryData);
        endif;
        return $stockData;
    }

    /** For App */
    public function getStockDataForApp(){
        $data['tableName'] = $this->stockTrans;
        $data['select'] = "stock_trans.*,item_master.item_code,item_master.item_name";
        $data['leftJoin']['item_master'] = "item_master.id = stock_trans.item_id";
        $data['where']['stock_trans.p_or_m'] = 1;

        $data['where']['stock_trans.ref_date >='] = ("2023-10-18" < $this->startYearDate)?$this->startYearDate:"2023-10-18";
        //$data['where']['stock_trans.ref_date >='] = $this->startYearDate;
        $data['where']['stock_trans.ref_date <='] = $this->endYearDate;
        
        $data['order_by']['stock_trans.ref_date'] = 'DESC';
        return $this->rows($data);
    }
    
    public function saveStockTransfer($data){
        try{
            $this->db->trans_begin();

            $stockMinus = [
                'id'=>'',
                'ref_date'=>date("Y-m-d"),
                'location_id'=>$data['location_id'],
                
                'qty'=>$data['batch_qty'],
                'item_id'=>$data['item_id'],
                'entry_type'=>1002,
                'p_or_m'=> -1,
            ];
            $resultMinus = $this->store($this->stockTrans,$stockMinus);

            $stockPlus = [
                'id'=>'',
                'ref_date'=>date("Y-m-d"),
                'main_ref_id'=>$resultMinus['id'],
                'location_id'=>0,
                'qty'=>$data['batch_qty'],
                'item_id'=>$data['item_id'],
                'entry_type'=>1002,
                'p_or_m'=> 1,
            ];
            $result = $this->store($this->stockTrans,$stockPlus);
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function getStockData($param = []){
        $data['tableName'] = $this->stockTrans;
        $data['select'] = "stock_trans.*,item_master.item_code,item_master.item_name,item_master.uom";
        $data['leftJoin']['item_master'] = "item_master.id = stock_trans.item_id";
        if(!empty($param['p_or_m'])){$data['where']['stock_trans.p_or_m'] = $param['p_or_m'];}
        if(!empty($param['entry_type'])){$data['where']['stock_trans.entry_type'] = $param['entry_type'];}
        if(isset($param['location_id'])){$data['where']['stock_trans.location_id'] = $param['location_id'];}
        if(!empty($param['skey'])){
			$data['like']['stock_trans.ref_date'] = str_replace(" ", "%", $param['skey']);
			$data['like']['stock_trans.qty'] = str_replace(" ", "%", $param['skey']);
			$data['like']['item_master.item_name'] = str_replace(" ", "%", $param['skey']);
			$data['like']['item_master.item_code'] = str_replace(" ", "%", $param['skey']);
        }

        if(!empty($param['limit'])){ $data['limit'] = $param['limit']; }
        
		if(isset($param['start'])){ $data['start'] = $param['start']; }
		
		if(!empty($param['length'])){ $data['length'] = $param['length']; }
		
		$data['order_by']['stock_trans.ref_date'] = 'DESC';
		$data['order_by']['stock_trans.id'] = 'DESC';
        return $this->rows($data);
    }

	public function getItemInwardDTRows($data){
        $data['tableName'] = $this->stockTrans;
        $data['select'] = "stock_trans.*,item_master.item_code,item_master.item_name,item_master.item_type,location_master.location,item_master.uom";
        $data['leftJoin']['item_master'] = "item_master.id = stock_trans.item_id";
        $data['leftJoin']['location_master'] = "location_master.id = stock_trans.location_id";

        $data['where']['stock_trans.p_or_m'] = 1;
        //$data['where_in']['item_master.item_type'] = '2,3';
		if(!empty($data['trans_type'])){ $data['where']['stock_trans.trans_type'] = $data['trans_type']; }

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "location_master.location";
        $data['searchCol'][] = "CONCAT(stock_trans.qty,' ',item_master.uom)";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }
}
?>