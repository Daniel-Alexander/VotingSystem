
<div class="navbox">
	<button class="dropNav" onclick="toggleNavigation()">Menu</button>
	<nav class="navigation">
		<ul>
			<li>Navigation</li>

			<li><a href="redirect.php?page=project"><button class="navBtn">Projekte</button></a></li>
			<li><a href="redirect.php?page=data"><button class="navBtn">Daten</button></a></li>
			<li><a href="redirect.php?logout=1"><button class="navBtn">Ausloggen</button></a></li>
		</ul>
		Rolle: <br>Student<br> Phase: <?php echo $this->getStageName();?>
</nav>
</div>

<div class="container">

	<?php
	if(strcmp($this->page,'main') === 0 or strcmp($this->page,'project') === 0)
	{
		if(strcmp($this->subpage,'none') === 0)
		{
			$nwish = $this->model->getNWish();
			$cur_interests = $this->model->getStudentInterests($_SESSION['student_id']);

			include 'student_project_none.php';
		}
		elseif(strcmp($this->subpage,'show') === 0)
		{
			$row = $this->model->getProjectById($this->page_id);
			$nwishes = $this->model->getNWish();
			if(!$row)
			{
				echo "Error: call to non existing project id";
			}
			else
			{
				include 'student_project_show.php';
			}
		}
		else
		{
			echo "Error: Call to non existing subpage: ";
			echo $this->subpage;
		}

	}
	elseif(strcmp($this->page,'data') === 0)
	{
		$row = $this->model->getStudentById($_SESSION['student_id']);
		if(!$row)
		{
			echo "Error: call to non existing page_id";
		}
		else
		{
			include 'student_data.php';
		}

	} else { ?>

		Error: Call to not existing page

	<?php } ?>

 </div>

  <script src="js/navigation.js"></script>
