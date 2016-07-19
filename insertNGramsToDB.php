<?php
require_once 'login.php';
$fp=fopen("dictionary.txt","r");
$count=0;

while(($details = fgets($fp))!=null){

	$keyValue=explode("=>", $details);
	$listValues=explode(" ",$keyValue[0]);
	if(sizeof($listValues)>1)
	{
		echo strtolower($keyValue[0])."<br/>";

	
		$ngram=strtolower($keyValue[0]);
	
	
		$incident=trim($listValues[0],'"');
		$query="INSERT INTO `ngrams` (`ngram`) VALUES ('".$ngram."')";
		//echo $query."<br/>";
		$result=mysql_query($query);	
		echo $result;
		
	}

}

fclose($fp);
?>