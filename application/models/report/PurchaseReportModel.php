<?php 
class PurchaseReportModel extends MasterModel
{
    private $po_master = "po_master";
    private $po_trans = "po_trans";
    private $grn_master = "grn_master";
    private $grn_trans = "grn_trans";
	private $purchase_enquiry = "purchase_enquiry";
    private $purchase_quotation = "purchase_quotation";

    public function getPurchaseOrderMonitoring($data){
        $queryData = array();
		$queryData['tableName'] = $this->po_trans;
		$queryData['select'] = 'po_trans.*,po_trans.trans_main_id,po_master.trans_date,item_master.item_name,party_master.party_name,po_master.trans_number,po_master.remark,unit_master.unit_name'; 
        $queryData['join']['po_master'] = "po_master.id = po_trans.trans_main_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = po_trans.item_id";
		$queryData['leftJoin']['party_master'] = 'party_master.id = po_master.party_id';
		$queryData['leftJoin']['unit_master'] = 'unit_master.id = po_trans.unit_id';
		if(!empty($data['item_type'])){
            $queryData['where']['item_master.item_type'] = $data['item_type'];
        }
		if(!empty($data['party_id'])){
            $queryData['where']['po_master.party_id'] = $data['party_id'];
        }
        $queryData['customWhere'][] = "po_master.trans_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
		$queryData['order_by']['po_master.trans_date'] = 'ASC';
		$result = $this->rows($queryData);
        return $result;
    }

    public function getPurchaseReceipt($data){
        $queryData = array();
		$queryData['tableName'] = $this->grn_trans;
		$queryData['select'] = 'grn_master.trans_date,grn_master.trans_no,grn_master.trans_prefix,grn_master.trans_number,grn_master.inv_date,grn_master.inv_no,grn_trans.qty';
		$queryData['leftJoin']['grn_master'] = 'grn_master.id = grn_trans.mir_id';
		$queryData['where']['grn_trans.item_id'] = $data['item_id'];
		$queryData['where']['grn_trans.po_id'] = $data['po_id'];
		$queryData['where']['grn_trans.po_trans_id'] = $data['po_trans_id'];
		$queryData['order_by']['grn_master.trans_date'] = 'ASC';
		$result = $this->rows($queryData);
		return $result;
    }

    public function getPurchaseInward($data){
        $queryData = array();
		$queryData['tableName'] = $this->grn_trans;
		$queryData['select'] = 'grn_master.trans_date,grn_master.trans_number,grn_master.inv_no,grn_trans.qty,party_master.party_name,item_master.item_name,po_master.trans_number as po_number,po_master.trans_date as po_date,grn_trans.price,unit_master.unit_name';
		$queryData['leftJoin']['grn_master'] = 'grn_master.id = grn_trans.mir_id';
        $queryData['leftJoin']['item_master'] = 'item_master.id = grn_trans.item_id';
		$queryData['leftJoin']['party_master'] = 'party_master.id = grn_master.party_id';
		$queryData['leftJoin']['po_master'] = 'po_master.id = grn_trans.po_id';
		$queryData['leftJoin']['po_trans'] = 'po_trans.id = grn_trans.po_trans_id';
		$queryData['leftJoin']['unit_master'] = 'unit_master.id = po_trans.unit_id'; 
        if(!empty($data['item_type'])){
            $queryData['where']['item_master.item_type'] = $data['item_type'];
        }
        $queryData['customWhere'][] = "grn_master.trans_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
		$result = $this->rows($queryData);
		return $result;
    }

	public function getEnquiryRegisterData($data){
        $queryData = array();
		$queryData['tableName'] = $this->purchase_enquiry;
		$queryData['select'] = 'purchase_enquiry.*,party_master.party_name,purchase_quotation.quote_no,purchase_quotation.quote_date,purchase_quotation.qty as qtnQty,purchase_quotation.price,purchase_quotation.feasible,purchase_quotation.lead_time,employee_master.emp_name,purchase_quotation.approve_date,unit_master.unit_name'; 
		$queryData['leftJoin']['party_master'] = 'party_master.id = purchase_enquiry.party_id';
		$queryData['leftJoin']['unit_master'] = 'unit_master.id = purchase_enquiry.unit_id';
		$queryData['leftJoin']['purchase_quotation'] = "purchase_enquiry.id = purchase_quotation.enq_id";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = purchase_quotation.approve_by";
		if(!empty($data['party_id'])){
            $queryData['where']['purchase_enquiry.party_id'] = $data['party_id'];
        }
        if(!empty($data['item_id'])){
            $queryData['where']['purchase_enquiry.item_id'] = $data['item_id'];
        }
        $queryData['customWhere'][] = "purchase_enquiry.trans_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
		$queryData['order_by']['purchase_enquiry.trans_date'] = 'ASC';
		$result = $this->rows($queryData);
        return $result;
    }

	/* Supplier Wise Item Report created by rashmi : 15/05/2024 */
    public function getSupplierWiseItem($data){
        $queryData = array();
		$queryData['tableName'] = $this->grn_trans;
		$queryData['select'] = 'grn_master.party_id,grn_trans.item_id,party_master.party_name,item_master.item_name,item_master.item_code';
		$queryData['leftJoin']['grn_master'] = 'grn_master.id = grn_trans.mir_id';
		$queryData['leftJoin']['party_master'] = 'party_master.id = grn_master.party_id';
		$queryData['leftJoin']['item_master'] = 'item_master.id = grn_trans.item_id';
		if(!empty($data['item_id'])){$queryData['where']['grn_trans.item_id'] = $data['item_id'];}
		if(!empty($data['party_id'])){$queryData['where']['grn_master.party_id'] = $data['party_id'];}
        $queryData['group_by'][] = 'grn_master.party_id';
        $queryData['group_by'][] = 'grn_trans.item_id';
		$result = $this->rows($queryData);
        return $result;
    }

	public function getMarketTrendData($data){
		$queryData = array();          
		$queryData['tableName'] = $this->po_master;
		$queryData['select'] = 'po_master.id,
								po_master.trans_date,
								item_master.item_name,
									(select 
										CASE
											WHEN MONTH(po_master.trans_date) = 4 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									from po_trans pot
									where po_master.id = pot.trans_main_id) as april,
									(select 
										CASE
											WHEN MONTH(po_master.trans_date) = 5 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									from po_trans pot
									where po_master.id = pot.trans_main_id) as may,
									(select 
										CASE
											WHEN MONTH(po_master.trans_date) = 6 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									from po_trans pot
									where po_master.id = pot.trans_main_id) as june,
									(select 
										CASE
											WHEN MONTH(po_master.trans_date) = 7 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									from po_trans pot
									where po_master.id = pot.trans_main_id) as july,
									(select 
										CASE
											WHEN MONTH(po_master.trans_date) = 8 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									from po_trans pot
									where po_master.id = pot.trans_main_id) as august,
									(select 
										CASE
											WHEN MONTH(po_master.trans_date) = 9 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									from po_trans pot
									where po_master.id = pot.trans_main_id) as september,
									(select 
										CASE
											WHEN MONTH(po_master.trans_date) = 10 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									from po_trans pot
									where po_master.id = pot.trans_main_id) as october,
									  (select 
										CASE
											WHEN MONTH(po_master.trans_date) = 11 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									from po_trans pot
									where po_master.id = pot.trans_main_id) as november,
									(select 
										CASE
											WHEN MONTH(po_master.trans_date) = 12 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									from po_trans pot
									where po_master.id = pot.trans_main_id) as december,
									(select 
										CASE
											WHEN MONTH(po_master.trans_date) = 1 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									 from po_trans pot
									where po_master.id = pot.trans_main_id) as january,
									(select 
										CASE
											WHEN MONTH(po_master.trans_date) = 2 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									 from po_trans pot
									where po_master.id = pot.trans_main_id) as february,
									(select 
										CASE
											WHEN MONTH(po_master.trans_date) = 3 THEN SUM(pot.price)/count(pot.id)
											ELSE "-"
										END
									from po_trans pot
									where po_master.id = pot.trans_main_id) as march';
		$queryData['leftJoin']['po_trans'] = 'po_master.id = po_trans.trans_main_id';
		$queryData['leftJoin']['item_master'] = 'item_master.id = po_trans.item_id';
		$queryData['where']['po_master.is_delete'] = 0;
		$result = $this->rows($queryData);
		return $result;
	}
}
?>