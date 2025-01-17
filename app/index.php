<?php

use Random\RandomException;

include_once "Game.php";
include_once "PhysicalPlayer.php";
include_once "IAPlayer.php";


/**
 * Nettoie la console
 * @return void
 */
function clearScreen() : void {
    popen("cls", "w");
}


/**
 * Menu principal
 * @return string Le choix de l'utilisateur ou null en cas d'erreur
 */
function menu() : string {
    clearScreen();
    echo "+-----------------------------------------------+";
    echo PHP_EOL;
    echo "|                   Morpion                     |";
    echo PHP_EOL;
    echo "+-----------------------------------------------+";
    echo PHP_EOL;
    echo "| R - > Règle du jeu                            |";
    echo PHP_EOL;
    echo "| J - > Jeu unique (2 joueurs)                  |";
    echo PHP_EOL;
    echo "| C - > Challenge de 3 parties (2 joueurs)      |";
    echo PHP_EOL;
    echo "| O - > Contre l’ordinateur                     |";
    echo PHP_EOL;
    echo "| Q - > Quitter                                 |";
    echo PHP_EOL;
    echo "+-----------------------------------------------+";
    echo PHP_EOL;
    $userChoice = strtoupper(readline("Votre choix : "));   //Choix de l'utilisateur en majuscule
    switch ($userChoice) {
        case "R":
        case "J":
        case "C":
        case "O":
        case "Q":
            return $userChoice;
        default:    //Si le choix de l'utilisateur n'est pas dans les case précédents, ont clear la console et on fait un appel récursif au menu
            popen("cls", "w");
            return menu();
    }
}


function menuIA() : string {
    echo "+-----------------------------------------------+";
    echo PHP_EOL;
    echo "|                   Choix de l'IA               |";
    echo PHP_EOL;
    echo "+-----------------------------------------------+";
    echo PHP_EOL;
    echo "| 1 - > AlphaBeta                               |";
    echo PHP_EOL;
    echo "| 2 - > Intelligence analytique                 |";
    echo PHP_EOL;
    echo "+-----------------------------------------------+";
    echo PHP_EOL;
    $userChoice = strtoupper(readline("Votre choix : "));   //Choix de l'utilisateur en majuscule
    echo "input menu $userChoice" . PHP_EOL;
    switch ($userChoice) {
        case "1":
        case "2":
            return $userChoice;
        default:    //Si le choix de l'utilisateur n'est pas dans les case précédents, ont clear la console et on fait un appel récursif au menu
            popen("cls", "w");
            return menuIA();
    }
}


/**
 * Démarre le nombre de partie passé en paramètre
 * @param int $counter Nombre de parties à jouer
 * @return void
 * @throws RandomException
 */
function play(int $counter=1) : void
{
    //Récupération des noms de joueurs
    $p1Name = readline("Entre le nom du joueur 1 (si vide, le joueur sera appelé par son id): ");
    $p2Name = readline("Entre le nom du joueur 2 (si vide, le joueur sera appelé par son id): ");
    $player1 = new PhysicalPlayer($p1Name); //Création du joueur 1
    $player2 = new PhysicalPlayer($p2Name); //Création du joueur 2
    $game = new Game($player1, $player2); //Création d'une partie
    for ($i = 0; $i < $counter; $i++) {
        while (!$game->isDone()) {  //Tant que la partie n'est pas finie on appel la méthode playNextRound()
            $game->playNextRound();
        }
        $game->newGame();
    }
    readline("Score final : {$game->getResult()}, entrée pour revenir au menu.");
}


/**
 * TODO: commenter
 * @param int $counter
 * @return void
 * @throws RandomException
 */
function playWithAI(int $counter=1) : void {
    //Récupération des noms de joueurs
    $p1Name = readline("Entre le nom du joueur physique (si vide, le joueur sera appelé par son id): ");
    $p2Name = readline("Entre le nom de l'ordinateur (si vide, le joueur sera appelé par son id): ");
    $userInput = menuIA();    //Récupération de l'entrée utilisateur
    echo "input $userInput" . PHP_EOL;
    $player2 = null;
    if ($userInput) {   //Si l'entrée utilisateur n'est pas null, pas de else car boucle infinie
        switch ($userInput) {
            case "1":   //Lecture des règles
                $player2 = new IAPlayer("AlphaBeta", $p2Name); //Création du joueur 2
                break;
            case "2":   //Partie simple
                $player2 = new IAPlayer("IA", $p2Name); //Création du joueur 2
                break;
        }
    }
    $player1 = new PhysicalPlayer($p1Name); //Création du joueur 1
    $game = new Game($player1, $player2); //Création d'une partie
    for ($i = 0; $i < $counter; $i++) {
        while (!$game->isDone()) {  //Tant que la partie n'est pas finie on appel la méthode playNextRound()
            $game->playNextRound();
        }
    }
    readline("Score final : {$game->getResult()}, entrée pour revenir au menu.");
}


/**
 * TODO: a commenter
 * @return void
 */
function rules(): void
{
    echo "+---------------------------------------------------------------------+\n";
    echo "|                             REGLES :                                |\n";
    echo "| Le mode Morpion 3x3 propose un affrontement stratégique en tour par |\n";
    echo "| tour entre deux joueurs. La grille de jeu est composée de 9 cases,  |\n";
    echo "| et chaque joueur doit, à son tour, sélectionner une case vide pour  |\n";
    echo "| y placer son symbole, soit un 'X' soit un 'O'. L'objectif est de    |\n";
    echo "| former une ligne de trois symboles identiques, que ce soit à        |\n";
    echo "| à l'horizontale, à la verticale ou en diagonale. Le premier joueur  |\n";
    echo "| à réaliser cet alignement remporte la partie. En cas de remplissage |\n";
    echo "| total de la grille sans alignement, le match est déclaré nul.       |\n";
    echo "+---------------------------------------------------------------------+\n";
    readline("Appuyez sur entrée pour quitter");
}


//Programme principal
while (1) { //Boucle infinie
    $userInput = menu();    //Récupération de l'entrée utilisateur
    if ($userInput) {   //Si l'entrée utilisateur n'est pas null, pas de else car boucle infinie
        switch ($userInput) {
            case "R":   //Lecture des règles
                clearScreen();
                rules();
                break;
            case "J":   //Partie simple
                clearScreen();
                play();
                break;
            case "C":   //3 Parties simples
                clearScreen();
                play(3);
                break;
            case "O":   //Partie contre l'ordinateur
                clearScreen();
                playWithAI();
                break;
            case "Q":
                die();
        }
    }
}
