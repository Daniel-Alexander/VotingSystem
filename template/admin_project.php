<div class="one"><!-- style="vertical-align:top;">-->
	<h2>Projekte</h2>
	<div class="one-third">
		<a href="redirect.php?page=project&subpage=new"><button class="linkBtn">Neues Projekt anlegen</button></a>
	</div>
	<br>
	<table>
		<thead>
			<tr>
				<th>Titel</th>
				<th>Abstarkt</th>
				<th>Betreuer</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php $this->model->startQuery('projects',0);
			while($row = $this->model->getRow())
			{
				echo "<tbody>
						<tr>
							<th>".$row["titel"]."</th>
							<th>".$row["abstract"]."</th>
							<th>";
							$teacher = $this->model->getTeacherByProject($row["order_id"]);
							foreach($teacher[0] as $name)
							{
								echo $name;
								echo ", ";
							}
							echo "</th>
							<th> <a href='redirect.php?page=project&subpage=show&page_id=".$row["project_id"]."'><button class='linkBtn'>Anzeigen</button></th>
						</tr>
					</tbody>";
			}
			?>
		</tbody>
	</table>
</div>