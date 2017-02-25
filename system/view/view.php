<?php

class cView
{
	private $path = 'template';
	private $error = false;
	private $success = false;
  private $template = null;
	private $container = null;
	private $role = null;
	private $page = null;
	private $subpage = null;
	private $page_id = null;
	private $stage = null;
	private $model = null;
	private $deleted_page = null;
	private $url = null;

	function __construct($model,$errorhandle)
	{
		$this->model = $model;
		$this->errorhandle = $errorhandle;
		$this->template = 'aut_student';
		$this->container = 0;
		$this->role = 0;
		$this->page = 'main';
		$this->subpage = 'none';
		$this->page_id = 0;
		$this->stage = 0;
		$this->deleted_page = false;
		$this->url = "http://alpha-voting.bplaced.net/";
	}

	public function setStage($stage)
	{
		$this->stage = $stage;
	}

	public function getStageName()
	{
		switch($this->stage)
		{
			case 1:
				return "Set up";
				break;
			case 2:
				return "Sign in";
				break;
			case 3:
				return "Sign in finished";
				break;
			case 4:
				return "Voting";
				break;
			case 5:
				return "Assignment";
				break;
			case 6:
				return "Post Assignment";
				break;
			default:
				return "UNKNOWN";
				break;
		}
		return "UNKNOWN";
	}

	public function loadLogoutPage($deleted)
	{
		if ($deleted === true) $this->deleted_page = true;
		$this->container = 0;
		$this->template = 'logout';
	}

	public function loadAdminPage($page,$subpage,$page_id,$error)
	{
		$role = 2;
		$this->error = $error;
		$this->container = 1;
		$this->template = 'admin';

		if(!$error)
		{
			$this->page = $page;
			$this->subpage = $subpage;
			$this->page_id = $page_id;
		}
		else
		{
			$this->page = $this->errorhandle->getPage();
			$this->subpage = $this->errorhandle->getSubpage();
			$this->page_id = $this->errorhandle->getPageId();
		}

	}

	public function loadTeacherPage($page,$subpage,$page_id,$error)
	{
		// TODO check if page exist
		$role = 1;
		$this->error = $error;
		$this->container = 1;
		$this->template = 'teacher';

		if(!$error)
		{
			$this->page = $page;
			$this->subpage = $subpage;
			$this->page_id = $page_id;
		}
		else
		{
			$this->page = $this->errorhandle->getPage();
			$this->subpage = $this->errorhandle->getSubpage();
			$this->page_id = $this->errorhandle->getPageId();
		}

	}

	public function loadStudentPage($page,$subpage,$page_id,$error)
	{
		// TODO check if page exist
		$role = 0;
		$this->error = $error;
		$this->container = 1;
		$this->template = 'student';

		if(!$error)
		{
			$this->page = $page;
			$this->subpage = $subpage;
			$this->page_id = $page_id;
		}
		else
		{
			$this->page = $this->errorhandle->getPage();
			$this->subpage = $this->errorhandle->getSubpage();
			$this->page_id = $this->errorhandle->getPageId();
		}
	}

	public function loadStartPage($role,$error)
	{
		$this->error = $error;
		if(strcmp($role,'student') == 0)
		{
			$this->template = 'aut_student';
			return 1;
		}
		if(strcmp($role,'teacher') == 0)
		{
			$this->template = 'aut_teacher';
			return 1;
		}
		return 0;
	}

	public function SetSignedInProperty()
	{
		$this->success = true;
	}

	public function loadTemplate()
	{
        $tpl = $this->template;

		if($this->container)
		{
			$head = $this->path . DIRECTORY_SEPARATOR . 'head.php';
			$foot = $this->path . DIRECTORY_SEPARATOR . 'foot.php';
		}

        $body = $this->path . DIRECTORY_SEPARATOR . $tpl . '.php';
        $exists = file_exists($body);

        if ($exists)
		{
            ob_start();

			if($this->container)
			{
				include $head;
				include $body;
				include $foot;
			}
			else
			{
				include $body;
			}

            $output = ob_get_contents();

            ob_end_clean();

            return $output;
        }
        else
		{
			// TODO Load Error not exist page here
            return 'could not find template';
        }

	}
}
