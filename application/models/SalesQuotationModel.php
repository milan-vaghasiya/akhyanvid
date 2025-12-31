<?php
class SalesQuotationModel extends MasterModel{
    private $sqMaster = "sq_master";
    private $sqTrans = "sq_trans";
    private $transExpense = "trans_expense";
    private $transDetails = "trans_details";

    public function getNextSQNo(){
		$queryData = array(); 
		$queryData['tableName'] = 'sq_master';
        $queryData['select'] = "MAX(trans_no) as trans_no ";
		$queryData['where']['sq_master.trans_date >='] = $this->startYearDate;
		$queryData['where']['sq_master.trans_date <='] = $this->endYearDate;
		$trans_no = $this->specificRow($queryData)->trans_no;
		$nextTransNo = (!empty($trans_no))?($trans_no + 1):1;        
		return $nextTransNo;
    }

	public function getDTRows($data){
        $data['tableName'] = $this->sqMaster;
        $data['select'] = 'sq_master.*,item_master.item_name,party_master.party_name,(CASE WHEN sq_master.project_type = 1 THEN "Automation" WHEN sq_master.project_type = 2 THEN "Theater"  ELSE "" END) as project_type';

        $data['leftJoin']['item_master'] = "item_master.id = sq_master.item_id";
        $data['leftJoin']['party_master'] = "party_master.id = sq_master.party_id";
		
        if(!empty($data['trans_status'])):
            $data['where']['sq_master.trans_status'] = $data['trans_status'];
		endif;

        $data['group_by'][] = 'sq_master.trans_number';
        $data['order_by']['sq_master.trans_date'] = "DESC";
        $data['order_by']['sq_master.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "sq_master.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(sq_master.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "(CASE WHEN sq_master.project_type = 1 THEN 'Automation' WHEN sq_master.project_type = 2 THEN 'Theater'  ELSE ''' END)";
        $data['searchCol'][] = "sq_master.description";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }
	
    public function save($data){ 
        try{
            $this->db->trans_begin();

            if(!empty($data['id'])):
                $dataRow = $this->getSalesQuotation(['trans_number'=>$data['trans_number']]);
                foreach($dataRow as $row):
                    $this->trash($this->sqMaster,['id'=>$row->id]);
                endforeach;
            endif;

            foreach ($data['itemData'] as $item) {
                $sqData = [
                    'id' => (!empty($item['id']) ? $item['id'] : ''),
                    'trans_number' => $data['trans_number'],
                    'trans_no' => $data['trans_no'],
                    'trans_date' => $data['trans_date'],
                    'party_id' => $data['party_id'],
                    'description' => $data['description'],
                    'item_id' => $item['item_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'amount' => $item['amount'],
                    'conditions' => $data['conditions'],
                    'quot_option' => $item['quot_option'], 
                    'is_delete' => 0,
                ];
                $result = $this->store($this->sqMaster, $sqData, 'Sales Quotation');
            }
           
            // Save Party Activity Log Record 
            if(empty($data['id'])):
                $this->party->savePartyActivity(['party_id'=>$data['party_id'],'lead_stage'=>6,'ref_date'=>$data['trans_date']." ".date("H:i:s"),'ref_no'=>$data['trans_number']]);
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

    public function getSalesQuotation($data){
        $queryData = array();
        $queryData['tableName'] = $this->sqMaster;
		$queryData['select'] = "sq_master.*,employee_master.emp_name as created_name,party_master.party_type,party_master.party_name,party_master.party_code,item_master.item_name,item_master.item_code,item_master.make_brand,item_master.item_class,item_category.category_name,item_master.gst_per";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = sq_master.created_by";
        $queryData['leftJoin']['party_master'] = "party_master.id = sq_master.party_id";
        $queryData['leftJoin']['item_master'] = "item_master.id = sq_master.item_id";
        $queryData['leftJoin']['item_category'] = "item_master.category_id = item_category.id";
        if(!empty($data['id'])){
            $queryData['where']['sq_master.id'] = $data['id'];
        }
        if(!empty($data['trans_number'])){
            $queryData['where']['sq_master.trans_number'] = $data['trans_number'];
        }
        if(!empty($data['project_id'])){
            $queryData['where']['sq_master.project_id'] = $data['project_id'];
        }
        if(!empty($data['item_id'])){
            $queryData['where']['sq_master.item_id'] = $data['item_id'];
        }
        if(!empty($data['group_by'])){
            $queryData['group_by'][] = $data['group_by'];
        }
		
		$queryData['order_by']['item_master.item_class'] = 'ASC';
		
        if(!empty($data['single_row'])):
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

    public function delete($data){
        try{
            $this->db->trans_begin();

            $this->trash('party_activities',['ref_no'=>$data['trans_number'],'lead_stage'=>6]); 
            $result = $this->trash($this->sqMaster,['trans_number'=>$data['trans_number']],'Sales Quotation');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function changeQuotationStatus($data) { 
        try{
            $this->db->trans_begin();

            $this->edit($this->sqMaster, ['trans_number'=> $data['trans_number']], ['trans_status' => $data['trans_status']]);
            $result = ['status' => 1, 'message' => 'Sales Quotation '.$data['msg'].' Successfully.'];

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