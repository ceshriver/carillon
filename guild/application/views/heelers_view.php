<style>
.guildie_results_row  {
	height: 10px;
}
.guildie_header {
	text-align: left;
}

.slotselector  {
	position: absolute;
	top: 20px;
}
.slotselector div {
	display: inline-block;
}
.slotselector select {
	display: inline-block;
}
.slotattime{
	right: 390px;
}
.slotdown{
	right: 270px;
}
.slotup {
	right: 160px;
}
.slottotal {
	right: 70px;
}

#guildieButtonBar {
	position: relative;
}

.guildie_add {
	text-decoration:none;
}
</style>

<style type="text/css">
.guildie_add:link {text-decoration:none;}
.guildie_add:visited {text-decoration:none;}
.guildie_add:hover {text-decoration:none;}
.guildie_add:active {text-decoration:none;}
</style>

<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				<?php echo form_open('./heelmanager/heelersListSubmit');?>
	    				<?php 
	    						$rows = $heelers;
	    				 ?>
	    				
	    				<div id="guildieButtonBar">
	    					
	    					<center><div id="guildEntryCount"><?=count($rows)?> Heeler<?=count($rows)==1?'':'s'?></div></center>
	    					<br>
	    					<input onkeyup="heelerSearch(this.value)" style="width:150px" type="text" name="" value="" placeholder="Search…" />
	    					
	    					<div class="slotselector slotattime">
	    					<div>Slots at Time:</div>
	    					<select onchange="this.form.submit()" name="slotattime"><option selected value="500">++</option><?php for ($i=0;$i<15;$i++) {?> <option <?php if ($slotattime==$i) echo "selected"?> value="<?=$i?>"><?=$i?></option><?php } ?></select>
	    					</div>
	    					
	    					<div class="slotselector slotdown">
	    					<div>Slots Down:</div>
	    					<select onchange="this.form.submit()" name="slotdown"><option selected value="500">++</option><?php for ($i=0;$i<15;$i++) {?> <option <?php if ($slotdown==$i) echo "selected"?> value="<?=$i?>"><?=$i?></option><?php } ?></select>
	    					</div>
	    					
	    					<div class="slotselector slotup">
	    					<div>Slots Up:</div>
	    					<select onchange="this.form.submit()" name="slotup"><option selected value="500">++</option><?php for ($i=0;$i<15;$i++) {?> <option <?php if ($slotup==$i) echo "selected"?> value="<?=$i?>"><?=$i?></option><?php } ?></select>
	    					</div>
	    					
	    					<div class="slotselector slottotal">
	    					<div>Total:</div>
	    					<select onchange="this.form.submit()" name="slottotal"><option selected value="500">++</option><?php for ($i=0;$i<15;$i++) {?> <option <?php if ($slottotal==$i) echo "selected"?> value="<?=$i?>"><?=$i?></option><?php } ?></select>
	    					</div>
	    												
							
							
	    					<div id="addButton" onclick="go('heelmanager/add');">+</div>
	    					
	    				</div>
	    				
	    				
	    				<table class="guildie_table guildie_titles">
	    				<tr>
	    					<td width="3%"><div class="guildie_header"></div></td><!-- Remove Row -->
	    					<td width="30%"><div class="guildie_header">Name</div></td>
	    					<td width="20%"><div class="guildie_header">Teacher</div></td>
	    					<td width="10%"><div class="guildie_header">Net ID</div></td>
	    					<td width="35%"><div class="guildie_header">Phone</div></td>
	    					<td width="3%"><div class="guildie_header"></div></td><!-- Edit Row -->
	    					<td width="4%"><div class="guildie_header"></div></td><!-- Remove Row -->
	    				</tr>
	    				</table>
	    				
						<div class="guildie_divider"></div>
	    				
	    				
	    					    				
	    				<table class="guildie_table" id="guildieEntries">
	    					
	    					
	    					
	    					<?php
	    						for ($i=0;$i<count($rows);$i++) { 
	    							$row = $rows[$i];
	    							$heeler = $row;
	    					?>
	    					<tr id="<?php echo($i==0?'guildieRowProxy':'');?>" class="guildie_results_row " tag="<?=$row->id?>">
	    						
	    						<td width="3%">
	    						<div class="guildie_data"><a class="guildie_add" href="<?=base_url()?>index.php/heelmanager/upgradeGuildie/<?=$row->id?>">+</a></div>
	    						</td>
	    						
	    						<td width="30%"><div class="guildie_data guildie_name"><?=$row->firstname?> <?=$row->lastname?></div></td>
	    						
	    						<td width="20%"><div class="guildie_data">
	    						
	    						<select name="teacher_<?=$row->id?>">
	    							<option selected value="">None</option>
	    						  <?php 
	    						  	for ($o=0;$o<count($guildies);$o++) {
	    						  		$guildie = $guildies[$o];
	    						  		if (!$guildie->active) continue;//Only active guildies can teach
	    						  ?>
	    						  	<option <?php if ($heeler && $heeler->teacher == $guildie->id) echo "selected"?> value="<?=$guildie->id?>"><?=$guildie->firstname?> <?=$guildie->lastname?></option>
	    						  <?php } ?>	    						  
	    						</select>
	    						
	    						</div></td>
	    						
	    						<td width="10%"><div class="guildie_data guildie_year"><?=$row->netid?></div></td>
	    						<td width="35%">
	    							<div class="guildie_data guildie_year">
	    								<input type="text" name="phone_<?=$row->id?>" value="<?=$row->phone?>" />
	    							</div>
	    						</td>
	    						
	    						
	    						<?php	    				
	    						
	    						// Conditionally enable editing for Heel admins only
	    						if ( ($user && $user->heeladmin)) {
	    						?>
	    						
	    						<td width="3%">
	    						<div class="guildie_data guildie_edit"><a href="<?=base_url()?>index.php/heelmanager/edit/<?=$row->id?>">Edit</a></div>
	    						</td>
	    						
	    						<td width="4%">
	    						<div class="guildie_data guildie_edit"><a href="<?=base_url()?>index.php/heelmanager/remove/<?=$row->id?>">Remove</a></div>
	    						</td>
	    						
	    						
	    						<?php
	    						}
	    						
	    						?>
	    						
	    						

	    					</tr>
	    					
	    					<?php } ?>
	    				
	    				
	    				
	    				</table>
	    				
	    				<input class="form_button" type="submit" name="update" value="Update" />
	    				
	    				
	    				<br><br><br>
	    				<i><center><font style="font-size:11px">To clear the heel manager, navigate to "Settings" above.</font></center></i>
	    				
	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
		</form>
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->