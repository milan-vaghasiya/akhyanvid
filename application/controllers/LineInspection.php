<?php 
class LineInspection extends MY_Controller
{
    private $indexPage = "line_inspection/index";
    private $iprIndexPage = "line_inspection/ipr_index";
    private $formPage = "line_inspection/form";
    
	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Line Inspection";
		$this->data['headData']->controller = "lineInspection";
        $this->data['headData']->pageUrl = "lineInspection";
	}
	 
	public function index(){
        $this->data['tableHeader'] = getQualityDtHeader('runningJobs');
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
        $data = $this->input->post();
        $result = $this->lineInspection->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getRunningJobsData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function addLineInspection(){
        $data = $this->input->post();
        $this->data['dataRow'] = $dataRow = $this->sop->getPRCProcessList(['prc_id'=>$data['prc_id'],'process_id'=>$data['process_id'],'single_row'=>1]);
        $this->data['operatorList'] = $this->employee->getEmployeeList();
        $this->data['machineList'] = $this->item->getItemList(['item_type'=>5]);
        $this->data['paramData'] = $this->item->getInspectionParameter(['item_id'=>$dataRow->item_id,'process_id'=>$data['process_id'],'control_method'=>'IPR']);
		$this->load->view($this->formPage,$this->data);
	}

    public function saveLineInspection(){ 
		$data = $this->input->post(); 
        $errorMessage = Array(); 

		if(empty($data['item_id'])){ $errorMessage['item_id'] = "Item is required.";}

        $insParamData = $this->item->getInspectionParameter(['item_id'=>$data['item_id'],'process_id'=>$data['process_id'],'control_method'=>'IPR']);
        if(count($insParamData) <= 0)
            $errorMessage['general'] = "Item Parameter is required.";

        $pre_inspection = Array(); $param_ids = Array();

        if(!empty($insParamData)):
            foreach($insParamData as $row):
                $param = Array();
                $param[] = (!empty($data['result_'.$row->id]) ? $data['result_'.$row->id] : ""); 
                $pre_inspection[$row->id] = $param;
				$param_ids[] = $row->id;
                unset($data['result_'.$row->id]);
            endforeach;
        endif;

        $data['observation_sample'] = json_encode($pre_inspection);
		$data['parameter_ids'] = implode(',',$param_ids);
        $data['param_count'] = count($insParamData);

		if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['created_by'] = $this->session->userdata('loginId');
            $this->printJson($this->lineInspection->saveLineInspection($data));
        endif;
	}

    public function iprIndex(){
        $this->data['tableHeader'] = getQualityDtHeader('lineInspection');
        $this->load->view($this->iprIndexPage,$this->data);
    }

    public function getIPRDTRows(){
        $data = $this->input->post();
        $result = $this->lineInspection->getIPRDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):          
            $row->sr_no = $i++;         
            $sendData[] = getLineInspectionData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function editLineInspection(){
        $data = $this->input->post();
        $this->data['lineInspData'] =$dataRow= $this->lineInspection->getLineInspectData(['id'=>$data['id']]);
        $this->data['operatorList'] = $this->employee->getEmployeeList();
        $this->data['machineList'] = $this->item->getItemList(['item_type'=>5]);
        $this->data['paramData'] = $this->item->getInspectionParameter(['item_id'=>$dataRow->item_id,'process_id'=>$dataRow->process_id,'control_method'=>'IPR']);
        $this->load->view($this->formPage,$this->data);       
    }

    public function delete(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->lineInspection->delete($id));
        endif;
    }

    function printLineInspection($id){
		$this->data['lineInspectData'] = $inspData = $this->lineInspection->getLineInspectData(['id'=>$id]);
        $this->data['paramData'] = $paramData = $this->item->getInspectionParameter(['item_id'=>$inspData->item_id,'process_id'=>$inspData->process_id ,'control_method'=>'IPR']);
		$this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();

        $tbodyData="";$theadData="";$i=1;$blankRow='';
        if(!empty($paramData)):
            foreach($paramData as $row):
                $paramItems = '<tr>
                    <td style="text-align:center;" height="30">'.$i.'</td>
                    <td style="text-align:center;">'.$row->parameter.'</td>
                    <td style="text-align:center;">'.$row->specification.'</td>
                    <td style="text-align:center;">'.$row->instrument.'</td>';
                
                    $objData = $this->lineInspection->getLineInspectData(['prc_id'=>$inspData->prc_id,'process_id'=>$inspData->process_id,'insp_date'=>$inspData->insp_date]);
                    $rcount = count($objData);
                    foreach($objData as $read):
                        if($i==1){
                            $insp_time = (!empty($read->insp_time)?date("h:i A",strtotime($read->insp_time)):'');
                            $theadData .= '<td style="text-align:center;">'.$insp_time.'</td>';
                        }
                        $obj = New StdClass; 
                        $obj = json_decode($read->observation_sample);
                        $result = '';
                        if(!empty($obj->{$row->id})):
                            $result = $obj->{$row->id}[0];
                            if(!empty($row->check_type)){
                                $result = !empty($obj->{$row->id}[0])?"Visual":'';                                
                            }
                            
                        endif;
                        $paramItems .= '<td style="text-align:center;">'.$result.'</td>';
                    endforeach;
                    $paramItems .= '</tr>';
                $tbodyData .= $paramItems;
                $i++;
            endforeach;
            for($j=20; $i<=$j; $i++):
                $blankRow .= '<tr>
                                <td>&nbsp;</td>
                                <td></td>
                                <td></td>
                                <td></td>';
                                for($td=1; $td<=$rcount; $td++){ $blankRow .= '<td></td>'; }
                $blankRow .= '</tr>';
            endfor;
            $tbodyData .= $blankRow;
        else:
            $tbodyData.= '<tr><td colspan="12" style="text-align:center;">No Data Found</td></tr>';
        endif;
        $this->data['rcount'] = $rcount;
        $this->data['theadData'] = $theadData;
        $this->data['tbodyData'] = $tbodyData;
		$bodyData = $this->load->view('line_inspection/printLineInspection',$this->data,true);

		$logo = base_url('assets/images/logo.png');
		$htmlHeader = '<table class="table">
							<tr>
								<td style="width:25%;"><img src="'.$logo.'" style="height:50px;"></td>
								<td class="org_title text-center" style="font-size:1rem;width:50%">LINE INSPECTION REPORT</td>
								<td style="width:25%;" class="text-right"><span style="font-size:0.8rem;"></td>
							</tr>
						</table><hr>';
                        
		$htmlFooter = '<table class="table" style="border-bottom:1px solid #000000;">
						<tr>
							<td style="width:50%;"></td>
							<td style="width:50%;" class="text-center"></td>
						</tr>
						<tr>
							<td style="width:50%;"></td>
							<td style="width:50%;" class="text-center"><b>Prepared By</b></td>
						</tr>
					</table>
					<table class="table top-table" style="margin-top:10px;">
						<tr>
							<td style="width:25%;"></td>
							<td style="width:25%;text-align:right;">Page No. {PAGENO}/{nbpg}</td>
						</tr>
					</table>';
		
		$pdfData = '<div style="width:200mm;height:140mm;">'.$bodyData.'</div>';
        $mpdf = new \Mpdf\Mpdf();
		$pdfFileName='IPR-'.$id.'.pdf';
		$stylesheet = file_get_contents(base_url('assets/css/pdf_style.css'));
		$mpdf->WriteHTML($stylesheet,1);
		$mpdf->SetDisplayMode('fullpage');
		$mpdf->SetProtection(array('print'));
        $mpdf->SetHTMLHeader($htmlHeader);
		$mpdf->SetHTMLFooter($htmlFooter);
        $mpdf->AddPage('P','','','','',5,5,30,5,5,5,'','','','','','','','','','A4-P');
		$mpdf->WriteHTML($pdfData);
		$mpdf->Output($pdfFileName,'I');
	}
}
?>