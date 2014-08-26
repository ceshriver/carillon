<div id="main" class="column">
	<div id="main-inner">                  
		<div id="content">
     	 	
     	 	<div id="content-content" class="clearfix">
        		<div id="node-8" class="node node-type-page node-published clearfix">
	  				<div class="content">
	    				
	    				<?php 
	    						//$rows = $newsData;
	    						//$guildies = $guildData->result();
	    				 ?>
	    				
	    				
	    				<font style="color:red"><h2>THIS IS THE OLD VERSION OF THE CONSTITUTION!<br>NEW VERSION COMING SOON</h2></font>
	    				
	    				<br><br>
	    				
	    				<h1>Guild Constitution</h1>
	    				<br><br>
	    				
	    				<?php
	    				//Build the HTML version of the constitution
						$CONSTITUTION_FILE = 'assets/documents/constitution.rtf';
						$HTML_FILE = 'assets/documents/constitution.html';
						if (1) {
							//php rtf2htm.php -par ../testRTF.rtf ../testOutput.htm
							$SHELL_COMMAND = "php assets/rtf2htm/rtf2htm.php -par " . $CONSTITUTION_FILE . " " . $HTML_FILE;
							exec($SHELL_COMMAND,$output);
						}
						$constitution_html = file_get_contents($HTML_FILE);
	    				echo $constitution_html;
	    				?>
	    				
	    				
					</div>
				</div>
      	</div> <!-- /content-content -->
		</div> <!-- /content -->
	</div> <!-- /#main-inner -->
</div> <!-- /#main -->