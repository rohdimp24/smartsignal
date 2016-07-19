<?php
require_once 'login.php';
require_once("AnalyzedCase.php");
require_once("dictionaryItem.php");
//read the list of the keywords
$fs=fopen("dictionary.txt","r");
$arrfinal=array();
while(($strfp = fgets($fs))!=null){
	/*if(!strlen(trim($strfp))==0)
	{
		$strfp=trim($strfp);
		$strfp=trim($strfp,'"');
		//$obj=new dictionaryItem($variant,$canonical);
		array_push($arrfinal,strtolower($strfp));

		//}
	}*/

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
    return preg_match('/\\d/', $String) > 0;
}



////////////////////////////////////
$fp=fopen("crmcases.txt","r");
$count=0;

while(($details = fgets($fp))!=null){
	$count=$count+1;
	//if($count<890)
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
			//$details=preg_replace('/[^A-Za-z0-9 _\-\+\&\,\#]/','', $details);
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

		    echo "<b>Cleanup (after removing puctuations)=></b>".$details."<br/>";		   

			$arrTempTerms=explode(" ",$details);
			$lenCase=sizeof($arrTempTerms);
			$str='';
			
			$lenCase=sizeof($arrTempTerms);
			$str='';
			for($i=0;$i<$lenCase;$i++)
			{
				$largestStringFound='';
				//unigram
				$tempword=strtolower($arrTempTerms[$i]);
				if($tempword==" ")
					continue;
				//echo "<br/>unigram=>".$tempword."<br/>";
				$retWord=my_in_array($tempword,$arrfinal);
				if($retWord)
				{
					//echo "unigram added";
					$largestStringFound=$retWord;
					//echo "<br/>the largest".$largestStringFound;
					//$str.=$retWord.",";
				}

				//bigram
				if($i<($lenCase-2))
				{
					$firstword=strtolower($arrTempTerms[$i]);
					$secondword=strtolower($arrTempTerms[$i+1]);
					if((strlen($secondword)<1)|| 
						ContainsNumbers($secondword))
					{
						//take the next word
						$secondword=strtolower($arrTempTerms[$i+2]);
					}
					
					$tempword=$firstword." ".$secondword;
					//echo "<br/>bigram=>".$tempword;
					//echo strlen($firstword).",".strlen($secondword)."<br/>";
					if((strlen($secondword)<1)||
						(strlen($tempword)<1))
						continue;
					//if(in_array($tempword,$arrfinal))
					$retWord=my_in_array($tempword,$arrfinal);
					if($retWord)
					{
						//echo "bigram added";
						//$str.=str_replace(" ", "_", $retWord).",";
						$largestStringFound=$retWord;
						//echo "<br/>the largest".$largestStringFound;
					}

				}	



				//trigrams
				if($i<($lenCase-3))
				{
					$firstword=strtolower($arrTempTerms[$i]);
					$secondword=strtolower($arrTempTerms[$i+1]);
					if(ContainsNumbers($secondword))
					{
						//then considert 4th word
						$secondword=strtolower($arrTempTerms[$i+2]);				
						$thirdword=strtolower($arrTempTerms[$i+3]);
					
					}
					else
					{
						$thirdword=strtolower($arrTempTerms[$i+2]);
					}
					$tempword=$firstword." ".$secondword." ".$thirdword;
					//echo "<br/>trigram=>".$tempword;
					if($tempword==" "||$secondword==" "||$thirdword==" ")
						continue;
					//if(in_array($tempword,$arrfinal))
					$retWord=my_in_array($tempword,$arrfinal);
					if($retWord)
					{
						//echo "trigram added";
						//$str.=$firstword."_".$secondword."_".$thirdword." ";
						//$str.=str_replace(" ", "_", $retWord).",";
						$largestStringFound=$retWord;
						//echo "<br/>the largest".$largestStringFound;
					}

				}

				if(strlen($largestStringFound)>1)
					$str.=str_replace(" ","_",$largestStringFound)." ";
			}



			echo "<b>Converted to =></b>".$str."<br/>-------------------------------------<br/>";
			if(strlen(trim($str))>2)
			{
				//enter to the sql
				$query="INSERT INTO `crmcleanup`(`originalDescription`, `cleanupDescription`) VALUES 
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