<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				<br>
	    				<h3>
	    					<a href="<?= base_url()."index.php/recordings"?>">Back to Recordings</a>
	    				</h3>
	    				
	    				<div id="scheduleButtonBar">	    					
	    					<div id="updateButton" class="menuButton" style="display:none" onclick="$('#shareForm').submit()">Save</div>
	    				</div>
	    				
	    				<br>
	    				
	    				<!-- Image Uploaded -->
	    				<?php 
	    					if (!$guildie) {
	    						$photo = "GuildPhotos/NoImage.png"; 
	    					} else {
	    						$photo = "GuildPhotos/".$guildie->photo;
	    					}
	    				?>
	    						<div id="recordings_guildie_image" style="background-image:url('<?= base_url()."assets/images/".$photo?>')">		    						
	    						</div>
	    				<!--  				-->
	    				</div>
	    				
	    				
	    				<br>
	    				
	    				<?php if ($guildie) { ?>
	    				<h1><?=$guildie->firstname?>'s Recordings</h1>
	    				<?php } ?>
	    				<i>
	    				
	    				<br>
	    				<?php
	    					$composer = $piece?$piece->composer:"";
	    					$title = $piece?$piece->title:$path;
	    					$year = $piece?$piece->year:"";
	    				?>
							<br>
							<h2><?=$title?></h2>
							<br>
	    				</i>
	    				
	    				<?php 
	    					$recordingLink = base_url().'index.php/recordings/manageFolder?path='.$_GET['path']."&library=".$_GET['library']."&guildie=".$_GET['guildie'];
	    				?>
	    				
	    				<form action='<?php echo $recordingLink?>' method="post" accept-charset="utf-8" id="shareForm">
	    					<input type="hidden" name="path" id="path" value="<?=urlencode($path)?>"/>
	    					<?php if ($guildie) {?><input type="hidden" name="guildie" id="guildie" value="<?=$guildie->id?>"/><?php } ?>
	    				
	    				
	    				
	    					<ul class="shared_list">
	    						<?php
	    							
	    							if ($sharedRecordings) {
	    									$allowedGuildies = $sharedRecordings["AllowedGuildies"];
	    						
	    									
	    						
	    									//echo "<pre>";
	    									//print_r($sharedRecordings);
	    									//echo "</pre>";
	    									
	    									$sharesCount = 0;
	    						
	    							   	 	for ($p=0;$p<count($allowedGuildies);$p++) {
	    							   	 		$index = $allowedGuildies[$p];
	    						?>
	    							   	 		<li class="ringerList">
	    							   	 				<!-- Hidden Input Goes Here -->
	    							   	 				<input type="hidden" name="shares__<?=$sharesCount?>" id="guildie" value="<?=$guildie->id?>">
	    							   	 				<?php $sharesCount++ ?>
	    							   	 				
	    							   	 				<?php
	    							   	 					
	    							   	 					$aGuildie = false;
	    							   	 					for($i=0;$i<count($guildies);$i++) {
	    							   	 						if ($guildies[$i]->id == $index) {
	    							   	 							$aGuildie = $guildies[$i];
	    							   	 							break;
	    							   	 						} 
	    							   	 					}
	    							   	 					
	    							   	 					$surname = $aGuildie->firstname;
	    							   	 					if ($aGuildie->nickname) $surname = $aGuildie->nickname;
	    							   	 				
	    							   	 				$removeFunc = "";
	    							   	 				
	    							   	 				if ($guildie->id == $me->id) {
	    							   	 				$removeFunc = "removeFromRing(this)";
	    							   	 				}
	    							   	 				
	    							   	 				?>
	    							   	 				<a tag="<?php echo ""?>" id="<?php echo $guildies[$index]->id?>" onclick="<?php echo $removeFunc;?>">
	    							   	 					<?=$surname." ".$aGuildie->lastname;?>
	    							   	 				</a>
	    							   	 		</li>
	    						<?php
	    							   	 	}
	    							   	 	
	    							}
	    						?>
	    						
	    						
	    						<?php
	    							if ($guildie->id == $me->id) { 
	    							//Only show the share field if you own the folder
	    						?>
	    						
	    						
	    						<select id="guildieSelectProxy" style="display:none">
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

	    						
	    						<?php } ?>
	    						
	    					</ul>
	    					
	    					<?php
	    						if ($guildie->id == $me->id) {
	    					?>
	    					
	    					<a tag="<?php echo ""?>" onclick="shareWithGuildie(this)" id="shareText">+ Share Recording</a>
	    					
	    					<?php
	    						}
	    					?>
	    				
	    				</form>
	    				
	    				
	    				
	    				<table class="recording_table recording_manage_table">
	    					<tr class="recording_selection_table_titlerow"><td><div>Categorize</div></td><td></td><td></td><td></td></tr>
	    					
		    					<tr id="recording_selection_table_firstrow" class="recording_selection_table_firstrow" onclick="categorizeRecording()">

		    						<td width="50%"><div class="recording_selection_header library_data_title"><?=$title?></div></td>
		    						<td width="30%"><div class="recording_selection_header library_data_composer"><?=$composer?></div></td>
		    						<td width="10%"><div class="recording_selection_header library_data_year"><?=$year?></div></td>
		    						<td width="10%"><div class="recording_selection_header"></div></td>
		    					
		    					</tr>
	    				</table>
	    				
	    				


	    				<table id="recordings_edit_search_table" class="recording_table recording_titles recording_manage_table_titles recording_selection_table">
	    					
	    					<tr id="recordings_edit_search_proxy_row" class="recordings_edit_search_result">
		    					<td width="50%"><div class="recording_selection_header library_data_title">></div></td>
		    					<td width="30%"><div class="recording_selection_header library_data_composer"></div></td>
		    					<td width="10%"><div class="recording_selection_header library_data_year"></div></td>
		    					<td width="10%"><div class="recording_selection_header"></div></td>
	    					</tr>
	    					
	    					
	    					<tr id="recordings_edit_search_row" class="recording_table recording_manage_table recording_selection_table recordings_row_permanent">
	    						<td width="50%">
	    							<input onkeyup="recordingCategorySearchForPieces(this.value)" type="text" name="" value="" placeholder="Search for Piece..." />
	    						</td>
	    						<td width="30%"><div class="recording_selection_header"></div></td>
	    						<td width="10%"><div class="recording_selection_header"></div></td>
	    						<td width="10%"><div class="recording_selection_header"></div></td>
	    					</tr>
	    				</table>
	    				
	    				
	    				
	    				
	    				<table class="recording_table recording_manage_table">
	    				
	    				
	    				<?php 
	    				
	    				date_default_timezone_set('America/New_York');
	    				
	    				
	    				
	    				$files = $records['files'];
	    				$folders = $records['folders'];
	    					
	    				$returnLink = $_SERVER['PATH_INFO']."?path=".rawurlencode($path);
	    					
	    					
	    				//echo "<pre>";
	    				//print_r($records);
	    				//echo "</pre>";
	    					
	    				for ($i=0;$i<count($files)+count($folders);$i++) { 
	    				
	    					$resource = $i<count($files)?$files[$i]:$folders[$i-count($files)];
	    					
	    					$date = date("h:m - M d, Y",$resource['modified']);
	    					
	    					$resourcePath = base_url().$resource['path'];
	    					
	    					$path = explode("/",$resource['path']);
	    					$path[count($path)-1] = rawurlencode($path[count($path)-1]);
	    					$path = implode("/",$path);
	    					
	    					$resourceName = $resource['name'];
	    					
	    					$guildieID = $guildie?$guildie->id:0;
	    					$editLink = "edit?path=".$path."&guildie=".$guildieID."&return=".rawurlencode($returnLink)."&library=".($piece?$piece->id:"0");
	    					
	    					$inLibrary = false;
	    					if (isset($item['inLibrary'])) {
								$inLibrary = true;
								$libraryEntry = $item['inLibrary'];
	    					}
	    					
	    				?>
	    				
	    					<tr>
	    						<td width="10%" class="recording_records_row_play">
	    							<a href="<?php echo $resourcePath?>">
	    							<?php if ($i<count($files)) {?>
	    								<div class="recording_records_row_play_button"></div>
	    							<?php }?>
	    							</a>
	    						</td>
	    						<td width="60%" class="recording_records_row_name">
	    							<?php if ($i>=count($files)) { 
	    							//"
	    							
	    							$recordingLink = base_url().'index.php/recordings/manageFolder?path='.rawurlencode($path)."&library=".($inLibrary?$libraryEntry:"0")."&guildie=".($guildie?$guildie->id:"0");
	    							?>
	    							
	    							<a href="<?php echo $recordingLink?>">
	    								<?php echo $resourceName ?>
	    							</a>
	    							<?php } else { ?>
	    								<?php echo $resourceName ?>	    							
	    							<?php } ?>
	    							
	    						</td>
	    						<td width="30%" class="recording_records_row_date"><?php echo $date ?></td>
								<td width="10%" class="recording_records_row_delete"><a href="<?php echo $editLink ?>">Edit</a></td>
	    					</tr>
	    				
	    				
	    				<?php } ?>
	    				
	    				
	    				<?php if (count($files) == 0) { ?>
	    				
	    					<tr>
	    						<td width="10%" class="recording_records_row_play">
	    						</td>
	    						<td width="60%" class="recording_records_row_name"><i>No Recordings</i></td>
	    						<td width="30%" class="recording_records_row_date"></td>
	    						<td width="10%" class="recording_records_row_delete"></td>
	    					</tr>
	    				
	    				<?php } ?>
	    				
	    				</table>

						
						
	    				
	    				
	    				<div style="display:none">
		    				Guildie:
		    				<?php if ($guildie) { print("<pre>".print_r($guildie,true)."</pre>"); } ?>
		    				<br>
		    				Piece
		    				<?= print("<pre>".print_r($piece,true)."</pre>"); ?>
		    				
		    				
	    				</div>
	    				      	
	    			</div>		      	
	    	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->


<script>
function shareWithGuildie(elem) {
	var proxy = $("#guildieSelectProxy").clone();
	//$("#guildieSelect").remove();
	
	proxy.attr('id','guildieSelect');
	proxy.css('display','block');
	
	
	elem2 = elem;

	var ringID = $(elem).attr('tag');
	
	//names of the drop downs will be in the following format
	// "add_0"
	// "add__" + (index of dropDown)
	var numElem = $(elem).parent().parent().find("select").length;
	proxy.attr('name', "add__"+numElem);
	
	/*var items = proxy.children("select").children('option');
	for (var i=0;i<items.length;i++) {
		var guildieID = $(items[i]).attr("value");
		//console.log(guildieID);
		var newValue = ringID + "." + guildieID;
		$(items[i]).attr('value',newValue);
	}*/
	$("#shareText").before(proxy);
	//$(elem).parent().after(proxy);
	
	
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