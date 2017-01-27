<div class="one">
<h1>Name: </h1>
<?php echo $row['full_name'] ?><br>
<hr>
<h1>E-Mail: </h1>
<?php echo $row['email'] ?><br>
<hr>
<div class="one-half">
<table>
  <thead><tr><th>Projekte</th><th></th></tr></thead>
  <tbody>
  <?php

  $projects = $this->model->getTeacherProjects($this->page_id);

  if(!$projects)
  {
    echo "<tr><td>Teacher has no projects</td><td></td></tr></tbody></table>";
  }
  else
  {
    foreach($projects as $id)
    {
      $project = $this->model->getProjectById($id);
      echo "<tr><td>";
      echo $project['titel'];
      echo "</td>";
      echo "<td><a href='redirect.php?page=project&subpage=show&page_id=".$project["project_id"]."'><button class='linkBtn'>Anzeigen</button></a></td></tr>";
    }
    echo "</tbody></table>";
  }

  ?>
</div>
<br>
<div class="one-third">
  <a href='redirect.php?page=teacher&subpage=edit&page_id=<?php echo $row["teacher_id"] ?>'><button class='linkBtn'>Bearbeiten</button></a>
</div>
<div class="one-third">
  <form action="redirect.php?page=teacher" method="post">
    <input type="hidden" value="<?php echo $this->page_id ?>" name="teacher_to_delete">
    <input type="submit" value="Löschen" name="delete_teacher" <?php if($this->stage !=1) echo "disabled"; ?> >
  </form>
</div>
</div>
