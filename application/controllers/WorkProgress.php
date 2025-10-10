<?php
class WorkProgress extends MY_Controller{
    private $indexPage = "work_progress/index";
    private $formPage = "work_progress/form";
   
    public function __construct(){
		parent::__construct();
		$this->data['headData']->pageTitle = "Work Progress";
		$this->data['headData']->controller = "workProgress";
		$this->data['headData']->pageUrl = "workProgress";
	}

    public function index(){
        $this->data['tableHeader'] = getSalesDtHeader($this->data['headData']->controller);
        $this->load->view($this->indexPage,$this->data);
    }

    public function getDTRows(){
		$data=$this->input->post();
        $data['trans_status'] = 2;
		$result = $this->project->getDTRows($data);
        $sendData = array();$i=($data['start']+1);
        foreach($result['data'] as $row):
            $row->sr_no = $i++;
            $sendData[] = getWorkProgressData($row);
        endforeach;
        $result['data'] = $sendData;
        $this->printJson($result);
    }

    public function updateWorkProgress(){
        $data = $this->input->post();
        $this->data['project_id'] = $data['id'];
        $this->load->view($this->formPage,$this->data);
    }

    public function save(){
        $data = $this->input->post(); 
        $errorMessage = array();

        if(empty($data['step_no']))
            $errorMessage['step_no'] = "Step No. is required.";
        if(empty($data['work_id'][0])){
            $errorMessage['general_error'] = "Please select at least one Instruction.";
        }

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $this->printJson($this->workProgress->save($data));
        endif;
    }

    // public function delete(){
    //     $id = $this->input->post('id');
    //     if(empty($id)):
    //         $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
    //     else:
    //         $this->printJson($this->workProgress->delete($id));

    //     endif;
    // }

    public function getWorkProgressData() {
        $data = $this->input->post(); 
        $workPlanData = $this->workProgress->getWorkProgressData(['project_id'=>$data['project_id'],'step_no'=>$data['step_no']]); 
        $html = "";  $i = 1; $groupedData = [];

        foreach ($workPlanData as $row) {
            $groupedData[$row->work_title][] = $row;
        }

        foreach ($groupedData as $workTitle => $instructions) {
            $html .= '<tr class="text-center bg-grey">';
                if($i == 1):
                    $html .= '<th style="width:5%;">
                              <input type="checkbox" id="masterSelect" class="filled-in chk-col-success workInstruction" value=""><label for="masterSelect" > ALL</label>
                             </th>';
                endif;
                    $html .= '<th class="text-center" style="width:25%;" colspan="2">'.$workTitle.'</th>
                      </tr>';

            foreach ($instructions as $row) {
                $checked = ($row->status == 1) ? 'checked' : '';
                $html .= '<tr>
                            <td class="text-center" style="width:50px;">
                                <input type="checkbox" name="work_id[]" id="work_id_' . $i . '" class="filled-in chk-col-success workInstruction" value="' . $row->work_id . '" '.$checked.'>
                                <label for="work_id_' . $i . '"></label>
                                 <input type="hidden" name="id[]" id="id_' . $i . '" value="' . $row->id . '" >
                                 <input type="hidden" name="all_work_id[]" id="all_work_id_' . $i . '" value="' . $row->work_id . '" >
                            </td>
                            <td style="width:100px;font-size:11px;" class="text-wrap text-left">' . $row->description . '</td>
                         
                        </tr>';
                $i++;
            }
        }

        $this->printJson(['status' => 1, 'tbodyData' => $html]);
    }

   
}
?>