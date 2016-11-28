<?php

require_once 'system/model/database.php';
require_once 'system/model/translate.php';
require_once 'system/model/whitelist.php';

class cModel
{
	private $database = null;
	private $translate = null;
	private $whitelist = null;
	//private $current_id = null;
	private $nwish = null;
	private $skills = null;
	private $admin = null;
	//private $current_name = null;
	//private $current_email = null;
	//private $connected = null;
	
	function __construct()
	{
		$this->database = new cDatabase();
		$this->translate = new cTranslate();
		$this->whitelist = new cWhiteList();
		$this->current_id = -1;
		$this->nwish = 2;
		$this->admin = 0;
		// TODO: $this->skills;
	}
	
	public function setConfig($nwish, $skills, $admin)
	{
		$this->nwish = $nwish;
		$this->skills =  $skills;
		$this->admin = $admin;
	}
	
	public function getTeacherProperties($email, $pw)
	{
		$email = $this->whitelist->validateEmail($email);
		// INFO Passwort gets hashed... No need to validate
		
		// TODO return specific error codes here
		if(!$email) return false;
		if(!$pw) return false;
		
		return $this->database->getTeacher($email, $pw, $this->admin);
	}
	
	public function startQuery($type,$id)
	{
		$this->database->startQuery($type,$id);
	}
	
	public function getRow()
	{
		return $this->database->getRow();
	}
	
	public function processAdminPost($post)
	{
		if(isset($post['create_new_teacher']))
		{			
			$name = isset($post['new_teacher_name']) ? $post['new_teacher_name'] : false;
			$mail = isset($post['new_teacher_mail']) ? $post['new_teacher_mail'] : false;
			$pw = isset($post['new_teacher_pw']) ? $post['new_teacher_pw'] : false;
			
			// TODO return specific error codes here
			if(!$name) return false;
			if(!$mail) return false;
			if(!$pw) return false;

			return $this->createNewTeacher($name,$mail,$pw);
		}
		
		if(isset($post['edit_teacher']))
		{
			$id = isset($post['teacher_id']) ? $post['teacher_id'] : false;
			$name = isset($post['edit_teacher_name']) ? $post['edit_teacher_name'] : false;
			$mail = isset($post['edit_teacher_mail']) ? $post['edit_teacher_mail'] : false;
			
			// TODO return specific error codes here
			if(!$id) return false;
			if(!$name) return false;
			if(!$mail) return false;
			
			return $this->updateTeacher($id, $name, $mail);
		}
		
		if(isset($post['edit_teacher_pw']))
		{
			$id = isset($post['teacher_id']) ? $post['teacher_id'] : false;
			$pw1 = isset($post['edit_teacher_pw_1']) ? $post['edit_teacher_pw_1'] : false;
			$pw2 = isset($post['edit_teacher_pw_2']) ? $post['edit_teacher_pw_2'] : false;
			
			// TODO return specific error codes here
			if(!$id) return false;
			if(!$pw1) return false;
			if(!$pw2) return false;
			
			return $this->updateTeacherPw($id, $pw1, $pw2);
		}
		
		if(isset($post['delete_teacher']))
		{
			$del = isset($post['teacher_to_delete']) ? $post['teacher_to_delete'] : false;
			
			// TODO return specific error codes here
			if(!$del) return false;
			
			return $this->deleteTeacher($del);
		}
		
		if(isset($post['create_new_project']))
		{
			$titel = isset($post['project_titel']) ? $post['project_titel'] : false;
			$keywords = isset($post['project_keywords']) ? $post['project_keywords'] : false;
			$abstract = isset($post['project_abstract']) ? $post['project_abstract'] : false;
			$description = isset($post['project_description']) ? $post['project_description'] : false;
			$degree = isset($post['wanted_grade']) ? $post['wanted_grade'] : false;
			$skills = isset($post['wanted_skills']) ? $post['wanted_skills'] : false;
			$teacher_id = isset($_SESSION['current_id']) ? $_SESSION['current_id'] : false;
			
			// TODO return specific error codes here
			if(!$titel) return false;
			if(!$keywords) return false;
			if(!$abstract) return false;
			if(!$description) return false;
			if(!$degree) return false;
			if(!$skills) return false;
			if(!$teacher_id) return false; // TODO emergency logout here!!
			
			$degree = $this->translate->degree2num($degree);
			$skills = $this->translate->skills2str($skills);
			
			// TODO return specific error code here
			if(!$degree) return false;
			if(!$skills) return false;

			return $this->createNewProject($titel, $keywords, $abstract, $description, $degree, $skills, $teacher_id);
		}
		
		if(isset($post['delete_project']))
		{
			$del = isset($post['project_to_delete']) ? $post['project_to_delete'] : false;
			
			// TODO return specific error codes here
			if(!$del) return false;
	
			return $this->deleteProject($del);
		}
		
		if(isset($post['edit_project']))
		{		
			$id = isset($post['project_id']) ? $post['project_id'] : false;
			$titel = isset($post['project_titel']) ? $post['project_titel'] : false;
			$keywords = isset($post['project_keywords']) ? $post['project_keywords'] : false;
			$abstract = isset($post['project_abstract']) ? $post['project_abstract'] : false;
			$description = isset($post['project_description']) ? $post['project_description'] : false;
			$degree = isset($post['wanted_grade']) ? $post['wanted_grade'] : false;
			$skills = isset($post['wanted_skills']) ? $post['wanted_skills'] : false;
			
			// TODO return specific error codes here
			if(!$id) return false;
			if(!$titel) return false;
			if(!$keywords) return false;
			if(!$abstract) return false;
			if(!$description) return false;
			if(!$degree) return false;
			if(!$skills) return false;

			
			$degree = $this->translate->degree2num($degree);
			$skills = $this->translate->skills2str($skills);
			
			// TODO return specific error code here
			if(!$degree) return false;
			if(!$skills) return false;
			
			return $this->editProject($id, $titel, $keywords, $abstract, $description, $degree, $skills);
		}
		
		if(isset($post['project_remove_teacher']))
		{
			$id = isset($post['project_id']) ? $post['project_id'] : false;
			$remove = isset($post['teacher_to_remove']) ? $post['teacher_to_remove'] : false;
			
			// TODO return specific error codes here
			if(!$id) return false;
			if(!$remove) return false;
			
			return $this->projectRemoveTeacher($id, $remove);
		}
		
		if(isset($post['project_add_teacher']))
		{
			$id = isset($post['project_id']) ? $post['project_id'] : false;
			$add = isset($post['teacher_to_add']) ? $post['teacher_to_add'] : false;
			
			// TODO return specific error codes here
			if(!$id) return false;
			if(!$add) return false;

			return $this->projectAddTeacher($id, $add);
		}

	}
	
	public function processTeacherPost($post)
	{
		
	}
	
	public function createNewTeacher($name, $email, $pw)
	{
		$name = $this->whitelist->validateName($name);
		$email = $this->whitelist->validateEmail($email);
		$hash = $this->whitelist->validateAndHashPassword($pw);
		// TODO return specific error codes here
		if(!$name) return false;
		if(!$email) return false;
		if(!$hash) return false;
		
		return $this->database->createNewTeacher($name, $email, $hash);
	}
	
	public function deleteTeacher($teacher_id)
	{
		$teacher_id= $this->whitelist->validateId($teacher_id);
		
		// TODO return specific error codes here
		if(!$teacher_id) return false;
			
		return $this->database->deleteTeacher($teacher_id);
	}
	
	public function getTeacherById($teacher_id)
	{
		$teacher_id = $this->whitelist->validateId($teacher_id);
		
		// TODO return specific error codes here
		if(!$teacher_id) return false;
		
		return $this->database->getTeacherById($teacher_id);
	}
	
	public function updateTeacher($teacher_id, $name, $email)
	{
		$teacher_id = $this->whitelist->validateId($teacher_id);
		$name = $this->whitelist->validateName($name);
		$email = $this->whitelist->validateEmail($email);
		
		// TODO return specific error codes here
		if(!$teacher_id) return false;
		if(!$name) return false;
		if(!$email) return false;
		
		return $this->database->updateTeacher($teacher_id, $name, $email);
	}
	
	public function updateTeacherPw($teacher_id, $pw1, $pw2)
	{
		// TODO return specific error codes here
		if (strcmp($pw1,$pw2) !== 0) return false;
		
		$teacher_id = $this->whitelist->validateId($teacher_id);
		$hash =  $this->whitelist->validateAndHashPassword($pw1);
		
		// TODO return specific error codes here
		if(!$teacher_id) return false;
		if(!$hash) return false;
		
		$this->database->updateTeacherPw($teacher_id, $hash);
	}
	
	public function getTeacherProjects($teacher_id)
	{
		$teacher_id = $this->whitelist->validateId($teacher_id);
		
		// TODO return specific error codes here
		if(!$teacher_id) return false;
		
		return $this->database->getTeacherProjects($teacher_id);
	}
	
	public function getTeacherByProject($project_id)
	{
		$project_id = $this->whitelist->validateId($project_id);
		
		// TODO return specific error codes here
		if(!$project_id) return false;
		
		return $this->database->getTeacherByProject($project_id);
	}
	
	public function deleteProject($project_id)
	{
		$project_id = $this->whitelist->validateId($project_id);
		
		// TODO return specific error codes here
		if(!$project_id) return false;
		
		$this->database->deleteProject($project_id);
	}
	
	public function getDegreeById($id)
	{
		$id = $this->whitelist->validateId($id);
		
		// TODO return specific error codes here
		if(!$id) return false;
		
		return $this->translate->num2degree($id);
	}
	
	public function getSkillsById($id)
	{
		// TODO Error handling maybe in translate class
		return $this->translate->id2skillStr($id, $this->skills);		
	}
	
	public function createNewStudent($name, $email, $field, $matr, $grade)
	{
		$name = $this->whitelist->validateName($name);
		$email = $this->whitelist->validateEmail($email);
		$field = $this->whitelist->validateStudyfield($field);
		$matr = $this->whitelist->validateMatrNr($matr);
		$grade = $this->whitelist->validateGrade($grade);
		
		// TODO return specific error codes here
		if(!$name) return false;
		if(!$email) return false;
		if(!$field) return false;
		if(!$matr) return false;
		if(!$grade) return false;
		
		// TODO check if crypt id is not given to another
		$crypt = $this->whitelist->getRandomId();
		
		return $this->database->insertNewStudent($name, $email, $field, $matr, $grade, $crypt);
	}
	
	public function createNewProject($titel, $keywords, $abstract, $description, $grade, $skills, $teacher_id)
	{
		$titel = $this->whitelist->validateText($titel,400);
		$keywords = $this->whitelist->validateText($keywords,400);
		$abstract = $this->whitelist->validateText($abstract,600);
		$description = $this->whitelist->validateText($description,1500);
		$grade = $this->whitelist->validateGrade($grade);
		$skills = $this->whitelist->validateSkills($skills);
		$teacher_id = $this->whitelist->validateId($teacher_id);
		
		// TODO return specific error codes here
		if(!$titel) return false;
		if(!$keywords) return false;
		if(!$abstract) return false;
		if(!$description) return false;
		if(!$grade) return false;
		if(!$skills) return false;
		if(!$teacher_id) return false;
		
		return $this->database->insertNewProject($titel, $keywords, $abstract, $description, $grade, $skills, $teacher_id);
	}
	
	public function editProject($project_id, $titel, $keywords, $abstract, $description, $grade, $skills)
	{
		$project_id = $this->whitelist->validateId($project_id);
		$titel = $this->whitelist->validateText($titel,400);
		$keywords = $this->whitelist->validateText($keywords,400);
		$abstract = $this->whitelist->validateText($abstract,600);
		$description = $this->whitelist->validateText($description,1500);
		$grade = $this->whitelist->validateGrade($grade);
		$skills = $this->whitelist->validateSkills($skills);
		
		
		// TODO return specific error codes here
		if(!$project_id) return false;
		if(!$titel) return false;
		if(!$keywords) return false;
		if(!$abstract) return false;
		if(!$description) return false;
		if(!$grade) return false;
		if(!$skills) return false;
		
		return $this->database->updateProject($project_id, $titel, $keywords, $abstract, $description, $grade, $skills);
	}
	
	public function projectRemoveTeacher($project_id, $teacher_ids)
	{
		$project_id = $this->whitelist->validateId($project_id);
		
		// TODO return specific error codes here
		if(!$project_id) return false;
		
		foreach($teacher_ids as $id)
		{
			if(!$this->whitelist->validateId($id)) return false;
		}

		return $this->database->removeTeacher($project_id, $teacher_ids);
	}
	
	public function projectAddTeacher($project_id, $teacher_id)
	{
		$project_id = $this->whitelist->validateId($project_id);
		$teacher_id = $this->whitelist->validateId($teacher_id);
		
		// TODO return specific error codes here
		if(!$project_id) return false;
		if(!$teacher_id) return false;

		return $this->database->projectAddTeacher($project_id, $teacher_id);
	}
	
	public function getProjectById($project_id)
	{
		$project_id = $this->whitelist->validateId($project_id);
		
		// TODO return specific error codes here
		if(!$project_id) return false;
		
		return $this->database->getProject($project_id);
	}
	
	public function getStudentById($rand_id)
	{
		$rand_id = $this->whitelist->validateRandomId($rand_id);
		
		// TODO return specific error codes here
		if(!$rand_id) return false;
		
		$result = $this->database->getStudent($rand_id);
		
		// TODO return student properties here
		if(!$result)
			return 0;
		return 1;
	}
	
	public function getSkills()
	{
		return $this->skills;
	}
		
}