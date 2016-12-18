<div class="navbox">
	<button class="dropNav" onclick="toggleNavigation()">Menu</button>
	<nav class="navigation">
		<ul>
			<li>Navigation</li>

			<li><a href="redirect.php?page=project"><button class="navBtn">Projekte</button></a></li>
			<li><a href="redirect.php?page=teacher"><button class="navBtn">Teacher</button></a></li>
			<li><a href="redirect.php?page=student"><button class="navBtn">Studenten</button></a></li>
			<li><a href="redirect.php?page=voting"><button class="navBtn">Voting</button></a></li>
			<li><a href="redirect.php?page=data"><button class="navBtn">Daten</button></a></li>
			<li><a href="redirect.php?page=settings"><button class="navBtn">Settings</button></a></li>
			<li><a href="redirect.php?logout=1"><button class="navBtn">Logout</button></a></li>
		</ul>
			Rolle: <br>Administrator<br> Phase: <?php echo $this->getStageName();?>
	</nav>
</div>


<div class="container">
	<?php
	if(strcmp($this->page,'main') === 0 or strcmp($this->page,'project') === 0)
	{
			if(strcmp($this->subpage,'none') === 0)
			{
				include 'admin_project_none.php';
			}
			elseif(strcmp($this->subpage,'new') === 0)
			{
				include 'admin_project_new.php';
			}
			elseif(strcmp($this->subpage,'show') === 0)
			{
				$row = $this->model->getProjectById($this->page_id);
				$nwishes = $this->model->getNWish();
				if(!$row)
				{
					echo "<div class='errcontainer'>Error: call to non existing project id</div>";
				}
				else
				{
					include 'admin_project_show.php';
				}
			}
			elseif(strcmp($this->subpage,'edit') === 0)
			{
				$row = $this->model->getProjectById($this->page_id);
				if(!$row)
				{
					echo "<div class='errcontainer'>Error: call to non existing project id</div>";
				}
				else
				{
					include 'admin_project_edit.php';
				}
			}
			else
			{
				echo "<div class='errcontainer'> Error: Call to non existing subpage</div>";
				echo $this->subpage;
			}
		}
		elseif(strcmp($this->page,'teacher') === 0)
		{
			if(strcmp($this->subpage,'none') === 0)
			{
				include 'admin_teacher_none.php';
			}
			elseif(strcmp($this->subpage,'new') === 0)
			{
				include 'admin_teacher_new.php';
			}
			elseif(strcmp($this->subpage,'show') === 0)
			{
				$row = $this->model->getTeacherById($this->page_id);
				if(!$row)
				{
					echo "<div class='errcontainer'>Error: call to non existing page_id</div>";
				}
				else
				{
					include 'admin_teacher_show.php';
				}
			}
			elseif(strcmp($this->subpage,'edit') === 0)
			{
				$row = $this->model->getTeacherById($this->page_id);
				if(!$row)
				{
					echo "<div class='errcontainer'>Error: call to non existing page id</div>";
				}
				else
				{
					include 'admin_teacher_edit.php';
				}
			}
			else
			{
				echo "<div class='errcontainer'>Error: Call to non existing subpage</div>";
				echo $this->subpage;
			}
	 }
	 elseif(strcmp($this->page,'student') === 0)
	 {
			 if(strcmp($this->subpage,'none') === 0)
			 {
				 include 'admin_student_none.php';
			 }
			 elseif(strcmp($this->subpage,'show') === 0)
			 {
					$row = $this->model->getStudentById($this->page_id);
					if(!$row)
					{
						echo "<div class='errcontainer'>Error: call to non existing page_id</div>";
					}
					else
					{
						include 'admin_student_show.php';
					}
			 }
			 elseif(strcmp($this->subpage,'new') === 0)
			 {
				 include 'admin_student_new.php';
			 }
			 elseif(strcmp($this->subpage,'edit') === 0)
			 {
				 $row = $this->model->getStudentById($this->page_id);
				 if(!$row)
				 {
					 echo "<div class='errcontainer'>Error: call to non existing page id</div>";
				 }
				 else
				 {
					 include 'admin_student_edit.php';
				 }
			 }
			 elseif(strcmp($this->subpage,'interests') === 0)
			 {
				 $row = $this->model->getStudentById($this->page_id);
				 if(!$row)
				 {
					 	echo "<div class='errcontainer'>Error: call to non existing page id</div>";
				 }
				 else
				 {
					 $nwish = $this->model->getNWish();
					 $cur_interests = $this->model->getStudentInterests($this->page_id);
					 include 'admin_student_interests.php';
				 }
			 }
			 else
			 {
					echo "<div class='errcontainer'>Error: Call to non existing subpage</div>";
	 				echo $this->subpage;
			 }
	 }
   elseif(strcmp($this->page,'voting') === 0)
	 {
      $nwish = $this->model->getNWish();
     include 'admin_voting.php';
   }
	 elseif(strcmp($this->page,'data') === 0)
	 {
				$own_id = $_SESSION['current_id'];
				$row = $this->model->getTeacherById($own_id);

				if(!$row)
				{
					echo "<div class='errcontainer'>Error: call to non existing id</div>";
				}
				else
				{
					include 'admin_data.php';
				}
		}
		elseif(strcmp($this->page,'settings') === 0)
		{
			include 'admin_settings.php';
		}
		else
		{
			echo "<div class='errcontainer'>Error: Call to not existing page</div>";
	 	}
		?>

 </div>

 <script src="js/navigation.js"></script>
