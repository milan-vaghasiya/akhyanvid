<?php
class StoreReport extends MY_Controller{
    public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Store Report";
		$this->data['headData']->controller = "reports/storeReport";
    }

    public function stockRegister(){
		$this->data['headData']->pageTitle = "STOCK REGISTER";
        $this->data['headData']->pageUrl = "reports/storeReport/stockRegister";
        $this->data['pageHeader'] = 'STOCK REGISTER';
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>1]);
        $this->load->view("report/store_report/item_stock",$this->data);
    }

    public function getStockRegisterData(){
        $data = $this->input->post();
        $stockData = $this->storeReport->getStockRegisterData($data);

        $tbody=''; $i=1;
        if (!empty($stockData)) {
            foreach ($stockData as $row) {
                $batch_qty = floatVal($row->stock_qty);
                if(floatVal($row->stock_qty) > 0){                
                    $locationId = (isset($data['item_type']) && $data['item_type'] == 0) ? "/".$this->CUT_STORE->id : '';
                    $batch_qty = '<a href="'.base_url("reports/storeReport/batchStockHistory/".$row->item_id.$locationId).'" target="_blank" datatip="Ledger" flow="left">'.floatVal($row->stock_qty).'</a>';
                }
                
                $itemName = '<a href="'.base_url("reports/storeReport/itemHistory/".encodeURL($row->item_id)).'" target="_blank" datatip="History" flow="left">'.$row->item_name.'</a>';
                $tbody .= '<tr>
                    <td class="text-center">'.$i++.'</td>
                    <td class="text-left">'.$itemName.'</td>
                    <td class="text-center">'.$row->uom.'</td>
                    <td class="text-right">'.$batch_qty.'</td>
                </tr>';
            }
        }		
        $this->printJson(['status'=>1,'tbody'=>$tbody]);
    }

    public function itemHistory($item_id=""){
        $this->data['item_id'] = $item_id = decodeURL($item_id);
		$this->data['headData']->pageTitle = "Item History";
        $this->data['itemData'] = $this->item->getItem(['id'=>$item_id]);
        $this->data['from_date'] = $this->startYearDate;
        $this->data['to_date'] = $this->endYearDate;
        $this->load->view('report/store_report/item_history',$this->data);
    }
	
    public function getItemHistory(){
		$data = $this->input->post();
        $itemData = $this->item->getItem(['id'=>$data['item_id']]);
		
		$itemSummary = $this->storeReport->getItemSummary($data);
        $itemHistory = $this->storeReport->getItemHistory($data);

        $thead = '<tr class="text-center">
            <th colspan="5" class="text-left">'.((!empty($itemData))?$itemData->item_name:"Item History").'</th>
            <th colspan="2" class="text-right">Op. Stock : '.floatVal($itemSummary->op_stock_qty).'</th>
        </tr>
        <tr>
            <th style="min-width:25px;">#</th>
            <th style="min-width:100px;">Trans. Type</th>
            <th style="min-width:100px;">Trans. No.</th>
            <th style="min-width:50px;">Trans. Date</th>
            <th style="min-width:50px;">Inward</th>
            <th style="min-width:50px;">Outward</th>
            <th style="min-width:50px;">Balance</th>
        </tr>';
		
        $i=1; $tbody =""; $tfoot=""; $balanceQty = $itemSummary->op_stock_qty;
        foreach($itemHistory as $row):  
            $balanceQty += $row->qty * $row->p_or_m;     

			$trans_type = ($row->p_or_m == 1) ? 'Inward To' : 'Out from';
			$row->ref_no = (!empty($row->ref_no) ? $row->ref_no : '');
			$row->emp_name = (!empty($row->emp_name) ? '<br>'.$row->emp_name : '<br> Admin');
			
            $tbody .= '<tr>
                <td>' . $i++ . '</td>
                <td>'.$trans_type.'</td>
                <td>'.(empty($data['stock_type']) ? $row->ref_no : '-').'</td>
                <td>'.(empty($data['stock_type']) ? formatDate($row->trans_date).$row->emp_name : '-').'</td>
                <td>'.floatVal($row->in_qty).'</td>
                <td>'.floatVal($row->out_qty).'</td>
                <td>'.floatVal($balanceQty).'</td>
            </tr>';
        endforeach;

        $tfoot .= '<tr>
            <th colspan="4" class="text-right">Cl. Stock</th>
            <th>' .floatVal($itemSummary->in_stock_qty). '</th>
            <th>' .floatVal($itemSummary->out_stock_qty). '</th>
            <th>' .floatVal($itemSummary->cl_stock_qty). '</th>
        </tr>';

        $this->printJson(['status'=>1,'thead'=>$thead,'tbody'=>$tbody,'tfoot'=>$tfoot]);
    }

    public function batchStockHistory($item_id="",$location_id=""){
        $this->data['headData']->pageTitle = "Stock Ledger";
        $this->data['pageHeader'] = 'STOCK LEDGER';
        $this->data['itemData'] = $this->item->getItem(['id'=>$item_id]);
        $this->data['location_id'] = $location_id;
        $this->load->view('report/store_report/stock_history',$this->data);
    }

    public function getBatchStockHistory(){
        $data = $this->input->post();
        $data['stock_required'] = 1;
        $data['group_by'] = "stock_trans.location_id,stock_trans.batch_no";
        $data['supplier'] = 1;
        $stockHistory = $this->itemStock->getItemStockBatchWise($data);
        
        $i=1; $tbody =""; 
        foreach($stockHistory as $row):  
			$tbody .= '<tr>
                <td>' . $i++ . '</td>
                <td>'.$row->store_name.' - '.$row->location.'</td>
                <td>'.$row->batch_no.'</td>
                <td>'.($row->party_name ?? '').'<br>'.$row->hsn_code.'</td>
                <td>'.$row->qty.' ('.$row->uom.')</td>
            </tr>';
        endforeach;

        $this->printJson(['status'=>1,'tbody'=>$tbody]);
    }
}
?>