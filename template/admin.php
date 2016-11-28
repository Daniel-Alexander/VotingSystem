
<nav>
	Main Menu<br>
	<a href="redirect.php?page=project"><button class="navBtn">Projekte</button></a><br>
	<a href="redirect.php?page=teacher"><button class="navBtn">Teacher</button></a><br>
	<a href="redirect.php?page=voting"><button class="navBtn">Voting</button></a><br>
	<a href="redirect.php?page=data"><button class="navBtn">Daten</button></a><br>
	<a href="redirect.php?page=settings"><button class="navBtn">Settings</button></a><br>
	<a href="redirect.php?logout=1"><button class="navBtn">Logout</button></a>

</nav>

<div class="container">
	<?php if(strcmp($this->page,'main') === 0 or strcmp($this->page,'project') === 0) { 
			if(strcmp($this->subpage,'none') === 0) { include 'admin_project.php' ?>
	
	
		
		
	
		<?php 
		} elseif(strcmp($this->subpage,'new') === 0) { ?>
			
		<div class="one">
			<form action="redirect.php?page=project" method="post">
				<div class="two-thirds">
					<h2>Neues Projekt anlegen</h2>
					<label for="id1">Titel</label>
					<input type="text" required name="project_titel" placeholder="max 400 Zeichen">
				</div>
				<br>
				<div class="two-thirds">
					<label for="id1">Abstrakt</label>
					<textarea name="project_abstract" required placeholder="max 600 Zeichen"></textarea>
				</div>
				<br>
				<div class="two-thirds">
					<label for="id1">Beschreibung</label>
					<textarea name="project_description" required placeholder="Beschreibung"></textarea>
				</div>
				<br>
				<div class="two-thirds">
					<label for="id1">Keywords</label>
					<input type="text" required name="project_keywords" placeholder="Keywords">
				</div>				
				<br>
				<div class="two-thirds">
					<label for="id1">Gewünschter Abschluss</label><br>
					<input type="checkbox" id="box_id" name="wanted_grade[]" value="Bsc"> B.sc.
					<input type="checkbox" id="box_id" name="wanted_grade[]" value="Msc"> M.sc.
				</div>
				<br>
				<div class="two-thirds">
					<label for="id1">Gewünschte Kenntnisse</label><br>
					<?php
						foreach($this->model->getSkills() as $skills)
						{
							echo "<input type='checkbox' value=".$skills->sign." name='wanted_skills[]'>".$skills->name."<br>";
						}	
					?>
				</div>
				<br>
				<div class="two-thirds">
					<input type="submit" value="Projekt erstellen" name="create_new_project">
				</div>
			</form>
		</div>
			
		<?php
			} elseif(strcmp($this->subpage,'show') === 0){ 
				$row = $this->model->getProjectById($this->page_id);		
				
				if(!$row)
				{
					echo "Error: call to non existing project id";
					
				}
				else
				{			
		?>	
				<div class="one" style="vertical-align:top;">
					<h2>Projekt <?php echo $row["titel"] ?></h2>
					<hr>

						<h1>Abstrakt</h1>
						<?php echo $row["abstract"] ?>
			
						<hr>
			
					
						<h1>Beschreibung</h1>
						<?php echo $row["description"] ?>
						<hr>
				
						<h1>Keywords</h1>
						<?php echo $row["keywords"] ?>
						<hr>
						<h1>Gewünschter Abschluss</h1>
						<?php echo $this->model->getDegreeById($row["degree"]) ?>
						<hr>
						<h1>Gewünschte Kenntnisse</h1>
						<?php echo $this->model->getSkillsById($row["skills"]) ?>
						<hr>
						<h1>Betreuer</h1>
					
						<?php
						$teacher = $this->model->getTeacherByProject($row["order_id"]);
						foreach($teacher[0] as $name)
						{
							echo $name;
							echo ", ";
						}
						?>
						<hr>
				
					<div class="one-half">
						<a href="redirect.php?page=project&subpage=edit&page_id=<?php echo $this->page_id ?>"><button class="linkBtn">Bearbeiten</button></a>
					</div>
					<div class="one-half">
						<form action="redirect.php?page=project" method="post">
							<input type="hidden" value="<?php echo $this->page_id ?>" name="project_to_delete">
							<input type="submit" value="Löschen" name="delete_project">
						</form>
					</div>
				</div>
			
		<?php
				}
			} elseif(strcmp($this->subpage,'edit') === 0) {
				
				$row = $this->model->getProjectById($this->page_id);		
				
				if(!$row)
				{
					echo "Error: call to non existing project id";
					
				}
				else
				{
		?>
			<div class="one">
				<form action="redirect.php?page=project" method="post">
					<div class="two-thirds">
						<h2>Projekt bearbeiten</h2>
						<input type="hidden" value="<?php echo $this->page_id ?>" name="project_id">
						<label for="id1">Titel</label>
						<input type="text" required name="project_titel" value="<?php echo $row['titel'] ?>" placeholder="max 400 Zeichen">
					</div>
					<br>
					<div class="two-thirds">
						<label for="id1">Abstrakt</label>
						<textarea name="project_abstract" required placeholder="max 600 Zeichen"><?php echo $row['abstract'] ?></textarea>
					</div>
					<br>
					<div class="two-thirds">
						<label for="id1">Beschreibung</label>
						<textarea name="project_description" required placeholder="Beschreibung"><?php echo $row['description'] ?></textarea>
					</div>
					<br>
					<div class="two-thirds">
						<label for="id1">Keywords</label>
						<input type="text" required name="project_keywords" value="<?php echo $row['keywords'] ?>" placeholder="Keywords">
					</div>				
					<br>
					<div class="two-thirds">
						<label for="id1">Gewünschter Abschluss</label><br>
						<input type="checkbox" id="box_id" name="wanted_grade[]" value="Bsc" <?php if($row['degree'] == 1 or $row['degree'] == 3) echo "checked" ?>> B.sc.
						<input type="checkbox" id="box_id" name="wanted_grade[]" value="Msc" <?php if($row['degree'] == 2 or $row['degree'] == 3) echo "checked" ?>> M.sc.
					</div>
					<br>
					<div class="two-thirds">
						<label for="id1">Gewünschte Kenntnisse</label><br>
						<?php
							foreach($this->model->getSkills() as $skills)
							{
								echo "<input type='checkbox' value=".$skills->sign." name='wanted_skills[]'";
								foreach(explode(";",$row["skills"]) as $skill)
								{
									if($skill == $skills->sign) echo "checked";
								}
								echo ">".$skills->name."<br>";
							}	
						?>
					</div>
					<br>
					<div class="two-thirds">
						<input type="submit" value="Bearbeitung speichern" name="edit_project">
					</div>					
				</form>
				<br>
				<div class="two-thirds">
				<h1>Betreuer</h1><br>
				<form action="redirect.php?page=project" method="post">
					<input type="hidden" value="<?php echo $this->page_id ?>" name="project_id"> 
					<?php 
					
						$teacher = $this->model->getTeacherByProject($row["order_id"]);
						$ind = 0;
						foreach($teacher[0] as $name)
						{
							echo $name;
							echo "<input type='checkbox' value=".$teacher[1][$ind]." name='teacher_to_remove[]'>";
							echo "<br>";
							$ind += 1;
						}

					?>
					<input type="submit" value="Betreuer entfernen" name="project_remove_teacher">
				</form>
				<br>
				Betreuer hinzufügen
				<form action="redirect.php?page=project" method="post">
					<input type="hidden" value="<?php echo $this->page_id ?>" name="project_id">
					<select name="teacher_to_add">
						<option disabled selected value>Bittel wählen</option>
						<?php $this->model->startQuery('teacher',0);
							while($row = $this->model->getRow())
							{
								echo "<option value='".$row['teacher_id']."'>".$row["full_name"]."</option>";
							}?>
					</select>
					<input type="submit" value="Betreuer hinzufügen" name="project_add_teacher">
				</form>
				</div>
			</div>
			
		<?php
				}
			} else {
				echo "Call to non existing subpage";
				echo $this->subpage;
			}
		} elseif(strcmp($this->page,'teacher') === 0) { 
			if(strcmp($this->subpage,'none') === 0) {
							
				
				
				?>
				
				<div class="one-half">
					<form action="redirect.php?page=teacher" method="post">
						<div class="two-thirds">
							<h2>Teacher</h2>
							<label for="id1">Name</label>
							<input type="text" required name="new_teacher_name" placeholder="Name">
						</div>
						<br>
						<div class="two-thirds">
							<label for="id1">E-Mail</label>
							<input type="text" required name="new_teacher_mail" placeholder="Mail">
						</div>
						<br>
						<div class="two-thirds">
							<label for="id1">Initiales Passwort</label>
							<input type="text" required name="new_teacher_pw" placeholder="Init Pw">
						</div>	
						<br>
						<div class="two-thirds">
							<input type="submit" value="Hinzufügen" name="create_new_teacher">
						</div>
					</form>
				</div>
				
				<div class="one-half" style="vertical-align:top;">
					<h2>Aktive Teacher</h2>
						<table>
							<thead>
								<tr>
								  <th>Name</th>
								  <th>Mail</th>
								  <th></th>
								</tr>
							</thead>
							
							<?php $this->model->startQuery('teacher',0);
								
								while($row = $this->model->getRow())
								{
									echo 	"<tbody>
												<tr>
													<th>".$row["full_name"]."</th>
													<th>".$row["email"]."</th>";
											if ($row["teacher_id"] == $_SESSION["current_id"])
											{
												echo "<th><a href='redirect.php?page=data'><button class='linkBtn'>Anzeigen</button></a></th>";
											}
											else
											{
												echo "<th><a href='redirect.php?page=teacher&subpage=show&page_id=".$row["teacher_id"]."'><button class='linkBtn'>Anzeigen</button></a></th>";
											}
									echo "</tr>
											</tbody>";
								}
							?>
							
						</table>
						<!--<input type="submit" value="Löschen" name="delete_teacher">-->
				</div>
				
				
				
				
				
				<?php
				
					
			} elseif(strcmp($this->subpage,'show') === 0) {
				
				$row = $this->model->getTeacherById($this->page_id);		
				
				if(!$row)
				{
					echo "Error: call to non existing page_id";
				}
				else
				{
				?>
				
					<div class="one">
					<h1>Name: </h1>
					<?php echo $row['full_name'] ?><br>
					<hr>
					<h1>Mail: </h1>
					<?php echo $row['email'] ?><br>
					<hr>
					<h1>Projekte</h1>
					
					<table>
						<?php
						
						$projects = $this->model->getTeacherProjects($this->page_id);
						
						if(!$projects)
						{
							echo "<tr>Teacher has no projects</tr>";
						}
						else
						{
							foreach($projects as $id)
							{
								$project = $this->model->getProjectById($id);
								echo "<tr><th>";
								echo $project['titel'];
								echo "</th>";
								echo "<th><a href='redirect.php?page=project&subpage=show&page_id=".$project["project_id"]."'><button class='linkBtn'>Anzeigen</button></a></th><tr>";
							}
						}
						
						?>
					</table>
					
					<div class="one-third">
						<a href='redirect.php?page=teacher&subpage=edit&page_id=<?php echo $row["teacher_id"] ?>'><button class='linkBtn'>Bearbeiten</button></a>
					</div>
					<div class="one-third">
						<form action="redirect.php?page=teacher" method="post">
							<input type="hidden" value="<?php echo $this->page_id ?>" name="teacher_to_delete">
							<input type="submit" value="Löschen" name="delete_teacher">
						</form>
					</div>
					</div>
				<?php
				
				}
				
			} elseif(strcmp($this->subpage,'edit') === 0) {
				
				$row = $this->model->getTeacherById($this->page_id);		
				
				if(!$row)
				{
					echo "Error: call to non existing page id";
				}
				else
				{
				?>
				
				<div class="one">
					<form action="redirect.php?page=teacher" method="post">
						<input type="hidden" value="<?php echo $this->page_id ?>" name="teacher_id">
						<div class="two-thirds">
							<h2>Teacher</h2>
							<label for="id1">Name</label>
							<input type="text" required name="edit_teacher_name" value="<?php echo $row["full_name"] ?>" placeholder="Name">
						</div>
						<br>
						<div class="two-thirds">
							<label for="id1">E-Mail</label>
							<input type="text" required name="edit_teacher_mail" value="<?php echo $row["email"] ?>" placeholder="Mail">
						</div>
						<div class="two-thirds">
							<input type="submit" value="Änderung speichern" name="edit_teacher">
						</div>
					</form>
					<hr>
					<form action="redirect.php?page=teacher" method="post">
						<input type="hidden" value="<?php echo $this->page_id ?>" name="teacher_id">
						<div class="two-thirds">
							<label for="id1">Neues Passwort</label>
							<input type="text" required name="edit_teacher_pw_1" placeholder="Passwort">
						</div>	
						<br>
						<div class="two-thirds">
							<label for="id1">Passwort wiederholen</label>
							<input type="text" required name="edit_teacher_pw_2" placeholder="Wiederholen">
						</div>
						<div class="two-thirds">
							<input type="submit" value="Passwort speichern" name="edit_teacher_pw">
						</div>
					</form>
				</div>
				
				
				
				
				<?php
				}
				
			} else {
				echo "Call to non existing subpage";
				echo $this->subpage;
			}
	 } elseif(strcmp($this->page,'data') === 0) { 

				$own_id = $_SESSION['current_id'];
				$row = $this->model->getTeacherById($own_id);		
				
				if(!$row)
				{
					echo "Error: call to non existing id";
				}
				else
				{
				?>
				
				<div class="one">
					<form action="redirect.php?page=teacher" method="post">
						<input type="hidden" value="<?php echo $own_id ?>" name="teacher_id">
						<div class="two-thirds">
							<h2>Eigene Daten ändern</h2>
							<label for="id1">Name</label>
							<input type="text" required name="edit_teacher_name" value="<?php echo $row["full_name"] ?>" placeholder="Name">
						</div>
						<br>
						<div class="two-thirds">
							<label for="id1">E-Mail</label>
							<input type="text" required name="edit_teacher_mail" value="<?php echo $row["email"] ?>" placeholder="Mail">
						</div>
						<div class="two-thirds">
							<input type="submit" value="Änderung speichern" name="edit_teacher">
						</div>
					</form>
					<hr>
					<form action="redirect.php?page=teacher" method="post">
						<input type="hidden" value="<?php echo $own_id ?>" name="teacher_id">
						<div class="two-thirds">
							<label for="id1">Neues Passwort</label>
							<input type="text" required name="edit_teacher_pw_1" placeholder="Passwort">
						</div>	
						<br>
						<div class="two-thirds">
							<label for="id1">Passwort wiederholen</label>
							<input type="text" required name="edit_teacher_pw_2" placeholder="Wiederholen">
						</div>
						<div class="two-thirds">
							<input type="submit" value="Passwort speichern" name="edit_teacher_pw">
						</div>
					</form>
				</div>	
	
	<?php 
				}
		} elseif(strcmp($this->page,'settings') === 0) {
			
			?>
			<div class="one">
				<h2>Settings</h2>
				<h1>Aktuelle Phase:</h1>
				<form action="redirect.php?page=settings" method="post">
				
				</form>
			</div>			
			<?php
			
			
		} else { ?>
	
		Error: Call to not existing page
	
	<?php } ?>
			

 </div>
