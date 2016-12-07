<div class="one">
<?php if($this->error) echo $this->errorhandle->getErrMsg() ?>
<form action="redirect.php?page=teacher" method="post">
<div class="two-thirds">
  <h2>Teacher</h2>
  <label for="id1">Name</label>
  <input type="text" required name="new_teacher_name" <?php if($this->error) echo $this->errorhandle->getStoredName(); ?> placeholder="Name">
</div>
<br>
<div class="two-thirds">
  <label for="id1">E-Mail</label>
  <input type="text" required name="new_teacher_mail" <?php if($this->error) echo $this->errorhandle->getStoredEmail(); ?> placeholder="Mail">
</div>
<br>
<div class="two-thirds">
  <label for="id1">Initiales Passwort</label>
  <input type="text" required name="new_teacher_pw" placeholder="Init Pw">
</div>
<br>
<div class="two-thirds">
  <input type="submit" value="Hinzufügen" name="create_new_teacher">
</div>
</form>
</div>
