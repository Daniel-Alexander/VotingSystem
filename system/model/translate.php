<?php

class cTranslate
{

	function __construct()
	{

	}

	public function degree2num($deg_array)
	{
		if(count($deg_array) > 1)
		{
			// FIXME No synthax correction here
			return 3;
		}
		elseif(strcmp($deg_array[0],'Msc') === 0)
		{
			return 2;
		}
		elseif(strcmp($deg_array[0],'Bsc') === 0)
		{
			return 1;
		}

		return false;
	}

	public function num2degree($num)
	{
		// TODO return specific error codes here
		switch($num)
		{
			case 1:
				return 'B.sc.';
				break;
			case 2:
				return 'M.sc.';
				break;
			case 3:
				return 'B.sc. und M.sc.';
				break;
			default:
				return false;
		}
		return false;
	}

	public function skills2str($skill_array)
	{
		// TODO some error handling here
		$str = "";
		foreach($skill_array as $skill)
		{
			$str = "$str$skill;";
		}

		return $str;
	}

	/*public function str2skills($str)
	{
		// TODO some error handling here
		return explode(";", $str);
	}*/

	public function removeSkillsFromStr($str, $skill_array)
	{
		// TODO some error handling here
		foreach($skill_array as $skill)
		{
			$str_to_remove = $this->skills2str(array($skill));
			$str = str_replace($str_to_remove,"", $str);
		}
		return $str;
	}


	public function id2skillStr($id, $skills)
	{
		// TODO Error handling
		$ids = explode(";", $id);

		$str = "";

		foreach($ids as $cur_id)
		{
			foreach($skills as $skill)
			{
				if($cur_id == $skill->sign)
					$str = "$str".$skill->name.", ";
			}
		}
		return $str;
	}

}
