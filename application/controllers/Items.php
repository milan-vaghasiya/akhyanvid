<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Items extends MY_Controller{
    private $indexPage = "item_master/index";
    private $form = "item_master/form";
    private $itemKitForm = "item_master/item_kit";
    private $productProcessForm = "item_master/product_process";
    private $excel_upload_form = "item_master/excel_upload_form";
	private $itemRevision = "item_master/item_revision";
	private $machineForm = "item_master/machine_form";
    private $insp_param_form = "item_master/insp_param_form";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Item Master";
		$this->data['headData']->controller = "items";        
	}

    public function list($item_type = 0){
        $this->data['headData']->pageUrl = "items/list/".$item_type;
        $this->data['item_type'] = $item_type;
        $headerName = str_replace(" ","_",strtolower($this->itemTypes[$item_type]));
		$this->data['headData']->pageTitle = $this->itemTypes[$item_type];
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
        $this->data['hsnData'] = $this->hsnModel->getHSNList();
        $this->data['customFieldList'] = $this->customField->getCustomFieldList(); 
        $this->data['customOptionList'] = $this->customOption->getMasterList(); 
        $this->data['materialGrade'] = $this->materialGrade->getMaterialGrades();
        $this->data['packingMaterialList'] = $this->item->getItemList(['item_type'=>2]);
        if($data['item_type'] == 5){
            $this->load->view($this->machineForm,$this->data);
        }else{
            $this->load->view($this->form,$this->data);
        }
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();
        
        /* if(empty($data['item_code']))
            $errorMessage['item_code'] = "Item Code is required."; */

		if(empty($data['item_name']))
			$errorMessage['item_name'] = "Item Name is required.";

		if($data['item_type'] != 5):
            if(empty($data['unit_id'])):
                $errorMessage['unit_id'] = "Unit is required.";
			endif;
            if(empty($data['com_unit_id'])):
                $errorMessage['com_unit_id'] = "Commercial Unit is required.";
			endif;
            if($data['unit_id'] != $data['com_unit_id']):
                if(empty($data['conversion_value'])):
                    $errorMessage['conversion_value'] = "Conversion Value is required.";
                endif;
            endif;
        endif;
        if(empty($data['category_id']))
            $errorMessage['category_id'] = "Category is required.";

        if($data['item_type'] == 1):
            if(!empty($_FILES['item_image']['name'])):
                $attachment = "";
                $this->load->library('upload');
                
                $_FILES['userfile']['name']     = $_FILES['item_image']['name'];
                $_FILES['userfile']['type']     = $_FILES['item_image']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['item_image']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['item_image']['error'];
                $_FILES['userfile']['size']     = $_FILES['item_image']['size'];

                $imagePath = realpath(APPPATH . '../assets/uploads/item_image/');

                $fileName = preg_replace('/[^A-Za-z0-9]+/', '_', strtolower($_FILES['item_image']['name']));
                $config = ['file_name' => $fileName, 'allowed_types' => 'jpg|jpeg|png|gif|JPG|JPEG|PNG', 'max_size' => 10240, 'overwrite' => FALSE, 'upload_path' => $imagePath];

                $this->upload->initialize($config);

                if(!$this->upload->do_upload()):
                    $errorMessage['item_image'] .= $fileName . " => " . $this->upload->display_errors();
                else:
                    $uploadData = $this->upload->data();
                    $attachment = $uploadData['file_name'];
                endif;

                if(!empty($errorMessage['item_image'])):
                    if (file_exists($imagePath . '/' . $attachment)) : unlink($imagePath . '/' . $attachment); endif;
                endif;

                $data['item_image'] = $attachment;
            endif;
        endif;
            
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			/* if(!empty($data['hsn_code'])):
			    $hsnData = $this->hsnModel->getHSNDetail(['hsn'=>$data['hsn_code']]);
				$data['gst_per'] = $hsnData->gst_per;
			endif; 
			if($data['item_type'] == 3):
				$data['item_name'] = (!empty($data['size'])) ? $data['size'] : '';
				$data['item_name'] .= (!empty($data['shape'])) ? ' '.$data['shape'] : '';
				$data['item_name'] .= (!empty($data['bartype'])) ? ' '.$data['bartype'] : '';
			endif;*/
			
            $this->printJson($this->item->save($data));
        endif;
    }

    public function edit(){
        $data = $this->input->post();
        $this->data['dataRow'] = $itemDetail = $this->item->getItem($data);
        $this->data['item_type'] = $itemDetail->item_type;
        $this->data['unitData'] = $this->item->itemUnits();
        $this->data['categoryList'] = $this->itemCategory->getCategoryList(['category_type'=>$itemDetail->item_type,'final_category'=>1]);
        $this->data['hsnData'] = $this->hsnModel->getHSNList();
        $this->data['customFieldList'] = $this->customField->getCustomFieldList(); 
        $this->data['customOptionList'] = $this->customOption->getMasterList();  
        $this->data['customData'] = $this->item->getItemUdfData(['item_id'=>$data['id']]);
        $this->data['materialGrade'] = $this->materialGrade->getMaterialGrades();
        $this->data['packingMaterialList'] = $this->item->getItemList(['item_type'=>2]);
        if($itemDetail->item_type == 5){
            $this->load->view($this->machineForm,$this->data);
        }else{
            $this->load->view($this->form,$this->data);
        }
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
    
    /* Product Excel Upload */ 
    /* Created By :- Avruti @12-04-2024 */
    public function addProductExcel(){
        $this->load->view($this->excel_upload_form,$this->data);
    }

    /* Created By :- Avruti @12-04-2024 */
    public function saveProductExcel(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Enter Item detail";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            foreach($data['itemData'] as $row):
                $catData = $this->itemCategory->getCategory(['category_name'=>$row['category_id']]);
                $unitData = $this->item->getUnitNameWiseId(['unit_name'=>$row['unit_id']]);  

                $row['category_id'] = $catData->id;
                $row['unit_id'] = $unitData->id;
                $row['item_type'] = 1;
                $row['packing_standard'] = (!empty($row['packing_standard'] > 0)) ? $row['packing_standard'] : 1 ; 

                $result = $this->item->save($row);
            endforeach;
           
            $this->printJson(['status'=>1,'message'=>'Item saved successfully.']);
        endif;
    }

    public function checkItemDuplicate(){
        $data = $this->input->post();
        $customWhere = "item_name = '".$data['item_name']."' ";
        $itemData = $this->item->getItem(['customWhere'=>$customWhere]);
        $this->printJson(['status'=>1,'item_id'=>(!empty($itemData->id)?$itemData->id:"")]);
    }
	
	   /* Start Item Revision 
    Created By Rashmi @24-04-2024 */
    public function addItemRevision(){
        $id = $this->input->post('id'); 
        $this->data['item_id'] = $id; 
        $this->load->view($this->itemRevision,$this->data);
    }

    public function saveItemRevision(){ 
        $data = $this->input->post(); 
		$errorMessage = array();
		
        if(empty($data['rev_no']))
            $errorMessage['rev_no'] = "Revision No. is required.";     
        if(empty($data['rev_date']))
            $errorMessage['rev_date'] = "Revision Date is required.";     
         
		if(!empty($errorMessage)):
			$this->printJson(['status'=>0,'message'=>$errorMessage]);
		else:
			$this->printJson($this->item->saveItemRevision($data));
		endif;
    }
    
    public function itemRevisionHtml(){  
        $data = $this->input->post();
        $revisionData = $this->item->getItemRevision(['item_id'=>$data['item_id']]);
		$i=1; $tbody='';
        
		if(!empty($revisionData)):
			foreach($revisionData as $row):
                $deleteParam = "{'postData':{'id' : ".$row->id."},'res_function':'itemRevisionHtml','fndelete':'deleteItemRevision'}";
				$tbody.= '<tr>
						<td>'.$i++.'</td>
						<td>'.$row->rev_no.'</td>
						<td>'.$row->rev_date.'</td>
						<td class="text-center">
							<button type="button" onclick="trash('.$deleteParam.');" class="btn btn-outline-danger waves-effect waves-light permission-remove"><i class="mdi mdi-trash-can-outline"></i></button>
						</td>
					</tr>';
			endforeach;
		endif;
        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
	}
	
	public function deleteItemRevision(){ 
        $data=$this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
			$this->printJson($this->item->deleteItemRevision($data['id']));
		endif;
    }
    // End Item Revision

    /**** Inspection Parameters */
    public function addInspectionParameter(){
        $data = $this->input->post();
        $this->data['item_id'] = $data['id'];
        $this->data['processList'] = $this->item->getProductProcessList(['item_id'=>$data['id']]);
        $this->data['revisionList'] = $this->item->getItemRevision(['item_id'=>$data['id']]);
        $this->load->view($this->insp_param_form,$this->data);
    }

    public function saveInspection(){
        $data = $this->input->post();
        $errorMessage = array();
        $itmData = $this->item->getItem(['id'=>$data['item_id']]);
        if(empty($data['rev_no']) && $itmData->item_type == 1){ $errorMessage['rev_no'] = "Revision is required."; }   
        if(empty($data['control_method'])){ $errorMessage['control_method'] = "Control Method is required."; }
        elseif(empty($data['process_id']) && in_array($data['control_method'],['IPR,SAR'])){ $errorMessage['process_id'] = "Process is required."; }
        if(empty($data['parameter'])){ $errorMessage['parameter'] = "Parameter is required."; }   
        if(empty($data['specification'])){  $errorMessage['specification'] = "Specification is required."; }
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['control_method'] = implode(",",$data['control_method']);
            $this->printJson($this->item->saveInspection($data));
        endif;
    }

    public function inspectionHtml(){
        $data = $this->input->post();
        $paramData = $this->item->getInspectionParam($data['item_id']);
        $tbodyData="";$i=1; 
        if(!empty($paramData)):
            $i=1;
            foreach($paramData as $row):
                $deleteParam = "{'postData':{'id' : ".$row->id."},'res_function':'inspectionHtml','fndelete':'deleteInspection'}";
                
                $editBtn = "<button type='button' onclick='editInspParam(".json_encode($row).",this);' class='btn btn-sm btn-outline-info waves-effect waves-light btn-sm permission-modify' datatip='Edit'><i class='far fa-edit'></i></button>";
                $tbodyData.= '<tr>
                            <td>'.$i++.'</td>
                            <td>'.$row->rev_no.'</td>
                            <td>'.$row->process_name.'</td>
                            <td>'.$row->parameter.'</td>
                            <td>'.$row->specification.'</td>
                            <td>'.$row->instrument.'</td>
                            <td>'.$row->control_method.'</td>
                            <td class="text-center">
                                '.$editBtn.'
                                <button type="button" onclick="trash('.$deleteParam.');" class="btn btn-outline-danger  btn-sm waves-effect waves-light permission-remove" datatip="Remove"><i class="mdi mdi-trash-can-outline"></i></button>
						    </td>
                        </tr>';
            endforeach;
        else:
            $tbodyData.= '<tr><td colspan="8" style="text-align:center;">No Data Found</td></tr>';
        endif;

        $this->printJson(['status'=>1,'tbodyData'=>$tbodyData]);
    }

    public function deleteInspection(){
        $data=$this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->item->deleteInspection($data['id']));
        endif;
    }

    /* Product Inspection Parameter Excel Upload */
    public function createProductInspExcel($item_id){
        $processData = $this->item->getProductProcessList(['item_id'=>$item_id]);
        $table_column = array('parameter','specification','instrument','min_value','max_value','is_line','is_final','check_type','rev_no');

        $spreadsheet = new Spreadsheet();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        
        $html = "<tr>
            <th>rev_no</th>
            <th>parameter</th>
            <th>specification</th>
            <th>instrument</th>
            <th>Control_Method</th>
        </tr>";
        $exlData = '<table>' . $html . '</table>';
        $spreadsheet = $reader->loadFromString($exlData);
        $excelSheet = $spreadsheet->getActiveSheet();
        $excelSheet = $excelSheet->setTitle('Inspection');
        
        $hcol = $excelSheet->getHighestColumn();
        $hrow = $excelSheet->getHighestRow();
        $packFullRange = 'A1:' . $hcol . $hrow;
        foreach (range('A', $hcol) as $col) :
            $excelSheet->getColumnDimension($col)->setAutoSize(true);
        endforeach;
        $i = 1;
        if(!empty($processData)):
            foreach ($processData as $row) :
            
                $pdfData = '<table>' . $html . '</table>';

                $reader->setSheetIndex($i);

                // if ($i == 0) : $spreadsheet = $reader->loadFromString($pdfData);
                // else : $spreadsheet = $reader->loadFromString($pdfData, $spreadsheet);  endif;
                $spreadsheet = $reader->loadFromString($pdfData, $spreadsheet);

                $row->process_name = trim(preg_replace('/[^A-Za-z0-9\-]/', ' ', $row->process_name));
                $row->process_name = substr(trim(str_replace('-', ' ', $row->process_name)),0,30);
                $spreadsheet->getSheet($i)->setTitle($row->process_name);
                $excelSheet = $spreadsheet->getSheet($i);
                $hcol = $excelSheet->getHighestColumn();
                $hrow = $excelSheet->getHighestRow();
                $packFullRange = 'A1:' . $hcol . $hrow;
                foreach (range('A', $hcol) as $col) :
                    $excelSheet->getColumnDimension($col)->setAutoSize(true);
                endforeach;
                $i++;
            endforeach;
            
        endif;

        $fileDirectory = realpath(APPPATH . '../assets/uploads/product_inspection');
        $fileName = '/product_inspection_' . time() . '.xlsx';

        $writer = new Xlsx($spreadsheet);
        $writer->save($fileDirectory . $fileName);
        header("Content-Type: application/vnd.ms-excel");
        redirect(base_url('assets/uploads/product_inspection') . $fileName);
    }

    public function importProductExcel(){
        $postData = $this->input->post();
        $insp_excel = '';
        if (isset($_FILES['insp_excel']['name']) || !empty($_FILES['insp_excel']['name'])) :
            $this->load->library('upload');
            $_FILES['userfile']['name']     = $_FILES['insp_excel']['name'];
            $_FILES['userfile']['type']     = $_FILES['insp_excel']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['insp_excel']['tmp_name'];
            $_FILES['userfile']['error']    = $_FILES['insp_excel']['error'];
            $_FILES['userfile']['size']     = $_FILES['insp_excel']['size'];

            $imagePath = realpath(APPPATH . '../assets/uploads/product_inspection');
            $config = ['file_name' => "inspection_" . $_FILES['userfile']['name'], 'allowed_types' => '*', 'max_size' => 10240, 'overwrite' => FALSE, 'upload_path' => $imagePath];

            $this->upload->initialize($config);
            if (!$this->upload->do_upload()) :
                $errorMessage['insp_excel'] = $this->upload->display_errors();
                $this->printJson(["status" => 0, "message" => $errorMessage]);
            else :
                $uploadData = $this->upload->data();
                $insp_excel = $uploadData['file_name'];
            endif;
            
            if (!empty($insp_excel)) {
                $processData = [];
                $processData = $this->item->getProductProcessList(['item_id'=>$postData['item_id']]);
                // if(empty($processData)){
                    $prsDt = new stdClass(); $prsDt->process_name = 'Inspection';
                    $processData[] = $prsDt;
                // }
                $row = 0;$paramData=[];
                foreach ($processData as $prs) :
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($imagePath . '/' . $insp_excel);
                    
                    $prs->process_name = trim(preg_replace('/[^A-Za-z0-9\-]/', ' ', $prs->process_name));
                    $prs->process_name = substr(trim(str_replace('-', ' ', $prs->process_name)),0,30);
                    
                    $fileData = array($spreadsheet->getSheetByName($prs->process_name)->toArray(null, true, true, true));
                    $fieldArray = array();
                    if (!empty($fileData)) {
                        $fieldArray = $fileData[0][1];
                        for ($i = 2; $i <= count($fileData[0]); $i++) {
                            $rowData = array();
                            $c = 'A';
                            foreach ($fileData[0][$i] as $key => $colData) :
                                    $field_val = strtolower($fieldArray[$c]);
                                    $rowData[$field_val] = $colData;
                                    $c++;
                            endforeach;
                            if(!empty($rowData['parameter'])):
                                $paramData[]=[
                                    'id'=>'',
                                    'process_id'=>(!empty($prs->process_id)?$prs->process_id:''),
                                    'item_id'=>$postData['item_id'],
                                    'rev_no'=>$rowData['rev_no'],
                                    'parameter'=>$rowData['parameter'],
                                    'specification'=>$rowData['specification'],
                                    'instrument'=>$rowData['instrument'],
                                    'control_method'=>$rowData['control_method'],
                                    'created_by'=>$this->loginId,
                                    'created_at'=>date("Y-m-d H:i:s"),
                                ];
                                $row++;
                            endif;
                        }
                    }
                endforeach;
				
                if(!empty($paramData)){
                    $result = $this->item->saveInspectionParamExcel($paramData);
                    $this->printJson($result);
                }else{
                    $this->printJson(['status' => 0, 'message' => 'Data not found...!']);
                }
                
            } else {
                $this->printJson(['status' => 0, 'message' => 'Data not found...!']);
            }
        else :
            $this->printJson(['status' => 0, 'message' => 'Please Select File!']);
        endif;
    }

    /* Raw Material Excel Upload */
    public function createRMInspExcel($item_id) {
        $paramData=$this->item->getPreInspectionParam(['item_id' => $item_id]);
		$table_column = array('parameter', 'specification','instrument', 'min_value');
		$spreadsheet = new Spreadsheet();
        $inspSheet = $spreadsheet->getActiveSheet();
        $inspSheet = $inspSheet->setTitle('Inspection');
		$xlCol = 'A';$rows=1;
		foreach ($table_column as $tCols){
            $inspSheet->setCellValue($xlCol.$rows, $tCols);
            $xlCol++;
        }

		$fileDirectory = realpath(APPPATH . '../assets/uploads/rm_inspection');
		$fileName = '/rm_inspection_'.time().'.xlsx';
        $writer = new Xlsx($spreadsheet);
		$writer->save($fileDirectory.$fileName);
		header("Content-Type: application/vnd.ms-excel");
        redirect(base_url('assets/uploads/rm_inspection').$fileName);              
    }

    public function importRMExcel(){
        $postData = $this->input->post();
        $insp_excel = '';
        if(isset($_FILES['insp_excel']['name']) || !empty($_FILES['insp_excel']['name']) ):
            $this->load->library('upload');
            $_FILES['userfile']['name']     = $_FILES['insp_excel']['name'];
            $_FILES['userfile']['type']     = $_FILES['insp_excel']['type'];
            $_FILES['userfile']['tmp_name'] = $_FILES['insp_excel']['tmp_name'];
            $_FILES['userfile']['error']    = $_FILES['insp_excel']['error'];
            $_FILES['userfile']['size']     = $_FILES['insp_excel']['size'];
            
            $imagePath = realpath(APPPATH . '../assets/uploads/rm_inspection');
            $config = ['file_name' => "inspection_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' =>$imagePath];
    
            $this->upload->initialize($config);
            if (!$this->upload->do_upload()):
                $errorMessage['insp_excel'] = $this->upload->display_errors();
                $this->printJson(["status"=>0,"message"=>$errorMessage]);
            else:
                $uploadData = $this->upload->data();
                $insp_excel = $uploadData['file_name'];
            endif;
            if(!empty($insp_excel))
            {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($imagePath.'/'.$insp_excel);
                $fileData = array($spreadsheet->getSheetByName('Inspection')->toArray(null,true,true,true));
                $fieldArray = Array();
                
                if(!empty($fileData))
                {
                    $fieldArray = $fileData[0][1];$row = 0;
                    for($i=2;$i<=count($fileData[0]);$i++)
                    {
                        $rowData = Array();$c='A';
                        $rowData['id'] = '';
                        foreach($fileData[0][$i] as $key=>$colData):
                            $field_val = strtolower($fieldArray[$c]);
                            $rowData[$field_val] = $colData;
                            $c++;
                        endforeach;
                        $rowData['item_id']=$postData['item_id'];
                        $rowData['created_by'] = $this->session->userdata('loginId');
                        $this->item->saveInspection($rowData);
                        $row++;
                    }
                }                
				$result['tbodyData'] = $this->rmInspectionHtml(['item_id'=>$postData['item_id']]);
                
                $this->printJson(['status' => 1, 'message' => $row . ' Record updated successfully.', 'tbodyData' => $result['tbodyData']]);
            }
            else{$this->printJson(['status'=>0,'message'=>'Data not found...!']);}
        else:
            $this->printJson(['status'=>0,'message'=>'Please Select File!']);
        endif;
    }
}
?>