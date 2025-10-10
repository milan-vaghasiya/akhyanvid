<?php
class SalesOrders extends MY_Controller{
    private $indexPage = "sales_order/index";
    private $form = "sales_order/form"; 
    private $partyOrder = "sales_order/party_order";   
    private $orderForm = "sales_order/party_order_form";   

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Sales Order";
		$this->data['headData']->controller = "salesOrders";        
        $this->data['headData']->pageUrl = "salesOrders";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'salesOrders','tableName'=>'so_master']);
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader("salesOrders");
        $this->load->view($this->indexPage,$this->data);
    }
	
    public function getDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->salesOrder->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getSalesOrderData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addOrder(){
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>"1,2"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
        $this->load->view($this->form,$this->data);
    }

    public function createOrder($id){
        $dataRow = $this->salesQuotation->getSalesQuotation(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        
        $dataRow->from_entry_type = $dataRow->entry_type;
        $dataRow->ref_id = $dataRow->id;
        $dataRow->entry_type = "";
        $dataRow->id = "";
        $dataRow->trans_prefix = "";
        $dataRow->trans_no = "";
        $dataRow->trans_number = "";

        $itemList = array();
        foreach($dataRow->itemList as $row):
            $row->from_entry_type = $row->entry_type;
            $row->ref_id = $row->id;
            $row->entry_type = "";
            $row->id = "";
            $row->taxable_amount = $row->amount = round(($row->qty * $row->price),2);
            if(!empty($row->disc_per) && !empty($row->amount)):
                $row->disc_amount = round((($row->disc_per * $row->amount) / 100),2);
                $row->taxable_amount = $row->taxable_amount - $row->disc_amount;
            endif;

            $row->net_amount = $row->taxable_amount;
            if(!empty($row->taxable_amount) && !empty($row->gst_per)):
                $row->gst_amount = round((($row->gst_per * $row->taxable_amount) / 100),2);

                $row->igst_per = $row->gst_per;
                $row->igst_amount = $row->gst_amount;

                $row->cgst_per = round(($row->gst_per / 2),2);
                $row->cgst_amount = round(($row->gst_amount / 2),2);
                $row->sgst_per = round(($row->gst_per / 2),2);
                $row->sgst_amount = round(($row->gst_amount / 2),2);

                $row->net_amount = $row->taxable_amount + $row->gst_amount;
            endif;

            $itemList[] = $row;
        endforeach;
        $dataRow->itemList = $itemList;
        
        $this->data['dataRow'] = $dataRow;
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => "1,2",'party_type'=>"0,1"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1,'active_item'=>"1,2"]);
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post(); 
        $errorMessage = array();

        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['itemData']))
            $errorMessage['itemData'] = "Item Details is required.";
		if (formatDate($data['trans_date'], 'Y-m-d') < $this->startYearDate OR formatDate($data['trans_date'], 'Y-m-d') > $this->endYearDate)
			$errorMessage['trans_date'] = "Invalid Date (Out of Financial Year).";
		
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:      
			if ($_FILES['attachment_file']['name'] != null || !empty($_FILES['attachment_file']['name'])) :
				$this->load->library('upload');
				$_FILES['userfile']['name']     = $_FILES['attachment_file']['name'];
				$_FILES['userfile']['type']     = $_FILES['attachment_file']['type'];
				$_FILES['userfile']['tmp_name'] = $_FILES['attachment_file']['tmp_name'];
				$_FILES['userfile']['error']    = $_FILES['attachment_file']['error'];
				$_FILES['userfile']['size']     = $_FILES['attachment_file']['size'];

				$imagePath = realpath(APPPATH . '../assets/uploads/sales_order/');
				$config = ['file_name' => time() . "_attachment_file_" . $_FILES['userfile']['name'], 'allowed_types' => '*', 'max_size' => 10240, 'overwrite' => FALSE, 'upload_path'    => $imagePath];

				$this->upload->initialize($config);
				if (!$this->upload->do_upload()) :
					$errorMessage['attachment_file'] = $this->upload->display_errors();
					$this->printJson(["status" => 0, "message" => $errorMessage]);
				else :
					$uploadData = $this->upload->data();
					$data['attachment_file'] = $uploadData['file_name'];
				endif;
			else :
				unset($data['attachment_file']);
			endif;
			
            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;
			
			if(empty($data['id'])){
				$data['trans_prefix'] = $this->data['entryData']->trans_prefix;
				$data['trans_no'] = $this->data['entryData']->trans_no;
				$data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
			}
            $this->printJson($this->salesOrder->save($data));
        endif;
    }

    public function edit($id,$accept = 0){
        $this->data['is_approve'] = (!empty($accept))?$this->loginId:0;
        $this->data['approve_date'] = (!empty($accept))?date("Y-m-d"):"";
        $this->data['dataRow'] = $dataRow = $this->salesOrder->getSalesOrder(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => "1,2"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
		$this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['salesExecutives'] = $this->employee->getEmployeeList();
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesOrder->delete($id));
        endif;
    }

    public function printOrder($id,$pdf_type=''){
        $this->data['dataRow'] = $dataRow = $this->salesOrder->getSalesOrder(['id'=>$id,'itemList'=>1]);
        $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
		$this->data['termsData'] = (!empty($dataRow->termsConditions) ? $dataRow->termsConditions: "");
        $this->data['taxList'] = $this->taxMaster->getActiveTaxList(2);
        $this->data['expenseList'] = $this->expenseMaster->getActiveExpenseList(2);
		
        //$signImg = base_url('assets/uploads/signature/sign_AE002.png');
        $logo = (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->company_logo):base_url('assets/images/logo.png');
        $this->data['letter_head'] =  (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->print_header):base_url('assets/images/letterhead_top.png');
       
        
        $pdfData = $this->load->view('sales_order/print', $this->data, true);        
        
        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
            <tr>
                <td style="width:25%;">SO. No. & Date : '.$dataRow->trans_number . ' [' . formatDate($dataRow->trans_date) . ']</td>
                <td style="width:25%;"></td>
                <td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
            </tr>
        </table>';
        
        //$pdfData .='<div style="position:fixed;bottom:60px;right:1030px;"><img style="width:110px;height:70px" src="'.$signImg.'"></div>';
        
		$mpdf = new \Mpdf\Mpdf();
		$filePath = realpath(APPPATH . '../assets/uploads/sales_quotation/');
        $pdfFileName = $filePath.'/' . str_replace(["/","-"],"_",$dataRow->trans_number) . '.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
		$mpdf->SetWatermarkImage($logo,0.05,array(120,45));
        $mpdf->showWatermarkImage = true;
        $mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',10,5,5,15,5,5,'','','','','','','','','','A4-P');
		
		
        $mpdf->WriteHTML($pdfData);
		
		ob_clean();
		$mpdf->Output($pdfFileName, 'I');
		
    }

    public function getPartyOrders(){
        $data = $this->input->post();
        $this->data['entry_type'] = (!empty($data['entry_type']) ? $data['entry_type'] : 0);
        $this->data['orderItems'] = $this->salesOrder->getPendingOrderItems($data);
        $this->load->view('sales_invoice/create_invoice',$this->data);
    }

    public function getPartySoOrders(){
        $data = $this->input->post();
        $data['entry_type'] = $this->data['entryData']->id;
        $soData = $this->salesOrder->getPendingOrderItems($data);
        $html="";
        if(!empty($soData)):
            $i=1;
            foreach($soData as $row):
                
                $html .= '<tr>
                            <td class="text-center">
                                <input type="checkbox" id="md_checkbox_'.$i.'" name="ref_id[]" class="filled-in chk-col-success" value="'.$row->id.'"><label for="md_checkbox_'.$i.'" class="mr-3"></label>
                            </td>
                            <td class="text-center">'.$row->trans_number.'</td>
                            <td class="text-center">'.formatDate($row->trans_date).'</td>
                            <td class="text-center">'.$row->doc_no.'</td>
                            <td class="text-center">'.$row->item_name.'</td>
                            <td class="text-center">'.($row->qty - $row->dispatch_qty).'</td>
                          </tr>';
                $i++;
            endforeach;
        else:
            $html = '<tr><td class="text-center" colspan="6">No Data Found</td></tr>';
        endif;
		$this->printJson(['status'=>1,'htmlData'=>$html]);
	}

    /* Party Order Start */
    public function partyOrders(){
        $this->data['headData']->pageTitle = "Orders";
        $this->data['tableHeader'] = getSalesDtHeader("partyOrders");
        $this->load->view($this->partyOrder,$this->data);
    }

    public function getPartyOrderDTRows($status = 0){
        $data = $this->input->post();$data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        $result = $this->salesOrder->getPartyOrderDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getPartyOrderData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addPartyOrder(){
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>"1,2"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->data['brandList'] = $this->brandMaster->getBrandList();
        $this->load->view($this->orderForm,$this->data);
    }

    public function savePartyOrder(){
        $data = $this->input->post();
        $errorMessage = array();

        if(array_sum(array_column($data['itemData'],'qty')) <= 0):
            $this->printJson(['status'=>0,'message'=>['item_error'=>'Please enter at least one item qty.']]);
        endif;

        $partyData = $this->party->getParty(['id'=>$this->partyId]);
        $gstType = ($partyData->state_code != 24)?2:1;

        $total_amount = $taxable_amount = $cgst_amount = $sgst_amount = $igst_amount = $net_amount = 0;

        $itemData = array();
        foreach($data['itemData'] as $row):
            if($row['qty'] > 0):
                $itemDetail = $this->item->getItem(['id'=>$row['item_id']]);
                $brandDetail = $this->brandMaster->getBrand(['id'=>$row['brand_id']]);

                $gstPer = $igstPer = $cgstPer = $sgstPer = 0;
                $amount = $taxableAmt = $netAmt = $discAmt = 0;
                $gstAmt = $igstAmt = $cgstAmt = $sgstAmt = 0;                

                $gstPer = $igstPer = $itemDetail->gst_per;
                $cgstPer = $sgstPer = round(($itemDetail->gst_per/2),2);                

                $amount = $taxableAmt = $row['qty'] * $itemDetail->price;
                if(!empty($itemDetail->defualt_disc) && !empty($amount)):
                    $discAmt = round((($amount * $itemDetail->defualt_disc)/100),2);
                    $taxableAmt = $amount - $discAmt;
                endif;

                if(!empty($taxableAmt)):
                    $gstAmt = $igstAmt = round((($gstPer * $taxableAmt)/100),2);
                    $cgstAmt = $sgstAmt = round(($gstAmt/2),2);
                endif;

                $netAmt = $taxableAmt + $gstAmt;

                $total_amount += $amount;
                $taxable_amount += $taxableAmt;
                if($gstType == 1):
                    $cgst_amount += $cgstAmt;
                    $sgst_amount += $sgstAmt;
                else:
                    $igst_amount += $igstAmt;
                endif;
                $net_amount += $netAmt;

                $itemData[] = [
                    'id' => '',
                    'item_id' => $itemDetail->id,
                    'item_name' => $itemDetail->item_name,
                    'item_code' => $itemDetail->item_code,
                    'item_type' => $itemDetail->item_type,
                    'hsn_code' => $itemDetail->hsn_code,
                    'qty' => $row['qty'],
                    'unit_id' => $itemDetail->unit_id,
                    'unit_name' => $itemDetail->unit_name,
                    'price' => $itemDetail->price,
                    'disc_per' => $itemDetail->defualt_disc,
                    'disc_amount' => $discAmt,
                    'amount' => $amount,
                    'taxable_amount' => $taxableAmt,
                    'net_amount' => $netAmt,
                    'amount' => $amount,
                    'cgst_per' => $cgstPer,
                    'cgst_amount' => $cgstAmt,
                    'sgst_per' => $sgstPer,
                    'sgst_amount' => $sgstAmt,
                    'igst_per' => $igstPer,
                    'igst_amount' => $igstAmt,
                    'gst_per' => $gstPer,
                    'gst_amount' => $gstAmt,
                    'item_remark' => $row['item_remark'],
                ];
            endif;
        endforeach;

        $trans_prefix = $this->data['entryData']->trans_prefix;
        $trans_no = $this->data['entryData']->trans_no;
        $masterData = [
            'id' => '',
            'entry_type' => $this->data['entryData']->id,
            'trans_prefix' => $trans_prefix,
            'trans_no' => $trans_no,
            'trans_number' => $trans_prefix.$trans_no,
            'trans_date' => date("Y-m-d"),
            'sales_executive' => $partyData->id,
            'party_id' => $partyData->id,
            'party_name' => $partyData->party_name,
            'gstin' => $partyData->gstin,
            'gst_type' => $gstType,
            'party_state_code' => $partyData->state_code,
            'apply_round' => 1,
            'ledger_eff' => 0,
            'masterDetails' => [
                't_col_1' => $partyData->contact_person,
                't_col_2' => $partyData->party_mobile,
                't_col_3' => $partyData->party_address,
                't_col_4' => $partyData->party_pincode,
            ],
            'itemData' => $itemData,
            'total_amount' => $total_amount,
            'taxable_amount' => $taxable_amount,
            'cgst_amount' => $cgst_amount,
            'sgst_amount' => $sgst_amount,
            'igst_amount' => $igst_amount,
            'net_amount' => $net_amount
        ];

        $masterData['vou_name_l'] = $this->data['entryData']->vou_name_long;
        $masterData['vou_name_s'] = $this->data['entryData']->vou_name_short;
        $this->printJson($this->salesOrder->save($masterData));
    }
    /* Party Order End */
	
	public function changeOrderStatus(){
        $postData = $this->input->post();
        if(empty($postData['trans_main_id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesOrder->changeOrderStatus($postData));
        endif;
    }

    public function approveSalesOrder(){
        $postData = $this->input->post();
        if(empty($postData['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesOrder->approveSalesOrder($postData));
        endif;
    }
	
	public function getSOLastRec() {
        $postData = $this->input->post();
        $postData['limit'] = 5;
        $soData = $this->salesOrder->getSalesOrderItems($postData);
        $tbodyData="";
        if(!empty($soData)):
            $i=1;
            foreach($soData as $row):
                $tbodyData .= '<tr>
                            <td class="text-center">'.$i++.'</td>
                            <td class="text-center">'.formatDate($row->trans_date).'</td>
                            <td class="text-center">'.$row->party_name.'</td>
                            <td class="text-center">'.floatval($row->qty).'</td>
                            <td class="text-center">'.floatval($row->price).'</td>
                          </tr>';
            endforeach;
        else:
            $tbodyData = '<tr><td class="text-center" colspan="5">No Data</td></tr>';
        endif;
        $this->printJson(['status'=>1,'tbodyData'=>$tbodyData]);
    }

    public function bomReport($item_id=""){
        $this->data['item_id'] = $item_id;
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->load->view("sales_order/bom_report",$this->data);
    }

    public function getBomData(){
        $data = $this->input->post();
        $this->data['item_name'] = $data['item_name'];
        $this->data['bomData'] = $this->storeReport->getBomData(['item_id'=>$data['item_id']]);
        $html = $this->load->view("sales_order/bom_view",$this->data,true);
        /*if($data['report_type'] == 1){
            $this->data['bomData'] = $this->storeReport->getBomData(['item_id'=>$data['item_id']]);
            $html = $this->load->view("reports/store_report/bom_view",$this->data,true);
        }else{
            $this->data['bomData'] = $this->storeReport->getBomCostData(['item_id'=>$data['item_id']]);
            $html = $this->load->view("reports/store_report/bom_cost_view",$this->data,true);
        }*/
       
        
        $this->printJson(['status'=>1, 'html'=>$html]);
    }
}
?>