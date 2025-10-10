
<div class="row">
	<div class="col-12">
		<table class="table  item-list-bb" style="margin-top:2px;">
			<tr  class="bg-light text-center">
				<th style="width:20%;font-size:0.8rem;">Date</th>
				<th style="width:20%;font-size:0.8rem;">PRC No</th>
				<th style="width:20%;font-size:0.8rem;">PRC Date</th>
				<th style="width:20%;font-size:0.8rem;">Setup</th>
			</tr>
			<tr class=" text-center">
				<td style="font-size:0.8rem;"><?=(!empty($lineInspectData->insp_date)) ? formatDate($lineInspectData->insp_date) : ""?></td>
				<td style="font-size:0.8rem;"><?=(!empty($lineInspectData->prc_number)) ? $lineInspectData->prc_number : ""?></td>
				<td style="font-size:0.8rem;"><?=(!empty($lineInspectData->prc_date)) ? formatDate($lineInspectData->prc_date) : ""?></td>
				<td style="font-size:0.8rem;"><?=(!empty($lineInspectData->process_name)) ?$lineInspectData->process_name:""?></td>
			</tr>
			<tr class="text-left">
				<td colspan="2" style="font-size:0.8rem;"><b>Part : </b><?=(!empty($lineInspectData->item_name)) ? $lineInspectData->item_name : ""?></td>
				<td colspan="2" style="font-size:0.8rem;"><b>Machine : </b><?=(!empty($lineInspectData->machine_name)) ? $lineInspectData->machine_name : ""?></td>
			</tr>
		</table>
		
		<table class="table item-list-bb" style="margin-top:10px;">
			<thead>
				<tr style="text-align:center;" class="bg-light">
					<th rowspan="2" style="width:5%;">#</th>
					<th rowspan="2">Parameter</th>
					<th rowspan="2">Specification</th>
					<th rowspan="2">Instrument</th>
					<th colspan="<?= $rcount ?>">Observation Samples</th>
				</tr>
				<tr class="bg-light">
					<?php echo $theadData; ?>
				</tr>
			</thead>
			<tbody>
				<?php echo $tbodyData; ?>
			</tbody>
		</table>
		
	</div>
</div>