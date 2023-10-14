<?php
require_once("classes/tile.class.php");
require_once("classes/board.class.php");
require_once("classes/game.class.php");
session_start();
$game = new Game;
if(!isSet($_SESSION["GAME"])){
    header("Location: index.php");
}
?>
<html>
<head>
<title>MineSweeper</title>
<link rel='stylesheet' href='main.css'>
<link rel='stylesheet' href='game.css'>
<link rel='shortcut icon' href='logo.png'>
</head>
<body>
<?php
$game->dropNotification();
?>
<form class="__GAME__" action='includes/controler.inc.php' method='post'>
<div class="panel">

<h2>LEVEL: <?php echo $game->getDifficultyLevelName(); ?></h2>
<h2>MINES: <?php echo $game->getGlobalMinesAmount(); ?></h2>
<button autofocus class="controls" name="changeTool" type="submit" selectedTool="<?php echo $game->getSelectedToolName(); ?>"><?php echo $game->getSelectedToolName(); ?></button>
<button class="controls" name="resetGame" type="submit">Restart</button>

</div>
<div class="board">
<?php
$game->displayBoard();
?>
</form>
</div>
</body>
</html>