<?php
class InstrumentModel extends MasterModel{
    private $itemMaster = "qc_instruments";
    private $stockTrans = "stock_trans";
    private $qc_indent = "qc_indent";
    private $calibration = 'calibration';
    private $qcChallan = "qc_challan";
    private $qcChallanTrans = "qc_challan_trans";

	public function getDTRows($data){
        $data['tableName'] = $this->itemMaster;
        $data['select'] = "qc_instruments.*, CONCAT('[',item_category.category_name,'] ',item_category.category_name) as category_name,location_master.location";
        $data['leftJoin']['item_category'] = "item_category.id = qc_instruments.category_id";
        $data['leftJoin']['location_master'] = "location_master.id = qc_instruments.location_id";
        $data['where']['qc_instruments.item_type'] = $data['item_type'];
        
        if(empty($data['status'])){$data['status'] = 0;}
        
        if($data['status'] != 5){ 
            $data['where']['qc_instruments.status'] = $data['status']; 
        }else{
            $data['where_in']['qc_instruments.status'] = "1,2";
            $data['customWhere'][] = "DATE_SUB(qc_instruments.next_cal_date, INTERVAL qc_instruments.cal_reminder DAY) <= '".date('Y-m-d')."'";
        }
        
        $columns = Array();
        if($data['status'] != 4){
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            if($data['status'] != 0){
                $data['searchCol'][] = "";
            }
            $data['searchCol'][] = "qc_instruments.item_code";
            $data['searchCol'][] = "qc_instruments.item_name";
            $data['searchCol'][] = "qc_instruments.make_brand";
            $data['searchCol'][] = "qc_instruments.cal_required";
            $data['searchCol'][] = "qc_instruments.cal_freq";
            $data['searchCol'][] = "qc_instruments.location";
            $data['searchCol'][] = "qc_instruments.last_cal_date";
            $data['searchCol'][] = "qc_instruments.next_cal_date";
            $data['searchCol'][] = "(qc_instruments.next_cal_date - (qc_instruments.cal_reminder+1))";
            $data['searchCol'][] = "qc_instruments.created_at";

            if($data['status'] != 0){
                $columns =array('','','','qc_instruments.item_code','qc_instruments.item_name','qc_instruments.make_brand','qc_instruments.cal_required','qc_instruments.cal_freq','qc_instruments.location','qc_instruments.last_cal_date','qc_instruments.next_cal_date','','qc_instruments.created_at');
            } else {
                $columns =array('','','qc_instruments.item_code','qc_instruments.item_name','qc_instruments.make_brand','qc_instruments.cal_required','qc_instruments.cal_freq','qc_instruments.location','qc_instruments.last_cal_date','qc_instruments.next_cal_date','','qc_instruments.created_at');
            }
        }else{
            
            $data['searchCol'][] = "";
            $data['searchCol'][] = "";
            $data['searchCol'][] = "qc_instruments.item_code";
            $data['searchCol'][] = "qc_instruments.item_name";
            $data['searchCol'][] = "qc_instruments.make_brand";
            $data['searchCol'][] = "qc_instruments.cal_required";
            $data['searchCol'][] = "qc_instruments.cal_freq";
            $data['searchCol'][] = "location_master.location";
            $data['searchCol'][] = "DATE_FORMAT(qc_instruments.rejected_at,'%d-%m-%Y')";
            $data['searchCol'][] = "qc_instruments.reject_reason";

            $columns =array('','','qc_instruments.item_code','qc_instruments.item_name','qc_instruments.make_brand','qc_instruments.cal_required','qc_instruments.cal_freq','location_master.location','qc_instruments.rejected_at','qc_instruments.reject_reason');
        }

		if(isset($data['order'])){
		    $data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];
		}else{
            $data['order_by']['qc_instruments.category_id'] = 'ASC';
            $data['order_by']['qc_instruments.serial_no'] = 'ASC';
		}
        $result = $this->pagingRows($data);
        return $result;
    }
    
    public function getSerialWiseDTRows($data){
        $data['tableName'] = $this->itemMaster;
        $data['select'] = "qc_instruments.*,calibration.cal_by,calibration.cal_date,calibration.next_cal_date";
        $data['leftJoin']['calibration'] = "qc_instruments.item_id = calibration.item_id AND qc_instruments.item_code = calibration.batch_no AND calibration.is_active = 1 AND calibration.is_delete = 0";
        $data['where']['qc_instruments.item_type'] = 1;
        $data['where']['qc_instruments.is_delete'] = 0;
        if(!empty($data['status'])){ $data['customWhere'][] = "(calibration.next_cal_date <= '".date('Y-m-d')."' OR calibration.next_cal_date IS NULL OR calibration.next_cal_date = '')"; }
        
        $data['searchCol'][] = "";
        $data['searchCol'][] = "";
        $data['searchCol'][] = "qc_instruments.item_code";
        $data['searchCol'][] = "qc_instruments.item_name";
        $data['serachCol'][] = "qc_instruments.make_brand";
        $data['serachCol'][] = "qc_instruments.mfg_sr";

		$columns =array('','','qc_instruments.item_code','qc_instruments.item_name','qc_instruments.make_brand','qc_instruments.mfg_sr');
		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getItem($id){
        $data['tableName'] = $this->itemMaster;
        $data['select'] = 'qc_instruments.*,item_category.category_name,item_category.category_code as cat_code';
        $data['leftJoin']['item_category'] = 'item_category.id = qc_instruments.category_id';
        $data['where']['qc_instruments.id'] = $id;
        return $this->row($data);
    }

    public function save($data){
        $chkDuplicate = array();
        $msg = 'Instrument';
        $chkDuplicate=[
            'id'=>$data['id'],
            'size'=>$data['size'],
            'category_id'=>$data['category_id'],
            'item_type'=>$data['item_type'],
        ];
        
        if(empty($data['id'])){
            $queryData = array();
    		$queryData['tableName'] = $this->itemMaster;
    		$queryData['select'] = "ifnull(MAX(serial_no) + 1,1) as serial_no";
    		$queryData['where']['category_id'] = $data['category_id'];
    		$serial_no = $this->specificRow($queryData)->serial_no;
    		
    		$data['serial_no'] = $serial_no;
            $data['item_code'] = $data['cat_code'].sprintf("/%02d",$serial_no);
            $data['item_name'] = $data['item_code'].' '.$data['cat_name'].' '.$data['size'];
        }else{
            $data['item_name'] = $data['item_code'].' '.$data['cat_name'].' '.$data['size'];
        }        
        unset($data['cat_name'],$data['cat_code']);

        return $this->store($this->itemMaster,$data);
	}

    public function checkDuplicate($postData){
        $data['tableName'] = $this->itemMaster;
        if(!empty($postData['item_type'])){$data['where']['item_type'] = $postData['item_type'];}
        if(!empty($postData['category_id'])){$data['where']['category_id'] = $postData['category_id'];}
        if(!empty($postData['size'])){$data['where']['size'] = $postData['size'];}
        if(!empty($postData['id']))
            $data['where']['id !='] = $postData['id'];

        return $this->numRows($data);
    }

    public function delete($id){
		$itemData = $this->getItem($id);
        return $this->trash($this->itemMaster,['id'=>$id]);
    }

    public function saveRejectGauge($data){
        return $this->edit($this->itemMaster,['id'=>$data['id']],['status'=>4,'reject_reason'=>$data['reject_reason'],'rejected_at'=>date('Y-m-d H:i:s'),'rejected_by'=>$this->loginId]);
    }
    
    public function getActiveInstruments(){
        $data['tableName'] = $this->itemMaster;
        $data['where']['status'] = 1;
        return $this->rows($data);
    }

    public function getIssueHistoryData($item_id){
        $data['tableName'] = 'qc_challan_trans';
        $data['select'] = 'qc_challan_trans.*,qc_challan.trans_number,qc_challan.challan_type,qc_challan.trans_date,qc_challan.party_id,(CASE WHEN qc_challan.challan_type != 1 THEN party_master.party_name ELSE department_master.name END) as party_name';
        $data['leftJoin']['qc_challan'] = "qc_challan.id = qc_challan_trans.challan_id";
        $data['leftJoin']['party_master'] = "party_master.id = qc_challan.party_id";
        $data['leftJoin']['department_master'] = "department_master.id = qc_challan.party_id";
        $data['where']['qc_challan.challan_type !='] = '3';
        $data['where']['qc_challan_trans.item_id'] = $item_id;
          
		return $this->rows($data);
    }
    
    public function getCalibrationData($data){
        $data['tableName'] = $this->calibration;
        $data['where']['item_id'] = $data['item_id'];
        $data['searchCol'][] = "calibration.cal_agency_name";
        $data['serachCol'][] = "calibration.cal_certi_no";
        $data['serachCol'][] = "calibration.remark";
		$columns =array('','','cal_agency_name','','cal_certi_no','remark');
		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function saveCalibrationData($data){ 
        return $this->store($this->calibration,$data,'Calibration');
    }

    public function getCalibration($id){
        $data['tableName'] = $this->calibration;
        $data['select'] = "calibration.*,item_master.item_code,item_master.item_name";
        $data['leftJoin']['item_master'] = "item_master.id = calibration.item_id";
        $data['where']['calibration.id'] = $id;
        return $this->row($data);
    }
}