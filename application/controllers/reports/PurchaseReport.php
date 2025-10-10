<?php
class PurchaseReport extends MY_Controller
{
    private $purchase_monitoring = "reports/purchase_report/purchase_monitoring";  
	private $purchase_inward = "reports/purchase_report/purchase_inward";	
	private $enquiry_register = "reports/purchase_report/enquiry_register";
	private $supplier_wise_item = "reports/purchase_report/supplier_wise_item";
	private $market_trend = "reports/purchase_report/market_trend";

    public function __construct(){
		parent::__construct();
		$this->isLoggedin();
        $this->data['headData']->pageTitle = "PURCHASE REPORT";
		$this->data['headData']->controller = "reports/purchaseReport";
	}
	
    public function purchaseMonitoring(){
        $this->data['headData']->pageTitle = "PURCHASE MONITORING REGISTER";
        $this->data['itemTypeData'] = $this->itemCategory->getCategoryList(['final_category'=>0]);
		$this->data['partyList'] = $this->party->getPartyList(['party_category'=>"1,2,3"]);
        $this->load->view($this->purchase_monitoring,$this->data);
    }

    public function getPurchaseMonitoring(){
        $data = $this->input->post();
        $errorMessage = array();
		if($data['to_date'] < $data['from_date'])
			$errorMessage['toDate'] = "Invalid date.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $purchaseData = $this->purchaseReport->getPurchaseOrderMonitoring($data);
            $tbody="";$i=1;   $blankInTd='';
            $blankInTd='<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>';
         
            if(!empty($purchaseData)):
            foreach($purchaseData as $row):
				$data['item_id'] = $row->item_id; 
                $data['po_id'] = $row->trans_main_id;
                $data['po_trans_id'] = $row->id;
				$receiptData = $this->purchaseReport->getPurchaseReceipt($data);
				$receiptCount = count($receiptData);

				$balanceQty = floatval($row->qty);
				
				$tbody .= '<tr>
					<td class="text-center">'.$i++.'</td>
					<td>'.($row->trans_number).'</td>
					<td>'.formatDate($row->trans_date).'</td>
					<td>'.$row->party_name.'</td>
					<td>'.($row->item_name).'</td>
					<td>'.floatval($row->qty).' '.$row->unit_name.'</td>
					<td>'.round($row->price,2).'</td>';
      
					if($receiptCount > 0):
						$j=1;
						foreach($receiptData as $recRow):
							$balanceQty -= floatval($recRow->qty);
							$totalAmt = $recRow->qty * $row->price;
							$gi_no = (!empty($recRow->trans_no))?$recRow->trans_prefix.sprintf("%04d",$recRow->trans_no):'';
							
							$tbody.='<td>'.formatDate($recRow->trans_date).'</td>
                                <td>'.$recRow->trans_number.'</td>
                                <td>'.formatDate($recRow->inv_date).'</td>
                                <td>'.$recRow->inv_no.'</td>
                                <td>'.floatval($recRow->qty).' '.$row->unit_name.'</td> 
                                <td>'.$balanceQty.' '.$row->unit_name.'</td>
                                <td>'.floatval($row->price).'</td>
                                <td>'.floatval($totalAmt).'</td>';

                            if($j != $receiptCount){$tbody.='</tr><tr><td>'.$i++.'</td>'.$blankInTd;}
							$j++;
						endforeach;
					else:
						$tbody.='<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
					endif;
				$tbody.='</tr>';
            endforeach;
            endif;
            $this->printJson(['status'=>1, 'tbody'=>$tbody]);
        endif;
    }

	public function purchaseInward(){
        $this->data['headData']->pageTitle = "PURCHASE INWARD REPORT";
        $this->data['pageHeader'] = 'PURCHASE INWARD REPORT';
        $this->data['itemTypeData'] = $this->itemCategory->getCategoryList(['final_category'=>0]);
        $this->load->view($this->purchase_inward,$this->data);
    }

	public function getPurchaseInward(){
        $data = $this->input->post();
        $inwardData = $this->purchaseReport->getPurchaseInward($data);
        $i=1; $tbody=''; $totalAmt=0; $poNo=''; $tfoot = ''; $totalQty=0; $totalItemPrice=0; $total=0;
        if(!empty($inwardData)){
            foreach($inwardData as $row):
                $totalAmt = ($row->qty * $row->price);
                $tbody .= '<tr>
                    <td>'.$i++.'</td>
                    <td>'.$row->trans_number.'</td>
                    <td>'.formatDate($row->trans_date).'</td>
                    <td>'.$row->po_number.'</td>
                    <td>'.formatDate($row->po_date).'</td>
                    <td>'.$row->party_name.'</td>
                    <td>'.$row->item_name.'</td>
                    <td>'.floatVal($row->qty).' '.$row->unit_name.'</td> 
                    <td>'.floatVal($row->price).'</td>
                    <td>'.$totalAmt.'</td>
                </tr>';
                $totalQty += $row->qty; $totalItemPrice += $row->price; $total += $totalAmt;
            endforeach;
        } 
        $tfoot = '<tr>
                <th colspan="7">Total</th>
                <th>'.(!empty($totalQty) ? round($totalQty) : '').'</th>
                <th>'.(!empty($totalItemPrice) ? round($totalItemPrice, 2) : '').'</th>
                <th>'.(!empty($total) ? round($total, 2) : '').'</th>
            </tr>';
        $this->printJson(['status'=>1, 'tbody'=>$tbody, 'tfoot'=>$tfoot]);
    }

	public function enquiryRegister(){
        $this->data['headData']->pageTitle = "ENQUIRY REGISTER";
        $this->data['itemList'] = $this->item->getItemList();
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>2]);
        $this->load->view($this->enquiry_register,$this->data);
    }

    public function getEnquiryRegister(){
        $data = $this->input->post();
        $errorMessage = array();
		if($data['to_date'] < $data['from_date'])
			$errorMessage['toDate'] = "Invalid date.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $purchaseData = $this->purchaseReport->getEnquiryRegisterData($data);
            $tbody="";$i=1; 
            if(!empty($purchaseData)):
                foreach($purchaseData as $row):
                    $tbody .= '<tr>
                        <td class="text-center">'.$i++.'</td>
                        <td>'.$row->trans_number.'</td>
                        <td>'.formatDate($row->trans_date).'</td>
                        <td>'.$row->party_name.'</td>
                        <td>'.$row->item_name.'</td>
                        <td>'.floatval($row->qty).' '.$row->unit_name.'</td>
                        <td>'.$row->quote_no.'</td>
                        <td>'.formatDate($row->quote_date).'</td>
                        <td>'.floatval($row->qtnQty).' '.$row->unit_name.'</td> 
                        <td>'.$row->price.'</td>
                        <td>'.$row->feasible.'</td>
                        <td>'.$row->lead_time.'</td>
                        <td>'.$row->emp_name.'<br>'.formatDate($row->approve_date).'</td>';
                    $tbody.='</tr>';
                endforeach;
            endif;
            $this->printJson(['status'=>1, 'tbody'=>$tbody]);
        endif;
    }

    /* Supplier Wise Item Report Created By Rashmi : 15/05/2024 */
    public function supplierWiseItem(){
        $this->data['headData']->pageTitle= 'SUPPLIER WISE ITEM & ITEM WISE SUPPLIER REPORT';      
        $this->data['itemList'] = $this->item->getItemList(['item_type'=>'2,3']);
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>2]);
        $this->load->view($this->supplier_wise_item,$this->data);
    }

	/* Created By Rashmi : 15/05/2024 */
    public function getSupplierWiseItem(){
        $data = $this->input->post();
        $purchaseData = $this->purchaseReport->getSupplierWiseItem($data);
        $tbody="";$i=1;
        foreach($purchaseData as $row):
			$tbody .= '<tr>
				<td>'.$i++.'</td>
				<td>'.$row->party_name.'</td>
				<td>' . (!empty($row->item_code) ? '[' . $row->item_code . '] ' . $row->item_name : $row->item_name) . '</td>';
			$tbody.='</tr>';
        endforeach;
        $this->printJson(['status'=>1, 'tbody'=>$tbody]);
    }

	public function marketTrend(){
        $this->data['headData']->pageTitle = "ENQUIRY REGISTER";
        $this->load->view($this->market_trend,$this->data);
    }
	
	public function getTrendMarket(){
        $data = $this->input->post();
        $errorMessage = array();
		if(empty($data['to_date']))
			$errorMessage['toDate'] = "Invalid date.";
		
		if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$marketData = $this->purchaseReport->getMarketTrendData($data);
			$tbody="";$i=1; 
			if(!empty($marketData)):
				foreach($marketData as $row):
					$tbody .= '<tr>
						<td class="text-center">'.$i.'</td>
						<td class="text-center">'.$row->item_name.'</td>
						<td class="text-center">'.(($row->april != '-')?round($row->april,2):'-').'</td>
						<td class="text-center">'.(($row->may != '-')?round($row->may,2):'-').'</td>
						<td class="text-center">'.(($row->june != '-')?round($row->june,2):'-').'</td>
						<td class="text-center">'.(($row->july != '-')?round($row->july,2):'-').'</td>
						<td class="text-center">'.(($row->august != '-')?round($row->august,2):'-').'</td>
						<td class="text-center">'.(($row->september != '-')?round($row->september,2):'-').'</td>
						<td class="text-center">'.(($row->october != '-')?round($row->october,2):'-').'</td>
						<td class="text-center">'.(($row->november != '-')?round($row->november,2):'-').'</td>
						<td class="text-center">'.(($row->december != '-')?round($row->december,2):'-').'</td>
						<td class="text-center">'.(($row->january != '-')?round($row->january,2):'-').'</td>
						<td class="text-center">'.(($row->february != '-')?round($row->february,2):'-').'</td>
						<td class="text-center">'.(($row->march != '-')?round($row->march,2):'-').'</td>';
					$tbody.='</tr>';
					$i++;
				endforeach;
			endif;
			$this->printJson(['status'=>1, 'tbody'=>$tbody]);
		endif;
    }
}
?>