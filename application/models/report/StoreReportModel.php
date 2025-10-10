<?php
class StoreReportModel extends MasterModel{
    private $itemMaster = "item_master";
    private $stockTrans = "stock_trans";

	public function getStockRegisterData($data){      
        $queryData = array();
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.id as item_id,item_master.item_code,item_master.item_name,item_master.uom,ifnull(st.stock_qty,0) as stock_qty";
        $queryData['leftJoin']['(SELECT SUM(qty * p_or_m) as stock_qty,item_id,location_id FROM stock_trans WHERE is_delete = 0 GROUP BY item_id) as st'] = "item_master.id = st.item_id";
        $queryData['where']['item_master.item_type'] = 1;
        
        if(!empty($data['item_id'])):
            $queryData['where']['item_master.id'] = $data['item_id'];
        endif;

        $result = $this->rows($queryData);		
        return $result;
    }

    
	public function getItemSummary($data){

        $queryData = array();
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.id,item_master.item_code,item_master.item_name,ifnull(st.op_stock_qty,0) as op_stock_qty,ifnull(st.in_stock_qty,0) as in_stock_qty,ifnull(st.out_stock_qty,0) as out_stock_qty,ifnull(st.cl_stock_qty,0) as cl_stock_qty";

        $queryData['leftJoin']['(SELECT item_id,
        SUM((CASE WHEN trans_date < "'.$data['from_date'].'" THEN (qty * p_or_m) ELSE 0 END)) as op_stock_qty,
        
        SUM((CASE WHEN trans_date >= "'.$data['from_date'].'" AND trans_date <= "'.$data['to_date'].'" AND p_or_m = 1 THEN qty ELSE 0 END)) as in_stock_qty,
        
        SUM((CASE WHEN trans_date >= "'.$data['from_date'].'" AND trans_date <= "'.$data['to_date'].'" AND p_or_m = -1 THEN qty ELSE 0 END)) as out_stock_qty,
        
        SUM((CASE WHEN trans_date <= "'.$data['to_date'].'" THEN (qty * p_or_m) ELSE 0 END)) as cl_stock_qty

        FROM stock_trans WHERE is_delete = 0 GROUP BY item_id) as st'] = "item_master.id = st.item_id";

        if(!empty($data['item_id'])):
            $queryData['where']['item_master.id'] = $data['item_id'];
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }	
	
	public function getItemHistory($data){
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = 'item_master.item_code,item_master.item_name,stock_trans.*,(CASE WHEN stock_trans.p_or_m = 1 THEN stock_trans.qty ELSE 0 END) as in_qty,(CASE WHEN stock_trans.p_or_m = -1 THEN stock_trans.qty ELSE 0 END) as out_qty,employee_master.emp_name';
        $queryData['leftJoin']['item_master'] = "item_master.id = stock_trans.item_id";
		$queryData['leftJoin']['employee_master'] = "employee_master.id = stock_trans.created_by";

        if(!empty($data['item_id'])) { $queryData['where']['stock_trans.item_id'] = $data['item_id']; }
		
        if(!empty($data['from_date'])) { $queryData['where']['stock_trans.trans_date >='] = $data['from_date']; }

        if(!empty($data['to_date'])) { $queryData['where']['stock_trans.trans_date <='] = $data['to_date']; }
      
        $queryData['order_by']['stock_trans.id'] = 'ASC';

		$result = $this->rows($queryData);
		return $result;
    }
}
?>