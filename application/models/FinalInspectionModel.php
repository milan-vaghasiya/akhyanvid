<?php
class FinalInspectionModel extends MasterModel{
    private $productionInspection = "production_inspection";
    private $stockTrans = "stock_trans";

    public function getDTRows($data){
        $data['tableName'] = $this->stockTrans;
        $data['select'] = "stock_trans.*,SUM(stock_trans.qty * stock_trans.p_or_m) as qty,item_master.item_code,item_master.item_name,prc_master.prc_number";
        $data['leftJoin']['item_master'] = "item_master.id = stock_trans.item_id";
        $data['leftJoin']['prc_master'] = "prc_master.id = stock_trans.main_ref_id";
        $data['having'][] = 'SUM(stock_trans.qty * stock_trans.p_or_m) > 0';
        $data['where']['stock_trans.location_id'] = $this->FIR_STORE->id;
        $data['group_by'][] = "stock_trans.item_id";
  
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "prc_master.prc_number";
        $data['serachCol'][] = "item_master.item_name";
        $data['serachCol'][] = "stock_trans.qty";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function getFirDTRows($data){
        $data['tableName'] = "production_inspection";
		$data['select'] = "production_inspection.*,prc_master.prc_number,item_master.item_name";
        $data['leftJoin']['prc_master'] = "prc_master.id = production_inspection.prc_id";
        $data['leftJoin']['item_master'] = "item_master.id = production_inspection.item_id";
        $data['where']['production_inspection.report_type'] = 2;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "production_inspection.insp_date";
        $data['searchCol'][] = "production_inspection.trans_number"; 
        $data['searchCol'][] = "prc_master.prc_number";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "production_inspection.ok_qty";
        $data['searchCol'][] = "production_inspection.rej_found";
        $data['searchCol'][] = "";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }  
}
?>