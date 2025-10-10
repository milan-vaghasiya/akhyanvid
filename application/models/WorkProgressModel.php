<?php
class WorkProgressModel extends MasterModel{
    private $work_progress = "work_progress";


 	public function getDTRows($data){
        $data['tableName'] = $this->work_progress;
        $data['select'] = "work_progress.*,project_info.project_name";
        $data['leftJoin']['project_info'] = "project_info.id = work_progress.project_id";

        $data['order_by']['work_progress.id'] = "DESC";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "project_info.project_name";
        $data['searchCol'][] = "work_progress.step_no";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";


        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function save($data){   
        try{
            $this->db->trans_begin();

            foreach($data['id'] as $key=>$value){ 
             
                $currentWorkId = $data['all_work_id'][$key]; 
                $status = in_array($currentWorkId, $data['work_id']) ? 1 : 0;
                $this->edit($this->work_progress, ['id'=>$value],['status'=> $status]); 
            }

            $logData = [
                        'id'=>'',
                        'project_id'=>$data['project_id'],
                        'work_id'=>implode(",",$data['work_id']),
                        'step_no'=>$data['step_no'],
                        'notes'=> $data['notes'],
                    ];

            $result = $this->store('work_logs', $logData, 'work progress');
            

            if ($this->db->trans_status() !== FALSE):
                $this->db->trans_commit();
                return $result;
            endif;
        }catch(\Throwable $e){
            $this->db->trans_rollback();
            return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
        }
    }

		
    public function getWorkProgressData($data){
        $queryData = array();
        $queryData['tableName'] = $this->work_progress;
        $queryData['select'] = "work_progress.*,project_info.project_name,work_instructions.description,work_instructions.work_title";
        $queryData['leftJoin']['project_info'] = "project_info.id = work_progress.project_id";
        $queryData['leftJoin']['work_instructions'] = "work_instructions.id = work_progress.work_id";

        if(!empty($data['id'])){
            $queryData['where']['work_progress.id'] = $data['id'];
        }
        if(!empty($data['project_id'])){
            $queryData['where']['work_progress.project_id'] = $data['project_id'];
        }

        if(!empty($data['step_no'])){
            $queryData['where']['work_progress.step_no'] = $data['step_no'];
        }
        
        if(!empty($data['single_row'])):
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

    // public function delete($id){
    //     try{
    //         $this->db->trans_begin();

    //          $this->edit($this->work_progress,['project_id'=>$id,'status'=> 1],['status'=>0]);
    //         $result = $this->trash('work_logs',['project_id'=>$id],'Work Progress');

    //         if ($this->db->trans_status() !== FALSE):
    //             $this->db->trans_commit();
    //             return $result;
    //         endif;
    //     }catch(\Throwable $e){
    //         $this->db->trans_rollback();
    //         return ['status'=>2,'message'=>"somthing is wrong. Error : ".$e->getMessage()];
    //     }
    // }


    // Work Logs Data 
     public function getWorkLogsData($data){
        $queryData = array();
        $queryData['tableName'] = 'work_logs';
        $queryData['select'] = "work_logs.*,employee_master.emp_name as created_name,
                            (SELECT COUNT(*) FROM work_progress 
                                WHERE work_progress.project_id = work_logs.project_id 
                             AND work_progress.step_no = work_logs.step_no) as totalWork";
        $queryData['leftJoin']['employee_master'] = "employee_master.id = work_logs.created_by";

        if(!empty($data['id'])){
            $queryData['where']['work_logs.id'] = $data['id'];
        }
        if(!empty($data['project_id'])){
            $queryData['where']['work_logs.project_id'] = $data['project_id'];
        }

        if(!empty($data['step_no'])){
            $queryData['where']['work_logs.step_no'] = $data['step_no'];
        }
        
        if(!empty($data['single_row'])):
            $result = $this->row($queryData);
        else:
            $result = $this->rows($queryData);
        endif;
        return $result;
    }

	
}
?>