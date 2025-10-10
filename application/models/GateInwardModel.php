<?php
class GateInwardModel extends masterModel{
    private $grn_master = "grn_master";
    private $grn_trans = "grn_trans";
    private $po_master = "po_master";
    private $po_trans = "po_trans";
    private $stockTrans = "stock_trans";
    private $icInspection = "ic_inspection";
    private $inspectParam = "inspection_param";
    private $testReport = "grn_test_report";
    private $batch_history = "batch_history";

    public function getDTRows($data){
        if($data['trans_type'] == 1):
            $data['tableName'] = $this->grn_master;

            $data['select'] = "grn_master.id,grn_master.trans_number,DATE_FORMAT(grn_master.trans_date,'%d-%m-%Y') as trans_date,party_master.party_name,grn_master.inv_no,ifnull(DATE_FORMAT(grn_master.inv_date,'%d-%m-%Y'),'') as inv_date,grn_master.doc_no,ifnull(DATE_FORMAT(grn_master.doc_date,'%d-%m-%Y'),'') as doc_date,grn_master.trans_status,grn_master.trans_type";

            $data['where']['grn_master.trans_status'] = $data['trans_status'];
            $data['where']['grn_master.entry_type'] = $this->data['entryData']->id;
        else:
            $data['tableName'] = $this->grn_trans;

            $data['select'] = "grn_master.id,grn_master.trans_number,DATE_FORMAT(grn_master.trans_date,'%d-%m-%Y') as trans_date,party_master.party_name,item_master.item_name,grn_master.inv_no,ifnull(DATE_FORMAT(grn_master.inv_date,'%d-%m-%Y'),'') as inv_date,grn_master.doc_no,ifnull(DATE_FORMAT(grn_master.doc_date,'%d-%m-%Y'),'') as doc_date,po_master.trans_number as po_number,grn_trans.trans_status,grn_master.trans_type,grn_trans.qty,grn_trans.id as mir_trans_id,item_master.item_type,grn_trans.iir_status,item_category.category_name,item_category.is_inspection,item_master.uom";

            $data['leftJoin']['grn_master'] = "grn_master.id = grn_trans.mir_id";
            $data['leftJoin']['item_master'] = "item_master.id = grn_trans.item_id";
            $data['leftJoin']['po_master'] = "po_master.id = grn_trans.po_id";        
			$data['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";

            $data['where']['grn_trans.trans_status'] = $data['trans_status'];
            $data['where']['grn_trans.entry_type'] = $this->data['entryData']->id;
        endif;

        $data['leftJoin']['party_master'] = "party_master.id = grn_master.party_id";
		
        $data['where']['grn_master.trans_type'] = $data['trans_type'];
            
        $data['order_by']['grn_master.id'] = "DESC";

        if($data['trans_type'] == 1):
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "grn_master.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(grn_master.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "party_master.party_name";
            $data['searchCol'][] = "grn_master.inv_no";
            $data['searchCol'][] = "ifnull(DATE_FORMAT(grn_master.inv_date,'%d-%m-%Y'),'')";
            $data['searchCol'][] = "grn_master.doc_no";
            $data['searchCol'][] = "ifnull(DATE_FORMAT(grn_master.doc_date,'%d-%m-%Y'),'')";
        else:
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "grn_master.trans_number";
            $data['searchCol'][] = "DATE_FORMAT(grn_master.trans_date,'%d-%m-%Y')";
            $data['searchCol'][] = "party_master.party_name";
            $data['searchCol'][] = "item_master.item_name";
            $data['searchCol'][] = "CONCAT(grn_trans.qty, ' ',item_master.uom)";
            $data['searchCol'][] = "po_master.trans_number";
        endif;

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
		if (isset($data['order'])) {
			$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];
		}

		return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if(!empty($data['id'])):
                $gateInwardData = $this->getGateInward($data['id']);

                if(!empty($gateInwardData->ref_id)):
                    $this->store($this->grn_master,['id'=>$gateInwardData->ref_id,'trans_status'=>0]);
                endif;

                foreach($gateInwardData->itemData as $row):
                    if(!empty($row->po_trans_id)):
                        $comQty = (!empty($row->conversion_value) && $row->conversion_value > 0) ? round($row->qty / ($row->conversion_value),2) : $row->qty;//06-06-24
                        $setData = array();
                        $setData['tableName'] = $this->po_trans;
                        $setData['where']['id'] = $row->po_trans_id;
                        $setData['set']['dispatch_qty'] = 'dispatch_qty, - '.$comQty;
                        $setData['update']['trans_status'] = 3;
                        $this->setValue($setData);

                        $setData = array();
                        $setData['tableName'] = $this->po_master;
                        $setData['where']['id'] = $row->po_id;
                        $setData['update']['trans_status'] = 3;
                        $this->setValue($setData);
                    endif;

                    $this->trash($this->grn_trans,['id'=>$row->id]);
                endforeach;
            endif;

            $itemData = $data['batchData'];unset($data['batchData']);

            $data['trans_type'] = 2;$data['entry_type'] = $this->data['entryData']->id;
            $result = $this->store($this->grn_master,$data,'Gate Inward');

            foreach($itemData as $row):         
                $itemData = $this->item->getItem($row['item_id']);

                $row['mir_id'] = $result['id'];
                $row['entry_type'] = $this->data['entryData']->id;
                $row['type'] = 1;
                $row['is_delete'] = 0;

                $this->store($this->grn_trans,$row);

                if(!empty($row['po_trans_id'])):
                    $comQty = (!empty($row['conversion_value']))? round(($row['qty'] / $row['conversion_value']),2) : $row['qty'];//06-06-24
                    $setData = array();
                    $setData['tableName'] = $this->po_trans;
                    $setData['where']['id'] = $row['po_trans_id'];
                    $setData['set']['dispatch_qty'] = 'dispatch_qty, + '.$comQty;
                    $setData['update']['trans_status'] = "(CASE WHEN dispatch_qty >= qty THEN 1 ELSE 3 END)";
                    $this->setValue($setData);

                    $setData = array();
                    $setData['tableName'] = $this->po_master;
                    $setData['where']['id'] = $row['po_id'];
                    $setData['update']['trans_status'] = "(SELECT IF( COUNT(id) = SUM(IF(trans_status != 3, 1, 0)) ,1 , 3 ) as trans_status FROM po_trans WHERE trans_main_id = ".$row['po_id']." AND is_delete = 0)";
                    $this->setValue($setData);
                endif;
                
            endforeach;

            //Update GI Status
            if(!empty($data['ref_id'])):
                $this->store($this->grn_master,['id'=>$data['ref_id'],'trans_status'=>1]);
            endif;        

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getGateInward($id){
        $queryData['tableName'] = $this->grn_master;
        $queryData['select'] = "grn_master.*,party_master.party_name,party_master.party_mobile,party_master.gstin,party_master.contact_person";
        $queryData['leftJoin']['party_master'] = "grn_master.party_id = party_master.id";
        $queryData['where']['grn_master.id'] = $id;
        $result = $this->row($queryData);

        $result->itemData = $this->getGateInwardItems($id);
        return $result;
    }
    
    public function getGateInwardItems($id){
        $queryData['tableName'] = $this->grn_trans;
        $queryData['select'] = "grn_trans.*,item_master.item_code,item_master.item_name,location_master.location as location_name,po_master.trans_number as po_number,item_master.uom"; 
        $queryData['leftJoin']['item_master'] = "item_master.id = grn_trans.item_id";
        $queryData['leftJoin']['location_master'] = "location_master.id = grn_trans.location_id";
        $queryData['leftJoin']['po_master'] = "po_master.id = grn_trans.po_id";
        $queryData['where']['grn_trans.mir_id'] = $id;
        return $this->rows($queryData);
    }

    public function getInwardItem($data){
        $queryData['tableName'] = $this->grn_trans;
		$queryData['select'] = "grn_trans.*,item_master.item_code,item_master.item_name,item_master.stock_type,location_master.location as location_name,trans_main.trans_number as po_no,grn_master.trans_number,grn_master.trans_date,party_master.party_name,grn_master.inv_no,grn_master.inv_date,grn_master.trans_prefix,grn_master.trans_no,item_master.uom";
        $queryData['leftJoin']['item_master'] = "item_master.id = grn_trans.item_id";
        $queryData['leftJoin']['location_master'] = "location_master.id = grn_trans.location_id";
        $queryData['leftJoin']['trans_main'] = "trans_main.id = grn_trans.po_id";
        $queryData['leftJoin']['grn_master'] = "grn_master.id = grn_trans.mir_id";
        $queryData['leftJoin']['party_master'] = "party_master.id = grn_master.party_id";
        $queryData['where']['grn_trans.id'] = $data['id'];
        return $this->row($queryData);
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $gateInwardData = $this->getGateInward($id);

            if(!empty($gateInwardData->ref_id)):
                $this->store($this->grn_master,['id'=>$gateInwardData->ref_id,'trans_status'=>0]);
            endif;

            foreach($gateInwardData->itemData as $row):
                if(!empty($row->po_trans_id)):
                    $comQty = ($row->conversion_value > 0)? round($row->qty / ($row->conversion_value),2) : $row->qty;//06-06-24
                    $setData = array();
                    $setData['tableName'] = $this->po_trans;
                    $setData['where']['id'] = $row->po_trans_id;
                    $setData['set']['dispatch_qty'] = 'dispatch_qty, - '.$comQty;
                    $setData['update']['trans_status'] = 3;
                    $this->setValue($setData);

                    $setData = array();
                    $setData['tableName'] = $this->po_master;
                    $setData['where']['id'] = $row->po_id;
                    $setData['update']['trans_status'] = 3;
                    $this->setValue($setData);
                endif;

                $this->trash($this->grn_trans,['id'=>$row->id]);
            endforeach;

            $result = $this->trash($this->grn_master,['id'=>$id],'Gate Inward');        

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
	
    public function saveInspectedMaterial($data){ 
        try{
            $this->db->trans_begin();
			
                $mirData = $this->getGateInward($data['mir_id']);
                $mirItem = $this->getInwardItem(['id'=>$data['id']]);

	            $totalQty = 0;
				$totalQty = ($data['ok_qty'] + $data['reject_qty'] + $data['short_qty']);
				
				if($mirItem->qty != $totalQty): 
					$this->db->trans_rollback();  
					return ['status'=>0,'message'=>['ok_qty' => "Invalid Qty."]];
				endif;
				
                //06-06-24
                $okQty = ($mirItem->conversion_value > 0) ? round($data['ok_qty'] / ($mirItem->conversion_value),2) : $data['ok_qty'];
                $rejQty = ($mirItem->conversion_value > 0) ? round($data['reject_qty'] / ($mirItem->conversion_value),2) : $data['reject_qty'];
                $shortQty = ($mirItem->conversion_value > 0) ? round($data['short_qty'] / ($mirItem->conversion_value),2) : $data['short_qty'];

                $data['ok_qty'] = (!empty($okQty))?$okQty:0;
                $data['reject_qty'] = (!empty($rejQty ))?$rejQty :0;
                $data['short_qty'] = (!empty($shortQty))?$shortQty:0;
                 
                $this->remove($this->stockTrans,['trans_type'=>'GRN','main_ref_id' => $mirData->id,'child_ref_id' => $mirItem->id]);

                $data['trans_status'] = ($totalQty >= $mirItem->qty)?1:0;

                $this->store($this->grn_trans,$data);

                if(!empty($data['ok_qty'])):
						$stockData = [
							'id' => "",
							'trans_type' => 'GRN',
							'trans_date' => $mirData->trans_date,
							'ref_no' => $mirData->trans_number,
							'main_ref_id' => $mirData->id,
							'child_ref_id' => $mirItem->id,
							'location_id' => $mirItem->location_id,
							'item_id' => $mirItem->item_id,
							'p_or_m' => 1,
							'qty' => $data['ok_qty'],
						];
						$this->store($this->stockTrans,$stockData);
                   
                endif;

            $result = ['status'=>1,'message'=>"Material Inspected successfully."];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }
		
    public function getPendingInwardItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->grn_trans;
        $queryData['select'] = "grn_trans.*,(grn_trans.qty - grn_trans.inv_qty) as pending_qty,grn_master.entry_type as main_entry_type,grn_master.trans_number,grn_master.trans_date,grn_master.inv_no,grn_master.inv_date,grn_master.doc_no,grn_master.doc_date,item_master.item_code,item_master.item_name,item_master.item_type,item_master.hsn_code,item_master.gst_per,unit_master.id as unit_id,unit_master.unit_name,'0' as stock_eff";
        $queryData['leftJoin']['grn_master'] = "grn_trans.mir_id = grn_master.id";
        $queryData['leftJoin']['item_master'] = "item_master.id = grn_trans.item_id";
        $queryData['leftJoin']['unit_master'] = "item_master.unit_id = unit_master.id";
        $queryData['where']['grn_master.party_id'] = $data['party_id'];
        $queryData['where']['grn_trans.entry_type'] = $this->data['entryData']->id;
        $queryData['where']['(grn_trans.qty - grn_trans.inv_qty) >'] = 0;
        $queryData['where']['grn_trans.trans_status'] = 0;
        return $this->rows($queryData);
    }

	public function getInspectParamData($id) {
        $queryData = array();
        $queryData['tableName'] = $this->inspectParam;
        $queryData['select'] = "inspection_param.*,grn_trans.id AS mir_trans_id, grn_master.id AS mir_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = inspection_param.item_id";
        $queryData['leftJoin']['grn_trans'] = "grn_trans.item_id = item_master.id";
        $queryData['leftJoin']['grn_master'] = "grn_master.id = grn_trans.mir_id";
        $queryData['where']['grn_trans.mir_id'] = $id;
        return $this->rows($queryData);
    }

    public function getInInspectData($data) {
        $queryData = array();
        $queryData['tableName'] = $this->icInspection;
        $queryData['select'] = "ic_inspection.*";
        if(!empty($data['mir_trans_id'])){ $queryData['where']['ic_inspection.mir_trans_id'] = $data['mir_trans_id']; }
        return $this->row($queryData);
    }

    public function saveInInspection($data) {
        try{
            $this->db->trans_begin();

            $mir_trans_id = $data['id']; unset($data['id']);
            $data['mir_trans_id'] = $mir_trans_id;

            $inInpectData = $this->getInInspectData($mir_trans_id);
            $data['id'] = (!empty($inInpectData->id))?$inInpectData->id:"";;

            $this->store($this->icInspection, $data);

            $result = ['status'=>1,'message'=>"Inspection successfully."];

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getNextIIRNo($type = 2){
        $queryData['tableName'] = $this->icInspection;
        $queryData['select'] = "ifnull(MAX(trans_no + 1),1) as next_no";
        $queryData['where']['YEAR(trans_date)'] = date("Y");
        return $this->row($queryData)->next_no;
    }
	public function saveInwardQc($data){
		try{
            $this->db->trans_begin();

            $this->edit($this->grn_trans,['id'=>$data['mir_trans_id']],['iir_status'=>1]);
    		$result = $this->store($this->icInspection,$data,'Inward QC');

    		if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
	}

    public function getTestReport($postData){
        $data['tableName'] = $this->testReport;
        $data['where']['grn_id'] = $postData['grn_id'];
        return $this->rows($data);
    }

    public function saveTestReport($data){
        try{
            $this->db->trans_begin();
			
			$result = $this->store($this->testReport,$data);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function deleteTestReport($id){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->testReport,['id'=>$id],'Test Report');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    //Batch No and Qty
    public function getItemWiseBatchList($id){
        $queryData['tableName'] = $this->stockTrans;
        $queryData['select'] = "stock_trans.qty,stock_trans.item_id,stock_trans.location_id";
        $queryData['where']['stock_trans.child_ref_id'] = $id;
        return $this->rows($queryData);
    }
}
?>