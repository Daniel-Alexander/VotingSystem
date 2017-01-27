<div class="one"><!-- style="vertical-align:top;">-->
	<?php if($this->error) echo $this->errorhandle->getErrMsg(); ?>
	<h2>Voting</h2>
	<table>
		<thead>
			<tr>
				<th>Project</th>
				<th>1. Wunsch</th>
        <?php
          if($nwish > 1) echo "<th>2. Wunsch</th>" ;
          if($nwish > 2) echo "<th>3. Wunsch</th>" ;
        ?>
			</tr>
		</thead>
		<tbody>
			<?php $this->model->startQuery('projects',$_SESSION['current_id']);
			while($row = $this->model->getRow())
			{
				echo "<tbody>
						<tr>
							<td>".$row["titel"]."</td>
							<td>";
        $students = $this->model->getStudentsByProject($row["project_id"],1);
        $max = sizeof($students[0]);
        for ($i=0; $i < $max; $i++)
        {
          echo "<a href='redirect.php?page=student&page_id=".$students[0][$i]."'>".$students[1][$i]."</a> ";
        }
        echo "</td>";

        if($nwish > 1)
        {
          echo "<td>";
          $students = $this->model->getStudentsByProject($row["project_id"],2);
          $max = sizeof($students[0]);

          for ($i=0; $i < $max; $i++)
          {
            echo "<a href='redirect.php?page=student&page_id=".$students[0][$i]."'>".$students[1][$i]."</a> ";
          }

          echo "</td>";
        }
        if($nwish > 2)
        {
          echo "<td>";
          $students = $this->model->getStudentsByProject($row["project_id"],3);
          $max = sizeof($students[0]);

          for ($i=0; $i < $max; $i++)
          {
            echo "<a href='redirect.php?page=student&page_id=".$students[0][$i]."'>".$students[1][$i]."</a> ";
          }

          echo "</td>";
        }
        echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>
