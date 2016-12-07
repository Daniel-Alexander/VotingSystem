<div class="one">
	<h2>Studenten</h2>
	<div class="one-third">
		<a href="redirect.php?page=student&subpage=new"><button class="linkBtn">Neuer Student</button></a>
	</div>
	<br>
	<table>
		<thead>
			<tr>
				<th>Name</th>
				<th>Email</th>
				<th>Aktiv</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php $this->model->startQuery('students',0);
			while($row = $this->model->getRow())
			{
				echo "<tbody>
						<tr>
							<th>".$row["full_name"]."</th>
							<th>".$row["email"]."</th>
              <th>".$row["active"]."</th>
							<th>";

							echo "<a href='redirect.php?page=student&subpage=show&page_id=".$row['student_id']."'><button class='linkBtn'>Ansehen</button></a></th>
						</tr>
					</tbody>";
			}
			?>
		</tbody>
	</table>
</div>
