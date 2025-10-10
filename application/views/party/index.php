<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
                    <div class="float-start">
					  
					</div>
					<div class="float-end">
                        <?php
							$addParam = "{'postData':{'party_category' : ".$party_category."},'modal_id' : 'bs-right-lg-modal', 'call_function':'addParty', 'form_id' : 'add".$this->partyCategory[$party_category]."', 'title' : 'Add ".$this->partyCategory[$party_category]."'}";
                        ?>
						<button type="button" class="btn btn-outline-dark btn-sm float-right permission-write press-add-btn" onclick="modalAction(<?=$addParam?>);" ><i class="fa fa-plus"></i> Add <?=$this->partyCategory[$party_category]?></button>
												
					</div>
				</div>
			</div>
		</div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id='partyTable' class="table table-bordered ssTable ssTable-cf" data-url='/getDTRows/<?=$party_category?>'></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
