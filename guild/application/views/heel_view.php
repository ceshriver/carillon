<?php 
	$days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"); 
	$startTime = $HEEL_START_HOUR;
?>

<style>

.heel_container_table {
	display: table;
	width: 745px;
	margin-left: 2px;
	border: none;
	border-spacing: 0px;
	border-collapse: collapse;
}
.heel_container_table_row {
	display: table-row;
}
.heel_container_table_cell {
	display: table-cell;
}
.heel_container_time_cell {
	width: 60px;
	border:solid thin white;
	
}



.heel_time_list {
	display: table;
	font-size: 7pt;
	border-spacing: 0px;
	padding: 1px;
	
	width: 100%;
	height: 950px;
	
}
.heel_time_list_row {
	display: table-row;
	border-right: solid thin gray;
	
}

.heel_time_list_cell {
	text-align: right;
	display: table-cell;
	vertical-align: middle;
	height: 28px;
	padding: 1px;
}

.heel_time_list_titlecell {
	border: none;	
}

.heel_time_matrix_table {
	display: table;
	width: 100%;
	border-spacing: 0px;
	border-collapse: separate;
	user-select:none;
	
	height: 950px;
	
	border-spacing: 1px;
	padding: 0px;
	
}
.heel_time_matrix_row {
	display: table-row;

}
.heel_time_matrix_cell {
	display: table-cell;
	height: 25px;
	vertical-align: middle;
	padding: 1px;
	width: 14.2%;
	text-align: center;
	color: gray;
	text-decoration: none;
}
.heel_time_matrix_cell:hover {
	text-decoration: none;
	color: black;	
}
.heel_time_matrix_cell:active{
	text-decoration: none;
}
.heel_time_matrix_cell:visited{
	text-decoration: none;
}

a:hover {
	text-decoration: none;
}

.heel_time_node {
	text-align: center;
	border:solid thin white;
}
.heel_time_selectable:hover {
	border:solid thin gray;
}

.heel_time_node_booked {
	background-color: rgba(255,0,0,0.2);
}
.heel_time_node_open {
	background-color: rgba(0,255,0,0.2);
}
.heel_time_node_lesson {
	background-color: rgba(0,0,255,0.2);
}

.heel_time_node_past {
	background-color: rgba(191,191,191,0.2);
}
.heel_time_node_master {
	background-color: rgba(247,150,86,0.8);
}


#HeelMenu {
	width: 745px;
	min-height: 150px;
	margin-left: 2px;
	border-bottom: solid thin lightgray;
	margin-bottom: 3px;
	position: relative;
}

.heel_menu_name {
	display: inline-block;
}
.heel_add_button {
	position: absolute;
	color: #036;
	font-size: 12pt;
	width: 20px;
	height: 15px;
	border: solid thin #036;
	right: 2px;
	text-align: center;
	margin-right: 20px;
	padding: 5px;
}
.heel_add_button:hover {
	background-color: rgba(200,200,200,0.9);
}

.heel_superadd_button {
	width: 90px;
	right: 140px;
}

#superweek {
	position: absolute;
	left: 150px;
}
#supertype {
	position: absolute;
	left: 200px;
}
#superuser {
	position: absolute;
	left: 270px;
}


.heel_heelers_button {
	position: absolute;
	color: #036;
	font-size: 12pt;
	width: 60px;
	height: 15px;
	border: solid thin #036;
	right: 50px;
	text-align: center;
	margin-right: 20px;
	padding: 5px;
}
.heel_heelers_button:hover {
	background-color: rgba(200,200,200,0.9);
}

.heel_menu_lessontable {
	display: table;
	position: relative;
	top: 30px;
	left: 2px;
	margin-bottom: 30px;
}
.heel_menu_lessontable_row {
	display: table-row;	
	height: 20px;
	padding-top: 2px;
}
.heel_menu_lessontable_cell {
	display: table-cell;
	padding: 2px;
}
.heel_menu_lessontable_cell_icon {
	width: 45px;
	height: 7px;
	border: solid thin gray;
	text-align: center;
	font-size: 6pt;	
}

.heel_menu_lessontable_titlecell {
	font-weight: bold;
	height: 16px;
}
.heel_menu_lessontable_titlerow {
	border-bottom: solid thin gray;
}

.heel_menu_title {
	font-weight: bold;
	display: inline-block;
	font-size: 10pt;
}

.heel_menu_name_container {
	position: absolute;
	top: 0px;
	left: 2px;
}

.heel_menu_auditions {
	position: absolute;
	right: 3px;
	bottom: 2px;
}
.heel_menu_auditions .heel_menu_title {
	display: inline-block;
}
.heel_menu_auditions .heel_menu_auditiondate {
	display: inline-block;
}


.heel_menu_slot_table {
	display: table;
	position: absolute;
	right: 3px;
	top: 30px;
	
}
.heel_menu_slot_table_row {
	display: table-row;
}
.heel_menu_slot_table_cell {
	display: table-cell;
	text-align: center;
	vertical-align: middle;
	padding: 2px;
}

.heel_menu_slot_table_icon {
	width: 30px;
	border: solid thin gray;
	height: 20px;
	text-decoration: none;
	font-size: 6pt;
	color: gray;
}
.heel_menu_slot_table_icon:hover {
	text-decoration: none;
}
.heel_menu_slot_table_icon:visited {
	color: gray;
}

.heel_menu_slot_table_text {
	font-size: 8pt;
	
}
.heel_menu_slot_table_title {
	font-weight: bold;
	border-bottom: solid thin lightgray;
}

.heel_menu_teacher_table {
	display: table;
	position: relative;
	top: 30px;
	width: 300px;
}

.heel_menu_teacher_table_row {
	display: table-row;
}
.heel_menu_teacher_table_titlerow {
	font-weight: bold;
}

.heel_menu_teacher_table_cell {
	display: table-cell;
}



#HeelError {
	width: 747px;
	background-color: white;
	height: 25px;
	color: rgba(255,0,0,0.6);
	font-size: 12pt;
	padding: 2px;
	border-bottom: dotted thin rgba(255,0,0,0.6);
	margin-bottom: 5px;
}

#HeelText {
	position: absolute;
	top: 90px;
	left: 50%;
	color: black;
	font-size: 20pt;
	margin-left: -130px;
	font-style: italic;
}

.heel_table_title {
	width: 748px;
	height: 30px;
	margin-top: 20px;
}
.heel_table_title h1 {
	float: left;
	width: 290px;
}
.heel_table_title a {
	float: left;
	font-size: 6pt;
}


</style>

<?php 
	$currentWeek = $now['week']+1;
	if ($user->inGuild && $user->heeladmin) $currentWeek = $this->session->userdata('superweek')+1;
?>

<div id="HeelText">


<?php if ($currentWeek == 9) { ?>

	<font style="color:red">Auditions: </font>
	
	<font style="font-size:14pt; font-weight:bold"/>
	<?php 
	//echo AUDITION_DATE;
	//date_format(AUDITION_DATE, 'Y-m-d');
	echo date("l, g:i a",$AUDITION_DATE);
	//echo AUDITION_DATE->format('Y-m-d H:i:s'); 
	?>
	</font>
	

<?php } else { ?>

<!-- The week is indexed from 0, but add 1 for display. Show a "week before" string -->
Heel Manager - <font style="color:red"> Week <?php echo $currentWeek<=0?"Before Heel!": $currentWeek?> </font> 

<?php } ?>
</div>


<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				
	    				<?php 
	    						//$rows = $newsData;
	    						//$guildies = $guildData->result();
	    				 ?>
	    				
	    				<?php if ($this->session->userdata('heelError')) { ?>
	    				<div id="HeelError"><?php echo $this->session->userdata('heelError');?></div>
	    				<?php 
	    						$this->session->set_userdata('heelError',false);
	    					} 
	    				?>
	    				
	    				<div id="HeelMenu">	    					
	    					
	    					<div class="heel_menu_name_container">
	    						<div class="heel_menu_title">Name:</div>
	    						<div class="heel_menu_name"><?php echo $user->firstname; echo " "; echo $user->lastname ?> </div>
	    					</div>
	    					
	    					<?php if ($user->inGuild) {   ?>
	    					
		    					<?php if ($user->heeladmin) { ?>
		    						<div class="heel_add_button" onclick="go('heelmanager/add');">+</div>
		    						<div class="heel_superadd_button heel_add_button" onclick="go('heelmanager/superadd');">Super Add</div>
		    						<div class="heel_heelers_button" onclick="go('heelmanager/heelers');">Heelers</div>
		    						
		    						<!-- This is the admin super user list - it can add/remove slots and lessons for anyone… -->
		    						<?=form_open('heelmanager/superswitch');?>
		    						
		    						<select onchange="this.form.submit()" id="superweek" name="superweek">
		    							<?php for ($i=0;$i<10;$i++) { ?>
		    							<option <?php if ($this->session->userdata("superweek")==$i) echo "selected";?> value="<?php echo $i?>"><?php echo $i+1 ?></option>
										<?php } ?>
		    						</select>
		    						
		    						<select onchange="this.form.submit()" id="supertype" name="supertype">
		    							<option <?php if ($this->session->userdata("supertype")=="slot") echo "selected";?> value="slot">Slot</option>
		    							<option <?php if ($this->session->userdata("supertype")=="lesson") echo "selected";?> value="lesson">Lesson</option>
		    							<option <?php if ($this->session->userdata("supertype")=="master") echo "selected";?> value="master">Master</option>
		    						</select>
		    						
		    						<select onchange="this.form.submit()" id="superuser" name="superuser">
		    						 
		    						 
		    						 
		    						 <?php 
		    						 	
		    						  	for ($i=0;$i<count($guildiesAdmin);$i++) {
		    						  		$guildie = $guildiesAdmin[$i];
		    						  		if (!$guildie->active || !$guildie->heeladmin) continue;//Only active guildies can teach
		    						  ?>
		    						  	<option <?php if ("guildie_".$guildie->id == $superuser) echo "selected"?> value="guildie_<?=$guildie->id?>">
		    						  		<?=$guildie->firstname?> <?=$guildie->lastname?>
		    						  	</option>
		    						  <?php } ?>
		    						  
		    						  <option>---Heelers---</option>

		    						  <?php 
		    						  	for ($i=0;$i<count($heelersAdmin);$i++) {
		    						  		$heeler = $heelersAdmin[$i];
		    						  ?>
		    						  	<option <?php if ("heeler_".$heeler->id == $superuser) echo "selected"?> value="heeler_<?=$heeler->id?>">
		    						  		<?=$heeler->firstname?> <?=$heeler->lastname?></option>
		    						  <?php } ?>
		    						  
		    						  <option>---Guildies---</option>
		    						  	    	
		    						 	<?php 
		    						 		
		    						 	 	for ($i=0;$i<count($guildiesAdmin);$i++) {
		    						 	 		$guildie = $guildiesAdmin[$i];
		    						 	 		if (!$guildie->active || $guildie->heeladmin) continue;//Only active guildies can teach
		    						 	 ?>
		    						 	 	<option <?php if ("guildie_".$guildie->id == $superuser) echo "selected"?> value="guildie_<?=$guildie->id?>">
		    						 	 		<?=$guildie->firstname?> <?=$guildie->lastname?>
		    						 	 	</option>
		    						 	 <?php } ?>
		    						  	    						  
		    						</select>
		    						
		    						
		    					<?php } ?>
		    					</form>
		    					
		    					
		    					
		    					<div class="heel_menu_lessontable">
		    						<div class="heel_menu_lessontable_titlerow heel_menu_lessontable_row">
		    							<div class="heel_menu_lessontable_titlecell heel_menu_lessontable_cell"></div>
		    							<div class="heel_menu_lessontable_titlecell heel_menu_lessontable_cell">Student</div>
		    							<div class="heel_menu_lessontable_titlecell heel_menu_lessontable_cell">Time</div>
		    							<div class="heel_menu_lessontable_titlecell heel_menu_lessontable_cell">Email</div>
		    							<div class="heel_menu_lessontable_titlecell heel_menu_lessontable_cell">Phone</div>
		    						</div>
		    						
		    						<?php
		    						if (isset($heelers)) {
		    							for ($i=0;$i<count($heelers);$i++) {
		    								$heeler = $heelers[$i];
		    						?>
		    						
			    						<div class="heel_menu_lessontable_row">
			    							<div class="heel_menu_lessontable_cell_icon heel_time_node_lesson heel_menu_lessontable_cell"><?php echo ($heeler->lessonup?"Upstairs":"Downstairs")?></div>
			    							<div class="heel_menu_lessontable_cell"><?php echo $heeler->firstname. " ".$heeler->lastname?></div>
			    							<div class="heel_menu_lessontable_cell"><?php echo $heeler->lesson?></div>
			    							<div class="heel_menu_lessontable_cell"><?php echo mailto($heeler->email, $heeler->email);?></div>
			    							<div class="heel_menu_lessontable_cell"><?php echo $heeler->phone?></div>
			    						</div>
			    						
		    						<?php }} ?>
		    						
		    					</div>
		    					
	    					<?php } else { 
	    					
	    						$AM = $user->lessonhour >= 12 ? "PM":"AM";
	    						$hour =  $user->lessonhour > 12 ? $user->lessonhour-12 : $user->lessonhour;
	    						$lessonString = $days[$user->lessonday]." ".$hour.":".($user->lessonhalf?"30":"00")." ".$AM;
	    						$lessonUp = $user->lessonup?"Upstairs":"Downstairs";
	    					?> 
	    						
	    						
	    						
	    						<div class="heel_menu_lessontable">
	    							<div class="heel_menu_lessontable_row">
	    								<div class="heel_menu_lessontable_titlecell heel_menu_lessontable_cell">Lesson</div>
	    								<div class="heel_menu_lessontable_titlecell heel_menu_lessontable_cell"></div>
	    							</div>
	    							<div class="heel_menu_lessontable_row">
	    								<div class="heel_menu_lessontable_cell_icon heel_time_node_lesson heel_menu_lessontable_cell"><?php echo $lessonUp?></div>
	    								<div class="heel_menu_lessontable_cell"><?php echo $lessonString?></div>
	    							</div>
	    						</div>
	    						
	    						
	    						<div class="heel_menu_x_table">
	    							<?php if ($user->teacher) { ?>
	    							<div class="heel_menu_teacher_table_titlerow heel_menu_teacher_table_row">
	    								<div class="heel_menu_teacher_table_cell">Teacher</div>
	    								<div class="heel_menu_teacher_table_cell">Email</div>
	    								<div class="heel_menu_teacher_table_cell">Phone</div>
	    							</div>
	    							
	    							<div class="heel_menu_teacher_table_row">
	    								<div class="heel_menu_teacher_table_cell"><?php echo $user->teacher->firstname. " ".$user->teacher->lastname?></div>
	    								<div class="heel_menu_teacher_table_cell"><?php echo mailto($user->teacher->email, $user->teacher->email);?></div>
	    								<div class="heel_menu_teacher_table_cell"><?php echo $user->teacher->phone?></div>
	    							</div>
	    							<?php } ?>
	    						</div>
	    					
	    					<?php } ?>	 
	    					
	    					
	    					
	    					
	    					<div class="heel_menu_slot_table">
	    					
	    						<div class="heel_menu_slot_table_row">
	    							<div class="heel_menu_slot_table_title heel_menu_slot_table_cell">Bookings</div>
	    							<div class="heel_menu_slot_table_title heel_menu_slot_table_cell"></div>
	    						</div>
	    						<div class="heel_menu_slot_table_row">
	    						<div class="heel_menu_slot_table_cell"></div>
	    						<div class="heel_menu_slot_table_cell"></div>
	    						</div>
	    						
	    					
	    					
	    							
	    							<?php 
	    							for ($i=0;$i<count($myBookings);$i++) {
	    								$slot = $myBookings[$i];
	    								
	    								if ($slot && $slot['past']) continue;
	    								
	    								$colorClass = "heel_time_node_booked";
	    								if ($slot['type'] == 1) $colorClass = "heel_time_node_lesson";
	    								
	    								$AM = $slot['hour'] >= 12 ? "PM":"AM";
	    								$hour = $slot['hour']>12?$slot['hour']-12:$slot['hour'];
	    								
	    								$timeString = $days[$slot['day']]." ".$hour.":".($slot['half']?"30":"00")." ".$AM;
	    								
	    								$week = $now['week'];
	    								if ($user->inGuild && $user->heeladmin) $week = $this->session->userdata("superweek");
	    								
	    								
	    								
	    								$unBook = base_url()."index.php/heelmanager/unbook/".$week."/".$slot['day']."/".($slot['hour'])."/".($slot['half'])."/".$slot['isup'];
	    								$upString = $slot['isup']?"Upstairs":"Downstairs";
	    							?>
	    							<div class="heel_menu_slot_table_row">
	    							<a href="<?php echo $unBook; ?>" class="<?php echo $colorClass ?> heel_menu_slot_table_icon heel_menu_slot_table_cell"><?php echo $upString?></a>
	    							<div class="heel_menu_slot_table_text heel_menu_slot_table_cell"><?php echo $timeString ?></div>
	    							</div>
	    							
	    							<?php } ?>
	    					</div>
	    					
	    					
	    					   					
	    					
	    					<div class="heel_menu_auditions">
		    					<div class="heel_menu_title">Auditions:</div>
		    					<div class="heel_menu_auditiondate"><?php echo date("F j, Y, g:i a",$AUDITION_DATE) ?></div>
	    					</div>
	    					
	    				</div>
	    				
	    				
	    				<!-- Heel Table BUilds Here -->
	    				<div class="heel_table_title">
	    					<h1><?php echo ($this->session->userdata("up")?"Upstairs":"Downstairs")?> Carillon</h1>
	    					<a href="<?php echo base_url()?>index.php/heelmanager/switchcarillon/<?php echo $this->session->userdata("up")==1?0:1?>">
	    						<h2>Switch to <?php echo ($this->session->userdata("up")?"Downstairs":"Upstairs")?> Carillon</h2>
	    					</a> 
	    				</div>
	    				
	    				
	    				<div class="heel_container_table">
	    					<div class=" heel_container_table_row">
	    				
	    				
	    						<div class="heel_container_time_cell heel_container_table_cell">
	    						<div class="heel_time_list">	
	    							<!-- Time View -->
	    							<div class="heel_time_list_row heel_time_list_titlerow">
	    								<div class="heel_time_list_cell heel_time_list_titlecell">&nbsp;<!--Header Row--></div>
	    							</div>
	    							<?php for ($p=0;$p<16*2;$p++) {//16 hours, 2 slots per hour, plus header row
	    								$hour = floor($p/2) + $startTime;
	    								$minute = ($p%2==0)?"00":"30";
	    								$minute = $minute . (($hour >= 12)?" PM":" AM");
	    								$hour = ($hour>12)?($hour-12):$hour;
	    								$timeString = $hour.":".$minute;
	    							?>
	    								<div class="heel_time_list_row">
	    									<div class="heel_time_list_cell"><?php echo $timeString; ?></div>
	    								</div>
	    								
	    							<?php } ?>
	    						</div>
	    						</div>
	    						
	    						
	    						<div class="heel_container_table_cell">
	    							<!-- Time Matrix -->
	    						
	    							<div class="heel_time_matrix_table">
	    							
	    							<!--
	    							<?php echo "<pre>"; print_r($booked);echo "</pre>";?>
	    							-->
	    							
	    							<?php 
	    							
	    							$isUp = $this->session->userdata("up");
	    							
	    							for ($p=-1;$p<16*2;$p++) {//16 hours, 2 slots per hour
	    								$hour = floor($p/2);
	    							?>	
	    								<div class="heel_time_matrix_row">
	    							<?php
	    							
	    								for ($i=0;$i<7;$i++) {
	    								
	    								$selectClass = "";
	    								$timeText = "";
	    								$cellLink = "#";
	    								
	    								
	    								
	    								if ($p== -1) {
	    									$colorClass = "heel_time_node_title";
	    									$timeText = $days[$i];
	    								} else {
	    									$bookData = $booked[$i][$hour+$startTime][$p%2!=0];
	    									$week = $now['week'];
		    								if (isset($bookData['user'])) {
		    									$currentUser = $bookData['user'];
		    									if ($bookData['type'] == 1 && ($user->inGuild || $currentUser->netid == $user->netid)) {
		    										$colorClass = "heel_time_node_lesson";
		    									} else if ($bookData['type'] == 2) {
		    										//Master Class!
		    										$colorClass = "heel_time_node_master";
		    									} else {
		    										$colorClass = "heel_time_node_booked";
		    									}
		    									
		    									//Admins and the heeler themselves can see their name in the grid
		    									//Teachers also see student names in the grid for lessons only
		    									
		    									if ($currentUser->netid == $user->netid || ($user->inGuild && $user->heeladmin)) { 
		    										$timeText = $currentUser->firstname;
		    										$selectClass = "heel_time_selectable";
		    										//Make this unbookable
		    										// /unbook/day/hour/half
		    										$cellLink = base_url()."index.php/heelmanager/unbook/".$week."/".$i."/".($hour+$startTime)."/".($p%2==0?0:1)."/".$isUp;
		    										
		    										if ($bookData['past'] == 1) {
		    											$cellLink = "#";
		    											$selectClass = "";
		    										}
		    										
		    									} else {
		    										if ($bookData['past'] == 1) {
		    											$colorClass = "heel_time_node_past";
		    											$cellLink = "#";
		    											$selectClass = "";
		    										}
		    									}	
		    									
		    								} else {
		    									$colorClass = "heel_time_node_open";
		    									$selectClass = "heel_time_selectable";
		    									$cellLink = base_url()."index.php/heelmanager/book/".$week."/".$i."/".($hour+$startTime)."/".($p%2==0?0:1)."/".$isUp;
		    									
		    									if ($bookData['past'] == 1) {
		    										$colorClass = "heel_time_node_past";
		    										$cellLink = "#";
		    										$selectClass = "";
		    									}
		    								}
		    								
		    								
		    								
		    								//$timeText = $hour." ".$i;
		    								
		    								if ($user->inGuild && $user->heeladmin) {
		    									//The admins get to make some crazy changes to the schedule
		    									//Specifically, they get their own CI function which can book lessons as well
		    									
		    									$superuser = "guild_".$user->id;
		    									$supertype = "slot";
		    									
		    									if ($this->session->userdata("superuser")) $superuser = $this->session->userdata("superuser");
		    									if ($this->session->userdata("supertype")) $supertype = $this->session->userdata("supertype");
		    									if ($this->session->userdata("superweek")) $superweek = $this->session->userdata("superweek");
		    									
		    									$cellLink = base_url()."index.php/heelmanager/superbook/".$superweek."/".$i."/".($hour+$startTime)."/".($p%2==0?0:1)."/".$isUp."/".$superuser."/".$supertype;
		    								}
		    								
	    								}
	    								
	    								
	    								
	    							?>
		   									<a href="<?php echo $cellLink; ?>" class="<?php echo $selectClass; echo " "; echo $colorClass ?> <?php if ($p!=-1) echo "heel_time_node" ?> heel_time_matrix_cell">
		   										<?php echo $timeText ?>
		   									</a>
		   									
	    								<?php } ?>
	    								</div>
	    							<?php } ?>
	    								
	    							
	    							</div>
	    						
	    						
	    						</div>
	    				
	    				
	    					</div>
	    				</div>
	    				
	    				
	    				
	    				
	    					    				
	    				<!-- End Heel Table -->
	    				
	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->