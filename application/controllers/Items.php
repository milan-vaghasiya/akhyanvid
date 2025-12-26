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
        
       if(empty($data['item_code']) || empty($data['category_id']) || empty($data['make_brand']))
            $errorMessage['item_name'] = "Item Name is required.";

        if(empty($data['uom']))
            $errorMessage['uom'] = "Unit is required.";
            
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else: 
            $data['item_name'] = (!empty($data['item_code']) ? $data['item_code'] : '');
            $data['item_name'] .= (!empty($data['category_name']) ? ' '.$data['category_name'] : '');
            $data['item_name'] .= (!empty($data['make_brand']) ? ' '.$data['make_brand'] : '');
            $data['item_name'] = strtoupper($data['item_name']);

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

	 /* Update Price */ 
    public function updatePrice(){
        $data = $this->input->post();
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['final_category'=>1]);
        $this->load->view('item_master/update_price',$this->data);
    }

	public function updatePriceData(){
        $data = $this->input->post();
        $itemList = $this->item->getItemList(['category_id'=>$data['category_id']]); 
        if(!empty($itemList)){
            $i = 1;$tbody ='';
            foreach($itemList as $row) {
                $tbody .= '<tr>
                        <td>'.$i.'</td>
                        <td>'.$row->item_name.'</td>
                        <td>
                            <select name="itemData['.$i.'][gst_per]" id="gst_per_' . $i . '" class="form-control">';
                            foreach($this->gstPer as $per=>$text):
                                $selected = (!empty($row->gst_per) && floatVal($row->gst_per) == $per)?"selected":"";
                                $tbody .= '<option value="'.$per.'" '.$selected.' data-gst_per = '.$text.'>'.$text.'</option>';
                            endforeach;
                        $tbody .= '</select>
                            <div class="error gst_per_'.$i .'"></div> 
                            <input type="hidden" name="itemData['.$i.'][id]" id="id_' . $i . '" value="'.$row->id.'" >
                        </td> 
                        <td>
                            <input type="text" name="itemData['.$i.'][price]" id="price_' . $i . '" class="form-control floatOnly calculateMRP" value="'.round($row->price).'"  data-price = '.$row->price.'/>
                            <div class="error price_'.$i .'"></div>
                        </td>
                        <td>
                            <input type="text" name="itemData['.$i.'][inc_price]" id="inc_price_' . $i . '" class="form-control floatOnly calculateMRP" value="'.round($row->inc_price).'"  data-inc_price = '.$row->inc_price.'/>
                            <div class="error inc_price_'.$i .'"></div>
                        </td>
                    </tr>';
                $i++;
                
            }
        }
        $this->printJson(['status'=>1,'tbody'=>$tbody]);
    }
	
    public function saveUpdatePrice(){
        $data = $this->input->post();
        $errorMessage = array(); 

        if(empty($data['category_id'])) {  $errorMessage['category_id'] = "Category is required";}

        if (!empty($errorMessage)) :
            $this->printJson(['status' => 0, 'message' => $errorMessage]);
        else :
			$this->printJson($this->item->saveUpdatePrice($data));
        endif;
    }

}
?>