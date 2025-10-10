<?php
class ItemModel extends MasterModel{
    private $itemMaster = "item_master";
    private $unitMaster = "unit_master";
    private $item_udf = "item_udf";
    private $productProcess = "product_process";
	private $processMaster = "process_master";
    private $inspectionParam = "inspection_param";
	private $item_revision = "item_revision";
    private $itemKit = "item_kit";

    // 20-02-2024
    public function getDTRows($data){
        $data['tableName'] = $this->itemMaster;
		$data['select'] = "item_master.*,CAST(item_master.gst_per AS FLOAT) as gst_per,item_category.category_name,unit_master.unit_name,item_category.is_inspection";
   
        $data['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";
        $data['leftJoin']['unit_master'] = "unit_master.id  = item_master.unit_id";

        $data['where']['item_master.item_type'] = $data['item_type'];
        $data['where']['item_master.active'] = 1;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "item_master.item_code";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "item_category.category_name";
        $data['searchCol'][] = "unit_master.unit_name";
        $data['searchCol'][] = "item_master.hsn_code";
        $data['searchCol'][] = "item_master.gst_per";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function getItemList($data=array()){
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.*,item_master.id as item_id,unit_master.unit_name,item_category.category_name,item_category.batch_stock as stock_type,item_category.location_id";

        $queryData['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";
        $queryData['leftJoin']['unit_master'] = "item_master.unit_id = unit_master.id";
        
        if(!empty($data['item_type'])):
            $queryData['where_in']['item_master.item_type'] = $data['item_type'];
        endif;

		if(!empty($data['category_id'])):
            $queryData['where_in']['item_master.category_id'] = $data['category_id'];
        endif;

        if(!empty($data['ids'])):
            $queryData['where_in']['item_master.id'] = $data['ids'];
        endif;

        if(!empty($data['not_ids'])):
            $queryData['where_not_in']['item_master.id'] = $data['not_ids'];
        endif;

        if(isset($data['is_packing'])):
            $queryData['where']['item_master.is_packing'] = $data['is_packing'];
        endif;

        if(!empty($data['active_item'])):
            $queryData['where_in']['item_master.active'] = $data['active_item'];
        else:
            $queryData['where']['item_master.active'] = 1;
        endif;

        return $this->rows($queryData);
    }

    public function getItem($data){
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.*,unit_master.unit_name,item_category.category_name,item_category.batch_stock as stock_type,unit.unit_name as com_unit,item_category.location_id";

        $queryData['leftJoin']['unit_master'] = "item_master.unit_id = unit_master.id";
        $queryData['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";
        $queryData['leftJoin']['unit_master as unit'] = "item_master.com_unit_id = unit.id";
        
        if(!empty($data['id'])):
            $queryData['where']['item_master.id'] = $data['id'];
        endif;

        if(!empty($data['item_code'])):
            $queryData['where']['item_master.item_code'] = trim($data['item_code']);
        endif;

        if(!empty($data['item_types'])):
            $queryData['where_in']['item_master.item_type'] = $data['item_types'];
        endif;
        
        if(!empty($data['customWhere'])):
            $queryData['customWhere'][] = $data['customWhere'];
        endif;

        if(!empty($data['item_name'])):
            $queryData['where']['item_master.item_name'] = $data['item_name'];
        endif;

        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $customField = (!empty($data['customField']))?$data['customField']:array(); 
            unset($data['customField']);

            if($this->checkDuplicate($data) > 0):
                $errorMessage['item_name'] = "Item Name is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;
            
			$untData = (!empty($data['unit_id'])) ? $this->itemUnit($data['unit_id']) : array();
			$data['uom'] = (!empty($untData->unit_name))?$untData->unit_name:'NOS';
			
            $result = $this->store($this->itemMaster,$data,"Item");

            if(!empty($customField)):
                $itemUdfData = $this->getItemUdfData(['item_id'=>$result['id']]); 
                $customField['item_id'] =$result['id'];       
                $customField['id'] = !empty($itemUdfData->id)?$itemUdfData->id :'';
                $this->store($this->item_udf,$customField);
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

    public function checkDuplicate($data){
        $queryData['tableName'] = $this->itemMaster;

        if(!empty($data['item_name']))
            $queryData['where']['item_name'] = $data['item_name'];
        if(!empty($data['item_type']))
            $queryData['where']['item_type'] = $data['item_type'];
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function getItemUdfData($param = []){
		$queryData['tableName'] = $this->item_udf;
        if(!empty($param['item_id'])):
            $queryData['where']['item_udf.item_id'] = $param['item_id'];
        endif;
        return $this->row($queryData);
	}

    public function delete($id){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = ["item_id","scrap_group","ref_item_id","box_id","packing_in"];
            $checkData['value'] = $id;
            $checkUsed = $this->checkUsage($checkData);
            
            if($checkUsed == true):
                return ['status'=>0,'message'=>'The Item is currently in use. you cannot delete it.'];
            endif;

            $result = $this->trash($this->itemMaster,['id'=>$id],'Item');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function itemUnits(){
        $queryData['tableName'] = $this->unitMaster;
		return $this->rows($queryData);
	}

    public function itemUnit($id){
        $queryData['tableName'] = $this->unitMaster;
		$queryData['where']['id'] = $id;
		return $this->row($queryData);
	}
	
	//12-04-2024
    public function getUnitNameWiseId($data=array()){
        $data['tableName'] = $this->unitMaster;
        if(!empty($data['unit_name'])){
            $data['where']['unit_name'] = $data['unit_name'];
        }
        return $this->row($data); 
    }

    public function getItemProcess($id){
		$data['tableName'] = $this->productProcess;
		$data['select'] = "product_process.*,process_master.process_name,item_master.item_code";
		$data['join']['process_master'] = "process_master.id = product_process.process_id";
		$data['leftJoin']['item_master'] = "item_master.id = product_process.item_id";
		$data['where']['product_process.item_id'] = $id;
		$data['order_by']['product_process.sequence'] = "ASC";
		return $this->rows($data);
	}
	
	/* Start Item Revision 
    Created By Rashmi @24-04-2024 */
	public function getItemRevision($id){
		$data['tableName'] = "item_revision";
        $data['select'] = "item_revision.*,";
        $data['leftJoin']['item_master'] = "item_master.id = item_revision.item_id";		
		if(!empty($param['item_id'])){$data['where']['item_revision.item_id'] = $param['item_id'];}
        $data['order_by']['item_revision.rev_no'] = 'ASC';
		$result = $this->rows($data);
		return $result;
	}

	public function saveItemRevision($data){
		try{
            $this->db->trans_begin();
            if($this->checkDuplicateRevNo($data) > 0):
				$errorMessage['rev_no'] = "Revision No. is duplicate.";
				return ['status'=>0,'message'=>$errorMessage];
			endif;
            $itemData = [
				'id'=>$data['id'],
				'item_id'=>$data['item_id'],
				'rev_no'=>$data['rev_no'],
				'rev_date'=>$data['rev_date'],
            ];
            $result = $this->store($this->item_revision,$itemData,'');
            $result = ['status'=>1,'message'=>'Item Revision saved successfully.'];
			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
		   return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}

    public function checkDuplicateRevNo($data){
        $queryData['tableName'] = $this->item_revision;

        if(!empty($data['rev_no']))
            $queryData['where']['rev_no'] = $data['rev_no'];
        if(!empty($data['item_id']))
            $queryData['where']['item_id'] = $data['item_id'];
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function deleteItemRevision($id){
        try{
            $this->db->trans_begin();
		$result =  $this->trash('item_revision',['id'=>$id],'');
        if ($this->db->trans_status() !== FALSE):
            $this->db->trans_commit();
            return $result;
        endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
        return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
	}
    // End Item Revision

    /* Start Inspection 
    Created By Rashmi @24-04-2024 */
    public function getInspectionParam($id){ 
		$data['tableName'] = $this->inspectionParam;
		$data['select'] = "inspection_param.*,process_master.process_name";	
		$data['leftJoin']['item_master'] = "item_master.id = inspection_param.item_id";
		$data['leftJoin']['process_master'] = "process_master.id = inspection_param.process_id";
		$data['where']['inspection_param.item_id'] = $id;
		return $this->rows($data);
	}
	
	public function saveInspection($data){
		try{
            $this->db->trans_begin();
			$result = $this->store($this->inspectionParam,$data,'');
			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}

	public function deleteInspection($id){
		try{
			$this->db->trans_begin();
			$result = $this->trash($this->inspectionParam,['id'=>$id],"Record");
			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}

    public function saveInspectionParamExcel($postData){
		try{
            $this->db->trans_begin();
			foreach($postData as $data){
				$result = $this->store($this->inspectionParam,$data,'Parameter');
			}
			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}
    /*End Inspection */

    /* Product Option */
    public function getProdOptDTRows($data){
        $data['tableName'] = $this->itemMaster;
        $data['select'] = "item_master.*,item_master.uom AS unit_name,item_category.category_name";
        $data['leftJoin']['item_category'] = "item_category.id = item_master.category_id";
		if(!empty($data['item_type'])){
			$data['where']['item_master.item_type'] = $data['item_type'];
		}else{
			$data['where']['item_master.item_type'] = 1;
		}
		
        $data['searchCol'][] = "";
        $data['searchCol'][] = "item_master.item_code";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = ""; 
        $data['searchCol'][] = ""; 

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function checkProductOptionStatus($id){
		$result = new StdClass; 
        $result->bom=0; $result->process=0;

		$queryData = Array();
		$queryData['tableName'] = $this->itemKit;
		$queryData['where']['item_id'] = $id;
		$bomData = $this->rows($queryData);
		$result->bom = count($bomData);
		
		$queryData = Array();
		$queryData['tableName'] = $this->productProcess;
		$queryData['where']['item_id'] = $id;
		$processData = $this->rows($queryData);
		$result->process = count($processData);
		
        $queryData = Array();
		$queryData['tableName'] = $this->productProcess;
		$queryData['where']['item_id'] = $id;
		$queryData['where']['cycle_time >'] = 0;
		$ctData = $this->rows($queryData);
		$result->cycleTime=count($ctData);

		return $result;
	}

    public function getProductProcessList($param = []){
		$queryData['tableName'] = $this->productProcess;
		$queryData['select'] = "product_process.id,product_process.item_id,product_process.process_id,product_process.cycle_time,product_process.finish_wt,product_process.sequence,process_master.process_name";
		$queryData['leftJoin']['process_master'] = "process_master.id = product_process.process_id";
		$queryData['order_by']['product_process.sequence'] = 'ASC';
		if(!empty($param['item_id'])){ $queryData['where']['product_process.item_id'] = $param['item_id']; }
		if(!empty($param['process_id'])){ $queryData['where_in']['product_process.process_id'] = $param['process_id']; }
        if(!empty($param['single_row'])){
            return $this->row($queryData);
        }else{
            return $this->rows($queryData);
        }
	}

    public function groupSearch($data){
		$data['tableName'] = $this->itemKit;
		$data['select'] = 'group_name';
		$data['where']['item_id'] = $data['item_id'];
        $data['group_by'][]="group_name";
		$result = $this->rows($data);
		$searchResult = array();
		foreach ($result as $row) {
			$searchResult[] = $row->group_name;
		}
		return $searchResult;
	}

	public function saveProductKit($data){
		try{
            $this->db->trans_begin();

			if($this->checkDuplicateBom($data) > 0):  
				$errorMessage['kit_item_id'] = "Item Bom is duplicate.";
				return ['status'=>0,'message'=>$errorMessage];
			endif;
           

            $itemKitData = [
                'id'=>$data['id'],
                'group_name'=>$data['group_name'],
                'item_id'=>$data['item_id'],
                'ref_item_id'=>$data['kit_item_id'],
                'ref_id'=>$data['ref_id'],
                'process_id'=>$data['process_id'],
                'qty'=>$data['kit_item_qty']
            ];
			if($data['ref_id'] != 0)
			{
				$altData = $this->getProductKitData(['id'=>$data['ref_id'],'single_row'=>1,'not_in_item_type'=>9]);
				$itemKitData['alt_item_id'] = (!empty($altData->ref_item_id) ? $altData->ref_item_id : 0);
			}
            $result = $this->store($this->itemKit,$itemKitData,'Product Bom');
			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
		   return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}

    public function checkDuplicateBom($data){
        $queryData['tableName'] = $this->itemKit;

        if(!empty($data['kit_item_id']))
            $queryData['where']['ref_item_id'] = $data['kit_item_id'];
		
		if(!empty($data['item_id']))
			$queryData['where']['item_id'] = $data['item_id'];

        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function getProductKitData($param = []){
		$queryData['tableName'] = $this->itemKit;
		$queryData['select'] = "item_kit.*,item_master.item_name,item_master.item_code,IFNULL(process_master.process_name,'Initial Stage') as process_name,item_master.item_type";
		$queryData['leftJoin']['item_master'] = "item_master.id = item_kit.ref_item_id";
		$queryData['leftJoin']['process_master'] = "process_master.id = item_kit.process_id";

		if(!empty($param['with_alt_items'])){
			$queryData['select'] .= ',altKit.ref_item_id as alt_ref_item,altKit.process_id as alt_process_id,altKit.qty as alt_qty,altItem.item_code as alt_item_code,altItem.item_name as alt_item_name';
			$queryData['leftJoin']['item_kit altKit'] = "altKit.ref_id = item_kit.id";
			$queryData['leftJoin']['item_master altItem'] = "altItem.id = altKit.ref_item_id";
		}

        if(!empty($param['item_id'])){$queryData['where']['item_kit.item_id'] = $param['item_id'];}
        if(!empty($param['group_name'])){$queryData['where']['item_kit.group_name'] = $param['group_name'];}
        if(!empty($param['is_main'])){$queryData['where']['item_kit.ref_id'] = 0;}
        if(!empty($param['ref_id'])){$queryData['where']['item_kit.ref_id'] = $param['ref_id'];}
        if(!empty($param['alt_item_id'])){$queryData['where']['item_kit.alt_item_id'] = $param['alt_item_id'];}
        if(!empty($param['id'])){$queryData['where']['item_kit.id'] = $param['id'];}
        if(!empty($param['ref_item_id'])){$queryData['where']['item_kit.ref_item_id'] = $param['ref_item_id'];}
        if(!empty($param['item_type'])){$queryData['where']['item_master.item_type'] = $param['item_type'];}
		if (!empty($param['rm_ids'])) { $queryData['where_in']['item_kit.ref_item_id'] = str_replace("~", ",", $param['rm_ids']); }
		if(!empty($param['not_in_item_type'])){$queryData['where']['item_master.item_type !='] = $param['not_in_item_type'];}
        if(isset($param['packing_type'])){$queryData['where']['item_kit.packing_type'] = $param['packing_type'];}
        if(!empty($param['group_by'])){ $queryData['group_by'][] = $param['group_by']; }
        
		if(!empty($param['single_row'])){
            return $this->row($queryData);
        }else{
            return $this->rows($queryData);
        }		
	}

    public function deleteProductKit($id){
        try{
			$this->db->trans_begin();

			$result = $this->trash($this->itemKit,['id'=>$id],'Product Bom');

			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}

	public function saveProductProcess($data){
		try{
            $this->db->trans_begin();
			
				if($this->checkDuplicateProcess($data) > 0):
					$errorMessage['process_id'] = "Process is duplicate.";
					return ['status'=>2,'message'=>$errorMessage];
				endif;
				$queryData = array();
				$queryData['select'] = "MAX(sequence) as sequence";
				$queryData['where']['item_id'] = $data['item_id'];
				$queryData['where']['is_delete'] = 0;
				$queryData['tableName'] = $this->productProcess;
				$sequence = $this->specificRow($queryData)->sequence;
				$nextsequence = (!empty($sequence))?($sequence + 1):1; 
				
				$productProcessData = [
					'id'=>"",
					'item_id'=>$data['item_id'],
					'process_id'=>$data['process_id'],
					'sequence'=>$nextsequence,
					'created_by' => $this->session->userdata('loginId')
				];
				$this->store($this->productProcess,$productProcessData,'');
    
    		$result = ['status'=>1,'message'=>'Product process saved successfully.'];

    		if ($this->db->trans_status() !== FALSE):
    			$this->db->trans_commit();
    			return $result;
    		endif;
    	}catch(\Exception $e){
    		$this->db->trans_rollback();
    	    return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
    	}	
	}

	public function checkDuplicateProcess($data){
        $queryData['tableName'] = $this->productProcess;

        if(!empty($data['process_id']))
            $queryData['where']['process_id'] = $data['process_id'];
        if(!empty($data['item_id']))
            $queryData['where']['item_id'] = $data['item_id'];
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

	public function deleteProductProcess($data){
        try{
			$this->db->trans_begin();

			$recData = $this->getProductProcessPrcWise(['item_id'=>$data['item_id'],'process_ids'=>$data['process_id']]);
			
			if(!empty($recData->prc_number)){
				$result = ['status'=>2,'message'=>'The process is currently in use. you cannot delete it.'];
			}else{
				$result = $this->trash($this->productProcess,['id'=>$data['id']],'Product Process');
			}

			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}

	public function getProductProcessPrcWise($data){
		$queryData['tableName']  = 'prc_master';
		$queryData['select'] = 'prc_master.*,prc_detail.process_ids,prc_master.prc_number';
		$queryData['leftJoin']['prc_detail'] = 'prc_detail.prc_id = prc_master.id';
		if(!empty($data['item_id'])){$queryData['where']['prc_master.item_id'] = $data['item_id'];}
		if(!empty($data['process_ids'])){$queryData['where']['find_in_set("'.$data['process_ids'].'", prc_detail.process_ids) >'] = 0;}
		$result =  $this->row($queryData);
		return $result;
	}

    public function updateProductProcessSequance($data){
		try{
            $this->db->trans_begin();
            
    		$ids = explode(',', $data['id']);
    		$i=1;
    		foreach($ids as $pp_id):
    			$seqData=Array("sequence"=>$i++);
    			$this->edit($this->productProcess,['id'=>$pp_id],$seqData);
    		endforeach;

    		$result = ['status'=>1,'message'=>'Process Sequence updated successfully.'];

    		if ($this->db->trans_status() !== FALSE):
    			$this->db->trans_commit();
    			return $result;
    		endif;
    	}catch(\Exception $e){
    		$this->db->trans_rollback();
    	    return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
    	}	
	}

	public function setProductionType($data) {
        try{
            $this->db->trans_begin();
            
            $result = $this->store($this->itemMaster, ['id'=> $data['item_id'], 'production_type' => $data['production_type']]);

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	    
    }
    /* End Product Option */
	
	public function getInspectionParameter($postData=[]){
		$data['tableName'] = $this->inspectionParam;
		$data['select'] = "inspection_param.*,item_master.item_name";
		$data['leftJoin']['item_master'] = "item_master.id = inspection_param.item_id";
		if(!empty($postData['item_id'])){ $data['where']['item_id'] = $postData['item_id']; }
        if(!empty($postData['process_id'])){ $data['where']['process_id'] = $postData['process_id']; }
        if(!empty($postData['control_method'])){  $data['where']['find_in_set("'.$postData['control_method'].'",control_method) > '] = 0;  }
        if(!empty($postData['rev_no'])){  $data['where']['find_in_set("'.$postData['rev_no'].'",rev_no) > '] = 0;  }
		return $this->rows($data);
	}

    public function saveProductProcessCycleTime($data){
		try{
            $this->db->trans_begin();

    		foreach($data['id'] as $key=>$value):
				$productProcessData = ['id'=>$value,'cycle_time'=>$data['cycle_time'][$key],'finish_wt'=>$data['finish_wt'][$key],'conv_ratio'=>$data['conv_ratio'][$key],'updated_by'=>$data['loginId']];
				$this->store($this->productProcess,$productProcessData,'');
    		endforeach;
    
    		$result = ['status'=>1,'message'=>'Cycle Time Updated successfully.'];
			
    		if ($this->db->trans_status() !== FALSE):
    			$this->db->trans_commit();
    			return $result;
    		endif;
    	}catch(\Exception $e){
    		$this->db->trans_rollback();
    	   return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
    	}	
	}

    public function getProductProcessForSelect($id){
		$data['select'] = "process_id";
		$data['where']['item_id'] = $id;
		$data['tableName'] = $this->productProcess;
		$result = $this->rows($data);
		$process = array();
		if($result){foreach($result as $row){$process[] = $row->process_id;}}
		return $process;
	}

	public function savePackingStandard($data){
		try{
            $this->db->trans_begin();

			if($this->checkDuplicateStandard($data) > 0):  
				$errorMessage['ref_item_id'] = "Packing Standard is duplicate.";
				return ['status'=>0,'message'=>$errorMessage];
			endif;
           
            $result = $this->store($this->itemKit, $data, 'Packing Standard');

			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
		   return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}

	public function checkDuplicateStandard($data){
        $queryData['tableName'] = $this->itemKit;

        if(!empty($data['ref_item_id']))
            $queryData['where']['ref_item_id'] = $data['ref_item_id'];
		
		if(!empty($data['item_id']))
			$queryData['where']['item_id'] = $data['item_id'];
		if(!empty($data['group_name']))
			$queryData['where']['group_name'] = $data['group_name'];
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

	public function deletePackingStandard($id){
        try{
			$this->db->trans_begin();

			$result = $this->trash($this->itemKit, ['id'=>$id], 'Packing Standard');

			if ($this->db->trans_status() !== FALSE):
				$this->db->trans_commit();
				return $result;
			endif;
		}catch(\Exception $e){
			$this->db->trans_rollback();
			return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
		}	
	}
}
?>