<?php 
	$days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"); 
?>


<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				<br>
	    				<h3><a href="<?= base_url()."index.php/guildies"?>">Back to Guildies</a></h3>
	    				<?php if ($guildie) { ?><h3 style="float:right"><a href="<?=base_url()."index.php/guildies/remove/".$guildie->id?>">Remove</a></h3><?php } ?>
	    				<br>
	    				<br>
	    				
	    				<h1><?=$guildie?"Edit":"Add"?> Guildie</h1>
	    				
	    				<br><br>
	    				
	    				
	    				<!-- Image Uploaded -->
	    				<?php 
	    					if (!$guildie) {
	    						$photo = "GuildPhotos/NoImage.png"; 
	    					} else {
	    						$photo = "GuildPhotos/".$guildie->photo;
	    					}
	    				?>
	    				
	    						<div id="guildie_image_uploader"
		    					style="background-image:url('<?= base_url()."assets/images/".$photo?>')"
		    					>
		    					<div id="guildie_image_uploader_overlay">Upload</div>
		    						
		    						
		    					
		    					</div>
	    					
	    				<!--  				-->
	    				
	    				
	    				
	    				<?php if (isset($error)) { ?>
	    				<font style="font-size:12pt;color:red"><?=$error?></font>
	    				<?php }?>
	    				
	    				<?=form_open('guildies/guildieUpdate');?>
	    				
	    				<input id="photoInput" type="hidden" name="photo" value="<?=$guildie?$guildie->photo:"NoImage.png"?>" />
	    				
	    				<?= form_hidden('id', $guildie?$guildie->id:0)?>
	    				
	    					<div class="guildie_edit_entry_form">
	    				
	    					<table class="guildie_entry_table guildie_table">
	    				
		    					<tr><td>First Name:</td><td><input type="text" name="firstname" value="<?php if ($guildie) echo $guildie->firstname?>" /></td></tr>
		    					<br>
		    					<tr><td>Last Name:</td><td><input type="text" name="lastname" value="<?php if ($guildie) echo $guildie->lastname?>" /></td></tr>
		    					
		    					<tr><td>Nick Name (Optional):</td><td><input type="text" name="nickname" value="<?php if ($guildie) echo $guildie->nickname?>" /></td></tr>
		    					
		    					
		    					
		    					<?php if($guildie->guildieadmin) { ?>
		    						<tr><td>NetID:</td><td><input type="text" name="netid" value="<?php if ($guildie) echo $guildie->netid?>" /></td></tr>
		    					<?php } else { ?>
		    						<tr><td>NetID:</td><td><?php if ($guildie) echo $guildie->netid?></td></tr>
		    					<?php } ?>
		    					
		    					
		    					<tr><td>Active:</td><td><input type="checkbox" name="active" value="<?php echo ($guildie && $guildie->active)?1:0;?>" <?php if ($guildie && $guildie->active) echo "checked"; ?>/></td></tr>
		    					
		    					
		    					<tr><td>Phone:</td><td><input type="text" name="phone" value="<?php if ($guildie) echo $guildie->phone?>" /></td></tr>
		    					<tr><td>Birthday:</td><td><input type="text" name="birthday" value="<?php if ($guildie) echo $guildie->birthday?>" /></td></tr>
		    					
		    					
		    					<tr>
		    						<td>College / Program:</td>
		    						<td>
		    						<select name="college">
		    							<option selected value="">None</option>
		    						  <?php 
		    						  	$colleges = array("Jonathan Edwards", "Siliman", "Pierson", "Trumbull", "Timothy Dwight", "Davenport", "Saybrook", "Branford", "Ezra Styles", "Morse", "Calhoun", "Berkeley", "Law", "Graduate Student", "Post Doc", "PHD");
		    						  	for ($i=0;$i<count($colleges);$i++) {
		    						  ?>
		    						  	<option <?php if ($guildie && $guildie->college == $colleges[$i]) echo "selected"?> value="<?=$colleges[$i]?>"><?=$colleges[$i]?></option>
		    						  <?php } ?>	    						  
		    						</select>
		    						</td>
		    					</tr>
		    					
		    					<tr><td>Major:</td><td><input type="text" name="major" value="<?php if ($guildie) echo $guildie->major?>" /></td></tr>
		    					
		    					
		    					<tr>
		    						<td>Year:</td>
		    						<td>
		    						<select name="year">
		    							<option selected value="">None</option>
		    						  <?php 
		    						  	$year=date("Y")+4;
		    						  	for ($i=0;$i<20;$i++) {
		    						  ?>
		    						  	<option <?php if ($guildie && $guildie->year == $year-$i) echo "selected"?> value="<?=$year-$i?>"><?=$year-$i?></option>
		    						  <?php } ?>	    						  
		    						</select>
		    						</td>
		    					</tr>
		    					
		    					
		    					<br>
		    					<tr><td>From:</td><td><input type="text" name="from" value="<?php if ($guildie) echo $guildie->from?>" /></td></tr>
		    					
		    					
		    					<tr><td>Email:</td><td><input type="text" name="email" value="<?php if ($guildie) echo $guildie->email?>" /></td></tr>
		    					<tr><td>Offices:</td><td><input type="text" name="office" value="<?php if ($guildie) echo $guildie->office?>" /></td></tr>
		    					
		    					
		    					<br><br>
		    					
								<tr><td>Ring Slot:</td>
									<td>
			    						<select name="ringID">
			    							<option selected value="0">None</option>
			    						  <?php 
			    						  	for ($i=0;$i<count($rings);$i++) {
			    						  ?>
			    						  	<option <?php if ($guildie && $guildie->ringID == $rings[$i]->id) echo "selected"?> value="<?=$rings[$i]->id?>">
			    						  	
			    						  	<?php 
			    						  	$row = $rings[$i];
			    						  	$hour = $row->hour;
			    						  	$AM = $hour>=12?"PM":"AM";
			    						  	$hour = $hour>12?$hour-12:$hour;
			    						  	$ringMinute = $row->minute;
			    						  	if ($ringMinute == 0) $ringMinute = "00";
			    						  	echo $days[$row->day]." ".$hour.":".$ringMinute." ".$AM;
			    						  	?>
			    						  	
			    						  	</option>
			    						  <?php } ?>	    						  
			    						</select>
			    					</td>
		    					</tr>

		    				
		    				<tr>
	    					<td>Bio</td>
	    					<td>
	    					<textarea name="bio" value=""><?php if ($guildie) echo $guildie->bio?></textarea>
	    					</td>
	    					</tr>
							</table>
	    				
	    				<br>
	    				
	    				
	    				<!-- What Guildies Play -->
	    				
	    				<h2> What they Play: </h2>
	    				<br>
	    				
	    				<table class="guildie_table guildies_play_table" id="guildies_play_table">
	    					
	    					<tr id="guildies_play_title_row" class="guildies_play_permanent">
	    						<td width="5%"></td>
	    						<td width="45%">Title</td>
	    						<td width="40%">Composer</td>
	    						<td width="10%">Year</td>
	    					</tr>
	    					
	    					<tr id="guildies_play_proxy_row" class="guildies_play_row guildies_play_permanent"  >
	    						<td width="5%"><div class="guildies_play_add">+</div></td>
	    						<td width="45%" class="library_data_title"></td>
	    						<td width="40%" class="library_data_composer"></td>
	    						<td width="10%" class="library_data_year"></td>
	    					</tr>
	    					
	    					<tr id="guildies_play_search_row" class="guildies_play_permanent">
	    						<td width="5%" class="guildies_play_select" colspan="1">
	    							
	    						</td>
	    						<td width="45%" class="library_data_title">
	    						<input type="text" name="" value="" onkeyup="searchForPieces(this.value)" placeholder="Search for Piece..." />
	    						</td>
	    						<td width="40%" class="library_data_composer"></td>
	    						<td width="10%" class="library_data_year"></td>
	    					</tr>
	    					
	    					
	    					<?php 
	    					if (isset($pieces)) {
	    						for ($i=0;$i<count($pieces);$i++) {
	    					?>
	    					
	    					<tr class="guildies_play_row guildies_play_permanent" tag="<?=$pieces[$i]->id?>">
	    						<td width="5%"><div class="guildies_play_add guildies_play_add_selected">&#x2713;</div></td>
	    						<td width="45%"><?=$pieces[$i]->title?></td>
	    						<td width="40%"><?=$pieces[$i]->composer?></td>
	    						<td width="10%"><?=$pieces[$i]->year?></td>
	    					</tr>
	    					
	    					<?php
	    						}
	    					}
	    					?>
	    					
	    					
	    					
	    					
	    					
	    				</table>
	    				
	    				
	    				<div id="piecesPlayed">
	    				
	    				<?php 
	    				if (isset($pieces)) {
	    					for ($i=0;$i<count($pieces);$i++) {
	    				?>
	    					<input type="hidden" name="pieces[]" value="<?=$pieces[$i]->id?>" />
	    				
	    				<?php
	    					}
	    				}
	    				?>
	    				
	    				</div>
	    				
	    				
	    				
	    				<input class="form_button" type="button" name="cancel" value="Cancel" onclick="go('guildies/');" />
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