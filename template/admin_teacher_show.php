<div class="one">
<h1>Name: </h1>
<?php echo $row['full_name'] ?><br>
<hr>
<h1>Mail: </h1>
<?php echo $row['email'] ?><br>
<hr>
<h1>Projekte</h1>

<table>
  <?php

  $projects = $this->model->getTeacherProjects($this->page_id);

  if(!$projects)
  {
    echo "<tr>Teacher has no projects</tr>";
  }
  else
  {
    foreach($projects as $id)
    {
      $project = $this->model->getProjectById($id);
      echo "<tr><th>";
      echo $project['titel'];
      echo "</th>";
      echo "<th><a href='redirect.php?page=project&subpage=show&page_id=".$project["project_id"]."'><button class='linkBtn'>Anzeigen</button></a></th><tr>";
    }
  }

  ?>
</table>

<div class="one-third">
  <a href='redirect.php?page=teacher&subpage=edit&page_id=<?php echo $row["teacher_id"] ?>'><button class='linkBtn'>Bearbeiten</button></a>
</div>
<div class="one-third">
  <form action="redirect.php?page=teacher" method="post">
    <input type="hidden" value="<?php echo $this->page_id ?>" name="teacher_to_delete">
    <input type="submit" value="Löschen" name="delete_teacher">
  </form>
</div>
</div>
