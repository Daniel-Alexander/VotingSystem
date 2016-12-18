<div class="two-thirds"  id="redips-drag"><!-- style="vertical-align:top;">-->
	<?php if($this->error) echo $this->errorhandle->getErrMsg(); ?>
	<h2>Voting</h2>
	<table>
		<thead>
			<tr>
				<th class='redips-mark'>Project</th>
				<th class='redips-mark'>1. Wunsch</th>
        <?php
          if($nwish > 1) echo "<th class='redips-mark'>2. Wunsch</th>" ;
          if($nwish > 2) echo "<th class='redips-mark'>3. Wunsch</th>" ;
        ?>

			</tr>
		</thead>
		<tbody>
			<?php
			$box_id = 1;

			$this->model->startQuery('projects',0);
			while($row = $this->model->getRow())
			{

				echo "<tbody>
						<tr>
							<td  class='redips-mark'>".$row["titel"]."</td>
							<td>";

        $students = $this->model->getStudentsByProject($row["project_id"],1);
				$max = sizeof($students[0]);
				for ($i=0; $i < $max; $i++)
				{
					echo "<div id='rdp_".$box_id."' class='redips-drag'><div id='".$students[0][$i]."'><div id='req_1'>".$students[1][$i]."</div></div></div>";
					$box_id = $box_id + 1;
				}
        echo "</td>";
        if($nwish > 1)
        {
          echo "<td>";
          $students = $this->model->getStudentsByProject($row["project_id"],2);
					$max = sizeof($students[0]);
					for ($i=0; $i < $max; $i++)
					{
						echo "<div id='rdp_".$box_id."' class='redips-drag'><div id='".$students[0][$i]."'><div id='req_2'>".$students[1][$i]."</div></div></div>";
						$box_id = $box_id + 1;
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
						echo "<div id='rdp_".$box_id."' class='redips-drag'><div id='".$students[0][$i]."'><div id='req_3'>".$students[1][$i]."</div></div></div>";
						$box_id = $box_id + 1;
					}
          echo "</td>";
        }
				echo "</tr></tbody>";
			}
			?>
		</tbody>
	</table>
</div>
