<?php
   include("/includes/sessions.php");  
   include("/includes/functions.php"); 
   require_once("/includes/dbconnection.php");
   //include("/includes/layouts/header.php"); 
?>
<?php
     // Get lat and long by address         
		
        $address1 = "50 Los Patos Way, Montecito CA 12309";		
        $prepAddr1 = str_replace(' ','+',$address1);
        $geocode1=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr1.'&sensor=false');
        $output1= json_decode($geocode1);		
		echo $geocode1;
		echo count($output1->results);
		if($output1->status=="OK"){
			$lat1 = $output1->results[0]->geometry->location->lat;
			$lon1 = $output1->results[0]->geometry->location->lng;		
			//echo $geocode1 ;
			echo "<br />";
			echo "Latitude: ". $lat1;
			echo "<br />";
			echo "Longitude: ".$lon1;
			echo "<br />";
		}
		else
			echo "Address not found";
		
		/*$address2 = "12306";		
        $prepAddr2 = str_replace(' ','+',$address2);
        $geocode2=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr2.'&sensor=false');
        $output2= json_decode($geocode2);		
		if($output2->status=="OK"){
			$lat2 = $output2->results[0]->geometry->location->lat;
			$lon2 = $output2->results[0]->geometry->location->lng;		
			echo $geocode2 ;
			echo "<br />";
			echo "Latitude: ". $lat2;
			echo "<br />";
			echo "Longitude: ".$lon2;
			echo "<br />";
		}
		else
			echo "Address not found";
		
		//$miles = distance($lat1,$lon1,$lat2,$lon2);
		//echo "Miles: ".$miles;*/

		$query = "SELECT *, 
				( 3959 * acos( cos( radians('$lat1') ) * 
				 cos( radians( Lat ) ) * 
				 cos( radians( Lng ) - 
				 radians('$lon1') ) + 
				 sin( radians('$lat1') ) * 
				 sin( radians( Lat ) ) ) ) 
				 AS distance FROM employees HAVING distance < 5 ORDER BY distance ASC LIMIT 0, 10";

		$result = mysqli_query($connection,$query);
		$count=mysqli_num_rows($result);
		echo $count."<br />";
	if ($count > 0){
		while ($row = mysqli_fetch_array($result)){
			$id = $row['EmpID'];
			$fname = $row['FName'];		
			echo $id." "."$fname<br />";

		}
	}
?>

<?php
function distance($lat1, $lon1, $lat2, $lon2) 
{ 
   $theta = $lon1 - $lon2; 
   $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
   $dist = acos($dist); 
   $dist = rad2deg($dist); 
   $miles = $dist * 60 * 1.1515;
   return $miles;
   
}