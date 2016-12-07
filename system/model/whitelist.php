<?php

class cWhiteList
{


	function __construct()
	{

	}

	public function validateId($id)
	{
		return $id;
	}

	public function validateName($name)
	{
		return $name;
	}

	public function validateEmail($email)
	{
		return $email;
	}

	public function validateMatrNr($matr)
	{
		return $matr;
	}

	public function validateStudyfield($field)
	{
		return $field;
	}

	public function validateGrade($grade)
	{
		return $grade;
	}

	public function validateSkills($skills)
	{
		return $skills;
	}

	public function validateText($txt, $length)
	{
		return $txt;
	}

	public function validateAndHashPassword($pw)
	{
		return $pw;
	}

	public function getRandomId()
	{
		return mt_rand(10000000,99999999);
	}

	public function validateRandomId($id)
	{
		return $id;
	}

	public function validateStageTransition($stage)
	{
		if($stage == 6) return 1;
		else return $stage + 1;
	}

	public function validateNWishes($nwishes)
	{
		return true;
	}
}
