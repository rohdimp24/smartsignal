<?php

set_time_limit(0);

require_once("login.php");


#open the file
$fp=fopen("collocation\\trainSS_5.csv","r");
$count=0;
while(($details = fgets($fp))!=null){

	$listValues=explode(",", $details);
	//if($count==0)
	//	continue;
	$count++;
	$sno=$listValues[0];
	$FirstEntity=trim($listValues[1],'"');
	$SecondEntity=trim($listValues[2],'"');
	$AB=intval(trim($listValues[3],'"'));
	$AnotB=intval(trim($listValues[4],'"'));
	$notAB=intval(trim($listValues[5],'"'));
	$notAnotB=intval(trim($listValues[6],'"'));
	$ChiScore=floatval(trim($listValues[7],'"'));
	$percent=floatval(trim($listValues[8],'"'));
	if($FirstEntity=="NA"||($FirstEntity==$SecondEntity)||$FirstEntity=="first")
		continue;

	else
	{
		if($ChiScore>4)
		{
			//echo $ChiScore;
			print_r($listValues);
			echo "<br/>";

			/*if((strpos($FirstEntity, "cnd_")>-1)||
				(strpos($SecondEntity, "cnd_")>-1))
			{
				$type="Condition";
			}
			else
			{
				$type="Asset";
			}*/
			$type="Asset";

			//inseert
			$query="INSERT INTO `associateentity` VALUES ('".$FirstEntity."','".$SecondEntity."','".$type."','".$AB."','".$AnotB."','".$notAB."','".$notAnotB."','".$ChiScore."','".$percent."')";
			echo $query."<br/>";
			mysql_query($query)	;

		}
	}


	//$count++;

	
}



?>