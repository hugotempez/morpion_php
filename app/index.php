<?php

include_once "Game.php";
include_once "Player.php";


/**
 * Nettoie la console
 * @return void
 */
function clearScreen() : void {
    popen("cls", "w");
}


/**
 * Menu principal
 * @return string|null Le choix de l'utilisateur ou null en cas d'erreur
 */
function menu() : ?string {
    clearScreen();
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
            menu();
    }
    return null;
}


/**
 * Démarre le nombre de partie passé en paramètre
 * @param int $counter Nombre de parties à jouer
 * @return void
 */
function play(int $counter=1) : void
{
    //Récupération des noms de joueurs
    $p1Name = readline("Entre le nom du joueur 1 (si vide, le joueur sera appelé par son id): ");
    $p2Name = readline("Entre le nom du joueur 2 (si vide, le joueur sera appelé par son id): ");
    $player1 = new Player($p1Name); //Création du joueur 1
    $player2 = new Player($p2Name); //Création du joueur 2
    $game = new Game($player1, $player2); //Création d'une partie
    for ($i = 0; $i < $counter; $i++) {
        while (!$game->isDone()) {  //Tant que la partie n'est pas finie on appel la méthode playNextRound()
            $game->playNextRound();
        }
    }
}

while (1) { //Boucle infinie
    $userInput = menu();    //Récupération de l'entrée utilisateur
    if ($userInput) {   //Si l'entrée utilisateur n'est pas null, pas de else car boucle infinie
        switch ($userInput) {
            case "R":   //Lecture des règles
                break;
            case "J":   //Partie simple
                clearScreen();
                play();
            case "C":   //3 Parties simples
                clearScreen();
                play(3);
            case "O":
                break;
            case "Q":
                break;
            default:
                break;
        }
    }
}
