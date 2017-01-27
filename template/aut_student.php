<html>
    <head>
        <title>Voting System</title>
        <link rel="stylesheet" href="style/style.css" />
    </head>
    <body>
      <div class="header">
      	<div class="logo">
      		Votingsystem
      	</div>
      </div>
        <div id="login-wrapper">

            <div class="login_container">

				<div class="one">
          <?php if($this->error) echo "<div class='errcontainer'>".$this->errorhandle->getErrMsg()."</div>";

          if($this->stage == 2)
          {
            if($this->success)
            {
              echo "<div class='infobox'>Sie haben sich erfolgreich registriert. Bitte bestätigen Sie den Link in Ihrer E-Mail und wählen Sie Ihre Wunschprojekte aus.</div>";
              echo "<br>";
    					echo "<a href='index.php?role=student'><u>Zurück zum Start</u></a>";
            }
            else
            {?>
					<form action="index.php" method="post">
						<label for="id1">Name</label>
						<input type="text" required name="student_name" id="id1">
            <label for="id1">Matrikelnummer</label>
						<input type="text" required name="student_matr" id="id1">
            <label for="id1">E-Mail Adresse</label>
						<input type="text" required name="student_email" id="id1">
            <label for="id1">Studiengang</label>
						<input type="text" required name="student_fos" id="id1">
						<input type="radio" required name="student_degree" value="Bsc"> B.sc.
						<input type="radio" required name="student_degree" value="Msc"> M.sc. <br>
            <label for="id1">Kenntnisse</label><br>
            <?php
              foreach($this->model->getSkills() as $skills)
              {
                echo "<input type='checkbox' value=".$skills->sign." name='student_skills[]'>".$skills->name."<br>";
              }
            ?>
						<input type="submit" value="Registrieren" name="create_new_student">
					</form>
          <?php
          echo "<br>";
					echo "<a href='index.php?role=teacher'><u>Als Betreuer anmelden</u></a>";
          }
        } else {
          echo "<div class='infobox'>Zur Zeit ist keine Registrierung möglich</div>";
          echo "<br>";
					echo "<a href='index.php?role=teacher'><u>Als Betreuer anmelden</u></a>";
        }?>
				</div>
			</div>
		</div>
    </body>
</html>
