<?php
class SalesEnquiryModel extends MasterModel{
    private $seMaster = "se_master";
    private $seTrans = "se_trans";

    public function getDTRows($data){
        $data['tableName'] = $this->seTrans;
        $data['select'] = "se_trans.id,item_master.item_name,se_trans.qty,se_master.id as trans_main_id,se_master.trans_number,DATE_FORMAT(se_master.trans_date,'%d-%m-%Y') as trans_date,party_master.party_name,se_trans.trans_status,se_master.party_id,party_master.sales_executive,item_master.uom";

        $data['leftJoin']['se_master'] = "se_master.id = se_trans.trans_main_id";
        $data['leftJoin']['party_master'] = "party_master.id = se_master.party_id";
        $data['leftJoin']['item_master'] = "item_master.id = se_trans.item_id";

        $data['where']['se_trans.entry_type'] = $data['entry_type'];

        if($data['status'] == 0):
            $data['where']['se_trans.trans_status'] = 0;
        elseif($data['status'] == 1):
            $data['where']['se_trans.trans_status'] = 1;
        endif;

        $data['where']['se_master.trans_date >='] = $this->startYearDate;
        $data['where']['se_master.trans_date <='] = $this->endYearDate;

        $data['order_by']['se_master.trans_date'] = "DESC";
        $data['order_by']['se_master.id'] = "DESC";

        $data['group_by'][] = "se_trans.id";

        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "se_master.trans_number";
        $data['searchCol'][] = "DATE_FORMAT(se_master.trans_date,'%d-%m-%Y')";
        $data['searchCol'][] = "party_master.party_name";
        $data['searchCol'][] = "item_master.item_name";
        $data['searchCol'][] = "CONCAT(se_trans.qty,' ',item_master.uom)"; 

        $columns =array(); foreach($data['searchCol'] as $row): $columns[] = $row; endforeach;
        if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
        
        return $this->pagingRows($data);
    }

    public function save($data){
        try{
            $this->db->trans_begin();

            if($this->checkDuplicate($data) > 0):
                $errorMessage['trans_number'] = "SE. No. is duplicate.";
                $result = ['status'=>0,'message'=>$errorMessage];
            endif;

            if(!empty($data['id'])):
                $this->trash($this->seTrans,['trans_main_id'=>$data['id']]);
            endif;
            
            $itemData = $data['itemData']; unset($data['itemData']);		

            $result = $this->store($this->seMaster,$data,'Sales Enquiry');

            foreach($itemData as $row):
                $row['entry_type'] = $data['entry_type'];
                $row['trans_main_id'] = $result['id'];
                $row['is_delete'] = 0;
                $this->store($this->seTrans,$row);
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

    public function checkDuplicate($data){
        $queryData['tableName'] = $this->seMaster;
        $queryData['where']['trans_number'] = $data['trans_number'];
        $queryData['where']['entry_type'] = $data['entry_type'];

        if(!empty($data['id']))
            $queryData['where']['id !='] = $data['id'];

        $queryData['resultType'] = "numRows";
        return $this->specificRow($queryData);
    }

    public function getSalesEnquiry($data){
        $queryData = array();
        $queryData['tableName'] = $this->seMaster;
        $queryData['select'] = "se_master.*";
        $queryData['where']['se_master.id'] = $data['id'];
        $result = $this->row($queryData);

        if($data['itemList'] == 1):
            $result->itemList = $this->getSalesEnquiryItems($data);
        endif;
        return $result;
    }

    public function getSalesEnquiryItems($data){
        $queryData = array();
        $queryData['tableName'] = $this->seTrans;
        $queryData['select'] = "se_trans.*,item_master.item_name,item_master.item_code,item_master.price,item_master.gst_per,item_master.hsn_code,unit_master.unit_name,item_master.defualt_disc";
        $queryData['leftJoin']['item_master'] = "item_master.id = se_trans.item_id";
        $queryData['leftJoin']['unit_master'] = "unit_master.id = item_master.unit_id";
        $queryData['where']['se_trans.trans_main_id'] = $data['id'];
        $result = $this->rows($queryData);
        return $result;
    }

    public function getSalesEnquiryItem($data){
        $queryData = array();
        $queryData['tableName'] = $this->seTrans;
        $queryData['where']['id'] = $data['id'];
        $result = $this->row($queryData);
        return $result;
    }

    public function delete($id){
        try{
            $this->db->trans_begin();

            $this->trash($this->seTrans,['trans_main_id'=>$id]);
            $result = $this->trash($this->seMaster,['id'=>$id],'Sales Enquiry');

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