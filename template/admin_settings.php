<div class="one">
  <?php if($this->error) echo "<div class='errcontainer'>".$this->errorhandle->getErrMsg()."</div>" ?>
  <h2>Settings</h2>
  <div class="one-third">
  <h1>Aktuelle Phase:  <?php echo $this->getStageName(); ?></h1>
  <form action="redirect.php?page=settings" method="post">
    <input type="submit" value="Nächste" name="next_stage">
  </form>
  <form action="redirect.php?page=settings" method="post">
    <input type="submit" value="Abbruch" name="cancel_system">
  </form>
  </div>
  <hr>
  <?php if($this->stage != 1) echo "<div class='infoBox'>Das Ändern der Settings ist nur in der Phase Set-Up möglich</div>";?>
  <?php
   $result = $this->model->getAssistAndDeadline();
  ?>
  <div class="one-third">
    <form action="redirect.php?page=settings" method="post">
      <h1>Deadline für Phase: Sign In</h1>
      <input type="text" name="deadline" value="<?php echo $result[1]; ?>" placeholder="dd.mm.yyyy">
      <input type="submit" value="Speichern" name="set_new_deadline" <?php if($this->stage != 1) echo "disabled"; ?>>
    </form>
  </div>
  <div class="one-third">
    <div class="infoBox">
    <h1>Der Server meldet folgende Zeit</h1>
    <?php
      $date = getdate();
      echo "<h1>Datum:   ".$date['mday'].".".$date['mon'].".".$date['year']."</h1>";
      echo "<h1>Uhrzeit: ".$date['hours'].":".$date['minutes'].":".$date['seconds']."</h1>"
    ?>
  </div>
  </div>
  <hr>
  <div class="one-third">
    <form action="redirect.php?page=settings" method="post">
      <h1>E-Mail der Verwaltung</h1>
        <input type="text" required name="assistent_email" value="<?php echo $result[0]; ?>" placeholder="E-Mail Adresse">
      <input type="submit" value="Ändern" name="change_assistent_email" <?php if($this->stage != 1) echo "disabled"; ?>>
    </form>
  </div>
  <hr>
  <div class="one-third">
  <h1>Fähigkeiten</h1>
  <form action="redirect.php?page=settings" method="post">
    <?php
    foreach($this->model->getSkills() as $skills)
    {
      echo "<input type='checkbox' value=".$skills->sign." name='skills_to_delete[]'>".$skills->name."<br>";
    }
    ?>
    <input type="submit" value="Löschen" name="delete_skills" <?php if($this->stage != 1) echo "disabled"?>>
  </form>
  <form action="redirect.php?page=settings" method="post">
    <input type="text" required name="new_skill" placeholder="Neue Fähigkeit">
    <input type="submit" value="Hinzufügen" name="add_new_skill"<?php if($this->stage != 1) echo "disabled"?>>
  </form>
  </div>
  <hr>
  <div class="one-third">
  <h1>Anzahl Wünsche</h1>
  <h1>Aktuell: <?php echo $this->model->getNWish(); ?></h1>
  <form action="redirect.php?page=settings" method="post">
    <select name="num_of_wishes">
      <option disabled selected value>Bittel wählen</option>
      <option>1</option>
      <option>2</option>
      <option>3</option>
    </select>
    <input type="submit" value="Festlegen" name="set_num_wishes" <?php if($this->stage != 1) echo "disabled"?>>
  </form>
  </div>
</div>
