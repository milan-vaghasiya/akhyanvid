<?php
class ItemModel extends MasterModel{
    private $itemMaster = "item_master";
    private $unitMaster = "unit_master";
   
    public function getDTRows($data){
        $data['tableName'] = $this->itemMaster;
        $data['select'] = "item_master.*,item_category.category_name";
        $data['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";

        $data['where']['item_master.item_type'] = $data['item_type'];
        $data['where']['item_master.active'] = 1;

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "item_master.item_code";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "item_category.category_name";
        $data['searchCol'][] = "item_master.uom";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function getItemList($data=array()){
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.*,item_master.id as item_id, item_category.category_name";
        $queryData['leftJoin']['item_category'] = "item_category.id  = item_master.category_id";
        
        if(!empty($data['item_type'])):
            $queryData['where_in']['item_master.item_type'] = $data['item_type'];
        endif;
        
        return $this->rows($queryData);
    }

    public function getItem($data){
        $queryData['tableName'] = $this->itemMaster;
        $queryData['select'] = "item_master.*,item_category.category_name";
        $queryData['leftJoin']['item_category'] = "item_category.id = item_master.category_id";
        
        if(!empty($data['id'])):
            $queryData['where']['item_master.id'] = $data['id'];
        endif;

        if(!empty($data['item_code'])):
            $queryData['where']['item_master.item_code'] = trim($data['item_code']);
        endif;

        if(!empty($data['item_types'])):
            $queryData['where_in']['item_master.item_type'] = $data['item_types'];
        endif;

        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();
		
            if($this->checkDuplicate(['item_name'=>$data['item_name'],'item_type'=>$data['item_type'],'category_id'=>$data['category_id'],'id'=>$data['id']]) > 0):
                $errorMessage['item_name'] = "Item Name is duplicate.";
                return ['status'=>0,'message'=>$errorMessage];
            endif;
            
            $result = $this->store($this->itemMaster,$data,"Item");            

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
		if(!empty($data['category_id']))
            $queryData['where']['category_id'] = $data['category_id'];
        
        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $checkData['columnName'] = ["item_id","ref_item_id","product_id"];
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


}
?>