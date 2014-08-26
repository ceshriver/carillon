<style>
#libraryButtonBar {
	position: relative;
}
.libraryPaging {
	position: absolute;
	left: 220px;
	top: 30px;
}
.libraryPaging a {
	display: inline-block;
}
.library_data {
    font-size:8pt;
}
</style>

<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				
	    				
	    				
	    				
	    				<?php $rows = $libraryData->result(); ?>
	    				
	    				<div id="libraryButtonBar">
	    					
	    					<center><div id="libraryEntryCount"><?=count($rows)?> Record<?=count($rows)==1?'':'s'?></div></center>
	    					<br>
	    					<input onkeyup="performLibrarySearch(this.value)" type="text" name="" value="" placeholder="Search…" />
	    					<?=form_open('library/librarySelect');?>
	    					<div class="libraryPaging">
	    					<?php
	    						$keys = explode(" ","A B C D E F G H I J K L M N O P Q U R S T U V W X Y Z ALL");
	    						$selected = $this->session->userdata("libraryPage");
	    						if (!isset($libraryPage)) $libraryPage = "";
	    						for ($i=0;$i<count($keys);$i++) {
	    							$link = $keys[$i];    		
	    					 ?>
	    					<?php if ($libraryPage == $link) echo "<b>"; ?>
	    					<a href="./<?php echo $link?>"><?php echo $link?></a>
	    					<?php if ($libraryPage == $link) echo "</b>"; ?>
	    					
	    					<?php } ?>
	    					</div>
	    				
	    				<?php if ($user->libraryadmin) { ?>
	    					<div id="addButton" onclick="go('library/add');">+</div>
	    				<?php } ?>
	    				</div>
	    				
	    				
	    				<table class="library_table library_titles">
	    				<tr>
	    					<td width="3%"><div class="library_header">&nbsp;</div></td>
	    					<td width="25%"><div class="library_header">Composer</div></td>
	    					<td width="30%"><div class="library_header">Title</div></td>
	    					<td width="18%"><div class="library_header">Collection</div></td>
	    					<td width="22%"><div class="library_header">Arranger</div></td>
	    					<td width="0%"><div class="library_header">Genre</div></td>
	    				</tr>
	    				</table>
	    				
						<div class="library_divider"></div>
	    				
	    				<table class="library_table" id="libraryEntries">
	    					
	    					
	    					<?php
	    					
	    					
	    						for ($i=0;$i<count($rows);$i++) { 
	    							$row = $rows[$i];

	    					?>
	    					<tr id="<?php echo($i==0?'libraryRowProxy':'');?>" class="<?=($i%2==0?'':'lightRow') ?> library_results_row " tag="<?=$row->id?>" onclick="selectLibraryEntry($(this).attr('tag'));">
	    						
	    						<td width="5%"><div class="library_data library_data_selected">
	    							<?php 
	    							
	    								if ($selectMode) {
	    									?>
	    									
	    										<input type="checkbox" name="selection" value="<?=$row->id?>" />
	    									
	    									<?php
	    								}
	    							
	    							?>
	    						</div></td>
	    						
	    						<td width="25%"><div class="library_data library_data_composer"><?=$row->composer?></div></td>
	    						<td width="30%"><div class="library_data library_data_title"><?=$row->title?></div></td>
	    						<td width="8%"><div class="library_data library_data_collection"><?=$row->collection?></div></td>
	    						<td width="20%"><div class="library_data library_data_arranger"><?=$row->arranger?></div></td>
	    						<td width="12%"><div class="library_data library_data_genre"><?=$row->genre?></div></td>
	    					
	    					</tr>
	    					
	    					<?php } ?>
	    				
	    				
	    				
	    				</table>
	    				
	    				
	    				
	    				</form>
	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->