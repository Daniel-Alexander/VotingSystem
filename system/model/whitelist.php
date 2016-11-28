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
		$id = 1223344;
		return $id;
	}
	
	public function validateRandomId($id)
	{
		return $id;
	}
}