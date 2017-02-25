<div class="one">
  <?php if($this->error) echo "<div class='errcontainer'>".$this->errorhandle->getErrMsg()."</div>" ?>
  <form action="redirect.php?page=teacher" method="post">
    <input type="hidden" value="<?php echo $this->page_id ?>" name="teacher_id">
    <div class="two-thirds">
      <h2>Teacher</h2>
      <label for="id1">Name</label>
      <input type="text" required name="edit_teacher_name" value="<?php echo $row["full_name"] ?>" placeholder="Name">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">E-Mail</label>
      <input type="text" required name="edit_teacher_mail" value="<?php echo $row["email"] ?>" placeholder="Mail">
    </div>
    <br>
    <div class="one-third">
      <input type="submit" value="Änderung speichern" name="edit_teacher">
    </div>
  </form>
  <hr>
  <form action="redirect.php?page=teacher" method="post">
    <input type="hidden" value="<?php echo $this->page_id ?>" name="teacher_id">
    <div class="two-thirds">
      <label for="id1">Neues Passwort</label>
      <input type="text" required name="edit_teacher_pw_1" placeholder="Passwort">
    </div>
    <br>
    <div class="two-thirds">
      <label for="id1">Passwort wiederholen</label>
      <input type="text" required name="edit_teacher_pw_2" placeholder="Wiederholen">
    </div>
    <br>
    <div class="one-third">
      <input type="submit" value="Passwort speichern" name="edit_teacher_pw">
    </div>
  </form>
</div>
