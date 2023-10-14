<?php

class Tile{
    public int $id;
    public int $type; // self::$styles[$i]
    public int $minesAround;
    public static $styles = [
        -4 => "flaggedIncorrectShow",
        -3 => "bombShow",
        -2 => "basic hoverables", //bomb
        -1 => "basic hoverables", //blank
        0 => "revealed",
        9 => "flagged", //correct
        10 => "flagged", //incorrect
        11 => "DEBUG_1",
        12 => "DEBUG_2"
    ];

    public function __construct(int $type){
        $this->type = $type;
    }
    public function getType(){
        return $this->type;
    }
    public function getStyle(){
        return self::$styles[$this->type];
    }
    public function getId(){
        return $this->id;
    }
    public function getMinesAround(){
        $displayables = [-4, 0];
        if(in_array($this->type, $displayables) && $this->minesAround != 0){
            return $this->minesAround;
        }
        return " ";
    }

    public function canFlag(){
        $flagables = [-2, -1, 9, 10];
        if(in_array($this->type, $flagables)){
            return true;
        }
        return false;
    }

    public function canDig(){
        $digables = [-2, -1];
        if(in_array($this->type, $digables)){
            return true;
        }
        return false;
    }

    public function flag(){
        if(!$this->canFlag()){
            return;
        }
        switch($this->type){
            case -2:
                $this->type = 9;
                break;
            case -1:
                $this->type = 10;
                break;
            case 9:
                $this->type = -2;
                break;
            case 10:
                $this->type = -1;
                break;
        }
    }

    public function dig(){
        if(!$this->canDig()){
            return;
        }
        switch($this->type){
            case -1:
                $this->type = 0;
                break;
            case -2:
                $this->type = -3;
                break;
        }
    }

    public function isMine(){
        $bombs = [-2, 9];
        if(in_array($this->type, $bombs)){
            return true;
        }
        return false;
    }
    public function isAlreadyDug(){
        if($this->type == 0){
            return true;
        }
        return false;
    }

    public function interactTile($tool){
        switch($tool){
            case 0:
                return $this->dig();
            case 1:
                return $this->flag();
        }
    }
}

?>