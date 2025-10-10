<div class="row">
	<div class="col-12">
		<table class="table item-list-bb" style="margin-top:2px;">
			<tr class="bg-light text-center">
				<th style="width:20%">Date</th>
				<th style="width:20%">FIR No</th>
				<th style="width:20%">PRC No</th>
				<th style="width:20%">Ok Qty</th>
				<th style="width:20%">Rej Qty</th>
			</tr>
            <tr class=" text-center">
				<td><?=(!empty($firData->insp_date)) ? formatDate($firData->insp_date) : ""?></td>
				<td><?=(!empty($firData->trans_number)) ? $firData->trans_number : ""?></td>
				<td><?=(!empty($firData->prc_number)) ? $firData->prc_number : ""?></td>
				<td><?=(!empty($firData->ok_qty)) ? floatval($firData->ok_qty) : ""?></td>
				<td><?=(!empty($firData->rej_qty)) ? floatval($firData->rej_qty) : ""?></td>
				
			</tr>
			<tr class="text-left">
				<th class="bg-light">Rev No</th>
				<td><?=(!empty($firData->rev_no)) ? ($firData->rev_no) : ""?></td>
				<th class="bg-light">Item</th>
				<td colspan="2"><?=(!empty($firData->item_name)) ? $firData->item_name : ""?></td>
			</tr>
		</table>

		<table class="table item-list-bb" style="margin-top:10px;">
		<?php $sample_size= (!empty($firData->sampling_qty))?floatval($firData->sampling_qty):5 ?>
			<thead>
				<tr style="text-align:center;"  class="bg-light">
					<th rowspan="2" >#</th>
					<th rowspan="2">Parameter</th>
					<th rowspan="2">Specification</th>
					<th rowspan="2" style="width:10%">Instrument</th>
					<th colspan="<?= $sample_size?>">Observation on Samples</th>
				</tr>
				<tr style="text-align:center;"  class="bg-light">
					<?php for($j=1;$j<=$sample_size;$j++):?> 
						<th><?= $j ?></th>
					<?php endfor;?>    
				</tr>
			</thead>
			<tbody>
			<?php
				$tbodyData="";$i=1; 
				if(!empty($paramData)):
					foreach($paramData as $row):
						$obj = New StdClass;
						if(!empty($firData)):
							$obj = json_decode($firData->observation_sample);
						endif;
						$flag=false;$paramItems = '';
							$paramItems.= '<tr>
										<td style="text-align:center;">'.$i.'</td>
										<td style="text-align:left;">'.$row->parameter.'</td>
										<td style="text-align:left;">'.$row->specification.'</td>   
										<td style="text-align:left;">'.$row->instrument.'</td>';
							for($c=0;$c<$sample_size;$c++):
								if(!empty($obj->{$row->id})):
									$paramItems .= '<td style="text-align:center;">'.$obj->{$row->id}[$c].'</td>';
								endif;
								if(!empty($obj->{$row->id}[$c])){$flag=true;}
							endfor;
							if($flag):
								$tbodyData .= $paramItems;$i++;
							endif;
					endforeach;
				else:
					$tbodyData.= '<tr><td colspan="11" style="text-align:center;">No Data Found</td></tr>';
				endif;
				echo $tbodyData;
			?>
			</tbody>
			
		</table>
	</div>
</div>