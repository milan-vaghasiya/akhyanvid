<?php
class DashboardModel extends MasterModel{
    
    public function getPendingQuotation(){
		$queryData = [];
		$queryData['tableName'] = "sq_master";
		$queryData['select'] = "id";
		$queryData['where']['trans_date >='] = $this->startYearDate;
		$queryData['where']['trans_date <='] = $this->endYearDate;
		$queryData['where']['trans_status'] = 1;
		$queryData['group_by'][] = 'sq_master.trans_number';

		$result = $this->rows($queryData);

		return count($result);
	}

	public function getOnGoingProjects(){
		$queryData = [];
		$queryData['tableName'] = "project_info";
		$queryData['select'] = "COUNT(id) as totalOnGoingProjects";
		$queryData['where']['DATE(created_at) >='] = $this->startYearDate;
		$queryData['where']['DATE(created_at) <='] = $this->endYearDate;
		$queryData['where']['trans_status'] = 2;

		$result = $this->row($queryData);

		return $result;
	}

	public function getPendingServices(){
		$queryData = [];
		$queryData['tableName'] = "service_master";
		$queryData['select'] = "COUNT(id) as totalPendingServices";
		$queryData['where']['DATE(trans_date) >='] = $this->startYearDate;
		$queryData['where']['DATE(trans_date) <='] = $this->endYearDate;
		$queryData['where']['status'] = 1;

		$result = $this->row($queryData);

		return $result;
	}

	public function getPendingComplaint(){
		$queryData = [];
		$queryData['tableName'] = "customer_complaint";
		$queryData['select'] = "COUNT(id) as totalPendingComplaint";
		$queryData['where']['date >='] = $this->startYearDate;
		$queryData['where']['date <='] = $this->endYearDate;
		$queryData['where']['status'] = 1;

		$result = $this->row($queryData);

		return $result;
	}

	public function getPendingTask(){
		$queryData = [];
		$queryData['tableName'] = "task_master";
		$queryData['select'] = "COUNT(id) as totalPendingTask";
		$queryData['where']['DATE(created_at) >='] = $this->startYearDate;
		$queryData['where']['DATE(created_at) <='] = $this->endYearDate;
		$queryData['where']['status'] = 1;

		$result = $this->row($queryData);

		return $result;
	}

}
?>