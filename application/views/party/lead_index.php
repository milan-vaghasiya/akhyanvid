<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
                   <div class="float-start">
                        <ul class="nav nav-pills">
                            <li class="nav-item"> 
                                <button onclick="statusTab('leadTable',1);" class=" btn waves-effect waves-light btn-outline-success active" style="outline:0px" data-toggle="tab" aria-expanded="false">WON</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('leadTable',2);" class=" btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">New</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('leadTable',3);" class=" btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Qualified</button> 
                            </li>
                            <li class="nav-item"> 
                                <button onclick="statusTab('leadTable',4);" class=" btn waves-effect waves-light btn-outline-success" style="outline:0px" data-toggle="tab" aria-expanded="false">Lost</button> 
                            </li>
                        </ul>
                    </div>
					<div class="float-end">
                        <?php
							$addParam = "{'postData':{'party_category' : '1','party_type' : '2'},'modal_id' : 'bs-right-lg-modal', 'call_function':'addParty', 'form_id' : 'addLead', 'title' : 'Add Lead'}";
                        ?>
						<button type="button" class="btn btn-outline-dark btn-sm float-right permission-write press-add-btn" onclick="modalAction(<?=$addParam?>);" ><i class="fa fa-plus"></i> Add Lead</button>
												
					</div>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='leadTable' class="table table-bordered ssTable ssTable-cf" data-url='/getLeadDTRows'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
