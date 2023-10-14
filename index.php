<?php
include_once("classes/board.class.php");
include_once("classes/game.class.php");
?>
<html>
<head>
<title>MineSweeper</title>
<link rel='stylesheet' href='main.css'>
<!--<link rel='stylesheet' href='game.css'>-->
<link rel='shortcut icon' href='logo.png'>
</head>
<body>

<div class="section">
	<h1 class="blue">MineSweeper</h1>
</div>

<div class="section">

<form class="__START__" action='includes/start.inc.php' method='post'>
	<h2>Choose difficulty level:</h2>
	<div class="selection">
		<select name="difficulty">
			<?php Game::displayOptions(); ?>
		</select>
	</div>
	<button type="submit" name="start" class="options">Start</button>
</form>

</div>
<div class="section">
	<p>Minesweeper &copy</p>
</div>

</body>
</html>