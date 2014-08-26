<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				<br>
	    				<h3><a href="../">Back to Library</a></h3>
	    				<br>
	    				<br>
	    				
	    				<h1><?=$entry?"Edit":"Add"?> Entry</h1>
	    				
	    				<style>
	    					.sheet_music_uploader {

	    					}
	    					#sheet_image_upload_button_style {
	    						width:100px;
	    						height:20px;
	    						color: #003399;
	    						border: solid 2px #003399;
	    						float:right;
	    						text-align:center;
	    						vertical-align:middle;
	    						border-radius: 7px;
	    						line-height:18px;
	    					}
	    					#sheet_image_upload_button_style:hover {
	    						color: gray;
	    						border-color:gray;
	    					}
	    					#sheet_image_upload_button_style {
	    						margin-bottom:10px;
	    					}
	    					.library_entry_editLink {
	    						
	    					}
	    					
	    				</style>
	    				
	    				<!-- Sheet Music Uploaded -->
	    				<?php 
	    					$hasSheet = false;
	    					if (count($sheetMusicLinks) > 0) $hasSheet = true;
	    				?>
	    				
	    				
	    				
	    				<div class="library_entry_editLink">
	    					<?php if ($entry) { ?><h4><a href="<?=base_url()."index.php/library/remove/".$entry->id?>">Remove Entry</a></h4><?php } ?>
	    					
	    					<?php if ($entry) { ?>
	    					<div id="sheet_image_upload_button_style">
	    						<div id="sheet_image_upload_button"><?= !$hasSheet?"Upload Sheet" : "Add Sheet"?></div>
	    					</div>
	    					<?php } ?>
	    					
	    					<br><br>
	    				
	    					
	    					<?php if ($hasSheet) { 
	    					
	    					for ($i=0;$i<count($sheetMusicLinks);$i++) {
							?>
								<a href="<?php echo $sheetMusicLinks[$i]->link; ?>"><?php echo basename($sheetMusicLinks[$i]->link); ?></a>
								<? if ($i < count($sheetMusicLinks)-1) echo "<br>"; ?>
									
							<?php 
									} 
								}
							?>
	    				
	    				</div>
	    				
	    				<br><br>
	    				
	    				<?php if (isset($error)) { ?>
	    				<font style="font-size:12pt;color:red"><?=$error?></font>
	    				<?php }?>
	    				
	    				
	    				<?=form_open('library/entryUpdate');?>
	    				
	    				
	    				<?= form_hidden('id', $entry?$entry->id:0)?>
	    				
	    					<div class="library_edit_entry_form">
	    				
	    					<table class="library_table">
	    				
	    					<tr><td>Title:</td><td><input type="text" name="title" value="<?php if ($entry) echo $entry->title?>" /></td></tr>
	    					
	    					<br><br>
	    					
	    					
	    					<tr><td>Composer:</td> <td><input type="text" name="composer" value="<?php if ($entry) echo $entry->composer?>" /></td></tr>
	    					<tr><td>Arranger:</td><td><input type="text" name="arranger" value="<?php if ($entry) echo $entry->arranger?>" /></td></tr>

	    					
	    					<tr>
	    						<td>Year:</td> 
	    						<td><input type="text" name="year" value="<?php if ($entry) echo $entry->year?>" /></td>
	    					</tr>
	    					
	    					<tr><td></td><td></td></tr>
	    					
	    					
	    					
	    					<tr>
	    						<td>Genre:</td> 
	    						<td><input type="text" name="genre" value="<?php if ($entry) echo $entry->genre?>" /></td>
	    					</tr>
	    					
	    					<tr>
	    						<td>Filed Under:</td> 
	    						<td><input type="text" name="filedunder" value="<?php if ($entry) echo $entry->filedunder?>" /></td>
	    					</tr>
	    					
	    					
	    						    					
	    					
	    					<tr>
	    						<td>Time:</td>
	    						<td>
	    						<select name="time">
	    							<option selected value="">Unknown</option>
	    						  <?php 
	    						  	for ($i=1;$i<21;$i++) {
	    						  	
	    						  		$label = floor($i/2).":".($i%2!=0?'30':'00');
	    						  ?>
	    						  	<option <?php if ($entry && $entry->year == $label) echo "selected"?> value="<?=$label; ?>"><?=$label; ?></option>
	    						  	
	    						  <?php } ?>	    						  
	    						</select>
	    						</td>
	    					</tr>
	    					
	    					<tr>
	    						<td>Copies:</td>
	    						<td>
	    						<select name="copies">
	    						  <?php 
	    						  	for ($i=1;$i<11;$i++) {
	    						  ?>
	    						  	<option <?php if ($entry && $entry->copies == $i) echo "selected"?> value="<?=$i; ?>"><?=$i; ?></option>
	    						  <?php } ?>	    						  
	    						</select>
	    						</td>
	    					</tr>
	    					
	    					
	    					<tr><td>Collection:</td><td><input type="text" name="collection" value="<?php if ($entry) echo $entry->collection?>" /></td></tr>
	    					
	    					
	    					
	    					
	    					
	    					<tr>
	    						<td>Octaves:</td>
	    						<td>
	    						<select name="octaves">
	    							<option selected value="">Unknown</option>
	    						  <?php 
	    						  	for ($i=1;$i<10;$i++) {
	    						  ?>
	    						  	<option <?php if ($entry && $entry->octaves == $i) echo "selected"?> value="<?=$i?>"><?=$i?></option>
	    						  	
	    						  <?php } ?>	    						  
	    						</select>
	    						</td>
	    					</tr>
	    					
	    					<tr>
	    						<td>Key:</td> 
	    						<td><input type="text" name="key" value="<?php if ($entry) echo $entry->key?>" /></td>
	    					</tr>
	    					
	    					</table>
	    					
	    					
	    					
	    				
	    				<br><br>
	    				<input class="form_button" type="button" name="cancel" value="Cancel" onclick="window.location='<?=base_url()."index.php/library/"?>'" />
	    				<input class="form_button" type="submit" name="update" value="Update" />
	    				
	    				</form>

	    				</div>
	    				
	    				
	    				
	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->