<div class="row">
	<div class="col-md-12">
		<table class="table item-list-bb" style="margin-top:2px;">
			<tr>
				<td class="text-left"><b>Discription</b></td><td><?=$insData->item_name?></td>
				<th class="text-left">Code</th><td><?=$insData->item_code?></td>
			</tr>
			<tr>
				<th class="text-left">Make</th>
				<td class="text-left" colspan="3"><?=$insData->make_brand?></td>
			</tr>
			<tr>
				<th class="text-left"><b>Cali. Frequency</b></th>
                <td class="text-left" colspan="3"><?=$insData->cal_freq?>(Month)</td>
			</tr>
			<?php 
			    if($insData->status == 4):
                    echo '<tr>';
                    echo '<th class="text-left"> Reject Date</th>';
                    echo '<td class="text-left">'.$insData->rejected_at.'</td>';
                    echo '<th class="text-left"> Reject Reason </th>';
                    echo '<td class="text-left">'.$insData->reject_reason.'</td>';
                    echo '</tr>';
                endif; 
            ?>
		</table>

        <table class="table item-list-bb" style="margin-top:10px;">
			<tr>
				<th style="width:40px;">Cali. Date</th>
				<th style="width:100px;">Cali. Agency</th>
				<th style="width:40px;">Cali. No.</th>
				<th style="width:40px;">Cali. Due Date</th>
			</tr>
			<?php
                if(!empty($calData)):
					foreach($calData as $row):
						if ($row->cal_agency == 0) {
							$cal_agency = 'In House';
						} 
						echo '<tr class="text-center" height="32">';
							echo '<td>'.formatDate($row->cal_date).'</td>';
							echo '<td>'.$cal_agency.'</td>';
							echo '<td>'.$row->cal_certi_no.'</td>';
							echo '<td>'.date('d-m-Y',strtotime($row->cal_date.' +'.$insData->cal_freq.' Months')).'</td>';
						echo '</tr>';
					endforeach;
				endif;
			?>
		</table>
	</div>
</div>