<div class="one">
  <?php if($this->error) echo "<div class='errcontainer'>".$this->errorhandle->getErrMsg()."</div>" ?>
  <?php if($this->stage != 1){ echo "<div class='infoBox'>Aktion in der aktuellen Phase nicht möglich</div>";
  } else { ?>
  <form action="redirect.php?page=project" method="post">
    <div class="two-thirds">
      <h2>Projekt bearbeiten</h2>
      <input type="hidden" value="<?php echo $this->page_id ?>" name="project_id">
      <label for="id1">Titel</label>
      <input type="text" required name="project_titel" value="<?php echo $row['titel'] ?>" placeholder="max 400 Zeichen">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Abstrakt</label>
      <textarea name="project_abstract" required placeholder="max 600 Zeichen"><?php echo $row['abstract'] ?></textarea>
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Beschreibung</label>
      <textarea name="project_description" required placeholder="Beschreibung"><?php echo $row['description'] ?></textarea>
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Keywords</label>
      <input type="text" required name="project_keywords" value="<?php echo $row['keywords'] ?>" placeholder="Keywords">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Gewünschter Abschluss</label><br>
      <input type="checkbox" id="box_id" name="wanted_grade[]" value="Bsc" <?php if($row['degree'] == 1 or $row['degree'] == 3) echo "checked" ?>> B.sc.
      <input type="checkbox" id="box_id" name="wanted_grade[]" value="Msc" <?php if($row['degree'] == 2 or $row['degree'] == 3) echo "checked" ?>> M.sc.
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Gewünschte Kenntnisse</label><br>
      <?php
        foreach($this->model->getSkills() as $skills)
        {
          echo "<input type='checkbox' value=".$skills->sign." name='wanted_skills[]'";
          foreach(explode(";",$row["skills"]) as $skill)
          {
            if($skill == $skills->sign) echo "checked";
          }
          echo ">".$skills->name."<br>";
        }
      ?>
    </div>
    <br>
    <div class="one-third">
      <input type="submit" value="Bearbeitung speichern" name="edit_project">
    </div>
  </form>
  <hr>
  <div class="one-third">
  <table>
    <thead><tr><th>Betreuer</th></tr></thead>
    <tboby>
    <?php
      $teacher = $this->model->getTeacherByProject($row["order_id"]);
      $ind = 0;
      foreach($teacher[0] as $name)
      {
        echo "<tr><td>".$name."</td></tr>";
      }
    ?>
    </tbody></table>
  <form action="redirect.php?page=project" method="post">
    <input type="hidden" value="<?php echo $this->page_id ?>" name="project_id">
    <input type="submit" value="Mich von diesem Projekt entfernen" name="project_remove_me">
  </form>
  <br>
  <h1>Betreuer hinzufügen</h1>
  <form action="redirect.php?page=project" method="post">
    <input type="hidden" value="<?php echo $this->page_id ?>" name="project_id">
    <select name="teacher_to_add">
      <option disabled selected value>Bittel wählen</option>
      <?php $this->model->startQuery('teacher',0);
        while($row = $this->model->getRow())
        {
          echo "<option value='".$row['teacher_id']."'>".$row["full_name"]."</option>";
        }?>
    </select>
    <input type="submit" value="Betreuer hinzufügen" name="project_add_teacher">
  </form>
  </div>
<?php } ?>
</div>
