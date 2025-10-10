<?php
class SalesReport extends MY_Controller{

    public function __construct(){
        parent::__construct();
        $this->data['headData']->pageTitle = "Sales Report";
        $this->data['headData']->controller = "reports/salesReport";
    }

    public function orderMonitoring(){
        $this->data['headData']->pageUrl = "reports/salesReport/orderMonitoring";
        $this->data['headData']->pageTitle = "ORDER MONITORING";
        $this->data['pageHeader'] = 'ORDER MONITORING';
        $this->data['startDate'] = getFyDate(date("Y-m-01"));
        $this->data['endDate'] = getFyDate(date("Y-m-t"));
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>1]);
        $this->load->view("reports/sales_report/order_monitoring",$this->data);
    }

    public function getOrderMonitoringData(){
        $data = $this->input->post();
        $errorMessage = array();
		if($data['to_date'] < $data['from_date'])
			$errorMessage['toDate'] = "Invalid date.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $result = $this->salesReport->getOrderMonitoringData($data);;
            $tbody=""; $i=1; $blankInTd='';
            $blankInTd='<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>';
         
            if(!empty($result)):
				foreach($result as $row):
					$data['ref_id'] = $row->id;
					$invoiceData = $this->salesReport->getSalesInvData($data);
					$invoiceCount = count($invoiceData);

					$tbody .= '<tr>
						<td class="text-center">'.$i++.'</td>
						<td>'.formatDate($row->trans_date).'</td>
						<td>'.$row->trans_number.'</td>
						<td>'.$row->party_name.'</td>
						<td>'.$row->item_name.'</td>
						<td>'.floatval($row->qty).' '.$row->uom.'</td>
						<td>'.formatDate($row->cod_date).'</td>';
		  
						if($invoiceCount > 0):
							$j=1;$qtyDiff=0;$totalQty=0;
							foreach($invoiceData as $invRow):
								$daysDiff = ''; 
								if(!empty($row->cod_date) AND !empty($invRow->invDate)){
									$cod_date = new DateTime($row->cod_date);
									$invDate = new DateTime($invRow->invDate);
									$due_days = $cod_date->diff($invDate)->format("%r%a");
									$daysDiff = ($due_days > 0) ? $due_days : 'On Time';
								}
								$totalQty+=$invRow->invQty;
								$qtyDiff = abs($row->qty - $totalQty);
								
								$tbody.='<td>'.formatDate($invRow->invDate).'</td>
										<td>'.$invRow->invNo.'</td>
										<td>'.floatval($invRow->invQty).' '.$row->uom.'</td>
										<td>'.$daysDiff.'</td>
										<td>'.abs($qtyDiff).' '.$row->uom.'</td>';

								if($j != $invoiceCount){$tbody.='</tr><tr><td>'.$i++.'</td>'.$blankInTd;}
								$j++;
							endforeach;
						else:
							$tbody.='<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
						endif;
					$tbody.='</tr>';
				endforeach;
            endif;
            $this->printJson(['status'=>1, 'tbody'=>$tbody]);
        endif;
    }

    public function salesAnalysis(){
        $this->data['headData']->pageUrl = "reports/salesReport/salesAnalysis";
        $this->data['headData']->pageTitle = "SALES ANALYSIS";
        $this->data['pageHeader'] = 'SALES ANALYSIS';
        $this->data['startDate'] = getFyDate(date("Y-m-01"));
        $this->data['endDate'] = getFyDate(date("Y-m-t"));
        $this->load->view("reports/sales_report/sales_analysis",$this->data);
    }

    public function getSalesAnalysisData(){
        $data = $this->input->post();
        $result = $this->salesReport->getSalesAnalysisData($data);

        $thead = $tbody = $tfoot = ''; $i=1;
        if($data['report_type'] == 1):
            $thead .= '<tr>
                <th>#</th>
                <th class="text-left">Customer Name</th>
                <th class="text-right">Taxable Amount</th>
                <th class="text-right">GST Amount</th>
                <th class="text-right">Net Amount</th>
            </tr>';

            $taxableAmount = $gstAmount = $netAmount = 0;
            foreach($result as $row):
                $tbody .= '<tr>
                    <td>'.$i.'</td>
                    <td class="text-left">'.$row->party_name.'</td>
                    <td class="text-right">'.floatval($row->taxable_amount).'</td>
                    <td class="text-right">'.floatval($row->gst_amount).'</td>
                    <td class="text-right">'.floatval($row->net_amount).'</td>
                </tr>';
                $i++;
                $taxableAmount += floatval($row->taxable_amount);
                $gstAmount += floatval($row->gst_amount);
                $netAmount += floatval($row->net_amount);
            endforeach;

            $tfoot .= '<tr>
                <th colspan="2" class="text-right">Total</th>
                <th class="text-right">'.$taxableAmount.'</th>
                <th class="text-right">'.$gstAmount.'</th>
                <th class="text-right">'.$netAmount.'</th>
            </tr>';
        else:
            $thead .= '<tr>
                <th>#</th>
                <th class="text-left">Item Name</th>
                <th class="text-right">Qty.</th>
                <th class="text-right">Price</th>
                <th class="text-right">Taxable Amount</th>
            </tr>';

            $totalQty = $taxableAmount = 0;
            foreach($result as $row):
                $tbody .= '<tr>
                    <td>'.$i.'</td>
                    <td class="text-left">'.$row->item_name.'</td>
                    <td class="text-right">'.floatVal($row->qty).'</td>
                    <td class="text-right">'.floatVal($row->price).'</td>
                    <td class="text-right">'.floatVal($row->taxable_amount).'</td>
                </tr>';
                $i++;
                $totalQty += floatval($row->qty);
                $taxableAmount += floatval($row->taxable_amount);
            endforeach;

            $tfoot .= '<tr>
                <th colspan="2" class="text-right">Total</th>
                <th class="text-right">'.$totalQty.'</th>
                <th></th>
                <th class="text-right">'.$taxableAmount.'</th>
            </tr>';
        endif;

        $this->printJson(['status'=>1,'thead'=>$thead,'tbody'=>$tbody,'tfoot'=>$tfoot]);
    }

    public function mrpReport(){
        $this->data['headData']->pageUrl = "reports/salesReport/mrpReport";
        $this->data['headData']->pageTitle = "MRP REPORT";
        $this->data['pageHeader'] = 'MRP REPORT';
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>'1,2']);
        $this->data['itemList'] = $this->salesOrder->getPendingOrderItems();
        $this->load->view("reports/sales_report/mrp_report",$this->data);
    }

    public function getPendingPartyOrders() {
        $data = $this->input->post();
        $result = $this->salesOrder->getPendingOrderItems($data);

        $itemIds = array_unique(array_column($result, 'item_id'));
        $itemName = array_unique(array_column($result, 'item_name'));

        $options = '<option value="">Select Item</option>';
        foreach($itemIds as $key => $row):
            $options .= '<option value="'.$row.'">'.$itemName[$key].'</option>';
        endforeach;

        $this->printJson(['status'=>1,'options'=>$options]);
    }

    public function getMrpReport(){
        $data = $this->input->post();
        $result = $this->salesReport->getMrpReportData($data);

        $tbody = '';$i=1;
        foreach($result as $row):
            $tbody .= '<tr>
                <td class="text-center">'.$i++.'</td>
                <td class="text-left">'.$row->trans_number.'</td>
                <td class="text-left">'.$row->trans_date.'</td>
                <td class="text-left">'.$row->bom_item_name.'</td>
                <td class="text-right">'.floor($row->plan_qty).' '.$row->uom.'</td>
            </tr>';
        endforeach;

        $this->printJson(['status'=>1,'tbody'=>$tbody]);
    }

	public function salesOrderRegister(){
        $this->data['headData']->pageUrl = "reports/salesReport/orderMonitoring";
        $this->data['headData']->pageTitle = "SALES ORDER REGISTER";
        $this->data['pageHeader'] = 'SALES ORDER REGISTER';
        $this->data['startDate'] = getFyDate(date("Y-m-01"));
        $this->data['endDate'] = getFyDate(date("Y-m-t"));
        $this->data['partyList'] = $this->party->getPartyList(['party_category'=>1]);
        $this->load->view("reports/sales_report/so_register",$this->data);
    }

    public function getSalesOrderRegister(){
        $data = $this->input->post();
        $errorMessage = array();
		if($data['to_date'] < $data['from_date'])
			$errorMessage['toDate'] = "Invalid date.";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $result = $this->salesReport->getSalesOrderRegister($data);;
            $tbody=""; $i=1;
            if(!empty($result)):
                foreach($result as $row):
					if($row->trans_status != 2 && $row->qty > $row->dispatch_qty):
						$status = '<span class="badge bg-danger fw-semibold font-12 v-super">Pending</span>';
					elseif($row->trans_status != 2 && $row->qty <= $row->dispatch_qty):
						$status = '<span class="badge bg-success fw-semibold font-12 v-super">Complete</span>';
					else:
						$status = '<span class="badge bg-dark fw-semibold font-12 v-super">Short Close</span>';
					endif;
					
					$tbody .= '<tr>
						<td class="text-center">'.$i++.'</td>
						<td>'.$row->trans_number.'</td>
						<td>'.formatDate($row->trans_date).'</td>
						<td>'.$row->party_name.'</td>
						<td>'.$row->item_name.'</td>
						<td>'.floatval($row->stock_qty).' '.$row->uom.'</td> 
						<td>'.floatval($row->qty).' '.$row->uom.'</td> 
                        <td>'.floatval($row->dispatch_qty).' '.$row->uom.'</td> 
                        <td>'.floatval($row->pending_qty).' '.$row->uom.'</td> 
                        <td>'.$status.'</td>';
					$tbody.='</tr>';
                endforeach;
            endif;
            $this->printJson(['status'=>1, 'tbody'=>$tbody]);
        endif;
    }
}
?>