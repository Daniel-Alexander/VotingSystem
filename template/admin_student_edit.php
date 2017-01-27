<div class="one">
  <?php if($this->error) echo $this->errorhandle->getErrMsg() ?>
  <form action="redirect.php?page=student" method="post">
    <div class="two-thirds">
      <h2>Student bearbeiten</h2>
      <input type="hidden" value="<?php echo $this->page_id ?>" name="student_id">
      <label for="id1">Name</label>
      <input type="text" required name="edit_student_name" value="<?php echo $row["full_name"] ?>" placeholder="Name">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Matrikelnummer</label>
      <input type="text" required name="edit_student_matr" value="<?php echo $row["matrikulation"] ?>" placeholder="Matrikelnummer">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">E-Mail Adresse</label>
      <input type="text" required name="edit_student_email" value="<?php echo $row["email"] ?>" placeholder="E-Mail">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Studiengang</label>
      <input type="text" required name="edit_student_field" value="<?php echo $row["studyfield"] ?>" placeholder="Studiengang">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Angestrebter Abschluss</label><br>
      <input type="radio" id="box_id" name="edit_student_grade" value="Bsc" <?php if($row['degree'] == 1) echo "checked" ?>> B.sc.
      <input type="radio" id="box_id" name="edit_student_grade" value="Msc" <?php if($row['degree'] == 2) echo "checked" ?>> M.sc.
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Kenntnisse</label><br>
      <?php
        foreach($this->model->getSkills() as $skills)
        {
          echo "<input type='checkbox' value=".$skills->sign." name='edit_student_skills[]'";
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
      <input type="submit" value="Änderungen speichern" name="edit_student">
    </div>
  </form>
</div>
