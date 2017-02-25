<?php

class cWhiteList
{
	function __construct()
	{
	}

	public function validateId($id)
	{
		if (is_numeric($id))
			return $id;
		else return false;
	}

	public function validateName($name)
	{
		$name = strip_tags($name);
		//$name = mysql_real_escape_string($name);
		if(preg_match("/^[a-zA-Z ]*$/",$name))
			return $name;
		else return false;
	}

	public function validateEmail($email)
	{
		$email = strip_tags($email);
		//$email = mysql_real_escape_string($email);
		if(filter_var($email, FILTER_VALIDATE_EMAIL))
			return $email;
		else return false;
	}

	public function validateMatrNr($matr)
	{
		if(is_numeric($matr))
			if(preg_match("/^[1-9][0-9]{5,5}$/",$matr))
				return $matr;
			else return false;
		else return false;
	}

	public function validateStudyfield($field)
	{
		$field = strip_tags($field);
		//$field = mysql_real_escape_string($field);
		$length = strlen($field);
		if($length > 2 && $length < 100)
			return $field;
		else return false;
	}

	public function validateGrade($grade)
	{
		//echo $grade[0];
		if($grade == 1 or $grade == 2 or $grade == 3) // 1:Bsc., 2:Msc., 3:both
			return $grade;
		else return false;
	}

	public function validateSkills($skills)
	{

		if(!is_array($skills))
		{
			$skills = array($skills);
		}

		foreach($skills as $skill)
		{
			if(is_numeric($skill))
			{
				if($skill > 10 or $skill < 0)  {return false;}
			}
			else{ return false;}
		}

		return $skills;
	}

	public function validateText($txt, $length)
	{
		$txt = strip_tags($txt);
		//$txt = mysql_real_escape_string($txt);
		$strlength = strlen($txt);
		if($strlength > 2 and $strlength < $length)
			return $txt;
		else return false;
	}

	public function validateAndHashPassword($pw)
	{
		return password_hash($pw, PASSWORD_BCRYPT);
	}

	public function getRandomId()
	{
		return mt_rand(10000000,99999999);
	}

	public function validateRandomId($id)
	{
		if(is_numeric($id))
			if($id >= 10000000 && $id <= 99999999)
				return $id;
			else return false;
		else return false;
	}

	public function validateStageTransition($stage)
	{
		if($stage == 6) return 1;
		if($stage == 5 or $stage == 4 or $stage == 3 or $stage == 2 or $stage == 1)
			return $stage + 1;
		else return 1;
	}

	public function validateNWishes($nwishes)
	{
		if($nwishes == 1 or $nwishes == 2 or $nwishes == 3)
			return true;
		else return false;
	}

	public function validateDeadline($deadline)
	{

		$dl = explode(".", $deadline);
		if(sizeof($dl) != 3) return false;
		if(!is_numeric($dl[0]) or !is_numeric($dl[1]) or !is_numeric($dl[2])) return false;
		if(!checkdate($dl[1],$dl[0],$dl[2])) return false;
		return $deadline;
	}
}
