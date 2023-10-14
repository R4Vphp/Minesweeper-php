<?php

if($_SERVER["REQUEST_METHOD"] != "POST"){
    header("Location: ../index.php");
    exit();
}

require_once("../classes/tile.class.php");
require_once("../classes/board.class.php");
require_once("../classes/game.class.php");
session_start();

$game = new Game;
$tile = $game->getClickedTile();
$tool = $game->getSelectedTool();

if($game->isOngoing()){

    if(isSet($_POST["changeTool"])){
        $game->changeTool();
    }

    if(is_object($tile)){
        
        if($game->onFirstClick()){
            $minesToAdd = $game->eraseMinesAround($tile);
            $game->addRemainingMines($minesToAdd);
            $game->countMinesAround();
        }elseif($tile->type == -2 && $tool == 0){
            $game->setFail();
        }

        if($tile->type == -1 && $tool == 0){
            $game->autoDigSearch($tile);
            $game->autoDig();
        }

        $tile->interactTile($tool);
        
    }

    if($game->winConditions()){
        $game->setWin();
    }

}
if(isSet($_POST["resetGame"])){
    header("Location: restart.inc.php ");
    exit();
}

header("Location: ../game.php");