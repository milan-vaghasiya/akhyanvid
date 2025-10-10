<?php
class QCIndentModel extends MasterModel
{
    private $qc_indent = "qc_indent";

    public function getDTRows($data) {
        $data['tableName'] = $this->qc_indent;
        $data['select'] = "qc_indent.*,DATE_FORMAT(qc_indent.created_at,'%d-%m-%Y') as req_date,CONCAT('[',item_category.category_code,'] ',item_category.category_name) as category_name,employee_master.emp_name as rejected_by";
        $data['leftJoin']['item_category'] = "item_category.id = qc_indent.category_id";
        $data['leftJoin']['employee_master'] = "employee_master.id = qc_indent.rejected_by";
        
        // Set Status to 0 (Pending) if Empty
        if(empty($data['status'])){
            $data['status'] = 0;
        }
        $data['where']['qc_indent.status'] = $data['status'];
        
		$data['searchCol'][] = "";
		$data['searchCol'][] = "";
		$data['searchCol'][] = "";
        $data['searchCol'][] = "DATE_FORMAT(qc_indent.created_at,'%d-%m-%Y')";
		$data['searchCol'][] = "qc_indent.req_number";
		$data['searchCol'][] = "CONCAT('[',item_category.category_name,'] ',item_category.category_name)";
        $data['searchCol'][] = "qc_indent.qty";
        $data['searchCol'][] = "qc_indent.size";
        $data['searchCol'][] = "qc_indent.make";
        $data['searchCol'][] = "DATE_FORMAT(qc_indent.delivery_date,'%d-%m-%Y')";
        
		$columns =array('','','',"DATE_FORMAT(qc_indent.created_at,'%d-%m-%Y')","qc_indent.req_number",'item_category.category_name','qc_indent.size','qc_indent.make','qc_indent.delivery_date');
		if(isset($data['order'])){$data['order_by'][$columns[$data['order'][0]['column']]] = $data['order'][0]['dir'];}
		return $this->pagingRows($data);
    }

    public function getRequestData($id) {
        $data['tableName'] = $this->qc_indent;
        $data['where_in']['qc_indent.id'] = $id;
        return $this->row($data);
    }

    public function getPurchaseRequestForOrder($id){
        $data['tableName'] = $this->qc_indent;
        $data['select'] = "qc_indent.*,item_master.full_name,item_master.item_name,item_master.item_code,item_master.gst_per,item_master.price,item_master.unit_id,item_master.hsn_code, unit_master.unit_name";
        $data['leftJoin']['item_master'] = "item_master.id = qc_indent.req_item_id";
        $data['leftJoin']['unit_master'] = "unit_master.id = item_master.unit_id";
        $data['where_in']['qc_indent.id'] = str_replace("~", ",", $id);
        return $this->rows($data);
    }
}