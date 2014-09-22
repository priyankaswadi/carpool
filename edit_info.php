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

    if (isset($_POST['cancel'])){
          redirect_to("/carpool/search.php");
                    
    }
?>
<?php if (isset($_POST['submit'])){
    
    $empid = mysql_prep($_SESSION['EmpID']);
	$startcity = mysql_prep($_POST['startcity']);
	$state = mysql_prep($_POST['state']);
	$startzip = mysql_prep($_POST['startzip']);
    $starttime = mysql_prep($_POST['starttime']);
    $endtime = mysql_prep($_POST['endtime']);
    $fname = mysql_prep($_POST['fname']);   
    $lname = mysql_prep($_POST['lname']);
    $address = mysql_prep($_POST['address']);     
    $phone =     mysql_prep($_POST['phone']);
    $email = mysql_prep($_POST['email']);
    $needcarpool = $_POST['needcarpool'];
    $no_error = true;
    if(!has_presence($startzip)){
        echo "<br />Please enter Start Zip<br />";        
        $no_error = false;
    }  
    elseif(!preg_match("#[0-9]{5}#", $startzip)  || !has_max_length($startzip, 5)){
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
    elseif(!preg_match("#[0-9]{10}#", $phone) || !has_max_length($phone, 10) ){
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
		//echo $lat,$lng;
		if($lat=="" || $lng==""){ //if for some reason the address is not found, instead of throwing an error just consider zip
			$lat = get_latitude($startzip);
			$lng = get_longitude($startzip);	
		}
	        
        $query = "UPDATE employees SET StartCity = '{$startcity}',
								  State = '{$state}'	,
                                  StartZip = '{$startzip}',
                                  StartTime ='{$starttime}',
                                  EndTime ='{$endtime}',
                                  FName ='{$fname}',
                                  LName = '{$lname}',
                                  Address = '{$address}',
                                  Phone='{$phone}',                                  
                                  NeedCarpool = {$needcarpool},
								  Lat = '{$lat}',
								  Lng = '{$lng}'
                                  WHERE Email = '{$email}' LIMIT 1 ";
	   $result = mysqli_query($connection,$query);
	   //if($result && mysqli_affected_rows($connection)==1){//fail msg is displayed if user doesn't change anything. hence only checking for result
	     if($result){
//	        $_SESSION["message"] = "Page edited";
 			  redirect_to("/carpool/edit_success.php");

		
       }
	   else{
	   		
            $_SESSION["message"] = "Page edit failed. You may want to try again or contact your admin";//not necessary to put in session here, as message variable is available on this page
            $fname = mysql_prep($_POST['fname']);   
            $lname = mysql_prep($_POST['lname']);
            $address = mysql_prep($_POST['address']);    
            $phone =     mysql_prep($_POST['phone']);
            $email = mysql_prep($_POST['email']);
            $startzip = mysql_prep($_POST['startzip']); 
            $startcity = mysql_prep($_POST['startcity']);
	   		$state = mysql_prep($_POST['state']);
            $starttime = mysql_prep($_POST['starttime']);
            $endtime = mysql_prep($_POST['endtime']);
            $needcarpool = $_POST['needcarpool'];   
          		
        }
    }
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
<script type="text/javascript">
	function hideTextBox(){		
		//alert('hideTextBox');
		remove();
		
	}
	
	function showTextBox(){		
		//alert('showTextBox');
		add();	
		
	}
	
	function add() { 
		displaypara();
		var element = document.createElement("input");
		element.setAttribute("type", "text");
		element.setAttribute("value", "");
		element.setAttribute("name", "reasontext"); 
		var txt = document.getElementById("textboxcontainer");     
		txt.appendChild(element);
 
}

function remove(){
	    var txt = document.getElementById("textboxcontainer");
		var element = document.getElementsByName("reasontext");
		//alert(element[0]);
		txt.removeChild(element[0]);
		var ptag = document.getElementById("one");
		//alert(ptag);
		txt.removeChild(ptag);
}

function displaypara() {
    var comment = "Would you like to tell us why?";
    var newParagraph = document.createElement('p');
	newParagraph.setAttribute("id","one");
    newParagraph.textContent = comment;
    document.getElementById("textboxcontainer").appendChild(newParagraph);
}
	
</script>

</head>

<body>
<div id="wrapper">
	<div id="menu-wrapper">
		<div id="menu" class="container">
			<ul>
				<li><a href="search.php">Search for Carpool Partners</a></li>
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
				<h2>Change your info</h2>
				<span class="byline">Change it</span>
			</div>
			<div>
         <?php
                echo message();
                $_SESSION["message"] = "";
                //$empid = $_SESSION["EmpID"];
			//	$email = $_SESSION["Email"];
                //$employee = find_employee_by_id($empid);
				//Insert code here to decrypt email
				$hash_email = $_GET["id"];
				//echo $hash_email."<br />";
				$email = encrypt_decrypt('decrypt', $hash_email);											
				$employee = find_employee_by_email($email);
				if (!isset($_POST['submit'])){
					$empid = $employee['EmpID'];
                    $fname =$employee['FName'];
                    $lname=$employee['LName'];
                    $address= $employee['Address'];    
                    $startzip=$employee["StartZip"];          
	   		        $state =$employee["State"];
                    $phone=$employee["Phone"];
                    $email =$employee["Email"];
                    $startcity = $employee["StartCity"];
                    $starttime=$employee["StartTime"];
                    $endtime=$employee["EndTime"];
                    $needcarpool=$employee['NeedCarpool'];                    
                }
                
                
         ?>
         <h2>Please edit your info</h2>
         <form action="edit_info.php" method="post">
			<p><label>Email ID: </label><input type="email" name="email" value="<?php echo $email?>" required readonly="true"/></p>
            <p><label>Employee ID: </label><input type="text" name="empid" value="<?php echo $empid?>" readonly="true"/></p>
            <p><label>First Name: </label><input type="text" name="fname" value="<?php echo $fname?>" required/></p>
            <p><label>Last Name: </label><input type="text" name="lname" value="<?php echo $lname?>" required/></p>
            <p><label>Address: </label><input type="text" name="address" value="<?php echo $address?>" required/></p>            
            <p><label>City: </label><input type="text" name="startcity" value="<?php echo $startcity?>" required/> </p>            			
			<p><label>State: </label><select name="state">
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
			<p><label>Start Zip: </label><input type="text" name="startzip" value="<?php echo $startzip?>" required pattern="^[0-9]{5}$" title="Zip code should be 5-digit"/></p>
            <p><label>Phone: </label><input type="text" name="phone" value="<?php echo $phone?>" required pattern="^[0-9]{10}$" title="Phone should be 10-digit"/></p>
            <p><label>Start Time: </label><select name="starttime">
            <?php
                      $times_set  = find_all_starttimes();
                      while($times = mysqli_fetch_array($times_set)){
                             if ($starttime==$times['value_time'] ){
                                    echo "<option value='" . $times['value_time'] . "' selected=\"\">" . $times['value_time'] . "</option>";
                             }
                             else
                                    echo "<option value='" . $times['value_time'] . "'>" . $times['value_time'] . "</option>";
                     }
            ?>        
            </select>
            </p>
            <p><label>End Time: </label><select name="endtime">
             <?php
                       $times_set  = find_all_endtimes();
                       while($times = mysqli_fetch_array($times_set)){
                              if ($endtime==$times['value_time'] ){
                                     echo "<option value='" . $times['value_time'] . "' selected=\"\">" . $times['value_time'] . "</option>";
                              }
                              else
                                     echo "<option value='" . $times['value_time'] . "'>" . $times['value_time'] . "</option>";
                                                                        
                                }
    
            ?>            
                        
           </select></p>
           <p>Do you still need carpool?
                  <input type="radio" onchange="showTextBox();" name="needcarpool" value="0" <?php if($needcarpool==0){echo "checked";}?>/>No
                  <input type="radio" onchange="hideTextBox();" name="needcarpool" value="1" <?php if($needcarpool==1){echo "checked";}?>/>Yes
           </p>		   
		   <div id="textboxcontainer">
				   
		   </div>
            <input type="submit" value="Enter" name="submit"/>&nbsp;&nbsp;
        <!--    <input type="submit" value="Cancel" name="cancel"/>--> <!-- removed this as causing problem to html 5 validations-->
            </form>
            <br />                                           

</div>
	
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
