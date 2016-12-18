<?php

require_once 'system/model/database.php';
require_once 'system/model/translate.php';
require_once 'system/model/whitelist.php';

class cModel
{
	private $database = null;
	private $translate = null;
	public $whitelist = null;
	private $errorhandle = null;
	//private $current_id = null;
	private $nwish = null;
	private $skills = null;
	private $admin = null;
	//private $current_name = null;
	//private $current_email = null;
	//private $connected = null;

	function __construct($errorhandle)
	{
		$this->errorhandle = $errorhandle;
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

	public function getNWish()
	{
		return $this->nwish;
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
		if(isset($post['next_stage']))
		{
			return $this->changeConfig("next_stage",0);
		}

		if(isset($post['cancel_system']))
		{
			return $this->changeConfig("cancel_system",0);
		}

		if(isset($post['create_new_teacher']))
		{
			$name = isset($post['new_teacher_name']) ? $post['new_teacher_name'] : false;
			$mail = isset($post['new_teacher_mail']) ? $post['new_teacher_mail'] : false;
			$pw = isset($post['new_teacher_pw']) ? $post['new_teacher_pw'] : false;

			// TODO return specific error codes here
			if(!$name or !$mail or !$pw)
			{
				$this->errorhandle->errFormNewTeacher($name,$mail,$pw,false);
				return true;
			}

			return $this->createNewTeacher($name,$mail,$pw);
		}

		if(isset($post['edit_teacher']))
		{
			$id = isset($post['teacher_id']) ? $post['teacher_id'] : false;
			$name = isset($post['edit_teacher_name']) ? $post['edit_teacher_name'] : false;
			$mail = isset($post['edit_teacher_mail']) ? $post['edit_teacher_mail'] : false;

			if(!$id or !$name or !$mail)
			{
				$this->errorhandle->errFormEditTeacher($id,$name,$mail,false);
				return true;
			}

			return $this->updateTeacher($id, $name, $mail);
		}

		if(isset($post['edit_teacher_pw']))
		{
			$id = isset($post['teacher_id']) ? $post['teacher_id'] : false;
			$pw1 = isset($post['edit_teacher_pw_1']) ? $post['edit_teacher_pw_1'] : false;
			$pw2 = isset($post['edit_teacher_pw_2']) ? $post['edit_teacher_pw_2'] : false;

			if(!$id or !$pw1 or !$pw2)
			{
				$this->errorhandle->errFormEditTeacherPw($id,false);
				return true;
			}

			return $this->updateTeacherPw($id, $pw1, $pw2);
		}

		if(isset($post['delete_teacher']))
		{
			$del = isset($post['teacher_to_delete']) ? $post['teacher_to_delete'] : false;

			if(!$del)
			{
				$this->errorhandle->errBadId("teacher");
				return true;
			}
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

			// TODO emergency logout here!!
			if(!$teacher_id or !$titel or !$keywords or !$abstract or !$description or !$degree or !$skills)
			{
				$this->errorhandle->errFormNewProject($teacher_id,$titel,$keywords,$abstract,$description,$degree,$skills);
				return true;
			}

			// TODO maybe do this in function
			$degree = $this->translate->degree2num($degree);
			$skills = $this->translate->skills2str($skills);

			if(!$degree or !$skills)
			{
				$this->errorhandle->errFormNewProject($teacher_id,$titel,$keywords,$abstract,$description,$degree,$skills);
				return true;
			}

			return $this->createNewProject($titel, $keywords, $abstract, $description, $degree, $skills, $teacher_id);
		}

		if(isset($post['delete_project']))
		{
			$del = isset($post['project_to_delete']) ? $post['project_to_delete'] : false;

			if(!$del)
			{
				$this->errorhandle->errBadId("project");
				return true;
			}

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

			// TODO special skills and degree handle here
			if(!$id or !$titel or !$keywords or !$abstract or !$description or !$degree or !$skills)
			{
				$this->errorhandle->errFormEditProject($id,$titel,$keywords,$abstract,$description,$degree,$skills);
				return true;
			}

			// TODO maybe do this in function
			$degree = $this->translate->degree2num($degree);
			$skills = $this->translate->skills2str($skills);

			if(!$degree or !$skills)
			{
				$this->errorhandle->errFormEditProject($teacher_id,$titel,$keywords,$abstract,$description,$degree,$skills);
				return true;
			}

			return $this->editProject($id, $titel, $keywords, $abstract, $description, $degree, $skills);
		}

		if(isset($post['project_remove_teacher']))
		{
			$id = isset($post['project_id']) ? $post['project_id'] : false;
			$remove = isset($post['teacher_to_remove']) ? $post['teacher_to_remove'] : false;

			if(!$id or !$remove)
			{
				$this->errorhandle->errBadId("project");
				return true;
			}

			return $this->projectRemoveTeacher($id, $remove);
		}

		if(isset($post['project_add_teacher']))
		{
			$id = isset($post['project_id']) ? $post['project_id'] : false;
			$add = isset($post['teacher_to_add']) ? $post['teacher_to_add'] : false;

			if(!$id or !$add)
			{
				$this->errorhandle->errBadId("project");
				return true;
			}

			return $this->projectAddTeacher($id, $add);
		}

		if(isset($post['add_new_student']))
		{
			$name = isset($post['new_student_name']) ? $post['new_student_name'] : false;
			$matr = isset($post['new_student_matr']) ? $post['new_student_matr'] : false;
			$email = isset($post['new_student_email']) ? $post['new_student_email'] : false;
			$field = isset($post['new_student_field']) ? $post['new_student_field'] : false;
			$grade = isset($post['new_student_grade']) ? $post['new_student_grade'] : false;
			$skills = isset($post['new_student_skills']) ? $post['new_student_skills'] : false;

			if(!$name or !$matr or !$email or !$field or !$grade or !$skills)
			{
				$this->errorhandle->errFormNewStudent($name,$matr,$email,$field,$grade,$skills,false);
				return true;
			}

			return $this->createNewStudent($name, $email, $field, $matr, $grade, $skills);
		}

		if(isset($post['edit_student']))
		{
			$student_id = isset($post['student_id']) ? $post['student_id'] : false;
			$name = isset($post['edit_student_name']) ? $post['edit_student_name'] : false;
			$matr = isset($post['edit_student_matr']) ? $post['edit_student_matr'] : false;
			$email = isset($post['edit_student_email']) ? $post['edit_student_email'] : false;
			$field = isset($post['edit_student_field']) ? $post['edit_student_field'] : false;
			$grade = isset($post['edit_student_grade']) ? $post['edit_student_grade'] : false;
			$skills = isset($post['edit_student_skills']) ? $post['edit_student_skills'] : false;

			if(!$student_id or !$name or !$matr or !$email or !$field or !$grade)
			{
				// INFO skills must not be set
				if(!$skills) $skills = true;
				$this->errorhandle->errFormEditStudent($student_id,$name,$matr,$email,$field,$grade,$skills,false);
				return true;
			}

			return $this->updateStudent($student_id, $name, $email, $field, $matr, $grade, $skills);
		}

		if(isset($post['student_save_interests']))
		{
			$student_id = isset($post['student_id']) ? $post['student_id'] : false;
			$interest1 = isset($post['first_wish']) ? $post['first_wish'] : false;
			$interest2 = isset($post['second_wish']) ? $post['second_wish'] : false;
			$interest3 = isset($post['third_wish']) ? $post['third_wish'] : false;

			if(!$student_id)
			{
				$this->errorhandle->errBadId("student");
				return true;
			}

			// INFO there must be at least one change ->this is not an error just nothing todo
			if(!$interest1 and !$interest2  and !$interest3) return false;

			return $this->updateStudentInterests($student_id, $interest1, $interest2, $interest3);
		}

		if(isset($post['student_toggle_active']))
		{
			$student_id = isset($post['student_to_toggle']) ? $post['student_to_toggle'] : false;

			if(!$student_id)
			{
				$this->errorhandle->errBadId("student");
				return true;
			}

			return $this->studentToggleActive($student_id,2);
		}

		if(isset($post['delete_student']))
		{
			$student_id = isset($post['student_to_delete']) ? $post['student_to_delete'] : false;

			if(!$student_id)
			{
				$this->errorhandle->errBadId("student");
				return true;
			}

			return $this->deleteStudent($student_id);

		}


		if(isset($post['delete_skills']))
		{
			$skill_array = isset($post['skills_to_delete']) ? $post['skills_to_delete'] : false;

			// TODO return specific error codes here
			if(!$skill_array) return false;

			return $this->changeConfig("delete_skills",$skill_array);
		}

		if(isset($post['add_new_skill']))
		{
			$new_skill = isset($post['new_skill']) ? $post['new_skill'] : false;

			// TODO return specific error codes here
			if(!$new_skill) return false;

			return $this->changeConfig("add_skills",$new_skill);
		}

		if(isset($post['set_num_wishes']))
		{
			$nwishes = isset($post['num_of_wishes']) ? $post['num_of_wishes'] : false;

			// TODO return specific error codes here
			if(!$nwishes) return false;

			return $this->changeConfig("change_num_wishes",$nwishes);
		}


	}

	public function processTeacherPost($post)
	{
		if(isset($post['create_new_project']))
		{
			$titel = isset($post['project_titel']) ? $post['project_titel'] : false;
			$keywords = isset($post['project_keywords']) ? $post['project_keywords'] : false;
			$abstract = isset($post['project_abstract']) ? $post['project_abstract'] : false;
			$description = isset($post['project_description']) ? $post['project_description'] : false;
			$degree = isset($post['wanted_grade']) ? $post['wanted_grade'] : false;
			$skills = isset($post['wanted_skills']) ? $post['wanted_skills'] : false;
			$teacher_id = isset($_SESSION['current_id']) ? $_SESSION['current_id'] : false;

			// TODO emergency logout here!!
			if(!$teacher_id or !$titel or !$keywords or !$abstract or !$description or !$degree or !$skills)
			{
				$this->errorhandle->errFormNewProject($teacher_id,$titel,$keywords,$abstract,$description,$degree,$skills);
				return true;
			}

			// TODO maybe do this in function
			$degree = $this->translate->degree2num($degree);
			$skills = $this->translate->skills2str($skills);

			if(!$degree or !$skills)
			{
				$this->errorhandle->errFormNewProject($teacher_id,$titel,$keywords,$abstract,$description,$degree,$skills);
				return true;
			}

			return $this->createNewProject($titel, $keywords, $abstract, $description, $degree, $skills, $teacher_id);
		}

		if(isset($post['delete_project']))
		{
			$del = isset($post['project_to_delete']) ? $post['project_to_delete'] : false;

			if(!$del)
			{
				$this->errorhandle->errBadId("project");
				return true;
			}

			// TODO authentification here!!
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

			// TODO special skills and degree handle here
			if(!$id or !$titel or !$keywords or !$abstract or !$description or !$degree or !$skills)
			{
				$this->errorhandle->errFormEditProject($id,$titel,$keywords,$abstract,$description,$degree,$skills);
				return true;
			}

			// TODO maybe do this in function
			$degree = $this->translate->degree2num($degree);
			$skills = $this->translate->skills2str($skills);

			if(!$degree or !$skills)
			{
				$this->errorhandle->errFormEditProject($teacher_id,$titel,$keywords,$abstract,$description,$degree,$skills);
				return true;
			}

			// TODO authentification here!!
			return $this->editProject($id, $titel, $keywords, $abstract, $description, $degree, $skills);
		}

		if(isset($post['project_add_teacher']))
		{
			$id = isset($post['project_id']) ? $post['project_id'] : false;
			$add = isset($post['teacher_to_add']) ? $post['teacher_to_add'] : false;

			if(!$id or !$add)
			{
				$this->errorhandle->errBadId("project");
				return true;
			}

			return $this->projectAddTeacher($id, $add);
		}

		if(isset($post['project_remove_me']))
		{
			$id = isset($post['project_id']) ? $post['project_id'] : false;
			$remove = isset($_SESSION['current_id']) ? $_SESSION['current_id'] : false;

			// TODO emergency logout here!!
			if(!$id or !$remove)
			{
				$this->errorhandle->errBadId("project");
				return true;
			}

			return $this->projectRemoveTeacher($id, array($remove));
		}

		// TODO authentification here!!
		if(isset($post['edit_teacher']))
		{
			$id = isset($post['teacher_id']) ? $post['teacher_id'] : false;
			$name = isset($post['edit_teacher_name']) ? $post['edit_teacher_name'] : false;
			$mail = isset($post['edit_teacher_mail']) ? $post['edit_teacher_mail'] : false;

			if(!$id or !$name or !$mail)
			{
				$this->errorhandle->errFormEditTeacher($id,$name,$mail,false);
				return true;
			}

			return $this->updateTeacher($id, $name, $mail);
		}

		// TODO authentification here!!
		if(isset($post['edit_teacher_pw']))
		{
			$id = isset($post['teacher_id']) ? $post['teacher_id'] : false;
			$pw1 = isset($post['edit_teacher_pw_1']) ? $post['edit_teacher_pw_1'] : false;
			$pw2 = isset($post['edit_teacher_pw_2']) ? $post['edit_teacher_pw_2'] : false;

			if(!$id or !$pw1 or !$pw2)
			{
				$this->errorhandle->errFormEditTeacherPw($id,false);
				return true;
			}

			return $this->updateTeacherPw($id, $pw1, $pw2);
		}

	}

	public function processStudentPost($post)
	{
		if(isset($post['student_save_interests']))
		{
			$student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : false;
			$interest1 = isset($post['first_wish']) ? $post['first_wish'] : false;
			$interest2 = isset($post['second_wish']) ? $post['second_wish'] : false;
			$interest3 = isset($post['third_wish']) ? $post['third_wish'] : false;

			// TODO emergency logout here!
			if(!$student_id)
			{
				$this->errorhandle->errBadId("student");
				return true;
			}

			// INFO there must be at least one change ->this is not an error just nothing todo
			if(!$interest1 and !$interest2  and !$interest3) return false;

			return $this->updateStudentInterests($student_id, $interest1, $interest2, $interest3);
		}

		if(isset($post['student_delete_me']))
		{
			$student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : false;

			// TODO Emergency logout here
			if(!$student_id)
			{
				return true;
			}

			$this->deleteStudent($student_id);

			// TODO use secure function here!
			session_destroy();
			header('Location: index.php');

			return false;
		}

		return false;
	}

	public function createNewTeacher($name, $email, $pw)
	{
		$name = $this->whitelist->validateName($name);
		$email = $this->whitelist->validateEmail($email);
		$hash = $this->whitelist->validateAndHashPassword($pw);
		// TODO return specific error codes here
		if(!$name or !$email or !$hash)
		{
			$this->errorhandle->errFormNewTeacher($name,$email,0,false);
			return true;
		}

		$err = $this->database->createNewTeacher($name, $email, $hash);

		if($err)
		{
			$this->errorhandle->errFormNewTeacher($name,$email,0,true);
			return true;
		}
		return false;
	}

	public function deleteTeacher($teacher_id)
	{
		$teacher_id= $this->whitelist->validateId($teacher_id);

		// TODO return specific error codes here
		if(!$teacher_id)
		{
			$this->errorhandle->errBadId("teacher");
			return true;
		}

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
		if(!$teacher_id or !$name or !$email)
		{
			$this->errorhandle->errFormEditTeacher($teacher_id,$name,$email,false);
			return true;
		}

		$err = $this->database->updateTeacher($teacher_id, $name, $email);

		if($err)
		{
			$this->errorhandle->errFormEditTeacher($teacher_id,$name,$email,true);
			return true;
		}
		return false;
	}

	public function updateTeacherPw($teacher_id, $pw1, $pw2)
	{

		$teacher_id = $this->whitelist->validateId($teacher_id);

		if (strcmp($pw1,$pw2) !== 0)
		{
			$this->errorhandle->errFormEditTeacherPw($teacher_id,true);
			return true;
		}

		$hash =  $this->whitelist->validateAndHashPassword($pw1);

		if(!$hash)
		{
			$this->errorhandle->errFormEditTeacherPw($teacher_id,false);
			return true;
		}

		$err = $this->database->updateTeacherPw($teacher_id, $hash);

		if($err)
		{
			$this->errorhandle->errFormEditTeacherPw($teacher_id,false);
			return true;
		}
		return false;
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

	public function getStudentsByProject($project_id,$wish)
	{
		// TODO return specific error codes here
		if(!$project_id) return false;

		return $this->database->getStudentsByProject($project_id,$wish);
	}

	public function deleteProject($project_id)
	{
		$project_id = $this->whitelist->validateId($project_id);

		if(!$project_id)
		{
			$this->errorhandle->errBadId("teacher");
			return true;
		}

		$err = $this->database->deleteProject($project_id);

		if($err)
		{
			$this->errorhandle->errBadId("teacher");
			return true;
		}
		return false;
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

	public function createNewStudent($name, $email, $field, $matr, $grade, $skills)
	{
		$name = $this->whitelist->validateName($name);
		$email = $this->whitelist->validateEmail($email);
		$field = $this->whitelist->validateStudyfield($field);
		$matr = $this->whitelist->validateMatrNr($matr);
		$grade = $this->whitelist->validateGrade($grade);
		$skills = $this->whitelist->validateSkills($skills);

		if(!$name or !$matr or !$email or !$field or !$grade or !$skills)
		{
			$this->errorhandle->errFormNewStudent($name,$matr,$email,$field,$grade,$skills,false);
			return true;
		}

		$grade = $this->translate->degree2num($grade);
		$skills = $this->translate->skills2str($skills);

		//echo $skills;
		if(!$grade or !$skills)
		{
			$this->errorhandle->errFormNewStudent($name,$matr,$email,$field,$grade,$skills,false);
			return true;
		}

		$crypt = $this->whitelist->getRandomId();

		$err = $this->database->insertNewStudent($name, $email, $field, $matr, $grade, $skills, $crypt);

		if($err)
		{
			$this->errorhandle->errFormNewStudent($name,$matr,$email,$field,$grade,$skills,true);
			return true;
		}
		return false;
	}

	public function updateStudent($student_id, $name, $email, $field, $matr, $grade, $skills)
	{
		$student_id = $this->whitelist->validateId($student_id);
		$name = $this->whitelist->validateName($name);
		$email = $this->whitelist->validateEmail($email);
		$field = $this->whitelist->validateStudyfield($field);
		$matr = $this->whitelist->validateMatrNr($matr);
		$grade = $this->whitelist->validateGrade($grade);
		$skills = $this->whitelist->validateSkills($skills);


		if(!$student_id or !$name or !$matr or !$email or !$field or !$grade)
		{
			// INFO skills must not be set
			if(!$skills) $skills = true;
			$this->errorhandle->errFormEditStudent($student_id,$name,$matr,$email,$field,$grade,$skills,false);
			return true;
		}

		$grade = $this->translate->degree2num(array($grade));

		if ($skills !== false)
			$skills = $this->translate->skills2str($skills);

		if(!$grade)
		{
			// INFO skills must not be set
			if(!$skills) $skills = true;
			$this->errorhandle->errFormEditStudent($student_id,$name,$matr,$email,$field,$grade,$skills,false);
			return true;
		}

		$err = $this->database->updateStudent($student_id, $name, $email, $field, $matr, $grade, $skills);

		if($err)
		{
			$this->errorhandle->errFormEditStudent($student_id,$name,$matr,$email,$field,$grade,$skills,true);
			return true;
		}
		return false;
	}

	public function updateStudentInterests($student_id, $interest1, $interest2, $interest3)
	{

		$student_id = $this->whitelist->validateId($student_id);

		if(!$student_id)
		{
			$this->errorhandle->errBadId("project");
			return true;
		}

		if(!$interest1) $interest1 = 0;
		else $interest1 = $this->whitelist->validateId($interest1);

		if(!$interest2) $interest2= 0;
		else $interest2 = $this->whitelist->validateId($interest2);

		if(!$interest3) $interest3= 0;
		else $interest3 = $this->whitelist->validateId($interest3);

		// INFO compare explicit boolean type here! values can be 0!
		if($interest1 === false or $interest2 === false or $interest3 === false)
		{
			$this->errorhandle->errBadId("student");
			return true;
		}

		$err = $this->database->updateStudentInterests($student_id, $interest1, $interest2, $interest3);

		if($err)
		{
			$this->errorhandle->errBadId("student");
			return true;
		}
		return false;
	}

	public function studentToggleActive($student_id,$toggle)
	{
		$student_id = $this->whitelist->validateId($student_id);

		// TODO return specific error codes here
		if(!$student_id) return false;

		return $this->database->studentToggleActive($student_id,$toggle);

	}

	public function getStudentById($student_id)
	{
		$student_id = $this->whitelist->validateId($student_id);

		// TODO return specific error codes here
		if(!$student_id) return false;

		return $this->database->getStudentById($student_id);
	}

	public function getStudentInterests($student_id)
	{
		$student_id = $this->whitelist->validateId($student_id);

		// TODO return specific error codes here
		if(!$student_id) return false;

		return $this->database->getInterestsByStudent($student_id);
	}

	public function deleteStudent($student_id)
	{
			$student_id = $this->whitelist->validateId($student_id);

			// TODO return specific error codes here
			if(!$student_id) return false;

			return $this->database->removeStudent($student_id);
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


		if(!$teacher_id or !$titel or !$keywords or !$abstract or !$description or !$grade or !$skills)
		{
			$this->errorhandle->errFormNewProject($teacher_id,$titel,$keywords,$abstract,$description,$grade,$skills);
			return true;
		}

		$err = $this->database->insertNewProject($titel, $keywords, $abstract, $description, $grade, $skills, $teacher_id);

		if($err)
		{
			$this->errorhandle->errFormNewProject($teacher_id,$titel,$keywords,$abstract,$description,$grade,$skills);
			return true;
		}
		return false;
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

		if(!$project_id or !$titel or !$keywords or !$abstract or !$description or !$grade or !$skills)
		{
			$this->errorhandle->errFormEditProject($project_id,$titel,$keywords,$abstract,$description,$grade,$skills);
			return true;
		}

		$err = $this->database->updateProject($project_id, $titel, $keywords, $abstract, $description, $grade, $skills);

		if($err)
		{
			$this->errorhandle->errFormEditProject($project_id,$titel,$keywords,$abstract,$description,$grade,$skills);
			return true;
		}
		return false;

	}

	public function projectRemoveTeacher($project_id, $teacher_ids)
	{
		$project_id = $this->whitelist->validateId($project_id);

		if(!$project_id)
		{
			$this->errorhandle->errBadId("project");
			return true;
		}

		foreach($teacher_ids as $id)
		{
			if(!$this->whitelist->validateId($id))
			{
				$this->errorhandle->errBadId("project");
				return true;
			}
		}

		$err = $this->database->removeTeacher($project_id, $teacher_ids);

		// TODO This is not a bad is error
		if($err)
		{
			$this->errorhandle->errBadId("project");
			return true;
		}
		return false;
	}

	public function projectAddTeacher($project_id, $teacher_id)
	{
		$project_id = $this->whitelist->validateId($project_id);
		$teacher_id = $this->whitelist->validateId($teacher_id);

		if(!$project_id or !$teacher_id)
		{
			$this->errorhandle->errBadId("project");
			return true;
		}

		$err = $this->database->projectAddTeacher($project_id, $teacher_id);

		// TODO this is not a bad ID maybe already 5 teacher
		if($err)
		{
			$this->errorhandle->errBadId("project");
			return true;
		}

	}

	public function getProjectById($project_id)
	{
		$project_id = $this->whitelist->validateId($project_id);

		// TODO return specific error codes here
		if(!$project_id) return false;

		return $this->database->getProject($project_id,true);
	}

	public function getProjectByAuth($project_id,$auth_id)
	{
		$project_id = $this->whitelist->validateId($project_id);
		$auth_id = $this->whitelist->validateId($auth_id);

		// TODO return specific error codes here
		if(!$project_id) return false;
		if(!$auth_id) return false;

		return $this->database->getProject($project_id,$auth_id);
	}

	/*public function addNewStudent($name, $matr, $email, $field, $grade, $skills)
	{
		$name = $this->whitelist->validateName($name);
		$matr = $this->whitelist->validateMatrNr($matr);
		$email = $this->whitelist->validateEmail($email);
		$field = $this->whitelist->validateStudyfield($field);
		$grade = $this->whitelist->validateGrade($grade);
		$skills = $this->whitelist->validateSkills($skills);

		if(!$name) return false;
		if(!$matr) return false;
		if(!$email) return false;
		if(!$field) return false;
		if(!$grade) return false;
		if(!$skills) return false;

		$grade = $this->translate->degree2num($grade);
		$skills = $this->translate->skills2str($skills);

		return $this->database->insertStudent($name, $matr, $email, $field, $grade, $skills)
	}*/

	public function getStudentByCrypt($rand_id)
	{
		$rand_id = $this->whitelist->validateRandomId($rand_id);

		// TODO return specific error codes here
		if(!$rand_id) return false;

		return $this->database->getStudent($rand_id);
	}

	public function getSkills()
	{
		return $this->skills;
	}

	private function changeConfig($config, $arg)
	{
		// TODO validate inputs!!!!
		$xml = new DOMDocument();
		$xml->load("system/config/config.xml");
		if(!$xml) return false; // TODO return fatal error here

		switch($config)
		{
			case "next_stage":
				$cur_stage = $xml->documentElement->getElementsByTagName("stage")->item(0)->textContent;
				$next = $this->whitelist->validateStageTransition($cur_stage);
				$xml->documentElement->getElementsByTagName("stage")->item(0)->textContent = $next;
				break;

			case "cancel_system":
				$xml->documentElement->getElementsByTagName("stage")->item(0)->textContent = 1;
				break;

			case "change_num_wishes":
				if($this->whitelist->validateNWishes($arg))
					$xml->documentElement->getElementsByTagName("nwish")->item(0)->textContent = $arg;
				break;

			case "delete_skills":
				// TODO search in projects and delete skills;
				$this->database->deleteSkillsBySign($arg, $this->translate);
				$skills = $xml->documentElement->getElementsByTagName("skill");
				foreach($arg as $sign_to_delete)
					foreach ($skills as $skill)
					{
							$sign = $skill->getElementsByTagName("sign")->item(0)->nodeValue;
							if($sign == $sign_to_delete)
								$skill->parentNode->removeChild($skill);
					}
				break;

			case "add_skills":
				if( $xml->documentElement->getElementsByTagName("skill")->length >= 10 )
					return false; // TODO return error code here: skill list full!

				// INFO find first free index
				$new_sign = 0;
				$skills = $xml->documentElement->getElementsByTagName("skill");
				$taken = false;
				for($ind = 1; $ind < 10; $ind++)
				{
					$taken = false;
					foreach ($skills as $skill)
					{
						if($skill->getElementsByTagName("sign")->item(0)->nodeValue == $ind)
						{
							$taken = true;
						}
					}
					if(!$taken)
					{
						$new_sign = $ind;
						break;
					}
				}

				if($new_sign == 0) return false; // TODO error code here

				$skill_node = $xml->createElement("skill");
				$sign_node = $xml->createElement("sign",$new_sign);
				$name_node = $xml->createElement("name",$arg);
				$skill_node = $xml->documentElement->appendChild($skill_node);
				$skill_node->appendChild($sign_node);
				$skill_node->appendChild($name_node);
				break;

			default:
				return false;
				break;
		}

		$xml->save("system/config/config.xml");

		// INFO ..done reload system now
		header("Location: redirect.php?page=settings");
	}

}
