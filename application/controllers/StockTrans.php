<?php
class StockTrans extends MY_Controller{
    private $indexPage = "stock_trans/index";
    private $form = "stock_trans/form";    

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = " Stock Inward";
		$this->data['headData']->controller = "stockTrans";        
        // $this->data['headData']->pageUrl = "stockTrans";
        // $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'stockTrans']);        
		$this->data['entryTypeData'] = $this->transMainModel->getEntryType(['controller'=>'stockTrans/itemInward']);
	}

    public function index(){
        $this->data['tableHeader'] = getStoreDtHeader("stockTrans");
        $this->load->view($this->indexPage,$this->data);
    }
	
	public function stockRegister(){
        $this->data['pageHeader'] = 'STOCK REGISTER';
        $this->data['headData']->pageUrl = "stockTrans/stockRegister";
        $this->load->view("stock_trans/item_stock",$this->data);
    }

    public function getDTRows($item_type = 1){
        $data = $this->input->post();
        $data['trans_type'] = 'OPS';
        $data['item_type']=$item_type;
        $result = $this->itemStock->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getStockTransData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

	public function addStock(){
        $item_type = $this->input->post('item_type');
		$this->data['item_type'] = $item_type;
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>$item_type]);
        $this->load->view($this->form, $this->data);
    }
	
	public function save(){
        $data = $this->input->post();
		$errorMessage = array();		

        if(empty($data['item_id'])){
			$errorMessage['item_id'] = "Item Name is required.";
        }else{
            $itemData = $this->item->getItem(['id'=>$data['item_id']]);
            //Packing Material Then add stock in packing Area
            if($itemData->item_type == 9){
                $data['location_id'] = $this->PACKING_STORE->id;
            }
        }
        if(empty(floatVal($data['qty'])))
			$errorMessage['qty'] = "Qty is required.";
        
      
        if(empty($data['location_id']))
            $errorMessage['location_id'] = "Location is required.";
        
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
           
            $data['location_id'] = $data['location_id'];
            $data['trans_type'] = 'OPS';
            
            $this->printJson($this->itemStock->save($data));
        endif;
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->itemStock->delete($id));
        endif;
    }

	public function itemInward(){
		$this->data['headData']->pageTitle = "Opening Stock";
        $this->data['headData']->pageUrl = "stockTrans/itemInward";
        $this->data['tableHeader'] = getStoreDtHeader("itemInward");
        $this->load->view("stock_trans/inward_index",$this->data);
    }

    public function addItemInward(){
        $data = $this->input->post();
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>['1,2,3,4,9']]);
        $this->data['locationList'] = $this->storeLocation->getStoreLocationList(['final_location'=>1]);
        $this->load->view("stock_trans/inward_form", $this->data);
    }

    public function getItemInwardDTRows(){
        $data = $this->input->post();
        $data['trans_type'] = 'OPS';
        $result = $this->itemStock->getItemInwardDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getItemStockData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }
}
?>