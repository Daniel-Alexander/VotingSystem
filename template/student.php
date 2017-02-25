
<div class="navbox">
	<button class="dropNav" onclick="toggleNavigation()">Menu</button>
	<nav class="navigation">
		<ul>
			<li class="head">Navigation</li>

			<li><a href="redirect.php?page=project"><button class="navBtn">Projekte</button></a></li>
			<li><a href="redirect.php?page=voting"><button class="navBtn">Voting</button></a></li>
			<li><a href="redirect.php?page=data"><button class="navBtn">Daten</button></a></li>
			<li><a href="redirect.php?logout=1"><button class="navBtn">Ausloggen</button></a></li>

			<li class="foot">Rolle: <br>Student<br> Phase: <?php echo $this->getStageName();?></li>
		</ul>
</nav>
</div>

<div class="container">

	<?php
	if(strcmp($this->page,'main') === 0 or strcmp($this->page,'project') === 0)
	{
			if(strcmp($this->subpage,'none') === 0)
			{
				if($this->stage == 1)
				{
					echo "<div class='one'><div class='infoBox'>Aktuell sind keine Aktionen möglich</div></div>";
				}
				elseif($this->stage == 2 or $this->stage == 3 or $this->stage == 4)
				{
					$nwish = $this->model->getNWish();
					$cur_interests = $this->model->getStudentInterests($this->model->getStudentId());//$_SESSION['student_id']);

					include 'student_project_none.php';
				}
				elseif($this->stage == 5 or $this->stage == 6)
				{
					$nwish = $this->model->getNWish();
					$cur_interests = $this->model->getStudentInterests($this->model->getStudentId());//$_SESSION['student_id']);

					include 'student_assigned.php';
				}
			}
			elseif(strcmp($this->subpage,'show') === 0 && $this->stage != 1)
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
	elseif(strcmp($this->page,'voting') === 0)
	{
		if($this->stage == 4)
		{
			$nwish = $this->model->getNWish();
			$cur_interests = $this->model->getStudentInterests($this->model->getStudentId());//$_SESSION['student_id']);
			include 'student_voting.php';
		}
		else
		{
			echo "<div class='one-half'><h2>Voting nicht freigeschaltet</h2><div class='infobox'>Das Voting findet wärend der Einführungsveranstaltung statt</div></div>";
		}
	}
	elseif(strcmp($this->page,'data') === 0)
	{
		$row = $this->model->getStudentById($this->model->getStudentId());//$_SESSION['student_id']);
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
