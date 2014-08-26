<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				<br>
	    				<h3><a href="<?= base_url()."index.php/heelmanager/heelers"?>">Back to Heelers</a></h3>
	    				<?php if ($heeler) { ?><h3 style="float:right"><a href="<?=base_url()."index.php/heelmanager/remove/".$heeler->id?>">Remove</a></h3><?php } ?>
	    				<br>
	    				<br>
	    				
	    				<h1><?=$heeler?"Edit":"Add"?> Heeler</h1>
	    				
	    				<br><br>
	    				
	    				
	    				<?php if (isset($error)) { ?>
	    				<font style="font-size:12pt;color:red"><?=$error?></font>
	    				<?php }?>
	    				
	    				<?=form_open('heelmanager/heelerUpdate');?>
	    				
	    				
	    				<?= form_hidden('id', $heeler?$heeler->id:0)?>
	    				
	    					<div class="guildie_edit_entry_form">
	    				
	    					<table class="guildie_entry_table guildie_table">
	    				
		    					<tr><td>First Name:</td><td><input type="text" name="firstname" value="<?php if ($heeler) echo $heeler->firstname?>" /></td></tr>
		    					<br>
		    					<tr><td>Last Name:</td><td><input type="text" name="lastname" value="<?php if ($heeler) echo $heeler->lastname?>" /></td></tr>
		    					
		    					
		    					<tr><td>NetID:</td><td><input type="text" name="netid" value="<?php if ($heeler) echo $heeler->netid?>" /></td></tr>
		    					
		    					
		    					<tr><td>Phone:</td><td><input type="text" name="phone" value="<?php if ($heeler) echo $heeler->phone?>" /></td></tr>
		    					
		    					
		    					<tr>
		    						<td>College / Program:</td>
		    						<td>
		    						<select name="college">
		    							<option selected value="">None</option>
		    						  <?php 
		    						  	$colleges = array("Jonathan Edwards", "Siliman", "Pierson", "Trumbull", "Timothy Dwight", "Davenport", "Saybrook", "Branford", "Ezra Styles", "Morse", "Calhoun", "Berkeley", "Law", "Graduate Student", "Post Doc", "PHD");
		    						  	for ($i=0;$i<count($colleges);$i++) {
		    						  ?>
		    						  	<option <?php if ($heeler && $heeler->college == $colleges[$i]) echo "selected"?> value="<?=$colleges[$i]?>"><?=$colleges[$i]?></option>
		    						  <?php } ?>	    						  
		    						</select>
		    						</td>
		    					</tr>
		    					
		    					<tr>
		    						<td>Teacher:</td>
		    						<td>
		    						<select name="teacher">
		    							<option selected value="">None</option>
		    						  <?php 
		    						  	for ($i=0;$i<count($guildies);$i++) {
		    						  		$guildie = $guildies[$i];
		    						  		if (!$guildie->active) continue;//Only active guildies can teach
		    						  ?>
		    						  	<option <?php if ($heeler && $heeler->teacher == $guildie->id) echo "selected"?> value="<?=$guildie->id?>"><?=$guildie->firstname?></option>
		    						  <?php } ?>	    						  
		    						</select>
		    						</td>
		    					</tr>
		    					
		    					
		    					
		    					<tr>
		    						<td>Year:</td>
		    						<td>
		    						<select name="year">
		    							<option selected value="">None</option>
		    						  <?php 
		    						  	$year=date("Y")+4;
		    						  	for ($i=0;$i<20;$i++) {
		    						  ?>
		    						  	<option <?php if ($heeler && $heeler->year == $year-$i) echo "selected"?> value="<?=$year-$i?>"><?=$year-$i?></option>
		    						  <?php } ?>	    						  
		    						</select>
		    						</td>
		    					</tr>
		    					
		    					
		    					<br>
		    					
		    					<tr><td>Email:</td><td><input type="text" name="email" value="<?php if ($heeler) echo $heeler->email?>" /></td></tr>
		    					
		    					<br><br>
		    							    				
							</table>
	    				
	    				<br>
	    				
	    				<input class="form_button" type="button" name="cancel" value="Cancel" onclick="go('heelmanager/heelers');" />
	    				<input class="form_button" type="submit" name="update" value="Update" />
	    				
	    				</div>
	    				</form>
	    				
	    				
	    				

	    				</div>


					
					

	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->