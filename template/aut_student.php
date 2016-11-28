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
						<label for="id1">Name</label>
						<input type="text" required name="student_name" id="id1">
						<label for="id1">Matrikelnummer</label>
						<input type="text" required name="student_matr" id="id1">
						<label for="id1">E-Mail</label>
						<input type="text" required name="student_mail" id="id1">
						<input type="radio" required name="student_grade" value="Bsc"> B.sc.
						<input type="radio" required name="student_grade" value="Msc"> M.sc. <br>
						<input type="submit" value="Register" name="create_new_student">
					</form>
					<br>
					<a href="index.php?role=teacher"><u>Login as Teacher</u></a>
				</div>
			</div>
		</div>
    </body>
</html>