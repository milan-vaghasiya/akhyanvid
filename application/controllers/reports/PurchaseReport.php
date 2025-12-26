<?php
class PurchaseReport extends MY_Controller
{
    private $purchase_monitoring = "report/purchase_report/purchase_monitoring";  
	private $purchase_inward = "report/purchase_report/purchase_inward";

    public function __construct(){
		parent::__construct();
		$this->isLoggedin();
        $this->data['headData']->pageTitle = "PURCHASE REPORT";
		$this->data['headData']->controller = "reports/purchaseReport";
	}

	public function purchaseInward(){
        $this->data['headData']->pageTitle = "PURCHASE INWARD REPORT";
        $this->data['pageHeader'] = 'PURCHASE INWARD REPORT';
		$this->data['projectList'] = $this->project->getProjectData();
        $this->load->view($this->purchase_inward,$this->data);
    }

	public function getPurchaseInward(){
        $data = $this->input->post();
        $inwardData = $this->purchaseReport->getPurchaseInward($data);

        $i=1; $tbody=''; $tfoot = ''; $totalAmt=0; $totalQty=0; $totalItemPrice=0; $total=0;
        if(!empty($inwardData)){
            foreach($inwardData as $row):
                $totalAmt = ($row->qty * $row->price);
                $tbody .= '<tr>
                    <td>'.$i++.'</td>
                    <td>'.$row->trans_number.'</td>
                    <td>'.formatDate($row->trans_date).'</td>
                    <td>'.$row->party_name.'</td>
                    <td>'.$row->item_name.'</td>
                    <td>'.floatVal($row->qty).'</td>
                    <td>'.floatVal($row->price).'</td>
                    <td>'.$totalAmt.'</td>
                </tr>';
                $totalQty += $row->qty; 
				$totalItemPrice += $row->price;
				$total += $totalAmt;
            endforeach;
        } 
        $tfoot = '<tr>
			<th colspan="5">Total</th>
			<th>'.(!empty($totalQty) ? round($totalQty) : '').'</th>
			<th>'.(!empty($totalItemPrice) ? round($totalItemPrice, 2) : '').'</th>
			<th>'.(!empty($total) ? round($total, 2) : '').'</th>
		</tr>';
        $this->printJson(['status'=>1, 'tbody'=>$tbody, 'tfoot'=>$tfoot]);
    }
	
}
?>