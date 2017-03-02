<?php

class cDatabase
{
	private $connected = null;
	private $query = null;
	private $active_query = null;
	private $db = null;
	private $stmt = null;
	private $db_host = null;
	private $db_username =  null;
	private $db_passwort = null;
	private $db_name = null;

	function __construct()
	{
		$this->connected = 0;
		$this->active_query = 0;

		//$this->db_host = "localhost";
		//$this->db_username =  "alpha-voting";
		//$this->db_passwort = "XXX";
		//$this->db_name = "alpha-voting";

		$this->db_host = "";
		$this->db_username =  "root";
		$this->db_passwort = "";
		$this->db_name = "votingsystem";

	}

	public function connect()
	{
		if(!$this->connected)
		{
			mysql_connect($this->db_host, $this->db_username, $this->db_passwort) or die ("Es konnte keine Verbindung hergestellt werden!");
			mysql_select_db($this->db_name);

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
			if(password_verify($hash, $row["pw"])) //($row["pw"] == $hash)
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

	public function getStudent($id, $toggle)
	{
		if(!$this->connected)
			$this->connect();

		// TODO make this nicer and safer: use prepared statement
		$sql = "select student_id from student where crypt_id = '".$id."'";
		$result = mysql_query($sql) or die(mysql_error());

		if(mysql_num_rows($result) == 1 )
		{
			$row = mysql_fetch_assoc($result);
			// TODO maybe dont do this everytime!
			if($toggle) $this->studentToggleActive($row['student_id'],1);
			return $row['student_id'];
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
			return true;

		$sql = "insert teacher"
			 . "(full_name, email, pw) values "
			 . "('" . $name . "', "
			 . "'" . $email . "', "
			 . "'" . $hash . "')";

		mysql_query($sql) or die(mysql_error());

		return false;
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
		mysql_query($sql) or die(mysql_error());
		return false;
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

	public function insertNewStudent($name, $email, $field, $matr, $grade, $skills, $crypt)
	{
		// TODO use error code here
		if(!$this->connected)
			$this->connect();

		// INFO look if email exists
		// TODO use error code here
		$sql = "select student_id from student where email = '".$email."'";
		$result = mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($result) >= 1)
			return true;

		$sql = "insert student"
			 . "(full_name, email, studyfield, degree, skills, matrikulation, crypt_id) values "
			 . "('" . $name . "', "
			 . "'" . $email . "', "
			 . "'" . $field . "', "
			 . "'" . $grade . "', "
			 . "'" . $skills . "', "
			 . "'" . $matr . "', "
			 . "'" . $crypt . "')";

		mysql_query($sql) or die(mysql_error());
	}

	public function updateStudent($student_id, $name, $email, $field, $matr, $grade, $skills)
	{
		// TODO use error code here
		if(!$this->connected)
			$this->connect();

		// TODO check if email exists!!
		$sql = "update student set full_name='".$name."',"
															." email='".$email."',"
															." studyfield='".$field."',"
															." matrikulation='".$matr."',"
															." degree='".$grade."',"
															." skills='".$skills."'"
															." where student_id = '".$student_id."'";

		mysql_query($sql) or die(mysql_error());
	}

	public function updateStudentInterests($student_id, $interest1, $interest2, $interest3)
	{
		// TODO use error code here
		if(!$this->connected)
			$this->connect();

		$sql = "select order_id from student_order where student_id = '".$student_id."'";
		$result = mysql_query($sql) or die(mysql_error());

		if(mysql_num_rows($result) !== 0)
		{
			$sql = "update student_order set project1_id='".$interest1."',"
																." project2_id='".$interest2."',"
																." project3_id='".$interest3."'"
																." where student_id = '".$student_id."'";
		}
		else
		{
			$sql = "insert student_order"
				 . "(student_id, project1_id, project2_id, project3_id) values "
				 . "('" . $student_id . "', "
				 . "'" . $interest1 . "', "
				 . "'" . $interest2 . "', "
				 . "'" . $interest3 . "')";
		}
		mysql_query($sql) or die(mysql_error());
	}

	public function getStudentById($student_id)
	{
		// TODO return error code here
		if(!$this->connected)
			$this->connect();

		$sql = "select * from student where student_id = '".$student_id."'";
		$result = mysql_query($sql) or die(mysql_error());

		if(mysql_num_rows($result) == 1 )
		{
			return mysql_fetch_assoc($result);
		}
		else return false;
	}

	public function getInterestsByStudent($student_id)
	{
		// TODO return error code here
		if(!$this->connected)
			$this->connect();

		$sql = "select * from student_order where student_id = '".$student_id."'";
		$result = mysql_query($sql) or die(mysql_error());

		if(mysql_num_rows($result) == 1 )
		{
			return mysql_fetch_assoc($result);
		}
		else return false;
	}

	public function studentToggleActive($student_id,$toggle)
	{
		// TODO return error code here
		if(!$this->connected)
			$this->connect();

		if ($toggle == 0)
		{
			$sql = "UPDATE student SET active='0' WHERE student_id='".$student_id."'";
		}
		elseif($toggle == 1)
		{
			$sql = "UPDATE student SET active='1' WHERE student_id='".$student_id."'";
		}
		elseif($toggle == 2)
		{
			$sql = "UPDATE student SET active=IF(active=1, 0, 1) WHERE student_id='".$student_id."'";
		}
		else
		{
			return true;
		}
		mysql_query($sql) or die(mysql_error());
		return false;
	}

	public function deactivateAllStudents()
	{
		// TODO return error code here
		if(!$this->connected)
			$this->connect();

		$sql = "UPDATE student SET active=0";
		mysql_query($sql) or die(mysql_error());

		return false;
	}

	public function deleteAllDeactive()
	{
		// TODO return error code here
		if(!$this->connected)
			$this->connect();

		$sql = "DELETE FROM student WHERE active=0";
		mysql_query($sql) or die(mysql_error());

		return false;
	}

	public function removeStudent($student_id)
	{
		// TODO return error code here
		if(!$this->connected)
			$this->connect();

			$sql = "delete from student where student_id='".$student_id."'";
			mysql_query($sql) or die(mysql_error());

			$sql = "delete from student_order where student_id='".$student_id."'";
			mysql_query($sql) or die(mysql_error());

			return false;
	}

	public function getStudentsByProject($project_id,$wish)
	{
		// TODO return error code here
		if(!$this->connected)
			$this->connect();

		$sql = "SELECT student.student_id, student.full_name FROM student INNER JOIN student_order ON student.student_id = student_order.student_id WHERE student_order.project".$wish."_id = '".$project_id."' AND student.active=1";
		$result = mysql_query($sql) or die(mysql_error());

		if(mysql_num_rows($result) > 0 )
		{
			$students = array(array(),array());
			while ($row = mysql_fetch_assoc($result))
			{
				array_push($students[0],$row["student_id"]);
				array_push($students[1],$row["full_name"]);
			}

			return $students;
			
		}

		else return false;
	}
	
	public function getStudentsAndMatrByProject($project_id)
	{
		if(!$this->connected)
			$this->connect();

		$sql = "SELECT student.full_name, student.matrikulation FROM student INNER JOIN student_order ON student.student_id = student_order.student_id WHERE student_order.project1_id = '".$project_id."' AND student.active=1";
		$result = mysql_query($sql) or die(mysql_error());

		if(mysql_num_rows($result) > 0 )
		{
			$students = array(array(),array());
			while ($row = mysql_fetch_assoc($result))
			{
				array_push($students[0],$row["full_name"]);
				array_push($students[1],$row["matrikulation"]);
			}

			return $students;	
		}
		else return false;
	}

	public function getStudentsWithoutRequest($nRequests)
	{
		// TODO return error code here
		if(!$this->connected)
			$this->connect();

			$sql = "SELECT student.student_id, student.full_name, '1' as request FROM student INNER JOIN student_order ON student.student_id = student_order.student_id WHERE (student_order.project1_id = '0' AND student.active=1)";

			if($nRequests > 1)
			{
				$sql = $sql."UNION ALL SELECT student.student_id, student.full_name, '2' as request FROM student INNER JOIN student_order ON student.student_id = student_order.student_id WHERE (student_order.project2_id = '0' AND student.active=1)";
			}

			if($nRequests > 2)
			{
				$sql = $sql."UNION ALL SELECT student.student_id, student.full_name, '3' as request FROM student INNER JOIN student_order ON student.student_id = student_order.student_id WHERE (student_order.project3_id = '0' AND student.active=1)";
			}

			$result = mysql_query($sql) or die(mysql_error());

			if(mysql_num_rows($result) > 0 )
			{
				$students = array(array(),array(),array());
				while ($row = mysql_fetch_assoc($result))
				{
					array_push($students[0],$row["student_id"]);
					array_push($students[1],$row["full_name"]);
					array_push($students[2],$row["request"]);
				}
				return $students;
			}
			return false;

	}

	public function updateVotingTable($table)
	{
		// TODO use error code here
		if(!$this->connected)
			$this->connect();

			$max = count($table);
			for ($i=0; $i < $max; $i++)
			{
				$sql = "UPDATE student_order SET project1_id = '".$table[$i][1]."', project2_id = '".$table[$i][2]."', project3_id = '".$table[$i][3]."' WHERE student_id = '".$table[$i][0]."'";
				mysql_query($sql) or die(mysql_error());
			}
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

	public function getProject($project_id,$auth_id)
	{
		// TODO use error code here
		if(!$this->connected)
			$this->connect();

		if($auth_id === true) // INFO Admin auth
		{
			$sql = "select * from projects where project_id = '".$project_id."'";
			$result = mysql_query($sql) or die(mysql_error());

			if(mysql_num_rows($result) == 1 )
			{
				$row = mysql_fetch_assoc($result);
				return $row;
			}
			return false;
		}
		else // INFO special teacher auth
		{
			$sql = "SELECT projects.* "
						."FROM projects "
						."INNER JOIN project_order "
						."ON project_order.project_id = projects.project_id "
						."WHERE projects.project_id = '".$project_id."'"
						."AND (project_order.teacher1_id = '".$auth_id."'"
						."OR project_order.teacher2_id = '".$auth_id."'"
						."OR project_order.teacher3_id = '".$auth_id."'"
						."OR project_order.teacher4_id = '".$auth_id."'"
						."OR project_order.teacher5_id = '".$auth_id."')";
			$result = mysql_query($sql) or die(mysql_error());

			if(mysql_num_rows($result) == 1 )
			{
				$row = mysql_fetch_assoc($result);
				return $row;
			}
			return false;
		}
	}

	public function updateTeacher($id, $name, $email)
	{
		// TODO use error code here
		if(!$this->connected)
			$this->connect();

		// INFO No need to change name in project because there only id is used
		// INFO look if email exists
		// TODO use error code here
		$sql = "select teacher_id from teacher where email = '".$email."'";
		$result = mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($result) >= 1)
		{
			while ($row = mysql_fetch_assoc($result))
			{
				if($row["teacher_id"] != $id)
					return true;
			}
		}

		$sql = "update teacher set full_name='".$name."', email='".$email."' where teacher_id = '".$id."'";
		mysql_query($sql) or die(mysql_error());
		return false;
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

		$sql = "UPDATE student_order SET project1_id= '0' WHERE project1_id=".$project_id."";
		mysql_query($sql) or die(mysql_error());
		$sql = "UPDATE student_order SET project2_id= '0' WHERE project2_id=".$project_id."";
		mysql_query($sql) or die(mysql_error());
		$sql = "UPDATE student_order SET project3_id= '0' WHERE project3_id=".$project_id."";
		mysql_query($sql) or die(mysql_error());

	}

	public function getTeacherById($teacher_id)	{
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
				return true;

			$str = rtrim($str, ",");
			$str = "$str where project_id='".$project_id."'";
			mysql_query($str) or die(mysql_error());

			return false;
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
			else return true; // INFO Project has already 5 teachers

			$str = "$str where project_id='".$project_id."'";

			mysql_query($str) or die(mysql_error());
		}

	}

	public function deleteSkillsBySign($skill_array, $translate)
	{
		// TODO return error code here
		if(!$this->connected)
			$this->connect();

		$sql = "select project_id, skills from projects";
		$result = mysql_query($sql) or die(mysql_error());

		while($row = mysql_fetch_assoc($result))
		{
			$str = $translate->removeSkillsFromStr($row["skills"], $skill_array);
			if(strcmp($str,$row["skills"]))
			{
				$sql = "update projects set skills='".$str."' where project_id = '".$row["project_id"]."'";
				mysql_query($sql) or die(mysql_error());
			}
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
				$sql = "SELECT projects.* "
							."FROM projects "
							."INNER JOIN project_order "
							."ON project_order.project_id = projects.project_id "
							."WHERE project_order.teacher1_id = '".$id."'"
							."OR project_order.teacher2_id = '".$id."'"
							."OR project_order.teacher3_id = '".$id."'"
							."OR project_order.teacher4_id = '".$id."'"
							."OR project_order.teacher5_id = '".$id."'";
				$this->query = mysql_query($sql);
				$this->active_query = 1;
			}
		}

		if($type === 'students')
		{
			$this->query = mysql_query('SELECT * FROM student');
			$this->active_query = 1;
		}

		if($type === 'first_interests')
		{
			$this->query = mysql_query("SELECT * FROM student_order where project1_id='".$id."'");
			$this->active_query = 1;
		}

		if($type === 'second_interests')
		{
			$this->query = mysql_query("SELECT * FROM student_order where project2_id='".$id."'");
			$this->active_query = 1;
		}

		if($type === 'third_interests')
		{
			$this->query = mysql_query("SELECT * FROM student_order where project3_id='".$id."'");
			$this->active_query = 1;
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
