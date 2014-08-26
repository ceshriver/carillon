<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				
	    				<?php
	    					$months = array("January", "February","March","April","May","June","July","August","September","October","November","December");
	    					$days= array(31,28,31,30,31,30,31,31,30,31,30,31);
	    					$years = array(2010,2011,2012,2013,2014,2015,2016,2017,2018);
	    				?>
	    				
	    				<br>
	    				<h2>Settings for the pages you manage will appear below...</h2>
	    				
	    				<?=form_open('settings/settingsUpdate');?>
	    				
	    				<br><br>
	    				
	    				<style type="text/css">
	    				.settings_section {
	    					width:600px;
	    					margin-top:40px;
	    				}
	    				.settings_section_content {
	    					margin-top:30px;
	    				}
	    				
	    				.settings_section_title {
	    					display:inline-block;
	    					width:90px;
	    					text-align:right;
	    				}
	    				.settings_section_setting {
	    					display:inline-block;
	    					margin-bottom:10px;
	    				}
	    				.settings_section select {
	    					width:100px;
	    				}
	    				
	    				</style>
	    				
	    				
	    				<?php
	    				
	    					//$heelstart = array("month"=>9,"day"=>7,"year"=>2013);
	    					//$audition = array("month"=>11,"day"=>21,"year"=>2013);
	    					//$heelstarthour = 8;
	    					//$season = "Fall";
	    				
	    				?>
	    				
<!--
define('HEEL_START', date('z-H-i',mktime(0, 0, 0, 9, 8, 2013)));
define('CURRENT_TIME', date('z-H-i'));
define('HEEL_START_HOUR',8);
//define('AUDITION_DATE', date("F j, Y, g:i a",mktime(11, 45, 0, 11, 11, 2012)));
define('AUDITION_DATE', mktime(11, 45, 0, 11, 10, 2013));
-->    				
						<?php if ($user->scheduleadmin) { ?>
	    				<!-- SCHEDULE SECTION -->
	    				<div class="settings_section">
							<div class="settings_section_header">
								<h1>Ring Schedule</h1>
							</div>
							
							<div class="settings_section_content">							
								<div class="settings_section_title">Ring Season</div>
								<div style="display:inline-block"> : </div>
							
								<div class="settings_section_setting">							
									<select name="season">
										<option <?php if ($season == "Fall") echo "selected"?> value="Fall">Fall</option>
										<option <?php if ($season == "Spring") echo "selected"?> value="Spring">Spring</option>
										<option <?php if ($season == "Summer") echo "selected"?> value="Summer">Summer</option>
									</select>
								</div>
							</div>
						</div>
						<?php } ?>

	    				
	    				<?php if ($user->heeladmin) { ?>
	    				<!-- HEEL SECTION -->
	    				<div class="settings_section">
							<div class="settings_section_header">
								<h1>Heel Manager</h1>
							</div>
							
							<div class="settings_section_content">
								
								<!-- HEEL START SECTION -->
								<div class="settings_section_title">Heel Start</div>
								<div style="display:inline-block"> : </div>
								<div class="settings_section_setting">
									<!-- DATE PICKER -->
										<select name="heel-start-month">
											<?php for ($i=0;$i<count($months);$i++) { ?>
												<option <?php if ($heelstart && $heelstart['month'] == $i+1) echo "selected"?> value="<?=$i+1?>"><?=$months[$i]?></option>
											<?php } ?>	    						  
										</select>
										<select name="heel-start-day">
										<?php
											$numDays = $days[$heelstart['month']];
											for ($i=1;$i<$numDays;$i++) { 
										?>
												<option <?php if ($heelstart && $heelstart['day'] == $i) echo "selected"?> value="<?=$i?>"><?=$i?></option>
											<?php } ?>	    						  
										</select>
										<select name="heel-start-year">
											<?php for ($i=0;$i<count($years);$i++) { ?>
												<option <?php if ($heelstart && $heelstart['year']== $years[$i]) echo "selected"?> value="<?=$years[$i]?>"><?=$years[$i]?></option>
											<?php } ?>	    						  
										</select>
									<!-- END DATE PICKER -->
								
								</div>
								<!-- END HEEL START -->
								
								<br>
								
								<!-- AUDITION SECTION -->
								<div class="settings_section_title">Audition Date</div>
								<div style="display:inline-block"> : </div>
								<div class="settings_section_setting">
									<!-- DATE PICKER -->
										<select name="audition-month">
											<?php for ($i=0;$i<count($months);$i++) { ?>
												<option <?php if ($audition && $audition['month'] == $i+1) echo "selected"?> value="<?=$i+1?>"><?=$months[$i]?></option>
											<?php } ?>	    						  
										</select>
										<select name="audition-day">
										<?php
											$numDays = $days[$audition['month']];
											for ($i=1;$i<$numDays;$i++) { 
										?>
												<option <?php if ($audition && $audition['day'] == $i) echo "selected"?> value="<?=$i?>"><?=$i?></option>
											<?php } ?>	    						  
										</select>
										<select name="audition-year">
											<?php for ($i=0;$i<count($years);$i++) { ?>
												<option <?php if ($audition && $audition['year']== $years[$i]) echo "selected"?> value="<?=$years[$i]?>"><?=$years[$i]?></option>
											<?php } ?>	    						  
										</select>
									<!-- END DATE PICKER -->
								</div>
								<!-- END AUDITION SECTION -->
								<br>
								<!-- HEEL START HOUR -->
								<div class="settings_section_title">Heel Start Hour</div>
								<div style="display:inline-block"> : </div>
								<div class="settings_section_setting">
									<select name="heelstarthour">
									<?php 
										
										for ($i=1;$i<24;$i++) {
										$hour = $i;
										$AM = $hour < 12 ? "AM" : "PM";
										$hour = $hour > 12 ? $hour-12 : $hour;
									?>
										<option <?php if ($heelstarthour == $i) echo "selected"?> value="<?=$i?>"><?=$hour.":00 ".$AM?></option>
									<?php } ?>
									</select>
								</div>
							</div>
							
							<br>
							
							<div class="settings_section_title">Heel Start Hour : </div>
							<input type="button" name="RESET HEEL MANAGER" value="RESET HEEL MANAGER" onclick="go('Settings/resetHeelManager')"/>
							
						</div>
						<!-- END HEEL SECTION -->
						<? } ?>
						
						<br><br><br><br>
						<div style="border-bottom:solid thin;width:700px"></div>
						<br>
						<input class="form_button" type="button" name="cancel" value="Cancel" onclick="go('settings/');" />
	    				<input class="form_button" type="submit" name="update" value="Update" />
						
						</form>
						
						<!-- 
							Load these:
								$heelstart = array("month"=>9,"day"=>7,"year"=>2014);
	    						$audition = array("month"=>11,"day"=>21,"year"=>2014);
	    						$heelstarthour = 8;
	    						$season
						-->
						
						<!-- SAVED KEYS
								season
								
								heel-start-month
								heel-start-day
								heel-start-year
								
								audition-month
								audition-day
								audition-year
								
								heelstarthour
						-->
						
						
						
						
						<!-- <input type="text" name="firstname" value="<?php if ($guildie) echo $guildie->firstname?>" /> -->
						
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->