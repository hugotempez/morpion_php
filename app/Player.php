<?php

class Player
{
    static int $counter = 0;    //Compteur de création d'objet (défini l'id du joueur)
    private int $id;    //id du joueur
    private string $name;   //nom du joueur


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
}