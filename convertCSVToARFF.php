<?php

#read the CSV file
$fc=fopen("forJava.csv","r");
$fp=fopen("forARFF.arff","w");
$arrClusterMap=array();


$arrTerms=explode(",",fgets($fc));
#print_r($arrTerms);

$len=sizeof($arrTerms);

$str='';
fwrite($fp,"@relation weather \n\n");
for($i=0;$i<$len;$i++)
{
	
	$str="@attribute ".$arrTerms[$i]." real"."\n";
	fwrite($fp,$str);

}
fwrite($fp,"\n");


while(($details = fgets($fc))!=null){

	fwrite($fp,$details);

}

fclose($fp);
fclose($fc);





?>