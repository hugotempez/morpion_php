<?php

include_once "Game.php";
include_once "Player.php";

function menu() : ?string {
    echo "+-----------------------------------------------+";
    echo PHP_EOL;
    echo "| Morpion";
    echo PHP_EOL;
    echo "+-----------------------------------------------+";
    echo PHP_EOL;
    echo "| R - > Règle du jeu";
    echo PHP_EOL;
    echo "| J - > Jeu unique (2 joueurs)";
    echo PHP_EOL;
    echo "| C - > Challenge de 3 parties (2 joueurs)";
    echo PHP_EOL;
    echo "| O - > Contre l’ordinateur";
    echo PHP_EOL;
    echo "| Q - > Quitter";
    echo PHP_EOL;
    echo "+-----------------------------------------------+";
    echo PHP_EOL;
    $userChoice = strtoupper(readline("Votre choix : "));
    switch ($userChoice) {
        case "R":
        case "J":
        case "C":
        case "O":
        case "Q":
            return $userChoice;
        default:
            popen("cls", "w");
            menu();
    }
    return null;
}


function clearScreen() : void {
    popen("cls", "w");
}

function play() {
    $p1Name = readline("Entre le nom du joueur 1 (si vide, le joueur sera appelé par son id): ");
    $p2Name = readline("Entre le nom du joueur 2 (si vide, le joueur sera appelé par son id): ");
    $game = new Game(new Player($p1Name), new Player($p2Name));
    while (!$game->isDone()) {
        $game->playNextRound();
    }
}

    //while (1) {
        $userInput = menu();
        if ($userInput) {
            switch ($userInput) {
                case "R":
                    break;
                case "J":
                    clearScreen();
                    play();
                case "C":
                    break;
                case "O":
                    break;
                case "Q":
                    break;
                default:
                    break;
            }
        }
    //}
