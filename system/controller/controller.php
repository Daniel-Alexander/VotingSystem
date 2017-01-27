<?php

require_once 'system/model/model.php';
require_once 'system/view/view.php';
require_once 'system/controller/errorhandle.php';

class cController
{
	private $get = null;
	private $post = null;
	private $view = null;
	private $model = null;
	private $errorhandle = null;
	private $stage = null;
	private $deadline = null;

	function __construct($get, $post)
	{
		$this->get = $get;
		$this->post = $post;
		$this->errorhandle = new cErrorHandle();
		$this->model = new cModel($this->errorhandle);
		$this->view = new cView($this->model,$this->errorhandle);
		$this->getConfig();
	}

	function redirectSession()
	{
		if(isset($this->get['logout']))
		{
			// TODO replace this by secure function
			session_destroy();
			$this->view->loadLogoutPage(false);
			return false;
		}

		//$this->view->setModel($this->model);

		$page = 'main';
		$subpage = 'none';
		$page_id = 0;
		if(isset($this->get['page']))
			$page = $this->get['page'];
		if(isset($this->get['subpage']))
			$subpage = $this->get['subpage'];
		if(isset($this->get['page_id']))
			$page_id = $this->get['page_id'];


		switch($_SESSION['role'])
		{
			case 2: // Admin

				$err = $this->model->processAdminPost($this->post);
				$this->view->loadAdminPage($page,$subpage,$page_id,$err);
				break;
			case 1: // teacher

				$err = $this->model->processTeacherPost($this->post);
				$this->view->loadTeacherPage($page,$subpage,$page_id,$err);
				break;
			case 0: // student

				$err = $this->model->processStudentPost($this->post);
				$this->view->loadStudentPage($page,$subpage,$page_id,$err);
				break;
			default: // fail
				// TODO replace this by secure function
				session_destroy();
				$this->view->loadLogoutPage(false);
				break;
		}

		return false;
	}

	function redirectOutside()
	{
		$err = false;
		$role = 'student';

		if(isset($this->get['signout']))
		{
				$this->view->loadLogoutPage(true);
				return;
		}

		if(isset($this->get['token']))
		{
			// INFO token gets validated in model
			// INFO Student gets activated in model
			$student_id = $this->studentLogin();

			if($student_id !== false)
			{
				// TODO replace this by secure function
				session_start();
				$_SESSION['valid'] = 1;
				$_SESSION['role'] = 0;
				$_SESSION['student_id'] = $student_id;

				header('Location: redirect.php');
				return false;
			}
		}

		if(isset($this->post['create_new_student']))
		{
			$err = $this->createNewStudent();
			if(!$err)
			{
				$this->view->SetSignedInProperty();
			}
		}

		if(isset($this->post['teacher_login']))
		{

			$stage = $this->teacherLogin();

			if(!$stage)
			{
				$err = $this->errorhandle->errLoginFail();
				$role = "teacher";
			}
			else
			{
				switch ($stage[0])
				{
					case 2: // Admin
					case 1: // teacher
						// TODO replace this by secure function
						session_start();
						$_SESSION['valid'] = 1;
						$_SESSION['role'] = $stage[0];
						$_SESSION['current_id'] = $stage[1];

						header('Location: redirect.php');
						//return false;
						break;
					default: // fail
						$err = $this->errorhandle->errLoginFail();
						break;
				}
			}
		}


		if(isset($this->get['role']))
			$role = $this->get['role'];

		$valid = $this->view->loadStartPage($role,$err);

		if(!$valid)
		{
			// TODO mabe remove this!
			echo 'Error: undefined role';
		}

	}

	function display()
	{
		return $this->view->loadTemplate();
	}

	private function createNewStudent()
	{

		$name = isset($this->post['student_name']) ? $this->post['student_name'] : false;
		$matr = isset($this->post['student_matr']) ? $this->post['student_matr'] : false;
		$email = isset($this->post['student_email']) ? $this->post['student_email'] : false;
		$field = isset($this->post['student_fos']) ? $this->post['student_fos'] : false;
		$degree = isset($this->post['student_degree']) ? array($this->post['student_degree']) : false;
		$skills = isset($this->post['student_skills']) ? $this->post['student_skills'] : false;


		if(!$name or !$matr or !$email or !$field or !$degree or !$skills)
		{
			$this->errorhandle->errFormNewStudent($name,$matr,$email,$field,$degree,$skills,false);
			return true;
		}

		return	$this->model->createNewStudent($name, $email, $field, $matr, $degree, $skills);
	}

	private function teacherLogin()
	{
		// TODO check teacher parameter here return 0 for fail 1 for teacher 2 for admin
		$email = isset($this->post['teacher_mail']) ? $this->post['teacher_mail'] : false;
		$pw = isset($this->post['teacher_pw']) ? $this->post['teacher_pw'] : false;

		if(!$email or !$pw)
		{
			$this->errorhandle->errLoginFail();
			return true;
		}

		return $this->model->getTeacherProperties($email, $pw);
	}

	private function studentLogin()
	{
		$token = isset($this->get['token']) ? $this->get['token'] : false;

		if(!$token)
		{
			$this->errorhandle->errLoginFail();
			return false;
		}

		return $this->model->getStudentByCrypt($token);
	}

	private function getConfig()
	{
		// TODO replace this by DOM!
		if (file_exists('system\config\config.xml'))
		{
			$xml = simplexml_load_file('system\config\config.xml');
			$this->stage = $xml->stage;
			$this->deadline = $xml->deadline;
			$this->view->setStage($xml->stage);
			$this->model->setConfig($xml->nwish, $xml->skill, $xml->admin, $xml->stage);
		}
	}

}
