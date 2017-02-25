<div class="one" id="redips-drag">
<?php if($this->stage < 4 or $this->stage > 6) echo "<div class='infoBox'>In der derzeitigen Phase kann nur gespeichert werden</div>";?>
<?php if($this->stage == 4) echo "<div class='infoBox'>Voting läuft...</div>";?>

<div class="two-thirds"><!-- style="vertical-align:top;">-->

	<?php if($this->error) echo $this->errorhandle->getErrMsg(); ?>

	<h2>Voting</h2>
	<table id="table">
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

				echo "<!--tbody-->
						<tr  id='".$row["project_id"]."' class='project'>
							<td class='redips-mark'>".$row["titel"]."</td>
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
				echo "</tr><!--/tbody-->";
			}
			?>
		</tbody>
	</table>
</div>

<?php
$students_on_stack = $this->model->getStudentsWithoutRequest($nwish);
?>

<div class="one-third">
<h2>_</h2>
	<table id="stack">
		<thead><tr><th class='redips-mark'>Nicht zugeordnet</th></tr></thead>
		<tbody>

			<?php
			echo "<tr><td>";
			$max = sizeof($students_on_stack[0]);
			for ($i=0; $i < $max; $i++)
			{
				echo "<div id='rdp_".$box_id."' class='redips-drag'><div id='".$students_on_stack[0][$i]."'><div id='req_".$students_on_stack[2][$i]."'>".$students_on_stack[1][$i]."</div></div></div>";
				$box_id = $box_id + 1;
			}
			echo "</td></tr>";
			?>
		</tbody>
	</table>
</div>

<div class='one-third'>
	<a href='redirect.php?page=voting' onclick='send_voting_table(false)'><button class="linkBtn">Speichern</button></a>
	<!--input type="submit" value="Abschließen" name="create_new_project"-->
</div>

<?php
if($this->stage == 4)
{ ?>
<div class='one-third'>
	<form action="redirect.php?page=voting" method="post">
    <input type="submit" value="Voting beenden" name="to_voting_stage">
  </form>
</div>
<?php }
if($this->stage == 5)
{
	?>
	<div class='one-third'>
		<a href='redirect.php?page=voting' onclick='send_voting_table(true)'><button class="linkBtn" >Assignment abschließen</button></a>
		<!--input type="submit" value="Abschließen" name="create_new_project"-->
	</div>
	<?php
}
if($this->stage == 6)
{
	?>
	<div class='one-third'>
		<a href='redirect.php?page=voting' onclick='send_voting_table(true)'><button class="linkBtn" >Erneut abschließen</button></a>
		<!--input type="submit" value="Abschließen" name="create_new_project"-->
	</div>
	<?php
}
?>
</div>
