<?php

set_time_limit(0);
// based on this we need to find out the tokens in the text. all else is noise
require_once("SimilarDocument.php");
require_once("login.php");



#$fp=fopen("cosineSimilarityMatrix.csv","r");
$fp=fopen("cosineMat13.csv","r");

$arrfinal=array();
$count=0;

/**

create map of the sno and the actual casenumber as in the R code we are starting from 1 to n while as per
tokenisedCase table there are some cases which are missing

**/
$arrMap=array();
/*$queryMap="Select sno from crmcleanup";
$resultMap=mysql_query($queryMap);
$rownumMap=mysql_num_rows($resultMap);
for($i=0;$i<$rownumMap;$i++)
{
	$key=$i+1;
	$row=mysql_fetch_row($resultMap);
	$value=$row[0];
	$arrMap[$key]=$value;
}

print_r($arrMap);
exit();
*/
//create a map of clusters
$fc=fopen("clustersFinalNew.txt","r");
$arrClusterMap=array();
while(($clusterDetail=fgets($fc))!=null)
{
	//echo $clusterDetail;
	list($docNum,$clusterNum)=explode(":",$clusterDetail);
	$arrClusterMap[$docNum]=$clusterNum;
}

//print_r($arrClusterMap);

while(($details = fgets($fp))!=null){
	if($count==0)
	{
		$count++;
		continue;
	}
	else
	{
		
		$arrTemp=explode(",",$details);
		//print_r($arrTemp);
		#$lenArr=sizeof($arrTemp);
		#$doc=$count;
		$clusterId=$arrClusterMap[$arrTemp[1]];
		//echo $count;
		#$str='';
		#$arrSimilarDocs=array();
		/*for($i=1;$i<$lenArr;$i++)
		{
			//echo "Doc#".$i." =>";
			#if($arrTemp[$i]<60)
			#{
				//$score=number_format((float) $arrTemp[$i], 2, '.', '');
				$score=round((float) $arrTemp[$i],2);
				$obj=new SimilarDocument($i,$score);
				//$obj=new SimilarDocument($i,$score);
				$str.="(".$i.":".$score."),";
				//$str.="(".$i.":".$score."),";
				array_push($arrSimilarDocs, $obj);
			#}
			
		}*/
		$score=round((float) $arrTemp[2],2);
		#$obj=new SimilarDocument($arrTemp[1],$score);
		#array_push($arrSimilarDocs, $obj);

		//echo $arrMap[$doc]."=>".$str."<br/>";
		
		#echo $doc."=>".json_encode($arrSimilarDocs)."=>".$clusterId."<br/>";
		echo $arrTemp[0]."=>".$arrTemp[1]."=>".$score."=>".$clusterId."<br/>";
		//echo $doc."=>".$str."<br/>";
		//echo $doc."=>".json_encode($arrSimilarDocs)."<br/>";
		//print_r($arrTemp);

		$query="INSERT INTO `casesimilarity` VALUES ('".$arrTemp[0]."','".$arrTemp[1]."','".
		$score."','".str_ireplace("\"", '', $arrTemp[3])."','".$clusterId."')";
		$result=mysql_query($query);
		if(!$result)
			echo $query;
		$count++;
		//if($count>100)
		//	break;
		
	}

}






/*
#print_r($arrfinal);

$db_hostname='localhost';
$db_database='upton';
$db_username='root';
$db_password='';


$db_server = mysql_connect($db_hostname, $db_username, $db_password);
if (!$db_server) die("Unable to connect to MYSQL: " . mysql_error());

mysql_select_db($db_database)
or die("Unable to select database: " . mysql_error());

*/

?>