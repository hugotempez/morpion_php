<?php

include_once "Player.php";

class Game {
    private static array $gameMap = [
        1 => [0, 0], 2 => [0, 1], 3 => [0, 2],
        4 => [1, 0], 5 => [1, 1], 6 => [1, 2],
        7 => [2, 0], 8 => [2, 1], 9 => [2, 2]
    ];
    private SplFixedArray $field;
    private SplFixedArray $players;
    private Player $currentPlayer;
    private bool $isDone = false;

    public function __construct(Player $player1, Player $player2) {
        $this->initField();
        $this->players = SplFixedArray::fromArray([$player1, $player2]);
        $this->setFirstPlayer();
    }


    private function initField() : void {
        $this->field = new SplFixedArray(3);
        for ($i = 0; $i < 3; $i++) {
            $this->field[$i] = new SplFixedArray(3);
        }
        count($this->field->toArray());
        foreach ($this->field as $column) {
            count($column->toArray());
        }
    }

    private function setFirstPlayer() : void {
        $this->currentPlayer = $this->players[rand(0, count($this->players) - 1)];
    }


    private function writeToField(int $userInput) : bool {
        $indexes = Game::$gameMap[$userInput];
        if ($this->field[$indexes[0]][$indexes[1]] === null) {
            $this->field[$indexes[0]][$indexes[1]] = $this->currentPlayer->getId();
            return true;
        } else {
            return false;
        }
    }


    public function playNextRound() : void {
        $playerName = ($this->currentPlayer->getName() !== "") ? $this->currentPlayer->getName() :
            "Joueur " . $this->currentPlayer->getId();
        $input = readline("A $playerName de jouer! Entrez votre coup suivant (1 à 9): ");
        try {
            $input = (int)$input;
        } catch (Exception $e) {
            die($e->getMessage());
        }
        if ($input >= 1 && $input <= 9) {
            if ($this->writeToField($input)) {
                $this->printField();
                echo "Au joueur suivant" . PHP_EOL;
                $this->nextPlayer();
            } else {
                echo "La case a deja été joué";
                $this->playNextRound();
            }
        } else {
            echo "Choix invalide";
        }
    }

    private function nextPlayer() : void {
        $index = array_search($this->currentPlayer, (array)$this->players);
        $this->currentPlayer = ($index === 0) ? $this->players[$index + 1] : $this->players[$index - 1];
    }

    public function printField() : void {
        echo "  -   -   -" . PHP_EOL;
        foreach ($this->field as $column) {
            echo "|";
            foreach ($column as $line) {
                echo " $line  |";
            }
            echo PHP_EOL . "  -   -   -" . PHP_EOL;
        }
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }
}