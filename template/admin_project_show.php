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

  <div class="one-half">
    <a href="redirect.php?page=project&subpage=edit&page_id=<?php echo $this->page_id ?>"><button class="linkBtn">Bearbeiten</button></a>
  </div>
  <div class="one-half">
    <form action="redirect.php?page=project" method="post">
      <input type="hidden" value="<?php echo $this->page_id ?>" name="project_to_delete">
      <input type="submit" value="Löschen" name="delete_project">
    </form>
  </div>
  <hr>
  <h1>Interessenten: </h1>
  <?php
    echo "<h1>Erstwunsch: </h1>";
    $this->model->startQuery("first_interests",$row['project_id']);
    while($stdnt = $this->model->getRow())
    {
      $stdnt_name = $this->model->getStudentById($stdnt['student_id']);
      echo $stdnt_name['full_name'];
      echo "<a href='redirect.php?page=student&subpage=show&page_id=".$stdnt['student_id']."'><button class='linkBtn'>Ansehen</button></a></th>";
      echo "<br>";
    }
    if($nwishes > 1)
    {
      echo "<h1>Zweitwunsch: </h1>";
      $this->model->startQuery("second_interests",$row['project_id']);
      while($stdnt = $this->model->getRow())
      {
        $stdnt_name = $this->model->getStudentById($stdnt['student_id']);
        echo $stdnt_name['full_name'];
        echo "<a href='redirect.php?page=student&subpage=show&page_id=".$stdnt['student_id']."'><button class='linkBtn'>Ansehen</button></a></th>";
        echo "<br>";
      }
    }
    if($nwishes > 2)
    {
      echo "<h1>Drittwunsch: </h1>";
      $this->model->startQuery("third_interests",$row['project_id']);
      while($stdnt = $this->model->getRow())
      {
        $stdnt_name = $this->model->getStudentById($stdnt['student_id']);
        echo $stdnt_name['full_name'];
        echo "<a href='redirect.php?page=student&subpage=show&page_id=".$stdnt['student_id']."'><button class='linkBtn'>Ansehen</button></a></th>";
        echo "<br>";
      }
    }


  ?>
</div>
