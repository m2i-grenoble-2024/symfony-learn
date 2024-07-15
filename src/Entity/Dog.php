<?php

namespace App\Entity;
use \Datetime;

class Dog {
    private ?int $id;
    private string $name;
    private string $breed;
    private DateTime $birthdate;

    public function getId(): ?int {
        return $this->id;
    }    
    public function getName(): string {
        return $this->name;
    }  
    public function getBreed(): string {
        return $this->breed;
    }  
    public function getBirthdate(): DateTime {
        return $this->birthdate;
    }      
    public function setId(?int $id){
        $this->id = $id;
    }    
    public function setName( string $name){
        $this->name = $name;
    }  
    public function setBreed( string $breed){
        $this->breed = $breed;
    }  
    public function setBirthdate( DateTime $birthdate){
        $this->birthdate = $birthdate;
    }    
}