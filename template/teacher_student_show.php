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
<h1>Interessiert an: </h1>

<?php
$projects = $this->model->getStudentInterests($this->page_id);
if(!$projects)
{
  echo "<tr>Noch keine Interessen hinterlegt</tr>";
}
else
{
  if($projects["project1_id"] != 0)
  {
    echo "1. Wunsch: ";
    $prj = $this->model->getProjectById($projects["project1_id"]);
    echo $prj["titel"];
    echo $projects["project1_id"];
    echo "<br>";
  }
  if($projects["project2_id"] != 0)
  {
    echo "2. Wunsch: ";
    $prj = $this->model->getProjectById($projects["project2_id"]);
    echo $prj["titel"];
    echo $projects["project2_id"];
    echo "<br>";
  }
  if($projects["project3_id"] != 0)
  {
    echo "3. Wunsch: ";
    $prj = $this->model->getProjectById($projects["project3_id"]);
    echo $prj["titel"];
  }
}
?>
</div>
