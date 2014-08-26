<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				<br>
	    				<h3><a href="<?= base_url()."index.php/schedule"?>">Back to Ring Schedule</a></h3>
	    				<?php if ($ring) { ?><h3 style="float:right"><a href="<?=base_url()."index.php/schedule/remove/".$ring->id?>">Remove Ring Slot</a></h3><?php } ?>
	    				<br>
	    				<br>
	    				
	    				<h1><?=$ring?"Edit":"Add"?> Ring Slot</h1>
	    				
	    				<br><br>
	    				
	    				<?php if (isset($error)) { ?>
	    					<font style="font-size:12pt;color:red"><?=$error?></font>
	    				<?php }?>
	    				
	    				<?=form_open('schedule/ringUpdate');?>
	    				
	    				
	    				<?= form_hidden('id', $ring?$ring->id:0)?>
	    				
	    					<div class="schedule_edit_entry_form">
	    				
	    					<table class="schedule_entry_table schedule_table">
	    				
		    					
		    					
		    					<tr>
		    						<td>Day:</td>
		    						<td>
		    						<select name="day">
		    							<?php 
		    								$days = array("Sunday","Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
		    							for ($i=0;$i<count($days);$i++) {
		    								$desc = $days[$i];
		    								$sel = $i == $ring->day?"selected":"";
		    							?>
		    							
		    							<option <?=$sel?> value="<?=$i?>"><?=$desc?></option>
		    							
		    							<?php
		    								}
		    							?>
		    								
		    						</select>
		    						</td>
		    					</tr>
		    					<br>
		    					<tr>
		    						<td>Time:</td>
		    						<td>
		    						<select name="time">

										<?php 
											$hour = $ring->hour;
	    									$AM = $hour>=12?"PM":"AM";
	    									$hour = $hour>12?$hour-12:$hour;
	    									$ringTimeString = $hour.":".($ring->minute<10?'0':'').$ring->minute." ".$AM; 	
										
											// I put these in manually since some ring times are more common than others...
											
											$ringTimes = array(
											
											"11:15 AM","11:30 AM","11:45 AM",
											"12:00 PM","12:15 PM","12:30 PM","12:45 PM",
											"1:15 PM","1:30 PM","1:45 PM",
											
											"4:45 PM",
											
											"5:00 PM","5:15 PM","5:30 PM","5:45 PM",
											"6:00 PM","6:15 PM","6:30 PM","6:45 PM",
											"7:00 PM","7:15 PM","7:30 PM","7:45 PM"
											);
											
											for ($i=0;$i<count($ringTimes);$i++) {
												$ringTime = $ringTimes[$i];
												$sel = $ringTimeString == $ringTime;
										?>
											
											<option <?=$sel?'selected':''?> value="<?=$ringTime?>"><?=$ringTime?></option>
											
										<?php
											
											}
											
										?>
										
		    								    								
		    						</select>
		    						</td>
		    					</tr>
		    					
		    					<br>
		    					<tr>
		    						<td>Duration:</td>
		    						<td>
		    						<select name="duration">
		    							<?php 
		    								$dur = 30;
		    								if ($ring) $dur = $ring->duration;
		    							?>
		    							<option <?=($dur==30?"selected":"")?> value="<?="30"?>"><?="30 minutes"?></option>
		    							<option <?=($dur==60?"selected":"")?> value="<?="60"?>"><?="60 minutes"?></option>
		    							<option <?=($dur==90?"selected":"")?> value="<?="90"?>"><?="90 minutes"?></option>
		    							
		    						</select>
		    						</td>
		    					</tr>
		    					
							</table>
							
	    				<br>
	    				<input class="form_button" type="button" name="cancel" value="Cancel" onclick="window.location='<?=base_url()."index.php/schedule/"?>'" />
	    				<input class="form_button" type="submit" name="update" value="<?=$ring?"Update":"Add"?>" />
	    				
	    				</div>
	    				</form>

	    				</div>
	    					    				

	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->