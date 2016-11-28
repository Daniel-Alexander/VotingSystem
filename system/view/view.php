<?php

class cView
{
	private $path = 'template';  
    private $template = null;
	private $container = null;
	private $role = null;
	private $page = null;
	private $subpage = null;
	private $page_id = null;
	private $model = null;
	
	function __construct()
	{
		// TODO better start with error page here
		$this->template = 'aut_student';
		$this->container = 0;
		$this->role = 0;
		$this->page = 'main';
		$this->subpage = 'none';
		$this->page_id = 0;
	}
	
	public function setModel($model)
	{
		// TODO include set-check value
		$this->model = $model;
	}

	public function loadLogoutPage()
	{
		$this->container = 0;
		$this->template = 'logout';
	}
	
	public function loadAdminPage($page,$subpage,$page_id)
	{
		// TODO check if page exist
		$role = 2;
		$this->page = $page;
		$this->subpage = $subpage;
		$this->page_id = $page_id;
		$this->container = 1;
		$this->template = 'admin';	
	}
	
	public function loadTeacherPage($page)
	{
		// TODO check if page exist
		$role = 1;
		$this->page = $page;
		$this->container = 1;
		$this->template = 'teacher';
		
	}
	
	public function loadStudentPage($page)
	{
		// TODO check if page exist
		$role = 0;
		$this->page = $page;
		$this->container = 1;
		$this->template = 'student';
	}
	
	public function loadStartPage($role)
	{
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