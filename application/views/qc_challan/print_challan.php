<div class="row">
	<div class="col-12">
		<table class="table"><tr><td class="fs-18 text-center" style="letter-spacing: 2px;font-weight:bold;padding:0px !important;">QC CHALLAN</td></tr></table>
		
		<table class="table top-table-border" style="margin-top:2px;">
			
			<tr class="text-left">
				<!-- <th style="width:20%;">Issue From</th> -->
				<!-- <td style="width:30%;">
				    <?php
				        echo ((!empty($challanData->dept_name))?$challanData->dept_name:'');
				    ?>
				</td> -->
			    <th style="width:20%;">Challan No</th><td style="width:30%;"><?=(!empty($challanData->trans_no)) ? $challanData->trans_prefix.$challanData->trans_no : ""?></td>
				<th style="width:20%;">Issue To</th>
				<td style="width:30%;">
				    <?php
				        if($challanData->challan_type == 1){
				            echo ((!empty($challanData->issue_to))?$challanData->issue_to:'IN-HOUSE');
				        }else{
				            echo ((!empty($challanData->party_name))?$challanData->party_name:'IN-HOUSE');
				        }
				    ?>
				</td>
			</tr>
			<tr class="text-left">
				<th>Challan Date</th>
				<td colspan="3"><?=(!empty($challanData->trans_date)) ? formatDate($challanData->trans_date) : ""?></td>
			</tr>
			<tr class="text-left">
				<th>Handover To</th>
				<td colspan="3"><?=$challanData->emp_name?></td>
			</tr>
			<tr class="text-left">
				<th>Remark</th>
				<td colspan="3"><?=$challanData->remark?></td>
			</tr>
			
		</table>
		
		<table class="table item-list-bb" style="margin-top:10px;">
			<tr>
				<th style="width:40px;">No.</th>
				<th class="text-center">Item Name</th>
				<th class="text-center" >Item Code</th>
			</tr>
			<?php
				$i=1;
				if(!empty($challanData->itemData)):
					foreach($challanData->itemData as $row):
						echo '<tr>';
							echo '<td class="text-center">'.$i++.'</td>';
							echo '<td class="text-center">'.$row->item_name.'</td>';
							echo '<td class="text-center">'.$row->item_code.'</td>';
						echo '</tr>';
						
					endforeach;
				endif;
			?>
			
		</table>
		
	</div>
</div>  