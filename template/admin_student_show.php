<div class="one">
<h1>Name: </h1>
<?php echo $row['full_name'] ?><br>
<hr>
<h1>Aktiv: </h1>
<?php echo $row['active'] ?><br>
<form action="redirect.php?page=student&subpage=show&page_id=<?php echo $this->page_id; ?>" method="post">
  <input type="hidden" value="<?php echo $this->page_id ?>" name="student_to_toggle">
  <input type="submit" value="Aktivieren" name="student_toggle_active">
</form>
<hr>
<h1>Matrikelnummer: </h1>
<?php echo $row['matrikulation'] ?><br>
<hr>
<h1>E-Mail Adresse: </h1>
<?php echo $row['email'] ?><br>
<hr>
<h1>Link: </h1>
<a href='index.php?token=<?php echo $row['crypt_id'] ?>'><u>
localhost/voting/index.php?token=<?php echo $row['crypt_id'] ?></u>
</a><br>
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
    echo "<a href='redirect.php?page=project&subpage=show&page_id=".$projects['project1_id']."'><button class='linkBtn'>Anzeigen</button></a>";
    echo "<br>";
  }
  if($projects["project2_id"] != 0)
  {
    echo "2. Wunsch: ";
    $prj = $this->model->getProjectById($projects["project2_id"]);
    echo $prj["titel"];
    echo $projects["project2_id"];
    echo "<a href='redirect.php?page=project&subpage=show&page_id=".$projects['project2_id']."'><button class='linkBtn'>Anzeigen</button></a>";
    echo "<br>";
  }
  if($projects["project3_id"] != 0)
  {
    echo "3. Wunsch: ";
    $prj = $this->model->getProjectById($projects["project3_id"]);
    echo $prj["titel"];
    echo "<a href='redirect.php?page=project&subpage=show&page_id=".$projects['project3_id']."'><button class='linkBtn'>Anzeigen</button></a>";
  }
}
?>
<div class="one-third">
  <a href='redirect.php?page=student&subpage=interests&page_id=<?php echo $row["student_id"] ?>'><button class='linkBtn'>Wünsche Bearbeiten</button></a>
</div>
<div class="one-third">
  <a href='redirect.php?page=student&subpage=edit&page_id=<?php echo $row["student_id"] ?>'><button class='linkBtn'>Bearbeiten</button></a>
</div>
<div class="one-third">
  <form action="redirect.php?page=student" method="post">
    <input type="hidden" value="<?php echo $this->page_id ?>" name="student_to_delete">
    <input type="submit" value="Löschen" name="delete_student">
  </form>
</div>
</div>
