<?php
class ProductionRequest extends MY_Controller
{
    private $indexPage = "production_request/index";
    private $formPage = "production_request/form";
    private $prc_index = "production_request/prc_index";
    
	public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Production Request";
		$this->data['headData']->controller = "productionRequest";
        $this->data['headData']->pageUrl = "productionRequest";		
	}
	
	public function index(){
        $this->data['tableHeader'] = getSalesDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }
	
    public function getDTRows($status=1){
        $data = $this->input->post(); $data['status'] = $status;
        $result = $this->productionRequest->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;
            $row->type = 1;             
            if ($row->order_status == 1) :
                $row->order_status_label = '<span class="font-10 font-weight-bold badge bg-danger">Pending</span>';
            elseif ($row->order_status == 2) :
                $row->order_status_label = '<span class="font-10 font-weight-bold badge bg-success">Completed</span>';
            elseif ($row->order_status == 3) :
                $row->order_status_label = '<span class="font-10 font-weight-bold badge bg-dark">Rejected</span>';
            endif;
            $sendData[] = getProductionRequestData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addProductionRequest(){
        $data = $this->input->post();
        $this->data['trans_prefix'] = 'REQ/'.getShortFY().'/';
        $this->data['trans_no'] = $this->productionRequest->getNextReqNo();;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->load->view($this->formPage, $this->data);
    }
	   
	public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if (empty($data['item_id']))
            $errorMessage['item_id'] = "Item Name is required.";
        if (empty($data['qty']))
            $errorMessage['qty'] = "Qty. is required.";

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $this->printJson($this->productionRequest->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $this->productionRequest->getProductionRequest($data);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->load->view($this->formPage, $this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->productionRequest->delete($id));
        endif;
    }

    public function changeReqStatus(){
        $data = $this->input->post();
        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Something went wrong...Please try again.']);
        else :
            $this->printJson($this->productionRequest->changeReqStatus($data));
        endif;
    }
	
	public function prcRequest(){
        $this->data['tableHeader'] = getSalesDtHeader($this->data['headData']->controller);
        $this->load->view($this->prc_index,$this->data);
    }
	
    public function getPrcDTRows($status=1){
        $data = $this->input->post(); $data['status'] = $status;
        $result = $this->productionRequest->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++; 
            $row->type = 2; 
            if ($row->order_status == 1) :
                $row->order_status_label = '<span class="font-10 font-weight-bold badge bg-danger">Pending</span>';
            elseif ($row->order_status == 2) :
                $row->order_status_label = '<span class="font-10 font-weight-bold badge bg-success">Completed</span>';
            endif;
            $sendData[] = getProductionRequestData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function createPRC(){
        $data = $this->input->post(); 
        $reqData = $this->productionRequest->getProductionRequest(['id'=>$data['id']]);
        $masterData = [
            'id'=>'',
            'prc_date'=>date("Y-m-d"),
            'party_id'=>0,
            'item_id'=>$reqData->item_id,
            'so_trans_id'=>0,
            'prc_qty'=>$reqData->qty,
            'target_date'=>$reqData->delivery_date,
            'req_id'=>$data['id'],
        ];
        $prcDetail = [ 
            'remark'=>"",
            'id'=>"",
        ];
        $masterData['created_by'] = $this->session->userdata('loginId');
        $prcDetail['created_by'] = $this->session->userdata('loginId');
        $sendData['masterData'] = $masterData;
        $sendData['prcDetail'] = $prcDetail;
        $this->printJson($this->sop->savePRC($sendData));
    }
}
?>