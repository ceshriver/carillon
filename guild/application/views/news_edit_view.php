<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				<br>
	    				<h3><a href="<?= base_url()."index.php/news"?>">Back to News</a></h3>
	    				<?php if ($story) { ?><h3 style="float:right"><a href="<?=base_url()."index.php/news/remove/".$story->id?>">Remove News Story</a></h3><?php } ?>
	    				<br>
	    				<br>
	    				
	    				<h1><?=$story?"Edit":"Add"?> News Story</h1>
	    				
	    				<br><br>
	    				
	    				
	    				
	    				<!-- Image Uploaded -->
	    				<?php 
	    					if (!$story || !$story->thumb) {
	    						$photo = "NewsPhotos/NoImage.png"; 
	    					} else {
	    						$photo = "NewsPhotos/".$story->thumb;
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
	    				
	    				<?=form_open('news/newsUpdate');?>
	    				
	    				<input id="photoInput" type="hidden" name="thumb" value="<?=$story?$story->thumb:"NoImage.png"?>" />
	    				
	    				
	    				<?php 
	    				
	    					date_default_timezone_set('America/New_York');
	    					$day1sql = date('Y-m-d H:i:s', time());
	    				?>
	    				
	    				<?= form_hidden('id', $story?$story->id:0)?>
	    				<?= form_hidden('updated', $day1sql)?>
	    				
	    				<?= form_hidden('created', !$story?$day1sql:$story->created)?>
	    				
	    				
	    					<div class="news_edit_entry_form">
	    				
	    					<table id="news_entry_table" class="news_entry_table news_table">
	    				
		    					<tr>
		    					<td>Title: </td><td><input type="text" name="title" value="<?=$story?($story->title):""?>" /></td>
		    					</tr>
		    					
		    					<br>
		    					<tr>
		    						<td>Author:</td>
		    						<td>
		    						<select name="author">
		    						
		    							<?php 
		    							for ($i=0;$i<count($guildies);$i++) {
		    								$sel = $guildies[$i]->id == $story->author?"selected":"";
		    								$name = $guildies[$i]->firstname." ".$guildies[$i]->lastname;
		    							?>
		    							
		    							<option <?=$sel?> value="<?=$guildies[$i]->id?>"><?=$name?></option>
		    							
		    							<?php
		    								}
		    							?>
		    								    								
		    						</select>
		    						</td>
		    					</tr>
		    					
		    					
		    					
		    					
		    					<tr>
		    					<td>Story:</td><td></td>
		    					</tr>
		    					
							</table>
							
							<textarea style="margin-left:55px" name="content" value=""><?= $story?($story->content):""; ?></textarea>
							
							<br>
							<br>
							
							
	    				<br>
	    				<input class="form_button" type="button" name="cancel" value="Cancel" onclick="window.location='<?=base_url()."index.php/news/"?>'" />
	    				<input class="form_button" type="submit" name="update" value="<?=$story?"Update":"Add"?>" />
	    				
	    				</div>
	    				</form>
	    				
	    				
	    				

	    				</div>
	    					    				

	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->