<?php 
	$days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"); 
?>

<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				
	    				<?php $rows = $guildData->result();
	    						$rings = $ringData->result();
	    				 ?>
	    				
	    				<div id="guildieButtonBar">
	    					
	    					<center><div id="guildEntryCount"><?=count($rows)?> Member<?=count($rows)==1?'':'s'?></div></center>
	    					<br>
	    					<input onkeyup="guildieSearch(this.value)" type="text" name="" value="" placeholder="Search…" />
	    					
	    					<?php if ($guildie && $guildie->guildieadmin) { ?>
	    					<div id="addButton" onclick="go('guildies/add');">+</div>
	    					<?php } ?>
	    					
	    				</div>
	    				
	    				
	    				<table class="guildie_table guildie_titles">
	    				<tr>
	    					<td width="20%"><div class="guildie_header"></div></td>
	    					<td width="35%"><div class="guildie_header">Name</div></td>
	    					<td width="25%"><div class="guildie_header">Ring Slot</div></td>
	    					<td width="10%"><div class="guildie_header">Year</div></td>
	    					<td width="10%"><div class="guildie_header"></div></td>
	    				</tr>
	    				</table>
	    				
						<div class="guildie_divider"></div>
	    				
	    				<table class="guildie_table" id="guildieEntries">
	    					
	    					<?php
	    						for ($i=0;$i<count($rows);$i++) { 
	    							$row = $rows[$i];
	    					?>
	    					<tr id="<?php echo($i==0?'guildieRowProxy':'');?>" class="guildie_results_row " tag="<?=$row->id?>">
	    						
	    						<td width="20%">
	    							<div class="guildie_data guildie_photo" style="background-image:url('<?= base_url()."assets/images/GuildPhotos/".$row->photo?>')">
	    							</div>
	    						</td>
	    						<td width="35%"><div class="guildie_data guildie_name">
	    						
	    						<?php if ($row->nickname) {
	    							echo $row->nickname;
	    						} else {
	    							echo $row->firstname;
	    						}
	    						?>
	    						
	    						
	    						 <?=$row->lastname?>
	    						<br>
	    						<font style="font-size:8pt;color:gray"><?= $row->phone ?></font>
	    						</div></td>
	    						
	    						
	    						<td width="25%"><div class="guildie_data guildie_ring">
	    						
	    						<?php
	    									
										$aRing = 0;
										for ($ringIndex=0;$ringIndex<count($rings);$ringIndex++) {
											
											if ($rings[$ringIndex]->id == $row->ringID) {
												$aRing = $rings[$ringIndex];
												break;
											}											
										}
	    						
	    							   if ($aRing) {
	    							   		$hour = $aRing->hour;
	    							   		$AM = $hour>=12?"PM":"AM";
	    							   		$hour = $hour>12?$hour-12:$hour;
	    							   		
	    							   		$ringMinute = $aRing->minute;
	    							   		if ($ringMinute == 0) $ringMinute = "00";
	    							   		
	    							   	 	echo $days[$aRing->day]." ".$hour.":".$ringMinute." ".$AM;
	    							   	} else {
	    							   		echo "No Ring";
	    							   }
	    							?>
	    							
	    						</div></td>
	    						<td width="10%"><div class="guildie_data guildie_year"><?=$row->year?></div></td>
	    						<td width="10%">
	    						
	    						<?php	    				
	    						
	    						// Conditionally enable editing for guildie admins only, or yourself
	    						if ( ($guildie && $guildie->guildieadmin) || ($guildie && $row->netid == $guildie->netid)) {
	    						?>
	    						<div class="guildie_data guildie_edit"><a href="<?=base_url()?>index.php/guildies/edit/<?=$row->id?>">Edit</a></div>
	    						
	    						<?php
	    						}
	    						
	    						?>
	    						
	    						</td>

	    					</tr>
	    					
	    					<?php } ?>
	    				
	    				
	    				
	    				</table>
	    				
	    				
	    				
	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->