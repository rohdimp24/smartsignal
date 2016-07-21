<?php
require_once 'login.php';
require_once("AnalyzedCase.php");
require_once("dictionaryItem.php");

$DEBUG_PRINT=0;

function get_numerics ($str) {
    preg_match_all('/\d+/', $str, $matches);
    return $matches[0];
}


$fs=fopen("stopwords.txt","r");
$arrfinal=array();
while(($strfp = fgets($fs))!=null){
	
	$strfp=str_ireplace("\"", ' ', $strfp);
	$arrfinal=explode(",", $strfp);
}

function my_in_array($entry,$arr)
{
	
	$len=sizeof($arr);

	for($i=0;$i<$len;$i++) {
		$item=trim($arr[$i]);
		if($item==$entry)
		{
			return $item;
			//echo $item."<br/>";
		}
		//echo $item."<br/>";
	}
	return false;

}

/*echo $arrfinal[718];
$tt=in_array("psia",$arrfinal);
if($tt)
	echo "dd".$tt."<br/>";
else
	echo "sdkjsldjl";

my_in_array("psia",$arrfinal);
exit();
*/
////////////////////////////////////
$fp=fopen("units.txt","w");
#$count=0;
$fetchQuery="Select originalDescription from crmcleanupnew";
$fetchResult=mysql_query($fetchQuery);
$fetchRowsNum=mysql_num_rows($fetchResult);

#echo $fetchRowsNum;

for($j=1;$j<=$fetchRowsNum;$j++)
{
	$fetchRow=mysql_fetch_row($fetchResult);
	$details=$fetchRow[0];
	if($j<4000)
		continue;
	if($j>5000)
	 	break;
	else
	{
			
		if(strlen($details)<5)
			continue;
		else
		{

			echo $j."=>"."<b>Original=></b>".$details."<br/>";
			$originalDescription=$details;
			//this will remove any text which has a digit and alhpabets
			$details=preg_replace('/[^A-Za-z0-9 _\-\+\&\,\#]/','', $details);
			$details=trim($details);
			$details=trim($details,'"');
			$details=str_ireplace("\"", ' ', $details);
		    $details=str_ireplace(">", ' ', $details);
		    $details=str_ireplace("@", ' ', $details);
		    $details=str_ireplace("<", ' ', $details);
		    $details=str_ireplace(":", ' ', $details);
		    $details=str_ireplace(".", ' ', $details);
		    $details=str_ireplace("[", ' ', $details);
		    $details=str_ireplace("]", '', $details);
		    $details=str_ireplace("_", ' ', $details);
		    $details=str_ireplace(",", ' ', $details);
		    $details=str_ireplace("#", ' ', $details);
		    $details=str_ireplace("-", ' ', $details);
		    $details=str_ireplace("+", ' ', $details);
		    $details=str_ireplace("*", ' ', $details);
		    $details=str_ireplace("/", ' ', $details);
		    #$details = preg_replace('/[0-9]+/', ' ', $details);

		    echo "<b>Cleanup (after removing puctuations)=></b>".$details."<br/>";		   
		    $digitIndexes=get_numerics($details);
		    //print_r($digitIndexes);
			//echo "<br/>";

			for($i=0;$i<sizeof($digitIndexes);$i++)
			{
				$pos=strripos($details, $digitIndexes[$i]);
				#echo "pos=>".$pos;
				$ss= substr($details, $pos,10);
				$arrSS=explode(" ",$ss);
				if(sizeof($arrSS)>1)
				{
					$testunit=strtolower(trim($arrSS[1]));

					//print_r($arrfinal);
					//exit();
					echo "test unit=>".$testunit."<br/>";
					$retword=my_in_array($testunit,$arrfinal);
					echo "return word=>".$retword."..length=>".strlen($retword)."<br/>";
					if(strlen($testunit)>0)
					{
						if(!$retword)
						{
							$units=$arrSS[0]."=>".strtolower($arrSS[1]);
							echo $units."<br/>";
							fprintf($fp, $units);
							fprintf($fp, "\n");
						}
					}
					
				}

				#print_r($arrSS);
				
			}



		}
		
	}

}

fclose($fp);
?>