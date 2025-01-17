<?php

include_once 'Player.php';

class IAPlayer extends Player
{
    private string $algorithm;

    public function __construct(string $algo = "alphabeta", string $name = "")
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
     * Implémentation de la logique IA basée sur l'algorithme choisi.
     * @param SplFixedArray $field Grille de jeu actuelle.
     * @return int Le numéro de case (entre 1 et 9) choisi par l'IA.
     */
    public function play(SplFixedArray $field): int
    {

        // Vérifier si le tableau est plein avant d'essayer de jouer
        if ($this->isBoardFull($field)) {
            throw new RuntimeException("Le tableau est plein, aucun mouvement possible.");
        }

        switch ($this->algorithm) {
            case "AlphaBeta":
                return $this->playAlphaBeta($field);
            case "IA":
                return $this->playAnalytic($field);
            default:
                throw new RuntimeException("Algorithme inconnu : {$this->algorithm}");
        }
    }

    /**
     * Implémentation de l'algorithme AlphaBeta pour choisir le meilleur coup.
     * @param SplFixedArray $field Grille de jeu actuelle.
     * @return int Le numéro de case choisi par AlphaBeta.
     */
    private function playAlphaBeta(SplFixedArray $field): int
    {
        $bestScore = PHP_INT_MIN;
        $bestMove = null;

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

        // Retourner le meilleur coup trouvé ou une exception si aucun coup valide
        if ($bestMove === null) {
            throw new RuntimeException("Aucun coup valide trouvé.");
        }

        return $bestMove;
    }

    /**
     * Implémentation de la stratégie analytique pour choisir un coup.
     * @param SplFixedArray $field Grille de jeu actuelle.
     * @return int Le numéro de case choisi par l'analyse.
     */
    private function playAnalytic(SplFixedArray $field): int
    {
        // Étape 1 : Vérifier si l'IA peut gagner immédiatement
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($field[$i][$j] === null) {
                    $field[$i][$j] = $this->id;
                    if ($this->checkWinner($field) === $this->id) {
                        $field[$i][$j] = null;
                        return $i * 3 + $j + 1;
                    }
                    $field[$i][$j] = null;
                }
            }
        }

        // Étape 2 : Bloquer une victoire imminente de l'adversaire
        $opponentId = $this->id === 1 ? 2 : 1;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($field[$i][$j] === null) {
                    $field[$i][$j] = $opponentId;
                    if ($this->checkWinner($field) === $opponentId) {
                        $field[$i][$j] = null;
                        return $i * 3 + $j + 1;
                    }
                    $field[$i][$j] = null;
                }
            }
        }

        // Étape 3 : Jouer au centre si disponible
        if ($field[1][1] === null) {
            return 5;
        }

        // Étape 4 : Jouer dans un coin si disponible
        $corners = [
            [0, 0], [0, 2], [2, 0], [2, 2]
        ];
        foreach ($corners as [$i, $j]) {
            if ($field[$i][$j] === null) {
                return $i * 3 + $j + 1;
            }
        }

        // Étape 5 : Jouer aléatoirement si aucune autre stratégie n'est applicable
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($field[$i][$j] === null) {
                    return $i * 3 + $j + 1;
                }
            }
        }

        throw new RuntimeException("Aucun coup valide trouvé.");
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
            return $winner === 0 ? 0 : ($winner === $this->id ? 10 - $depth : $depth - 10);
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
     * Vérifie si le tableau est plein.
     * @param SplFixedArray $field Grille de jeu.
     * @return bool True si le tableau est plein, False sinon.
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
     * Vérifie le gagnant actuel ou une égalité.
     * @param SplFixedArray $field Grille de jeu.
     * @return int|null L'id du joueur gagnant, 0 pour une égalité, ou null si la partie continue.
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

        // Vérifier si toutes les cases sont pleines pour une égalité
        foreach ($field as $row) {
            foreach ($row as $cell) {
                if ($cell === null) {
                    return null; // La partie continue
                }
            }
        }

        return 0; // Égalité
    }
}
