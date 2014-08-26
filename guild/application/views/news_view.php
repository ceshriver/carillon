<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				
	    				<?php 
	    						$rows = $newsData;
	    						//$guildies = $guildData->result();
	    				 ?>
	    				
	    				<div id="newsButtonBar">
	    					
	    					<center><div id="newsEntryCount"><?=count($rows)?> News Stor<?=count($rows)==1?'y':'ies'?></div></center>
	    					<input onkeyup="newsSearch(this.value)" type="text" name="" value="" placeholder="Search…" style="float:left" />
	    					<br>
	    					
	    					<?php if ($guildie && $guildie->newsadmin) { ?>
	    					<div id="addButton" onclick="go('news/add');">+</div>
	    					<?php } ?>
	    				</div>
	    				
	    				
	    				<table class="news_table news_titles">
	    				<tr>
	    					<td width="20%"><div class="news_header"></div></td>
	    					<td width="35%"><div class="news_header">Title</div></td>
	    					<td width="25%"><div class="news_header">Author</div></td>
	    					<td width="15%"><div class="news_header">Date</div></td>
							<td width="5%"><div class="news_header"></div></td>

	    				</tr>
	    				</table>
	    				
						<div class="news_divider"></div>
	    				
	    				<table class="news_table" id="newsEntries">

						<?php
								
								date_default_timezone_set('America/New_York');
								
	    						for ($i=0;$i<count($rows);$i++) { 
	    							$row = $rows[$i];
	    				?>
	    					<tr id="<?php echo($i==0?'newsRowProxy':'');?>" class="news_results_row" tag="<?=$row->id?>">
	    						
	    						<td width="20%">
	    							<div class="news_data news_photo" style="background-size:contain;background-image:url('<?= base_url()."assets/images/NewsPhotos/".$row->thumb?>')">
	    							</div>
	    						</td>
	    						<td width="35%">
	    							<div class="news_data news_title"><?=$row->title?></div>
	    							<br>
	    							<i><div class="news_data news_content">
	    							
		    							<?php {
		    								$content = $row->content;
											if (strlen($content) > 100) $content = substr($content,0, 100)."…";
											echo $content;
		    							}?>
	    							</div></i>
	    						</td>
	    						<td width="25%"><div class="news_data news_author">
	    						
	    						<?php
	    							   if ($row->author > 0) {
	    							   
	    							   // Find the guildie
	    							   $author = "";
	    							   for ($p=0;$p<count($guildies);$p++) {
	    							   	if ($row->author == $guildies[$p]->id) {
	    							   		$author = $guildies[$p]->firstname." ".$guildies[$p]->lastname;
	    							   		break;
	    							   	}
	    							   }
	    							   echo $author;
	    							  }
	    						?>
	    							
	    							
	    						</div></td>
	    						<td width="15%">
	    						
	    						<div class="news_data news_date">
	    							<?php
	    								$date = $row->created;
	    								if (isset($row->updated)) $date = $row->updated;
	    								$date = date("M d, Y",strtotime($date));
	    								echo $date;
	    							?>
	    						</div></td>
	    						
	    						<?php if ($guildie && $guildie->newsadmin) { ?>
	    						
	    						<td width="5%"><div class="news_data news_edit"><a href="<?=base_url()?>index.php/news/edit/<?=$row->id?>">Edit</a></div></td>
								<?php } ?>
	    					</tr>
	    					
	    				<?php } ?>
	    				
	    				</table>
	    				
	    				
	    				
	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->