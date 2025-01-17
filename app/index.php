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
while (1) {
    function alphaBeta($board, $depth, $alpha, $beta, $isMaximizingPlayer)
    {
        // Vérifie si le jeu est terminé ou si la profondeur maximale est atteinte
        $winner = checkWinner($board);
        if ($winner !== null) {
            // Retourne une évaluation basée sur le gagnant
            return $winner === 'X' ? 10 : ($winner === 'O' ? -10 : 0);
        }
        if ($depth === 0 || isBoardFull($board)) {
            return 0; // Partie nulle ou profondeur atteinte
        }

        if ($isMaximizingPlayer) {
            $maxEval = -INF;
            foreach (getAvailableMoves($board) as $move) {
                // Simule le mouvement pour le joueur Max ('X')
                $board[$move] = 'X';
                $eval = alphaBeta($board, $depth - 1, $alpha, $beta, false);
                $board[$move] = ''; // Annule le mouvement
                $maxEval = max($maxEval, $eval);
                $alpha = max($alpha, $eval);
                if ($beta <= alpha) {
                    break; // Élagage
                }
            }
            return $maxEval;
        } else {
            $minEval = INF;
            foreach (getAvailableMoves($board) as $move) {
                // Simule le mouvement pour le joueur Min ('O')
                $board[$move] = 'O';
                $eval = alphaBeta($board, $depth - 1, $alpha, $beta, true);
                $board[$move] = ''; // Annule le mouvement
                $minEval = min($minEval, $eval);
                $beta = min($beta, $eval);
                if ($beta <= alpha) {
                    break; // Élagage
                }
            }
            return $minEval;
        }
    }

    function checkWinner($board)
    {
        // Définit les combinaisons gagnantes
        $winningLines = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], // Lignes
            [0, 3, 6], [1, 4, 7], [2, 5, 8], // Colonnes
            [0, 4, 8], [2, 4, 6]             // Diagonales
        ];

        foreach ($winningLines as $line) {
            if ($board[$line[0]] !== '' &&
                $board[$line[0]] === $board[$line[1]] &&
                $board[$line[1]] === $board[$line[2]]) {
                return $board[$line[0]]; // Retourne le gagnant ('X' ou 'O')
            }
        }

        return null; // Pas de gagnant
    }

    function isBoardFull($board): bool
    {
        foreach ($board as $cell) {
            if ($cell === '') {
                return false;
            }
        }
        return true;
    }

    function getAvailableMoves($board): array
    {
        $moves = [];
        foreach ($board as $index => $cell) {
            if ($cell === '') {
                $moves[] = $index;
            }
        }
        return $moves;
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
    // Exemple d'utilisation
    $board = [
        '', '', '', // Grille initiale vide
        '', '', '',
        '', '', ''
    ];

    // Trouver le meilleur coup pour 'X'
    $bestMove = null;
    $bestValue = -INF;
    foreach (getAvailableMoves($board) as $move) {
        $board[$move] = 'X';
        $moveValue = alphaBeta($board, 9, -INF, INF, false);
        $board[$move] = '';
        if ($moveValue > $bestValue) {
            $bestValue = $moveValue;
            $bestMove = $move;
        }
    }

    echo "Le meilleur coup pour 'X' est : " . $bestMove . PHP_EOL;

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
        menu();
    }

    function menu(): ?string
    {
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
                rules();
                break;
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


    $userInput = menu();

}
