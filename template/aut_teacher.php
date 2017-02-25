<html>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
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
          <?php if($this->error) echo "<div class='errcontainer'>".$this->errorhandle->getErrMsg()."</div>" ?>
					<form action="index.php" method="post">
						<label for="id1">E-Mail</label>
						<input type="text" required name="teacher_mail" id="id1">
						<label for="id1">Passwort</label>
						<input type="password" required name="teacher_pw" id="id1">
						<input type="submit" value="Login" name="teacher_login">
					</form>
					<br>
					<a href="index.php?role=student"><u>Als Student registrieren</u></a>
				</div>
			</div>
		</div>
    </body>
</html>
