
<?php
	require("/phpmailer/class.phpmailer.php");
	$key_value = "xxx"; 
    function redirect_to($new_location){
        header("Location: ".$new_location);
        exit;
        
    }
    
    function mysql_prep($string){
        global $connection; 
        $escaped_staring = mysqli_real_escape_string($connection,$string);
        return $escaped_staring;
    }
    function confirm_query($result_set){
	   if(!$result_set)
	       die("Query failed");
    }
    
    function find_all_cities(){
        global $connection;
       	$query = "SELECT * FROM cities";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    
    function find_employee_by_id($empid){
        global $connection;
        $empid = mysqli_real_escape_string($connection,$empid);
        $query = "SELECT * FROM employees WHERE EmpID='{$empid}' LIMIT 1";
        $emp_set = mysqli_query($connection,$query);
        confirm_query($emp_set);
        if($emp = mysqli_fetch_assoc($emp_set))
            return $emp;
        else
            return null;    
        
        
    }
	
	function find_employee_by_email($email){
        global $connection;		
		$email = trim($email);
		$email = mysqli_real_escape_string($connection,$email);
		$query = "SELECT * FROM employees WHERE Email='{$email}' LIMIT 1";
        $emp_set = mysqli_query($connection,$query);
        confirm_query($emp_set);
        if($emp = mysqli_fetch_assoc($emp_set))
            return $emp;
        else
            return null;    
        
        
    }
    
    function old_find_carpool_match($startcity,$starttime,$endtime){
        global $connection;
       	$query = "SELECT * FROM employees WHERE StartCity='{$startcity}' AND StartTime='{$starttime}' AND EndTime='{$endtime}'";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        if($result)
            return $result;
        else
            return null;    
 
    }
    function find_carpool_match($lat1,$lon1){ //finds within 5 miles
		global $connection;
        $query = "SELECT *, 
				( 3959 * acos( cos( radians('$lat1') ) * 
				 cos( radians( Lat ) ) * 
				 cos( radians( Lng ) - 
				 radians('$lon1') ) + 
				 sin( radians('$lat1') ) * 
				 sin( radians( Lat ) ) ) ) 
				 AS distance FROM employees WHERE NeedCarpool=1 HAVING distance < 5 ORDER BY distance ASC LIMIT 0, 10";

		$result = mysqli_query($connection,$query);
        confirm_query($result);
        if($result)
            return $result;
        else
            return null;    
 
    }
    function find_all_starttimes(){
        global $connection;
       	$query = "SELECT * FROM starttimes";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    
    function find_all_endtimes(){
        global $connection;
       	$query = "SELECT * FROM endtimes";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
    
	function find_all_states(){
        global $connection;
       	$query = "SELECT * FROM states ORDER BY Code";
    	$result = mysqli_query($connection,$query);
        confirm_query($result);
        return $result;

    }
	
	/*********************************************************************************************************************************************/
	/*GOOGLEMAPS API CODE*/
    function get_geocode($address){		
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);		
		return $output;
		
	}
	
	function get_latitude($address){//the status OK means address was found anf a single result entry means the result was mapped uniquely, as otherwise gmaps provides a list of possible matches which is not what we want
		$output = get_geocode($address);
		if($output->status=="OK" && (count($output->results) == 1)){
			$latitude = $output->results[0]->geometry->location->lat;			
			return $latitude;			
		}
		else
			return null;
		
	}
	function get_longitude($address){//the status OK means address was found anf a single result entry means the result was mapped uniquely, as otherwise gmaps provides a list of possible matches which is not what we want
		$output = get_geocode($address);
		if($output->status=="OK" && (count($output->results) == 1)){
			$longitude = $output->results[0]->geometry->location->lng;			
			return $longitude;			
		}
		else
			return null;
		
	}
	/***********************************************************************************************************************************************/
	/*SENDING EMAIL*/
	function send_edit_info($email){
		if(sendemail($email))
			return true;
		else
			return false;	
	}	
    function sendemail($email){	
		//Insert code here to encrpyt email	
		$hash_email = encrypt_decrypt('encrypt', $email);
		$link = 'http://localhost/carpool/edit_info.php?id='.urlencode($hash_email);
		$mail = new PHPMailer;
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.mail.yahoo.com';  // Specify main and backup SMTP servers
		$mail->Port = 465; 
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'xxx';                 // SMTP username
		$mail->Password = 'xxx';                           // SMTP password
		$mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
		$mail->isHTML(true);

		$mail->From = 'xxx';
		$mail->FromName = 'Carpool Services';		
		$mail->addAddress($email);     // Add a recipient
		$mail->Subject = 'Modify your info';
		$mail->Body    = 'Thank you for using Carpool. Please use the link below to edit your information. <br />'.$link;		
		$mail->SMTPDebug = 1;
		if(!$mail->send()) {
				return false;
		} else {
				return true;
}
	}
	/*------------------------------------------------------------------------------------------------------------------------------*/
	/*ENCRYPTION AND DECRYPTION FUNCTIONS HERE*/
	function encrypt_decrypt($action, $string) {
		$output = false;
		$key = 'xxx';

		// initialization vector 
		$iv = md5(md5($key));

		if( $action == 'encrypt' ) {
				$output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
				$output = base64_encode($output);
		}
		else if( $action == 'decrypt' ){
				$output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
				$output = rtrim($output, "");
		}
		return $output;
}
	
    
?>