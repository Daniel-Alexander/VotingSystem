<div class="one">
  <?php if($this->error) echo $this->errorhandle->getErrMsg() ?>
  <form action="redirect.php?page=project" method="post">
    <div class="two-thirds">
      <h2>Neues Projekt anlegen</h2>
      <label for="id1">Titel</label>
      <input type="text" required name="project_titel" <?php if($this->error) echo $this->errorhandle->getStoredTitle(); ?> placeholder="max 400 Zeichen">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Abstrakt</label>
      <textarea name="project_abstract" required placeholder="max 600 Zeichen"><?php if($this->error) echo $this->errorhandle->getStoredAbstract(); ?></textarea>
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Beschreibung</label>
      <textarea name="project_description" required placeholder="Beschreibung"><?php if($this->error) echo $this->errorhandle->getStoredDescription(); ?></textarea>
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Keywords</label>
      <input type="text" required name="project_keywords" <?php if($this->error) echo $this->errorhandle->getStoredKeywords(); ?> placeholder="Keywords">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Gewünschter Abschluss</label><br>
      <input type="checkbox" id="box_id" name="wanted_grade[]" value="Bsc"> B.sc.
      <input type="checkbox" id="box_id" name="wanted_grade[]" value="Msc"> M.sc.
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Gewünschte Kenntnisse</label><br>
      <?php
        foreach($this->model->getSkills() as $skills)
        {
          echo "<input type='checkbox' value=".$skills->sign." name='wanted_skills[]'>".$skills->name."<br>";
        }
      ?>
    </div>
    <br>
    <div class="two-thirds">
      <input type="submit" value="Projekt erstellen" name="create_new_project">
    </div>
  </form>
</div>
