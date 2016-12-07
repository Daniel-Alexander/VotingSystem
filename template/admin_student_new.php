<div class="one">
  <?php if($this->error) echo $this->errorhandle->getErrMsg() ?>
  <form action="redirect.php?page=student" method="post">
    <div class="two-thirds">
      <h2>Studenten hinzufügen</h2>
      <label for="id1">Name</label>
      <input type="text" required name="new_student_name" <?php if($this->error) echo $this->errorhandle->getStoredName(); ?> placeholder="Name">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Matrikelnummer</label>
      <input type="text" required name="new_student_matr" <?php if($this->error) echo $this->errorhandle->getStoredMatr(); ?> placeholder="Matrikelnummer">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">E-Mail Adresse</label>
      <input type="text" required name="new_student_email" <?php if($this->error) echo $this->errorhandle->getStoredEmail(); ?> placeholder="E-Mail">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Studiengang</label>
      <input type="text" required name="new_student_field" <?php if($this->error) echo $this->errorhandle->getStoredField(); ?> placeholder="Studiengang">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Angestrebter Abschluss</label><br>
      <input type="radio" id="box_id" name="new_student_grade[]" value="Bsc"> B.sc.
      <input type="radio" id="box_id" name="new_student_grade[]" value="Msc"> M.sc.
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Kenntnisse</label><br>
      <?php
        foreach($this->model->getSkills() as $skills)
        {
          echo "<input type='checkbox' value=".$skills->sign." name='new_student_skills[]'>".$skills->name."<br>";
        }
      ?>
    </div>
    <br>
    <div class="two-thirds">
      <input type="submit" value="Hinzufügen" name="add_new_student">
    </div>
  </form>
</div>
