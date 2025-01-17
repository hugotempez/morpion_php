<?php

include_once 'Player.php';

class PhysicalPlayer extends Player
{
    /**
     * Constructeur
     * @param string $name Le nom du joueur
     */
    public function __construct(string $name="")
    {
        parent::__construct();
        $this->id = Player::$counter;
        $this->name = $name;
    }


    /**
     * Destructeur
     */
    public function __destruct()
    {
        parent::__destruct();
    }
}