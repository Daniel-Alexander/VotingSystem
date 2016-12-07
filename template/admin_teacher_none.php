<div class="one">
  <?php if($this->error) echo $this->errorhandle->getErrMsg(); ?>
  <h2>Aktive Teacher</h2>

  <div class="one-third">
    <a href="redirect.php?page=teacher&subpage=new"><button class="linkBtn">Betreuer hinzufügen</button></a>
  </div>
  <br>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Mail</th>
          <th></th>
        </tr>
      </thead>

      <?php $this->model->startQuery('teacher',0);

        while($row = $this->model->getRow())
        {
          echo 	"<tbody>
                <tr>
                  <th>".$row["full_name"]."</th>
                  <th>".$row["email"]."</th>";
              if ($row["teacher_id"] == $_SESSION["current_id"])
              {
                echo "<th><a href='redirect.php?page=data'><button class='linkBtn'>Anzeigen</button></a></th>";
              }
              else
              {
                echo "<th><a href='redirect.php?page=teacher&subpage=show&page_id=".$row["teacher_id"]."'><button class='linkBtn'>Anzeigen</button></a></th>";
              }
          echo "</tr>
              </tbody>";
        }
      ?>

    </table>
    <!--<input type="submit" value="Löschen" name="delete_teacher">-->
</div>
