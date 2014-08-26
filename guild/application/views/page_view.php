<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<meta name="robots" content="noindex">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style type="text/css">
#logout_button {
	position:relative;
	float:right;
	right:6px;
	top:6px;
}

</style>

<link rel="shortcut icon" href="/sites/all/themes/yale_centered/favicon.ico" type="image/x-icon" />
  
<title><?=$title ?></title>
<a href="./">
<?=$header ?>
</a>
</head>




<body class="front logged-in page-node node-type-page one-sidebar sidebar-left">
  <div id="page"> 
  
	<div id="logout_button"><a href="<?php echo base_url()."index.php/home/logout"?>">Logout</a></div>
  
	<div id="header" class="clearfix">
	<div id="header-title"></div>
	<div id="header-navigation">
		<div id="secondary" class="clearfix">
			<ul class="links secondary-links">
				<li class="menu-513 active-trail first active"><a href="<?=base_url()?>index.php/home" title="" class="active">Home</a></li>
				<li class="menu-473"><a href="<?=base_url()?>index.php/about" title="">About</a></li>
				<li class="menu-473"><a href="<?=base_url()?>index.php/library" title="">Library</a></li>
				<li class="menu-476"><a href="<?=base_url()?>index.php/guildies" title="">Guildies</a></li>
				<li class="menu-476"><a href="<?=base_url()?>index.php/news" title="">News</a></li>
				<li class="menu-476"><a href="<?=base_url()?>index.php/schedule" title="">Ring Schedule</a></li>
				<li class="menu-474"><a href="<?=base_url()?>index.php/heelmanager" title="">Heel Manager</a></li>
				<li class="menu-474"><a href="<?=base_url()?>index.php/settings" title="">Settings</a></li>
				<li class="menu-475"><a href="<?=base_url()?>index.php/recordings" title="">Recordings</a></li> <!---->
			</ul>
		</div>
    </div> <!-- /header-navigation -->
 
    </div> <!-- /header -->

    <div id="container">
      <div id="container-inner">
        
          
        
        <!--
        <div id="sidebar-left" class="column sidebar">
	       	<div id="sidebar-left-inner">
	       	          
	       	                      
	       	            
	       	      <a class="context-block-region" id="context-block-region-left">Left sidebar</a>
	       	      <div id="block-menu-yale-editorial-controls" class="block block-menu">
	       	 	 	<h4 class="block-title">My tools</h4>
	       	
		       	  	<div class="content clearfix">
		       	  		Hello
		       	  	</div>
	       		</div>
	       	</div> 
       	</div>
       	-->
       	<!-- /sidebar-left-inner --><!-- /sidebar-left -->
       	
       <div id="content-photo">
       
         	<?=$content ?>
        </div> <!-- /content-photo -->
      
      </div> <!-- /container-inner -->
    </div> <!-- /container -->

    <div id="footer" class="clearfix">
      <div id="footer-inner">
        <div id="footer-logo">
        </div> <!-- /#footer-logo -->
        <div id="footer-message">
          <p>Copyright &copy; 2012 Yale University. All rights reserved. 
          <span class="footer-links">
          <a title="Yale Privacy policy" href="http://www.yale.edu/privacy">Privacy policy</a> | 
          <a title="Login via Central Authentication Service" onclick="go('logout')">Logout</a></span><br />
         Content may not have been approved by or reflect the views of Yale University.
          </p>
                  </div> <!-- /#footer-message -->
      </div> <!-- /#footer-inner -->
    </div> <!-- /#footer -->

    </div>
   </body>
</html>
