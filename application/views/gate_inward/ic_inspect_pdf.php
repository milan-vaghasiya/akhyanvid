<div class="row">
	<div class="col-12">
		<table class="table item-list-bb" style="margin-top:2px;">
			<tr>
				<td><b>Supplier Name :  </b><?=(!empty($inInspectData->party_name)) ? $inInspectData->party_name:""?></td>
				<td><b>Receiving Date :  </b><?=(!empty($inInspectData->trans_date)) ? formatDate($inInspectData->trans_date):""?></td>
			</tr>
			<tr>
				<td><b>Item Name :  </b><?=(!empty($inInspectData->item_name)) ? $inInspectData->item_name:""?></td>
				<td><b>Quality :  </b><?=(!empty($inInspectData->qty)) ?$inInspectData->qty:""?></td>
			</tr>
			<tr>
				<td><b>Invoice No :  </b><?=(!empty($inInspectData->inv_no)) ? $inInspectData->inv_no:""?></td>
				<td><b>Invoice Date :   </b><?=(!empty($inInspectData->inv_date)) ? formatDate($inInspectData->inv_date):""?></td>
			</tr>
			<tr>
				<td colspan="2"><b>Purchase No :  </b><?=(!empty($inInspectData->po_no)) ?$inInspectData->po_no:""?> </td>
			</tr>
		</table>

		<table class="table item-list-bb" style="margin-top:10px;">
		<?php $sample_size= (!empty($observation->sampling_qty))?floatval($observation->sampling_qty):5 ?>
		<tr style="text-align:center;">
			<th rowspan="2" >#</th>
			<th rowspan="2">Parameter</th>
			<th rowspan="2">Specification</th>
			<th rowspan="2">Instrument</th>
			<th colspan="<?= $sample_size?>">Observation on Samples</th>
			<th rowspan="2" >Result</th>
		</tr>
		<tr style="text-align:center;">
			<?php for($j=1;$j<=$sample_size;$j++):?> 
				<th><?= $j ?></th>
			<?php endfor;?>    
		</tr>
			<?php
				$tbodyData="";$i=1; 
				if(!empty($paramData)):
					
					foreach($paramData as $row):
						$obj = New StdClass;
						if(!empty($observation)):
							$obj = json_decode($observation->observation_sample);
						endif;
						// print_r($obj);
						$flag=false;$paramItems = '';
							$paramItems.= '<tr>
										<td style="text-align:center;">'.$i.'</td>
										<td style="text-align:center;">'.$row->parameter.'</td>
										<td style="text-align:center;">'.$row->specification.'</td>   
										<td style="text-align:center;">'.$row->instrument.'</td>';
							for($c=0;$c<$sample_size;$c++):
								if(!empty($obj->{$row->id})):
									$paramItems .= '<td style="text-align:center;">'.$obj->{$row->id}[$c].'</td>';
								endif;
								if(!empty($obj->{$row->id}[$c])){$flag=true;}
							endfor;
							if(!empty($obj->{$row->id})):
								$paramItems .= '<td style="text-align:center;">'.$obj->{$row->id}[$sample_size].'</td></tr>';
							endif;
							
							if($flag):
								$tbodyData .= $paramItems;$i++;
							endif;
					endforeach;
				else:
					$tbodyData.= '<tr><td colspan="11" style="text-align:center;">No Data Found</td></tr>';
				endif;
				echo $tbodyData;
			?>
		</table>
		
	</div>
</div>