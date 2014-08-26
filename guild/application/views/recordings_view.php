<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				
	    				
	    				<div id="recordingButtonBar">
	    					<br><br>
	    					<br>
	    					
	    					
	    					<?php $recordCount = count($folders);?>
	    					
	    					<br>
	    					
	    					<center><div id="recordingEntryCount"><?=$recordCount?> Recording<?=$recordCount==1?'':'s'?></div></center>
	    					<input onkeyup="recordingSearch(this.value)" type="text" name="" value="" placeholder="Search…" style="float:left" />
	    					<!--<div id="addButton" onclick="go('guildies/add');">+</div>-->
	    				</div>
	    				
	    				<div style="display:none">
	    				Folders
	    				<?= print("<pre>".print_r($folders,true)."</pre>"); ?>
	    				
	    				<br><br>
	    				
	    				Guildies
	    				<br><br>
	    				<?php //echo print("<pre>".print_r($guildies,true)."</pre>"); 
	    				?>
	    				</div>
	    				
	    				
	    				<table class="recording_table recording_titles" style="margin-top:70px">
	    				<tr>
	    					<td width="25%"><div class="recording_header"></div></td>
	    					<td width="45%"><div class="recording_header">Record Name</div></td>
	    					<td width="20%"><div class="recording_header recording_date">Date</div></td>
	    					<td width="10%"><div class="recording_header"></div></td>
	    				</tr>
	    				</table>
	    				
						<div class="recording_divider"></div>
	    				
	    				<table class="recording_table" id="recordingEntries">
	    					
	    					<?php
	    					
	    					// This handles all the Guildie Recordings
	    					
	    						date_default_timezone_set('America/New_York');
	    					
	    					
	    						
	    						$shouldShow = false;
	    					
	    						for ($i=0;$i<count($folders);$i++) { 
	    							
	    							$firstRow = true;
	    							
	    							$guildieSharedPieces = array();
	    							$folder = $folders[$i];
	    							
	    							$guildieFiles = $folder['contents']['files'];
	    							
	    							$guildieFolders = $folder['contents']['folders'];
	    							$guildie = false;
	    							if (isset($folder['guildie'])) {
	    								$guildie = $folder['guildie'];
	    							}
	    							
	    							
	    							$hasSharedItems = false;
	    							
	    							
	    							$ringer = $folder['name'];
	    							if ($guildie) {		
	    								$ringer = $guildie->firstname." ".$guildie->lastname;
	    								if ($me->id != $guildie->id) {
	    								
	    									//We should only show this folder if it has been shared!
	    									
	    									$sharedSongs = false;
	    									if ($sharedRecordings && count($sharedRecordings) != 0) {
	    										$sharedSongs = $sharedRecordings[0];
	    									}
	    									
	    									if ($sharedSongs) {
	    										foreach ($sharedSongs as $key => $value) {
	    											if ($guildie->id == $key) {
	    												$guildieSharedPieces = $value;
	    											}
	    										}
	    										//Guildie has some things to share. keep them and search their folder
	    									} else {
	    										continue;
	    									}
	    								}
	    							}
	    							
	    							//echo "<pre>";
	    							//print_r($guildieSharedPieces);
	    							//echo "</pre>";
														
	    							for ($p=0;$p<count($guildieFiles)+count($guildieFolders);$p++) {
	    								
	    								
	    								$isFile = $p<count($guildieFiles);
	    								$item = $isFile?$guildieFiles[$p]:$guildieFolders[$p-count($guildieFiles)];
	    								
										//Should we show this item?
											// - if it's me then show
											// - if it's shared with me then show		
										$shouldShow = false;
										if ($guildie && $guildie->id == $me->id) $shouldShow = true;
										
										$hasKey = array_key_exists($item['path'],$guildieSharedPieces);
										
										$hasString = false;
										$actualFolder = $item['path'];
										
										
										
										foreach($guildieSharedPieces as $key => $value ){
											
											/*
											echo "<pre>";
											print_r($item['path']);
											echo "<br>";
											echo $key;
											echo "</pre>";
											echo "<br>";echo "<br>";
											*/
											
											if (strpos($key,$item['path']) !== false) {
												//echo "HERE";
												$actualFolder = $key;
												$hasString = true;
												break;
											}
										}
										
										if ($hasKey || $hasString) {
											
											$sharers = $guildieSharedPieces[$actualFolder]["AllowedGuildies"];
											
											//echo "<pre>";
											//print_r($guildieSharedPieces);
											//echo "</pre>";
											
											
											for ($o=0;$o<count($sharers);$o++) {
												if ($sharers[$o] == $me->id) {
													$shouldShow = true;
													$hasSharedItems = true;
													break;
												}
											}
										}
										
										
										
										if (!$shouldShow) continue;

	    								$date = date("M d, Y",$item['modified']);
	    								$recordName = $item['name'];
	    								
										$elements = explode("/",$item['path']);
										$elements[count($elements)-1]  = rawurlencode($elements[count($elements)-1]);
										
	    								$recordingLink = base_url().implode("/",$elements);
	    								
	    								
	    								$inLibrary = false;
	    								
	    								if (isset($item['inLibrary'])) {
	    									$inLibrary = true;
	    									$libraryEntry = $item['inLibrary'];
	    								}
	    								
	    								
	    								if (!$isFile) $recordingLink = base_url().'index.php/recordings/manageFolder?path='.rawurlencode($item['path'])."&library=".($inLibrary?$libraryEntry:"0")."&guildie=".($guildie?$guildie->id:"0");
	    								
	    								
	    					?>
	    					
		    					<tr id="<?php echo($i==0?'recordingRowProxy':'');?>" class="recording_results_row " tag="">
		    							    						
		    						<!-- Show picture for first entry (if has guildie) -->
		    						
		    						<?php 
		    						$photo = "none";
		    						if ($firstRow) { 
		    							if ($guildie) {
		    								$photo = base_url()."assets/images/GuildPhotos/".$guildie->photo;
		    								$firstRow = false;
		    							}
		    						// $firstRow?count($guildieFiles)+count($guildieFolders):"";
		    						}
		    						
		    						?>
		    						
		    						<td width="25%">
											<div class="recording_data recording_photo" style="background-image:url('<?=$photo?>')">
												<?php
													if (!$guildie) {
												?>
													<h2><?=$ringer?></h2>	
												<?php
													}
												?>
											</div>
									</td>
		    						
		    						
		    						 						
		    						<td width="45%">
		    						<div class="recording_data recording_name">
		    								<a href="<?=$recordingLink?>">
		    									<?php
		    									
		    									if ($isFile) echo "<i>";
		    									if (!$isFile && $inLibrary) echo "<b>";
		    									echo $recordName;
		    									
		    									if (!$isFile && $inLibrary) echo "</b>";
		    									if ($isFile) echo "</i>";
		    									
		    									?>
		    									
		    								</a>
		    						</div>
		    						</td>

		    						<td width="20%"><div class="recording_data recording_date"><?=$date?></div></td>
		    						
		    						<?php
		    							$fileLink = base_url().'index.php/recordings/edit?path='.rawurlencode($item['path'])."&guildie=".($guildie?$guildie->id:"0")."&library=".($inLibrary?$libraryEntry:"0");
		    							
		    						?>
		    						
		    						<td width="10%">
										<div class="recording_data recording_edit">
											<?php if ($isFile) {?><a href="<?=$fileLink?>"><?php }?>
										
											<?=$isFile?'Edit':''?>
										
											<?php if ($isFile) {?></a><?php }?>
										
										</div>
		    						</td>
		    						
								</tr>	    					
	    				
							<?php }
							
								//if (!$shouldShow) continue;
							
								//if (count($guildieFiles) > 0 || count($guildieFolders) > 0) {
								$isMyFolder = $guildie && $guildie->id == $me->id;
								
								$showGuildie = count($guildieFiles) > 0 || count($guildieFolders) > 0;
								$showGuildie = $showGuildie && $shouldShow;
																
								if ($isMyFolder || $hasSharedItems) {
							?>
							
								<tr class="recordings_divider_row" style="height:40px">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								
								<tr class="" style="height:40px">
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							
							<?php
								}
							
							} ?>
			
	    				
	    				</table>
	    				
	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->