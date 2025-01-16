<?php

class Player
{
    static int $counter = 0;
    private int $id;
    private string $name;
    private bool $isPlaying;
    private bool $isWinner;

    public function __construct(string $name="")
    {
        Player::$counter++;
        $this->id = Player::$counter;
        $this->name = $name;
        $this->isPlaying = false;
        $this->isWinner = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}