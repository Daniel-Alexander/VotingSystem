<div class="one">
  <h2>Projektwünsche Bearbeiten</h2>
  <form action="redirect.php?page=student" method="post">
  <input type="hidden" value="<?php echo $this->page_id ?>" name="student_id">
  <table>
    <thead>
			<tr>
				<th>1. Wunsch</th>
        <?php
          if($nwish > 1) echo "<th>2. Wunsch</th>" ;
          if($nwish > 2) echo "<th>3. Wunsch</th>" ;
        ?>
				<th>Projekttitel</th>
				<th>Betreuer</th>
        <th></th>
			</tr>
		</thead>
    <tbody>
			<?php $this->model->startQuery('projects',0);
			while($row = $this->model->getRow())
			{
				echo "<tr><th><input type='radio' name='first_wish' value='".$row["project_id"]."'";
        if($cur_interests !== false)
        {
          if($cur_interests["project1_id"] == $row["project_id"]) echo " checked";
        }
        echo "></th>";
        if($nwish > 1)
        {
          echo "<th><input type='radio' name='second_wish' value='".$row["project_id"]."'";
          if($cur_interests !== false)
          {
            if($cur_interests["project2_id"] == $row["project_id"]) echo " checked";
          }
          echo "></th>";
        }
        if($nwish > 2)
        {
          echo "<th><input type='radio' name='third_wish' value='".$row["project_id"]."'";
          if($cur_interests !== false)
          {
            if($cur_interests["project3_id"] == $row["project_id"]) echo " checked";
          }
          echo "></th>" ;
        }
  			echo "<th>".$row["titel"]."</th>
  							<th>";
  							$teacher = $this->model->getTeacherByProject($row["order_id"]);
  							foreach($teacher[0] as $name)
  							{
  								echo $name;
  								echo ", ";
  							}
  							echo "</th>
  							<th> <a href='redirect.php?page=project&subpage=show&page_id=".$row["project_id"]."'><button type='button' class='linkBtn'>Anzeigen</button></th>
						  </tr>";
			}
			?>
		</tbody>
  </table>

<div class="one-third">
  <input type="submit" value="Speichern" name="student_save_interests">
</div>
</form>
</div>
