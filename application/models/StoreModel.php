<?php
class StoreModel extends MasterModel{
    private $last_req_no = 0;
    private $storeRequest = "store_request";
    private $issueRegister = "issue_register";
    private $materialReturn = "material_return";
    private $stockTransation = "stock_trans";
	
	public function getNextReqNo(){
		$queryData = array(); 
		$queryData['tableName'] = 'store_request';
        $queryData['select'] = "MAX(trans_no) as trans_no ";
		$queryData['where']['store_request.trans_date >='] = $this->startYearDate;
		$queryData['where']['store_request.trans_date <='] = $this->endYearDate;

		$trans_no = $this->specificRow($queryData)->trans_no;
		$trans_no = (empty($this->last_req_no))?($trans_no + 1):$trans_no;
		return $trans_no;
    }

    public function getNextIssueNo(){
		$queryData = array(); 
		$queryData['tableName'] = 'issue_register';
        $queryData['select'] = "MAX(issue_no) as issue_no ";
		$queryData['where']['issue_register.issue_date >='] = $this->startYearDate;
		$queryData['where']['issue_register.issue_date <='] = $this->endYearDate;

		$issue_no = $this->specificRow($queryData)->issue_no;
		$issue_no = (empty($this->last_req_no))?($issue_no + 1):$issue_no;
		return $issue_no;
    }

	public function getDTRows($data){
        $data['tableName'] = $this->storeRequest;
        $data['select'] = "store_request.*,(store_request.req_qty - store_request.issue_qty) AS pending_qty,item_master.item_name,prc_master.prc_number,item_master.uom,item_master.item_code";
        $data['leftJoin']['item_master'] = "item_master.id  = store_request.item_id";
        $data['leftJoin']['prc_master'] = "prc_master.id  = store_request.prc_id";

        $data['where']['(store_request.req_qty - store_request.issue_qty) >'] = 0;
        $data['where']['store_request.status'] = $data['status'];

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "store_request.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(store_request.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "CONCAT('[',item_master.item_code,'] ',item_master.item_name)";
        $data['searchCol'][] = "store_request.req_qty";
        $data['searchCol'][] = "store_request.issue_qty";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function getIssueDTRows($data){
        $data['tableName'] = $this->issueRegister;
        $data['select'] = "issue_register.*,store_request.trans_number,store_request.trans_date,item_master.item_name,prc_master.prc_number,item_master.uom";
        $data['leftJoin']['store_request'] = "store_request.id = issue_register.req_id";
        $data['leftJoin']['item_master'] = "item_master.id  = issue_register.item_id";
        $data['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";
        $data['leftJoin']['prc_master'] = "prc_master.id  = issue_register.prc_id";

        if($data['status'] == 3){
            $data['where']['item_category.is_return'] = 1;
			$data['where']['(issue_register.issue_qty - issue_register.return_qty) >'] = 0;
        }

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "issue_register.issue_number";
        $data['searchCol'][] = "DATE_FORMAT(issue_register.issue_date,'%d-%m-%Y')";
        $data['searchCol'][] = "store_request.trans_number";
        $data['searchCol'][] = "prc_master.prc_number";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "CONCAT(store_request.issue_qty,' ',item_master.uom)";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function getInspDTRows($data) {
        $data['tableName'] = $this->materialReturn;
        $data['select'] = "material_return.*,issue_register.issue_number,item_master.uom";
        $data['leftJoin']['issue_register'] = "material_return.issue_id  = issue_register.id";
        $data['leftJoin']['store_request'] = "issue_register.req_id  = store_request.id";
        $data['leftJoin']['item_master'] = "item_master.id  = store_request.item_id";
        $data['where']['material_return.trans_type'] = $data['trans_type'];

        if($data['trans_type'] == 1){
            $data['where']['(material_return.total_qty - material_return.insp_qty) > '] = 0;
        }

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "issue_register.issue_number";
        $data['searchCol'][] = "DATE_FORMAT(material_return.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "CONCAT(material_return.total_qty,' ',item_master.uom)";
        // $data['searchCol'][] = "material_return.batch_no";
        $data['searchCol'][] = "material_return.remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }
	
	public function saveRequest($data){ 
		try 
		{
			$this->db->trans_begin();

            if(!empty($data['id'])){
                foreach($data['itemData'] AS $row){
                    $this->trash($this->storeRequest, ['id' => $row['id']]);
                }
            }else{
                $data['trans_prefix'] = $data['trans_prefix'];
                $data['trans_no'] = $this->store->getNextReqNo();
                $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
            }
           
            foreach($data['itemData'] AS $row){
                $reqData = [
                    'id' => $row['id'],
                    'trans_no' => $data['trans_no'],
                    'trans_number' => $data['trans_number'],
                    'trans_date' => $data['trans_date'],
                    'item_id' => $row['item_id'],
                    'req_qty' => $row['req_qty'],
                    'remark' => $row['remark'],
                ];
                $this->store($this->storeRequest, $reqData);
            }

            $result = ['status' => 1, 'message' => 'Requisition Saved Successfully.'];

			if($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
			
		}catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
	}

    public function getRequest($param) {
        $queryData = array();          
		$queryData['tableName'] = "store_request";
        $queryData['select'] = "store_request.*,(store_request.req_qty - store_request.issue_qty) as pending_qty,item_master.item_code,item_master.item_name";
        $queryData['leftJoin']['item_master'] = "item_master.id = store_request.item_id";

        if(!empty($param['id'])){ $queryData['where']['store_request.id'] = $param['id']; }
        if(!empty($param['item_id'])){ $queryData['where']['store_request.item_id'] = $param['item_id']; }
        if(!empty($param['fg_id'])){ $queryData['where']['store_request.fg_id'] = $param['fg_id']; }
        if(!empty($param['status'])){ $queryData['where']['store_request.status'] = $param['status']; }        
        if(!empty($param['trans_number'])){ $queryData['where']['store_request.trans_number'] =  $param['trans_number']; } 

        if(!empty($param['multi_rows'])){
            $result = $this->rows($queryData);
        }else{
            $result = $this->row($queryData);
        }
        return $result;
    }
	
    public function getIssueRequest($param) {
        $queryData = array();          
		$queryData['tableName'] = "issue_register";
        if(!empty($param['id'])){ $queryData['where']['id'] = $param['id']; }
        if(!empty($param['item_id'])){ $queryData['where']['item_id'] = $param['item_id']; }
        $result = $this->row($queryData);
        return $result;
    }

    public function getMaterialIssueData($param) {
        $queryData = array();          
		$queryData['tableName'] = "issue_register";
        $queryData['select'] = "issue_register.*, IFNULL(im.item_name,'') as item_name, IFNULL(prc_master.prc_number,'') as prc_number,im.uom";
        $queryData['leftJoin']['item_master im'] = "im.id = issue_register.item_id ";
        $queryData['leftJoin']['prc_master'] = "prc_master.id = issue_register.prc_id";

        if(!empty($param['supplier_data'])){
            $queryData['select'] .= ',party_master.party_name,grn_master.party_id';
            $queryData['leftJoin']['grn_trans'] = 'issue_register.item_id = issue_register.item_id AND grn_trans.is_delete = 0';
            $queryData['leftJoin']['grn_master'] = 'grn_master.id = grn_trans.mir_id';
            $queryData['leftJoin']['party_master'] = 'grn_master.party_id = party_master.id';
        }
        if(!empty($param['sum_data'])){
            $queryData['select'] .= ',SUM(issue_qty) as issue_qty';
            
        }

        if(!empty($param['id'])){ $queryData['where']['issue_register.id'] = $param['id']; }
        if(!empty($param['item_id'])){ $queryData['where']['issue_register.item_id'] = $param['item_id']; }
        if(!empty($param['prc_id'])){ $queryData['where']['issue_register.prc_id'] = $param['prc_id']; }
        // if(!empty($param['batch_no'])){ $queryData['where']['issue_register.batch_no'] = $param['batch_no']; }
        if(!empty($param['issue_date'])){ $queryData['where']['issue_register.issue_date'] = $param['issue_date']; }
        if(!empty($param['from_date'])){ $queryData['where']['issue_register.issue_date >='] = $param['from_date']; }
        if(!empty($param['to_date'])){ $queryData['where']['issue_register.issue_date <='] = $param['to_date'];  }
        if(!empty($param['customWhere'])){ $queryData['customWhere'][] = $param['customWhere']; }

        if(!empty($param['group_by'])){ $queryData['group_by'][] = $param['group_by']; }
        if(!empty($param['skey'])){
			$queryData['like']['issue_register.trans_number'] = str_replace(" ", "%", $param['skey']);
			$queryData['like']['issue_register.trans_date'] = str_replace(" ", "%", $param['skey']);
			$queryData['like']['prc_master.prc_number'] = str_replace(" ", "%", $param['skey']);
			$queryData['like']['im.item_name'] = str_replace(" ", "%", $param['skey']);
        }

        if(!empty($param['limit'])){ $queryData['limit'] = $param['limit']; }
        
		if(isset($param['start'])){ $queryData['start'] = $param['start']; }
		
		if(!empty($param['length'])){ $queryData['length'] = $param['length']; }

        $queryData['order_by']['issue_register.issue_date'] = 'DESC';
		$queryData['order_by']['issue_register.id'] = 'DESC';

        if(!empty($param['single_row'])){
            $result = $this->row($queryData);
        }else{
            $result = $this->rows($queryData);
        }
        
        return $result;
    }

    public function getMaterialData($param) {
        $data['tableName'] = $this->materialReturn;
        $data['select'] = "material_return.*";
        $data['leftJoin']['issue_register'] = "issue_register.id  = material_return.issue_id";
        $data['leftJoin']['store_request'] = "store_request.id  = issue_register.req_id";
		$data['where']['material_return.id'] = $param['id'];
        return $this->row($data);
    }
	
	public function getRequestList($param=[]){
        $queryData = array();          
		$queryData['tableName'] = "store_request";
		
		$queryData['select'] = "store_request.*,im.item_name,(store_request.req_qty - store_request.issue_qty) as pending_qty";
		$queryData['select'] .= ", IFNULL(im.item_name,'') as item_name, IFNULL(fg.item_name,'') as fg_name, IFNULL(prc_master.prc_number,'') as prc_number,im.uom";
        
        $queryData['leftJoin']['item_master im'] = "im.id = store_request.item_id ";
		$queryData['leftJoin']['item_master fg'] = "fg.id = store_request.fg_id ";
        $queryData['leftJoin']['prc_master'] = "prc_master.id = store_request.prc_id";
		
		if(!empty($param['status'])){
		    if($param['status'] == 'closed'){ 
                $queryData['where']['store_request.status'] = 2; 
            } else {
		        if($param['status'] == 'pending'){ $queryData['customWhere'][] = "store_request.req_qty > store_request.issue_qty"; }
		        if($param['status'] == 'completed'){ $queryData['customWhere'][] = "store_request.req_qty <= store_request.issue_qty"; }
                $queryData['where']['store_request.status !='] = 2;
		    }
		}

        if(!empty($param['trans_no'])){ $queryData['where']['store_request.trans_no'] = $param['trans_no']; }
		
		if(!empty($param['item_id'])){ $queryData['where']['store_request.item_id'] = $param['item_id']; }
		
		if(!empty($param['prc_id'])){ $queryData['where']['store_request.prc_id'] = $param['prc_id']; }
		
		if(!empty($param['trans_date'])){ $queryData['where']['store_request.trans_date'] = $param['trans_date']; }
		
		if(!empty($param['overdue'])){ $queryData['where']['store_request.trans_date < '] = $param['overdue']; }
		
		if(!empty($param['customWhere'])){ $queryData['customWhere'][] = $param['customWhere']; }
		
		if(!empty($param['pending'])){
		    $queryData['where']['store_request.status'] = $param['status'];
		}
		
        if(!empty($param['skey'])){
			$queryData['like']['store_request.trans_number'] = str_replace(" ", "%", $param['skey']);
			$queryData['like']['store_request.trans_date'] = str_replace(" ", "%", $param['skey']);
			$queryData['like']['prc_master.prc_number'] = str_replace(" ", "%", $param['skey']);
			$queryData['like']['im.item_name'] = str_replace(" ", "%", $param['skey']);
        }

        if(!empty($param['limit'])){ $queryData['limit'] = $param['limit']; }
        
		if(isset($param['start'])){ $queryData['start'] = $param['start']; }
		
		if(!empty($param['length'])){ $queryData['length'] = $param['length']; }
		
		$queryData['order_by']['store_request.trans_date'] = 'DESC';
		$queryData['order_by']['store_request.id'] = 'DESC';
		
        $result = $this->rows($queryData);
        return $result;
    }

    public function delete($id){
        return $this->trash($this->storeRequest,['id'=>$id]);
    }
    
    public function closeRequest($data){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = ["req_id"];
            $checkData['value'] = $data['id'];
            $checkUsed = $this->checkUsage($checkData);
            
            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Request is currently in use. you cannot Close it.'];
            endif;
            
			$result = $this->edit('store_request',['id'=>$data['id']],['status'=>2]);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
    
    public function deleteRequest($id){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = ["req_id"];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);
            
            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Request is currently in use. you cannot delete it.'];
            endif;
            
            $result = $this->trash('store_request',['id'=>$id],'Store Request');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function saveIssueRequisition($data){
        try {
            $this->db->trans_begin();

            foreach ($data['batch_qty'] as $key => $value) {

                $issue_no = $this->getNextIssueNo();

                if(!empty($value) && $value > 0) {

                    $issueData = [
                        'id' => '',
                        'req_id' => (!empty($data['req_id'])?$data['req_id']:''),
                        'issue_no' => $issue_no,
                        'issue_number' => 'ISU/'.str_pad($issue_no, 5, '0', STR_PAD_LEFT),
                        'issue_date' => date("Y-m-d"),
                        'item_id' => $data['item_id'],
                        // 'batch_no' => $data['batch_no'][$key],
                        // 'heat_no' => $data['heat_no'][$key],
                        'prc_id' => $data['prc_id'],
                        'issue_qty' => $value,
                        'issued_to' => $data['issued_to'],
                        'created_by' => $data['created_by']
                    ];
                    $result = $this->store($this->issueRegister, $issueData, 'Issue Requisition');

                    $stockMinusQuery = [
                        'id' => "",
                        'trans_type' => 'SSI',
                        'trans_date' => date("Y-m-d"),
                        'location_id'=> $data['location_id'][$key],
                        // 'batch_no' => $data['batch_no'][$key],
                        'item_id' => $data['item_id'],
                        'qty' => $value,
                        'p_or_m' => -1,
                        'main_ref_id' => $result['insert_id'],
                        'child_ref_id' =>  $data['prc_id'],
                        'ref_no' => 'ISU/'.str_pad($issue_no, 5, '0', STR_PAD_LEFT),
                        'created_by' => $data['created_by']
                    ];
                    $issueTrans = $this->store('stock_trans', $stockMinusQuery);
					
					if(!empty($data['to_location'])){
                        $stockPlusQuery = [
                            'id' => "",
                            'trans_type' => 'SSI',
                            'trans_date' => date("Y-m-d"),
                            'location_id'=> $data['to_location'],
                            'item_id' => $data['item_id'],
                            'qty' => $value,
                            'p_or_m' => 1,
                            'main_ref_id' => $result['insert_id'],
                            'child_ref_id' =>  $data['prc_id'],
                            'ref_no' => 'ISU/'.str_pad($issue_no, 5, '0', STR_PAD_LEFT),
                            'created_by' => $data['created_by']
                        ];
                        $this->store('stock_trans', $stockPlusQuery);
                    }
                }
            }

            if(!empty($data['req_id'])){
                $setData = array();
                $setData['tableName'] = $this->storeRequest;
                $setData['where']['id'] = $data['req_id'];
                $setData['set']['issue_qty'] = 'issue_qty, + ' .  array_sum($data['batch_qty']);
                $this->setValue($setData);
            }

            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return ['status' => 1, 'message' => 'Material issue Successfully.'];
            endif;
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }

    public function deleteIssueRequisition($data) {
        try {
            $this->db->trans_begin();

            $issueData = $this->getIssueRequest(['id' => $data['id']]);
			
            if(!empty($issueData)){

                $stockData = $this->remove($this->stockTransation, ['trans_type'=>'SSI', 'main_ref_id'=>$data['id'], 'child_ref_id'=>$issueData->prc_id]);
                
                $setData = array();
                $setData['tableName'] = $this->storeRequest;
                $setData['where']['id'] = $issueData->req_id;
                $setData['set']['issue_qty'] = 'issue_qty, - ' .  $issueData->issue_qty;
                $this->setValue($setData);
            }

            $this->trash($this->issueRegister,['id'=>$data['id']], 'Delete Issue Requisitin');

            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return ['status' => 1, 'message' => 'Material Issue suucessfully.'];
            endif;
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }

    public function saveReturnReq($data) {
        try {
			$this->db->trans_begin();

            $entry_type = (!empty($data['entry_type'])) ? $data['entry_type'] : "" ;
            $issue_number = (!empty($data['issue_number'])) ? $data['issue_number'] : "" ;
            $location_id = (!empty($data['location_id'])) ? $data['location_id'] : "" ;
            unset($data['entry_type']);unset($data['issue_number']);unset($data['location_id']);

            $data['id'] = '';
            $result = $this->store($this->materialReturn, $data, 'Return Material');

            if($data['trans_type'] == 1){
                $setData = array();
                $setData['tableName'] = $this->issueRegister;
                $setData['where']['id'] = $data['issue_id'];
                $setData['set']['return_qty'] = 'return_qty, + ' . $data['total_qty'];
                $this->setValue($setData);
            }

            if($data['trans_type'] == 2){

                if($data['usable_qty'] != "" && $data['usable_qty'] != 0)
                {
                    $stockPlusQuery = [
                        'id' => "",
                        'trans_type' => 'SSR',
                        'trans_date' => date("Y-m-d"),
                        'location_id'=> $location_id,
                        // 'batch_no' => $data['batch_no'],
                        'item_id' => $data['item_id'],
                        'qty' => $data['usable_qty'],
                        'p_or_m' => 1,
                        'main_ref_id' =>  $data['issue_id'],
                        'child_ref_id' => $result['insert_id'],
                        'ref_no' => $issue_number,
                        'created_by' => $data['created_by']
                    ];
                    $issueTrans = $this->store('stock_trans', $stockPlusQuery);
                }

                $setData = array();
                $setData['tableName'] = $this->materialReturn;
                $setData['where']['id'] = $data['ref_id'];
                $setData['set']['insp_qty'] = 'insp_qty, + ' . $data['insp_qty'];
                $this->setValue($setData);
            }
			
			if ($this->db->trans_status() !== FALSE) :
				$this->db->trans_commit();
				return $result;
			endif;
		} catch (\Exception $e) {
			$this->db->trans_rollback();
			return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
		}
    }

    public function getPrcMaterialDTRows($data){
        $data['tableName'] = "prc_bom";
        $data['select'] = "prc_bom.*,prc_master.prc_number,prc_master.prc_date,prc_master.prc_qty,item_master.item_name,item_master.item_type,item_master.uom,item_master.item_code,IFNULL(stock_trans.issue_qty,0) as issue_qty,SUM((prc_bom.ppc_qty * prc_master.prc_qty) - IFNULL(stock_trans.issue_qty,0)) AS pending_qty,prc_master.prc_type";

        $data['leftJoin']['prc_master'] = "prc_master.id = prc_bom.prc_id";
        $data['leftJoin']['item_master'] = "item_master.id = prc_master.item_id";
        $data['leftJoin']['(SELECT SUM(abs(stock_trans.qty)) as issue_qty,child_ref_id,stock_trans.item_id
                             FROM stock_trans  WHERE stock_trans.is_delete=0 AND stock_trans.trans_type IN("SSI") GROUP BY stock_trans.child_ref_id,stock_trans.item_id) stock_trans']="stock_trans.child_ref_id = prc_bom.prc_id AND prc_bom.item_id = stock_trans.item_id";
        $data['where_in']['prc_master.status'] = [0,1,2];
        $data['where']['prc_master.prc_type'] = $data['prc_type'];
		$data['where']['prc_master.is_delete'] = 0;

        $data['group_by'][]='prc_bom.prc_id';
        $data['having'][]='pending_qty > 0';
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "prc_master.prc_number";
        $data['searchCol'][] = "CONCAT('[',item_master.item_code,'] ',item_master.item_name)";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }
}
?>