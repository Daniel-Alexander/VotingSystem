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
							<td>".$row["full_name"]."</td>
							<td>".$row["email"]."</td>";

				if($row["active"])
				{
					echo "<td><img src='style/img/active24.ico' alt='active'></td>";
				}
				else
				{
					echo "<td><img src='style/img/deactive24.ico' alt='active'></td>";
				}

							echo "<td><a href='redirect.php?page=student&subpage=show&page_id=".$row['student_id']."'><button class='linkBtn'>Ansehen</button></a></td>
						</tr>
					</tbody>";
			}
			?>
		</tbody>
	</table>
</div>
