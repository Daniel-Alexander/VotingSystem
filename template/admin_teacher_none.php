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
                  <td>".$row["full_name"]."</td>
                  <td>".$row["email"]."</td>";
              if ($row["teacher_id"] == $this->model->getTeacherId())//$_SESSION["current_id"])
              {
                echo "<td><a href='redirect.php?page=data'><button class='linkBtn'>Anzeigen</button></a></td>";
              }
              else
              {
                echo "<td><a href='redirect.php?page=teacher&subpage=show&page_id=".$row["teacher_id"]."'><button class='linkBtn'>Anzeigen</button></a></td>";
              }
          echo "</tr>
              </tbody>";
        }
      ?>

    </table>
    <!--<input type="submit" value="Löschen" name="delete_teacher">-->
</div>
