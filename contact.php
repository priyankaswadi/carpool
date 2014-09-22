<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by TEMPLATED
http://templated.co
Released for free under the Creative Commons Attribution License

Name       : GrassyGreen 
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20140310

-->
<?php
   include("/includes/sessions.php");  
   include("/includes/functions.php"); 
   require_once("/includes/dbconnection.php");
   //include("/includes/layouts/header.php"); 
   include("/includes/validation_functions.php"); 
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Carpool</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="http://fonts.googleapis.com/css?family=Raleway:400,200,500,600,700,800,300" rel="stylesheet" />
<link href="stylesheets/default.css" rel="stylesheet" type="text/css" media="all" />
<link href="fonts.css" rel="stylesheet" type="text/css" media="all" />
<!--[if IE 6]>
<link href="default_ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
</head>
<body>
<div id="wrapper">
	<div id="menu-wrapper">
		<div id="menu" class="container">
			<ul>
				<li><a href="search.php">Search for Carpool Partners</a></li>
				<li><a href="create.php">Join Carpool</a></li>
				<li><a href="edit.php">Manage your info</a></li>				
				<li class="current_page_item"><a href="#">Contact Us</a></li>
			</ul>
		</div>
		<!-- end #menu --> 
	   <div id="header-wrapper">
			<div id="header" class="container">
				<div id="logo">
					<h1><a href="#">Carpool</a></h1>				
				</div>
			</div>	
		</div>
	</div>	
	<div id="page" class="container">
		<div id="content">
			<div class="title">
				<h2>Contact Info</h2>
				<span class="byline"></span>
			</div>
				 This is a demo of how this tool can be used by your employees to carpool.<br />
				 Your system may need to meet some requirments to get this working internally within your company.<br /><br />
				 If you want to use this tool, Please call xxx-xxx-xxxx or email to xxx@xxx.com. Thank you!
		</div><!--this closing tag seems like end of div id =content-->
	</div><!--this closing tag seems like end of div id =container-->
</div><!--this closing tag seems like end of div id =wrapper-->
<div id="copyright" class="container">
	<p>&copy; Priyanka S. All rights reserved. | Photos by <a href="http://fotogrph.com/">Fotogrph</a> | Design by <a href="http://templated.co" rel="nofollow">TEMPLATED</a>.</p>
</div>
</body>
</html>
