<?php
require_once 'login.php';


$fp=fopen("crmcases.txt","r");
$count=0;

while(($details = fgets($fp))!=null){
	$count=$count+1;
	$details=trim($details);
	if(strlen($details)<5)
		continue;
	$query="INSERT INTO `crm` (`description`) VALUES ('".$details."')";
	echo $query."<br/>";
	$result=mysql_query($query);	
	//echo $result;
		
	
}

fclose($fp);
?>