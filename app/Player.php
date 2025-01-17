<?php

class Player
{
    protected static int $counter = 0;  //Compteur de création d'objet (défini l'id du joueur)
    protected int $id;                  //id du joueur
    protected string $name;             //nom du joueur
    protected int $winCount = 0;        //Compteur de victoire


    /**
     * Constructeur
     */
    public function __construct()
    {
        Player::$counter++;
    }


    /**
     * Destructeur, va permettre de reprendre le compte d'id à 0 lors d'une seconde partie
     */
    public function __destruct() {
        Player::$counter--;
    }


    /**
     * Incrémente le compteur de victoire
     * @return void
     */
    public function incrementWinCount() : void {
        $this->winCount++;
    }


    /**
     * Getter id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * Getter nom du joueur
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Getter compteur de victoire
     * @return int
     */
    public function getWinCount(): int
    {
        return $this->winCount;
    }
}