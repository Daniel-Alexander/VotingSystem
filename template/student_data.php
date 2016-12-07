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
<br>
<div class="one-third">
  <form action="redirect.php?page=project" method="post">
    <input type="submit" value="Abmelden" name="student_delete_me">
  </form>
</div>
</div>
