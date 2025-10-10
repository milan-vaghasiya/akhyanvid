<?php
class GateInwardModel extends masterModel{
    private $stockTrans = "stock_trans";
    private $grnMaster = "grn_master";
    private $grnTrans = "grn_trans";

    public function getNextGrnNo(){
		$queryData = array(); 
		$queryData['tableName'] = 'grn_master';
        $queryData['select'] = "MAX(trans_no) as trans_no ";
		$queryData['where']['grn_master.trans_date >='] = $this->startYearDate;
		$queryData['where']['grn_master.trans_date <='] = $this->endYearDate;
		$trans_no = $this->specificRow($queryData)->trans_no;
		$nextTransNo = (!empty($trans_no))?($trans_no + 1):1;        
		return $nextTransNo;
    }

    public function getDTRows($data){
        $data['tableName'] = 'grn_trans';
        $data['select'] = "grn_trans.id,grn_master.trans_number,DATE_FORMAT(grn_master.trans_date,'%d-%m-%Y') as trans_date,grn_trans.qty,party_master.party_name,item_master.item_name,grn_master.doc_no,ifnull(DATE_FORMAT(grn_master.doc_date,'%d-%m-%Y'),'') as doc_date,grn_trans.trans_status,grn_master.id as grn_id";
        $data['leftJoin']['grn_master'] = "grn_master.id = grn_trans.grn_id";
        $data['leftJoin']['item_master'] = "item_master.id = grn_trans.item_id";
        $data['leftJoin']['party_master'] = "party_master.id = grn_master.party_id";
        
        $data['order_by']['grn_trans.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "grn_master.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(grn_master.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "grn_trans.qty";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if (isset($data['order'])) {
			$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];
		}

		return $this->pagingRows($data);
    }

    public function getGateInward($param){
        $queryData['tableName'] = 'grn_master';
        $queryData['select'] = "grn_master.id, grn_master.trans_number, DATE(grn_master.trans_date) as trans_date, party_master.party_name, grn_master.doc_no, grn_master.trans_prefix, grn_master.trans_no, grn_master.party_id,grn_master.created_at,employee_master.emp_name as prepareBy,grn_master.doc_date";
        $queryData['leftJoin']['party_master'] = "party_master.id = grn_master.party_id";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = grn_master.created_by";
				
        if(!empty($param['id'])):
            $queryData['where']['grn_master.id'] = $param['id'];
		endif;
		
        if(!empty($param['party_id'])):
            $queryData['where']['grn_master.party_id'] = $param['party_id'];
		endif;
		
        if(!empty($param['trans_date'])):
            $queryData['where']['DATE(grn_master.trans_date)'] = $param['trans_date'];
		endif;

		$result = $this->row($queryData);

        if(!empty($param['itemData']) AND !empty($param['id'])): 
            $result->itemData = $this->getInwardItem(['grn_id'=>$param['id']]);
        endif;
		
		return $result;
    }

    public function save($data){ 
        try{
            $this->db->trans_begin();
			
			$itemData = $data['item_data'];unset($data['item_data']);
            $data['trans_prefix'] = "GRN/".$this->shortYear."/";
            $data['trans_no'] = $this->getNextGrnNo();
            $data['trans_number'] = $data['trans_prefix'].$data['trans_no'];

            if(!empty($data['id'])):
                $gateInwardData = $this->getGateInward(['id'=>$data['id'],'itemData'=>1]);

                foreach($gateInwardData->itemData as $row): 
                    $this->trash($this->grnTrans,['id'=>$row->id]);
                    $this->remove($this->stockTrans,['child_ref_id'=>$row->id]);
                endforeach;
            endif;
			
			$result = Array();
			if(!empty($itemData))
			{
				$result = $this->store("grn_master",$data);
				foreach($itemData as $row)
				{
					$row= (object) $row;
					$grnTransData = Array();$stockPlusQuery = Array();
					if(!empty($row->item_id) AND !empty($row->qty))
					{
						$grnTransData['id'] = (!empty($row->id) ? $row->id : "");
						$grnTransData['item_id'] = $row->item_id;
						$grnTransData['qty'] = $row->qty;
						$grnTransData['grn_id'] = (!empty($data['id']) ? $data['id'] : $result['insert_id']);
						$grnTransData['price'] = (!empty($row->price) ? $row->price : 0);
						$grnTransData['item_remark'] = (!empty($row->item_remark) ? $row->item_remark : "");
                        $grnTransData['is_delete'] = 0;
						
						$resultTrans = $this->store("grn_trans",$grnTransData);
						
						//STOCK TRANS EFFECT
						$stockPlusQuery = [
							'id' => "",
							'trans_type' =>'GRN',
							'trans_date' => $data['trans_date'],
							'item_id' => $row->item_id,
							'qty' => $row->qty,
							'p_or_m' => 1,
							'child_ref_id' =>(!empty($row->id) ? $row->id : $resultTrans['insert_id']),
                            'main_ref_id' => (!empty($row->id) ? $row->id : $result['insert_id']),
							'ref_no'=>$data['trans_number']
						];
						$this->store('stock_trans', $stockPlusQuery);
					}
				}
			}
			
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	
    public function getInwardItem($param){
        $queryData['tableName'] = 'grn_trans';
        $queryData['select'] = "grn_trans.id, grn_trans.item_id, grn_trans.qty, grn_trans.price, item_master.item_code,item_master.item_name,grn_trans.grn_id,grn_trans.item_remark,stock_trans.main_ref_id,item_master.uom";
        $queryData['leftJoin']['item_master'] = "item_master.id = grn_trans.item_id";
        $queryData['leftJoin']['stock_trans'] = "stock_trans.child_ref_id = grn_trans.id";
				
        if(!empty($param['id'])):
            $queryData['where']['grn_trans.id'] = $param['id'];
		endif;
		
        if(!empty($param['grn_id'])):
			$queryData['where']['grn_trans.grn_id'] = $param['grn_id'];
		endif;

        if(!empty($param['single_row'])){
            $result = $this->row($queryData);
        }else{
            $result = $this->rows($queryData);
        }
		return $result;
    }

	public function delete($data){
        try{
            $this->db->trans_begin();

            $grnItemsForCount = $this->getInwardItem(['grn_id'=>$data['grn_id']]);         
            $grnCount = (!empty($grnItemsForCount) ? count($grnItemsForCount) : 0);

            $grnTransData = $this->getInwardItem(['id'=>$data['id'],'single_row'=>1]); 
            if (!empty($grnTransData)) {
                
                $stockData = $this->store->getItemStockBatchWise(['item_id'=>$grnTransData->item_id,'stock_required'=>1,'single_row'=>1]);
                $stock_qty = (!empty($stockData->qty) ? $stockData->qty : 0);

                if($grnTransData->qty > $stock_qty) {
                    return ['status'=>0,'message'=>'You can not delete this GRN.'];
                }
                $this->remove('stock_trans', ['trans_type'=>'GRN', 'child_ref_id'=>$data['id']]);
                $result = $this->trash($this->grnTrans,['id'=>$data['id']]);

                if($grnCount <= 1):
                    $this->trash($this->grnMaster,['id'=>$data['grn_id']],'Gate Inward');  
                endif;

            }else{
                $result = ['status'=>0,'message'=>'GRN already deleted'];
            }
      
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
    
    public function updateGRN($data){
        try{
            $this->db->trans_begin();
				
            $itemData = $data['itemData']; unset($data['itemData']); 
            $this->remove('stock_trans', ['trans_type'=>'GRN', 'child_ref_id'=>$itemData['trans_id']]);
			           
            $result = $this->store($this->grnMaster, $data, 'GRN');

            if (!empty($itemData['trans_id'])) {
                $itemData['grn_id'] = $data['id'];
                $itemData['id'] = $itemData['trans_id'];
                unset($itemData['trans_id']);                
                $resultTrans = $this->store($this->grnTrans, $itemData);
                
                //STOCK TRANS EFFECT
                $stockPlusQuery = [
                    'id' => "",
                    'trans_type' =>'GRN',
                    'trans_date' => $data['trans_date'],
                    'item_id' => $itemData['item_id'],
                    'qty' => $itemData['qty'],
                    'p_or_m' => 1,
                    'main_ref_id' => $itemData['grn_id'], 
                    'child_ref_id' => $itemData['id'],
                    'ref_no' => $data['trans_number']
                ];
                $this->store('stock_trans', $stockPlusQuery);
            }
			
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
}
?>