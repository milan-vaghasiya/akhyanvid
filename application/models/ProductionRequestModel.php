<?php
class ProductionRequestModel extends MasterModel
{
    private $production_request = "production_request";

    public function getNextReqNo(){
        $queryData['tableName'] = $this->production_request;
        $queryData['select'] = "ifnull(MAX(trans_no + 1),1) as next_no";
        $queryData['where']['trans_date >='] = $this->startYearDate;
        $queryData['where']['trans_date <='] = $this->endYearDate;
        return $this->row($queryData)->next_no;
    }

    public function getDTRows($data){
        $data['tableName'] = $this->production_request;
        $data['select'] = "production_request.*,item_master.item_name,unit_master.unit_name";
        $data['leftJoin']['item_master'] = "item_master.id = production_request.item_id";
        $data['leftJoin']['unit_master'] = "unit_master.id = item_master.unit_id";

        $data['where']['production_request.order_status'] = $data['status'];

        $data['where']['production_request.trans_date >='] = $this->startYearDate;
        $data['where']['production_request.trans_date <='] = $this->endYearDate;

        $data['order_by']['production_request.trans_date'] = "DESC";
        $data['order_by']['production_request.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "production_request.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(production_request.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "production_request.qty";
        $data['searchCol'][] = "DATE_FORMAT(production_request.delivery_date,'%d-%m-%Y')";
        $data['searchCol'][] = "production_request.remark";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();
            
            $result = $this->store($this->production_request,$data,'Production Request');
            
            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

	public function getProductionRequest($data){
        $data['tableName'] = $this->production_request;
        
        if(!empty($data['id'])):
            $data['where']['production_request.id'] = $data['id'];
        endif;

        if(!empty($data['item_id'])):
            $data['where']['production_request.item_id'] = $data['item_id'];
        endif;

        if(!empty($data['order_status'])):
            $data['where_in']['production_request.order_status'] = $data['order_status'];
        endif;

        if(!empty($data['multi_row'])){
            return $this->rows($data);
        }else{
            return $this->row($data);
        }
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->production_request,['id'=>$id],'Production Request');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function changeReqStatus($postData){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->production_request,$postData,'Production Request');
            
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