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
<?php
                echo message();
                $_SESSION["message"] = "";
                if (!isset($_POST['submit'])){ // old form values are retained in case of errors. But if form
                                               //displayed for first time, these values are set to default 
					$empid="";
                    $startzip="";
                    $fname="";
                    $lname="";
                    $phone="";
                    $email="";
                    $address="";
					$startcity="";
                }
				else{	//this is needed again to retain old values after submit is clicked and list of matching partners shown
					$address = mysql_prep($_POST['address']);
				    $startcity = mysql_prep($_POST['startcity']);
					$startzip = mysql_prep($_POST['startzip']);
					$state = mysql_prep($_POST['state']);
		
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
				<li class="current_page_item"><a href="#">Search for Carpool Partners</a></li>
				<li><a href="create.php">Join Carpool</a></li>
				<li><a href="edit.php">Manage your info</a></li>				
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
				<h2>Find your Carpool Partner</h2>
				<span class="byline">Search employees within your company</span>
			</div>
			<form action="search.php" method="post" id="search">        
				<p><label for="address">Address: </label><input type="text" name="address" value="<?php echo $address?>" required autofocus/></p>			
				<p><label for="startcity">Start City: </label><input type="text" name="startcity" value="<?php echo $startcity?>" required/>             </p>            
				<p><label for="state">State: </label><select name="state">
            	<?php
                               $states_set  = find_all_states();
                                while($states = mysqli_fetch_array($states_set)){
                                        if ($state!="" && $state==$states['Code'] ){
                                            echo "<option value='" . $states['Code'] . "' selected=\"\">" . $states['Name'] . "</option>";
                                        }
                                        else
                                            echo "<option value='" . $states['Code'] . "'>" . $states['Name'] . "</option>";
                                                                            
                                }
    
            	?>            
                        
            	</select>
				</p>
				<p> <label for="startzip">Start Zip: </label><input type="text" name="startzip" value="<?php echo $startzip?>" required pattern="^[0-9]{5}$" title="Zip code should be 5-digit"/></p>			            
				<input type="submit" value="Enter" name="submit"/>
			</form>
			<?php 
			if (isset($_POST['submit'])){
					$no_error = true;
					$address = mysql_prep($_POST['address']);
					$startcity = mysql_prep($_POST['startcity']);
					$startzip = mysql_prep($_POST['startzip']);
					$state = mysql_prep($_POST['state']);
					if(!has_presence($startzip)){
            			echo "<br />Please enter Start Zip<br />";        
            			$no_error = false;
        			}  
					elseif(!preg_match("#[0-9]{5}#", $startzip)|| !has_max_length($startzip, 5)){
              			echo "<br />Zip Code should be a 5-digit number<br />";        
            			$no_error = false;
        			}
					if(!has_presence($address)){
            			echo "<br />Please enter Address<br />";
            			$no_error = false;
        			} 
					if(!has_presence($startcity)){
            			echo "<br />Please enter City<br />";
            			$no_error = false;
        			}
					if($no_error){
							$lat1 = get_latitude($address." ".$startcity." ".$state." ".$startzip);
							$lng1 = get_longitude($address." ".$startcity." ".$state." ".$startzip);								
							//echo $lat1,$lng1;
							if($lat1=="" || $lng1==""){ //if for some reason the address is not found, instead of throwing an error just consider zip 
									$lat1 = get_latitude($startzip);
									$lng1 = get_longitude($startzip);	
									//				echo "Address could not be found. Please check the spelling. Or try entering again.";
							}
						
							$result = find_carpool_match($lat1,$lng1);
							$count=mysqli_num_rows($result);
							if ($count > 0){    
									echo "<br />Here's a list of employees within 5 miles of your location<br />";
									echo "<table border=\"1\">";
									echo "<tr>";
									//echo "<th>Employee ID</th>";
									echo "<th>Name</th>";
									echo "<th>Address</th>";
									echo "<th>Phone</th>";
									echo "<th>Email</th>";
									echo "<th>Timings</th>";
									echo "</tr>";
									while($emp = mysqli_fetch_array($result)){
											echo "<tr>";
										//	echo "<td>".$emp['EmpID']."</td>";  
											echo "<td>".$emp['FName']." ".$emp['LName']."</td>";  
											echo "<td>".$emp['Address'].", <br /> ".$emp['StartCity']." ".$emp['State']." ".$emp['StartZip']."</td>";  
											echo "<td>".$emp['Phone']."</td>";  
											echo "<td>".$emp['Email']."</td>";  
											echo "<td>Usually starts around ".$emp['StartTime']."<br />and leaves office around ".$emp['EndTime']."</td>";  
											echo "</tr>";
									}
					
									echo "</table>";
							}
							else
									echo "No employees found within 5 miles";
					
					}
			}
			?>

			<?php
					mysqli_close($connection);
			?>

		</div><!--this closing tag seems like end of div id =content-->
		<div id="sidebar">
			<div class="box2">
				<div class="title">
					<h2>New Here?</h2>
					<span class="byline">Few tips!</span>					
				</div>
				<p>
					This is a simple carpooling app that allows you to search for and get in touch with interested carpoolers within your company. On this page you may search for people in your company who are looking for carpool partners. You can contact them if you like.
					If you would like others to find you, please register using the <a href="create.php">Join Carpool</a> link from the menu. Once you have joined you can change your information by going to <a href="create.php">Manage Your Info</a>. If you have any other questions please <a href="contact.php">Contact Us</a>.
					
				</ul></p>
				
			</div>
		</div>
	</div><!--this closing tag seems like end of div id =container-->
</div><!--this closing tag seems like end of div id =wrapper-->
<div id="copyright" class="container">
	<p>&copy; Priyanka Swadi. All rights reserved. | Photos by <a href="http://fotogrph.com/">Fotogrph</a> | Design by <a href="http://templated.co" rel="nofollow">TEMPLATED</a>.</p>
</div>
</body>
</html>
