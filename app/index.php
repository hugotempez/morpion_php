<?php
    while (1) {
        $userInput = menu();

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
            $userChoice = readline("Votre choix : ");
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
    }
