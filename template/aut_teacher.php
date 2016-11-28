<html>
    <head>
        <title>Voting System</title>
        <link rel="stylesheet" href="style/style.css" />
    </head>
    <body>
		<div class="top-box" />
        <div id="login-wrapper">
			<header>
				Register
			</header>
            <div class="login_container">
				<div class="one">
					<form action="index.php" method="post">
						<label for="id1">E-Mail</label>
						<input type="text" required name="teacher_mail" id="id1">
						<label for="id1">Passwort</label>
						<input type="text" required name="teacher_pw" id="id1">
						<input type="submit" value="Login" name="teacher_login">
					</form>
					<br>
					<a href="index.php?role=student"><u>Register as Student</u></a>
				</div>
			</div>
		</div>
    </body>
</html>