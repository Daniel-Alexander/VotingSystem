<div class="one" style="vertical-align:top;">
  <h2>Projekt <?php echo $row["titel"] ?></h2>
  <hr>

    <h1>Abstrakt</h1>
    <?php echo $row["abstract"] ?>

    <hr>


    <h1>Beschreibung</h1>
    <?php echo $row["description"] ?>
    <hr>

    <h1>Keywords</h1>
    <?php echo $row["keywords"] ?>
    <hr>
    <h1>Gewünschter Abschluss</h1>
    <?php echo $this->model->getDegreeById($row["degree"]) ?>
    <hr>
    <h1>Gewünschte Kenntnisse</h1>
    <?php echo $this->model->getSkillsById($row["skills"]) ?>
    <hr>
    <h1>Betreuer</h1>

    <?php
    $teacher = $this->model->getTeacherByProject($row["order_id"]);
    foreach($teacher[0] as $name)
    {
      echo $name;
      echo ", ";
    }
    ?>
    <hr>
    <?php if($this->stage != 1) echo "<div class='infoBox'>In der Aktuellen Phase können Projekte nur vom Administrator geändert werden</div>";?>
  <div class="one-third">
    <a href="redirect.php?page=project&subpage=edit&page_id=<?php echo $this->page_id ?>"><button class="linkBtn" <?php if($this->stage != 1) echo "disabled"; ?>>Bearbeiten</button></a>
  </div>
  <div class="one-third">
    <form action="redirect.php?page=project" method="post">
      <input type="hidden" value="<?php echo $this->page_id ?>" name="project_to_delete">
      <input type="submit" value="Löschen" name="delete_project" <?php if($this->stage != 1) echo "disabled"; ?>>
    </form>
  </div>
  <hr>
  <div class="one-half">
  <h1>Interessenten: </h1>
  <table><thead><tr><th>Erstwunsch</th><th></th></tr></thead>
  <tbody>
  <?php
    $this->model->startQuery("first_interests",$row['project_id']);

    while($stdnt = $this->model->getRow())
    {
      $stdnt_name = $this->model->getStudentById($stdnt['student_id']);
      echo "<tr><td>".$stdnt_name['full_name']."</td>";
      echo "<td><a href='redirect.php?page=student&subpage=show&page_id=".$stdnt['student_id']."'><button class='linkBtn'>Ansehen</button></a></td>";
      echo "</tr>";
    }
    echo "</tbody></table>";

    if($nwishes > 1)
    {
      echo "<table><thead><tr><th>Zweitwunsch</th><th></th></tr></thead>
            <tbody>";
      $this->model->startQuery("second_interests",$row['project_id']);
      while($stdnt = $this->model->getRow())
      {
        $stdnt_name = $this->model->getStudentById($stdnt['student_id']);
        echo "<tr><td>".$stdnt_name['full_name']."</td>";
        echo "<td><a href='redirect.php?page=student&subpage=show&page_id=".$stdnt['student_id']."'><button class='linkBtn'>Ansehen</button></a></td>";
        echo "</tr>";
      }
      echo "</tbody></table>";
    }
    if($nwishes > 2)
    {
      echo "<table><thead><tr><th>Drittwunsch</th><th></th></tr></thead>
            <tbody>";
      $this->model->startQuery("third_interests",$row['project_id']);
      while($stdnt = $this->model->getRow())
      {
        $stdnt_name = $this->model->getStudentById($stdnt['student_id']);
        echo "<tr><td>".$stdnt_name['full_name']."</td>";
        echo "<td><a href='redirect.php?page=student&subpage=show&page_id=".$stdnt['student_id']."'><button class='linkBtn'>Ansehen</button></a></td>";
        echo "</tr>";
      }
      echo "</tbody></table>";
    }
  ?>
  </div>
</div>
