<?php
	class Status
	{
		static function convert($input)
		{
			switch($input){
				case 0: 
					$output = "Inactive";
					break;
				case 1:
					$output = "Active";
					break;
				case 2:
					$output = "Repair";
					break;
				case 3:
					$output = "Standby";
					break;
			}
			return $output;
		}
		
		static function printOption()
		{
			//$optionString = "";
			for($i=0;$i<2;$i++)
			{
				echo '<option value="'.$i.'">'.self::convert($i).'</option>';
			}
			//echo $optionString;
		}
	}
?>