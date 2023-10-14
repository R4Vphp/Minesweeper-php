<?php

class Board{
    protected array $size; // [x,y]
    protected array $map;
    protected int $difficulty; // 0-4
    protected int $globalMinesAmount;
    public array $tilesToDig = [];

    protected static $difficultyList = [
        0 => ["name" => "Beginner", "size" => [10,10], "dens" => 10],
        1 => ["name" => "Easy", "size" => [15,10], "dens" => 15],
        2 => ["name" => "Medium", "size" => [20,15], "dens" => 17.5],
        3 => ["name" => "Hard", "size" => [20,15], "dens" => 20],
        4 => ["name" => "Extreme", "size" => [25,15], "dens" => 22.5]
    ];

    public function generateBoard(int $difficulty){
        
        $difficulty = min(max($difficulty, array_key_first(self::$difficultyList)), count(self::$difficultyList) - 1);
        $this->difficulty = $difficulty;

        $this->size = self::$difficultyList[$difficulty]["size"];
        $xLimit = $this->size[0];
        $yLimit = $this->size[1];
        
        $density = self::$difficultyList[$difficulty]["dens"];
        $tilesAmount = $xLimit * $yLimit;
        $minesAmount = round($tilesAmount * $density / 100, 0);
        $this->globalMinesAmount = $minesAmount;

        $mapTemp = [];
        for($id = 0; $id < $tilesAmount; $id++){
            if($id < $minesAmount ? $type = -2 : $type = -1);
            array_push($mapTemp, new Tile($type));
        }
        shuffle($mapTemp);

        forEach($mapTemp as $key => $tile){
            $tile->id = $key;
        }

        $mapTemp = array_chunk($mapTemp, $xLimit);
       
        $this->map = $mapTemp;
    }

    public function displayBoard(){
		
        $state = $this->getState(true);
        $tool = $this->getSelectedToolName();
        echo "<table class='__BOARD__' selectedTool='$tool' state='$state'>";
        forEach($this->map as $y => $row){
            echo "<tr>";
            forEach($row as $x => $tile){
                $id = $tile->getId();
                $type = $tile->getType();
                $skin = $tile->getStyle();
                $mines = $tile->getMinesAround();
                echo "<td><button id='$id' type='submit' class='__TILE__ $skin' name='__TILE__' value='$id'>$mines</button></td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }

    private function extractChunk3x3(array $origin){ //[x,y]
        $xPos = $origin[0];
        $yPos = $origin[1];

        $mapTemp = $this->map;
        $mapTemp = array_slice($this->map, max($yPos - 1, 0), ($yPos + 1) - (max($yPos - 1, 0) - 1));
        $chunk3x3 = [];
        forEach($mapTemp as $y => $row){
            $row = array_slice($row, max($xPos - 1, 0), ($xPos + 1) - (max($xPos - 1, 0) - 1));
            $chunk3x3 = array_merge($chunk3x3, $row);
        }

        return $chunk3x3;
    }

    private function getTilePosition(Tile $tileToFind){
        forEach($this->map as $y => $row){    
            forEach($row as $x => $tile){
                if($tile === $tileToFind){
                    return [$x,$y];
                }
            }
        }
        return [-1,-1];
    }

    public function countMinesAround(){
        forEach($this->map as $y => $row){
            forEach($row as $x => $tile){

                $position = $this->getTilePosition($tile);
                $chunk3x3 = $this->extractChunk3x3($position);

                $mines = 0;
                forEach($chunk3x3 as $part){
                    if($part->isMine()){
                        $mines++;
                    }
                }
                $tile->minesAround = $mines;
            }
        }
    }

    public function eraseMinesAround(Tile $tile){

        $position = $this->getTilePosition($tile);
        $chunk3x3 = $this->extractChunk3x3($position);

        $mines = 0;
        forEach($chunk3x3 as $part){
            if($part->isMine()){
                $part->type = -1;
                $mines++;
            }
        }
        return $mines;
    }

    public function addRemainingMines(int $amount){
        
        forEach($this->map as $y => $row){
            forEach($row as $x => $tile){
                if(!$tile->isMine() && !$tile->isAlreadyDug() && $amount > 0){
                    $amount--;
                    $tile->type = -2;
                }
            }
        }
    }

    public function autoDig(){

        for($i = 0; $i < 4096; $i++){
            if(isSet($this->tilesToDig[$i])){
                $this->autoDigSearch($this->tilesToDig[$i]);
            }
        }
        forEach($this->tilesToDig as $tile){
            $tile->dig();
        }
        $this->tilesToDig = [];
    }

    public function autoDigSearch(Tile $tile){
        if($tile->minesAround == 0){

            $position = $this->getTilePosition($tile);
            $chunk3x3 = $this->extractChunk3x3($position);

            forEach($chunk3x3 as $part){
                if($part->type == -1 && !array_search($part, $this->tilesToDig)){
                    $this->tilesToDig[] = $part;
                }
            }   
        }
    }

    public function changeTypeFromTo($from, int $to){ // (#1 array/int)
        if(!is_array($from)){
            $from = [$from];    
        }

        forEach($this->map as $row){
            forEach($row as $tile){
                if(in_array($tile->type, $from)){
                    $tile->type = $to;
                }
            }
        }
    }
}

?>