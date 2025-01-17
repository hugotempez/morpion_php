<?php

include_once 'Player.php';

//TODO: Implémenter les diverses fonctions (toutes privées) qui permettrait à l'IA de jouer
class IAPlayer extends Player
{
    private string $algorithm; //TODO le type d'algo avec lequel l'IA va jouer

    public function __construct(string $algo, string $name="")  //TODO passer le type d'algo que l'on veux en paramètre, refuser la création de l'objet si ce n'est pas le cas ou définir un algo par defaut
    {
        parent::__construct();
        $this->algorithm = $algo;
        $this->id = Player::$counter;
        $this->name = $name;
    }

    public function __destruct()
    {
        parent::__destruct();
    }


    /**
     * TODO: En fonction de l'algo choisi et de la map de morpion passé en paramètre, va décider du meilleur coup suivant (le type de retour sera trés probablement à modifier)
    /**
     * Implémentation de l'algorithme AlphaBeta pour choisir le meilleur coup.
     * @param SplFixedArray $field Grille de jeu actuelle.
     * @return int Le numéro de case (entre 1 et 9) choisi par l'IA.
     */
    public function play(SplFixedArray $field): int
    {
        $bestScore = PHP_INT_MIN;
        $bestMove = -1;

        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($field[$i][$j] === null) {
                    // Simuler le coup
                    $field[$i][$j] = $this->id;
                    $score = $this->minimax($field, 0, false, PHP_INT_MIN, PHP_INT_MAX);
                    $field[$i][$j] = null; // Annuler le coup

                    if ($score > $bestScore) {
                        $bestScore = $score;
                        $bestMove = $i * 3 + $j + 1; // Conversion des indices en un numéro de case (1-9)
                    }
                }
            }
        }

        return $bestMove;
    }

    /**
     * Algorithme Minimax avec élagage AlphaBeta.
     * @param SplFixedArray $field Grille de jeu actuelle.
     * @param int $depth Profondeur actuelle de l'arbre.
     * @param bool $isMaximizing True si l'IA maximise son score, False pour l'adversaire.
     * @param int $alpha Valeur alpha pour l'élagage.
     * @param int $beta Valeur beta pour l'élagage.
     * @return int Le score évalué pour cet état.
     */
    private function minimax(SplFixedArray $field, int $depth, bool $isMaximizing, int $alpha, int $beta): int
    {
        $winner = $this->checkWinner($field);
        if ($winner !== null) {
            return $winner === $this->id ? 10 - $depth : $depth - 10;
        }

        if ($this->isBoardFull($field)) {
            return 0; // Match nul
        }

        if ($isMaximizing) {
            $maxEval = PHP_INT_MIN;
            for ($i = 0; $i < 3; $i++) {
                for ($j = 0; $j < 3; $j++) {
                    if ($field[$i][$j] === null) {
                        $field[$i][$j] = $this->id;
                        $eval = $this->minimax($field, $depth + 1, false, $alpha, $beta);
                        $field[$i][$j] = null;
                        $maxEval = max($maxEval, $eval);
                        $alpha = max($alpha, $eval);
                        if ($beta <= $alpha) {
                            break 2;
                        }
                    }
                }
            }
            return $maxEval;
        } else {
            $minEval = PHP_INT_MAX;
            $opponentId = $this->id === 1 ? 2 : 1;
            for ($i = 0; $i < 3; $i++) {
                for ($j = 0; $j < 3; $j++) {
                    if ($field[$i][$j] === null) {
                        $field[$i][$j] = $opponentId;
                        $eval = $this->minimax($field, $depth + 1, true, $alpha, $beta);
                        $field[$i][$j] = null;
                        $minEval = min($minEval, $eval);
                        $beta = min($beta, $eval);
                        if ($beta <= $alpha) {
                            break 2;
                        }
                    }
                }
            }
            return $minEval;
        }
    }

    /**
     * Vérifie si la grille est pleine.
     * @param SplFixedArray $field Grille de jeu.
     * @return bool True si la grille est pleine, False sinon.
     */
    private function isBoardFull(SplFixedArray $field): bool
    {
        foreach ($field as $row) {
            foreach ($row as $cell) {
                if ($cell === null) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Vérifie le gagnant actuel.
     * @param SplFixedArray $field Grille de jeu.
     * @return int|null L'id du joueur gagnant, ou null si aucun gagnant.
     */
    private function checkWinner(SplFixedArray $field): ?int
    {
        for ($i = 0; $i < 3; $i++) {
            // Vérifier les lignes
            if ($field[$i][0] !== null && $field[$i][0] === $field[$i][1] && $field[$i][0] === $field[$i][2]) {
                return $field[$i][0];
            }
            // Vérifier les colonnes
            if ($field[0][$i] !== null && $field[0][$i] === $field[1][$i] && $field[0][$i] === $field[2][$i]) {
                return $field[0][$i];
            }
        }
        // Vérifier les diagonales
        if ($field[0][0] !== null && $field[0][0] === $field[1][1] && $field[0][0] === $field[2][2]) {
            return $field[0][0];
        }
        if ($field[0][2] !== null && $field[0][2] === $field[1][1] && $field[0][2] === $field[2][0]) {
            return $field[0][2];
        }

        return null;
    }
}