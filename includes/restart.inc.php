<?php
require_once("../classes/tile.class.php");
require_once("../classes/board.class.php");
require_once("../classes/game.class.php");
session_start();
$game = new Game;
$difficulty = $game->getDifficultyLevel();

session_unset();
session_destroy();
header("Location: ../index.php?lvl=$difficulty");
exit();