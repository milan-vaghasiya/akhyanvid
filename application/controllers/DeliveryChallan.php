<?php
class DeliveryChallan extends MY_Controller{
    private $indexPage = "delivery_challan/index";
    private $form = "delivery_challan/form";    
	private $pendingOrder = "delivery_challan/pending_order";

    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Delivery Challan";
		$this->data['headData']->controller = "deliveryChallan";        
        $this->data['headData']->pageUrl = "deliveryChallan";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'deliveryChallan']);
	}

	public function index($status = 2){ 
        $this->data['status'] = $status;
        $this->data['headData']->pageTitle = "Delivery Challan";
        if($status == 2){
            $this->data['tableHeader'] = getSalesDtHeader("salesOrders");
        }elseif($status == 3){
            $this->data['tableHeader'] = getSalesDtHeader("pendingDO");
        }else{
            $this->data['tableHeader'] = getSalesDtHeader("deliveryChallan");
        }
        $this->load->view($this->indexPage,$this->data);
    }

	public function getDTRows($status = 2){
        $data = $this->input->post();
        $data['status'] = $status;
        $data['entry_type'] = $this->data['entryData']->id;
        if($status == 2):
			$data['status'] = 0;
			$data['entry_type'] = $this->transMainModel->getEntryType(['controller'=>'salesOrders','tableName'=>'so_master'])->id;
		    $result = $this->salesOrder->getDTRows($data);
        /*elseif($status == 3):
		    $result = $this->dispatchOrder->getPendingDODTRows($data);*/
        else:
            $result = $this->deliveryChallan->getDTRows($data);
        endif;

        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $row->entry_type =  $this->data['entryData']->id;
            if($status == 2):
				$row->use_for = 'pending_so';
                $sendData[] = getSalesOrderData($row);
            /*elseif($status == 3):
                $sendData[] = getPendingDOData($row);*/
            else:
                $sendData[] = getDeliveryChallanData($row);
			endif;
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addChallan($jsonData=""){
		$postData = [];
		if(!empty($jsonData)):
            $postData = (Array) decodeURL($jsonData);
        endif;
		
		$challanItem = [];
		if(!empty($postData['ref_id'])){
            if($postData['entry_type'] == 14){
                $this->data['challanItem'] = $challanItem = $this->salesOrder->getSalesOrderItems(['ref_id'=>$postData['ref_id']]);
                $this->data['party_id'] = $challanItem[0]->party_id;
				$this->data['ship_address'] = $challanItem[0]->ship_address;
				$this->data['delivery_pincode'] = $challanItem[0]->delivery_pincode;
				
				$this->data['ref_id'] = implode(',',array_unique(array_column($challanItem,'trans_main_id')));
                $this->data['from_entry_type'] = $postData['entry_type'];
            }
            /*if($postData['entry_type'] == 190){
                $this->data['challanItem'] = $challanItem = $this->dispatchOrder->getPendingDispatchOrders(['ref_id'=>$postData['ref_id']]);
				$this->data['party_id'] = $challanItem[0]->party_id;
                $this->data['ref_id'] = $postData['ref_id'];
                $this->data['from_entry_type'] = $postData['entry_type'];
            }*/
        }
        $this->data['entry_type'] = $this->data['entryData']->id;
        $this->data['trans_prefix'] = $this->data['entryData']->trans_prefix;
        $this->data['trans_no'] = $this->data['entryData']->trans_no;
        $this->data['trans_number'] = $this->data['trans_prefix'].$this->data['trans_no'];
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => "1,2"]);
        $this->data['itemList'] =  $challanItem;
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['transportList'] = $this->transport->getTransportList();
        $this->load->view($this->form,$this->data);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = array();

        if(empty($data['trans_no']))
            $errorMessage['trans_number'] = "DC. No. is required.";
        if(empty($data['trans_date']))
            $errorMessage['trans_date'] = "DC. Date is required.";
		if (formatDate($data['trans_date'], 'Y-m-d') < $this->startYearDate OR formatDate($data['trans_date'], 'Y-m-d') > $this->endYearDate)
			$errorMessage['trans_date'] = "Invalid Date (Out of Financial Year).";
        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party Name is required.";
        if(empty($data['itemData'])):
            $errorMessage['itemData'] = "Item Details is required.";
        else:
            $bQty = array();
              foreach($data['itemData'] as $key => $row):
                if($row['stock_eff'] == 1):
                    $batchDetail = $row['batch_detail'];
                    $batchDetail = json_decode($batchDetail,true);

                    if(!empty($row['id'])):
                        $oldItem = $this->deliveryChallan->getDeliveryChallanItem(['id'=>$row['id']]);
                    endif;

                    $batchQty = (!empty($batchDetail))?array_sum(array_column($batchDetail,'batch_qty')):0;
                    if(floatval($row['qty']) <> floatval($batchQty)):
                        $errorMessage['qty'.$key] = "Invalid Batch Qty.";
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
                                    $errorMessage['qty'.$key] = "Stock not available.".$bQty[$batchKey] .'>'. $stockQty;
                                endif;
                            endif;
                        endforeach;
                    endif;

                endif;
            endforeach;            
        endif;
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			if(empty($data['id'])){
				$data['trans_prefix'] = $this->data['entryData']->trans_prefix;
				$data['trans_no'] = $this->data['entryData']->trans_no;
				$data['trans_number'] = $data['trans_prefix'].$data['trans_no'];
			}
            $data['vou_name_l'] = $this->data['entryData']->vou_name_long;
            $data['vou_name_s'] = $this->data['entryData']->vou_name_short;
            $this->printJson($this->deliveryChallan->save($data));
        endif;
    }

    public function edit($id){
        $this->data['dataRow'] = $dataRow = $this->deliveryChallan->getDeliveryChallan(['id'=>$id,'itemList'=>1]);
        $this->data['gstinList'] = $this->party->getPartyGSTDetail(['party_id' => $dataRow->party_id]);        
        $this->data['partyList'] = $this->party->getPartyList(['party_category' => "1,2"]);
        $this->data['itemList'] = $dataRow->itemList;//$this->item->getItemList(['item_type'=>[1]]);
        $this->data['unitList'] = $this->item->itemUnits();
        $this->data['hsnList'] = $this->hsnModel->getHSNList();
        $this->data['termsList'] = $this->terms->getTermsList(['type'=>'Sales']);
        $this->data['transportList'] = $this->transport->getTransportList();
        $this->load->view($this->form,$this->data);
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->deliveryChallan->delete($id));
        endif;
    }

    public function printChallan($id){
        $this->data['dataRow'] = $dataRow = $this->deliveryChallan->getDeliveryChallan(['id'=>$id,'itemList'=>1,'packingList'=>1]);
        $this->data['partyData'] = $this->party->getParty(['id'=>$dataRow->party_id]);
        $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo($dataRow->cm_id);
        
        $logo = base_url('assets/images/logo.png');
        $this->data['letter_head'] = base_url('assets/images/letterhead_top.png');//base_url($companyData->print_header);
        
        $pdfData = $this->load->view('delivery_challan/print', $this->data, true);
        
        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
            <tr>
                <td style="width:25%;">DC. No. & Date : '.$dataRow->trans_number . ' [' . formatDate($dataRow->trans_date) . ']</td>
                <td style="width:25%;"></td>
                <td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
            </tr>
        </table>';
        
		$mpdf = new \Mpdf\Mpdf();
		$filePath = realpath(APPPATH . '../assets/uploads/delivery_challan/');
        $pdfFileName = $filePath.'/' . str_replace(["/","-"],"_",$dataRow->trans_number) . '.pdf';

        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetWatermarkImage($logo, 0.03, array(120, 120));
        $mpdf->showWatermarkImage = true;
        $mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',10,5,5,15,5,5,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);
		
		ob_clean();
		$mpdf->Output($pdfFileName, 'I');
    }

    public function getPartyChallan(){
        $data = $this->input->post();		
        $this->data['entry_type'] = (!empty($data['entry_type']) ? $data['entry_type'] : 0);
        $this->data['orderItems'] = $this->deliveryChallan->getPendingChallanItems($data);
        $this->load->view('sales_invoice/create_invoice',$this->data);
    }

    public function packingListPrint($id){
        $this->data['packingData'] = $this->deliveryChallan->getPackingItemDetail(['dc_id'=>$id]);
        $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();

        $htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
            <tr>
                <td style="width:25%;"></td>
                <td style="width:25%;"></td>
                <td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
            </tr>
        </table>';    
        $trans_number = "";  
        if(!empty($this->data['packingData'])){
            $trans_number = $this->data['packingData'][0]->trans_number;
            $pdfData = $this->load->view('delivery_challan/print_packing_list', $this->data, true);
        }else{
            $trans_number ='No_Data';
            $pdfData = '<table class="table">
                            <tr>
                                <th class="text-center" style="width:100%;"><h1>No Packing List Found</h1></th>
                            </tr>
                        </table>';
        }
        
        $mpdf = new \Mpdf\Mpdf();
		$filePath = realpath(APPPATH . '../assets/uploads/packing_list/');
        $pdfFileName = str_replace(["/","-"],"_",$trans_number) . '.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css?v='.time()));

        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showWatermarkImage = true;
		$mpdf->SetWatermarkImage($logo,0.05,array(120,45));
        $mpdf->SetHTMLFooter($htmlFooter);
		$mpdf->AddPage('P','','','','',10,5,5,15,5,5,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);				

        ob_clean();
		$mpdf->Output($pdfFileName, 'I');
    }

}
?>