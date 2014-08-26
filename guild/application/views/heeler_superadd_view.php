<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				<br>
	    				<h3><a href="<?= base_url()."index.php/heelmanager/heelers"?>">Back to Heelers</a></h3>
	    				<br>
	    				<br>
	    				
	    				<h1>Super Add Heelers</h1>
	    				
	    				<br><br>
	    				
	    				<div style="width:758px"></div>
	    				
	    				<?php if (isset($error)) { ?>
	    				<font style="font-size:12pt;color:red"><?=$error?></font>
	    				<?php }?>
	    				
	    				<br><br>
	    				
	    				<?php echo form_open('./heelmanager/superaddSubmit');?>
	    				
	    				<h2>Enter Net IDs Separated by New Returns:</h2>
	    				<br>
	    				<textarea name="netids" width="400" height="300"></textarea>
	    				
	    				<br><br>
	    				
		    			<input class="form_button" type="button" name="cancel" value="Cancel" onclick="go('heelmanager/');" />
	    				<input class="form_button" type="submit" name="update" value="Update" />
	    				
	    				
	    				</form>
	    				
	    				</div>

					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->