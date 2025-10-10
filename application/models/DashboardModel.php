<?php
class DashboardModel extends MasterModel{
    
    public function getRevenue($data){
		$formDate = (!empty($data['from_date']))?$data['from_date']:$this->startYearDate;
		$toDate = (!empty($data['to_date']))?$data['to_date']:$this->endYearDate;

		$queryData = [];
		$queryData['tableName'] = "trans_main";
		$queryData['select'] = "SUM(taxable_amount) as total_revenue";
		$queryData['where']['trans_date >='] = $formDate;
		$queryData['where']['trans_date <='] = $toDate;
		$queryData['where']['trans_status !='] = 3;
		$queryData['where_in']['vou_name_s'] = $data['vou_name_s'];
		$queryData['where_in']['cm_id'] = (!empty($data['cm_id']))?$data['cm_id']:$this->cm_ids;
		$result = $this->row($queryData);

		return $result;
	}

	public function getOrderAvgValue($data){
		$queryData = [];
		$queryData['tableName'] = "trans_main";
		$queryData['select'] = "(SUM(taxable_amount) / COUNT(id)) as ord_avg_value";
		$queryData['where']['trans_date >='] = $this->startYearDate;
		$queryData['where']['trans_date <='] = $this->endYearDate;
		$queryData['where_in']['vou_name_s'] = $data['vou_name_s'];
		$queryData['where_in']['cm_id'] = (!empty($data['cm_id']))?$data['cm_id']:$this->cm_ids;
		$result = $this->row($queryData);

		return $result;
	}

	public function getTodayOrder($data){
		$queryData = [];
		$queryData['tableName'] = "trans_main";
		$queryData['select'] = "COUNT(id) as today_orders";
		$queryData['where']['trans_date'] = getFyDate();
		$queryData['where_in']['vou_name_s'] = $data['vou_name_s'];
		$queryData['where_in']['cm_id'] = (!empty($data['cm_id']))?$data['cm_id']:$this->cm_ids;
		$result = $this->row($queryData);

		return $result;
	}

	public function getConversionRate($data){
		$result = $this->db->query("
			SELECT 
				(CASE WHEN cs.total_sales_amount <> 0 AND cs.total_order_amount <> 0 THEN ((cs.total_sales_amount * 100) / cs.total_order_amount) ELSE 0 END) as conversion_rate
			FROM (
				SELECT 
					SUM((CASE WHEN vou_name_s = 'SOrd' THEN taxable_amount ELSE 0 END)) as total_order_amount,
					SUM((CASE WHEN vou_name_s = 'Sale' THEN taxable_amount ELSE 0 END)) as total_sales_amount
				FROM trans_main
				WHERE is_delete = 0
				AND trans_status != 3
				AND vou_name_s IN ('Sale','SOrd')
				AND cm_id IN (".((!empty($data['cm_id']))?$data['cm_id']:$this->cm_ids).")
				AND trans_date >= '".$this->startYearDate."'
				AND trans_date <= '".$this->endYearDate."'
			) as cs
		")->row();

		return $result;
	}

	public function getOutstanding($data){
		$cm_id = (!empty($data['cm_id']))?$data['cm_id']:$this->cm_ids;
        $party_balance_condition = " AND party_balance.cm_id IN (".$cm_id.") ";
        $ledger_balance_condition = " AND tl.cm_id IN (".$cm_id.") ";

		$result = $this->db->query("
			SELECT  
				SUM((CASE WHEN lb.cl_balance < 0 THEN abs(lb.cl_balance) ELSE 0 END)) as receivable, 
				SUM((CASE WHEN lb.cl_balance > 0 THEN abs(lb.cl_balance) ELSE 0 END)) as payable 
        	FROM (
				SELECT am.id, 
					(ifnull(pb.op_balance,0) + SUM( CASE WHEN tl.trans_date <= '".$this->endYearDate."' THEN (tl.amount * tl.p_or_m) ELSE 0 END )) as cl_balance          
				FROM party_master as am 
				LEFT JOIN trans_ledger as tl ON am.id = tl.vou_acc_id  AND tl.is_delete = 0  ".$ledger_balance_condition."
				LEFT JOIN (
					SELECT party_id,SUM(ifnull(op_balance,0)) as op_balance 
					FROM party_balance 
					WHERE is_delete = 0 
					".$party_balance_condition." 
					GROUP BY party_id
				) as pb ON pb.party_id = am.id       
				WHERE am.group_code IN ( 'SD','SC' ) 
				AND am.is_delete = 0 
				GROUP BY am.id
			) as lb
		")->row();

		return $result;
	}

	public function getMonthWiseSummary($data){
        $formDate = (!empty($data['from_date']))?$data['from_date']:$this->startYearDate;
		$toDate = (!empty($data['to_date']))?$data['to_date']:$this->endYearDate;
        $vouName = $data['vou_name_s'];

        $this->db->query("set @start_date = '".$formDate."';");
        $this->db->query("set @end_date = '".$toDate."';");
        $this->db->query("set @months = -1;");

        $result = $this->db->query("
			SELECT 
				DATE_FORMAT(monthList.date_range,'%b, %Y') AS month_name, 
				tm.total_taxable_amount
			FROM (
				SELECT (date_add(@start_date, INTERVAL (@months := @months +1 ) month)) as date_range
				FROM information_schema.COLUMNS monthList
			) monthList
			LEFT JOIN (
				SELECT DATE_FORMAT(trans_date,'%Y-%m') as month_name, SUM(taxable_amount) as total_taxable_amount
				FROM trans_main 
				WHERE is_delete = 0
				AND vou_name_s IN (".$vouName.")
				AND trans_date >= '".$formDate."'
				AND trans_date <= '".$toDate."'
				AND trans_status != 3
				AND cm_id IN (".((!empty($data['cm_id']))?$data['cm_id']:$this->cm_ids).")
				GROUP BY  DATE_FORMAT(trans_date,'%Y-%m')
			) AS tm ON tm.month_name = DATE_FORMAT(monthList.date_range,'%Y-%m')
			WHERE monthList.date_range BETWEEN @start_date AND last_day(@end_date)
		")->result();

        return $result;
    }

	public function getTopSellingStateList($data){
		$queryData = [];

		$queryData['tableName'] = "trans_main";
		$queryData['select'] = "(CASE WHEN states.gst_statecode <> 0 THEN CONCAT(states.gst_statecode,' - ',states.name) ELSE states.name END) as state_name, SUM(trans_main.taxable_amount) as amount";
		$queryData['leftJoin']['states'] = "states.gst_statecode = trans_main.party_state_code";
		$queryData['where']['trans_main.trans_date >='] = $this->startYearDate;
		$queryData['where']['trans_main.trans_date <='] = $this->endYearDate;
		$queryData['where']['trans_main.trans_status !='] = 3;
		$queryData['where_in']['trans_main.vou_name_s'] = $data['vou_name_s'];
		$queryData['where_in']['trans_main.cm_id'] = (!empty($data['cm_id']))?$data['cm_id']:$this->cm_ids;
		$queryData['group_by'][] = "states.name";
		$queryData['order_by']['SUM(trans_main.taxable_amount)'] = "DESC";
		$queryData['limit'] = 10;
		$result = $this->rows($queryData);

		return $result;
	}

	public function getTopSellingCustomerList($data){
		$queryData = [];

		$queryData['tableName'] = "trans_main";
		$queryData['select'] = "trans_main.party_name, SUM(trans_main.taxable_amount) as amount";
		$queryData['where']['trans_main.trans_date >='] = $this->startYearDate;
		$queryData['where']['trans_main.trans_date <='] = $this->endYearDate;
		$queryData['where']['trans_main.trans_status !='] = 3;
		$queryData['where_in']['trans_main.vou_name_s'] = $data['vou_name_s'];
		$queryData['where_in']['trans_main.cm_id'] = (!empty($data['cm_id']))?$data['cm_id']:$this->cm_ids;
		$queryData['group_by'][] = "trans_main.party_name";
		$queryData['order_by']['SUM(trans_main.taxable_amount)'] = "DESC";
		$queryData['limit'] = 10;
		$result = $this->rows($queryData);

		return $result;
	}

	public function getTopSellingProductList($data){
		$queryData = [];

		$queryData['tableName'] = "trans_child";
		$queryData['select'] = "trans_child.item_name as product_name, SUM(trans_child.qty) as qty";
		$queryData['leftJoin']['trans_main'] = "trans_child.trans_main_id = trans_main.id";
		$queryData['where']['trans_main.trans_date >='] = $this->startYearDate;
		$queryData['where']['trans_main.trans_date <='] = $this->endYearDate;
		$queryData['where']['trans_main.trans_status !='] = 3;
		$queryData['where_in']['trans_main.vou_name_s'] = $data['vou_name_s'];
		$queryData['where_in']['trans_main.cm_id'] = (!empty($data['cm_id']))?$data['cm_id']:$this->cm_ids;
		$queryData['group_by'][] = "trans_child.item_name";
		$queryData['order_by']['SUM(trans_child.qty)'] = "DESC";
		$queryData['limit'] = 10;
		$result = $this->rows($queryData);

		return $result;
	}

	public function getProductCategoryList($data){
		$todayDate = getFyDate();
		$current_date = new DateTime($todayDate);
		$current_date->modify('-1 day');
		$yesterdayDate = $current_date->format('Y-m-d');

		$result = $this->db->query("
			SELECT 
				cs.category_name, cs.today_amount, cs.yesterday_amount, (CASE WHEN cs.yesterday_amount <> 0 THEN ROUND((((cs.today_amount - cs.yesterday_amount) / cs.yesterday_amount) * 100),2) ELSE 0 END) as diff_per
			FROM(
				SELECT
					trans_child.brand_name as category_name,
					SUM((CASE WHEN trans_main.trans_date = '".$todayDate."' THEN trans_child.qty ELSE 0 END)) as today_amount,
					SUM((CASE WHEN trans_main.trans_date = '".$yesterdayDate."' THEN trans_child.qty ELSE 0 END)) as yesterday_amount
				FROM trans_child
				LEFT JOIN trans_main ON trans_main.id = trans_child.trans_main_id
				WHERE trans_child.is_delete = 0
				AND trans_main.trans_date >= '".$yesterdayDate."'
				AND trans_main.trans_date <= '".$todayDate."'
				AND trans_main.trans_status != 3
				AND trans_main.vou_name_s = 'Sale'
				AND trans_main.cm_id IN (".((!empty($data['cm_id']))?$data['cm_id']:$this->cm_ids).") 
				GROUP BY trans_child.brand_name
			) as cs
		")->result();
		
		return $result;
	}	
}
?>