<?php
class PermissionModel extends MasterModel{
    private $menuMaster = "menu_master";
    private $subMenuMaster = "sub_menu_master";
    private $menuPermission = "menu_permission";
    private $subMenuPermission = "sub_menu_permission";
    private $dashboardWidget = "dashboard_widget";
    private $dashboardPermission = "dashboard_permission";

    public function getMainMenus($is_report = 0,$menu_type=""){
        $queryData = array();
        $queryData['tableName'] = $this->menuMaster;
        $queryData['customWhere'][] = "menu_master.id IN (SELECT menu_id FROM sub_menu_master WHERE sub_menu_master.is_report=" . $is_report . " AND is_delete='0' GROUP BY menu_id)";
        if(!empty($menu_type)){
            $queryData['customWhere'][] = "menu_master.id IN (SELECT menu_id FROM sub_menu_master WHERE sub_menu_master.menu_type=" . $menu_type . " AND is_delete='0' GROUP BY menu_id)";
        }
        $queryData['order_by']['menu_seq'] = "ASC";
        $result = $this->rows($queryData);
        return $result;
    }

    public function getSubMenus($menu_id, $is_report = 0,$menu_type = 1){
        $queryData = array();
        $queryData['tableName'] = $this->subMenuMaster;
        $queryData['where']['menu_id'] = $menu_id;
        $queryData['where']['is_report'] = $is_report;
        $queryData['order_by']['sub_menu_seq'] = "ASC";
        $queryData['where']['menu_type'] = $menu_type;
        return $this->rows($queryData);
    }

    public function getPermission($is_report = 0,$menu_type = 1){
        $mainPermission = $this->getMainMenus($is_report,$menu_type);
        $dataRows = array();$subData = new stdClass();
        foreach($mainPermission as $row):
            if($row->is_master == 1):
                $subData->id = $row->id;
                $subData->sub_menu_seq = 1;
                $subData->sub_menu_icon = $row->menu_icon;
                $subData->sub_menu_name = $row->menu_name;
                $subData->sub_controller_name = $row->controller_name;
                $subData->menu_id = 0;
                $subData->is_report = 0;

                $subMenus = $subData;
                $row->subMenus = $subMenus;
            else:
                $subMenus = $this->getSubMenus($row->id,$is_report,$menu_type);
                $row->subMenus = $subMenus;
            endif;
            $dataRows[] = $row;
        endforeach;
        return $dataRows;
    }

    public function getEmployeePermission($emp_id,$menu_type=1){
        $queryData = array();
        $queryData['tableName'] = $this->menuPermission;
        $queryData['where']['emp_id'] = $emp_id;
        $result['mainPermission'] = $this->rows($queryData);

        $queryData = array();
        $queryData['tableName'] = $this->subMenuPermission;
        $queryData['select'] = "sub_menu_permission.*";
        $queryData['leftJoin']["sub_menu_master"]="sub_menu_master.id = sub_menu_permission.sub_menu_id";
        $queryData['where']['emp_id'] = $emp_id;
        $queryData['where']['sub_menu_master.menu_type'] = $menu_type;
        $result['subMenuPermission'] = $this->rows($queryData);
        return $result;
    }

    public function save($data){
        
        $employeePermission = $this->getEmployeePermission($data['emp_id'],$data['menu_type']);
        $mainPermissionData = array();
        foreach($employeePermission['mainPermission'] as $row):
            $mainPermissionData[] = $row->menu_id;
        endforeach;

        $subPermissionData = array();
        foreach($employeePermission['subMenuPermission'] as $row):
            $subPermissionData[] = $row->sub_menu_id;
        endforeach;

        $mainPermission = array();
        foreach($data['menu_id'] as $key=>$value):
            if(in_array($value,$mainPermissionData)):
                $menuRead = (isset($data['menu_read_'.$value]))?$data['menu_read_'.$value][0]:0;
                $menuWrite = (isset($data['menu_write_'.$value]))?$data['menu_write_'.$value][0]:0;
                $menuModify = (isset($data['menu_modify_'.$value]))?$data['menu_modify_'.$value][0]:0;
                $menuDelete = (isset($data['menu_delete_'.$value]))?$data['menu_delete_'.$value][0]:0;
                $mainPermission = [
                    'emp_id' => $data['emp_id'],
                    'menu_id' => $value,
                    'is_read' => $menuRead,
                    'is_write' => $menuWrite,
                    'is_modify' => $menuModify,
                    'is_remove' => $menuDelete,
                    'is_master' => $data['is_master'][$key],
                    'created_by' => $this->loginId
                ];
                if(in_array($value,$data['main_id'])):
                    $subPermission = array();
                    foreach($data['sub_menu_id_'.$value] as $subKey => $subValue):
                        if(in_array($subValue,$subPermissionData)):
                            $subMenuRead = (isset($data['sub_menu_read_'.$subValue.'_'.$value]))?$data['sub_menu_read_'.$subValue.'_'.$value][0]:0;
                            $subMenuWrite = (isset($data['sub_menu_write_'.$subValue.'_'.$value]))?$data['sub_menu_write_'.$subValue.'_'.$value][0]:0;
                            $subMenuModify = (isset($data['sub_menu_modify_'.$subValue.'_'.$value]))?$data['sub_menu_modify_'.$subValue.'_'.$value][0]:0;
                            $subMenuDelete = (isset($data['sub_menu_delete_'.$subValue.'_'.$value]))?$data['sub_menu_delete_'.$subValue.'_'.$value][0]:0;
                            $subMenuApprove = (isset($data['sub_menu_approve_'.$subValue.'_'.$value]))?$data['sub_menu_approve_'.$subValue.'_'.$value][0]:0;
                            $subPermission = [
                                'emp_id' => $data['emp_id'],
                                'menu_id' => $value,
                                'sub_menu_id' => $subValue,
                                'is_read' => $subMenuRead,
                                'is_write' => $subMenuWrite,
                                'is_modify' => $subMenuModify,
                                'is_remove' => $subMenuDelete,
                                'is_approve' => $subMenuApprove,
                                'created_by' => $this->loginId
                            ];
                            $this->db->where('menu_id',$value)->where('sub_menu_id',$subValue)->where('emp_id',$data['emp_id'])->update($this->subMenuPermission,$subPermission);
                        else:
                            $subMenuRead = (isset($data['sub_menu_read_'.$subValue.'_'.$value]))?$data['sub_menu_read_'.$subValue.'_'.$value][0]:0;
                            $subMenuWrite = (isset($data['sub_menu_write_'.$subValue.'_'.$value]))?$data['sub_menu_write_'.$subValue.'_'.$value][0]:0;
                            $subMenuModify = (isset($data['sub_menu_modify_'.$subValue.'_'.$value]))?$data['sub_menu_modify_'.$subValue.'_'.$value][0]:0;
                            $subMenuDelete = (isset($data['sub_menu_delete_'.$subValue.'_'.$value]))?$data['sub_menu_delete_'.$subValue.'_'.$value][0]:0;
                            $subMenuApprove = (isset($data['sub_menu_approve_'.$subValue.'_'.$value]))?$data['sub_menu_approve_'.$subValue.'_'.$value][0]:0;
                            $subPermission = [
                                'emp_id' => $data['emp_id'],
                                'menu_id' => $value,
                                'sub_menu_id' => $subValue,
                                'is_read' => $subMenuRead,
                                'is_write' => $subMenuWrite,
                                'is_modify' => $subMenuModify,
                                'is_remove' => $subMenuDelete,
                                'is_approve' => $subMenuApprove,
                                'created_by' => $this->loginId
                            ];
                            $this->db->insert($this->subMenuPermission,$subPermission);
                        endif;
                    endforeach;
                endif;
                $this->db->where('menu_id',$value)->where('emp_id',$data['emp_id'])->update($this->menuPermission,$mainPermission);
            else:
                $menuRead = (isset($data['menu_read_'.$value]))?$data['menu_read_'.$value][0]:0;
                $menuWrite = (isset($data['menu_write_'.$value]))?$data['menu_write_'.$value][0]:0;
                $menuModify = (isset($data['menu_modify_'.$value]))?$data['menu_modify_'.$value][0]:0;
                $menuDelete = (isset($data['menu_delete_'.$value]))?$data['menu_delete_'.$value][0]:0;
                $mainPermission = [
                    'emp_id' => $data['emp_id'],
                    'menu_id' => $value,
                    'is_read' => $menuRead,
                    'is_write' => $menuWrite,
                    'is_modify' => $menuModify,
                    'is_remove' => $menuDelete,
                    'is_master' => $data['is_master'][$key],
                    'created_by' => $this->loginId
                ];
                if(in_array($value,$data['main_id'])):
                    $subPermission = array();
                    foreach($data['sub_menu_id_'.$value] as $subKey => $subValue):
                        $subMenuRead = (isset($data['sub_menu_read_'.$subValue.'_'.$value]))?$data['sub_menu_read_'.$subValue.'_'.$value][0]:0;
                        $subMenuWrite = (isset($data['sub_menu_write_'.$subValue.'_'.$value]))?$data['sub_menu_write_'.$subValue.'_'.$value][0]:0;
                        $subMenuModify = (isset($data['sub_menu_modify_'.$subValue.'_'.$value]))?$data['sub_menu_modify_'.$subValue.'_'.$value][0]:0;
                        $subMenuDelete = (isset($data['sub_menu_delete_'.$subValue.'_'.$value]))?$data['sub_menu_delete_'.$subValue.'_'.$value][0]:0;
                        $subMenuApprove = (isset($data['sub_menu_approve_'.$subValue.'_'.$value]))?$data['sub_menu_approve_'.$subValue.'_'.$value][0]:0;
                        $subPermission = [
                            'emp_id' => $data['emp_id'],
                            'menu_id' => $value,
                            'sub_menu_id' => $subValue,
                            'is_read' => $subMenuRead,
                            'is_write' => $subMenuWrite,
                            'is_modify' => $subMenuModify,
                            'is_remove' => $subMenuDelete,
                            'is_approve' => $subMenuApprove,
                            'created_by' => $this->loginId
                        ];
                        $this->db->insert($this->subMenuPermission,$subPermission);
                        
                    endforeach;
                endif;
                $this->db->insert($this->menuPermission,$mainPermission);
            endif;
        endforeach;

        return ['status'=>1,'message'=>'Employee Permission saved successfully.'];
    }

    public function saveCopyPermission($data){
        $fromData = $this->getEmployeePermission($data['from_id']);

        if(!empty($data['to_id'])){
            $this->remove($this->menuPermission,['emp_id'=>$data['to_id']]); 
            $this->remove($this->subMenuPermission,['emp_id'=>$data['to_id']]); 
            $this->remove($this->dashboardPermission,['emp_id'=>$data['to_id']]);
        }

        $mainPermissionData = $fromData['mainPermission'];
        $submenuPermissionData = $fromData['subMenuPermission'];
            
        $mainPermission = array();
        foreach ($mainPermissionData as $row) :                
            $mainPermission = [
                'id'=>"",
                'is_master'=>$row->is_master,
                'emp_id' => $data['to_id'],
                'menu_id' => $row->menu_id,
                'is_read' => $row->is_read,
                'is_write' => $row->is_write,
                'is_modify' => $row->is_modify,
                'is_remove' => $row->is_remove,
                'created_by' => $this->loginId
            ]; 
            $result = $this->store($this->menuPermission,$mainPermission);
        endforeach;

        $subPermission = array();
        foreach ($submenuPermissionData as $row) : 
            $subPermission = [
                'id'=>"",
                'emp_id' => $data['to_id'],
                'menu_id' => $row->menu_id,
                'sub_menu_id' => $row->sub_menu_id,
                'is_read' => $row->is_read,
                'is_write' => $row->is_write,
                'is_modify' => $row->is_modify,
                'is_remove' => $row->is_remove,
                'is_approve' => $row->is_approve,
                'created_by' => $this->loginId
            ];
            $result = $this->store($this->subMenuPermission,$subPermission);
        endforeach;

        $dashPermission = $this->getDashboardPermission(['emp_id'=>$data['from_id'],'is_read'=>1]);
        foreach ($dashPermission as $row) : 
            $row = (array) $row;
            $row['id'] = "";
            $row['emp_id'] = $data['to_id']; 
            $result = $this->store($this->dashboardPermission,$row);
        endforeach;

        $result = ['status'=>1,'message'=>'Permission Copied successfully.'];
        return $result;
    }

    public function editPermission($emp_id,$menu_type){
        $employeePermission = $this->getEmployeePermission($emp_id,$menu_type);
        $empPermission = array();
        foreach($employeePermission['mainPermission'] as $row):
            if(!empty($row->is_read))
                $empPermission[] = "menu_read_".$row->menu_id;
            if(!empty($row->is_write))
                $empPermission[] = "menu_write_".$row->menu_id;
            if(!empty($row->is_modify))
                $empPermission[] = "menu_modify_".$row->menu_id;
            if(!empty($row->is_remove))
                $empPermission[] = "menu_delete_".$row->menu_id;
        endforeach;

        foreach($employeePermission['subMenuPermission'] as $row):
            if(!empty($row->is_read))
                $empPermission[] = "sub_menu_read_".$row->sub_menu_id."_".$row->menu_id;
            if(!empty($row->is_write))
                $empPermission[] = "sub_menu_write_".$row->sub_menu_id."_".$row->menu_id;
            if(!empty($row->is_modify))
                $empPermission[] = "sub_menu_modify_".$row->sub_menu_id."_".$row->menu_id;
            if(!empty($row->is_remove))
                $empPermission[] = "sub_menu_delete_".$row->sub_menu_id."_".$row->menu_id;
            if(!empty($row->is_approve))
                $empPermission[] = "sub_menu_approve_".$row->sub_menu_id."_".$row->menu_id;
        endforeach;

        return ['status'=>1,'message'=>'Record Found','empPermission'=>$empPermission];
    }

    public function getEmployeeMenus1(){
        $queryData = array();
        $queryData['tableName'] = $this->menuPermission;
        $queryData['select'] = 'menu_permission.*,menu_master.menu_name,menu_master.controller_name,menu_master.menu_icon';
        $queryData['leftJoin']['menu_master'] = "menu_master.id = menu_permission.menu_id";
        $queryData['where']['menu_permission.emp_id'] = $this->loginId;
        $queryData['order_by']['menu_master.menu_seq'] = "ASC";
        $menuData = $this->rows($queryData);

        $html = ""; $employeePermission = array();
        foreach($menuData as $row):
            if(!empty($row->permission)):
                if(!empty($row->is_read)):
                    if(!empty($row->is_read) || !empty($row->is_write) || !empty($row->is_modify) || !empty($row->is_remove)):
                        $url = (!empty($row->controller_name))?$row->controller_name:"#";
                        $employeePermission[$url] = ['is_read'=>$row->is_read,'is_write'=>$row->is_write,'is_modify'=>$row->is_modify,'is_remove'=>$row->is_remove];
                        $html .= '<li class="sidebar-item"><a href="'.base_url($url).'" class="sidebar-link waves-effect waves-dark" aria-expanded="false"><i class="'.$row->menu_icon.'"></i><span class="hide-menu">'.$row->menu_name.'</span></a></li>';
                    endif;
                endif;
            else:
                $subMenus = "";
                
                $queryData = array();
                $queryData['tableName'] = $this->subMenuPermission;
                $queryData['select'] = 'sub_menu_permission.*,sub_menu_master.sub_menu_name,sub_menu_master.sub_controller_name,sub_menu_master.sub_menu_icon';
                $queryData['leftJoin']['sub_menu_master'] = "sub_menu_master.id = sub_menu_permission.sub_menu_id";
                $queryData['where']['sub_menu_permission.emp_id'] = $this->loginId;
                $queryData['where']['sub_menu_permission.menu_id'] = $row->menu_id;
                $queryData['where']['sub_menu_master.is_report'] = 0;
                $queryData['where']['sub_menu_master.is_delete'] = 0;
                $queryData['order_by']['sub_menu_master.sub_menu_seq'] = "ASC";
                $subMenuData = $this->rows($queryData);

                $subMenuHtml = "";$show_menu = false; 
                foreach($subMenuData as $subRow):
                    if(!empty($subRow->is_read)):
                        if(!empty($subRow->is_read) || !empty($subRow->is_write) || !empty($subRow->is_modify) || !empty($subRow->is_remove)):
                            $show_menu = true; 
                            $sub_url = (!empty($subRow->sub_controller_name))?$subRow->sub_controller_name:"#";
                            $employeePermission[$sub_url] = ['is_read'=>$subRow->is_read,'is_write'=>$subRow->is_write,'is_modify'=>$subRow->is_modify,'is_remove'=>$subRow->is_remove,'is_approve'=>$subRow->is_approve];
                            $subMenus .= '<li class="sidebar-item"><a href="'.base_url($sub_url).'" class="sidebar-link"><i class="icon-Record"></i><span class="hide-menu"> '.$subRow->sub_menu_name.'</span></a></li>';
                        endif;
                    endif;
                endforeach;

                if($show_menu == true):
                    $html .= '<li class="sidebar-item">
                    <a href="javaScript:void();" class="sidebar-link has-arrow waves-effect waves-dark" aria-expanded="false">
                        <i class="'.$row->menu_icon.'"></i><span  class="hide-menu">'.$row->menu_name.'</span>
                    </a>
                    <ul aria-expanded="false" class="collapse  first-level">'.$subMenus.'</ul>
                    </li>';
                endif;
            endif;
        endforeach;
        
        $reportsPermission = $this->getEmployeeReportMenus()['reportPermission'];
        $employeePermission = array_merge($employeePermission,$reportsPermission);
        $this->session->set_userdata('emp_permission', array());
        $this->session->set_userdata('emp_permission', $employeePermission);

        return $html;
    }

    public function getEmployeeMenus(){
        $queryData = array();
        $queryData['tableName'] = $this->menuPermission;
        $queryData['select'] = 'menu_permission.*,menu_master.menu_name,menu_master.controller_name,menu_master.menu_icon';
        $queryData['leftJoin']['menu_master'] = "menu_master.id = menu_permission.menu_id";
        $queryData['where']['menu_permission.emp_id'] = $this->loginId;
        $queryData['order_by']['menu_master.menu_seq'] = "ASC";
        $menuData = $this->rows($queryData);

        $html = ""; $employeePermission = array();
        foreach($menuData as $row):
            if(!empty($row->permission)):
                if(!empty($row->is_read)):
                    if(!empty($row->is_read) || !empty($row->is_write) || !empty($row->is_modify) || !empty($row->is_remove)):
						$url = (!empty($row->controller_name))?$row->controller_name:"#";
                        $employeePermission[$url] = ['is_read'=>$row->is_read,'is_write'=>$row->is_write,'is_modify'=>$row->is_modify,'is_remove'=>$row->is_remove];
						
						$html .= '<li class="nav-item">
                            <a href="'.base_url($url).'" class="nav-link" aria-expanded="false">
                                <i class="'.$row->menu_icon.'"></i><span>'.$row->menu_name.'</span>
                            </a>
                        </li>';
                    endif;
                endif;
            else:
                $subMenus = "";
                
                $queryData = array();
                $queryData['tableName'] = $this->subMenuPermission;
                $queryData['select'] = 'sub_menu_permission.*,sub_menu_master.sub_menu_name,sub_menu_master.sub_controller_name,sub_menu_master.sub_menu_icon';
                $queryData['leftJoin']['sub_menu_master'] = "sub_menu_master.id = sub_menu_permission.sub_menu_id";
                $queryData['where']['sub_menu_permission.emp_id'] = $this->loginId;
                $queryData['where']['sub_menu_permission.menu_id'] = $row->menu_id;
                $queryData['where']['sub_menu_master.menu_type'] = 1;
                $queryData['where']['sub_menu_master.is_report'] = 0;
                $queryData['where']['sub_menu_master.is_delete'] = 0;
                $queryData['order_by']['sub_menu_master.sub_menu_seq'] = "ASC";
                $subMenuData = $this->rows($queryData);

                $subMenuHtml = "";$show_menu = false; 
                foreach($subMenuData as $subRow):
                    if(!empty($subRow->is_read)):
                        if(!empty($subRow->is_read) || !empty($subRow->is_write) || !empty($subRow->is_modify) || !empty($subRow->is_remove)):
                            $show_menu = true; 
                            $sub_url = (!empty($subRow->sub_controller_name))?$subRow->sub_controller_name:"#";
                            $employeePermission[$sub_url] = ['is_read'=>$subRow->is_read,'is_write'=>$subRow->is_write,'is_modify'=>$subRow->is_modify,'is_remove'=>$subRow->is_remove,'is_approve'=>$subRow->is_approve];
                            
							$subMenus .= '<li class="nav-item"><a href="'.base_url($sub_url).'" class="nav-link" >'.$subRow->sub_menu_name.'</a></li>';
                        endif;
                    endif;
                endforeach;

                if($show_menu == true):
					$url = "#menu".$row->menu_id;
					$html .= '<li class="nav-item">
                        <a href="'.$url.'" class="nav-link collapsed" data-bs-toggle="collapse" role="button" aria-controls="menu'.$row->menu_id.'">
                            <i class="menu-icon '.$row->menu_icon.'"></i><span>'.$row->menu_name.'</span>
                        </a>
                        <div class="collapse" id="menu'.$row->menu_id.'"><ul class="nav flex-column">'.$subMenus.'</ul></div>
                    </li>';
                endif;
            endif;
        endforeach;
        
        $reportsPermission = $this->getEmployeeReportMenus()['reportPermission'];
        $employeePermission = array_merge($employeePermission,$reportsPermission);
        $this->session->set_userdata('emp_permission', array());
        $this->session->set_userdata('emp_permission', $employeePermission);
		
        return $html;
    }


    public function getEmployeeReportMenus(){ 
        $employeePermission = array();
        $menuList = $this->getMainMenus(1);
        $menuArray=array();
        foreach ($menuList as $menu) {
            $queryData = array();
            $queryData['tableName'] = $this->subMenuPermission;
            $queryData['select'] = 'sub_menu_permission.*,sub_menu_master.sub_menu_name,sub_menu_master.sub_controller_name,sub_menu_master.sub_menu_icon';
            $queryData['leftJoin']['sub_menu_master'] = "sub_menu_master.id = sub_menu_permission.sub_menu_id";
            $queryData['where']['sub_menu_permission.emp_id'] = $this->loginId;
            $queryData['where']['sub_menu_permission.menu_id'] = $menu->id;
            $queryData['where']['sub_menu_master.is_report'] = 1;
            $queryData['where']['sub_menu_master.is_delete'] = 0;
            $queryData['order_by']['sub_menu_master.sub_menu_seq'] = "ASC";
            $subMenuData = $this->rows($queryData);
            $show_menu = false;
            $i = 0;
            $html = "";
            foreach ($subMenuData as $subRow) :
                if (!empty($subRow->is_read)) :
                    if (!empty($subRow->is_read) || !empty($subRow->is_write) || !empty($subRow->is_modify) || !empty($subRow->is_remove)) :
                        $show_menu = true;
                        $sub_url = (!empty($subRow->sub_controller_name)) ? $subRow->sub_controller_name : "#";
                        $employeePermission[$sub_url] = ['is_read' => $subRow->is_read, 'is_write' => $subRow->is_write, 'is_modify' => $subRow->is_modify, 'is_remove' => $subRow->is_remove, 'is_approve' => $subRow->is_approve, 'menu_id' => $subRow->menu_id];
                        // $subMenus .= '<li class="sidebar-item"><a href="' . base_url($sub_url) . '" class="sidebar-link"><i class="icon-Record"></i><span class="hide-menu"> ' . $subRow->sub_menu_name . '</span></a></li>';
                        $banner = ($i % 2 == 0) ? base_url("assets/images/background/ar_icon-1.png") : base_url("assets/images/background/ar_icon-2.png");
                        $btnbg = ($i % 2 == 0) ? 'bg-blue' : 'bg-green';
                        $html .= '<a href="' . base_url($sub_url) . '" class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-xs-12" target="_blank">
                                <div>
                                <div class="card">
                                    <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pr-0 pt-3">
                                            <div class="card-content">
                                            <h5 class="fs-15">' . $subRow->sub_menu_name . '</h5>
                                            <h2 class="fs-18">&nbsp;</h2>
                                            <p class="btn btn-icon icon-left btn-report ' . $btnbg . '"><i class="fas fa-eye"></i> View</p>
                                            <!--<h2 class="mb-3 fs-18">258</h2>
                                            <p class="mb-0"><span class="col-green">10%</span> Increase</p>-->
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pl-0">
                                            <div class="banner-img">
                                            <img src="' . $banner . '" alt="Customers">
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </a>';
                        $i++;
                    endif;
                endif;
            endforeach;
            $menu->subMenuData=$html;
            $menuArray[]=$menu;
        }

        $menuArray['reportPermission'] = $employeePermission;
        return $menuArray;
    }

    public function getEmployeeAppMenuList(){
        $queryData = array();
        $queryData['tableName'] = $this->subMenuPermission;
        $queryData['select'] = 'sub_menu_permission.*,sub_menu_master.sub_menu_name,sub_menu_master.sub_controller_name,sub_menu_master.sub_menu_icon,sub_menu_master.report_id';
        $queryData['leftJoin']['sub_menu_master'] = "sub_menu_master.id = sub_menu_permission.sub_menu_id";
        $queryData['where']['sub_menu_permission.emp_id'] = $this->loginId;
        $queryData['where']['sub_menu_master.is_report'] = 0;
        $queryData['where']['sub_menu_master.menu_type'] = "2";
        $queryData['where']['sub_menu_master.is_delete'] = 0;
        $queryData['order_by']['sub_menu_master.sub_menu_seq'] = "ASC";
        $subMenuData = $this->getData($queryData,"rows"); 

        //$permissionData = [];$i=1;$menu_position = ["Home"=>0,"Attendance"=>0,"Party"=>0,"Sales_Enquiry"=>0,"Visit"=>0,"Expense"=>0,"Profile"=>0,"Logout"=>0];
		$permissionData = [];$i=1;$menu_position = ["Home"=>0,"Profile"=>0,"Logout"=>0];
		
        $permissionData["bottomMenus"][] = ['menu_name'=>"Home",'menu_icon'=>"",'base_url'=>base_url("api/dashboard"),'is_read'=>1,'is_write'=>0,'is_modify'=>0,'is_remove'=>0,'is_approve'=>0];
		$permissionData["menuPosition"] = [];
        $permissionData["sidebarMenus"][] = ['menu_name'=>"Home",'menu_icon'=>"",'base_url'=>base_url("api/dashboard"),'is_read'=>1,'is_write'=>0,'is_modify'=>0,'is_remove'=>0,'is_approve'=>0];
        foreach($subMenuData as $subRow):
            if(!empty($subRow->is_read)):
                if(!empty($subRow->is_read) || !empty($subRow->is_write) || !empty($subRow->is_modify) || !empty($subRow->is_remove)):
                    $sub_url = (!empty($subRow->sub_controller_name))?str_replace("app/","api/",$subRow->sub_controller_name):"#";

                    $permissionData["sidebarMenus"][] = ['menu_name'=>$subRow->sub_menu_name,'menu_icon'=>$subRow->sub_menu_icon,'base_url'=>base_url($sub_url),'is_read'=>$subRow->is_read,'is_write'=>$subRow->is_write,'is_modify'=>$subRow->is_modify,'is_remove'=>$subRow->is_remove,'is_approve'=>$subRow->is_approve];
					
					$menu_position[str_replace(" ","_",$subRow->sub_menu_name)] = $i++;

                    if($subRow->report_id == 1):
                        $permissionData["bottomMenus"][] = ['menu_name'=>$subRow->sub_menu_name,'menu_icon'=>$subRow->sub_menu_icon,'base_url'=>base_url($sub_url),'is_read'=>$subRow->is_read,'is_write'=>$subRow->is_write,'is_modify'=>$subRow->is_modify,'is_remove'=>$subRow->is_remove,'is_approve'=>$subRow->is_approve];
                    endif;
                endif;
            endif;
        endforeach;
        $permissionData["sidebarMenus"][] = ['menu_name'=>"Logout",'menu_icon'=>"",'base_url'=>base_url("api/logout"),'is_read'=>1,'is_write'=>0,'is_modify'=>0,'is_remove'=>0,'is_approve'=>0];
		$menu_position["Logout"] = $i;
		$permissionData["menuPosition"] = $menu_position;

        return $permissionData;
    }
    
    public function getDashboardWidget(){
        $queryData = array();
        $queryData['tableName'] = $this->dashboardWidget;
        $queryData['order_by']['id'] = "ASC";
        return $this->rows($queryData);
    }

    public function saveDashboardPermission($data){ 
        try {
            $this->db->trans_begin();

            $this->trash($this->dashboardPermission,['emp_id'=>$data['emp_id']]);

            foreach ($data['permission'] as $row):
                $row['emp_id'] = $data['emp_id'];
                $row['is_read'] = (!empty($row['is_read']))?1:0;
                $row['is_delete'] = 0;
                $result = $this->store($this->dashboardPermission,$row,'Employee Permission');
            endforeach;

            if ($this->db->trans_status() !== FALSE) :
                $this->db->trans_commit();
                return $result;
            endif;
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ['status' => 2, 'message' => "somthing is wrong. Error : " . $e->getMessage()];
        }
    }

    public function getDashboardPermission($data=array()){
        $queryData = array();
        $queryData['tableName'] = $this->dashboardPermission;
        $queryData['select'] = "dashboard_permission.*";
        $queryData['leftJoin']['dashboard_widget'] = "dashboard_widget.id = dashboard_permission.widget_id";
        $queryData['where']['dashboard_permission.emp_id'] = $data['emp_id'];

        if(!empty($data['is_read'])):
            $queryData['where']['dashboard_permission.is_read'] = $data['is_read'];
        endif;

        $queryData['where']['dashboard_widget.is_delete'] = 0;
        return $this->rows($queryData);
    }

    // report_id field used for bottom menu
    public function getEmployeeAppMenus($bottom_menu = 0){
        $html = ""; $employeePermission = array(); $subMenus = "";
                
        $queryData = array();
        $queryData['tableName'] = $this->subMenuPermission;
        $queryData['select'] = 'sub_menu_permission.*,sub_menu_master.sub_menu_name,sub_menu_master.sub_controller_name,sub_menu_master.sub_menu_icon,sub_menu_master.report_id';
        $queryData['leftJoin']['sub_menu_master'] = "sub_menu_master.id = sub_menu_permission.sub_menu_id";
        $queryData['where']['sub_menu_permission.emp_id'] = $this->loginId;
        $queryData['where']['sub_menu_master.is_report'] = 0;
        $queryData['where']['sub_menu_master.menu_type'] = 2;
        $queryData['where']['sub_menu_master.is_delete'] = 0;
        $queryData['order_by']['sub_menu_master.sub_menu_seq'] = "ASC";
        $subMenuData = $this->rows($queryData);
        $subMenuHtml = "";$show_menu = false; 
        foreach($subMenuData as $subRow):
            if(!empty($subRow->is_read)):
                if(!empty($subRow->is_read) || !empty($subRow->is_write) || !empty($subRow->is_modify) || !empty($subRow->is_remove)):
                    $show_menu = true; 
                    $sub_url = (!empty($subRow->sub_controller_name))?$subRow->sub_controller_name:"#";
                    $employeePermission[$sub_url] = ['is_read'=>$subRow->is_read,'is_write'=>$subRow->is_write,'is_modify'=>$subRow->is_modify,'is_remove'=>$subRow->is_remove,'is_approve'=>$subRow->is_approve];
                    if($bottom_menu == 1 && $subRow->report_id == 1){
                        $html.= '<a href="'.base_url($sub_url).'" class="nav-link" data-page_url="'.$sub_url.'">
                                    <div class="shape">
                                        <i class="'.$subRow->sub_menu_icon.'"></i>
                                        <div class="inner-shape"></div>
                                    </div>
                                    <span>'.$subRow->sub_menu_name.'</span>
                                </a>';
                    }elseif($bottom_menu == 0){
                        $html.= '<li><a href="'.base_url($sub_url).'" class="nav-link" data-page_url="'.$sub_url.'">
                                    <span class="dz-icon">
                                        <i class="'.$subRow->sub_menu_icon.'"></i>
                                        <div class="inner-shape"></div>
                                        </span>
                                    <span>'.$subRow->sub_menu_name.'</span>
                                </a></li>';
                    }
                    
                endif;
            endif;
        endforeach;

        
        $this->session->set_userdata('emp_app_permission', $employeePermission);
		
        return $html;
    }
}
?>