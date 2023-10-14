<?php

if(!isSet($_POST["start"])){
    header("Location: ../index.php");
    exit();
}

require_once("../classes/tile.class.php");
require_once("../classes/board.class.php");
require_once("../classes/game.class.php");
session_start();

$game = new Game;
$game->startGame();
$game->generateBoard($_POST["difficulty"]);

header("Location: ../game.php");