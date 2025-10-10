<?php
class PurchaseIndent extends MY_Controller
{
    private $indexPage = "purchase_indent/index";
    private $purchase_req_index = "purchase_indent/purchase_req_index";
    private $purchase_req_form = "purchase_indent/purchase_req_form";

    public function __construct()
    {
        parent::__construct();
        $this->isLoggedin();
        $this->data['headData']->pageTitle = "Purchase Indent";
        $this->data['headData']->controller = "purchaseIndent";
        $this->data['headData']->pageUrl = "purchaseIndent";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'purchaseIndent/purchaseRequest','tableName'=>'purchase_indent']);
    }

    public function index()
    {
        $this->data['tableHeader'] = getPurchaseDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage, $this->data);
    }

    public function getDTRows($status=1)
    {
        $data = $this->input->post();
        $data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->purchaseIndent->getDTRows($data);
        $sendData = array();
        $i=($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            if ($row->order_status == 1) :
                $row->order_status_label = '<span class="font-10 font-weight-bold badge bg-danger">Pending</span>';
            elseif ($row->order_status == 2) :
                $row->order_status_label = '<span class="font-10 font-weight-bold badge bg-success">Completed</span>';
            elseif ($row->order_status == 3) :
                $row->order_status_label = '<span class="font-10 font-weight-bold badge bg-dark">Rejected</span>';
            endif;
            $sendData[] = getPurchaseIndentData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function changeReqStatus()
    {
        $data = $this->input->post();

        if (empty($data['id'])) :
            $this->printJson(['status' => 0, 'message' => 'Something went wrong...Please try again.']);
        else :
            $this->printJson($this->purchaseIndent->changeReqStatus($data));
        endif;
    }

    public function purchaseRequest()
    {
        $this->data['headData']->pageTitle = "Purchase Request";
        $this->data['tableHeader'] = getPurchaseDtHeader('purchaseRequest');
        $this->load->view($this->purchase_req_index, $this->data);
    }

    public function getPurchaseReqDTRows($status=0)
    {
        $data = $this->input->post();
        $data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->purchaseIndent->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach ($result['data'] as $row) :
            $row->sr_no = $i++;
            if ($row->order_status == 1) :
                $row->order_status_label = '<span class="font-10 font-weight-bold badge bg-danger">Pending</span>';
            elseif ($row->order_status == 2) :
                $row->order_status_label = '<span class="font-10 font-weight-bold badge bg-success">Completed</span>';
            elseif ($row->order_status == 3) :
                $row->order_status_label = '<span class="font-10 font-weight-bold badge bg-dark">Rejected</span>';
            endif;

            $sendData[] = getPurchaseRequestData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addPurchaseRequest()
    {
        $data = $this->input->post();
        if(!empty($data['itemData'])){
            $this->data['reqItems'] = $data['itemData'];
        }
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"2,3,4"]);
        $this->load->view($this->purchase_req_form, $this->data);
    }
	   
	public function save()
    {
        $data = $this->input->post();
        $errorMessage = array();

        if (empty($data['itemData'])){
            $errorMessage['general_error'] = "Item Detail required.";
        }else{
            $i=1;
           foreach($data['itemData'] AS $row){
                if(empty($row['qty'])){
                    $errorMessage['qty_'.$i] = "Item Detail required.";
                }
                $i++;
           }
        }

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;
            $this->printJson($this->purchaseIndent->save($data));
        endif;
    }

    public function edit()
    {
        $data = $this->input->post();
        $indentItems= $this->purchaseIndent->getPurchaseRequestList($data);
        $this->data['dataRow'] = $indentItems[0];
        $this->data['indentItems'] = $indentItems;
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"2,3,4"]);
        $this->load->view($this->purchase_req_form, $this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->purchaseIndent->delete($id));
        endif;
    }

    public function purchaseShortage(){
		$this->data['headData']->pageTitle = "Store Forecast"; 
		$this->data['tableHeader'] = getPurchaseDtHeader('purchaseShortage');
        $this->load->view('purchase_indent/po_shortage',$this->data);
	}

	public function getPurchaseShortageDtRows(){
		$data = $this->input->post();
		$result = $this->sop->getPurchaseShortageDtRows($data);
		$sendData = array();$i=($data['start']+1);
		foreach($result['data'] as $row):          
			$row->sr_no = $i++;         
			$sendData[] = getPurchaseShortageData($row);
		endforeach;
		$result['data'] = $sendData;
		$this->printJson($result);
	}

  
}
