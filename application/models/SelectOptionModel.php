<?php
class SelectOptionModel extends MasterModel
{     
    private $select_master = "select_master";  

    public function getDTRows($data){
        $data['tableName'] = $this->select_master;
        $data['where']['type'] = $data['type'];
        
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "label";
        $data['searchCol'][] = "remark";

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function getSelectOption($data){
        $queryData['tableName'] = $this->select_master;
        if(!empty($data['id'])){ $queryData['where']['id'] = $data['id']; }
		if(!empty($data['label'])){ $queryData['where']['label'] = $data['label']; }
        return $this->row($queryData);
    }

    public function getSelectOptionList($data){
		
		$queryData['tableName'] = $this->select_master;
        
		if(!empty($data['select'])):
			$queryData['select'] = $data['select'];
		elseif(!empty($data['selectbox'])):
			$queryData['select'] = "select_master.id, select_master.label, select_master.type, select_master.remark, select_master.is_active";
		else:
			$queryData['select'] = "select_master.*";
		endif;
		
        if(!empty($data['id'])){ $queryData['where']['id'] = $data['id']; }
		
        if(!empty($data['is_active'])){ $queryData['where']['is_active'] = $data['is_active']; }
		else{$queryData['where']['is_active'] = 1;}
        
        if(!empty($data['type'])){
            $queryData['where_in']['type'] = $data['type'];
        }else{ 
			$queryData['where']['type <='] = 5;
		}
		
		if(!empty($data['type']) && $data['type'] == 5){
            $queryData['order_by']['label'] = 'ASC';
        }
        
        if(!empty($data['result_type'])):
            $result = $this->getData($queryData,$data['result_type']);
        elseif(!empty($data['id'])):
            $result = $this->getData($queryData,"row");
        else:
            $result = $this->getData($queryData,"rows");
        endif;
		
		return $result;
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->select_master,$data,'Select Option');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Exception $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }	
    }

    public function delete($id){
        try {
            $this->db->trans_begin();

            $result = $this->trash($this->select_master, ['id' => $id], 'Select Option');

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;

        } catch (\Throwable $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }
     
}
?>