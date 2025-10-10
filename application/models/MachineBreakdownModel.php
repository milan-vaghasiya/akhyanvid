<?php
class MachineBreakdownModel extends MasterModel{
    private $machine_breakdown = "machine_breakdown";


    public function getDTRows($data){
        $data['tableName'] = "machine_breakdown";
		$data['select'] = "machine_breakdown.*,prc_master.prc_number,item_master.item_name as machine_name,rejection_comment.code,rejection_comment.remark as idle_reason";
        $data['leftJoin']['prc_master'] = "prc_master.id = machine_breakdown.prc_id";
        $data['leftJoin']['item_master'] = "item_master.id = machine_breakdown.machine_id";
        $data['leftJoin']['rejection_comment'] = "rejection_comment.id = machine_breakdown.idle_reason";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "machine_breakdown.trans_date";
        $data['searchCol'][] = "machine_breakdown.start_time";
        $data['searchCol'][] = "machine_breakdown.end_time";
        $data['searchCol'][] = "prc_master.prc_number";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "rejection_comment.remark";
        $data['searchCol'][] = "machine_breakdown.remark";

		$columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;

		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        return $this->pagingRows($data);
    }

    public function getMachineBreakdown($data){
        $queryData['tableName'] = $this->machine_breakdown;
       
        if(!empty($data['id'])):
            $queryData['where']['machine_breakdown.id'] = $data['id'];
        endif;

        return $this->row($queryData);
    }

    public function save($data){
        try{
            $this->db->trans_begin();
         
            $result = $this->store($this->machine_breakdown,$data,"Machine Breakdown");

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $result = $this->trash($this->machine_breakdown,['id'=>$id],'Machine Breakdown');

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