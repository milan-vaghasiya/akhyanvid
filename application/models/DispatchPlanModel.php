<?php
class DispatchPlanModel extends MasterModel{

    public function getDTRows($data){
        $entryData = $this->transMainModel->getEntryType(['controller'=>'salesOrders','tableName'=>'so_master']);
        $data['tableName'] = 'so_trans';
        $data['select'] = "so_trans.id as trans_child_id,so_trans.item_id,item_master.item_name,so_trans.qty,so_trans.cod_date, IFNULL(dt.dispatch_qty, 0.000) AS dispatch_qty, IF((so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) < 0, 0, (so_trans.qty - IFNULL(dt.dispatch_qty, 0.000))) as pending_qty, so_master.id,so_master.trans_number,DATE_FORMAT(so_master.trans_date,'%d-%m-%Y') as trans_date,party_master.party_name,so_trans.trans_status,party_master.sales_executive,so_master.party_id,so_master.is_approve,IFNULL(dispatchPlan.plan_qty,0) AS plan_qty,IFNULL(st.stock_qty,0) stock_qty,item_master.uom";

        $data['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id";
        $data['leftJoin']['item_master'] = "item_master.id = so_trans.item_id";
        $data['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
        $data['leftJoin']['(SELECT SUM(qty) AS dispatch_qty, ref_id FROM trans_child WHERE is_delete = 0 AND from_entry_type = 14 GROUP BY ref_id) AS dt'] = "dt.ref_id = so_trans.id";
        $data['leftJoin']['(SELECT SUM(prc_qty) AS plan_qty, so_trans_id FROM prc_master WHERE is_delete = 0  GROUP BY so_trans_id) AS dispatchPlan'] = "dispatchPlan.so_trans_id = so_trans.id";
		$data['leftJoin']['(SELECT SUM(qty * p_or_m) as stock_qty,item_id FROM stock_trans WHERE is_delete = 0 GROUP BY item_id) as st'] = "so_trans.item_id = st.item_id";

        $data['where']['so_trans.entry_type'] = $entryData->id;

        $data['where']['(so_trans.qty - IFNULL(dt.dispatch_qty, 0.000)) >'] = 0;
        $data['where']['so_trans.trans_status != '] = 2;
        $data['where']['so_master.trans_date <='] = $this->endYearDate;

        $data['order_by']['so_master.trans_date'] = "DESC";
        $data['order_by']['so_master.id'] = "DESC";

        $data['group_by'][] = "so_trans.id";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "so_master.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(so_master.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "DATE_FORMAT(so_trans.cod_date)";
        $data['searchCol'][] = "";
		$data['searchCol'][] = "so_trans.qty";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getPlanDTRows($data){
        $data['tableName'] = 'prc_master';
        $data['select'] = "prc_master.*,so_master.trans_date,so_master.trans_number,item_master.item_name,party_master.party_name,item_master.uom";

        $data['leftJoin']['so_trans'] = "so_trans.id = prc_master.so_trans_id";
        $data['leftJoin']['so_master'] = "so_master.id = so_trans.trans_main_id";
        $data['leftJoin']['item_master'] = "item_master.id = prc_master.item_id";
        $data['leftJoin']['party_master'] = "party_master.id = so_master.party_id";
       
        $data['where']['prc_master.prc_type'] =2;
        $data['where']['prc_master.status'] = $data['status'];

        $data['order_by']['prc_master.prc_date'] = "ASC";
        $data['order_by']['prc_master.id'] = "ASC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "prc_master.prc_number";
        $data['searchCol'][] = "DATE_FORMAT(prc_master.prc_date,'%d-%m-%Y')";
        $data['searchCol'][] = "so_master.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(so_master.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "CONCAT(prc_master.prc_qty,' ',item_master.uom)";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getNextPlanNumber(){
        $queryData = array(); 
		$queryData['tableName'] = 'dispatch_plan';
        $queryData['select'] = "MAX(plan_no ) as plan_no ";
	
		$queryData['where']['dispatch_plan.plan_date >='] = $this->startYearDate;
		$queryData['where']['dispatch_plan.plan_date <='] = $this->endYearDate;

		$plan_no = $this->specificRow($queryData)->plan_no;
		$plan_no = (!empty($plan_no))?($plan_no + 1):1;
		return $plan_no;
    }

    public function save($param){ 
		try {
			$this->db->trans_begin();
            $opBalance = array();
			
			if(empty($param['id'])){
			
				$param['plan_no'] = $this->dispatchPlan->getNextPlanNumber();
				$param['plan_number'] = 'DP/'.$this->shortYear.'/'.sprintf("%02d",$param['plan_no']);
			}
            $result = $this->store('dispatch_plan', $param);

			if($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		}catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}

    public function getPlanDetail($param = []){
        $data['tableName'] = 'dispatch_plan';
        $data['select'] = "dispatch_plan.*";
        $data['where']['dispatch_plan.id'] = $param['id'];
        return $this->row($data);
    }
}
?>