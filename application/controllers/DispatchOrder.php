<?php
class DispatchOrder extends MY_Controller{
    private $index = "dispatch_order/index";
    private $form = "dispatch_order/form";
    private $packingLinkForm = "dispatch_order/packing_link_form";
    private $finalPackingForm = "dispatch_order/pallet_packing_form";

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Dispatch Order";
		$this->data['headData']->controller = "dispatchOrder";        
        $this->data['headData']->pageUrl = "dispatchOrder";
        $this->data['entryData'] = $this->transMainModel->getEntryType(['controller'=>'dispatchOrder']);
    }

    public function index(){
        $this->data['headData']->pageTitle = "Dispatch Order";
        $this->data['tableHeader'] = getSalesDtHeader("pendingSO");
        //$this->data['tableHeader'] = getSalesDtHeader("dispatchOrder");
        $this->load->view($this->index,$this->data);
    }

	public function getDTRows($status = 3){
        $data = $this->input->post();
        $data['status'] = $status;
        
		if($status == 3):
		    $result = $this->dispatchOrder->pendingSODTRows($data);
        else:
            $result = $this->dispatchOrder->getDTRows($data);
        endif;
		
        $sendData = array();$i=($data['start']+1);
		
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $row->status = $status;
			$row->entry_type =  $this->data['entryData']->id;
            if($status == 3):
                $sendData[] = getPendingSOData($row);
            else:
                $sendData[] = getDispatchOrderData($row);
			endif;
        endforeach;
		
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addDispatchOrder(){
        $data = $this->input->post();
        $this->data['add_item'] = 0;
        $this->data['order_prefix'] = "DO".n2y(getFyDate("Y"));
        $this->data['order_no'] = $this->transMainModel->getNextNo(['no_column'=>'order_no','tableName'=>'dispatch_order','condition'=>'order_date >= "'.$this->startYearDate.'" AND order_date <= "'.$this->endYearDate.'"']);
        $this->data['order_number'] = $this->data['order_prefix'].sprintf("%04d",$this->data['order_no']);
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>"1,2"]);
        $this->data['party_id'] = (!empty($data['party_id']))?$data['party_id']:"";
        $this->load->view($this->form,$this->data);
    }

    public function addDispatchOrderItem(){
        $data = $this->input->post();
        $this->data['order_prefix'] = $data['order_prefix'];
        $this->data['order_no'] = $data['order_no'];
        $this->data['order_number'] = $data['order_number'];
        $this->data['order_date'] = $data['order_date'];
        $this->data['party_id'] = $data['party_id'];
        $this->data['add_item'] = 1;
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>"1,2"]);
        $this->load->view($this->form,$this->data);
    }

    public function getPartyOrderItems(){
        $data = $this->input->post();
        $data['dispatch_order'] = 1;
        $data['stock_data'] = 1;
        $result = $this->salesOrder->getPendingOrderItems($data);
        $tbody = '';$i=1;
        foreach($result as $row):
            $tbody .= '<tr>
                <td>'.$i.'</td>
                <td>'.$row->trans_number.'</td>
                <td>'.formatDate($row->trans_date).'</td>
                <td>'.((!empty($row->cod_date))?formatDate($row->cod_date):"").'</td>
                <td>'.$row->item_name.'</td>
                <td>'.floatval($row->qty).'</td>
                <td>'.floatval($row->pending_qty).'</td>
                <td>'.floatval($row->pending_do_qty).'</td>
                <td>'.floatval($row->stock_qty).'</td>
                <td>
                    <input type="hidden" name="itemData['.$i.'][id]" value="">
                    <input type="hidden" name="itemData['.$i.'][so_id]" value="'.$row->trans_main_id.'">
                    <input type="hidden" name="itemData['.$i.'][so_trans_id]" value="'.$row->id.'">
                    <input type="hidden" name="itemData['.$i.'][item_id]" value="'.$row->item_id.'">
                    <input type="text" name="itemData['.$i.'][order_qty]" data-stock_qty="'.$row->stock_qty.'"  data-row_id="'.$i.'" class="form-control floatOnly checkStock" value="">
                    <div class="error order_qty_'.$i.'"></div>
                    <div class="error stock_qty_'.$i.'"></div>
                </td>
            </tr>';
            $i++;
        endforeach;

        $this->printJson(['status'=>1,'tbody'=>$tbody]);
    }

    public function save(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Customer Name is required.";
        if(empty($data['order_date']))
            $errorMessage['order_date'] = "Order Date is required.";
        if(empty($data['itemData'])):
            $errorMessage['orderError'] = "Atleast one order is required.";
        else:
            if(empty(array_sum(array_column($data['itemData'],'order_qty')))):
                $errorMessage['orderError'] = "DO. Qty is required.";
            endif;

            foreach($data['itemData'] as $key=>$row):
                if(floatval($row['order_qty']) > 0):
                    $orderItem = $this->salesOrder->getOrderItem(['id'=>$row['so_trans_id'],'dispatch_order'=>1]);
                    if(floatval($row['order_qty']) > $orderItem->pending_do_qty):
                        $errorMessage['order_qty_'.$key] = "Invalid Qty.";
                    endif;
                    $stockData = $this->itemStock->getItemStockBatchWise(["item_id" => $row['item_id'], 'location_id'=>$this->DISP_STORE->id, 'group_by'=>'location_id','single_row'=>1]);
                    if($row['order_qty'] > $stockData->qty):
                        $errorMessage['stock_qty_'.$key] = "Stock Not Available".$stockData->qty;
                    endif;
                endif;
            endforeach;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            if(empty($data['add_item'])):
                $data['order_prefix'] = "DO".n2y(getFyDate("Y"));
                $data['order_no'] = $this->transMainModel->getNextNo(['no_column'=>'order_no','tableName'=>'dispatch_order','condition'=>'order_date >= "'.$this->startYearDate.'" AND order_date <= "'.$this->endYearDate.'"']);
                $data['order_number'] = $data['order_prefix'].sprintf("%04d",$data['order_no']);
            endif;
            unset($data['add_item']);

            $data['entry_type'] = $this->data['entryData']->id;

            $this->printJson($this->dispatchOrder->save($data));
        endif;
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->dispatchOrder->delete($id));
        endif;
    }

    public function linkPacking(){
        $data = $this->input->post();
        $this->data['orderDetail'] = $orderDetail = $this->dispatchOrder->getDispatchOrderItem($data);
        $this->data['orderItemList'] = $this->dispatchOrder->getDispatchOrderItemList(['order_number'=>$orderDetail->order_number]);
        $this->load->view($this->packingLinkForm,$this->data);
    }

    public function getDispatchOrderItem(){
        $data = $this->input->post();
        $result = $this->dispatchOrder->getDispatchOrderItem($data);
        $this->printJson(['status'=>1,'orderItemDetail'=>$result]);
    }

    public function getItemStock(){
        $data = $this->input->post();
        $batchData = $this->itemStock->getItemStockBatchWise(["item_id" => $data['item_id'], 'location_id'=>$this->RTD_STORE->id, 'stock_required'=>1, 'group_by'=>'location_id,size']);

        $tbody = '';$i=1;
        if(!empty($batchData)):
            foreach($batchData as $row):
                $batchId = $row->location_id.$row->item_id;
                $location_name = '['.$row->store_name.'] '.$row->location;
                $row->size = (!empty(floatval($row->size)))?$row->size:1;

                $tbody .= '<tr id="'.$batchId.'" data-ind="'.$i.'">
                    <td>['.$row->store_name.'] '.$row->location.'</td>
                    <td>
                        '.floatval(($row->qty / $row->size)).'
                        <br>
                        <small>('.floatval(($row->qty / $row->size)).' x '.floatval($row->size).' = '.floatval($row->qty).')</small>
                    </td>
                    <td>
                        <input type="text" id="box_qty_'.$i.'" class="form-control floatOnly calculateBoxQty" data-srno="'.$i.'" value="">
                        <input type="hidden" name="batchDetail['.$i.'][batch_qty]" class="calculateBatchQty" id="batch_qty_'.$i.'" value="">
                        <input type="hidden" name="batchDetail['.$i.'][size]" id="size_'.$i.'" value="'.$row->size.'">
                        <input type="hidden" name="batchDetail['.$i.'][location_id]" id="location_id_'.$i.'" value="'.$row->location_id.'">
                        <input type="hidden" name="batchDetail['.$i.'][batch_id]" id="batch_id_'.$i.'" value="'.$batchId.'">
                        <input type="hidden" name="batchDetail['.$i.'][location_name]" id="location_name_'.$i.'" value="'.$location_name.'">
                        <input type="hidden" name="batchDetail['.$i.'][batch_stock]" id="batch_stock_'.$i.'" value="'.floatVal($row->qty).'">
                        <div class="error batch_qty_'.$i.'"></div>
                    </td>
                </tr>';
                $i++;
            endforeach;
        endif;

        if(empty($tbody)):
            $tbody = '<tr>
                <td colspan="4" class="text-center">No data available in table</td>
            </tr>';
        endif;

        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
    }

    public function getDispatchOrderTransaction(){
        $data = $this->input->post();
        
        $batchData = $this->itemStock->getItemStockBatchWise(["item_id" => $data['item_id'], 'main_ref_id'=>$data['id'], 'entry_type' => $this->data['entryData']->id, "remark"=> $data['order_number'].'-'.$data['id'], 'location_id'=>$this->DISP_STORE->id, 'group_by'=>'stock_trans.id']);

        $tbody = '';$i=1;
        if(!empty($batchData)):
            foreach($batchData as $row):
                $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Unlink','fndelete':'removePackingLink','res_function':'resRemovePackingLink'}";
                $tbody .= '<tr>
                    <td>'.$i.'</td>
                    <td>['.$row->store_name.'] '.$row->location.'</td>
                    <td>
                        '.floatval(($row->qty / $row->size)).'
                    </td>
                    <td>
                        '.floatval($row->qty).'
                    </td>
                    <td class="text-center">
                        <button type="button" onclick="trash('.$deleteParam.');" class="btn btn-outline-danger waves-effect waves-light btn-delete permission-remove"><i class="mdi mdi-trash-can-outline"></i></button>
                    </td>
                </tr>';
                $i++;
            endforeach;
        endif;

        if(empty($tbody)):
            $tbody = '<tr>
                <td colspan="6" class="text-center">No data available in table</td>
            </tr>';
        endif;

        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
    }

    public function savePackingLinkDetails(){
        $data = $this->input->post();
        $errorMessage = [];

        if(empty($data['batchDetail'])):
            $errorMessage['batchDetail'] = "Batch Detail is required.";
        else:
            foreach($data['batchDetail'] as $key => $row):   
                $postData = ['location_id' => $row['location_id'], 'size' => $row['size'],'item_id' => $data['item_id'],'stock_required'=>1,'single_row'=>1];                    
                $stockData = $this->itemStock->getItemStockBatchWise($postData);

                $stockQty = (!empty($stockData->qty))?$stockData->qty:0;

                if(floatval($row['batch_qty']) > floatval($stockQty)):
                    $errorMessage['batch_qty_'.$key] = "Stock not avalible.";
                endif;
            endforeach;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->dispatchOrder->savePackingLinkDetails($data));
        endif;
    }

    public function removePackingLink(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->dispatchOrder->removePackingLink($id));
        endif;
    }

    public function getCartoonNoList(){
        $data = $this->input->post();
        $result = $this->dispatchOrder->getCartoonNoList($data);

        $options = '<option value="">New</option>';
        foreach($result as $row):
            $options .= '<option value="'.$row->cartoon_no.'" data-box_id="'.$row->box_id.'" data-box_weight="'.floatval($row->box_weight).'">'.$row->cartoon_no.'</option>';
        endforeach;

        $this->printJson(['status'=>1,'cartoonNoList'=>$options]);
    }

    public function getLinkedItemList(){
        $data = $this->input->post(); 
        $result = $this->dispatchOrder->getLinkedItemList($data);       

        $options = '<option value="">Select Item</option>';
        foreach($result as $row):
            //if(floatval($row->pending_qty) > 0):
				$row->item_name = (!empty($row->item_code)) ? '['.$row->item_code.'] '.$row->item_name : $row->item_name;
                $options .= '<option value="'.$row->id.'" data-do_id="'.$row->main_ref_id.'" data-pending_qty="'.$row->pending_qty.'">'.$row->item_name.' </option>';
            //endif;
        endforeach;

        $this->printJson(['status'=>1,'itemList'=>$options]);
    }

    public function finalPacking(){
        $data = $this->input->post();
        $this->data['do_no'] = $data['order_no'];
        $this->data['order_number'] = $data['order_number'];
        $this->data['packingMaterialList'] = $this->item->getItemList(['item_type'=>"2"]);
        $this->data['orderItems'] = $this->dispatchOrder->getDispatchOrderItemList(['order_number'=>$data['order_number']]);
        $this->load->view($this->finalPackingForm,$this->data);
    }

    function getAnnexureDetail(){
        $data = $this->input->post();
        $result = $this->dispatchOrder->getAnnexureDetail($data);

        $tbody = '';
        foreach($result as $row):
            $deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Unlink','fndelete':'removeAnnexureItem','res_function':'resRemoveAnnexureItem'}";
            
            $tbody .= '<tr>
                <td>'.$row->cartoon_no.'</td>
                <td colspan="2">'.$row->cartoon_name.'</td>
                <td>'.$row->cartoon_weight.'</td>
                <td>'.$row->item_name.'</td>
                <td>'.floatval($row->box_qty).'</td>
                <td class="text-center">
                    <button type="button" onclick="trash('.$deleteParam.');" class="btn btn-outline-danger btn-sm waves-effect waves-light btn-delete permission-remove"><i class="mdi mdi-trash-can-outline"></i></button>
                </td>
            </tr>';
        endforeach;

        if(empty($tbody)):
            $tbody = '<tr>
                <td colspan="8" class="text-center">No data available in table</td>
            </tr>';
        endif;

        $this->printJson(['status'=>1,'tbodyData'=>$tbody]);
    }

    public function saveFinalPacking(){
        $data = $this->input->post();
        $errorMessage = [];
        if(empty($data['box_id']))
            $errorMessage['box_id'] = "Packing Material is required.";
        if(empty(floatval($data['cartoon_qty'])))
            $errorMessage['cartoon_qty'] = "Cartoon Qty is required.";
        if(empty(floatval($data['box_weight'])))
            $errorMessage['box_weight'] = "Cartoon Weight is required.";
        if(empty($data['ref_id']))
            $errorMessage['ref_id'] = "Item Name is required.";
        if(empty(floatval($data['box_qty']))):
            $errorMessage['box_qty'] = "Box Qty. is required.";
        else:
            $itemDetail = $this->dispatchOrder->getLinkedItemList(['order_number'=>$data['order_number'],'ref_id'=>$data['ref_id']]);
            if(floatval(($data['box_qty'] * $data['cartoon_qty'])) > floatval($itemDetail->pending_qty)):
                $errorMessage['box_qty'] = "Invalid Box Qty.";
            endif;
        endif;

        if(empty($data['cartoon_no'])):
            if(!empty($data['box_id'])):
                $postData = ['location_id' => $this->PACKING_STORE->id,'item_id' => $data['box_id'],'stock_required'=>1,'single_row'=>1];                    
                $stockData = $this->itemStock->getItemStockBatchWise($postData);  
    
                $stockQty = (!empty($stockData->qty))?$stockData->qty:0;
                $no_of_box = floatval($data['cartoon_qty']);
                if(floatval($no_of_box) > floatval($stockQty)):
                    $errorMessage['box_id'] = "Stock not avalible.";
                endif;
            endif;
        endif;

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->dispatchOrder->saveFinalPacking($data));
        endif;
    }

    public function removeAnnexureItem(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->dispatchOrder->removeAnnexureItem($id));
        endif;
    }

    public function printPackingList($order_no){
        $annexureDetail = $this->dispatchOrder->getAnnexureDetail(['order_number'=>$order_no,'print'=>'1']);
        if(!empty($annexureDetail)):
            $cartoonDetail = array_reduce($annexureDetail, function($itemData, $row) {
                $itemData[$row->cartoon_no]['cartoon_no'] = $row->cartoon_no;
                $itemData[$row->cartoon_no]['cartoon_size'] = $row->cartoon_size;
                $itemData[$row->cartoon_no]['cartoon_weight'] = $row->cartoon_weight;
                $itemData[$row->cartoon_no]['item'][] = $row->cartoon_no.'-'.$row->item_id;                
                return $itemData;
            }, []);

            $itemDetail = array_reduce($annexureDetail, function($itemData, $row) {
                $itemData[$row->cartoon_no.'-'.$row->item_id][] = $row;
                return $itemData;
            }, []);

            $tbody = '';$cartoonHtml = $itemHtml = ''; $totalNetWeight = $totalPackingWeight = $totalGrossWeight = $totalCartoonWeight = $totalPackingGrossWeight = 0;
            foreach($cartoonDetail as $cartoon_no => $cartoon):

                $cartoonCount = COUNT($cartoon['item']);
                $cartoon['item'] = array_unique($cartoon['item']);

                $cartoonHtml .= '<tr>';
                    $cartoonHtml .= '<td rowspan="'.$cartoonCount.'">'.$cartoon['cartoon_no'].'</td>';
                    $cartoonHtml .= '<td rowspan="'.$cartoonCount.'">'.$cartoon['cartoon_size'].'</td>';
                
                    $cartoonItemHtml = ''; $itemHtml = ''; $grossWeight = 0; $c=1;
                    foreach($cartoon['item'] as $item):
                        
                        $i=1;$itemCount = COUNT($itemDetail[$item]);                    

                        foreach($itemDetail[$item] as $row):
                            $row->total_qty = ($row->size * $row->box_qty);
                            $row->net_weight = round(($row->total_qty * $row->wt_pcs),3);
                            $row->packing_weight = round(($row->box_weight * $row->total_qty),3);
                            $row->gross_weight = round(($row->net_weight + $row->packing_weight),3);

                            if($c == 1):
                                $cartoonHtml .= '<td rowspan="'.$itemCount.'">'.$row->item_name.'</td>';
                                $cartoonHtml .= '<td class="text-right">'.floatval($row->size).'</td>';
                                $cartoonHtml .= '<td class="text-right">'.floatval($row->box_qty).'</td>';
                                $cartoonHtml .= '<td class="text-right">'.floatval($row->total_qty).'</td>';
                                $cartoonHtml .= '<td class="text-right">'.sprintf("%.3f",$row->wt_pcs).'</td>';
                                $cartoonHtml .= '<td class="text-right">'.sprintf("%.3f",$row->net_weight).'</td>';
                                $cartoonHtml .= '<td class="text-right">'.sprintf("%.3f",$row->packing_weight).'</td>';
                                $cartoonHtml .= '<td class="text-right">'.sprintf("%.3f",$row->gross_weight).'</td>';
                                $c++;
                            else:                                
                                $itemHtml .= '<tr>';
                                    if($i == 1):
                                        $itemHtml .= '<td rowspan="'.$itemCount.'">'.$row->item_name.'</td>';
                                    endif;
                                    $itemHtml .= '<td class="text-right">'.floatval($row->size).'</td>';
                                    $itemHtml .= '<td class="text-right">'.floatval($row->box_qty).'</td>';
                                    $itemHtml .= '<td class="text-right">'.floatval($row->total_qty).'</td>';
                                    $itemHtml .= '<td class="text-right">'.sprintf("%.3f",$row->wt_pcs).'</td>';
                                    $itemHtml .= '<td class="text-right">'.sprintf("%.3f",$row->net_weight).'</td>';
                                    $itemHtml .= '<td class="text-right">'.sprintf("%.3f",$row->packing_weight).'</td>';
                                    $itemHtml .= '<td class="text-right">'.sprintf("%.3f",$row->gross_weight).'</td>';
                                $itemHtml .= '</tr>';                                
                            endif;
                            $i++;
                            $grossWeight += $row->gross_weight;

                            $totalNetWeight += $row->net_weight;
                            $totalPackingWeight += $row->packing_weight;
                            $totalGrossWeight += $row->gross_weight;                            
                        endforeach;
                    endforeach;                
                    
                    $cartoonHtml .= '<td rowspan="'.$cartoonCount.'" class="text-right">'.sprintf("%.3f",$cartoon['cartoon_weight']).'</td>';
                    $cartoonHtml .= '<td rowspan="'.$cartoonCount.'" class="text-right">'.sprintf("%.3f",($cartoon['cartoon_weight'] + $grossWeight)).'</td>';
                $cartoonHtml .= '</tr>';
                $cartoonHtml .= $itemHtml;

                $totalCartoonWeight += $cartoon['cartoon_weight'];
                $totalPackingGrossWeight += $cartoon['cartoon_weight'] + $grossWeight;
            endforeach;

            $tbody = $cartoonHtml;

            $pdfData = '<table class="table item-list-bb" border="1">
                <thead>
                    <tr>
                        <th colspan="2">
                            '.$order_no.'
                        </th>
                        <th colspan="10">
                            Packing Annexure
                        </th>
                    </tr>
                    <tr>
                        <th>Cartoon No.</th>
                        <th style="width:10%;">Box Size (cm)</th>
                        <th>Item Name</th>
                        <th>Qty Per Box (Nos)</th>
                        <th>Total Box (Nos)</th>
                        <th>Total Qty. (Nos)</th>
                        <th>Net Weight Per Piece (kg)</th>
                        <th>Total Net Weight (kg)</th>
                        <th>Packing Weight (kg)</th>
                        <th>Item Gross Weight (kg)</th>
                        <th>Wooden Box Weight (kg)</th>
                        <th>Packing Gross Weight (kg)</th>
                    </tr>
                </thead>
                <tbody>'.$tbody.'</tbody>
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-right">Total</th>
                        <th class="text-right">'.sprintf("%.3f",$totalNetWeight).'</th>
                        <th class="text-right">'.sprintf("%.3f",$totalPackingWeight).'</th>
                        <th class="text-right">'.sprintf("%.3f",$totalGrossWeight).'</th>
                        <th class="text-right">'.sprintf("%.3f",$totalCartoonWeight).'</th>
                        <th class="text-right">'.sprintf("%.3f",$totalPackingGrossWeight).'</th>
                    </tr>
                </tfoot>
            </table>';
        else:
            $result = $this->dispatchOrder->getPackingListDetail(['order_number'=>$order_no]);
            
            $tbody = "";$totalBox = $totalNetWeight = $totalGrossWeight = 0;
            foreach($result as $row):
                $boxQty = floatval(($row->qty / $row->size));
                $netWeight = round(($row->qty * $row->wt_pcs),3);
                $boxWeight = round(($boxQty * $row->box_weight),3);
                $grossWeight = round(($netWeight + $boxWeight),3);

                $tbody .= '<tr>
                    <td class="text-center">'.(($boxQty > 1)?"1 to ".$boxQty:"1").'</td>
                    <td class="text-center">'.$row->item_name.'</td>
                    <td class="text-center">'.$boxQty.'</td>
                    <td class="text-center">'.sprintf("%0.3f",$netWeight).'</td>
                    <td class="text-center">'.sprintf("%0.3f",$grossWeight).'</td>
                    <td class="text-center">'.$row->box_size.'</td>
                </tr>';

                $totalBox += $boxQty;
                $totalNetWeight += $netWeight;
                $totalGrossWeight += $grossWeight;
            endforeach;

            $pdfData = '<table class="table item-list-bb">
                <thead>
                    <tr>
                        <th colspan="6">Packing List</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Box Qty</th>
                        <th>Net Weight</th>
                        <th>Gross Weight</th>
                        <th>Box Size</th>
                    </tr>
                </thead>
                <tbody>'.$tbody.'</tbody>
                <tfoot>
                    <tr>
                        <th class="text-right" colspan="2">Total</th>
                        <th>'.$totalBox.'</th>
                        <th>'.sprintf("%0.3f",$totalNetWeight).'</th>
                        <th>'.sprintf("%0.3f",$totalGrossWeight).'</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>';
        endif;

        //print_r($pdfData);exit;

        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [100,50]]); // Landscap
        $pdfFileName ='pack' . '.pdf';
        $stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetProtection(array('print'));
        $mpdf->AddPage('P','','','','',10,5,5,15,5,5,'','','','','','','','','','A4-P');
        $mpdf->WriteHTML($pdfData);
        $mpdf->Output($pdfFileName, 'I');
    }

    public function getPartyOrders(){
        $data = $this->input->post();
        $this->data['entry_type'] = (!empty($data['entry_type']) ? $data['entry_type'] : 0);         
        $this->data['orderItems'] = $this->dispatchOrder->getPendingDispatchOrders(['party_id'=>$data['party_id'],'group_by'=>'order_number']);
        $this->load->view('dispatch_order/create_invoice',$this->data);
    }
}
?>