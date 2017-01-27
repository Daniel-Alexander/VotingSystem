<div class="one">
  <h2>Voting</h2>
  <form action="redirect.php?page=voting" method="post">
  <table>
    <thead>
			<tr>
				<th>1</th>
        <?php
          if($nwish > 1) echo "<th>2</th>" ;
          if($nwish > 2) echo "<th>3</th>" ;
        ?>
				<th>Projekttitel</th>
			</tr>
		</thead>
    <tbody>
			<?php $this->model->startQuery('projects',0);
			while($row = $this->model->getRow())
			{
				echo "<tr><td class='radiofield'><input type='radio' name='first_wish' value='".$row["project_id"]."'";
        if($cur_interests !== false)
        {
          if($cur_interests["project1_id"] == $row["project_id"]) echo " checked";
        }
        echo "></td>";
        if($nwish > 1)
        {
          echo "<td class='radiofield'><input type='radio' name='second_wish' value='".$row["project_id"]."'";
          if($cur_interests !== false)
          {
            if($cur_interests["project2_id"] == $row["project_id"]) echo " checked";
          }
          echo "></td>";
        }
        if($nwish > 2)
        {
          echo "<td class='radiofield'><input type='radio' name='third_wish' value='".$row["project_id"]."'";
          if($cur_interests !== false)
          {
            if($cur_interests["project3_id"] == $row["project_id"]) echo " checked";
          }
          echo "></td>" ;
        }
  			echo "<td>".$row["titel"]."</td></tr>";
			}
			?>
		</tbody>
  </table>
  <div class="one-third">
    <input type="submit" value="Abstimmen" name="student_vote">
  </div>
</form>
</div>
