<div class="one">
  <h2>Projekte</h2>
  <h1>Für Sie sind folgende Projekte eingetragen</h1>
  <table>
    <thead>
			<tr>
				<th>Wnsch</th>
        <th>Projekttitel</th>
        <th></th>
			</tr>
		</thead>
    <tbody>
      <tr>
        <td>1.Wunsch</td>
			<?php
      if($cur_interests !== false)
      {
        $row = $this->model->getProjectById($cur_interests["project1_id"]);
        if($row !== false)
        {
          echo "
              <td>".$row['titel']."</td><td> <a href='redirect.php?page=project&subpage=show&page_id=".$row["project_id"]."'><button class='linkBtn'>Anzeigen</button></td>";
        }
        else
        {
          echo "<td>Keine Zuordnung</td><td></td>";
        }
      }
      else
      {
        echo "<td>Keine Zuordnung</td><td></td>";
      }

      echo "</tr>";

      if($nwish > 1)
      {
        echo "<tr><td>2.Wunsch</td>";
        if($cur_interests !== false)
        {
          $row = $this->model->getProjectById($cur_interests["project2_id"]);
          if($row !== false)
          {
            echo "<td>".$row['titel']."</td><td> <a href='redirect.php?page=project&subpage=show&page_id=".$row["project_id"]."'><button class='linkBtn'>Anzeigen</button></td>";
          }
          else
          {
            echo "<td>Keine Zuordnung</td><td></td>";
          }
        }
        else
        {
          echo "<td>Keine Zuordnung</td><td></td>";
        }
        echo "</tr>";
      }

      if($nwish > 2)
      {
        echo "<tr><td>3.Wunsch</td>";
        if($cur_interests !== false)
        {
          $row = $this->model->getProjectById($cur_interests["project3_id"]);
          if($row !== false)
          {
            echo "<td>".$row['titel']."</td><td> <a href='redirect.php?page=project&subpage=show&page_id=".$row["project_id"]."'><button class='linkBtn'>Anzeigen</button></td>";
          }
          else
          {
            echo "<td>Keine Zuordnung</td><td></td>";
          }
        }
        else
        {
          echo "<td>Keine Zuordnung</td><td></td>";
        }
        echo "</tr>";
      }
      ?>
		</tbody>
  </table>
</div>
