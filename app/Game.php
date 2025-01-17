<?php

include_once "Player.php";

class Game {
    private static array $gameMap = [   //Tableau associatif qui associe un int à des coordonnées dans la carte du morpion
        1 => [0, 0], 2 => [0, 1], 3 => [0, 2],
        4 => [1, 0], 5 => [1, 1], 6 => [1, 2],
        7 => [2, 0], 8 => [2, 1], 9 => [2, 2]
    ];
    private SplFixedArray $field;   //Carte du morpion
    private SplFixedArray $players; //Tableau des joueurs
    private Player $currentPlayer;  //Joueur actuel
    private int $roundCount = 0;    //Nombre de tour joué depuis le debut de la partie
    private bool $isDone = false;   //Partie terminé ou non


    /**
     * Constructeur
     * @param Player $player1 Joueur 1
     * @param Player $player2 Joueur 2
     */
    public function __construct(Player $player1, Player $player2) {
        $this->initField(); //Initialisation de la carte de jeu
        $this->players = SplFixedArray::fromArray([$player1, $player2]);    //Assignation des joueurs dans le tableau dédié
        $this->setFirstPlayer();    //Choix du premier joueur au hasard
    }


    /**
     * Nettoie la console
     * @return void
     */
    private function clearScreen() : void {
        popen("cls", "w");
    }


    /**
     * Initialise la carte de jeu
     * @return void
     */
    private function initField() : void {
        $this->field = new SplFixedArray(3);    //Tableau de taille fixe de longueur 3
        for ($i = 0; $i < 3; $i++) {
            $this->field[$i] = new SplFixedArray(3);    //Tableau de taille fixe de longueur 3 assigné à chaque index du tableau field pour créer un tableau 2D
        }
    }


    /**
     * Désignation aléatoire du joueur qui ouvre la partie
     * @return void
     */
    private function setFirstPlayer() : void {
        $this->currentPlayer = $this->players[rand(0, count($this->players) - 1)];
    }


    /**
     * Ecrit l'id du joueur dans la carte de jeu aux coordonées selectionnées par le joueur
     * @param int $userInput Les coordonées selectionnées par le joueur
     * @return bool Si l'écriture dans la carte de jeu s'est déroulé corréctement (faux si la case est deja prise)
     */
    private function writeToField(int $userInput) : bool {
        $indexes = Game::$gameMap[$userInput];
        if ($this->field[$indexes[0]][$indexes[1]] === null) {  //Si la case est vide
            $this->field[$indexes[0]][$indexes[1]] = $this->currentPlayer->getId();
            return true;
        } else {
            return false;
        }
    }


    /**
     * Jouer le prochain tour
     * @param string $message Potentiel message d'erreur à afficher
     * @return void
     */
    public function playNextRound(string $message="") : void {
        $this->clearScreen();   //Nettoie la console
        if ($message !== "") {
            echo "$message" . PHP_EOL;
        }
        $playerName = ($this->currentPlayer->getName() !== "") ? $this->currentPlayer->getName() :
            "Joueur " . $this->currentPlayer->getId();  //Si le nom du joueur est vide, le joueur sera appelé par son id
        $this->printField();    //Ecrit la map dans la console
        $input = readline("A $playerName de jouer! Entrez votre coup suivant (1 à 9): ");   //Choix de l'utilisateur
        try {
            $input = (int)$input;   //Si la conversion en int est réussie l'éxecution du programme continue, sinon on stop le programme
        } catch (Exception $e) {
            die($e->getMessage());
        }
        if ($input >= 1 && $input <= 9) {   //Si l'input est valide, donc entre 1 et 9 compris
            if ($this->writeToField($input)) {  //Si l'input de l'utilisateur a été correctement écrit dans la map (donc que la case était vide)
                $this->roundCount++;    //Incrémentation du compteur de round
                if ($this->checkGameStatus()) { //Si la partie est finie
                    $this->clearScreen();   //Nettoie la console
                    $this->isDone = true;   //La partie est terminée
                    $this->printField();    //Ecriture définitive de la carte du morpion, vu que la partie est terminé
                    readline("Victoire de $playerName ({$this->currentPlayer->getId()})!" . PHP_EOL);
                } else {    //Si la partie n'est pas finie
                    echo "Au joueur suivant" . PHP_EOL;
                    $this->nextPlayer();    //Switch du joueur en cours
                }
            } else {    //Si l'input de l'utilisateur a deja été joué
                $this->playNextRound("La case $input a deja été joué. "); //Appel récursif à cette fonction
            }
        } else {    //Si l'input de l'utilisateur est invalide
            $this->playNextRound("$input est invalide. "); //Appel récursif à cette fonction
        }
    }


    /**
     * Changement de joueur
     * @return void
     */
    private function nextPlayer() : void {
        $index = array_search($this->currentPlayer, (array)$this->players);
        $this->currentPlayer = ($index === 0) ? $this->players[$index + 1] : $this->players[$index - 1];
    }


    /**
     * Ecriture de la carte de jeu dans la console
     * @return void
     */
    public function printField() : void {
        echo "  -   -   -" . PHP_EOL;
        foreach ($this->field as $column) {
            echo "|";
            foreach ($column as $line) {
                if ($line !== null) {
                    echo " $line |";
                } else {
                    echo "   |";
                }
            }
            echo PHP_EOL . "  -   -   -" . PHP_EOL;
        }
    }


    /**
     * Vérification des conditions de victoire
     * @return bool Si la partie est gagnée ou non
     */
    public function checkGameStatus() : bool {
        if ($this->roundCount >= 5) {   //Pour des raisons d'optimisation, pas de check avant le 5eme tour car aucune possibilité de victoire
            $count = count($this->field->toArray());
            for ($i = 0; $i < $count; $i++) {   //Vérification des lignes
                if ($this->field[$i][0] === $this->field[$i][1] && $this->field[$i][0] === $this->field[$i][2]) {
                    return true;
                }
            }

            for ($i = 0; $i < $count; $i++) {   //Vérifications des colonnes
                if ($this->field[0][$i] === $this->field[1][$i] && $this->field[0][$i] === $this->field[2][$i]) {
                    return true;
                }
            }

            if ($this->field[0][0] === $this->field[1][1] && $this->field[0][0] === $this->field[2][2]) {   //Vérification diagonale 1
                return true;
            }

            if ($this->field[0][2] === $this->field[1][1] && $this->field[0][2] === $this->field[2][0]) {   //Vérification diagonale 2
                return true;
            }

        }
        return false;
    }


    private function destructPlayers() : void {

    }


    /**
     * Getter attribut isDone
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->isDone;
    }
}