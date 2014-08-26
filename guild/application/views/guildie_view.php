<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				<br>
	    				<h3><a href="<?=base_url()?>index.php/guildies">Back to Guildies</a></h3>
	    				<br>
	    				<br>
	    				<h1> <?=$guildie->firstname." ".$guildie->lastname?></h1>
	    				<br>
	    				<?php if ($guildie->from) { ?> <h2><?=$entry->from?></h2><?php }?>
	    				
	    				<div class="guildie_entry_editLink"><h4><a href="<?= base_url() . "index.php/guildie/edit/".$entry->id?>">Edit Guildie</a></h4></div>
	    				
		    			<div id="guildie_entry_container">
	    				
	    				
	    				<div class="guildie_data guildie_photo" style="float:left;background-image:url('<?= base_url()."assets/images/GuildPhotos/".$row->photo?>')">
	    				</div>	
	    				
	    					
			    			<?php if ($guildie->from) { ?> <p><?=$entry->from?></p><?php }?>
			    			<?php if ($guildie->year) { ?> <p><?=$entry->year?></p><?php }?>
			    			
			    			<br><br>
			    			<?php if ($guildie->bio) { ?> <p><?=$entry->bio?></p><?php }?>
		    				
	    				</div>
	    				
	    				
	    				
	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->