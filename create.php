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
				<li class="current_page_item"><a href="create.php">Join Carpool</a></li>
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
				<h2>Join Carpool</h2>
				<span class="byline">Let others in your company find you too</span>
			</div>
			<?php 
			if (isset($_POST['submit'])){
    
			   	$empid = mysql_prep($_POST['empid']);
					$startcity = mysql_prep($_POST['startcity']);
					$startzip = mysql_prep($_POST['startzip']);
					$state = mysql_prep($_POST['state']);
    				$starttime = mysql_prep($_POST['starttime']);
    				$endtime = mysql_prep($_POST['endtime']);
				   $fname = mysql_prep($_POST['fname']);   
    				$lname = mysql_prep($_POST['lname']);
    				$address = mysql_prep($_POST['address']);     
    				$phone =  mysql_prep($_POST['phone']);
    				$email = mysql_prep($_POST['email']);
    				$no_error = true;
    				if(find_employee_by_email($email)){
        						echo "Your Email ID already exists in our system. You may use the Manage your info link in the menu if you wish to change anything.<br /><br />";        
        						$no_error = false;
    				}
    				else{    
        						if(!has_presence($empid)){
            							echo "<br />Please enter Employee ID<br />";        
            							$no_error = false;
        						}
        						if(!has_presence($startzip)){
            							echo "<br />Please enter Start Zip<br />";        
            							$no_error = false;
        						}  
        						elseif(!preg_match("#[0-9]{5}#", $startzip)|| !has_max_length($startzip, 5)){
              							echo "<br />Zip Code should be a 5-digit number<br />";        
            							$no_error = false;
        						}
        						if(!has_presence($fname)){
            							echo "<br />Please enter First Name<br />";
            							$no_error =false;
        						}  
        						if(!has_presence($lname)){
            							echo "<br />Please enter Last Name<br />";
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
        						if(!has_presence($phone)){
            							echo "<br />Please enter Phone<br />";
            							$no_error = false;
        						}			
        						elseif(!preg_match("#[0-9]{10}#", $phone)|| !has_max_length($phone, 10) ){
            							echo "<br />Phone number should be a 10 digit number only. Please do not enter - or . in the field<br />";        
            							$no_error = false;
        						}      
        						if(!has_presence($email)){
            							echo "<br />Please enter Email<br />";
            							$no_error = false;
        						}
        						elseif(!validateEMAIL($email)){
            							echo "<br />Please enter Email in valid format.<br />";
            							$no_error = false;
        						}			
        						if($no_error){  
											$lat = get_latitude($address." ".$startcity." ".$state." ".$startzip);
											$lng = get_longitude($address." ".$startcity." ".$state." ".$startzip);			
											echo $lat,$lng;
											if($lat=="" || $lng==""){ //if for some reason the address is not found, instead of throwing an error just consider zip 
														$lat = get_latitude($startzip);
														$lng = get_longitude($startzip);	
											}
	        								$query = "INSERT INTO employees (EmpID,StartCity,State,StartZip,StartTime,EndTime,FName,LName,Address,Phone,Email,NeedCarpool,Lat,Lng)
    		  											 VALUES ('{$empid}','{$startcity}','{$state}','{$startzip}','{$starttime}','{$endtime}','{$fname}','{$lname}','{$address}','{$phone}','{$email}',1,'{$lat}','{$lng}')";
	        								$result = mysqli_query($connection,$query);
	        								if($result){
	               							//	$_SESSION["message"] = "Congrats you have now joined the carpool";
	               								 redirect_to("/carpool/create_success.php");	
            							}
	        								else{
	               								$_SESSION["message"] = "Could not enter your information, please contact admin.";		
            							}			
        						}			
    					}			
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
                  
         ?>
            
         <form action="create.php" method="post" id="create">
			<p><label>Email ID: </label><input type="email" name="email" value="<?php echo $email?>" required/></p>
         	<p><label>Employee ID: </label><input type="text" name="empid" value="<?php echo $empid?>" required/></p>
            <p><label>First Name: </label><input type="text" name="fname" value="<?php echo $fname?>" required/></p>
            <p><label>Last Name: </label><input type="text" name="lname" value="<?php echo $lname?>" required/></p>
            <p><label>Address: </label><input type="text" name="address" value="<?php echo $address?>" required/></p>            
            <p><label>City: </label><input type="text" name="startcity" value="<?php echo $startcity?>" required/> </p>            
			<p><label>State </label><select name="state">
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
            <p><label>Zip: </label><input type="text" name="startzip" value="<?php echo $startzip?>" required pattern="^[0-9]{5}$" title="Zip code should be 5-digit"/></p>
            <p><label>Phone </label><input type="text" name="phone" value="<?php echo $phone?>" required pattern="^[0-9]{10}$" title="Phone should be 10-digit"/></p>            
            <p><label>When do you leave home?</label><select name="starttime">
            		<?php
                         $times_set  = find_all_starttimes();
                         while($times = mysqli_fetch_array($times_set)){
                                if ($starttime!="" && $starttime==$times['value_time'] ){
                                      echo "<option value='" . $times['value_time'] . "' selected=\"\">" . $times['value_time'] . "</option>";
                                }
                                else
                                      echo "<option value='" . $times['value_time'] . "'>" . $times['value_time'] . "</option>";
                                                                            
                         }
    
            		?>     
            </select>
            </p><br />
            <p><label>When do you leave office?</label><select name="endtime">
		            <?php
                         $times_set  = find_all_endtimes();
                         while($times = mysqli_fetch_array($times_set)){
                                if ($endtime!="" && $endtime==$times['value_time'] ){
                                        echo "<option value='" . $times['value_time'] . "' selected=\"\">" . $times['value_time'] . "</option>";
                                }
                                else
                                        echo "<option value='" . $times['value_time'] . "'>" . $times['value_time'] . "</option>";
                                                                        
                         }
            		?>            
                        
            </select>
            </p>       <br />    
            <input type="submit" value="Enter" name="submit"/>
            </form>
            <br />         

         <?php
  
					mysqli_close($connection);
    		?>


		</div><!--this closing tag seems like end of div id =content-->
		<div id="sidebar">
			<div class="box2">
				<div class="title">
					<h2>Rest Assured, this is safe</h2>					
				</div>
				<p>We need to use Google Maps to make this application work! But your company's privacy and security is important. None of the information you share here (except your address) leaves your company. Fill the form here and let others find you too.</p>
				
			</div>
		</div>
	</div><!--this closing tag seems like end of div id =container-->
</div><!--this closing tag seems like end of div id =wrapper-->
<div id="copyright" class="container">
	<p>&copy; Priyanka S. All rights reserved. | Photos by <a href="http://fotogrph.com/">Fotogrph</a> | Design by <a href="http://templated.co" rel="nofollow">TEMPLATED</a>.</p>
</div>
</body>
</html>
