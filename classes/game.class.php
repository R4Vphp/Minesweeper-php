<?php

class Game extends Board{

    private int $selectedTool;
    private int $state;
    private int $clicks;
    private int $startTime;
    private int $endTime;

    private static $states = [
        -1 => "Failed",
        0 => "Ongoing",
        1 => "Win"
    ];

    private static $tools = [
        0 => "dig",
        1 => "flag"
    ];
    
    private static $saves = [
        "globalMinesAmount" => 0,
        "selectedTool" => 0,
        "difficulty" => 0,
        "state" => 0,
        "clicks" => 0,
        "size" => [],
        "map" => [],
        "startTime" => 0,
        "endTime" => 0
    ];

    public function startGame(){
        $this->startTime = time();
    }
    public function setEndTime(){
        $this->endTime = time() - $this->startTime;
    }
    public function getFinishTime(){
        return date("i:s", $this->endTime);
    }

    public function getClickedTile(){
        forEach($this->map as $row){
            forEach($row as $tile){
                if(isSet($_POST["__TILE__"]) && $tile->id == $_POST["__TILE__"]){
                    $this->clicks++;
                    return $tile;
                }
            }
        }
        return false;
    }

    public function changeTool(){
        $this->selectedTool++;
        if($this->selectedTool > 1){
            $this->selectedTool = 0;
        }
    }

    public function getSelectedToolName(){
        return self::$tools[$this->selectedTool];
    }
    public function getSelectedTool(){
        return $this->selectedTool;
    }

    public function getDifficultyLevelName(){
        return self::$difficultyList[$this->difficulty]["name"];
    }
    public function getDifficultyLevel(){
        return $this->difficulty;
    }

    public function getGlobalMinesAmount(){
        return max($this->globalMinesAmount - $this->countTilesOfType([9, 10]),0);
    }
    private function countTilesOfType($types){
        if(!is_array($types)){
            $types = [$types];    
        }

        $i = 0;
        forEach($this->map as $row){
            forEach($row as $tile){
                if(in_array($tile->type, $types)){
                    $i++;
                }
            }
        }
        return $i;
    }

    public function getClicksCount(){
        return $this->clicks;
    }
    public function onFirstClick(){
        if($this->clicks == 1){
            return true;
        }
        return false;
    }

    public function __construct(){
        forEach(self::$saves as $key => $param){
            if(isSet($_SESSION["GAME"][$key])){
                $this->$key = $_SESSION["GAME"][$key];
            }else{
                $this->$key = $param;
            }
        }
    }
    public function __destruct(){
        forEach(self::$saves as $key => $param){
            $_SESSION["GAME"][$key] = $this->$key;
        }
    }

    public static function displayOptions($default = 2){

        if(isSet($_GET["lvl"])){
            $default = $_GET["lvl"];
        }

        forEach(self::$difficultyList as $key => $option){
            $value = $key;
            $name = $option["name"];
            if($key == $default ? $i = "selected" : $i = "");
            echo "<option value='$value' $i>$name</option>";
        }
    }

    public function getState(bool $name = false){
        if($name){
            return self::$states[$this->state];
        }
        return $this->state;
        
    }
    public function setWin(){
        $this->setEndTime();
        $this->state = 1;
    }
    public function setFail(){
        $this->setEndTime();
        $this->state = -1;
        $this->changeTypeFromTo([-2, 9], -3);
        $this->changeTypeFromTo(10, -4);
    }
    public function isOngoing(){
        if($this->state == 0){
            return true;
        }
        return false;
    }

    public function winConditions(){
        if(
            $this->countTilesOfType([-3, -2, -1, 10]) == 0 &&
            $this->countTilesOfType(9) == $this->globalMinesAmount
        ){
            return true;
        }
        return false;
    }

    public function dropNotification(){
        if($this->getState() != 0){
            $state = $this->getState(true);
            $time = $this->getFinishTime();
            echo "<div class='notification'>
            <h3 class='$state' >You $state! $time</h3>
            </div>";
        }
    }
}

?>