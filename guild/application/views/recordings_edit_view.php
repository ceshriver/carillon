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
	    				
	    				<h1>
	    				<?php
	    					if ($guildie) {
	    						$name = $guildie->firstname."'s Recordings";
	    					} else {
	    						$name = $path;
	    					}
	    				?>
	    				</h1>
	    				
	    				<br>
	    				
	    				<!-- -.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-. -->
	    				
	    				<?=form_open('recordings/recordingUpdate');?>
	    				
	    				<input type="hidden" name="path" value="<?=$path?>" />
	    				
	    				<input type="hidden" name="return" value="
	    				<?=
	    					$_SERVER['PATH_INFO']
	    				?>
	    				" />
	    				
	    				<?php if ($guildie) { ?>
	    				<h1><?=$name?></h1>
	    				<br>
	    				<?php } ?>
	    				
	    				<?php
	    					$composer = $piece?$piece->composer:"";
	    					$title = $piece?$piece->title:$path;
	    					$year = $piece?$piece->year:"";
	    				?>
							<br>
							<h2><?=$title?></h2>
							<br>
	    				</i>
	    				
	    				
	    				<br><br><br><br>
	    				
	    				<?php 
	    						$pieceName = explode("/",$path);
	    						$pieceName = $pieceName[count($pieceName)-1];
	    				?>
	    				
						Piece Name: <input type="text" id="pieceinput" name="piecename" value="<?=$pieceName?>" />
	    				
	    				<br>
	    				
	    				
	    				
	    				
	    				
	    				<form> 
	    					    					<input type="hidden" name="path" id="path" value="<?=$path?>"/>
	    					    					<?php if ($guildie) {?>
	    					    						<input type="hidden" name="guildie" id="guildie" value="<?=$guildie->id?>"/>
	    					    					<?php } ?>
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
	    				
	    				
	    				
	    				
	    				
	    				
	    				
	    				<br><br>
	    				
	    				<?php 
	    					$return = '/recordings/';
	    					if (isset($_GET['return'])) {
	    						$guildieID = $guildie?$guildie->id:0;
	    						$return = $_GET['return']."&guildie=".$guildieID."&library=".($piece?$piece->id:"0");
	    					}
	    				?>
	    				
	    				<input class="form_button" type="button" name="cancel" value="Cancel" onclick="go('<?=$return?>');" />
	    				<input class="form_button" type="submit" name="update" value="Update" />
	    				
	    				
	    				
	    				</form>
	    				
	    				<!-- -.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-. -->
	    				
	    				<div style="display:none">
		    				Guildie:
		    				<?= print("<pre>".print_r($guildie,true)."</pre>"); ?>
		    				<br>
		    				Piece
		    				<?= print("<pre>".print_r(false,true)."</pre>"); ?>
	    				</div>
	    				      	
	    			</div>		      	
	    	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->