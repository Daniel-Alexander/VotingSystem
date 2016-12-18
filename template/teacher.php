<div class="navbox">
	<button class="dropNav" onclick="toggleNavigation()">Menu</button>
	<nav class="navigation">
		<ul>
			<li>Navigation</li>

			<li><a href="redirect.php?page=project"><button class="navBtn">Projekte</button></a></li>
			<li><a href="redirect.php?page=data"><button class="navBtn">Daten</button></a></li>
			<li><a href="redirect.php?logout=1"><button class="navBtn">Ausloggen</button></a></li>
		</ul>
			Rolle: <br>Betreuer<br> Phase: <?php echo $this->getStageName();?>
		</nav>
</div>

<div class="container">

	<?php
	if(strcmp($this->page,'main') === 0 or strcmp($this->page,'project') === 0)
	{
		if(strcmp($this->subpage,'none') === 0)
		{
			include 'teacher_project_none.php';
		}
		elseif(strcmp($this->subpage,'new') === 0)
		{
			include 'teacher_project_new.php';
		}
		elseif(strcmp($this->subpage,'show') === 0)
		{
			$row = $this->model->getProjectByAuth($this->page_id,$_SESSION['current_id']);
			$nwishes = $this->model->getNWish();
			if(!$row)
			{
				echo "Error: call to non existing project id";
			}
			else
			{
				include 'teacher_project_show.php';
			}
		}
		elseif(strcmp($this->subpage,'edit') === 0)
		{
			$row = $this->model->getProjectByAuth($this->page_id,$_SESSION['current_id']);
			if(!$row)
			{
				echo "Error: call to non existing project id";
			}
			else
			{
				include 'teacher_project_edit.php';
			}
		}

		else
		{
			echo "Call to non existing subpage";
			echo $this->subpage;
		}
 	}
	elseif(strcmp($this->page,'student') === 0)
	{
		$row = $this->model->getStudentById($this->page_id);
		if(!$row)
		{
			echo "Error: call to non existing page_id";
		}
		else
		{
			include 'teacher_student_show.php';
		}
	}
	// TODO teacher comes from errorhandler fix this in there!!
	elseif(strcmp($this->page,'data') === 0 or strcmp($this->page,'teacher') === 0 )
	{
		$own_id = $_SESSION['current_id'];
		$row = $this->model->getTeacherById($own_id);

		if(!$row)
		{
			echo "Error: call to non existing id";
		}
		else
		{
			include 'teacher_data.php';
		}

	} else { ?>

		Error: Call to not existing page

	<?php } ?>

 </div>

  <script src="js/navigation.js"></script>
