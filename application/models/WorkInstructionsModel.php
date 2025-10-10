
<?php
class WorkInstructionsModel extends MasterModel{
    private $workInstructions = "work_instructions";
	
    public function getDTRows($data){
        $data['tableName'] = $this->workInstructions;
        $data['where']['work_type'] = $data['work_type'];

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "work_title";
        $data['searchCol'][] = "description";
        $data['searchCol'][] = "notes";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getWorkInstructions($data=[]){
        $queryData['tableName'] = $this->workInstructions;

        if(!empty($data['id'])){$queryData['where']['id'] = $data['id'];}
        if(!empty($data['work_type'])){$queryData['where']['work_type'] = $data['work_type'];}
        if(!empty($data['work_id'])){
            $queryData['where_in']['id'] = $data['work_id'];
        }

        if(!empty($data['single_row'])):
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;                                                                                                          
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            $result = $this->store($this->workInstructions,$data,'Work Instructions');

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