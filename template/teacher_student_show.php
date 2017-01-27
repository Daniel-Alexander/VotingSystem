<div class="one">
<h1>Name: </h1>
<?php echo $row['full_name'] ?><br>
<hr>
<h1>Matrikelnummer: </h1>
<?php echo $row['matrikulation'] ?><br>
<hr>
<h1>E-Mail Adresse: </h1>
<?php echo $row['email'] ?><br>
<hr>
<h1>Studiengang: </h1>
<?php echo $row['studyfield'] ?><br>
<hr>
<h1>Angestrebter Abschluss: </h1>
<?php echo $this->model->getDegreeById($row["degree"]) ?>
<hr>
<h1>Kenntnisse: </h1>
<?php
  $skills = $this->model->getSkillsById($row["skills"]);
  if(!$skills)
  {
    echo "Noch keine Fähigkeiten hinterlegt";
  }
  else
  {
    echo $skills;
  }
?>
<hr>
<div class="one-half">
<h1>Interessiert an: </h1>
<table><tbody>
<?php
$projects = $this->model->getStudentInterests($this->page_id);
if(!$projects)
{
  echo "<tr><td>Noch keine Interessen hinterlegt</td></tr>";
}
else
{
  if($projects["project1_id"] != 0)
  {
    echo "<tr><td>1. Wunsch</td>";
    $prj = $this->model->getProjectById($projects["project1_id"]);
    echo "<td>".$prj["titel"]."</td>";
    echo "</tr>";
  }
  if($projects["project2_id"] != 0)
  {
    echo "<tr><td>2. Wunsch</td>";
    $prj = $this->model->getProjectById($projects["project2_id"]);
    echo "<td>".$prj["titel"]."</td>";
    echo "</tr>";
  }
  if($projects["project3_id"] != 0)
  {
    echo "<tr><td>3. Wunsch</td>";
    $prj = $this->model->getProjectById($projects["project3_id"]);
    echo "<td>".$prj["titel"]."</td>";
    echo "</tr>";
  }
}
?>
</tbody></table>
</div>
</div>
