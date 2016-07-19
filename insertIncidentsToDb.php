<?php
require_once 'login.php';


$fp=fopen("extracted_incident.incident.csv","r");
$count=0;

while(($details = fgets($fp))!=null){

	$listValues=explode(",", $details);
	$count=$count+1;
	if($count==1)
		continue;
	else
	{
		$incident=trim($listValues[0],'"');
		$query="INSERT INTO `incidents` (`incidentName`) VALUES ('".$incident."')";
		//echo $query."<br/>";
		$result=mysql_query($query);	
		echo $result;
		
	}
}

fclose($fp);
?>