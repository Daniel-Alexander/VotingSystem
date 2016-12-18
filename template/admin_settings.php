<div class="one">
  <h2>Settings</h2>
  <h1>Aktuelle Phase:  <?php echo $this->getStageName(); ?></h1>
  <form action="redirect.php?page=settings" method="post">
    <input type="submit" value="Nächste" name="next_stage">
  </form>
  <form action="redirect.php?page=settings" method="post">
    <input type="submit" value="Abbruch" name="cancel_system">
  </form>
  <hr>
  <?php if($this->stage != 1) echo "Das Ändern der Settings ist nur in der Set-Up Phase möglich"?>
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
  <hr>
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
