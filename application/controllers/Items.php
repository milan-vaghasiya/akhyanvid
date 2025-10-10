<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Items extends MY_Controller{
    private $indexPage = "item_master/index";
    private $form = "item_master/form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Item Master";
		$this->data['headData']->controller = "items";        
	}

    public function list($item_type = 0){
        $this->data['headData']->pageUrl = "items/list/".$item_type;
        $this->data['item_type'] = $item_type;
        $headerName = str_replace(" ","_",strtolower($this->itemTypes[$item_type]));
        $this->data['tableHeader'] = getMasterDtHeader($headerName);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($item_type = 0){
        $data = $this->input->post();$data['item_type'] = $item_type;
        $result = $this->item->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $row->item_type_text = $this->itemTypes[$row->item_type];
            $sendData[] = getProductData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addItem(){
        $data = $this->input->post();
        $this->data['item_type'] = $data['item_type'];
        $this->data['unitData'] = $this->item->itemUnits();
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['category_type'=>$data['item_type'],'final_category'=>1]);
        $this->load->view($this->form,$this->data);
    }
	
    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        
        if(empty($data['item_name']))
            $errorMessage['item_name'] = "Item Name is required.";

        if(empty($data['uom']))
            $errorMessage['uom'] = "Unit is required.";
        if(empty($data['category_id']))
            $errorMessage['category_id'] = "Category is required.";
            
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['item_name'] = strtoupper($data['item_name']);
			if($data['item_type'] == 1)
			{
				$gst_per = $data['gst_per'];
				$price = $data['price'];
				$price_tax_status = $data['price_tax_status'];
				
				// Exclusive of tax
				if ($price_tax_status == 0) {					
					$gst_amount = ($price * $gst_per) / 100;
					$inc_price = $price + $gst_amount;
				}
				else { // Inclusive of tax
					$price_ex_gst = $price / (1 + ($gst_per / 100));
					$inc_price = $price;
					$price = $price_ex_gst;
				}
				
				$data['price'] = round($price,2);
				$data['inc_price'] = round($inc_price,2);
			}
            $this->printJson($this->item->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $itemDetail = $this->item->getItem($data);
        $this->data['unitData'] = $this->item->itemUnits();
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['category_type'=>$itemDetail->item_type,'final_category'=>1]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->item->delete($id));
        endif;
    }

    public function getItemList(){
        $data = $this->input->post();
        $itemList = $this->item->getItemList($data);
        $this->printJson(['status'=>1,'data'=>['itemList'=>$itemList]]);
    }

    public function getItemDetails(){
        $data = $this->input->post();
        $itemDetail = $this->item->getItem($data);
        $this->printJson(['status'=>1,'data'=>['itemDetail'=>$itemDetail]]);
    }
}
?>