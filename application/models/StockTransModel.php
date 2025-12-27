<?php
class StockTransModel extends MasterModel{
    private $stockTrans = "stock_trans";

    public function getItemStockBatchWise($data){
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_trans.id,stock_trans.trans_date,stock_trans.item_id, item_master.item_code, item_master.item_name, SUM(stock_trans.qty * stock_trans.p_or_m) as qty,stock_trans.batch_no,stock_trans.location_id, lm.location, lm.store_name,stock_trans.remark,item_master.uom";
        
        $queryData['leftJoin']['location_master as lm'] = "lm.id=stock_trans.location_id";
        $queryData['leftJoin']['item_master'] = "stock_trans.item_id = item_master.id";

        if(!empty($data['supplier'])){
			$queryData['select'] .= ",party_master.party_name,batch_history.heat_no,batch_history.party_id";
			$queryData['leftJoin']['batch_history'] = "stock_trans.item_id = batch_history.item_id AND stock_trans.batch_no = batch_history.batch_no AND batch_history.is_delete = 0";
			$queryData['leftJoin']['party_master'] = "batch_history.party_id = party_master.id";	
		}
        if(!empty($data['item_id'])): 
            $queryData['where']['stock_trans.item_id'] = $data['item_id'];           
        endif;

        if(!empty($data['location_id'])):
            $queryData['where']['stock_trans.location_id'] = $data['location_id'];
        endif;

        if(!empty($data['batch_no'])):
            $queryData['where']['stock_trans.batch_no'] = $data['batch_no'];
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

        if(!empty($data['opt_qty'])):
            $queryData['where']['stock_trans.opt_qty'] = $data['opt_qty'];
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

        if(!empty($data['location_ids'])):
            $queryData['where_in']['stock_trans.location_id'] = $data['location_ids'];
        endif;
        
        if(!empty($data['not_in_location'])):
            $queryData['where_not_in']['stock_trans.location_id'] = $data['not_in_location'];
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
}
?>