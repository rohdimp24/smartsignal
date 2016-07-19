<?php
require_once 'login.php';
require_once("AnalyzedCase.php");
require_once("dictionaryItem.php");
//read the list of the keywords
$fs=fopen("unigram_based_dictionary.txt","r");
$arrfinal=array();
while(($strfp = fgets($fs))!=null){
	if(!strlen(trim($strfp))==0)
	{
		list($variant,$canonical)=explode("=>",$strfp);

		$obj=new dictionaryItem(strtolower($variant),strtolower($canonical));
		array_push($arrfinal,$obj);
	}
}

//print_r($arrfinal);

//exit();

function my_in_array($entry,$arr)
{
	/*$len=sizeof($arr);

	foreach ($arr as $item) {
		if($item==$entry)
			return $item;
	}
	return null;
	*/
	$len=sizeof($arr);

	foreach ($arr as $item) {
		if($item->variant==$entry)
			return trim($item->canonical);
	}
	return null;

}

function ContainsNumbers($String){
	$tt=preg_match('/\\d/', $String) > 0;
	if($tt)
		echo "number found..";
	else
		echo "no number found..";
	return $tt;
}



////////////////////////////////////
$fp=fopen("crmcases.txt","r");
$count=0;

while(($details = fgets($fp))!=null){
	$count=$count+1;
	//if($count<113)
	 //	continue;
	if($count>11000)
	 	break;
	else
	{
		//$details="Bearing Metal Temp - Generator B has been decreasing and flatlining to 0degF before returning to the estimate";

		//echo $details."<br/>";		
		if(strlen($details)<5)
			continue;
		else
		{

			echo $count."=>"."<b>Original=></b>".$details."<br/>";
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
		    $details=str_ireplace("]", ' ', $details);
		    $details=str_ireplace("_", ' ', $details);
		    $details=str_ireplace(",", ' ', $details);
		    $details=str_ireplace("#", ' ', $details);
		    $details=str_ireplace("-", ' ', $details);
		    $details=str_ireplace("/", ' ', $details);
		    $details = preg_replace('/[0-9]+/', ' ', $details);

		    echo "<b>Cleanup (after removing puctuations)=></b>".$details."<br/>";		   

			$arrTempTerms=explode(" ",$details);
			$lenCase=sizeof($arrTempTerms);
			$str='';
			
			$lenCase=sizeof($arrTempTerms);
			$str='';
			for($i=0;$i<$lenCase;$i++)
			{
				$largestStringFound='';
				
				$firstword=strtolower($arrTempTerms[$i]);
				
				$tempword=trim($firstword);
				
				//echo $tempword."<br/>";
				//echo "<br/>unigram=>".$tempword."<br/>";
				$retWord=my_in_array($tempword,$arrfinal);
				if($retWord)
				{
					//echo "<br/>the largest".$largestStringFound;
					$str.=$retWord." ";
					//$i=$i+1;
					echo "uni=>".$tempword."=>".$retWord."=>".$i."<br/>";
					continue;
				}
				else
				{
					//echo $i."<br/>";
					if(strlen($tempword)>=1)
						$str.=$tempword." ";
				}
			}



			echo "<b>Converted to =></b>".$str."<br/>-------------------------------------<br/>";
			if(strlen(trim($str))>2)
			{
				// enter to the sql
				$query="INSERT INTO `crmcleanupnew`(`originalDescription`, `normalizedUnigramDesc`) VALUES 
				('".$originalDescription."','".$str."')";
				$result=mysql_query($query);
				if(!$result)
				{
					echo $query;
				}
			}

		}
		
	}

}

fclose($fp);
?>