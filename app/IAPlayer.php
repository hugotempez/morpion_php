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
     * @param SplFixedArray $field
     * @return void
     */
    public function play(SplFixedArray $field) : void {

    }
}