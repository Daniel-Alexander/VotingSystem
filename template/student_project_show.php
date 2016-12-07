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
</div>
