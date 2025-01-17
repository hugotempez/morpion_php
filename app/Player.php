<?php

class Player
{
    static int $counter = 0;    //Compteur de création d'objet (défini l'id du joueur)
    private int $id;    //id du joueur
    private string $name;   //nom du joueur
    private int $winCount = 0;  //Compteur de victoire


    /**
     * Constructeur
     * @param string $name Le nom du joueur
     */
    public function __construct(string $name="")
    {
        Player::$counter++;
        $this->id = Player::$counter;
        $this->name = $name;
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