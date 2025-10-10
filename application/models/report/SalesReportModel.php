<?php
class SalesReportModel extends MasterModel{
    private $soMaster = "so_master";
    private $soTrans = "so_trans";
    private $transMain = "trans_main";
    private $transChild = "trans_child";

    public function getOrderMonitoringData($data){
        $queryData = array();
        $queryData['tableName'] = $this->soMaster;
        $queryData['select'] = "so_master.trans_number,so_master.trans_date,so_trans.qty,item_master.item_name,party_master.party_name,so_trans.id,so_trans.cod_date,item_master.uom"; 
        $queryData['leftJoin']['so_trans'] = "so_trans.trans_main_id = so_master.id";
        $queryData['leftJoin']['party_master'] = "so_master.party_id = party_master.id";
        $queryData['leftJoin']['item_master'] = "so_trans.item_id = item_master.id";
        $queryData['customWhere'][] = "so_master.trans_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
        if(!empty($data['party_id'])):
            $queryData['where']['so_master.party_id'] = $data['party_id'];
        endif;
        $queryData['order_by']['so_master.trans_date'] = "ASC";
        $queryData['order_by']['so_master.trans_number'] = "ASC";

        $result = $this->rows($queryData);
        return $result;
    }

    public function getSalesInvData($data){
        $queryData = array();
        $queryData['tableName'] = $this->transMain;
        $queryData['select'] = "trans_main.trans_number as invNo,trans_main.trans_date as invDate,trans_child.qty as invQty,trans_child.ref_id";
        $queryData['leftJoin']['trans_child'] = "trans_main.id = trans_child.trans_main_id AND trans_child.is_delete = 0";
		$queryData['where']['trans_child.ref_id'] = $data['ref_id'];
        $queryData['where']['trans_child.from_entry_type'] = 14;
        $result = $this->rows($queryData);
        return $result;
    }

    public function getSalesAnalysisData($data){
        $queryData = array();
        if($data['report_type'] == 1):
            $queryData['tableName'] = $this->transMain;
            $queryData['select'] = "party_name,SUM(taxable_amount) as taxable_amount,SUM(gst_amount) as gst_amount,SUM(net_amount) as net_amount";
            $queryData['where']['trans_date >='] = $data['from_date'];
            $queryData['where']['trans_date <='] = $data['to_date'];
            $queryData['where']['vou_name_s'] = "Sale";
            $queryData['group_by'][] = 'party_id';
            $queryData['order_by']['SUM(taxable_amount)'] = $data['order_by'];
            $result = $this->rows($queryData);
        else:
            $queryData['tableName'] = $this->transChild;
            $queryData['select'] = "trans_child.item_name,SUM(trans_child.qty) as qty,SUM(trans_child.taxable_amount) as taxable_amount,ROUND((SUM(trans_child.taxable_amount) / SUM(trans_child.qty)),2) as price";
            $queryData['leftJoin']['trans_main'] = "trans_child.trans_main_id = trans_main.id";
            $queryData['where']['trans_date >='] = $data['from_date'];
            $queryData['where']['trans_date <='] = $data['to_date'];
            $queryData['where']['vou_name_s'] = "Sale";
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

    public function getMrpReportData($data) {
        $queryData['tableName'] = $this->soTrans;  
        $queryData['select'] = 'so_master.trans_number,so_master.trans_date,bom.item_name as bom_item_name,(stock_data.stock_qty / item_kit.qty) AS plan_qty,bom.uom'; 
        $queryData['leftJoin']['so_master'] = "so_trans.trans_main_id = so_master.id";
        $queryData['leftJoin']['item_kit'] = "item_kit.item_id = so_trans.item_id AND item_kit.is_delete = 0";
        $queryData['leftJoin']['item_master AS bom'] = "bom.id = item_kit.ref_item_id";
        $queryData['leftJoin']['(SELECT SUM(qty) AS dispatch_qty,`so_id` FROM `delivery_transaction` WHERE `delivery_transaction`.`is_delete` = 0 GROUP BY `delivery_transaction`.`so_id`) AS dt'] = "dt.so_id = so_trans.id";
        $queryData['leftJoin']['(SELECT SUM(`stock_trans`.`qty` * `stock_trans`.`p_or_m`) AS stock_qty,`stock_trans`.`item_id` FROM `stock_trans` WHERE is_delete = 0 GROUP BY `stock_trans`.`item_id`) AS stock_data'] = 'stock_data.item_id = item_kit.ref_item_id';
        if(!empty($data['party_id']) && $data['party_id'] != 'ALL'){ $queryData['where']['so_master.party_id'] = $data['party_id']; }
        if(!empty($data['item_id'])){ $queryData['where']['so_trans.item_id'] = $data['item_id']; }
        $queryData['where']['(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) >'] = 0;
        $queryData['order_by']['so_master.trans_no'] = 'ASC';
        $queryData['order_by']['so_trans.id'] = 'ASC';
        return $this->rows($queryData);
    }

	public function getSalesOrderRegister($data){
        $queryData = array();
        $queryData['tableName'] = $this->soTrans;
        $queryData['select'] = "so_master.trans_number,so_master.trans_date,so_trans.qty,item_master.item_name,party_master.party_name,so_trans.id,so_trans.trans_status,ifnull(st.stock_qty,0) as stock_qty, IFNULL(dt.dispatch_qty, 0.000) AS dispatch_qty, IF((so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) < 0, 0, (so_trans.qty - IFNULL(dt.dispatch_qty, 0.000))) as pending_qty,item_master.uom";
        $queryData['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id";
        $queryData['leftJoin']['party_master'] = "so_master.party_id = party_master.id";
        $queryData['leftJoin']['item_master'] = "so_trans.item_id = item_master.id";
        $queryData['leftJoin']['(SELECT SUM(qty * p_or_m) as stock_qty,item_id FROM stock_trans WHERE is_delete = 0 GROUP BY item_id) as st'] = "so_trans.item_id = st.item_id";
        $queryData['leftJoin']['(SELECT SUM(qty) AS dispatch_qty, ref_id FROM trans_child WHERE is_delete = 0 AND from_entry_type = 14 GROUP BY ref_id) AS dt'] = "dt.ref_id = so_trans.id";
        $queryData['customWhere'][] = "so_master.trans_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
        if(!empty($data['party_id'])):
            $queryData['where']['so_master.party_id'] = $data['party_id'];
        endif;
        if($data['trans_status'] != 'All'):
			if($data['trans_status'] == 0):
				$queryData['where']['(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) >'] = 0;
				$queryData['where']['so_trans.trans_status != '] = 2;
			elseif($data['trans_status'] == 1):
				$queryData['where']['(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) <='] = 0;
				$queryData['where']['so_trans.trans_status != '] = 2;
			elseif($data['trans_status'] == 2):
				$queryData['where']['so_trans.trans_status'] = 2;
			endif;	
        endif;
        $queryData['order_by']['so_master.trans_date'] = "ASC";
        $queryData['order_by']['so_master.trans_number'] = "ASC";

        $result = $this->rows($queryData);
        return $result;
    }
}
?>