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
		$this->view = new cView($this->errorhandle);
		$this->model = new cModel($this->errorhandle);
		$this->getConfig();
	}

	function redirectSession()
	{
		if(isset($this->get['logout']))
		{
			// TODO replace this by secure function
			session_destroy();
			$this->view->loadLogoutPage();
			return;
		}

		// TODO View only gets a model if nessesary. Is that the best way?
		$this->view->setModel($this->model);

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
				break;
		}
	}

	function redirectOutside()
	{
		if(isset($this->get['token']))
		{
			// INFO token gets validated in model
			$student_id = $this->studentLogin();

			if($student_id !== false)
			{
				// TODO replace this by secure function
				session_start();
				$_SESSION['valid'] = 1;
				$_SESSION['role'] = 0;
				$_SESSION['student_id'] = $student_id;

				// TODO set active here!

				header('Location: redirect.php');
			}
		}

		if(isset($this->post['create_new_student']))
		{
			// TODO check validness
			$this->createNewStudent();
		}

		if(isset($this->post['teacher_login']))
		{
			$stage = $this->teacherLogin();
			if(!$stage)
			{
				return false;
			}
			switch ($stage[0])
			{
				case 2: // Admin
				case 1: // teacher
					// TODO replace this by secure function
					session_start();
					$_SESSION['valid'] = 1;

					// TODO check if Admin already logged in?
					$_SESSION['role'] = $stage[0];
					$_SESSION['current_id'] = $stage[1];
					header('Location: redirect.php');

					break;
				case 0: // fail
				default:
				// TODO redirect to error page here: Err: Login failed
					break;
			}
		}

		$role = 'student';
		if(isset($this->get['role']))
			$role = $this->get['role'];

		$valid = $this->view->loadStartPage($role);

		if(!$valid)
		{
			// TODO Include Error handling here
			echo 'Error: undefined role';
		}

	}

	function display()
	{
		return $this->view->loadTemplate();
	}

	private function createNewStudent()
	{
		// TODO check and set inputs here

		$name = $this->post['student_name'];
		$mail = $this->post['student_mail'];

		//TODO get this from formular
		$field = "Informatik";

		$matr = $this->post['student_matr'];

		// TODO transfere grade here
		$grade = 1; //$this->post['student_grade'];

		$this->model->createNewStudent($name, $mail, $field, $matr, $grade);
		return 1;
	}

	private function teacherLogin()
	{
		// TODO check teacher parameter here return 0 for fail 1 for teacher 2 for admin
		$mail = $this->post['teacher_mail'];
		$pw = $this->post['teacher_pw'];
		return $this->model->getTeacherProperties($mail, $pw);
	}

	private function studentLogin()
	{
		// TODO what is not todo here
		return $this->model->getStudentByCrypt($this->get['token']);
	}

	private function getConfig()
	{
		if (file_exists('system\config\config.xml'))
		{
			$xml = simplexml_load_file('system\config\config.xml');
		}
		// TODO return fatal error if file not exist
		$this->stage = $xml->stage;
		$this->deadline = $xml->deadline;
		$this->view->setStage($xml->stage);
		$this->model->setConfig($xml->nwish, $xml->skill, $xml->admin);
	}

}
