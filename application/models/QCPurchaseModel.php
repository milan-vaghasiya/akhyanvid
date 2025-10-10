<?php
class QCPurchaseModel extends MasterModel{
    private $transMain = "trans_main";
    private $transChild = "trans_child";
	private $transExpense = "trans_expense";
    private $transDetails = "trans_details";
	private $itemCategory = "item_category";
    private $itemMaster = "item_master";
    private $qc_indent = "qc_indent";

    public function nextPoNo(){
        $data['select'] = "MAX(trans_no) as trans_no";
        $data['tableName'] = $this->transMain;
		$trans_no = $this->specificRow($data)->trans_no;
		$nextPoNo = (!empty($trans_no))?($trans_no + 1):1;
		return $nextPoNo;
    }

    public function getDTRows($data){
        $data['tableName'] = $this->transChild;
        $data['select'] = "trans_child.*,trans_main.trans_no,trans_main.trans_prefix,trans_main.trans_number,trans_main.trans_date,trans_main.party_id,trans_main.net_amount,party_master.party_name,item_category.category_code,item_category.category_name";
        $data['join']['trans_main'] = "trans_main.id = trans_child.trans_main_id";
        $data['leftJoin']['party_master'] = "trans_main.party_id = party_master.id";
        $data['join']['item_category'] = "item_category.id = trans_child.category_id";
        $data['where']['trans_main.sales_type'] = 2;
    
        if(empty($data['status'])){
            $data['where']['trans_child.trans_status'] = 0;
        }else{
            $data['where']['trans_child.trans_status'] = 1;
        }
        
        $data['searchCol'][] = "CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(trans_main.trans_prefix, '/', 1), '/', -1),'/',trans_main.trans_no,'/',SUBSTRING_INDEX(SUBSTRING_INDEX(trans_main.trans_prefix, '/', 2), '/', -1))";
        $data['searchCol'][] = "DATE_FORMAT(trans_main.trans_date, '%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "item_category.category_name";
        $data['searchCol'][] = "trans_child.price";
        $data['searchCol'][] = "trans_child.qty";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "DATE_FORMAT(trans_child.cod_date, '%d-%m-%Y')";

		$columns =array('','','trans_main.trans_no','trans_main.po_date','party_master.party_name','item_category.category_name','trans_child.price','trans_child.qty','','','trans_child.cod_date');
		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
       
		return $this->pagingRows($data);
    }

	public function save($data){

		try{
            $this->db->trans_begin();
			
			if($this->checkDuplicateOrder($data) > 0):
				$errorMessage['trans_no'] = "PO. No. is duplicate.";
				return ['status'=>0,'message'=>$errorMessage];
			endif;

			if(!empty($data['id'])):
                $itemList = $this->getPurchaseOrderItems(['id'=>$data['id']]);
                foreach($itemList as $row):
                    if(!empty($row->request_id)):
                        $setData = Array();
                        $setData['tableName'] = $this->qc_indent;
                        $setData['where']['id'] = $row->request_id;
                        $setData['set']['po_qty'] = 'po_qty, - '.$row->qty;
                        $this->setValue($setData);
    
                        $this->edit($this->qc_indent,['id'=>$row->request_id],['status'=>1]);
                    endif;
                    $this->trash($this->transChild,['id'=>$row->id]);
                endforeach;

                $this->trash($this->transExpense,['trans_main_id'=>$data['id']]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"PO TERMS"]);
                $this->remove($this->transDetails,['main_ref_id'=>$data['id'],'table_name'=>$this->transMain,'description'=>"PO MASTER DETAILS"]);
            endif;

			$masterDetails = $data['masterDetails'];
            $itemData = $data['itemData'];

            $transExp = getExpArrayMap(((!empty($data['expenseData']))?$data['expenseData']:array()));
			$expAmount = $transExp['exp_amount'];
            $termsData = (!empty($data['termsData']))?$data['termsData']:array();

			unset($transExp['exp_amount'],$data['itemData'],$data['expenseData'],$data['termsData'],$data['masterDetails']);		

            $result = $this->store($this->transMain,$data,'Purchase Order');

            $masterDetails['id'] = "";
            $masterDetails['main_ref_id'] = $result['id'];
            $masterDetails['table_name'] = $this->transMain;
            $masterDetails['description'] = "PO MASTER DETAILS";
            $this->store($this->transDetails,$masterDetails);

            $expenseData = array();
            if($expAmount <> 0):				
				$expenseData = $transExp;
                $expenseData['id'] = "";
				$expenseData['trans_main_id'] = $result['id'];
                $this->store($this->transExpense,$expenseData);
			endif;

            if(!empty($termsData)):
                foreach($termsData as $row):
                    $row['id'] = "";
                    $row['table_name'] = $this->transMain;
                    $row['description'] = "PO TERMS";
                    $row['main_ref_id'] = $result['id'];
                    $this->store($this->transDetails,$row);
                endforeach;
            endif;

            foreach($itemData as $row):
                $row['id'] = "";
                $row['entry_type'] = $data['entry_type'];
                $row['trans_main_id'] = $result['id'];
                $row['ref_id'] = $row['request_id'];
                $row['is_delete'] = 0;
                $this->store($this->transChild,$row);
                
                if(!empty($row['request_id'])):
                    $setData = Array();
                    $setData['tableName'] = $this->qc_indent;
                    $setData['where']['id'] = $row['request_id'];
                    $setData['set']['po_qty'] = 'po_qty, + '.$row['qty'];
                    $this->setValue($setData);

                    $reqData = $this->qcIndent->getRequestData(['id'=>$row['request_id']]);
                    if($reqData->qty >= $reqData->po_qty):
                        $this->edit($this->qc_indent,['id'=>$row['request_id']],['status'=>1]);
                    endif;
                endif;
            endforeach;

			if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
		}catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function deleteOrder($id){
        //order transation delete
		$where['trans_main_id'] = $id;
		$this->trash($this->transChild,$where);
        
        //order master delete
		return $this->trash($this->transMain,['id'=>$id],'Purchase Order');
    }

	public function checkDuplicateOrder($data){
        $queryData['tableName'] = $this->transMain;
        $queryData['where']['trans_number'] = $data['trans_number'];
		$queryData['where']['sales_type'] = 2;

        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

	public function getCategoryList(){
        $data['tableName'] = $this->itemCategory;
		$data['where']['final_category'] = 1;
        $data['where_in']['category_type'] = "6,7";
		$result = $this->rows($data);
		return $result;
    }

    public function getPurchaseOrderItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->transChild;
        $queryData['where']['trans_main_id'] = $data['id'];
        $result = $this->rows($queryData);
        return $result;
    }

    public function getPurchaseOrder($id){
		$data['tableName'] = $this->transMain;
		$data['select'] = "trans_main.*,party_master.party_name,party_master.contact_person,party_master.party_email as contact_email,party_master.party_mobile,party_master.gstin,party_master.party_address";
		$data['join']['party_master'] = "trans_main.party_id = party_master.id";
        $data['where']['trans_main.id'] = $id;
        $result = $this->row($data);
		$result->itemList = $this->getPurchaseOrderTransactions($id);

        $queryData = array();
        $queryData['tableName'] = $this->transExpense;
        $queryData['where']['trans_main_id'] = $id;
        $result->expenseData = $this->row($queryData);

        $queryData = array();
        $queryData['tableName'] = $this->transDetails;
        $queryData['select'] = "i_col_1 as term_id,t_col_1 as term_title,t_col_2 as condition";
        $queryData['where']['main_ref_id'] = $id;
        $queryData['where']['table_name'] = $this->transMain;
        $queryData['where']['description'] = "PO TERMS";
        $result->termsConditions = $this->rows($queryData);
		return $result;
	}

	public function getPurchaseOrderTransactions($id){
        $data['tableName'] = $this->transChild;
        $data['select'] = "trans_child.*,item_category.category_name,item_category.category_code";
        $data['leftJoin']['item_category'] = "item_category.id = trans_child.category_id";
        $data['where']['trans_child.trans_main_id'] = $id;
        return $this->rows($data);
    }

    public function purchaseRecive($data){
        try{
            $this->db->trans_begin();
            foreach($data['id'] as $key=>$value):
                if($data['receive_qty'][$key] > 0):
                    $poitem = $this->getPOItems($value);
                    
                    for($i=1; $i<=$data['receive_qty'][$key]; $i++): 
                        $queryData = array();
                		$queryData['tableName'] = 'qc_instruments';
                		$queryData['select'] = "ifnull(MAX(serial_no) + 1,1) as serial_no";
                		$queryData['where']['category_id'] = $poitem->category_id;
                		$serial_no = $this->specificRow($queryData)->serial_no;

            	        $code = $poitem->category_code.sprintf("/%02d",$serial_no);
            	        $name = $code.' '.$poitem->category_name.' '.$poitem->size;

                        $qcInst = [
                            'id'=>NULL,
                            'ref_id'=>$value,
                            'item_code'=>$code,
                            'serial_no'=>$serial_no,
                            'item_name'=>$name,
                            'item_type'=>($poitem->item_group == 7) ? 1 : 2,
                            'category_id'=>$poitem->category_id,
                            'unit_id'=>25,
                            'gst_per'=>$poitem->gst_per,
                            'make_brand'=>$poitem->make,
                            'size'=>$poitem->size,
                            'grn_date'=>date('Y-m-d',strtotime($data['grn_date'])),
                            'in_challan_no'=>$data['in_challan_no']
                        ];
                        $this->store('qc_instruments',$qcInst);
                    endfor;
                    
                    $setData = array();
                    $setData['tableName'] = $this->transChild;
    				$setData['where']['id'] = $value;
    				$setData['set']['receive_qty'] = 'receive_qty, + '.$data['receive_qty'][$key];
    				$qryresult = $this->setValue($setData);
    				
    				$potrans = $this->getPOItems($value); 
    				/** If Po Order Qty is Complete then Close PO **/
    				if($potrans->receive_qty >= $potrans->qty):
    					$this->store($this->transChild,["id"=>$value, "trans_status"=>1]);
    				else:
    					$this->store($this->transChild,["id"=>$value, "trans_status"=>0]);
    				endif;
                endif;
            endforeach;
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return ['status'=>1,'message'=>'Recived Sucessfully.'];
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function getPOItems($id){
		$data['tableName'] = $this->transChild;
        $data['select'] = "trans_child.*,item_category.category_name,item_category.category_code,item_category.ref_id as item_group,  unit_master.unit_name";
        $data['leftJoin']['item_category'] = "item_category.id = trans_child.category_id";
        $data['leftJoin']['unit_master'] = "unit_master.id = trans_child.unit_id";
        $data['where']['trans_child.id'] = $id;
        return $this->row($data);
	}

    public function getQCPRListForPO($id){
        $data['tableName'] = $this->qc_indent;
        $data['select'] = "qc_indent.*,item_category.category_name,item_category.category_code";
        $data['leftJoin']['item_category'] = "item_category.id = qc_indent.category_id";
        $data['where_in']['qc_indent.id'] = str_replace("~", ",", $id);
        $result = $this->rows($data);
        return $result;
    }
}
?>