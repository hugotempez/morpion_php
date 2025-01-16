<?php
    while (1) {
        function rules(){
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
