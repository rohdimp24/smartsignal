<?php 
require_once 'login.php';

//read the list of the keywords
$fs=fopen("new_dictionary.txt","r");
while(($strfp = fgets($fs))!=null){
	if(!strlen(trim($strfp))==0)
		{
			list($variant,$canonical)=explode("=>",$strfp);

			#$obj=new dictionaryItem(strtolower($variant),strtolower($canonical));
			#array_push($arrfinal,$obj);
			//echo $canonical."<br/>";
			$canonical=str_replace(" ", "_", $canonical)." ";
			$query="INSERT INTO `normalizedwords`(`word`) VALUES ('".trim($canonical)."') ";
			$result=mysql_query($query);
			if(!$result)
			{
				//echo $canonical."...";
				
				echo mysql_error()."<br/>";
			}

		}
}
	
fclose($fs);
 ?>