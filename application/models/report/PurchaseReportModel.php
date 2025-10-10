<?php 
class PurchaseReportModel extends MasterModel
{
    private $transChild = "trans_child";
    private $grnTrans = "grn_trans";

    public function getPurchaseOrderMonitoring($data){
        $queryData = array();
		$queryData['tableName'] = $this->transChild;
		$queryData['select'] = 'trans_child.*,trans_child.trans_main_id,trans_main.trans_date,item_master.item_name,party_master.party_name,trans_main.trans_number,trans_main.remark,project_master.project_name,item_master.item_code';
        $queryData['join']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = trans_child.item_id";
		$queryData['leftJoin']['party_master'] = 'party_master.id = trans_main.party_id';
		$queryData['leftJoin']['project_master'] = 'project_master.id = trans_main.project_id';
        $queryData['where']['trans_main.entry_type'] = 21;

		if(!empty($data['item_type'])){
            $queryData['where']['item_master.item_type'] = $data['item_type'];
        }

		if(!empty($data['party_id'])){
            $queryData['where']['trans_main.party_id'] = $data['party_id'];
        }

		if(isset($data['status']) && $data['status'] !== ''){
            $queryData['where_in']['trans_child.trans_status'] = $data['status'];
        }

        $queryData['customWhere'][] = "trans_main.trans_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
		$queryData['order_by']['trans_main.trans_date'] = 'ASC';

		$result = $this->rows($queryData);
        return $result;
    }

    public function getPurchaseReceipt($data){
        $queryData = array();
		$queryData['tableName'] = $this->grnTrans;
		$queryData['select'] = 'grn_master.trans_date,grn_master.trans_no,grn_master.trans_prefix,grn_master.trans_number,grn_master.doc_date,grn_master.doc_no,grn_trans.qty';
		$queryData['leftJoin']['grn_master'] = 'grn_master.id = grn_trans.grn_id';
		$queryData['where']['grn_trans.item_id'] = $data['item_id'];
		$queryData['where']['grn_trans.po_id'] = $data['po_id'];
		$queryData['where']['grn_trans.po_trans_id'] = $data['po_trans_id'];
		$queryData['order_by']['grn_master.trans_date'] = 'ASC';
		$result = $this->rows($queryData);
		return $result;
    }

    public function getPurchaseInward($data){
        $queryData = array();
		$queryData['tableName'] = $this->grnTrans;
		$queryData['select'] = 'grn_master.trans_date,grn_master.trans_number,grn_master.doc_no,grn_trans.qty,party_master.party_name,item_master.item_name,trans_main.trans_number as po_number,trans_main.trans_date as po_date,grn_trans.price';
		$queryData['leftJoin']['grn_master'] = 'grn_master.id = grn_trans.grn_id';
        $queryData['leftJoin']['item_master'] = 'item_master.id = grn_trans.item_id';
		$queryData['leftJoin']['party_master'] = 'party_master.id = grn_master.party_id';
		$queryData['leftJoin']['trans_main'] = 'trans_main.id = grn_master.po_id';

        if(!empty($data['project_id'])){
            $queryData['where']['grn_master.project_id'] = $data['project_id'];
        }
		
        $queryData['where']['grn_master.ref_id'] = 0;
        $queryData['customWhere'][] = "grn_master.trans_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";

		$result = $this->rows($queryData);
		return $result;
    }
}
?>