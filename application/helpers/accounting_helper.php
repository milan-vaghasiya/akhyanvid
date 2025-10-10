<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function getAccountingDtHeader($page){
    /* Sales Invoice Header */
    $data['salesInvoice'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['salesInvoice'][] = ["name"=>'<input type="checkbox" id="bulk-action" class="filled-in chk-col-success bulk-action-check"><label for="bulk-action"> # </label>',"class"=>"text-center no_filter","sortable"=>FALSE];
    $data['salesInvoice'][] = ["name"=>"Ship To"];
    $data['salesInvoice'][] = ["name"=>"Unit"];
	$data['salesInvoice'][] = ["name"=>"Inv No."];
	$data['salesInvoice'][] = ["name"=>"Inv Date"];
	$data['salesInvoice'][] = ["name"=>"Customer Name"];
	$data['salesInvoice'][] = ["name"=>"Taxable Amount"];
	$data['salesInvoice'][] = ["name"=>"GST Amount"];
    $data['salesInvoice'][] = ["name"=>"Net Amount"];
    $data['salesInvoice'][] = ["name"=>"EINV ACK No.","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['salesInvoice'][] = ["name"=>"EWB No.","class"=>"text-center no_filter noExport","sortable"=>FALSE];

    /* Credit Note Header */
    $data['creditNote'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['creditNote'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['creditNote'][] = ["name"=>"Unit"]; 
	$data['creditNote'][] = ["name"=>"CN Type."];
	$data['creditNote'][] = ["name"=>"CN No."];
	$data['creditNote'][] = ["name"=>"CN Date"];
	$data['creditNote'][] = ["name"=>"Party Name"];
	$data['creditNote'][] = ["name"=>"Taxable Amount"];
	$data['creditNote'][] = ["name"=>"GST Amount"];
    $data['creditNote'][] = ["name"=>"Net Amount"];

    /* Purchase Invoice Header */
    $data['purchaseInvoice'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['purchaseInvoice'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
	$data['purchaseInvoice'][] = ["name"=>"Unit"];
    $data['purchaseInvoice'][] = ["name"=>"Inv No."];
	$data['purchaseInvoice'][] = ["name"=>"Inv Date"];
	$data['purchaseInvoice'][] = ["name"=>"Party Name"];
	$data['purchaseInvoice'][] = ["name"=>"Taxable Amount"];
	$data['purchaseInvoice'][] = ["name"=>"GST Amount"];
    $data['purchaseInvoice'][] = ["name"=>"Net Amount"];

    /* Debit Note Header */
    $data['debitNote'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['debitNote'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE]; 
    $data['debitNote'][] = ["name"=>"Unit"];
	$data['debitNote'][] = ["name"=>"DN Type."];
	$data['debitNote'][] = ["name"=>"DN No."];
	$data['debitNote'][] = ["name"=>"DN Date"];
	$data['debitNote'][] = ["name"=>"Party Name"];
	$data['debitNote'][] = ["name"=>"Taxable Amount"];
	$data['debitNote'][] = ["name"=>"GST Amount"];
    $data['debitNote'][] = ["name"=>"Net Amount"];

    /* GST Expense Header */
    $data['gstExpense'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['gstExpense'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['gstExpense'][] = ["name"=>"Unit"];
	$data['gstExpense'][] = ["name"=>"Inv No."];
	$data['gstExpense'][] = ["name"=>"Inv Date"];
	$data['gstExpense'][] = ["name"=>"Party Name"];
	$data['gstExpense'][] = ["name"=>"Taxable Amount"];
	$data['gstExpense'][] = ["name"=>"GST Amount"];
    $data['gstExpense'][] = ["name"=>"Net Amount"];

    /* GST Income Header */
    $data['gstIncome'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['gstIncome'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['gstIncome'][] = ["name"=>"Unit"];
	$data['gstIncome'][] = ["name"=>"Inv No."];
	$data['gstIncome'][] = ["name"=>"Inv Date"];
	$data['gstIncome'][] = ["name"=>"Party Name"];
	$data['gstIncome'][] = ["name"=>"Taxable Amount"];
	$data['gstIncome'][] = ["name"=>"GST Amount"];
    $data['gstIncome'][] = ["name"=>"Net Amount"];

    /* Journal Entry Header */
    $data['journalEntry'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['journalEntry'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['journalEntry'][] = ["name" => "JV No."];
    $data['journalEntry'][] = ["name" => "JV Date."];
    $data['journalEntry'][] = ["name" => "Ledger Name"];
    $data['journalEntry'][] = ["name" => "Debit", "textAlign" => "right"];
    $data['journalEntry'][] = ["name" => "Credit", "textAlign" => "right"];
    $data['journalEntry'][] = ["name" => "Note"];

    /* Payment Voucher  */
    $data['paymentVoucher'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['paymentVoucher'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['paymentVoucher'][] = ["name" => "Voucher No."];
    $data['paymentVoucher'][] = ["name" => "Voucher Date"];
    $data['paymentVoucher'][] = ["name" => "Party Name"];
    $data['paymentVoucher'][] = ["name" => "Bank/Cash"];
    $data['paymentVoucher'][] = ["name" => "Amount"];
    $data['paymentVoucher'][] = ["name" => "Doc. No."];
    $data['paymentVoucher'][] = ["name" => "Doc. Date"];
    $data['paymentVoucher'][] = ["name" => "Note"];

    /* Tax Payment Voucher */
    $data['taxPayment'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['taxPayment'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['taxPayment'][] = ["name"=>"Unit"];
    $data['taxPayment'][] = ["name" => "Voucher No."];
    $data['taxPayment'][] = ["name" => "Voucher Date"];
    $data['taxPayment'][] = ["name" => "Ledger Name"];
    $data['taxPayment'][] = ["name" => "Bank/Cash"];
    $data['taxPayment'][] = ["name" => "Amount", "style" => "width:5%;", "textAlign" => "center"];
    $data['taxPayment'][] = ["name" => "Doc. No."];
    $data['taxPayment'][] = ["name" => "Doc. Date"];
    $data['taxPayment'][] = ["name" => "Note"];

    /* Tax Payment Voucher */
    $data['tcsTdsPayment'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
	$data['tcsTdsPayment'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['tcsTdsPayment'][] = ["name"=>"Unit"];
    $data['tcsTdsPayment'][] = ["name" => "Quarter"];
    $data['tcsTdsPayment'][] = ["name" => "CHL. No."];
    $data['tcsTdsPayment'][] = ["name" => "Collection/<br>Section Code"];
    $data['tcsTdsPayment'][] = ["name" => "Bank Vou. No."];
    $data['tcsTdsPayment'][] = ["name" => "CHL. Date"];
    $data['tcsTdsPayment'][] = ["name" => "Ledger Name"];
    $data['tcsTdsPayment'][] = ["name" => "Bank/Cash"];
    $data['tcsTdsPayment'][] = ["name" => "Cheque/DD No."];
    $data['tcsTdsPayment'][] = ["name" => "BRS Code"];
    $data['tcsTdsPayment'][] = ["name" => "Vou. Amount"];
    $data['tcsTdsPayment'][] = ["name" => "Settled Amount"];
    $data['tcsTdsPayment'][] = ["name" => "Note"];

    /* Other Expense */
    $data['otherExpense'][] = ["name"=>"Action","class"=>"text-center no_filter noExport","sortable"=>FALSE];
    $data['otherExpense'][] = ["name"=>"#","class"=>"text-center no_filter","sortable"=>FALSE];
    $data['otherExpense'][] = ["name"=>"Vou. No."];
    $data['otherExpense'][] = ["name" => "Vou. Date"];
    $data['otherExpense'][] = ["name" => "Description"];
    $data['otherExpense'][] = ["name" => "Amount"];

    return tableHeader($data[$page]);
}

/* Sales Invoice Table Data */
function getSalesInvoiceData($data){
    $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="'.base_url('salesInvoice/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Sales Invoice'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $print = '<a href="javascript:void(0)" class="btn btn-info btn-edit printDialog permission-approve1" datatip="Print Invoice" flow="down" data-id="'.$data->id.'" data-fn_name="printInvoice"><i class="fa fa-print"></i></a>';

    $ewbPDF = '';$ewbDetailPDF = '';$generateEWB = '';  $cancelEwb = '';$syncEWB = '';

    if(empty($data->eway_bill_no)):
        $syncEwbParam = "{'postData':{'id' : ".$data->id.",'party_id' : ".$data->party_id.",'doc_type':'INV'}, 'modal_id' : 'modal-xl', 'form_id' : 'generateEwayBill', 'title' : 'E-way Bill For Invoice No. : ".($data->trans_number)."', 'fnedit' : 'addEwayBill', 'fnsave' : 'generateEwb', 'js_store_fn' : 'generateEwb','controller':'ebill','syncBtn':1,'button':'close'}";
        $syncEWB = '<a href="javascript:void(0)" class="btn btn-primary" datatip="SYNC E-way Bill" flow="down" onclick="ebillFrom('.$syncEwbParam.');"><i class="mdi mdi-repeat"></i></a>';
    endif;

    if(!empty($data->ewb_status)):
        $ewbPDF = '<a href="'.base_url('ebill/ewb_pdf/'.$data->eway_bill_no).'" target="_blank" datatip="EWB PDF" flow="down" class="btn btn-dark"><i class="fa fa-print"></i></a>';

        $ewbDetailPDF = '<a href="'.base_url('ebill/ewb_detail_pdf/'.$data->eway_bill_no).'" target="_blank" datatip="EWB DETAIL PDF" flow="down" class="btn btn-warning"><i class="fas fa-print"></i></a>';

        if($data->ewb_status == 3):
            $ewbParam = "{'postData':{'id' : ".$data->id.",'party_id' : ".$data->party_id.",'doc_type':'INV'}, 'modal_id' : 'modal-xl', 'form_id' : 'generateEwayBill', 'title' : 'E-way Bill For Invoice No. : ".($data->trans_number)."', 'fnedit' : 'addEwayBill', 'fnsave' : 'generateEwb', 'js_store_fn' : 'generateEwb','controller':'ebill','syncBtn':1}";

            $generateEWB = '<a href="javascript:void(0)" class="btn btn-dark" datatip="E-way Bill" flow="down" onclick="ebillFrom('.$ewbParam.');"><i class="fas fa-truck"></i></a>';
        else:
            $cancelEwbParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'cancelEwb', 'title' : 'Cancel Eway Bill [ Invoice No. : ".($data->trans_number)." ]', 'fnedit' : 'loadCancelEwayBillForm', 'fnsave' : 'cancelEwayBill', 'js_store_fn' : 'cancelEwayBill','controller':'ebill','syncBtn':0,'save_btn_text':'Cancel EWB'}";
            $cancelEwb = '<a href="javascript:void(0)" class="btn btn-danger" datatip="Cancel Eway Bill" flow="down" onclick="ebillFrom('.$cancelEwbParam.');"><i class="fas fa-times"></i></a>';

            $editButton="";$deleteButton="";
        endif;
    else:
        if(empty($data->eway_bill_no)):
            $ewbParam = "{'postData':{'id' : ".$data->id.",'party_id' : ".$data->party_id.",'doc_type':'INV'}, 'modal_id' : 'modal-xl', 'form_id' : 'generateEwayBill', 'title' : 'E-way Bill For Invoice No. : ".($data->trans_number)."', 'fnedit' : 'addEwayBill', 'fnsave' : 'generateEwb', 'js_store_fn' : 'generateEwb','controller':'ebill','syncBtn':1}";

            $generateEWB = '<a href="javascript:void(0)" class="btn btn-dark" datatip="E-way Bill" flow="down" onclick="ebillFrom('.$ewbParam.');"><i class="fa fa-truck"></i></a>';
        endif;
    endif;

    $generateEinv = ""; $einvPdf = "";
    $syncParam = "{'postData':{'id' : ".$data->id.",'party_id' : ".$data->party_id.",'doc_type' : 'INV'}, 'modal_id' : 'modal-xl', 'form_id' : 'generateEinv', 'title' : 'E-Invoice For Invoice No. : ".$data->trans_number."', 'fnedit' : 'addEinvoice', 'fnsave' : 'generateEinvoice', 'js_store_fn' : 'generateEinvoice','controller':'ebill','syncBtn':1,'button':'close'}";
    $syncEinvBtn = '<a href="javascript:void(0)" class="btn btn-primary" datatip="SYNC E-Invoice" flow="down" onclick="ebillFrom('.$syncParam.');"><i class="mdi mdi-repeat"></i></a>';

    if(!empty($data->e_inv_status)):
        $einvPdf = '<a href="'.base_url('ebill/einv_pdf/'.$data->e_inv_no).'" target="_blank" datatip="E-Invoice PDF" flow="down" class="btn btn-dark"><i class="fa fa-print"></i></a>';
        $editButton="";$deleteButton="";
    else:
        if(empty($data->e_inv_no)):
            $einvParam = "{'postData':{'id' : ".$data->id.",'party_id' : ".$data->party_id.",'doc_type' : 'INV'}, 'modal_id' : 'modal-xl', 'form_id' : 'generateEinv', 'title' : 'E-Invoice For Invoice No. : ".$data->trans_number."', 'fnedit' : 'addEinvoice', 'fnsave' : 'generateEinvoice', 'js_store_fn' : 'generateEinvoice','controller':'ebill','syncBtn':1}";

            $generateEinv = '<a href="javascript:void(0)" class="btn btn-dark" datatip="E-Invoice" flow="down" onclick="ebillFrom('.$einvParam.');"><i class="mdi mdi-receipt"></i></a>';
        endif;
    endif;

    $cancelInv = "";
    if($data->trans_status != 3):
        $cancelInvParam = "{'postData':{'id' : ".$data->id.",'doc_type' : 'INV'}, 'modal_id' : 'modal-md', 'form_id' : 'cancelInv', 'title' : 'Cancel Invoice No. : ".$data->trans_number."', 'fnedit' : 'loadCancelInvForm', 'fnsave' : 'cancelEinvoice', 'js_store_fn' : 'cancelEinv','controller':'ebill','syncBtn':0,'save_btn_text':'Cancel Invoice'}";
        $cancelInv = '<a href="javascript:void(0)" class="btn btn-danger" datatip="Cancel Invoice" flow="down" onclick="ebillFrom('.$cancelInvParam.');"><i class="fa fa-times"></i></a>';
    else:
        $editButton = $deleteButton = $generateEinv = $generateEWB = $cancelEwb = $syncEinvBtn = $syncEWB = '';
    endif;//$cancelInv = "";

    if(!empty($data->e_inv_no) && $data->trans_status != 3):
        $data->e_inv_no = '<span class="badge badge-soft-success fs-12">'.$data->e_inv_no.'</span>';
    elseif(!empty($data->e_inv_no) && $data->trans_status == 3):
        $data->e_inv_no = '<span class="badge badge-soft-danger fs-12">'.$data->e_inv_no.'</span>';
    else:
        $data->e_inv_no = '<span class="badge badge-soft-danger fs-12">Pending</span>';
    endif;

    if(!empty($data->eway_bill_no) && $data->trans_status != 3):
        $data->eway_bill_no = '<span class="badge badge-soft-success fs-12">'.$data->eway_bill_no.'</span>';
    elseif(!empty($data->eway_bill_no) && $data->trans_status == 3):
        $data->eway_bill_no = '<span class="badge badge-soft-danger fs-12">'.$data->eway_bill_no.'</span>';
    else:
        $data->eway_bill_no = '<span class="badge badge-soft-danger fs-12">Pending</span>';
    endif;

    $whatsapp = "";
    $whatsapp = '<a href="javascript:void(0)" class="btn btn-success sendWhatsappMessage" datatip="Send Message" flow="down" data-ref_id="'.$data->id.'" data-subject="Send Invoice" data-js_fn_name="sendInvoiceInWhatsapp"  data-doc_name="TaxInv"><i class="fab fa-whatsapp" style="font-size:18px;"></i></a>';

    $action = getActionButton($print.$whatsapp.$ewbPDF.$ewbDetailPDF.$generateEWB.$syncEWB.$cancelEwb.$einvPdf.$generateEinv.$syncEinvBtn.$cancelInv.$editButton.$deleteButton);

    $sr_no = '<input type="checkbox" id="row-action-'.$data->id.'" class="filled-in chk-col-success row-action-check" value="'.$data->id.'"><label for="row-action-'.$data->id.'"> '.$data->sr_no.' </label>';

    return [$action,$sr_no,$data->ship_to,$data->company_code,$data->trans_number,formatDate($data->trans_date),$data->party_name,moneyFormatIndia($data->taxable_amount),moneyFormatIndia($data->gst_amount),moneyFormatIndia($data->net_amount),$data->e_inv_no,$data->eway_bill_no];
}

/* Credit Note Table Data */
function getCreaditNoteData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('creditNote/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Credit Note'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $print = '<a href="javascript:void(0)" class="btn btn-warning btn-edit printDialog permission-approve1" datatip="Print Invoice" flow="down" data-id="'.$data->id.'" data-fn_name="printCreditNote"><i class="fa fa-print"></i></a>';

    $generateEinv = ""; $einvPdf = "";
    if(!empty($data->e_inv_status)):
        $einvPdf = '<a href="'.base_url('ebill/einv_pdf/'.$data->e_inv_no).'" target="_blank" datatip="E-Invoice PDF" flow="down" class="btn btn-dark"><i class="fa fa-print"></i></a>';
        $editButton="";$deleteButton="";
    else:
        if(empty($data->e_inv_no)):
            $einvParam = "{'postData':{'id' : ".$data->id.",'party_id' : ".$data->party_id.",'doc_type' : 'CRN'}, 'modal_id' : 'modal-xl', 'form_id' : 'generateEinv', 'title' : 'E-Invoice For Invoice No. : ".$data->trans_number."', 'fnedit' : 'addEinvoice', 'fnsave' : 'generateEinvoice', 'js_store_fn' : 'generateEinvoice','controller':'ebill','syncBtn':1}";

            $generateEinv = '<a href="javascript:void(0)" class="btn btn-dark" datatip="E-Invoice" flow="down" onclick="ebillFrom('.$einvParam.');"><i class="mdi mdi-receipt"></i></a>';
        endif;
    endif;

    $cancelInv = "";
    if($data->trans_status != 3):
        $cancelInvParam = "{'postData':{'id' : ".$data->id.",'doc_type' : 'CRN'}, 'modal_id' : 'modal-md', 'form_id' : 'cancelInv', 'title' : 'Cancel Credit Note No. : ".$data->trans_number."', 'fnedit' : 'loadCancelInvForm', 'fnsave' : 'cancelEinvoice', 'js_store_fn' : 'cancelEinv','controller':'ebill','syncBtn':0,'save_btn_text':'Cancel Credit Note'}";
        $cancelInv = '<a href="javascript:void(0)" class="btn btn-danger" datatip="Cancel Credit Note" flow="down" onclick="ebillFrom('.$cancelInvParam.');"><i class="fa fa-times"></i></a>';
    else:
        $editButton="";$deleteButton="";$generateEinv = "";$generateEWB = '';  $cancelEwb = '';
    endif;

    $whatsapp = "";
    $whatsapp = '<a href="javascript:void(0)" class="btn btn-success sendWhatsappMessage" datatip="Send Message" flow="down" data-ref_id="'.$data->id.'" data-subject="Send C.N." data-js_fn_name="sendInvoiceInWhatsapp"  data-doc_name="C.N."><i class="fab fa-whatsapp" style="font-size:18px;"></i></a>';

    $action = getActionButton($print.$whatsapp.$einvPdf.$generateEinv.$cancelInv.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->company_code,$data->order_type,$data->trans_number,formatDate($data->trans_date),$data->party_name,moneyFormatIndia($data->taxable_amount),moneyFormatIndia($data->gst_amount),moneyFormatIndia($data->net_amount)];
}

/* Purchase Invoice Table Data */
function getPurchaseInvoiceData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('purchaseInvoice/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Purchase Invoice'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    
    $printBtn = '<a class="btn btn-success btn-info" href="'.base_url('purchaseInvoice/printInvoice/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print" ></i></a>';

    $action = getActionButton($printBtn.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->company_code,$data->trans_number,formatDate($data->trans_date),$data->party_name,moneyFormatIndia($data->taxable_amount),moneyFormatIndia($data->gst_amount),moneyFormatIndia($data->net_amount)];
}

/* Debit Note Table Data */
function getDebitNoteData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('debitNote/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Debit Note'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $print = '<a href="javascript:void(0)" class="btn btn-warning btn-edit printDialog permission-approve1" datatip="Print Debit Note" flow="down" data-id="'.$data->id.'" data-fn_name="printDebitNote"><i class="fa fa-print"></i></a>';

    $ewbPDF = '';$ewbDetailPDF = '';$generateEWB = '';  $cancelEwb = '';
    if(!empty($data->ewb_status)):
        $ewbPDF = '<a href="'.base_url('ebill/ewb_pdf/'.$data->eway_bill_no).'" target="_blank" datatip="EWB PDF" flow="down" class="btn btn-dark"><i class="fa fa-print"></i></a>';

        $ewbDetailPDF = '<a href="'.base_url('ebill/ewb_detail_pdf/'.$data->eway_bill_no).'" target="_blank" datatip="EWB DETAIL PDF" flow="down" class="btn btn-warning"><i class="fas fa-print"></i></a>';

        if($data->ewb_status == 3):
            $ewbParam = "{'postData':{'id' : ".$data->id.",'party_id' : ".$data->party_id.",'doc_type':'DBN'}, 'modal_id' : 'modal-xl', 'form_id' : 'generateEwayBill', 'title' : 'E-way Bill For Invoice No. : ".($data->trans_number)."', 'fnedit' : 'addEwayBill', 'fnsave' : 'generateEwb', 'js_store_fn' : 'generateEwb','controller':'ebill','syncBtn':1}";

            $generateEWB = '<a href="javascript:void(0)" class="btn btn-dark" datatip="E-way Bill" flow="down" onclick="ebillFrom('.$ewbParam.');"><i class="fas fa-truck"></i></a>';
        else:
            $cancelEwbParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'modal-md', 'form_id' : 'cancelEwb', 'title' : 'Cancel Eway Bill [ Invoice No. : ".($data->trans_number)." ]', 'fnedit' : 'loadCancelEwayBillForm', 'fnsave' : 'cancelEwayBill', 'js_store_fn' : 'cancelEwayBill','controller':'ebill','syncBtn':0,'save_btn_text':'Cancel EWB'}";
            $cancelEwb = '<a href="javascript:void(0)" class="btn btn-danger" datatip="Cancel Eway Bill" flow="down" onclick="ebillFrom('.$cancelEwbParam.');"><i class="fas fa-times"></i></a>';

            $editButton="";$deleteButton="";
        endif;
    else:
        if(empty($data->eway_bill_no)):
            $ewbParam = "{'postData':{'id' : ".$data->id.",'party_id' : ".$data->party_id.",'doc_type':'DBN'}, 'modal_id' : 'modal-xl', 'form_id' : 'generateEwayBill', 'title' : 'E-way Bill For Invoice No. : ".($data->trans_number)."', 'fnedit' : 'addEwayBill', 'fnsave' : 'generateEwb', 'js_store_fn' : 'generateEwb','controller':'ebill','syncBtn':1}";

            $generateEWB = '<a href="javascript:void(0)" class="btn btn-dark" datatip="E-way Bill" flow="down" onclick="ebillFrom('.$ewbParam.');"><i class="fa fa-truck"></i></a>';
        endif;
    endif;

    $generateEinv = ""; $einvPdf = "";
    if(!empty($data->e_inv_status)):
        $einvPdf = '<a href="'.base_url('ebill/einv_pdf/'.$data->e_inv_no).'" target="_blank" datatip="E-Invoice PDF" flow="down" class="btn btn-dark"><i class="fa fa-print"></i></a>';
        $editButton="";$deleteButton="";
    else:
        if(empty($data->e_inv_no)):
            $einvParam = "{'postData':{'id' : ".$data->id.",'party_id' : ".$data->party_id.",'doc_type' : 'DBN'}, 'modal_id' : 'modal-xl', 'form_id' : 'generateEinv', 'title' : 'E-Invoice For Invoice No. : ".$data->trans_number."', 'fnedit' : 'addEinvoice', 'fnsave' : 'generateEinvoice', 'js_store_fn' : 'generateEinvoice','controller':'ebill','syncBtn':1}";

            $generateEinv = '<a href="javascript:void(0)" class="btn btn-dark" datatip="E-Invoice" flow="down" onclick="ebillFrom('.$einvParam.');"><i class="mdi mdi-receipt"></i></a>';
        endif;
    endif;

    $cancelInv = "";
    if($data->trans_status != 3):
        $cancelInvParam = "{'postData':{'id' : ".$data->id.",'doc_type' : 'DBN'}, 'modal_id' : 'modal-md', 'form_id' : 'cancelInv', 'title' : 'Cancel Debit Note No. : ".$data->trans_number."', 'fnedit' : 'loadCancelInvForm', 'fnsave' : 'cancelEinvoice', 'js_store_fn' : 'cancelEinv','controller':'ebill','syncBtn':0,'save_btn_text':'Cancel Debit Note'}";
        $cancelInv = '<a href="javascript:void(0)" class="btn btn-danger" datatip="Cancel Debit Note" flow="down" onclick="ebillFrom('.$cancelInvParam.');"><i class="fa fa-times"></i></a>';
    else:
        $editButton="";$deleteButton="";$generateEinv = "";$generateEWB = '';  $cancelEwb = '';
    endif;

    $action = getActionButton($print.$ewbPDF.$ewbDetailPDF.$generateEWB.$cancelEwb.$einvPdf.$generateEinv.$cancelInv.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->company_code,$data->order_type,$data->trans_number,formatDate($data->trans_date),$data->party_name,moneyFormatIndia($data->taxable_amount),moneyFormatIndia($data->gst_amount),moneyFormatIndia($data->net_amount)];
}

/* GST Expense Table Data */
function getGstExpenseData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('gstExpense/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Expese'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->company_code,$data->trans_number,formatDate($data->trans_date),$data->party_name,moneyFormatIndia($data->taxable_amount),moneyFormatIndia($data->gst_amount),moneyFormatIndia($data->net_amount)];
}

/* GST Income Table Data */
function getGstIncomeData($data){
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('gstIncome/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Income'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);

    return [$action,$data->sr_no,$data->company_code,$data->trans_number,formatDate($data->trans_date),$data->party_name,moneyFormatIndia($data->taxable_amount),moneyFormatIndia($data->gst_amount),moneyFormatIndia($data->net_amount)];
}

/* Journal Entry Data */
function getJournalEntryData($data){
    //$editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('journalEntry/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';

    if($data->order_type == "HAVALA"):
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="'.base_url('journalEntry/edit/'.$data->id).'" datatip="Edit" flow="down" ><i class="mdi mdi-square-edit-outline"></i></a>';
    else:
        $editParam = "{'postData':{'id' : ".$data->id."}, 'init_action' : 'editJournalEntry', 'call_function':'edit'}";
        $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    endif;

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Journal Entry'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

	$printVoucher = '<a href="'.base_url('journalEntry/printJV/'.$data->id).'" type="button" class="btn btn-primary" datatip="Print JV" flow="down" target="_blank"><i class="fas fa-print"></i></a>';

    $action = getActionButton($printVoucher . $editButton . $deleteButton);
	$debit = $credit = "";
    if($data->c_or_d == 'DR'){$debit = moneyFormatIndia($data->amount);}else{$credit = moneyFormatIndia($data->amount);}

    return [$action, $data->sr_no,$data->trans_number, formatDate($data->trans_date), $data->acc_name, $debit, $credit, $data->remark];
}

/* Payment Voucher Data */
function getPaymentVoucher($data){
    $editButton = '';$deleteButton = '';

    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Voucher'}";
    $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editVoucher', 'title' : 'Update Voucher','call_function':'edit'}";
    
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $print = '';    
    $print = '<a class="btn btn-dribbble" href="'.base_url('paymentVoucher/printPaymentVoucher/'.$data->id).'" target="_blank" datatip="Print" flow="down"><i class="fas fa-print"></i></a>';
    
	
    $action = getActionButton($print.$editButton.$deleteButton);

    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->opp_acc_name,$data->vou_acc_name,moneyFormatIndia($data->amount),$data->doc_no,((!empty($data->doc_date))?formatDate($data->doc_date):""),$data->remark];
}

/* Tax Payment Data */
function getTaxPaymentData($data){
    $editButton = '';$deleteButton = '';

    $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-xl-modal', 'form_id' : 'editVoucher', 'title' : 'Update Voucher','call_function':'edit'}";
    $editButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
    
    $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Voucher'}";
    $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';

    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->company_code,$data->trans_number,formatDate($data->trans_date),$data->opp_acc_name,$data->vou_acc_name,moneyFormatIndia($data->net_amount),$data->doc_no,((!empty($data->doc_date))?formatDate($data->doc_date):""),$data->remark];
}

/* TCS/TDS Payment Data */
function getTcsTdsVoucherData($data){
    $editButton = '';$deleteButton = '';

    if(empty(floatval($data->settled_amount))):
        $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-lg-modal', 'form_id' : 'editVoucher', 'title' : 'Update Voucher','call_function':'editTcsTdsVoucher','fnsave':'saveTcsTdsVoucher'}";
        $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Voucher','fndelete':'deleteTcsTdsVoucher'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;

    $settlementParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-lg-modal', 'form_id' : 'voucherSettlement', 'title' : 'Voucher Settlement','call_function':'voucherSettlement','fnsave':'saveSettledTransaction','js_store_fn':'customStore'}";
    $settlementButton = '<a class="btn btn-success btn-edit permission-modify" href="javascript:void(0)" datatip="Settlement" flow="down" onclick="modalAction('.$settlementParam.');"><i class="mdi mdi-link"></i></a>';

    $action = getActionButton($settlementButton.$editButton.$deleteButton);
    return [$action,$data->sr_no,$data->company_code,$data->memo_type,$data->trans_no,$data->order_type,$data->trans_number,formatDate($data->trans_date),$data->opp_acc_name,$data->vou_acc_name,$data->doc_no,$data->ref_by,moneyFormatIndia($data->net_amount),moneyFormatIndia($data->settled_amount),$data->remark];
}

/* Other Expense Data */
function getOtherExpenseData($data){
    $editButton = '';$deleteButton = '';

    if($data->trans_status == 0):
        $editParam = "{'postData':{'id' : ".$data->id."}, 'modal_id' : 'bs-right-md-modal', 'form_id' : 'otherExpenseForm', 'title' : 'Update Expense','call_function':'edit','fnsave':'save'}";
        $editButton = '<a class="btn btn-warning btn-edit permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');"><i class="mdi mdi-square-edit-outline"></i></a>';
        
        $deleteParam = "{'postData':{'id' : ".$data->id."},'message' : 'Voucher','fndelete':'delete'}";
        $deleteButton = '<a class="btn btn-danger btn-delete permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down"><i class="mdi mdi-trash-can-outline"></i></a>';
    endif;

    $action = getActionButton($editButton.$deleteButton);
    return [$action,$data->sr_no,$data->trans_number,formatDate($data->trans_date),$data->remark,moneyFormatIndia($data->net_amount)];
}
?>