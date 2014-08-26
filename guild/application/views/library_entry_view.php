<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				<br>
	    				<h3><a href="<?=base_url()?>index.php/library">Back to Library</a></h3>
	    				<br>
	    				<br>
	    				<h1> <?=$entry->title?></h1>
	    				
	    				
	    				
	    			
	    				
	    				<?php if ($user->libraryadmin) { ?>
							<div class="library_entry_editLink">
								<h4><a href="<?= base_url() . "index.php/library/edit/".$entry->id?>">Edit Entry</a></h4>
								<?php if ($entry) { ?><h4><a href="<?=base_url()."index.php/library/remove/".$entry->id?>">Remove Entry</a></h4><?php } ?>
							
							
							<?php 
	    						$hasSheet = false;
	    						if (count($sheetMusicLinks) > 0) $hasSheet = true;
	    					?>	
	    					
							<?php if ($hasSheet) { ?>
	    					<br>
	    					<h4>Sheet Music: </h4>
	    					<?php for ($i=0;$i<count($sheetMusicLinks);$i++) { ?>
								<a href="<?php echo $sheetMusicLinks[$i]->link; ?>"><?php echo basename($sheetMusicLinks[$i]->link); ?></a>
								<? if ($i < count($sheetMusicLinks)-1) echo "<br>"; ?>	
							<?php 
									} 
								}
							?>
							</div>
							
							
							
	    				<?php } ?>
						
						
	    				
	    				
		    			<div id="library_entry_container">
	    				
		    				<table class="library_entry_table library_table">
		    					
		    					<tr><td></td></tr>
		    					
		    					<?php if ($entry->composer) { ?><tr><td class="library_floatTable_col1">Composer</td><td><?=$entry->composer?></td></tr><?php }?>
		    					<?php if ($entry->arranger) { ?><tr><td class="library_floatTable_col1">Arranger</td><td><?=$entry->arranger?></td></tr><?php }?>
		    					<?php if ($entry->year) { ?><tr><td class="library_floatTable_col1">Year</td><td><?=$entry->year?></td></tr><?php }?>
		    				
		    				
						
		    				
			    			<?php if ($entry->time) { ?> <p><b>Time:</b> <?=$entry->time?></p> <?php }?>
			    			<?php if ($entry->collection) { ?> <p><b>Collection:</b> <?=$entry->collection?></p><?php }?>
			    			<?php if ($entry->arranger) { ?> <p><b>Arranger:</b> <?=$entry->arranger?></p><?php }?>
			    			<?php if ($entry->genre) { ?> <p><b>Genre:</b> <?=$entry->genre?></p><?php }?>
			    			<?php if ($entry->octaves) { ?> <p><b>Octaves:</b> <?=$entry->octaves?></p><?php }?>
		    				<?php if ($entry->key) { ?> <p><b>Key:</b> <?=$entry->key?></p><?php }?>
		    				
		    				<tr><td></td></tr>
		    				
		    				<tr><td colspan="2">
		    				
		    				<?php if ($players) { ?>
		    				<h2>Who Plays This Song</h2>
		    					<br>
	    					
	    					<table id="library_who_plays_table">
	    						<?php
	    						 for($i=0;$i<count($players);$i++) { 
	    							
	    							$url = base_url()."assets/images/GuildPhotos/".$players[$i]->photo;
	    							$name = $players[$i]->firstname." ".$players[$i]->lastname;
	    						?>
	    						
	    						<tr>
	    							<td width="25%" class="library_who_plays_photo"><div class="library_who_plays_photo_frame" style="background-image:url('<?=$url?>')"></div></td>
	    							<td width="75%" class="library_who_plays_name"><?=$name?></td>
	    						</tr>
	    						
	    						<?php } ?>
	    					
	    					</table>
	    					<?php } ?>
	    					
	    					</td><td></td>
	    					</tr>
	    					
	    					</table>
	    					
	    				</div>
	    				
	    				
	    				
	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->