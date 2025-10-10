<?php
class SalesInvoice extends MY_Controller{
    private $indexPage = "sales_invoice/index";
    private $form = "sales_invoice/form";    
    private $packingPrintForm = "sales_invoice/packing_print_form";
	 private $pendingOrder = "sales_invoice/pending_order";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Tax Invoice";
		$this->data['headData']->controller = "salesInvoice";        
        $this->data['headData']->pageUrl = "salesInvoice";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'salesInvoice']);
	}

    public function index($status = 0){
        $this->data['status'] = $status;
        if($status == 2){
            $this->data['tableHeader'] = getSalesDtHeader("pendingSO");
        }elseif($status == 3){
            $this->data['tableHeader'] = getSalesDtHeader("pendingDO");
        }elseif($status == 4){
            $this->data['tableHeader'] = getSalesDtHeader("pendingChallan");
        }else{
            $this->data['tableHeader'] = getAccountingDtHeader("salesInvoice");
        }
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows($status = 0){
        $data = $this->input->post();
        $data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        if($status == 2):
		    $result = $this->dispatchOrder->pendingSODTRows($data);
        elseif($status == 3):
		    $result = $this->dispatchOrder->getPendingDODTRows($data);
        elseif($status == 4):
            $result = $this->deliveryChallan->getPendingChallanDTRows($data);
        else:
            $result = $this->salesInvoice->getDTRows($data);
        endif;
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $row->entry_type =  $this->data['entryData']->id;
            if($status == 2):
                $sendData[] = getPendingSOData($row);
            elseif($status == 3):
                $sendData[] = getPendingDOData($row);
            elseif($status == 4):
                $sendData[] = getPendingChallanData($row);
            else:
                $sendData[] = getSalesInvoiceData($row);
			endif;
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addInvoice($jsonData=""){
		$postData = [];
		if(!empty($jsonData)):
            $postData = (Array) decodeURL($jsonData);
        endif;
		if(!empty($postData['ref_id'])){
            if($postData['entry_type'] == 14){
                $this->data['invItem'] = $invItem = $this->salesOrder->getSalesOrderItems(['ref_id'=>$postData['ref_id']]);
                $this->data['party_id'] = $invItem[0]->party_id;
				$this->data['ref_id'] = $postData['ref_id'];
                $this->data['from_entry_type'] = $postData['entry_type'];
            }
            if($postData['entry_type'] == 190){
                $this->data['invItem'] = $invItem = $this->dispatchOrder->getPendingDispatchOrders(['ref_id'=>$postData['ref_id'],'batchDetail'=>1]);
				$this->data['party_id'] = $invItem[0]->party_id;
				$this->data['party_name'] = $invItem[0]->party_name;
				$this->data['delivery_address'] = $invItem[0]->delivery_address;
				$this->data['doc_no'] = implode(',',array_column($invItem,'doc_no'));
                $this->data['ref_id'] = $postData['ref_id'];
                $this->data['from_entry_type'] = $postData['entry_type'];
            }
            if($postData['entry_type'] == 177){
                $this->data['invItem'] = $invItem = $this->deliveryChallan->getPendingChallanItems(['ref_id'=>$postData['ref_id']]);
				$this->data['party_id'] = $invItem[0]->party_id;
                $this->data['ref_id'] = implode(',',array_unique(array_column($invItem,'trans_main_id')));
                $this->data['from_entry_type'] = $postData['entry_type'];
				
				$addessDetail = array();
				if(!empty($invItem)){					
					$addessDetail = $this->salesOrder->getTransDetailData(['trans_main_id'=>$invItem[0]->trans_main_id, 'description'=>'DC MASTER DETAILS', 'single_row'=>1, 'tableName'=>'trans_main']);
				}
				$this->data['shipTo'] = $addessDetail;
            }
        }
        $this->data['entry_type'] = $this->data['entryData']->id;
        //$this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        //$this->data['trans_no'] = $this->data['entryData']->trans_no;
		$this->data['trans_no'] = $this->salesInvoice->getNextInvNo();
		//$this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>"1,2"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"1,8"]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['transportList'] = $this->transport->getTransportList();

        $this->data['taxClassList'] = $this->taxClass->getActiveTaxClass(2);
		$this->data['ledgerList'] = $this->party->getPartyList(['group_code'=>["'DT'","'ED'","'EI'","'ID'","'II'"]]);
        $this->load->view($this->form,$this->data);
    }		

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

		if (formatDate($data['trans_date'], 'Y-m-d') < $this->startYearDate OR formatDate($data['trans_date'], 'Y-m-d') > $this->endYearDate)
			$errorMessage['trans_date'] = "Invalid Date (Out of Financial Year).";
        if(empty($data['trans_no']))
            $errorMessage['trans_no'] = "Inv. No. is required.";
        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['sp_acc_id']))
            $errorMessage['sp_acc_id'] = "GST Type is required.";
        if(empty($data['sys_per']))
            $errorMessage['sys_per'] = "Bill Per. is required.";
        if(empty($data['itemData'])):
            $errorMessage['itemData'] = "Item Details is required.";
        else:
            $bQty = array();
            foreach($data['itemData'] as $key => $row):
                if($row['stock_eff'] == 1):
                    $batchDetail = $row['batch_detail'];
                    $batchDetail = json_decode($batchDetail,true);
                    if(!empty($row['id'])):
                        $oldItem = $this->salesInvoice->getSalesInvoiceItem(['id'=>$row['id']]);
                    endif;
                    $batchQty = !empty($batchDetail)?array_sum(array_column($batchDetail,'batch_qty')):0;
                    if($row['qty'] > $batchQty):
                        $errorMessage['qty'.$key] = "Stock not available.";
                    else:
                        foreach($batchDetail as $batch):
                            $postData = [
                                'location_id' => $batch['location_id'],
                                'opt_qty' => $batch['opt_qty'],
                                'item_id' => $row['item_id'],
                                'stock_required' => 1,
                                'single_row' => 1
                            ];                        
                            $stockData = $this->itemStock->getItemStockBatchWise($postData);  
                            $batchKey = "";
                            $batchKey = $batch['remark'];
                            
                            $stockQty = (!empty($stockData->qty))?floatVal($stockData->qty):0;
                            if(!empty($row['id'])):                            
                                $stockQty = $stockQty + $oldItem->qty;
                            endif;
                            
                            if(!isset($bQty[$batchKey])):
                                $bQty[$batchKey] = $batch['batch_qty'] ;
                            else:
                                $bQty[$batchKey] += $row['batch_qty'];
                            endif;
    
                            if(empty($stockQty)):
                                $errorMessage['qty'.$key] = "Stock not available.";
                            else:
                                if($bQty[$batchKey] > $stockQty):
                                    $errorMessage['qty'.$key] = "Stock not available.";
                                endif;
                            endif;
                        endforeach;;
                    endif;
                    
                endif;
            endforeach;
        endif;
	
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;
            if(isset($data['batchDetail'])): unset($data['batchDetail']); endif;
            $this->printJson($this->salesInvoice->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->salesInvoice->getSalesInvoice(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => "1,2"]);
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>"1,8"]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['transportList'] = $this->transport->getTransportList();

        $this->data['taxClassList'] = $this->taxClass->getActiveTaxClass(2);
        $this->data['ledgerList'] = $this->party->getPartyList(['group_code'=>["'DT'","'ED'","'EI'","'ID'","'II'"]]);
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->salesInvoice->delete($id));
        endif;
    }

    public function printInvoice($jsonData=""){
        if(!empty($jsonData)):
            $postData = (Array) decodeURL($jsonData);
        else: 
            $postData = $this->input->post();
        endif;
        
        $printTypes = array();
        if(!empty($postData['original'])):
            $printTypes[] = "ORIGINAL";
        endif;

        if(!empty($postData['duplicate'])):
            $printTypes[] = "DUPLICATE";
        endif;

        if(!empty($postData['triplicate'])):
            $printTypes[] = "TRIPLICATE";
        endif;

        if(!empty($postData['extra_copy'])):
            for($i=1;$i<=$postData['extra_copy'];$i++):
                $printTypes[] = "EXTRA COPY";
            endfor;
        endif;

        $postData['header_footer'] = (!empty($postData['header_footer']))?1:0;
        $this->data['header_footer'] = $postData['header_footer'];
        $print_format = (!empty($postData['print_format']) ? 'sales_invoice/print_'.$postData['print_format'] : 'sales_invoice/print');

        $inv_id = (!empty($id))?$id:$postData['id'];

		$this->data['invData'] = $invData = $this->salesInvoice->getSalesInvoice(['id'=>$inv_id,'itemList'=>1,'packingList'=>1]);
		$this->data['partyData'] = $this->party->getParty(['id'=>$invData->party_id]);
        
        $taxClass = $this->taxClass->getTaxClass($invData->tax_class_id);
        $this->data['taxList'] = (!empty($taxClass->tax_ids))?$this->taxMaster->getTaxList(['tax_ids'=>$taxClass->tax_ids]):array();
        $this->data['expenseList'] = (!empty($taxClass->expense_ids))?$this->expenseMaster->getExpenseList(['expense_ids'=>$taxClass->expense_ids]):array();

		$this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
        $this->data['descriptionSetting'] = $this->masterModel->getAccountSettings();
		$this->data['termsData'] = (!empty($invData->termsConditions) ? $invData->termsConditions: "");
		$response="";
		$logo = (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->company_logo):'';
        $this->data['letter_head'] = (!empty($companyData->print_header))?base_url("assets/uploads/company_logo/".$companyData->print_header):base_url('assets/images/letterhead_top.png');
		$this->data['letter_foot'] = base_url('assets/images/lh-footer.png');
				
        $pdfData = "";
        $countPT = count($printTypes); $i=0;
        foreach($printTypes as $printType):
            ++$i;           
            $this->data['printType'] = $printType;
            $this->data['maxLinePP'] = (!empty($postData['max_lines']))?$postData['max_lines']:10;
		    $pdfData .= $this->load->view($print_format,$this->data,true);
            if($i != $countPT): $pdfData .= "<pagebreak>"; endif;
        endforeach;
            
		$mpdf = new \Mpdf\Mpdf();
		$pdfFileName = str_replace(["/","-"," "],"_",$invData->trans_number).'.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
		$mpdf->SetTitle($pdfFileName); 
        $mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		if(!empty($logo))
		{
		    $mpdf->SetWatermarkImage($logo,0.03,array(120,45));
		    $mpdf->showWatermarkImage = true;
		}
		//$mpdf->SetProtection(array('print'));
		$mpdf->AddPage('P','','','','',10,5,(($postData['header_footer'] == 1)?5:35),35,5,5,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
	}

    public function getPartyInvoiceItems(){
        $data = $this->input->post();
        $this->data['orderItems'] = $this->salesInvoice->getPendingInvoiceItems($data);
        $this->load->view('credit_note/create_creditnote',$this->data);
    }

	public function getPendingOrderData(){
        $data = $this->input->post();
        if($data['vou_type'] == 1){
            $result = $this->salesOrder->getPendingOrderItems($data);
            $i=1;$tbody="";
            if(!empty($result)){
                foreach($result as $row):
                    $tbody .= '<tr>
                                <td>'.$i++.'</td>
                                <td>'.$row->trans_number.'</td>
                                <td>'.$row->trans_date.'</td>
                                <td>'.$row->doc_no.'</td>
                                <td>'.$row->item_name.'</td>
                                <td>'.$row->qty.'</td>
                            </tr>';
                endforeach;
            }
        }elseif($data['vou_type'] == 2){
			 $entryData = $this->transMainModel->getEntryType(['controller'=>'deliveryChallan']);
            $data['entry_type'] = $entryData->id;
            $result = $this->deliveryChallan->getPendingChallanItems($data);
            
            $i=1;$tbody="";
            if(!empty($result)){
                foreach($result as $row):
                    $tbody .= '<tr>
                                <td>'.$i++.'</td>
                                <td>'.$row->trans_number.'</td>
                                <td>'.formatDate($row->trans_date).'</td>
                                <td>'.$row->doc_no.'</td>
                                <td>'.$row->item_name.'</td>
                                <td>'.floatval($row->pending_qty).'</td>
                            </tr>';
                endforeach;
            }
        }else{
            $result = $this->dispatchOrder->getPendingDispatchOrders($data); 
            $i=1;$tbody="";
            if(!empty($result)){
                foreach($result as $row):
                    $tbody .= '<tr>
                                <td>'.$i++.'</td>
                                <td>'.$row->order_number.' - '.$row->so_number.'</td>
                                <td>'.$row->so_date.'</td>
                                <td>'.$row->doc_no.'</td>
                                <td>'.$row->item_name.'</td>
                                <td>'.$row->link_qty.'</td>
                            </tr>';
                endforeach;
            }
        }
        $this->printJson(['status'=>0,'tbody'=>$tbody]);
    }

    public function getInvTransNo(){
        $data = $this->input->post();
        $trans_no = $this->salesInvoice->getNextInvNo($data['trans_prefix']);
        $this->printJson(['trans_no'=>$trans_no]);
    }
}
?>