<?php
class Service extends MY_ApiController{

	public function getServiceList(){
        $data = $this->input->post();
		if(!in_array($data['status'], [4,5,6])){
			$data['status'] = 2; //Defult approved service		
		}

        $serviceList = $this->service->getServiceList($data);

		if(!empty($serviceList)){
			$this->printJson(['status' => 1, 'message' => 'Data Found', 'data' => $serviceList]);
		} else{
			$this->printJson(['status' => 0, 'message' => 'Data Not Found', 'data' => []]);
		}
    }

	public function AcceptService(){
        $data = $this->input->post();
		$headData = json_decode(base64_decode($this->input->get_request_header('sign')));
        $errorMessage = array();
        
		if(empty($data['service_id'])){
            $errorMessage = "Service is required.";
		} else{
			$serviceData = $this->service->getService(['id' => $data['service_id']]);
			if(!empty($serviceData->technician_id)){
				$errorMessage = "This Service is already accepted.";
			}

			if(!empty($errorMessage)){
				$this->printJson(['status'=>0,'message' => $errorMessage]);
			}else{
				$data['id'] = $data['service_id'];
				$data['technician_id'] = $headData->loginId;
				$data['status'] = 3;
				unset($data['service_id']);
				
				$this->printJson($this->service->save($data));
			}
		}
    }

	public function StartAndCompleteService(){
        $data = $this->input->post();
        $errorMessage = array();
        
		if(empty($data['service_id'])){
            $errorMessage = "Service is required.";
		}
		if (empty($data['flag']) || !in_array($data['flag'], [1, 2])) {
			$errorMessage = "Flag is required and must be 1 or 2.";
		}

		if(!empty($errorMessage)){
			$this->printJson(['status' => 0,'message' => $errorMessage]);
		}
		else{
			$serviceData = $this->service->getService(['id' => $data['service_id']]);
			
			if(!empty($serviceData->start_date) && $data['flag'] == 1){
				$errorMessage = "This Service is already started.";
			}
			else if(empty($serviceData->start_date) && $data['flag'] == 2){
				$errorMessage = "Please start the service before completing it.";
			}			
			else if(!empty($serviceData->complete_date)){
				$errorMessage = "This Service is already completed.";
			}

			if(!empty($errorMessage)){
				$this->printJson(['status' => 0,'message' => $errorMessage]);
			}else{
				$data['id'] = $data['service_id'];
				if($data['flag'] == 1){
					$data['status'] = 4;
					$data['start_date'] = date('Y-m-d H:i:s');
				}
				else if($data['flag'] == 2){
					$data['status'] = 5;
					$data['complete_date'] = date('Y-m-d H:i:s');

				}
				unset($data['service_id'],$data['flag']);
				$this->printJson($this->service->save($data));
			}
		}
    }

    public function saveCompleteService(){
        $data = $this->input->post();
        $errorMessage = array();
		if(empty($data['action_detail'])){ $errorMessage['action_detail'] = "Action Taken required."; }
        
        $aft_images = array();
        if($_FILES['aft_images']['name'][0] != null || !empty($_FILES['aft_images']['name'][0])):
            foreach ($_FILES['aft_images']['tmp_name'] as $key => $value):
                $this->load->library('upload');
                $_FILES['userfile']['name']     = $_FILES['aft_images']['name'][$key];
                $_FILES['userfile']['type']     = $_FILES['aft_images']['type'][$key];
                $_FILES['userfile']['tmp_name'] = $_FILES['aft_images']['tmp_name'][$key];
                $_FILES['userfile']['error']    = $_FILES['aft_images']['error'][$key];
                $_FILES['userfile']['size']     = $_FILES['aft_images']['size'][$key];
                
                $imagePath = realpath(APPPATH . '../assets/uploads/service/');
				$fileName = 'AFTER_'.time().'_'.$_FILES['aft_images']['name'][$key];
                $config = ['file_name' => $fileName,'allowed_types' => '*','max_size' => 10240,'overwrite' => TRUE, 'upload_path'=>$imagePath];

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['aft_images'] = $this->upload->display_errors();
                    $this->printJson(["status"=>0,"message"=>$errorMessage]);
                else:
                    $uploadData = $this->upload->data();
                    $aft_images[] = $uploadData['file_name'];
                endif;
            endforeach;
        else:
            unset($data['aft_images']);
        endif; 
        if(!empty($aft_images)):
            $data['aft_images'] = implode(",",$aft_images);
        endif; 

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
			$data['complete_date'] = date('Y-m-d H:i:s');
			$data['status'] = 5;
            $this->printJson($this->service->save($data));
        endif;
    }

}
?>