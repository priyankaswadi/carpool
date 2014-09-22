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

<?php                                
    if (!isset($_POST['submit'])){
    $_SESSION["message"] = "";    
                }
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
				<li ><a href="search.php">Search for Carpool Partners</a></li>
				<li ><a href="create.php">Join Carpool</a></li>
				<li class="current_page_item"><a href="edit.php">Manage your info</a></li>				
				<li><a href="contact.php">Contact Us</a></li>
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
				<h2>Manage your info</h2>
				<span class="byline">Enter your email below</span>
			</div>
			<?php
   				 if (isset($_POST['cancel'])){
          		 		redirect_to("/carpool/search.php");
    				 }
    				 if (!isset($_POST['submit'])){
          		 		$empid = "";
    				 }
			?>	
		   <!--<h2>Enter your Email</h2>-->
   	   <form action="edit.php" method="post">
      	      <p>Email ID: <input type="email" name="email" value="" required autofocus/></p>
         	   <input type="submit" value="Enter" name="submit"/>&nbsp;&nbsp;
              <!-- <input type="submit" value="Cancel" name="cancel"/>--><!-- removed this as causing problem to html 5 validations-->
         </form>
         <?php 
			if (isset($_POST['submit'])){
   				  $email = mysql_prep($_POST['email']);
    			   // $result = find_employee_by_id($empid);
					  $result = find_employee_by_email($email);
    				  if($result){
        						$_SESSION["Email"] = $email;	
	    					//	redirect_to("edit_info.php");		
								if(send_edit_info($email))
										redirect_to("sentemail.php");		
								else		
										$_SESSION["message"] = "Message was not sent.. probably something wrong. Try again.";		
    					}
						else{
	   						$_SESSION["message"] = "Hmmm.. For some reason we can't find your Email ID: ".$email." Could you try once more? Or you may want to try contacting admin.";		
    					}				
			}	
			?>		

  			<?php
         	       echo "<br />".message();
            	    $_SESSION["message"] = "";
                
         ?>
        	<?php
					mysqli_close($connection);
    	  	?>		

		</div><!--this closing tag seems like end of div id =content-->
	</div><!--this closing tag seems like end of div id =container-->
</div><!--this closing tag seems like end of div id =wrapper-->
<div id="copyright" class="container">
	<p>&copy; Priyanka S. All rights reserved. | Photos by <a href="http://fotogrph.com/">Fotogrph</a> | Design by <a href="http://templated.co" rel="nofollow">TEMPLATED</a>.</p>
</div>
</body>
</html>
