<div class="one">
<h1>Name: </h1>
<?php echo $row['full_name'] ?><br>
<hr>
<h1>Aktiv: </h1>
<form action="redirect.php?page=student&subpage=show&page_id=<?php echo $this->page_id; ?>" method="post">
  <input type="hidden" value="<?php echo $this->page_id ?>" name="student_to_toggle">
  <?php
    if($row["active"])
    {
      echo "<div class='one-third'><img src='style/img/active24.ico' alt='active'></div>";
      echo "<div class='one-third'><input type='submit' value='Deaktivieren' name='student_toggle_active'></div>";
    }
    else
    {
      echo "<div class='one-third'><img src='style/img/deactive24.ico' alt='active'></div>";
      echo "<div class='one-third'><input type='submit' value='Aktivieren' name='student_toggle_active'></div>";
    }
  ?>
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
<div class="one-half">
<table><thead><tr><th>Interessiert an: </th><th></th><th></th></thead>
</tbody>
<?php
$projects = $this->model->getStudentInterests($this->page_id);
if(!$projects)
{
  echo "<tr><td>Noch keine Interessen hinterlegt</td><td></td><td></td></tr></tbody></table>";
}
else
{
  if($projects["project1_id"] != 0)
  {
    echo "<tr><td>1. Wunsch</td>";
    $prj = $this->model->getProjectById($projects["project1_id"]);
    echo "<td>".$prj["titel"]."</td>";
    echo "<td><a href='redirect.php?page=project&subpage=show&page_id=".$projects['project1_id']."'><button class='linkBtn'>Anzeigen</button></a>";
    echo "</td>";
  }
  if($projects["project2_id"] != 0)
  {
    echo "<tr><td>2. Wunsch</td>";
    $prj = $this->model->getProjectById($projects["project2_id"]);
    echo "<td>".$prj["titel"]."</td>";
    echo "<td><a href='redirect.php?page=project&subpage=show&page_id=".$projects['project2_id']."'><button class='linkBtn'>Anzeigen</button></a>";
    echo "</td>";
  }
  if($projects["project3_id"] != 0)
  {
    echo "<tr><td>3. Wunsch</td>";
    $prj = $this->model->getProjectById($projects["project3_id"]);
    echo "<td>".$prj["titel"]."</td>";
    echo "<td><a href='redirect.php?page=project&subpage=show&page_id=".$projects['project3_id']."'><button class='linkBtn'>Anzeigen</button></a>";
    echo "</td>";
  }
  echo "</tbody></table>";
}
?>

</div>
<br>
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
