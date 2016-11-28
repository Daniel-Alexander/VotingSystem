<?php

class cDatabase
{
	private $connected = null;
	private $query = null;
	private $active_query = null;
	private $db = null;
	private $stmt = null;
	
	function __construct()
	{
		$this->connected = 0;
		$this->active_query = 0;
	}
	
	public function connect()
	{
		// TODO replace by secure functions
		// TODO check if connection was succesful
		if(!$this->connected)
		{
			mysql_connect("","root");
			mysql_select_db("votingsystem");
			$this->connected = 1;
		}
		// Prepared statements usage:
		//$this->db = new mysqli("","root","","votingsystem");
	}
	
	public function getTeacher($email, $hash, $admins)
	{
		if(!$this->connected)
			$this->connect();

		// TODO make this nicer and safer: use prepared statement
		// TODO use error statements
		$sql = "select * from teacher where email = '".$email."'";
		$result = mysql_query($sql) or die(mysql_error());
		
		// prepared statements usage:
		//$this->stmt = $this->db->prepare("SELECT * FROM teacher WHERE email = ?");
		//$this->stmt->bind_param('s', $email);
		//$this->stmt->execute();
		//$this->stmt->bind_result($teacher_id, $full_name, $email, $hash);
		//$this->stmt->fetch();

		if(mysql_num_rows($result) == 1 )
		{
			$row = mysql_fetch_assoc($result);
			
			// TODO use hashes here
			if($row["pw"] == $hash)
			{
				foreach($admins as $admin)
				{
					if($row["teacher_id"] == $admin)
						return array(2,$row["teacher_id"]);
				}
				return array(1,$row["teacher_id"]);
			}
			else return false;
		}
		return false;
	}
	
	public function getStudent($id)
	{
		if(!$this->connected)
			$this->connect();
		
		// TODO make this nicer and safer: use prepared statement
		// TODO use error statements
		$sql = "select * from student where crypt_id = '".$id."'";
		$result = mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($result) == 1 )
		{
			// TODO return something
			$row = mysql_fetch_assoc($result);
			return 1;
		}
		return false;
	}
	
	public function createNewTeacher($name, $email, $hash)
	{
		if(!$this->connected) 
			$this->connect();
		
		// INFO look if email exists
		// TODO use error code here
		$sql = "select teacher_id from teacher where email = '".$email."'";
		$result = mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($result) >= 1)
			return false;
		
		$sql = "insert teacher"
			 . "(full_name, email, pw) values "
			 . "('" . $name . "', "
			 . "'" . $email . "', "
			 . "'" . $hash . "')";
			 
		mysql_query($sql) or die(mysql_error());
		
		return true;
	}
	
	public function deleteTeacher($teacher_id)
	{
		// TODO return error code
		if(!$this->connected)
			$this->connect();
		
		$projects = $this->getTeacherProjects($teacher_id);
		
		if($projects !== false)
		{
			foreach($projects as $project_id)
			{
				$this->removeTeacher($project_id, array($teacher_id));
			}
		}
		
		$sql = "delete from teacher where teacher_id in ('$teacher_id')";
		$result = mysql_query($sql) or die(mysql_error());
	}
	
	public function getTeacherProjects($teacher_id)
	{
		// TODO return error code
		if(!$this->connected) 
			$this->connect();
		
		$sql = "select project_id from project_order where teacher1_id='".$teacher_id."'"
					." or teacher2_id='".$teacher_id."'"
					." or teacher3_id='".$teacher_id."'"
					." or teacher4_id='".$teacher_id."'"
					." or teacher5_id='".$teacher_id."'";
					
		$result = mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($result) >= 1 )
		{
			$projects = array();
			while($row = mysql_fetch_assoc($result))
			{
				array_push($projects, $row['project_id']);
			}
			return $projects;
		}
		return false;
	}
	
	public function insertNewStudent($name, $email, $field, $matr, $grade, $crypt)
	{
		// TODO use error code here
		if(!$this->connected) 
			$this->connect();
		
		// INFO look if email exists
		// TODO use error code here
		$sql = "select student_id from student where email = '".$email."'";
		$result = mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($result) >= 1)
			return false;
		
		$sql = "insert student"
			 . "(full_name, email, studyfield, degree, matrikulation, crypt_id) values "
			 . "('" . $name . "', "
			 . "'" . $email . "', "
			 . "'" . $field . "', "
			 . "'" . $grade . "', "
			 . "'" . $matr . "', "
			 . "'" . $crypt . "')";
			 
		mysql_query($sql) or die(mysql_error());
	}
	
	public function insertNewProject($titel, $keywords, $abstract, $description, $degree, $skills, $teacher_id)
	{
		// TODO use error code here
		if(!$this->connected) 
			$this->connect();
		
		$sql = "insert project_order (teacher1_id) values ('" . $teacher_id . "')";
		mysql_query($sql) or die(mysql_error());
		$order_id = mysql_insert_id();
		
		$sql = "insert projects"
			 . "(titel, keywords, abstract, description, degree, skills, order_id) values "
			 . "('" . $titel . "', "
			 . "'" . $keywords . "', "
			 . "'" . $abstract . "', "
			 . "'" . $description . "', "
			 . "'" . $degree . "', "
			 . "'" . $skills . "', "
			 . "'" . $order_id . "')";
			 
		mysql_query($sql) or die(mysql_error());
			 
		$project_id = mysql_insert_id();
		
		$sql = "UPDATE project_order SET project_id = '".$project_id."' WHERE order_id = '".$order_id."'";
		mysql_query($sql) or die(mysql_error());
		
	}
	
	public function getProject($project_id)
	{
		// TODO use error code here
		if(!$this->connected) 
			$this->connect();
		
		$sql = "select * from projects where project_id = '".$project_id."'";
		$result = mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($result) == 1 )
		{
			$row = mysql_fetch_assoc($result);
			return $row;
		}
		return false;
	}
	
	public function updateTeacher($id, $name, $email)
	{
		// TODO use error code here
		if(!$this->connected) 
			$this->connect();
		
		// INFO No need to change name in project because there only id is used
		
		$sql = "update teacher set full_name='".$name."', email='".$email."' where teacher_id = '".$id."'";
		mysql_query($sql) or die(mysql_error());

	}
	
	public function updateTeacherPw($id, $hash)
	{
		// TODO use error code here
		if(!$this->connected) 
			$this->connect();
		
		$sql = "update teacher set pw='".$hash."' where teacher_id = '".$id."'";
		mysql_query($sql) or die(mysql_error());
	}
	
	public function updateProject($id, $titel, $keywords, $abstract, $description, $degree, $skills)
	{
		// TODO use error code here
		if(!$this->connected) 
			$this->connect();
		
		$sql = "select order_id from project_order where project_id='".$id."'";
		$result = mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($result) == 1 )
		{		
			$row = mysql_fetch_assoc($result);
			$sql = "update projects set titel= '". $titel ."',
									keywords= '". $keywords ."',
									abstract= '". $abstract ."',
									description= '". $description ."',
									degree= '". $degree ."',
									skills= '". $skills ."',
									order_id= '". $row['order_id'] ."'
									where project_id='".$id."'";
									
			mysql_query($sql) or die(mysql_error());
		}
	}
	
	public function deleteProject($project_id)
	{
		// TODO use error code here
		if(!$this->connected) 
			$this->connect();
		
		$sql = "DELETE FROM projects WHERE project_id=".$project_id."";
		mysql_query($sql) or die(mysql_error());
		
		$sql = "DELETE FROM project_order WHERE project_id=".$project_id."";
		mysql_query($sql) or die(mysql_error());
	}
	
	public function getTeacherById($teacher_id)
	{
		// TODO return error code here
		if(!$this->connected) 
			$this->connect();
		
		$sql = "select * from teacher where teacher_id = '".$teacher_id."'";
		$result = mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($result) == 1 )
		{
			return mysql_fetch_assoc($result);
		}
		else return false;
	}
	
	public function getTeacherByProject($order_id)
	{
		// TODO return error code here
		if(!$this->connected) 
			$this->connect();
		
		$sql = "select * from project_order where order_id = '".$order_id."'";
		$result = mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($result) == 1 )
		{
			$row = mysql_fetch_assoc($result);
			
			$tchr = array(array(),array());
			if($row['teacher1_id'] != 0)
			{
				array_push($tchr[0],$this->getTeacherNameById($row['teacher1_id']));
				array_push($tchr[1],$row['teacher1_id']);
			}
			if($row['teacher2_id'] != 0)
			{
				array_push($tchr[0],$this->getTeacherNameById($row['teacher2_id']));
				array_push($tchr[1],$row['teacher2_id']);
			}
			if($row['teacher3_id'] != 0)
			{
				array_push($tchr[0],$this->getTeacherNameById($row['teacher3_id']));
				array_push($tchr[1],$row['teacher3_id']);
			}
			if($row['teacher4_id'] != 0)
			{
				array_push($tchr[0],$this->getTeacherNameById($row['teacher4_id']));
				array_push($tchr[1],$row['teacher4_id']);
			}
			if($row['teacher5_id'] != 0)
			{
				array_push($tchr[0],$this->getTeacherNameById($row['teacher5_id']));
				array_push($tchr[1],$row['teacher5_id']);
			}

			return $tchr;
		}
		return false;
	}
	
	public function getTeacherNameById($teacher_id)
	{
		// TODO return error code here
		if(!$this->connected) 
			$this->connect();
		
		$sql = "select full_name from teacher where teacher_id = '".$teacher_id."'";
		$result = mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($result) == 1 )
		{
			$row = mysql_fetch_assoc($result);
			return $row['full_name'];
		}
		return false;
	}
	
	public function removeTeacher($project_id, $teacher_ids)
	{
		// TODO return error code here
		if(!$this->connected) 
			$this->connect();
		
		$sql = "select * from project_order where project_id = '".$project_id."'";
		
		$result = mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($result) == 1 )
		{
			$row = mysql_fetch_assoc($result);
			$str = "update project_order set";
			
			$removed = false;
			
			foreach($teacher_ids as $cur_id)
			{
				if($row['teacher1_id'] == $cur_id)
				{
					$str = "$str teacher1_id='0',";
					$removed = true;
				}
				if($row['teacher2_id'] == $cur_id)
				{
					$str = "$str teacher2_id='0',";
					$removed = true;
				}
				if($row['teacher3_id'] == $cur_id)
				{
					$str = "$str teacher3_id='0',";
					$removed = true;
				}
				if($row['teacher4_id'] == $cur_id)
				{
					$str = "$str teacher4_id='0',";
					$removed = true;
				}
				if($row['teacher5_id'] == $cur_id)
				{
					$str = "$str teacher5_id='0',";
					$removed = true;
				}
			}
			
			if(!$removed)
				return false;
			
			$str = rtrim($str, ",");
			$str = "$str where project_id='".$project_id."'";
			mysql_query($str) or die(mysql_error());
			
			return true;
		}
	}
	
	public function projectAddTeacher($project_id, $teacher_id)
	{
		// TODO use error codes here
		if(!$this->connected) 
			$this->connect();
		
		$sql = "select * from project_order where project_id = '".$project_id."'";
		
		$result = mysql_query($sql) or die(mysql_error());
		
		if(mysql_num_rows($result) == 1 )
		{
			$row = mysql_fetch_assoc($result);
			
			if($row['teacher1_id'] == $teacher_id 
				or $row['teacher2_id'] == $teacher_id
				or $row['teacher3_id'] == $teacher_id
				or $row['teacher4_id'] == $teacher_id
				or $row['teacher5_id'] == $teacher_id)
					return false; // INFO Teacher is already on this project
			
			
			$str = "update project_order set";
			
			if($row['teacher1_id'] == 0)
				$str = "$str teacher1_id='".$teacher_id."'";
			elseif($row['teacher2_id'] == 0)
				$str = "$str teacher2_id='".$teacher_id."'";
			elseif($row['teacher3_id'] == 0)
				$str = "$str teacher3_id='".$teacher_id."'";
			elseif($row['teacher4_id'] == 0)
				$str = "$str teacher4_id='".$teacher_id."'";
			elseif($row['teacher5_id'] == 0)
				$str = "$str teacher5_id='".$teacher_id."'";
			else return false; // INFO Project has already 5 teachers
			
			$str = "$str where project_id='".$project_id."'";
			
			mysql_query($str) or die(mysql_error());
		}	
		
	}
	
	public function startQuery($type, $id)
	{
		// TODO return error code here
		if(!$this->connected)
			$this->connect();
		
		if($type === 'teacher')
		{
			$this->query = mysql_query('SELECT * FROM teacher');
			$this->active_query = 1;
		}
		
		if($type === 'projects')
		{
			if($id === 0)
			{
				$this->query = mysql_query('SELECT * FROM projects');
				$this->active_query = 1;
			}
			else
			{
				// TODO return specific projects here
			}
		}
	}
	
	public function getRow()
	{
		// TODO return error code here
		if($this->active_query)
		{

			if($row = mysql_fetch_assoc($this->query))
				return $row;
			else
			{
				$this->active_query = 0;
			}
		}
	}
}