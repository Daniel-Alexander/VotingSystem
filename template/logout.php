<html>
    <head>
        <title>Voting System</title>
        <link rel="stylesheet" href="style/style.css" />
    </head>
    <body>
      <div class="header">
      	<span class="text">
      		Votingsystem
      	</span>
      </div>
      <div id="login-wrapper">
      <div class="login_container">
				<div class="one">
          <?php
          if($this->deleted_page)
          {
            echo "<div class='infobox'>Sie wurden wieder gelöscht</div>";
          }
          else
          {
            echo "<div class='infobox'>Logout erfolgreich</div>";
          }
          ?>
					<a href="index.php"><u>Zum Start</u></a>
				</div>
			</div>
		</div>
    </body>
</html>
