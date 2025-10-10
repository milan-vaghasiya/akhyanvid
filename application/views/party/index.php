<?php $this->load->view('includes/header'); ?>
<div class="page-content-tab">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="page-title-box">
					<div class="float-end">
                        <?php
                            $addParam = "{'postData':{'party_category' : ".$party_category."},'modal_id' : '".(($party_category != 4)?"bs-right-lg-modal":"bs-right-md-modal")."', 'call_function':'addParty', 'form_id' : 'add".$this->partyCategory[$party_category]."', 'title' : 'Update ".$this->partyCategory[$party_category]."'}";
                        ?>
						<button type="button" class="btn btn-outline-dark btn-sm float-right permission-write press-add-btn" onclick="modalAction(<?=$addParam?>);" ><i class="fa fa-plus"></i> Add <?=$this->partyCategory[$party_category]?></button>

                        <?php if($party_category == 4){ ?>
                            <a href="<?=base_url($headData->controller."/opBalIndex")?>" class="btn btn-outline-dark btn-sm float-right permission-write m-r-5" target="_blank"><i class="icon-Money-Bag font-bold"></i> Update Op. Bal.</a>
                        <?php } ?> 
					</div>
					<h4 class="page-title"><?=$this->partyCategory[$party_category]?></h4>
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
<script>
function resSavePartyGstDetail(data,formId){
    if(data.status==1){
        Swal.fire( 'Success', data.message, 'success' );

        $('#'+formId)[0].reset();

        var gstTrans = {'postData':{'party_id':$("#gstDetail #party_id").val()},'table_id':"gstDetail",'tbody_id':'gstDetailBody','tfoot_id':'','fnget':'getPartyGSTDetailHtml'};
        getTransHtml(gstTrans);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            Swal.fire( 'Sorry...!', data.message, 'error' );
        }			
    }
}

function resTrashPartyGstDetail(data){
    if(data.status==1){
        Swal.fire( 'Success', data.message, 'success' );

        var gstTrans = {'postData':{'party_id':$("#gstDetail #party_id").val()},'table_id':"gstDetail",'tbody_id':'gstDetailBody','tfoot_id':'','fnget':'getPartyGSTDetailHtml'};
        getTransHtml(gstTrans);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            Swal.fire( 'Sorry...!', data.message, 'error' );
        }			
    }
}

function resSavePartyContactDetail(data,formId){
    if(data.status==1){
        Swal.fire( 'Success', data.message, 'success' );

        $('#'+formId)[0].reset();

        var contactTrans = {'postData':{'party_id':$("#contactDetail #party_id").val()},'table_id':"contactDetail",'tbody_id':'contactDetailBody','tfoot_id':'','fnget':'getPartyContactDetailHtml'};
        getTransHtml(contactTrans);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            Swal.fire( 'Sorry...!', data.message, 'error' );
        }			
    }
}

function resTrashPartyContactDetail(data){
    if(data.status==1){
        Swal.fire( 'Success', data.message, 'success' );

        var contactTrans = {'postData':{'party_id':$("#contactDetail #party_id").val()},'table_id':"contactDetail",'tbody_id':'contactDetailBody','tfoot_id':'','fnget':'getPartyContactDetailHtml'};
        getTransHtml(contactTrans);
    }else{
        if(typeof data.message === "object"){
            $(".error").html("");
            $.each( data.message, function( key, value ) { $("."+key).html(value); });
        }else{
            Swal.fire( 'Sorry...!', data.message, 'error' );
        }			
    }
}
</script>