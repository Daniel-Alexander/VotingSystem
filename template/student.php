
<nav>
	Main Menu<br>
	<a href="redirect.php?page=project"><button>Projekte</button></a><br>
	<a href="redirect.php?page=data"><button>Daten</button></a><br>
	<a href="redirect.php?logout=1"><button>Ausloggen</button></a>
</nav>

<div class="container">
	
	<?php if(strcmp($this->page,'main') === 0 or strcmp($this->page,'project') === 0) { ?>
	
		Projektseite Student
	
	<?php } elseif(strcmp($this->page,'data') === 0) { ?>
	
		Datenseite
	
	<?php } else { ?>
	
		Error: Call to not existing page
	
	<?php } ?>
	
 </div>
