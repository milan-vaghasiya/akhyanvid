<?php
class PurchaseModel extends MasterModel{
	
	private $purchase_enquiry = "purchase_enquiry";
	private $purchase_quotation = "purchase_quotation";

	public function getPurchaseEnqList($param=[]){
		$queryData['tableName'] = $this->purchase_enquiry;		
		$queryData['select'] = "purchase_enquiry.*, IFNULL(um.unit_name,'') as unit_name, IFNULL(pm.party_name,'') as party_name, IFNULL(cat.category_name,'') as category_name,IFNULL(pq.quote_count,0) as quotation_count,pq.price,pq.qty as quot_qty";        
        $queryData['leftJoin']['party_master pm'] = "pm.id = purchase_enquiry.party_id";
        $queryData['leftJoin']['unit_master um'] = "um.id = purchase_enquiry.unit_id";
        $queryData['leftJoin']['item_category cat'] = "cat.id = purchase_enquiry.item_type";
		$queryData['leftJoin']['(SELECT COUNT(*) as quote_count,enq_id,price,qty FROM purchase_quotation WHERE is_delete = 0 GROUP BY enq_id) as pq'] = "pq.enq_id = purchase_enquiry.id";
		
		if(!empty($param['orderData'])){
			$queryData['select'] .= ',po_master.trans_number as order_number';
			$queryData['leftJoin']['po_trans'] = "po_trans.ref_id = purchase_enquiry.id AND po_trans.from_entry_type = 160";
			$queryData['leftJoin']['po_master'] = "po_trans.trans_main_id = po_master.id ";
		}
		if(!empty($param['status'])){ 
			$queryData['where']['purchase_enquiry.trans_status'] = $param['status']; 
		}
		if(!empty($param['id'])){ $queryData['where']['purchase_enquiry.id'] = $param['id']; }
		if(!empty($param['item_id'])){ $queryData['where']['purchase_enquiry.item_id'] = $param['item_id']; }
		
		if(!empty($param['skey'])){
			$queryData['like']['purchase_enquiry.trans_number'] = str_replace(" ", "%", $param['skey']);
			$queryData['like']['purchase_enquiry.trans_date'] = str_replace(" ", "%", $param['skey']);
			$queryData['like']['purchase_enquiry.item_name'] = str_replace(" ", "%", $param['skey']);
			$queryData['like']['purchase_enquiry.qty'] = str_replace(" ", "%", $param['skey']);
			$queryData['like']['pm.party_name'] = str_replace(" ", "%", $param['skey']);
        }

        if(!empty($param['limit'])){ $queryData['limit'] = $param['limit']; }
        
		if(isset($param['start'])){ $queryData['start'] = $param['start']; }
		
		if(!empty($param['length'])){ $queryData['length'] = $param['length']; }
		

		$queryData['order_by']['purchase_enquiry.trans_date'] = 'DESC';
		$queryData['order_by']['purchase_enquiry.id'] = 'DESC';
		
        if(!empty($param['single_row'])):
			$result = $this->row($queryData);
		else:
			$result = $this->rows($queryData);
		endif;
        return $result;  
    }

	public function saveEnquiry($data){
		try {
            $this->db->trans_begin();
			
			if($this->checkDuplicate($data) > 0):
                $errorMessage['trans_no'] = "Enquiry No. is duplicate.";
                $result = ['status'=>0,'message'=>$errorMessage];
            endif;

            $result = $this->store($this->purchase_enquiry,$data,'Purchase Enquiry');

			if(!empty($data['req_id'])):
				$this->edit('purchase_indent',['id'=>$data['req_id']],['order_status'=>2]);
			endif;
					
			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
                return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}	
	}
	
	public function checkDuplicate($data){
        $queryData['tableName'] = $this->purchase_enquiry;
        $queryData['where']['trans_number'] = $data['trans_number'];
        $queryData['where']['entry_type'] = $data['entry_type'];

        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

	public function deleteEnquiry($id){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->purchase_enquiry,['id'=>$id],'Purchase Enquiry');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

	public function saveQuotation($data){
		try {
            $this->db->trans_begin();

            $result = $this->store($this->purchase_quotation, $data, 'Quotation');
					
			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
                return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}	
	}

public function getQuotationData($param=[]){
		$queryData['tableName'] = $this->purchase_quotation;		
		$queryData['select'] = "purchase_quotation.*, IFNULL(pm.party_name,'') as party_name,purchase_enquiry.item_name,purchase_enquiry.trans_number as enq_number,purchase_enquiry.trans_date as enq_date";        
        $queryData['leftJoin']['party_master pm'] = "pm.id = purchase_quotation.party_id";
        $queryData['leftJoin']['purchase_enquiry'] = "purchase_enquiry.id = purchase_quotation.enq_id";
		if(!empty($param['lastPOPrice'])){
			$queryData['select'] .= ',purchaseOrder.price as last_po_price';
			$queryData['leftJoin']['(SELECT party_id, po_trans.price, po_trans.item_id FROM po_trans JOIN po_master ON po_master.id = po_trans.trans_main_id WHERE po_trans.is_delete = 0 AND po_trans.id IN( SELECT MAX(pt.id) FROM po_trans pt JOIN po_master po ON po.id = pt.trans_main_id WHERE pt.is_delete = 0 GROUP BY po.party_id, pt.item_id)) as purchaseOrder'] = "purchaseOrder.party_id = purchase_quotation.party_id AND purchaseOrder.item_id = purchase_quotation.item_id ";
		}
        
		if(!empty($param['trans_status'])){$queryData['where']['purchase_quotation.trans_status'] = $param['trans_status'];  }
		if(!empty($param['id'])){ $queryData['where']['purchase_quotation.enq_id'] = $param['id']; }
		if(!empty($param['item_id'])){ $queryData['where']['purchase_quotation.item_id'] = $param['item_id']; }
		if(!empty($param['party_id'])){ $queryData['where_in']['purchase_quotation.party_id'] = $param['party_id']; } 
		if(!empty($param['group_by'])){ $queryData['group_by'][] = $param['group_by']; }
		if(!empty($param['order_by'])){ $queryData['order_by']['purchase_quotation.created_at'] = 'DESC'; }
			
        if(!empty($param['multi_row'])):
			$result = $this->rows($queryData);
		else:
			$result = $this->row($queryData);
		endif;
        return $result;  
    }

	public function chageEnqStatus($data){
		try {
            $this->db->trans_begin();

			if(!empty($data['item_id'])){
				$this->edit($this->purchase_quotation, ['item_id'=>$data['item_id']], ['trans_status'=>3]);
				$this->edit($this->purchase_enquiry, ['item_id'=>$data['item_id']], ['trans_status'=>3]);
			}

			$masterData = [
				'id' => $data['id'],
				'trans_status' => $data['val'],
				'approve_by' => ($data['val'] == 2) ? $this->loginId : 0,
				'approve_date' => ($data['val'] == 2) ? date('Y-m-d') : NULL
			];
			$this->store($this->purchase_quotation, $masterData);

			$this->store($this->purchase_enquiry, ['id'=>$data['enq_id'],'trans_status'=>$data['val']]);

			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return ['status' => 1, 'message' => 'Quotation ' . $data['msg'] . ' successfully.'];
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}
}
?>