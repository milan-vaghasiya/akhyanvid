<?php
class OutsourceModel extends MasterModel{

    public function getNextChallanNo(){
		$queryData = array(); 
		$queryData['tableName'] = 'outsource';
        $queryData['select'] = "MAX(ch_no) as ch_no ";	
		$queryData['where']['outsource.ch_date >='] = $this->startYearDate;
		$queryData['where']['outsource.ch_date <='] = $this->endYearDate;

		$ch_no = $this->specificRow($queryData)->ch_no;
		$ch_no = $ch_no + 1;
		return $ch_no;
    }

    public function getDTRows($data){
        $data['tableName'] = "prc_challan_request";
		$data['select'] = "prc_challan_request.*,outsource.id as out_id,outsource.ch_number,outsource.ch_date,outsource.party_id,prc_master.prc_date,prc_master.prc_number,process_master.process_name,item_master.item_name,IFNULL(receiveLog.ok_qty,0) as ok_qty,IFNULL(receiveLog.rej_qty,0) as rej_qty,party_master.party_name,item_master.uom"; 
		$data['leftJoin']['outsource'] = "outsource.id = prc_challan_request.challan_id";
		$data['leftJoin']['prc_master'] = "prc_master.id = prc_challan_request.prc_id";
		$data['leftJoin']['process_master'] = "process_master.id = prc_challan_request.process_id";
		$data['leftJoin']['item_master'] = "item_master.id = prc_master.item_id";
		$data['leftJoin']['party_master'] = "party_master.id = outsource.party_id";
        $data['leftJoin']['(SELECT sum(qty) as ok_qty,SUM(rej_found) as rej_qty,process_id,ref_trans_id FROM prc_log WHERE is_delete = 0 AND process_by = 3 GROUP BY process_id,ref_trans_id) as receiveLog'] = "receiveLog.ref_trans_id = prc_challan_request.id AND prc_challan_request.process_id = receiveLog.process_id";
        $data['where']['prc_challan_request.challan_id >'] = 0;
        if ($data['status'] == 0) :
            $data['having'][] = "prc_challan_request.qty > (ok_qty+rej_qty)";
        endif;
        if ($data['status'] == 1) :
            $data['having'][] = "prc_challan_request.qty - (ok_qty+rej_qty) <= 0";

        endif;
       

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "DATE_FORMAT(outsource.ch_date,'%d-%m-%Y')";
        $data['searchCol'][] = "outsource.ch_number";
        $data['searchCol'][] = "prc_master.prc_number";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "CONCAT(item_master.item_code,' ',item_master.item_name)";
        $data['searchCol'][] = "process_master.process_name";
        $data['searchCol'][] = "CONCAT(prc_challan_request.qty,' ',item_master.uom)";
        $data['searchCol'][] = "CONCAT((ok_qty+rej_qty),' ',item_master.uom)";
        $data['searchCol'][] = "CONCAT((prc_challan_request.qty - (ok_qty+rej_qty)),' ',item_master.uom)";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		$result = $this->pagingRows($data);
        // $this->printQuery();
        return $result;
    }

    public function save($data){
		try {
			$this->db->trans_begin();
            $ch_prefix = 'VC/'.getYearPrefix('SHORT_YEAR').'/';
            $ch_no = $this->outsource->getNextChallanNo();
            $challanData = [
                'id'=>'',
                'party_id'=>$data['party_id'],
                'ch_date'=>$data['ch_date'],
                'ch_no'=>$ch_no,
                'ch_number'=>$ch_prefix.$ch_no,
                'vehicle_no'=>$data['vehicle_no'],
                'remark'=>$data['vehicle_no']
            ];
            $result = $this->store('outsource',$challanData);
            foreach($data['id'] as $key=>$id){
                $chData = [
                    'id'=>$id,
                    'qty'=>$data['ch_qty'][$key],
                    'price'=>$data['price'][$key],
                    'challan_id'=>$result['id'],
                ];
                $this->store('prc_challan_request',$chData, 'Challan Request');
            }
			
			if($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return ['status'=>1,'message'=>'Record Updated Successfully'];
			endif;
		}catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}

    public function delete($id){
        try {
			$this->db->trans_begin();
            $chData = $this->sop->getChallanRequestData(['challan_id'=>$id,'challan_receive'=>1]);
            foreach($chData as $row){
                if(($row->ok_qty+$row->rej_qty) > 0){
                    return ['status'=>0,'message'=>'You can not delete this Challan'];
                }
                $this->store("prc_challan_request",['id'=>$row->id,'challan_id'=>0,'qty'=>$row->old_qty]);
            }
			$result = $this->trash('outsource', ['id'=>$id], 'Challan');
			if($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		}catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
    }

    public function getOutSourceData($data){
		$data['tableName'] = 'outsource';
		$data['select'] = 'outsource.*,employee_master.emp_name,party_master.party_name,party_master.party_address,party_master.gstin';
		$data['leftJoin']['employee_master'] = 'employee_master.id = outsource.created_by';
		$data['leftJoin']['party_master'] = 'party_master.id = outsource.party_id';
		$data['where']['outsource.id'] = $data['id'];
		return $this->row($data);
	}
}
?>