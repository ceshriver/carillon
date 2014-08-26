<?php 
	$days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"); 
?>

<style>
	table {
		background-color: white;
	}
	
	.ringtable {
		border: solid thin #036;
		width: 540px;
		color: #036;
		Verdana,Arial,Helvetica,sans-serif;
		font-size: 12pt;
		text-align: center;
	}
	
	.ringtablerow {
		height: 50px;
	}
	
	.ringtablerowtitles {
		height: 20px;
		border: none;
		text-align: center;
	}
	
	
	.ringtable tr td {
		width: 70px;
		border-bottom: solid thin rgba(200,200,200,0.4);
		border-right: solid thin rgba(200,200,200,0.4);
		//border-top: solid thin rgba(200,200,200,0.4);
	}
	
	.ringtabletitles {
		border-top: none;
		border-right: none;
		border-left: none;
	}
	
	.ringtable tr td:first-child {
		border-right: solid thin #036;
	}
	
	.ringtabletitles tr td:first-child {
		border-right: none;
	}
	
	.ringtabletitles tr td {
		border: none;
		text-align: center;
	}
	
	
	
	h2 {
		color: #036;
		font-size: 20pt;
	}
	
	.guildie_name {
		font-size: 14pt;
	}
	.guildie_office {
		font-size: 8pt;
	}
	.guildie_email {
		text-decoration: none;
		color: #036;
		font-size: 8pt;
	}
	.guildie_email:hover {
		color: rgb(160,160,160);
	}
	.guildie_email:active {
		color: rgba(120,120,120,0.6);
	}
	
	
	.guildie_college .guildie_major {
		font-size: 8pt;
	}
	
	.ring_table_guildie {
		font-size: 8pt;
		text-align: left;
		margin-left: 4px;
		margin-bottom: 2px;
	}
	.ring_table_guildie a {
		text-decoration: none;
		color: #036;
	}
	.ring_table_guildie a:hover {
		color: rgb(200,200,200);
	}
	
	.guildie_bio {
		margin-top: 6px;
		margin-right: 4px;
		display: none;
	}
	
	.ringTable_darkRow {
		background-color: rgba(250,214,164,0.1);	
	}
	
	
	
	.ringerList {
		text-decoration:none;
	}
	.ringerList a:hover {
		text-decoration:none;
		color:red;
		cursor:pointer;
	}
	
	
</style>


<script type="text/javascript">
var elem2;

function addToRing(elem) {
	var proxy = $("#guildieSelectProxy").clone();
	//$("#guildieSelect").remove();
	
	proxy.attr('id','guildieSelect');
	proxy.css('display','block');
	
	
	elem2 = elem;

	var ringID = $(elem).attr('tag');
	
	//names of the drop downs will be in the following format
	// "add_13_0"
	// "add"  +  ringID + (index of dropDown)
	var numElem = $(elem).parent().parent().find("select").length;
	proxy.children("select").attr('name', "add_"+ringID+"_"+numElem);
	
	/*var items = proxy.children("select").children('option');
	for (var i=0;i<items.length;i++) {
		var guildieID = $(items[i]).attr("value");
		//console.log(guildieID);
		var newValue = ringID + "." + guildieID;
		$(items[i]).attr('value',newValue);
	}*/
	
	$(elem).parent().before(proxy);
	
	
	$("#updateButton").css("display","block");
}

function removeFromRing(elem) {
	var listItem = $(elem).parent();
	if (listItem.children("input").length == 0) {
		//Remove this guildie
		var guildieID = $(elem).attr('id');
		var ringID = $(elem).attr('tag');
		var hiddenInput = ('<input type="hidden" name="remove_' + ringID + "_" + guildieID + '" value="'+guildieID+'"/>');
		$(elem).before(hiddenInput);
		$(elem).css('text-decoration','line-through');
	} else {
		//Re-enable this guildie
		var hiddenInput = listItem.children("input");
		hiddenInput.remove();
		$(elem).css('text-decoration','');
	}
	$("#updateButton").css("display","block");
}



</script>

<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<form action='schedule/saveSchedule' method="post" accept-charset="utf-8" id="scheduleForm">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				
	    				<?php $rows = $ringData->result();
	    						$guildies = $guildData->result();
	    				 ?>
	    				
	    				<div id="scheduleButtonBar">
	    					
	    					<center><div id="scheduleEntryCount"><?=count($rows)?> Ring Slot<?=count($rows)==1?'':'s'?></div></center>
	    					<br>
	    					<?php if ($user->scheduleadmin) { ?>
	    					<div id="addButton" onclick="go('schedule/add');">+</div>
	    					<div id="updateButton" class="menuButton" style="display:none" onclick="$('#scheduleForm').submit()">Save</div>
	    					<?php } ?>
	    				</div>
	    				
	    				
	    				
	    				<!-- Schedule Table -->
	    				
	    				<!--
	    				<pre>
	    				<?php print_r($tableData);?>
	    				</pre>
	    				-->
	    				
	    				
	    				
	    				
	    				<center>
	    				<h2 id="ringTableTitle"></h2>
	    				
	    				<table class="ringtable ringtabletitles">
	    					<tr class="ringtablerow ringtablerowtitles">
	    						<td></td>
	    						<td>Sun</td>
	    						<td>Mon</td>
	    						<td>Tue</td>
	    						<td>Wed</td>
	    						<td>Thu</td>
	    						<td>Fri</td>
	    						<td>Sat</td>
	    						
	    					</tr>
	    				</table>
	    				
	    				<table class="ringtable" id="ringtable">
	    				
	    				<?php for ($segment=0;$segment<2;$segment++) {
	    					$timeString = $segment==0?"12:30 PM":"5:30 PM";
	    					$ringClass = $segment==0?"afternoonring":"eveningring";
	    				?>
	    				<tr class="ringtablerow <?php echo $ringClass?>">
	    					<td><?=$timeString?></td>
	    					
	    					
	    					<?php
	    						for ($i=0;$i<7;$i++) {
	    					?>
	    					
	    					<td class="<?php echo $days[$i]?>">
	    					
	    					<?php if (isset($tableData[$i][1-$segment])) {
	    						$r = $tableData[$i][1-$segment];
	    						
	    						for ($p=0;$p<count($r);$p++) {
	    							if ($p>0) echo "<br>";
	    							$link = "#";
	    							if ($user->guildieadmin) $link = "./guildies/edit/".$r[$p]->id;
	    					?>
	    							<a href="<?php echo $link?>">
	    					<?php
	    							$surname = $r[$p]->firstname;
									if ($r[$p]->nickname) $surname = $r[$p]->nickname;
	    							echo $surname;
	    					?>
	    							</a>	
	    					<?php
	    						 }
	    						}
	    						
	    					?>
	    					</td>
	    					
	    					<?php
	    						}
	    					?>
	    				
	    				</tr>
	    				
	    				<?php } ?>
	    				
	    				</table>
	    				
	    				
	    				
	    				<!-- End Schedule Table -->
	    				
	    				
	    				
	    				
	    				<table class="schedule_table schedule_titles">
	    				<tr>
	    					<td width="30%"><div class="schedule_header">Day</div></td>
	    					<td width="30%"><div class="schedule_header">Time</div></td>
	    					<td width="30%"><div class="schedule_header">Ringers</div></td>
							<td width="10%"><div class="schedule_header"></div></td>

	    				</tr>
	    				</table>
	    				
	    				</center>
	    				
						<div class="schedule_divider"></div>
	    				
	    				<table class="schedule_table" id="scheduleEntries">
	    					
	    					
	    					<?php
	    						for ($i=0;$i<count($rows);$i++) { 
	    							$row = $rows[$i];
	    					?>
	    					<tr id="<?php echo($i==0?'scheduleRowProxy':'');?>" class="schedule_results_row ">
	    						
	    						
	    						<td width="30%"><div class="schedule_data schedule_day"><?=$days[$row->day]?></div></td>
	    						<td width="30%"><div class="schedule_data schedule_time">
	    						
	    						<?php
	    						
	    						$hour = $row->hour;
	    						$AM = $hour>=12?"PM":"AM";
	    						$hour = $hour>12?$hour-12:$hour;
	    						echo $hour.":".$row->minute.($row->minute<10?'0':'')." ".$AM;
	    						
	    						?>
	    						</div></td>
	    						
	    						<td width="30%"><div class="schedule_data schedule_ringers">
	    						<ul class="schedule_ringers_list">
	    						<?php
	    							   if ($row->id > 0) {
	    							   	 	for ($p=0;$p<count($guildies);$p++) {
	    							   	 		if ($guildies[$p]->ringID == $row->id) {
	    							   	 		?>
	    							   	 			<li class="ringerList">
	    							   	 				<!-- Hidden Input Goes Here -->
	    							   	 				
	    							   	 				<?php
	    							   	 					$surname = $guildies[$p]->firstname;
	    							   	 					if ($guildies[$p]->nickname) $surname = $guildies[$p]->nickname;
	    							   	 				?>
	    							   	 				<?php if ($user->scheduleadmin) { ?>
	    							   	 				<a tag="<?php echo "$row->id"?>" id="<?php echo $guildies[$p]->id?>" onclick="removeFromRing(this)">
	    							   	 					<?=$surname." ".$guildies[$p]->lastname;?>
	    							   	 				</a>
	    							   	 				<?php } else { ?>
	    							   	 					<?=$surname." ".$guildies[$p]->lastname;?>
	    							   	 				<?php } ?>
	    							   	 				
	    							   	 			</li>
	    							   	 		<?php
	    							   	 		}
	    							   	 	}
	    							   	}
	    							?>
	    							
	    							<?php if ($user->scheduleadmin) { ?>
	    								<li><a tag="<?php echo "$row->id"?>" onclick="addToRing(this)">Add</a></li>
	    							<?php } ?>
	    							
	    							</ul>
	    							<br>
	    							
	    						</div></td>
	    						
	    						<?php if ($user->scheduleadmin) { ?>
	    							<td width="10%"><div class="schedule_data schedule_edit"><a href="<?=base_url()."index.php/"?>schedule/edit/<?=$row->id?>">Edit</a></div></td>
								<?php } ?>
	    					</tr>
	    					
	    					<?php } ?>
	    				
	    				
	    				
	    				</table>
	    				
	    				
	    				
	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
      	
      	</form>
      	
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->


<!-- Hidden Elements -->
<li style="display:none" id="guildieSelectProxy">
	<select>
	<?php
		echo "<option value=''>";
		echo "--";
		echo "</option>";
		for ($i=0;$i<count($guildies);$i++) {
		if (!$guildies[$i]->active) continue;
			$id = $guildies[$i]->id;
			echo "<option value='$id'>";
			
			$surname = $guildies[$i]->firstname;
			if ($guildies[$i]->nickname) $surname = $guildies[$i]->nickname;
			
			echo $surname." ".$guildies[$i]->lastname;
			echo "</option>";
		}
	?>
	</select>
</li>