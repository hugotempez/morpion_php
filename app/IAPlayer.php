<?php

include_once 'Player.php';

class IAPlayer extends Player
{

    public function __construct(string $name="")
    {
        parent::__construct();
        $this->id = Player::$counter;
        $this->name = $name;
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}